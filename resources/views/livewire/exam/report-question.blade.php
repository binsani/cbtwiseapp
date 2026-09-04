<div x-data="{ open: @entangle('isOpen') }" x-show="open" 
     class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="open = false"></div>

    <!-- Modal Container -->
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white rounded-3xl shadow-2xl border border-gray-100 max-w-lg w-full p-8 overflow-hidden transform transition-all duration-300 scale-100">
            <!-- Header -->
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 font-heading">Report Question Issue</h3>
                    <p class="text-xs text-gray-500 mt-1">Help us maintain the accuracy of our question bank.</p>
                </div>
                <button @click="open = false" class="text-gray-400 hover:text-gray-600 font-bold text-lg">&times;</button>
            </div>

            <!-- Form -->
            <form wire:submit.prevent="submitReport" class="space-y-6">
                <!-- Reason select -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Reason for Report</label>
                    <select wire:model="reason" class="block w-full border-gray-200 rounded-2xl shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 py-3 px-4 transition-all">
                        <option value="wrong_answer">Wrong Correct Answer Marked</option>
                        <option value="typo">Typo/Grammar Error</option>
                        <option value="offensive">Offensive Content</option>
                        <option value="duplicate">Duplicate Question</option>
                        <option value="other">Other/Format issue</option>
                    </select>
                    @error('reason') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Notes textarea -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Provide Details</label>
                    <textarea wire:model="notes" rows="4" placeholder="Explain the error in detail..."
                              class="block w-full border-gray-200 rounded-2xl shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 py-3 px-4 transition-all"></textarea>
                    @error('notes') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                    <button type="button" @click="open = false" 
                            class="px-5 py-2.5 border border-gray-300 text-gray-700 font-bold rounded-2xl hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-6 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-2xl shadow-md hover:shadow-lg transition-colors flex items-center space-x-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span>Submit Report</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
