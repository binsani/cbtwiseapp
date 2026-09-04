<?php

namespace App\Livewire\Schools;

use App\Models\School;
use App\Models\SchoolMember;
use App\Models\SchoolInvite;
use App\Models\SchoolAssignment;
use App\Models\SchoolResult;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use League\Csv\Reader;
use Barryvdh\DomPDF\Facade\Pdf;

class Dashboard extends Component
{
    use WithPagination, WithFileUploads;

    public $school;
    public $activeTab = 'roster'; // roster, assignments, results

    // Invite fields
    public $inviteEmail;
    public $inviteRole = 'student';

    // Bulk CSV import
    public $csvFile;

    // Search filters
    public $memberSearch = '';

    protected $rules = [
        'inviteEmail' => 'required|email',
        'inviteRole' => 'required|in:teacher,student',
    ];

    public function mount(string $slug)
    {
        $user = Auth::user();
        $this->school = School::where('slug', $slug)->firstOrFail();

        // Check if user is a member of this school
        $member = SchoolMember::where('school_id', $this->school->id)
            ->where('user_id', $user->id)
            ->first();

        if (!$member || ($member->role !== 'admin' && $member->role !== 'teacher')) {
            abort(403, 'Unauthorized. Only school admins and teachers can access this dashboard.');
        }
    }

    public function inviteMember()
    {
        $this->validate();

        if (!$this->school->hasAvailableSeats() && $this->inviteRole === 'student') {
            session()->flash('invite_error', 'Your school seat limit has been reached. Please upgrade to add more students.');
            return;
        }

        // Check if already registered/invited
        $existingUser = User::where('email', $this->inviteEmail)->first();
        if ($existingUser) {
            $isMember = SchoolMember::where('school_id', $this->school->id)
                ->where('user_id', $existingUser->id)
                ->exists();

            if ($isMember) {
                session()->flash('invite_error', 'This user is already a member of your school.');
                return;
            }
        }

        $token = Str::random(40);
        SchoolInvite::create([
            'school_id' => $this->school->id,
            'email' => strtolower($this->inviteEmail),
            'token' => $token,
            'role' => $this->inviteRole,
            'expires_at' => now()->addDays(7),
        ]);

        // Send Email Invitation
        // In a real application, you would send an email. For demo/compliance, we flash link.
        $inviteLink = route('schools.invite.accept', ['token' => $token]);
        session()->flash('invite_success', "Invitation sent to {$this->inviteEmail}! Magic accept link: " . $inviteLink);
        
        $this->inviteEmail = '';
    }

    public function importCsv()
    {
        $this->validate([
            'csvFile' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        if (!$this->csvFile) {
            return;
        }

        try {
            $csv = Reader::createFromPath($this->csvFile->getRealPath(), 'r');
            $csv->setHeaderOffset(0);
            
            $importedCount = 0;
            $errorCount = 0;

            foreach ($csv->getRecords() as $record) {
                $email = isset($record['email']) ? trim($record['email']) : null;
                $name = isset($record['name']) ? trim($record['name']) : null;

                if (!$email || !$name) {
                    $errorCount++;
                    continue;
                }

                if (!$this->school->hasAvailableSeats()) {
                    session()->flash('csv_error', 'Seat limit reached during import. Imported ' . $importedCount . ' students.');
                    $this->csvFile = null;
                    return;
                }

                // Check if user already exists
                $user = User::where('email', $email)->first();
                if (!$user) {
                    $user = User::create([
                        'name' => $name,
                        'email' => strtolower($email),
                        'password' => Hash::make(Str::random(12)),
                        'plan' => 'free', // B2B seats managed by school subscription
                    ]);
                }

                // Check if member already
                $isMember = SchoolMember::where('school_id', $this->school->id)
                    ->where('user_id', $user->id)
                    ->exists();

                if (!$isMember) {
                    SchoolMember::create([
                        'school_id' => $this->school->id,
                        'user_id' => $user->id,
                        'role' => 'student',
                        'joined_at' => now(),
                    ]);

                    $this->school->increment('seats_used');
                    $importedCount++;
                }
            }

            session()->flash('csv_success', "Successfully imported {$importedCount} students. Errors/Skips: {$errorCount}.");
        } catch (\Exception $e) {
            session()->flash('csv_error', 'Error parsing CSV file: ' . $e->getMessage());
        }

        $this->csvFile = null;
    }

    public function removeMember($memberId)
    {
        $member = SchoolMember::findOrFail($memberId);
        
        // Prevent deleting the owner/admin
        if ($member->user_id === $this->school->owner_id) {
            session()->flash('roster_error', 'The school owner cannot be removed.');
            return;
        }

        $member->delete();
        $this->school->decrement('seats_used');
        session()->flash('roster_success', 'Member removed from school roster.');
    }

    public function downloadPdfReport($assignmentId)
    {
        $assignment = SchoolAssignment::with(['exam', 'subject', 'results.user'])->findOrFail($assignmentId);
        
        $data = [
            'school' => $this->school,
            'assignment' => $assignment,
            'results' => $assignment->results()->with('user')->orderByDesc('score')->get(),
        ];

        // Generate PDF
        $pdf = Pdf::loadView('pdf.assignment_report', $data);
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, "assignment_report_{$assignment->id}.pdf");
    }

    public function render()
    {
        // Roster Tab Query
        $rosterQuery = SchoolMember::where('school_id', $this->school->id)
            ->with('user')
            ->latest();

        if ($this->memberSearch) {
            $rosterQuery->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->memberSearch . '%')
                  ->orWhere('email', 'like', '%' . $this->memberSearch . '%');
            });
        }

        $members = $rosterQuery->paginate(10, ['*'], 'rosterPage');

        // Assignments Tab Query
        $assignments = SchoolAssignment::where('school_id', $this->school->id)
            ->with(['exam', 'subject'])
            ->latest()
            ->paginate(10, ['*'], 'assignmentsPage');

        // Results Tab Query
        $results = SchoolResult::whereHas('assignment', function ($q) {
                $q->where('school_id', $this->school->id);
            })
            ->with(['assignment', 'user'])
            ->latest()
            ->paginate(15, ['*'], 'resultsPage');

        return view('livewire.schools.dashboard', [
            'members' => $members,
            'assignments' => $assignments,
            'results' => $results,
        ])->layout('layouts.app');
    }
}
