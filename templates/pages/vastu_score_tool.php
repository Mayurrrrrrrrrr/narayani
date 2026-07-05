<div class="max-w-3xl mx-auto px-6 py-12" x-data="{ 
    step: 1, 
    entrance: 'NE',
    kitchen: 'SE',
    bedroom: 'SW',
    toilet: 'NW',
    name: '',
    email: '',
    phone: '',
    error: '',
    score: null,
    advice: [],
    loading: false,

    submitQuiz() {
        if (!this.name || (!this.email && !this.phone)) {
            this.error = 'Please enter your name and contact coordinates.';
            return;
        }
        this.error = '';
        this.loading = true;

        fetch('/api/vastu-score', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                name: this.name,
                email: this.email,
                phone: this.phone,
                entrance: this.entrance,
                kitchen: this.kitchen,
                bedroom: this.bedroom,
                toilet: this.toilet
            })
        })
        .then(res => res.json())
        .then(data => {
            this.loading = false;
            if (data.success) {
                this.score = data.score;
                this.advice = data.advice;
                this.step = 6; // Move to results step
            } else {
                this.error = data.error || 'Submission failed.';
            }
        })
        .catch(err => {
            this.loading = false;
            this.error = 'A network error occurred. Please try again.';
        });
    }
}">
    <!-- Header Section -->
    <div class="text-center space-y-3 mb-10">
        <span class="text-xs uppercase tracking-[0.2em] text-brand-gold font-bold">Complimentary Auditing</span>
        <h1 class="font-serif text-3xl md:text-4xl text-brand-text">Home Vastu Score Audit</h1>
        <p class="text-xs text-slate-500 font-light max-w-md mx-auto">Evaluate your living space's elemental and dimensional alignments to discover your Vastu harmony index.</p>
    </div>

    <!-- Wizard Box -->
    <div class="glass bg-white p-8 md:p-10 rounded-3xl border border-brand-gold/15 shadow-md min-h-[350px] flex flex-col justify-between">
        
        <!-- Step Indicator -->
        <div class="flex justify-between items-center text-[9px] uppercase tracking-wider text-slate-400 font-bold border-b border-slate-50 pb-4 mb-6" x-show="step <= 5">
            <span x-text="'Element: ' + (step === 1 ? 'Air/Water (Entrance)' : step === 2 ? 'Fire (Kitchen)' : step === 3 ? 'Earth (Bedroom)' : step === 4 ? 'Space (Toilet)' : 'Gating Gate')"></span>
            <span x-text="'Step ' + step + ' of 5'"></span>
        </div>

        <!-- Question Content -->
        <div class="flex-grow space-y-6">
            
            <!-- Step 1: Entrance Direction -->
            <div x-show="step === 1" class="space-y-4">
                <h3 class="font-serif text-xl text-brand-text font-bold">1. Where is the main entrance threshold of your home located?</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="p-4 rounded-2xl border border-slate-200 hover:border-brand-gold cursor-pointer flex items-center space-x-3 transition-colors" :class="entrance === 'NE' ? 'border-brand-gold bg-brand-gold/5' : ''">
                        <input type="radio" x-model="entrance" value="NE" class="text-brand-gold focus:ring-brand-gold">
                        <span class="text-xs text-slate-700 font-semibold">North-East (Highly Recommended)</span>
                    </label>
                    <label class="p-4 rounded-2xl border border-slate-200 hover:border-brand-gold cursor-pointer flex items-center space-x-3 transition-colors" :class="entrance === 'N' || entrance === 'E' ? 'border-brand-gold bg-brand-gold/5' : ''">
                        <input type="radio" x-model="entrance" value="N" class="text-brand-gold focus:ring-brand-gold">
                        <span class="text-xs text-slate-700 font-semibold">East or North (Favorable)</span>
                    </label>
                    <label class="p-4 rounded-2xl border border-slate-200 hover:border-brand-gold cursor-pointer flex items-center space-x-3 transition-colors" :class="entrance === 'W' || entrance === 'S' ? 'border-brand-gold bg-brand-gold/5' : ''">
                        <input type="radio" x-model="entrance" value="W" class="text-brand-gold focus:ring-brand-gold">
                        <span class="text-xs text-slate-700 font-semibold">West or South (Moderate)</span>
                    </label>
                    <label class="p-4 rounded-2xl border border-slate-200 hover:border-brand-gold cursor-pointer flex items-center space-x-3 transition-colors" :class="entrance === 'SW' ? 'border-brand-gold bg-brand-gold/5' : ''">
                        <input type="radio" x-model="entrance" value="SW" class="text-brand-gold focus:ring-brand-gold">
                        <span class="text-xs text-slate-700 font-semibold">South-West (Critical)</span>
                    </label>
                </div>
            </div>

            <!-- Step 2: Kitchen Zone -->
            <div x-show="step === 2" class="space-y-4">
                <h3 class="font-serif text-xl text-brand-text font-bold">2. Which quadrant houses your cooking zone or kitchen stove?</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="p-4 rounded-2xl border border-slate-200 hover:border-brand-gold cursor-pointer flex items-center space-x-3 transition-colors" :class="kitchen === 'SE' ? 'border-brand-gold bg-brand-gold/5' : ''">
                        <input type="radio" x-model="kitchen" value="SE" class="text-brand-gold focus:ring-brand-gold">
                        <span class="text-xs text-slate-700 font-semibold">South-East (Agni corner)</span>
                    </label>
                    <label class="p-4 rounded-2xl border border-slate-200 hover:border-brand-gold cursor-pointer flex items-center space-x-3 transition-colors" :class="kitchen === 'NW' ? 'border-brand-gold bg-brand-gold/5' : ''">
                        <input type="radio" x-model="kitchen" value="NW" class="text-brand-gold focus:ring-brand-gold">
                        <span class="text-xs text-slate-700 font-semibold">North-West (Wind corner)</span>
                    </label>
                    <label class="p-4 rounded-2xl border border-slate-200 hover:border-brand-gold cursor-pointer flex items-center space-x-3 transition-colors" :class="kitchen === 'NE' ? 'border-brand-gold bg-brand-gold/5' : ''">
                        <input type="radio" x-model="kitchen" value="NE" class="text-brand-gold focus:ring-brand-gold">
                        <span class="text-xs text-slate-700 font-semibold">North-East (Water corner - Conflict)</span>
                    </label>
                    <label class="p-4 rounded-2xl border border-slate-200 hover:border-brand-gold cursor-pointer flex items-center space-x-3 transition-colors" :class="kitchen === 'Other' ? 'border-brand-gold bg-brand-gold/5' : ''">
                        <input type="radio" x-model="kitchen" value="Other" class="text-brand-gold focus:ring-brand-gold">
                        <span class="text-xs text-slate-700 font-semibold">Other zones</span>
                    </label>
                </div>
            </div>

            <!-- Step 3: Master Bedroom -->
            <div x-show="step === 3" class="space-y-4">
                <h3 class="font-serif text-xl text-brand-text font-bold">3. Where is the main master bedroom situated?</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="p-4 rounded-2xl border border-slate-200 hover:border-brand-gold cursor-pointer flex items-center space-x-3 transition-colors" :class="bedroom === 'SW' ? 'border-brand-gold bg-brand-gold/5' : ''">
                        <input type="radio" x-model="bedroom" value="SW" class="text-brand-gold focus:ring-brand-gold">
                        <span class="text-xs text-slate-700 font-semibold">South-West (Nairutya stable zone)</span>
                    </label>
                    <label class="p-4 rounded-2xl border border-slate-200 hover:border-brand-gold cursor-pointer flex items-center space-x-3 transition-colors" :class="bedroom === 'S' || bedroom === 'W' ? 'border-brand-gold bg-brand-gold/5' : ''">
                        <input type="radio" x-model="bedroom" value="S" class="text-brand-gold focus:ring-brand-gold">
                        <span class="text-xs text-slate-700 font-semibold">South or West</span>
                    </label>
                    <label class="p-4 rounded-2xl border border-slate-200 hover:border-brand-gold cursor-pointer flex items-center space-x-3 transition-colors" :class="bedroom === 'NE' ? 'border-brand-gold bg-brand-gold/5' : ''">
                        <input type="radio" x-model="bedroom" value="NE" class="text-brand-gold focus:ring-brand-gold">
                        <span class="text-xs text-slate-700 font-semibold">North-East (Highly Active Zone)</span>
                    </label>
                </div>
            </div>

            <!-- Step 4: Toilet position -->
            <div x-show="step === 4" class="space-y-4">
                <h3 class="font-serif text-xl text-brand-text font-bold">4. Where is the toilet/bathroom drainage located?</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="p-4 rounded-2xl border border-slate-200 hover:border-brand-gold cursor-pointer flex items-center space-x-3 transition-colors" :class="toilet === 'NW' ? 'border-brand-gold bg-brand-gold/5' : ''">
                        <input type="radio" x-model="toilet" value="NW" class="text-brand-gold focus:ring-brand-gold">
                        <span class="text-xs text-slate-700 font-semibold">North-West (Disposal zone)</span>
                    </label>
                    <label class="p-4 rounded-2xl border border-slate-200 hover:border-brand-gold cursor-pointer flex items-center space-x-3 transition-colors" :class="toilet === 'NE' || toilet === 'SW' ? 'border-brand-gold bg-brand-gold/5' : ''">
                        <input type="radio" x-model="toilet" value="NE" class="text-brand-gold focus:ring-brand-gold">
                        <span class="text-xs text-slate-700 font-semibold">North-East or South-West (Energy Leak)</span>
                    </label>
                    <label class="p-4 rounded-2xl border border-slate-200 hover:border-brand-gold cursor-pointer flex items-center space-x-3 transition-colors" :class="toilet === 'Other' ? 'border-brand-gold bg-brand-gold/5' : ''">
                        <input type="radio" x-model="toilet" value="Other" class="text-brand-gold focus:ring-brand-gold">
                        <span class="text-xs text-slate-700 font-semibold">Other zones</span>
                    </label>
                </div>
            </div>

            <!-- Step 5: Gated Form (Lead generation) -->
            <div x-show="step === 5" class="space-y-4">
                <h3 class="font-serif text-xl text-brand-text font-bold">Secure Your Spatial Assessment</h3>
                <p class="text-xs text-slate-500 font-light">Submit your details to process calculations and display tailored Vastu recommendations instantly.</p>
                
                <div x-show="error" class="p-3 bg-red-500/10 border border-red-500/20 text-red-500 text-xs rounded-xl font-semibold text-center" x-text="error"></div>
                
                <div class="space-y-3">
                    <div class="space-y-1">
                        <label class="text-[10px] uppercase tracking-wider text-slate-400 font-bold block">Seeker Name</label>
                        <input type="text" x-model="name" placeholder="Priya Nair" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-brand-gold">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] uppercase tracking-wider text-slate-400 font-bold block">Email Coordinates</label>
                        <input type="email" x-model="email" placeholder="priya@example.com" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-brand-gold">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] uppercase tracking-wider text-slate-400 font-bold block">Phone Number (Optional)</label>
                        <input type="text" x-model="phone" placeholder="+91 98765 43210" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-brand-gold">
                    </div>
                </div>
            </div>

            <!-- Step 6: Results -->
            <div x-show="step === 6" class="space-y-6 flex flex-col items-center">
                <!-- Radial Gauge SVG -->
                <div class="relative w-40 h-40 flex items-center justify-center">
                    <svg class="w-full h-full transform -rotate-90">
                        <circle cx="80" cy="80" r="70" stroke="#E2E8F0" stroke-width="12" fill="transparent" />
                        <circle cx="80" cy="80" r="70" stroke="url(#vastuGrad)" stroke-width="12" fill="transparent"
                                :stroke-dasharray="2 * Math.PI * 70"
                                :stroke-dashoffset="2 * Math.PI * 70 * (1 - (score || 0) / 100)"
                                stroke-linecap="round" />
                        <defs>
                            <linearGradient id="vastuGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#7B2FF7" />
                                <stop offset="100%" stop-color="#FF2E9A" />
                            </linearGradient>
                        </defs>
                    </svg>
                    <div class="absolute text-center space-y-0.5">
                        <span class="font-mono text-3xl font-bold text-brand-text" x-text="score + '%'"></span>
                        <span class="text-[9px] uppercase tracking-wider text-slate-400 block font-semibold">Harmony Index</span>
                    </div>
                </div>

                <!-- Custom tailored recommendations -->
                <div class="w-full text-left space-y-4">
                    <div class="border-b border-slate-100 pb-2">
                        <h4 class="font-serif text-lg text-brand-text font-bold">Acharya's Tailored Suggestions</h4>
                    </div>
                    <ul class="space-y-2 text-xs text-slate-600 leading-relaxed font-light">
                        <template x-for="item in advice">
                            <li class="flex items-start space-x-2 bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <span class="text-brand-gold font-bold">◈</span>
                                <span x-text="item"></span>
                            </li>
                        </template>
                    </ul>
                    
                    <div class="pt-4 border-t border-slate-50 flex flex-col md:flex-row gap-4 items-center justify-between">
                        <p class="text-[10px] text-slate-400 font-light">For complete calculations matching structural geometries, book a detailed Vastu alignment session.</p>
                        <a href="/booking?service_id=1" class="px-5 py-2.5 rounded-full bg-brand-gold text-white text-xs font-semibold uppercase tracking-wider whitespace-nowrap">
                            Book Audit Session
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <!-- Buttons Footer -->
        <div class="flex justify-between items-center pt-6 border-t border-slate-50 mt-6" x-show="step <= 5">
            <div>
                <button type="button" @click="step--" x-show="step > 1" class="px-5 py-2 rounded-full border border-slate-200 text-slate-500 text-xs font-bold uppercase tracking-wider hover:bg-slate-50 transition-colors">
                    Back
                </button>
            </div>
            
            <div>
                <button type="button" @click="step++" x-show="step < 5" class="px-6 py-2.5 rounded-full bg-slate-800 text-white text-xs font-bold uppercase tracking-wider hover:bg-slate-700 transition-colors">
                    Next Section
                </button>
                <button type="button" @click="submitQuiz()" x-show="step === 5" :disabled="loading" class="px-6 py-2.5 rounded-full bg-gradient-to-r from-brand-purple to-brand-pink text-white text-xs font-bold uppercase tracking-wider shadow-md">
                    <span x-show="!loading">Audit Results</span>
                    <span x-show="loading">Auditing...</span>
                </button>
            </div>
        </div>

    </div>
</div>
