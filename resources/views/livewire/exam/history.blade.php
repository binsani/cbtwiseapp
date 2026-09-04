<div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight font-heading">
            Practice Session History
        </h1>
        <p class="text-sm text-slate-600 mt-1">Review your scores, detailed explanations, or retake previous practice exams.</p>
    </div>

    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-700 rounded-r-xl text-sm font-semibold shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white shadow-xl rounded-3xl border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Exam</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Mode</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Score</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Completed At</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse ($sessions as $sess)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-900">
                                {{ $sess->exam->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 capitalize">
                                {{ $sess->mode }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-black 
                                    @if ($sess->score >= 70) bg-emerald-50 text-emerald-700 
                                    @elseif ($sess->score >= 50) bg-amber-50 text-amber-700 
                                    @else bg-rose-50 text-rose-700 @endif">
                                    {{ $sess->score }}%
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                {{ $sess->submitted_at->format('d M, Y — H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <a href="{{ route('exam.results', $sess->id) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-2xl transition-colors">
                                    Review
                                </a>
                                <button wire:click="retakeSession({{ $sess->id }})" 
                                        class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-2xl transition-colors">
                                    Re-take
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-sm text-slate-500">
                                No session history found. <a href="{{ route('exam.setup') }}" class="text-emerald-600 hover:text-emerald-700 font-bold">Start a session now!</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
            {{ $sessions->links() }}
        </div>
    </div>
</div>
