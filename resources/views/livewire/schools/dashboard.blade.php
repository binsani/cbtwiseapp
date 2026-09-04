<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- Header Banner --}}
        <div class="bg-gradient-to-r from-emerald-600 to-teal-700 rounded-3xl p-6 sm:p-8 text-white shadow-lg mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <span class="inline-flex px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-white/20 text-white mb-2">
                    {{ strtoupper($school->tier) }} {{ __('PORTAL') }}
                </span>
                <h1 class="text-3xl font-black">{{ $school->name }}</h1>
                <p class="text-sm text-emerald-100 mt-1">
                    {{ __('Portal:') }} <code class="bg-white/10 px-2 py-0.5 rounded font-bold">{{ $school->subdomain }}.cbtwise.com</code> 
                    • {{ __('Seats:') }} {{ $school->seats_used }} / {{ $school->seat_limit }}
                    @if($school->expires_at)
                        • {{ __('Expires:') }} {{ $school->expires_at->format('M d, Y') }} ({{ $school->expires_at->diffForHumans() }})
                    @endif
                </p>
            </div>
            
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('schools.assignment.create', ['slug' => $school->slug]) }}" class="px-5 py-3 bg-white text-emerald-700 hover:bg-emerald-50 text-sm font-extrabold rounded-2xl transition-all shadow-md">
                    📝 {{ __('Create Assignment') }}
                </a>
            </div>
        </div>

        {{-- Tabs Selector --}}
        <div class="flex border-b border-slate-100 mb-8 overflow-x-auto whitespace-nowrap">
            <button 
                wire:click="$set('activeTab', 'roster')"
                class="px-6 py-3 border-b-2 text-sm font-black transition-colors {{ $activeTab === 'roster' ? 'border-emerald-600 text-emerald-700' : 'border-transparent text-slate-400 hover:text-slate-600' }}"
            >
                👥 {{ __('Roster & Invites') }}
            </button>
            <button 
                wire:click="$set('activeTab', 'assignments')"
                class="px-6 py-3 border-b-2 text-sm font-black transition-colors {{ $activeTab === 'assignments' ? 'border-emerald-600 text-emerald-700' : 'border-transparent text-slate-400 hover:text-slate-600' }}"
            >
                📝 {{ __('Assignments') }}
            </button>
            <button 
                wire:click="$set('activeTab', 'results')"
                class="px-6 py-3 border-b-2 text-sm font-black transition-colors {{ $activeTab === 'results' ? 'border-emerald-600 text-emerald-700' : 'border-transparent text-slate-400 hover:text-slate-600' }}"
            >
                📊 {{ __('Results & Reports') }}
            </button>
        </div>

        {{-- Tab Content --}}
        <div>
            @if ($activeTab === 'roster')
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    {{-- Roster List --}}
                    <div class="lg:col-span-2 space-y-6">
                        
                        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6">
                                <h3 class="text-lg font-black text-slate-900">{{ __('Roster') }}</h3>
                                <input 
                                    type="text" 
                                    wire:model.live="memberSearch" 
                                    placeholder="Search members..."
                                    class="w-full sm:w-64 bg-slate-50 border border-slate-100 rounded-xl px-4 py-2 text-xs text-slate-700 font-semibold focus:outline-none"
                                >
                            </div>

                            @if(session()->has('roster_error'))
                                <div class="mb-4 p-3 bg-rose-50 border border-rose-100 text-rose-700 rounded-xl text-xs font-semibold">
                                    {{ session('roster_error') }}
                                </div>
                            @endif

                            @if(session()->has('roster_success'))
                                <div class="mb-4 p-3 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-xl text-xs font-semibold">
                                    {{ session('roster_success') }}
                                </div>
                            @endif

                            @if($members->isEmpty())
                                <div class="text-center py-10 text-slate-400 text-sm">
                                    {{ __('No school members matching search.') }}
                                </div>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left border-collapse">
                                        <thead>
                                            <tr class="border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase">
                                                <th class="pb-3">{{ __('Name / Email') }}</th>
                                                <th class="pb-3">{{ __('Role') }}</th>
                                                <th class="pb-3">{{ __('Joined') }}</th>
                                                <th class="pb-3 text-right">{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-sm font-medium text-slate-700 divide-y divide-slate-50">
                                            @foreach($members as $m)
                                                <tr>
                                                    <td class="py-3">
                                                        <span class="font-extrabold text-slate-900 block">{{ $m->user->name }}</span>
                                                        <span class="text-xs text-slate-400 block">{{ $m->user->email }}</span>
                                                    </td>
                                                    <td class="py-3">
                                                        <span class="inline-flex px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-wider
                                                            {{ $m->role === 'admin' ? 'bg-emerald-50 text-emerald-700' : '' }}
                                                            {{ $m->role === 'teacher' ? 'bg-blue-50 text-blue-700' : '' }}
                                                            {{ $m->role === 'student' ? 'bg-slate-100 text-slate-600' : '' }}
                                                        ">
                                                            {{ $m->role }}
                                                        </span>
                                                    </td>
                                                    <td class="py-3 text-xs text-slate-400">
                                                        {{ $m->joined_at ? $m->joined_at->format('M d, Y') : '-' }}
                                                    </td>
                                                    <td class="py-3 text-right">
                                                        @if($m->user_id !== $school->owner_id)
                                                            <button 
                                                                wire:click="removeMember({{ $m->id }})"
                                                                class="text-rose-600 hover:text-rose-700 text-xs font-bold"
                                                            >
                                                                {{ __('Remove') }}
                                                            </button>
                                                        @else
                                                            <span class="text-xs text-slate-400 italic">{{ __('Owner') }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-4">
                                    {{ $members->links() }}
                                </div>
                            @endif
                        </div>

                    </div>

                    {{-- Invites and Import Sidebar --}}
                    <div class="space-y-6">
                        
                        {{-- Invite Form --}}
                        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                            <h3 class="text-base font-black text-slate-900 mb-4">{{ __('Invite Member') }}</h3>

                            @if(session()->has('invite_success'))
                                <div class="mb-4 p-3 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-xl text-xs font-semibold break-words">
                                    {{ session('invite_success') }}
                                </div>
                            @endif

                            @if(session()->has('invite_error'))
                                <div class="mb-4 p-3 bg-rose-50 border border-rose-100 text-rose-700 rounded-xl text-xs font-semibold">
                                    {{ session('invite_error') }}
                                </div>
                            @endif

                            <form wire:submit.prevent="inviteMember" class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">{{ __('Email Address') }}</label>
                                    <input 
                                        type="email" 
                                        wire:model="inviteEmail" 
                                        placeholder="e.g. student@gmail.com"
                                        class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-xs text-slate-700 font-semibold focus:outline-none"
                                    >
                                    @error('inviteEmail') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">{{ __('Role') }}</label>
                                    <select 
                                        wire:model="inviteRole" 
                                        class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-xs text-slate-700 font-semibold focus:outline-none"
                                    >
                                        <option value="student">{{ __('Student') }}</option>
                                        <option value="teacher">{{ __('Teacher') }}</option>
                                    </select>
                                    @error('inviteRole') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <button type="submit" class="w-full py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-xs rounded-xl transition-all shadow-md">
                                    ✉️ {{ __('Send Invitation') }}
                                </button>
                            </form>
                        </div>

                        {{-- Bulk CSV Import --}}
                        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                            <h3 class="text-base font-black text-slate-900 mb-1">{{ __('Bulk CSV Import') }}</h3>
                            <p class="text-[10px] text-slate-400 leading-relaxed mb-4">
                                {{ __('Upload a CSV file containing columns named "name" and "email" to add student accounts in bulk.') }}
                            </p>

                            @if(session()->has('csv_success'))
                                <div class="mb-4 p-3 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-xl text-xs font-semibold">
                                    {{ session('csv_success') }}
                                </div>
                            @endif

                            @if(session()->has('csv_error'))
                                <div class="mb-4 p-3 bg-rose-50 border border-rose-100 text-rose-700 rounded-xl text-xs font-semibold">
                                    {{ session('csv_error') }}
                                </div>
                            @endif

                            <form wire:submit.prevent="importCsv" class="space-y-4">
                                <div 
                                    x-data="{ isUploading: false, progress: 0 }"
                                    x-on:livewire-upload-start="isUploading = true"
                                    x-on:livewire-upload-finish="isUploading = false"
                                    x-on:livewire-upload-error="isUploading = false"
                                    x-on:livewire-upload-progress="progress = $event.detail.progress"
                                >
                                    <input 
                                        type="file" 
                                        wire:model="csvFile" 
                                        class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-slate-50 file:text-slate-700 hover:file:bg-slate-100 cursor-pointer"
                                    >
                                    
                                    {{-- Progress Bar --}}
                                    <div x-show="isUploading" class="mt-2 w-full bg-slate-100 rounded-full h-1">
                                        <div class="bg-emerald-600 h-1 rounded-full" x-bind:style="'width: ' + progress + '%'"></div>
                                    </div>
                                    
                                    @error('csvFile') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl transition-all shadow-md">
                                    📤 {{ __('Upload CSV') }}
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            @endif

            @if ($activeTab === 'assignments')
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-black text-slate-900">{{ __('Class Assignments') }}</h3>
                        <a href="{{ route('schools.assignment.create', ['slug' => $school->slug]) }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold rounded-xl transition-all shadow-sm">
                            + {{ __('New Assignment') }}
                        </a>
                    </div>

                    @if($assignments->isEmpty())
                        <div class="text-center py-12 text-slate-400 text-sm">
                            {{ __('No assignments created yet.') }}
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase">
                                        <th class="pb-3">{{ __('Assignment Title') }}</th>
                                        <th class="pb-3">{{ __('Subject / Exam') }}</th>
                                        <th class="pb-3">{{ __('Duration') }}</th>
                                        <th class="pb-3">{{ __('Active Dates') }}</th>
                                        <th class="pb-3 text-right">{{ __('Reports') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm font-medium text-slate-700 divide-y divide-slate-50">
                                    @foreach($assignments as $assign)
                                        <tr>
                                            <td class="py-4">
                                                <span class="font-extrabold text-slate-900 block">{{ $assign->title }}</span>
                                                <span class="text-xs text-slate-400 block mt-0.5">{{ $assign->question_count }} questions</span>
                                            </td>
                                            <td class="py-4">
                                                <span class="text-slate-800 font-bold block">{{ $assign->subject?->name ?: __('All Subjects') }}</span>
                                                <span class="text-[10px] text-slate-400 block uppercase tracking-wider font-extrabold">{{ $assign->exam?->name }}</span>
                                            </td>
                                            <td class="py-4 font-semibold text-slate-600">
                                                {{ $assign->time_limit_mins }} {{ __('mins') }}
                                            </td>
                                            <td class="py-4 text-xs text-slate-500">
                                                @if($assign->start_at || $assign->end_at)
                                                    {{ $assign->start_at ? $assign->start_at->format('M d') : 'Open' }} - 
                                                    {{ $assign->end_at ? $assign->end_at->format('M d, Y') : 'Ongoing' }}
                                                @else
                                                    {{ __('Always Open') }}
                                                @endif
                                            </td>
                                            <td class="py-4 text-right">
                                                <button 
                                                    wire:click="downloadPdfReport({{ $assign->id }})"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-rose-50 text-rose-700 hover:bg-rose-100 text-xs font-black rounded-lg transition-all"
                                                >
                                                    📄 {{ __('PDF Report') }}
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $assignments->links() }}
                        </div>
                    @endif
                </div>
            @endif

            @if ($activeTab === 'results')
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                    <h3 class="text-lg font-black text-slate-900 mb-6">{{ __('Student Submissions') }}</h3>

                    @if($results->isEmpty())
                        <div class="text-center py-12 text-slate-400 text-sm">
                            {{ __('No assignment results recorded yet.') }}
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase">
                                        <th class="pb-3">{{ __('Student') }}</th>
                                        <th class="pb-3">{{ __('Assignment') }}</th>
                                        <th class="pb-3">{{ __('Score') }}</th>
                                        <th class="pb-3">{{ __('Time Taken') }}</th>
                                        <th class="pb-3">{{ __('Submitted') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm font-medium text-slate-700 divide-y divide-slate-50">
                                    @foreach($results as $res)
                                        <tr>
                                            <td class="py-4">
                                                <span class="font-extrabold text-slate-900 block">{{ $res->user->name }}</span>
                                                <span class="text-xs text-slate-400 block">{{ $res->user->email }}</span>
                                            </td>
                                            <td class="py-4 font-semibold text-slate-700">
                                                {{ $res->assignment->title }}
                                            </td>
                                            <td class="py-4">
                                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-extrabold
                                                    {{ $res->score >= 70 ? 'bg-emerald-50 text-emerald-800' : '' }}
                                                    {{ $res->score >= 50 && $res->score < 70 ? 'bg-amber-50 text-amber-800' : '' }}
                                                    {{ $res->score < 50 ? 'bg-rose-50 text-rose-800' : '' }}
                                                ">
                                                    {{ $res->score }}% ({{ $res->correct_count }}/{{ $res->total_questions }})
                                                </span>
                                            </td>
                                            <td class="py-4 text-slate-500 font-semibold">
                                                {{ $res->time_taken_secs ? gmdate('H:i:s', $res->time_taken_secs) : '-' }}
                                            </td>
                                            <td class="py-4 text-xs text-slate-400">
                                                {{ $res->submitted_at ? $res->submitted_at->format('M d, Y H:i') : '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $results->links() }}
                        </div>
                    @endif
                </div>
            @endif
        </div>

    </div>
</div>
