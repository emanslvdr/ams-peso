<div
    id="candidate-modal"
    class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center transition duration-200 hidden"
    tabindex="-1"
>
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md px-7 py-8 relative animate-fade-in flex flex-col gap-4">
        <!-- Close Button -->
        <button type="button"
            class="absolute top-5 right-5 text-gray-400 hover:text-red-500 hover:scale-110 transition p-2 rounded-full focus:outline-none"
            id="close-candidate-modal"
            aria-label="Close modal"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>

        <!-- Header (Photo + Name + Contact) -->
        <div class="flex items-center gap-5">
            <!-- In your candidate-modal.blade.php -->
<div id="candidate-avatar" class="w-16 h-16 rounded-full flex items-center justify-center font-bold text-2xl text-blue-700 bg-blue-100 shadow ring-2 ring-blue-200 shrink-0 overflow-hidden"></div>

            <div class="flex-1">
    <!-- Name & Best Match -->
    <div class="flex items-center gap-2">
        <span class="font-semibold text-xl leading-tight" id="candidate-name"></span>
        <span id="best-match-badge"
              class="hidden bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full font-semibold flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Best Match
        </span>
    </div>
    <!-- Contact Info -->
    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-1 text-[15px] text-gray-600">
        <span id="candidate-email" class="flex items-center gap-1">
            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M16 12A4 4 0 118 12a4 4 0 018 0z" /><path d="M12 16v6" />
            </svg>
            <!-- Email will be filled by JS -->
        </span>
        <span id="candidate-phone" class="flex items-center gap-1">
            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M22 16.92V19a2 2 0 01-2.18 2A19.72 19.72 0 013 5.18 2 2 0 015 3h2.09a2 2 0 012 1.72c.13.93.34 1.84.62 2.73a2 2 0 01-.45 2L8.1 10.1a16 16 0 006.5 6.5l1.65-1.65a2 2 0 012-.45c.89.28 1.8.49 2.73.62A2 2 0 0122 16.92z"/>
            </svg>
            <!-- Phone will be filled by JS -->
        </span>
        <span id="candidate-age" class="flex items-center gap-1 text-gray-400">
            <!-- Age will be filled by JS -->
        </span>
    </div>
</div>

        </div>

        <!-- Divider -->
        <div class="border-b border-gray-100 mb-1"></div>

        <!-- Status & Stage -->
        <div class="flex items-center gap-3 text-sm">
            <span class="font-semibold text-gray-600">Status:</span>
            <span id="candidate-status" class="inline-block px-2 py-0.5 rounded bg-gray-100 text-gray-800 font-medium text-xs"></span>
            <span id="candidate-stage" class="inline-block px-2 py-0.5 rounded bg-blue-100 text-blue-700 font-medium text-xs"></span>
        </div>

        <!-- Skills -->
        <div class="flex items-start gap-2 mb-1">
    <span class="font-semibold text-gray-700 text-sm w-24 flex-shrink-0 mt-1">Skills:</span>
    <div id="candidate-skills" class="flex flex-wrap gap-2"></div>
</div>

        <!-- Education -->
        <div class="flex items-center gap-2">
            <span class="font-semibold text-gray-700 text-sm w-32">Education:</span>
            <span id="candidate-education" class="text-gray-600 text-sm flex-1"></span>
        </div>
        <!-- Work Experience -->
        <div class="flex items-center gap-2">
            <span class="font-semibold text-gray-700 text-sm w-32">Work Exp.:</span>
            <span id="candidate-work" class="text-gray-600 text-sm flex-1"></span>
        </div>
        <!-- Resume -->
        <div class="flex items-center gap-2">
            <span class="font-semibold text-gray-700 text-sm w-32">Resume:</span>
            <a href="#" id="candidate-resume-view" target="_blank" class="hidden">
                
            </a>
            <a href="#" id="candidate-resume-download" class="flex items-center gap-1 px-2 py-1 bg-gray-50 hover:bg-gray-100 rounded text-gray-700 font-medium text-xs transition hidden" download>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 4v16m0 0l-6-6m6 6l6-6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Download
            </a>
            <span id="candidate-no-resume" class="text-xs text-gray-400 hidden">No resume uploaded</span>
        </div>

        <!-- Match Score Bar -->
<div class="w-full my-2">
    <div class="flex items-center gap-2 mb-1">
        <span id="candidate-match-score" class="text-xs font-semibold"></span>
    </div>
    <div class="w-full h-3 bg-blue-50 rounded-full overflow-hidden relative">
        <div id="candidate-match-progress"
            class="absolute left-0 top-0 h-3 rounded-full transition-all"
            style="width:0%; background: linear-gradient(90deg, #38bdf8, #2563eb);"></div>
    </div>
</div>

        <!-- Submitted & Updated -->
        <div class="text-xs text-gray-400 flex justify-between mt-2">
            <span>Submitted: <span id="candidate-created-at"></span></span>
            <span>Last Update: <span id="candidate-updated-at"></span></span>
            
        </div>
    </div>
</div>
