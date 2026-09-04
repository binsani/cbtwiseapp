<?php

namespace App\Livewire\Schools;

use App\Models\School;
use App\Models\SchoolMember;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class Register extends Component
{
    public $name;
    public $subdomain;
    public $contact_email;
    public $contact_phone;
    public $state;
    public $address;
    public $tier = 'starter';

    protected $rules = [
        'name' => 'required|string|max:150|min:3',
        'subdomain' => 'required|string|alpha_dash|unique:schools,subdomain|max:50|min:3',
        'contact_email' => 'required|email|max:150',
        'contact_phone' => 'nullable|string|max:20',
        'state' => 'required|string|max:50',
        'address' => 'nullable|string|max:250',
        'tier' => 'required|in:starter,growth,pro,enterprise',
    ];

    public function mount()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to create a school.');
        }

        // Fill default email/phone from user profile
        $user = Auth::user();
        $this->contact_email = $user->email;
        $this->contact_phone = $user->phone;
    }

    public function updatedName()
    {
        if (empty($this->subdomain)) {
            $this->subdomain = Str::slug($this->name);
        }
    }

    public function register()
    {
        $this->validate();

        $user = Auth::user();

        // Check if user already owns a school
        $existing = School::where('owner_id', $user->id)->first();
        if ($existing) {
            session()->flash('error', 'You already own a registered school: ' . $existing->name);
            return;
        }

        $slug = Str::slug($this->name) . '-' . rand(100, 999);
        $seatLimit = config("cbtwise_phase5.school_tier_seats.{$this->tier}", 50);

        // For starter/growth/pro tiers, we start them with a 14-day free trial
        $school = School::create([
            'name' => $this->name,
            'slug' => $slug,
            'subdomain' => strtolower($this->subdomain),
            'tier' => $this->tier,
            'seat_limit' => $seatLimit,
            'seats_used' => 1, // The owner is automatically the first user/admin
            'owner_id' => $user->id,
            'status' => 'active', // Trial is active
            'contact_email' => $this->contact_email,
            'contact_phone' => $this->contact_phone,
            'state' => $this->state,
            'address' => $this->address,
            'expires_at' => now()->addDays(14), // 14 days trial
        ]);

        // Add owner as admin member
        SchoolMember::create([
            'school_id' => $school->id,
            'user_id' => $user->id,
            'role' => 'admin',
            'joined_at' => now(),
        ]);

        session()->flash('success', "Success! Your school portal has been created. Enjoy your 14-day trial.");
        return redirect()->route('schools.dashboard', ['slug' => $school->slug]);
    }

    public function render()
    {
        return view('livewire.schools.register')->layout('layouts.app');
    }
}
