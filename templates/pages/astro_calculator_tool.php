<div class="max-w-3xl mx-auto px-6 py-12" x-data="{
    name: '',
    email: '',
    phone: '',
    birth_date: '',
    birth_time: '',
    birth_city: '',
    error: '',
    loading: false,
    results: null,

    calculate() {
        if (!this.name || (!this.email && !this.phone) || !this.birth_date) {
            this.error = 'All gated coordinates (name, email/phone, birth date) are required.';
            return;
        }
        this.error = '';
        this.loading = true;

        fetch('/api/sun-moon-sign', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
            },
            body: JSON.stringify({
                name: this.name,
                email: this.email,
                phone: this.phone,
                birth_date: this.birth_date,
                birth_time: this.birth_time,
                birth_city: this.birth_city
            })
        })
        .then(res => res.json())
        .then(data => {
            this.loading = false;
            if (data.success) {
                this.results = data;
            } else {
                this.error = data.error || 'Astro calculation failed.';
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
        <span class="text-xs uppercase tracking-[0.2em] text-brand-gold font-bold">Stellar Coordinates</span>
        <h1 class="font-serif text-3xl md:text-4xl text-brand-text">Cosmic Solar Sign Finder</h1>
        <p class="text-xs text-slate-500 font-light max-w-md mx-auto">Map your birth coordinates against Gregorian solar alignments to discover your primary zodiac characteristics.</p>
    </div>

    <!-- Interface Box -->
    <div class="glass bg-white p-8 md:p-10 rounded-3xl border border-brand-gold/15 shadow-md min-h-[350px]">
        
        <div x-show="!results" class="space-y-6">
            <h3 class="font-serif text-xl text-brand-text font-bold text-center">Calculate Sun Sign Alignments</h3>
            
            <div x-show="error" class="p-3 bg-red-500/10 border border-red-500/20 text-red-500 text-xs rounded-xl font-semibold text-center" x-text="error"></div>

            <form @submit.prevent="calculate()" class="space-y-6">
                <!-- Birth parameters -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="space-y-1">
                        <label class="text-[10px] uppercase tracking-wider text-slate-400 font-bold block">Gregorian Birth Date</label>
                        <input type="date" x-model="birth_date" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-brand-gold">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] uppercase tracking-wider text-slate-400 font-bold block">Birth Time (Optional)</label>
                        <input type="time" x-model="birth_time" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-brand-gold">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] uppercase tracking-wider text-slate-400 font-bold block">Birth City (Optional)</label>
                        <input type="text" x-model="birth_city" placeholder="e.g. Bangalore" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-brand-gold">
                    </div>
                </div>

                <div class="border-t border-slate-50 pt-4 space-y-3">
                    <span class="text-[10px] uppercase tracking-wider text-brand-gold font-bold block">Gate: Seeker Access Contact Info</span>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[10px] uppercase tracking-wider text-slate-400 font-bold block">Seeker Name</label>
                            <input type="text" x-model="name" required placeholder="Mayur Dev" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-brand-gold">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] uppercase tracking-wider text-slate-400 font-bold block">Email Coordinates</label>
                            <input type="email" x-model="email" required placeholder="mayur@domain.com" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-brand-gold">
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] uppercase tracking-wider text-slate-400 font-bold block">Phone Number (Optional)</label>
                        <input type="text" x-model="phone" placeholder="+91 99999 88888" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:border-brand-gold">
                    </div>
                </div>

                <button type="submit" :disabled="loading" class="w-full py-3.5 rounded-full bg-gradient-to-r from-brand-teal to-brand-red text-white text-xs font-semibold tracking-wider uppercase shadow-md hover:opacity-90 transition-opacity">
                    <span x-show="!loading">Locate Cosmic Sign</span>
                    <span x-show="loading">Aligning Stellar Maps...</span>
                </button>
            </form>
        </div>

        <!-- Output display -->
        <div x-show="results" class="space-y-6" style="display: none;">
            <div class="text-center space-y-2 border-b border-slate-50 pb-6">
                <span class="text-xs uppercase tracking-widest text-brand-gold font-bold" x-text="'Stellar Alignments: ' + results?.element + ' Element'"></span>
                <h3 class="font-serif text-4xl text-brand-text font-bold" x-text="results?.sign"></h3>
                <p class="text-xs text-slate-400 font-light" x-text="'Ruler Planet: ' + results?.ruler"></p>
            </div>

            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 space-y-4">
                <h4 class="font-serif text-lg text-brand-text font-bold">Stellar Vibrations</h4>
                <p class="text-xs text-slate-600 leading-relaxed font-light" x-text="results?.description"></p>
            </div>

            <!-- Rising/Moon Disclaimers -->
            <div class="bg-amber-500/5 border border-brand-gold/20 p-6 rounded-2xl space-y-3">
                <div class="flex items-center space-x-2 text-brand-gold">
                    <span class="font-bold text-sm">⚠ Disclaimer</span>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed font-light">
                    Calculating precise lunar (Moon) or ascending (Rising/Lagna) signs requires high-resolution ephemerides based on your exact latitude, longitude, and birth time coordinates. 
                    This complimentary tool determines solar coordinates. For accurate Vedic charts, schedule a personalized session.
                </p>
            </div>

            <!-- Booking Call-to-action -->
            <div class="flex flex-col md:flex-row items-center justify-between gap-4 pt-4 border-t border-slate-50">
                <p class="text-[10px] text-slate-400 font-light">Ready to unlock your entire Cosmic Resonance Chart?</p>
                <div class="flex space-x-3">
                    <button type="button" @click="results = null; name = ''; email = ''; phone = ''; birth_date = '';" class="px-5 py-2.5 rounded-full border border-slate-200 text-slate-500 text-xs font-bold uppercase tracking-wider hover:bg-slate-50 transition-colors">
                        Re-Calculate
                    </button>
                    <a href="/booking?service_id=3" class="px-5 py-2.5 rounded-full bg-gradient-to-r from-brand-teal to-brand-red text-white text-xs font-semibold uppercase tracking-wider shadow-md">
                        Book Natal Chart Reading
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
