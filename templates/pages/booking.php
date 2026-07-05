<?php
$modes = !empty($consultant['modes']) ? json_decode($consultant['modes'], true) : ['Video Call', 'Audio Consultation'];
$isLoggedIn = isset($_SESSION['user_id']);
$sessionUser = [
    'name' => $_SESSION['user_name'] ?? '',
    'email' => $_SESSION['user_email'] ?? '',
    'phone' => $_SESSION['user_phone'] ?? '',
    'city' => $_SESSION['user_city'] ?? '',
];
?>
<section class="py-16 max-w-4xl mx-auto px-6 relative" x-data="bookingWizard()">
    <!-- Pulsing Lotus Loading State Overlay -->
    <div x-show="transitioning" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 bg-white/75 backdrop-blur-sm flex flex-col items-center justify-center space-y-4" style="display: none;">
        <svg class="w-16 h-16 text-brand-gold animate-pulse" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18M3 12h18M12 3c-1.2 3.6-4.8 6.4-9 9 4.2 2.6 7.8 5.4 9 9 1.2-3.6 4.8-6.4 9-9-4.2-2.6-7.8-5.4-9-9z"/>
        </svg>
        <span class="text-[10px] uppercase tracking-widest font-bold text-brand-gold">Aligning Cosmic Pathways...</span>
    </div>

    <!-- Wizard Steps Indicator -->
    <div class="mb-12">
        <div class="flex items-center justify-between relative">
            <!-- Progress Line -->
            <div class="absolute left-0 right-0 top-1/2 -translate-y-1/2 h-0.5 bg-slate-200 z-0"></div>
            <div class="absolute left-0 top-1/2 -translate-y-1/2 h-0.5 bg-brand-gold transition-all duration-300 z-0" :style="'width: ' + ((step - 1) * 33.33) + '%'"></div>

            <!-- Step Bullets -->
            <template x-for="s in [1, 2, 3, 4]">
                <div class="relative z-10 flex flex-col items-center">
                    <button 
                        @click="goToStep(s)" 
                        :disabled="s > maxStepReached"
                        class="w-10 h-10 rounded-full flex items-center justify-center font-semibold text-sm transition-all duration-300 border-2"
                        :class="step === s ? 'bg-brand-gold border-brand-gold text-white shadow-md' : (step > s ? 'bg-white border-brand-gold text-brand-gold' : 'bg-slate-50 border-slate-200 text-slate-400')"
                    >
                        <span x-text="s"></span>
                    </button>
                    <span class="text-[10px] uppercase tracking-wider font-semibold mt-2 text-slate-400" :class="step === s && 'text-brand-gold'" x-text="getStepLabel(s)"></span>
                </div>
            </template>
        </div>
    </div>

    <!-- Wizard Card -->
    <div class="glass bg-white p-8 md:p-12 rounded-3xl border border-brand-gold/15 shadow-sm min-h-[400px] flex flex-col justify-between">
        
        <!-- Step 1: Service & Mode -->
        <div x-show="step === 1" class="space-y-6">
            <div class="space-y-2">
                <span class="text-xs uppercase tracking-[0.2em] text-brand-gold font-bold">Step 1</span>
                <h2 class="font-serif text-3xl text-brand-text">Select Pathway & Mode</h2>
                <p class="text-slate-500 font-light text-sm">Choose the spiritual alignment consultation and preferred method of contact.</p>
            </div>

            <div class="space-y-4">
                <div class="space-y-2">
                    <label class="text-xs uppercase tracking-wider font-semibold text-slate-500">Alignment Pathway</label>
                    <select x-model="bookingData.service_id" @change="onServiceChange" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:outline-none focus:border-brand-gold text-slate-700">
                        <option value="">-- Choose a Service --</option>
                        <template x-for="srv in services" :key="srv.id">
                            <option :value="srv.id" x-text="srv.title + ' (₹' + parseFloat(srv.price_inr).toLocaleString() + ')'"></option>
                        </template>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-xs uppercase tracking-wider font-semibold text-slate-500">Consultation Session Mode</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <template x-for="m in modes" :key="m">
                            <button 
                                @click="bookingData.mode = m" 
                                class="px-4 py-3 rounded-xl border text-sm font-semibold transition-all text-center"
                                :class="bookingData.mode === m ? 'border-brand-gold bg-brand-gold/10 text-brand-gold' : 'border-slate-200 bg-white text-slate-600 hover:border-brand-gold/50'"
                                x-text="m"
                            ></button>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 2: Date & Slot Picker -->
        <div x-show="step === 2" class="space-y-6">
            <div class="space-y-2">
                <span class="text-xs uppercase tracking-[0.2em] text-brand-gold font-bold">Step 2</span>
                <h2 class="font-serif text-3xl text-brand-text">Reserve Time Coordinates</h2>
                <p class="text-slate-500 font-light text-sm">Select an available date and time slot from the consultant calendar.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="text-xs uppercase tracking-wider font-semibold text-slate-500">Target Date</label>
                    <input 
                        type="date" 
                        x-model="bookingData.date" 
                        @change="fetchSlots"
                        :min="new Date().toISOString().split('T')[0]"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-brand-gold text-slate-700"
                    >
                </div>

                <div class="space-y-2">
                    <label class="text-xs uppercase tracking-wider font-semibold text-slate-500">Available Resonance Slots</label>
                    <div class="grid grid-cols-3 gap-3 max-h-[180px] overflow-y-auto pr-2" x-show="slots.length > 0">
                        <template x-for="sl in slots" :key="sl">
                            <button 
                                @click="bookingData.slot = sl" 
                                class="px-2 py-2 rounded-lg border text-xs font-semibold transition-all text-center"
                                :class="bookingData.slot === sl ? 'border-brand-gold bg-brand-gold text-white' : 'border-slate-100 bg-slate-50 text-slate-600 hover:border-brand-gold'"
                                x-text="sl"
                            ></button>
                        </template>
                    </div>
                    <div class="text-xs text-slate-400 font-light py-4 text-center border border-dashed border-slate-200 rounded-xl" x-show="slots.length === 0">
                        No slots available on this date. Select another day (e.g. Mon/Wed/Fri).
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 3: Conditional Intake Form & Seeker Info -->
        <div x-show="step === 3" class="space-y-6">
            <div class="space-y-2">
                <span class="text-xs uppercase tracking-[0.2em] text-brand-gold font-bold">Step 3</span>
                <h2 class="font-serif text-3xl text-brand-text">Sacred Intake Coordinates</h2>
                <p class="text-slate-500 font-light text-sm">Supply your personal profile details and specific configuration inputs.</p>
            </div>

            <!-- Profile Info (Required if not logged in, otherwise displays session summary) -->
            <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl space-y-4">
                <h4 class="text-xs uppercase tracking-wider font-bold text-slate-500">Seeker Details</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[10px] uppercase font-semibold text-slate-400">Full Name</label>
                        <input type="text" x-model="bookingData.name" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs bg-white focus:outline-none focus:border-brand-gold">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] uppercase font-semibold text-slate-400">Email Address</label>
                        <input type="email" x-model="bookingData.email" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs bg-white focus:outline-none focus:border-brand-gold">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] uppercase font-semibold text-slate-400">Phone Connection</label>
                        <input type="text" x-model="bookingData.phone" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs bg-white focus:outline-none focus:border-brand-gold">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] uppercase font-semibold text-slate-400">City</label>
                        <input type="text" x-model="bookingData.city" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs bg-white focus:outline-none focus:border-brand-gold">
                    </div>
                </div>
            </div>

            <!-- Conditional Intake Questions -->
            <div class="space-y-4">
                <h4 class="text-xs uppercase tracking-wider font-bold text-brand-gold border-b border-brand-gold/10 pb-1.5">Intake Details</h4>

                <!-- Jyotish Category -->
                <div x-show="selectedService && selectedService.category_slug === 'jyotish'" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="space-y-1">
                        <label class="text-[10px] uppercase font-semibold text-slate-500">Birth Date</label>
                        <input type="date" x-model="bookingData.intake.birth_date" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:outline-none focus:border-brand-gold">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] uppercase font-semibold text-slate-500">Accurate Birth Time</label>
                        <input type="time" x-model="bookingData.intake.birth_time" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:outline-none focus:border-brand-gold">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] uppercase font-semibold text-slate-500">Birth City / Town</label>
                        <input type="text" x-model="bookingData.intake.birth_city" placeholder="e.g. Mumbai, India" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:outline-none focus:border-brand-gold">
                    </div>
                </div>

                <!-- Vastu Category -->
                <div x-show="selectedService && selectedService.category_slug === 'vastu'" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[10px] uppercase font-semibold text-slate-500">Property Type</label>
                            <select x-model="bookingData.intake.property_type" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs bg-white focus:outline-none focus:border-brand-gold">
                                <option value="Residential">Residential (Apartment, Villa)</option>
                                <option value="Commercial">Commercial (Office, Retail Shop)</option>
                                <option value="Industrial">Industrial (Factory, Warehouse)</option>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] uppercase font-semibold text-slate-500">Blueprint / Layout Map URL (or placeholder description)</label>
                            <input type="text" x-model="bookingData.intake.layout_plan" placeholder="Describe layout or paste drive link" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:outline-none focus:border-brand-gold">
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] uppercase font-semibold text-slate-500">Property Full Address</label>
                        <textarea x-model="bookingData.intake.address" rows="2" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:outline-none focus:border-brand-gold"></textarea>
                    </div>
                </div>

                <!-- Healing Category -->
                <div x-show="selectedService && selectedService.category_slug !== 'jyotish' && selectedService.category_slug !== 'vastu'" class="space-y-2">
                    <label class="text-[10px] uppercase font-semibold text-slate-500">Session Intentions & Health/Spiritual Background Details</label>
                    <textarea x-model="bookingData.intake.background" rows="3" placeholder="Please list what areas you want to harmonize..." class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:outline-none focus:border-brand-gold"></textarea>
                </div>
            </div>
        </div>

        <!-- Step 4: Summary & Submit -->
        <div x-show="step === 4" class="space-y-6">
            <div class="space-y-2">
                <span class="text-xs uppercase tracking-[0.2em] text-brand-gold font-bold">Step 4</span>
                <h2 class="font-serif text-3xl text-brand-text">Final Review & Sign</h2>
                <p class="text-slate-500 font-light text-sm">Please verify your booking details before committing the transaction.</p>
            </div>

            <div class="border border-brand-gold/15 p-6 rounded-2xl bg-[#FCFAF7] space-y-4 text-sm">
                <div class="grid grid-cols-2 gap-y-3 gap-x-6">
                    <div class="space-y-0.5">
                        <span class="text-[10px] uppercase font-semibold text-slate-400">Selected Pathway</span>
                        <div class="font-bold text-slate-700" x-text="selectedService ? selectedService.title : ''"></div>
                    </div>
                    <div class="space-y-0.5">
                        <span class="text-[10px] uppercase font-semibold text-slate-400">Exchange Fee</span>
                        <div class="font-bold text-brand-gold" x-text="selectedService ? '₹' + parseFloat(selectedService.price_inr).toLocaleString() : ''"></div>
                    </div>
                    <div class="space-y-0.5">
                        <span class="text-[10px] uppercase font-semibold text-slate-400">Session Mode</span>
                        <div class="font-bold text-slate-700" x-text="bookingData.mode"></div>
                    </div>
                    <div class="space-y-0.5">
                        <span class="text-[10px] uppercase font-semibold text-slate-400">Coordinates Slot</span>
                        <div class="font-bold text-slate-700" x-text="bookingData.date + ' @ ' + bookingData.slot"></div>
                    </div>
                    <div class="space-y-0.5">
                        <span class="text-[10px] uppercase font-semibold text-slate-400">Seeker</span>
                        <div class="font-bold text-slate-700" x-text="bookingData.name + ' (' + bookingData.email + ')'"></div>
                    </div>
                    <div class="space-y-0.5">
                        <span class="text-[10px] uppercase font-semibold text-slate-400">Location City</span>
                        <div class="font-bold text-slate-700" x-text="bookingData.city"></div>
                    </div>
                </div>

                <!-- Small display of custom intake values -->
                <div class="border-t border-brand-gold/10 pt-4 mt-2">
                    <span class="text-[10px] uppercase font-semibold text-slate-400 block mb-1">Intake Details Saved</span>
                    <pre class="text-[11px] font-mono text-slate-500 bg-white p-3 rounded-lg border border-slate-100 max-h-[80px] overflow-y-auto" x-text="JSON.stringify(bookingData.intake, null, 2)"></pre>
                </div>
            </div>

            <!-- Error banner -->
            <div x-show="errorMessage" class="p-4 rounded-xl bg-red-50 border border-red-100 text-xs text-red-600 font-semibold" x-text="errorMessage"></div>
        </div>

        <!-- Success Screen -->
        <div x-show="step === 5" class="text-center py-8 space-y-6">
            <div class="w-20 h-20 rounded-full bg-brand-gold/10 text-brand-gold border border-brand-gold/20 flex items-center justify-center mx-auto text-glow">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/>
                </svg>
            </div>
            
            <div class="space-y-2">
                <span class="text-xs uppercase tracking-[0.2em] text-brand-gold font-bold">Transformation Confirmed</span>
                <h2 class="font-serif text-3xl text-brand-text">Booking Confirmed & Paid!</h2>
                <p class="text-slate-500 font-light text-sm max-w-md mx-auto">
                    Your appointment is now <strong>confirmed</strong>. An email notification with your attached PDF receipt has been sent to your address.
                </p>
            </div>

            <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl max-w-sm mx-auto text-xs text-left space-y-1">
                <div><span class="text-slate-400 font-semibold uppercase">Booking Ref:</span> <span class="font-bold text-slate-700" x-text="'#BKG-' + successBookingId"></span></div>
                <div><span class="text-slate-400 font-semibold uppercase">Scheduled Coordinate:</span> <span class="font-bold text-slate-700" x-text="bookingData.date + ' @ ' + bookingData.slot"></span></div>
                <div><span class="text-slate-400 font-semibold uppercase">Status:</span> <span class="font-bold text-brand-gold">Confirmed & Paid</span></div>
            </div>

            <div class="pt-4 flex flex-col md:flex-row items-center justify-center gap-4">
                <a :href="'/booking/receipt/' + successBookingId" class="px-6 py-3 rounded-full border border-brand-gold text-brand-gold text-xs font-semibold tracking-wider uppercase hover:bg-brand-gold hover:text-white transition-colors">
                    Download PDF Receipt
                </a>
                <a href="/dashboard" class="px-8 py-3 rounded-full bg-gradient-to-r from-brand-teal to-brand-red text-white text-xs font-semibold tracking-wider uppercase shadow-md shadow-brand-red/20">
                    Go to Portal Dashboard
                </a>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="flex items-center justify-between pt-8 border-t border-slate-100 mt-8" x-show="step <= 4">
            <button 
                @click="prevStep()" 
                x-show="step > 1"
                class="px-6 py-2.5 rounded-full border border-slate-200 text-slate-500 text-xs font-semibold uppercase tracking-wider hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed"
                :disabled="transitioning"
            >
                Back
            </button>
            <div x-show="step === 1"></div> <!-- spacer -->

            <button 
                @click="nextStep()" 
                class="px-6 py-2.5 rounded-full bg-brand-gold text-white text-xs font-semibold uppercase tracking-wider shadow-md hover:opacity-95 transition-opacity disabled:opacity-50 disabled:cursor-not-allowed inline-flex items-center"
                :disabled="transitioning"
            >
                <svg x-show="transitioning" class="w-4 h-4 animate-pulse inline-block mr-2 text-white" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2.5s-4 5-4 10.5c0 3 2 6 4 7 2-1 4-4 4-7 0-5.5-4-10.5-4-10.5z M2 14c0 3 2.5 5 4.5 5.5 1.5-3 3-5 3-7s-3-5.5-5-6c-2 2-2.5 5.5-2.5 7.5z M22 14c0 3-2.5 5-4.5 5.5-1.5-3-3-5-3-7s3-5.5 5-6c2 2 2.5 5.5 2.5 7.5z" />
                </svg>
                <span x-text="step === 4 ? 'Confirm & Book' : 'Continue'"></span>
            </button>
        </div>
    </div>
</section>

<!-- Razorpay Web Checkout SDK -->
<script nonce="<?= CSP_NONCE ?>" src="https://checkout.razorpay.com/v1/checkout.js"></script>

<!-- Alpine.js Application Logic -->
<script nonce="<?= CSP_NONCE ?>">
function bookingWizard() {
    return {
        step: 1,
        maxStepReached: 1,
        transitioning: false,
        services: <?= json_encode($services) ?>,
        modes: <?= json_encode($modes) ?>,
        slots: [],
        selectedService: null,
        errorMessage: '',
        successBookingId: null,
        bookingData: {
            service_id: '',
            mode: '',
            date: '',
            slot: '',
            name: '<?= htmlspecialchars($sessionUser['name']) ?>',
            email: '<?= htmlspecialchars($sessionUser['email']) ?>',
            phone: '<?= htmlspecialchars($sessionUser['phone']) ?>',
            city: '<?= htmlspecialchars($sessionUser['city']) ?>',
            intake: {}
        },

        init() {
            // Pre-fill service from URL if passed
            const urlParams = new URLSearchParams(window.location.search);
            const srvId = urlParams.get('service_id');
            if (srvId) {
                this.bookingData.service_id = parseInt(srvId, 10);
                this.onServiceChange();
                // Advance step slightly
                this.step = 2;
                this.maxStepReached = 2;
            }
        },

        onServiceChange() {
            const srv = this.services.find(s => parseInt(s.id, 10) === parseInt(this.bookingData.service_id, 10));
            this.selectedService = srv || null;
            this.bookingData.slot = '';
            this.bookingData.intake = {};
            this.slots = [];
        },

        getStepLabel(s) {
            const labels = {
                1: 'Service',
                2: 'Schedule',
                3: 'Intake',
                4: 'Review'
            };
            return labels[s];
        },

        goToStep(s) {
            if (s <= this.maxStepReached && !this.transitioning) {
                this.transitioning = true;
                setTimeout(() => {
                    this.step = s;
                    this.transitioning = false;
                }, 400);
            }
        },

        prevStep() {
            if (this.step > 1 && !this.transitioning) {
                this.transitioning = true;
                setTimeout(() => {
                    this.step--;
                    this.transitioning = false;
                }, 400);
            }
        },

        async nextStep() {
            if (this.transitioning) return;
            this.errorMessage = '';

            // Step 1 Validation
            if (this.step === 1) {
                if (!this.bookingData.service_id) {
                    alert('Please select an alignment pathway.');
                    return;
                }
                if (!this.bookingData.mode) {
                    alert('Please select a session mode.');
                    return;
                }
                this.transitioning = true;
                setTimeout(() => {
                    this.step = 2;
                    this.maxStepReached = Math.max(this.maxStepReached, 2);
                    this.transitioning = false;
                }, 400);
                return;
            }

            // Step 2 Validation
            if (this.step === 2) {
                if (!this.bookingData.date) {
                    alert('Please select a session date.');
                    return;
                }
                if (!this.bookingData.slot) {
                    alert('Please select a time slot.');
                    return;
                }
                this.transitioning = true;
                setTimeout(() => {
                    this.step = 3;
                    this.maxStepReached = Math.max(this.maxStepReached, 3);
                    this.transitioning = false;
                }, 400);
                return;
            }

            // Step 3 Validation
            if (this.step === 3) {
                if (!this.bookingData.name || !this.bookingData.email) {
                    alert('Name and Email are required.');
                    return;
                }
                this.transitioning = true;
                setTimeout(() => {
                    this.step = 4;
                    this.maxStepReached = Math.max(this.maxStepReached, 4);
                    this.transitioning = false;
                }, 400);
                return;
            }

            // Step 4 Validation & Submission (Razorpay Trigger)
            if (this.step === 4) {
                try {
                    const response = await fetch('/api/book', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                        },
                        body: JSON.stringify(this.bookingData)
                    });

                    const resData = await response.json();

                    if (!response.ok) {
                        this.errorMessage = resData.error || 'Server error occurred.';
                        return;
                    }

                    if (resData.success && resData.razorpay_payload) {
                        const payload = resData.razorpay_payload;

                        // Check if order ID is mock sandbox
                        if (payload.order_id.startsWith('order_mock_')) {
                            const confirmPayment = confirm("This is a simulated Razorpay sandbox transaction. Proceed to confirm payment?");
                            if (!confirmPayment) {
                                this.errorMessage = "Payment mock checkout cancelled.";
                                return;
                            }

                            const verifyRes = await fetch('/api/verify-payment', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                                },
                                body: JSON.stringify({
                                    booking_id: resData.booking_id,
                                    razorpay_payment_id: 'pay_mock_' + Math.random().toString(36).substring(2, 9),
                                    razorpay_order_id: payload.order_id,
                                    razorpay_signature: 'mock_signature_bypass'
                                })
                            });

                            const verifyData = await verifyRes.json();
                            if (verifyData.success) {
                                this.successBookingId = resData.booking_id;
                                this.step = 5;
                                this.maxStepReached = 5;
                            } else {
                                this.errorMessage = verifyData.error || 'Mock Payment verification failed.';
                            }
                            return;
                        }

                        // Trigger Real Razorpay Checkout modal
                        const options = {
                            ...payload,
                            handler: async (paymentRes) => {
                                const verifyRes = await fetch('/api/verify-payment', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                                    },
                                    body: JSON.stringify({
                                        booking_id: resData.booking_id,
                                        razorpay_payment_id: paymentRes.razorpay_payment_id,
                                        razorpay_order_id: paymentRes.razorpay_order_id,
                                        razorpay_signature: paymentRes.razorpay_signature
                                    })
                                });

                                const verifyData = await verifyRes.json();
                                if (verifyData.success) {
                                    this.successBookingId = resData.booking_id;
                                    this.step = 5;
                                    this.maxStepReached = 5;
                                } else {
                                    this.errorMessage = verifyData.error || 'Payment verification failed.';
                                }
                            },
                            modal: {
                                ondismiss: () => {
                                    this.errorMessage = 'Payment checkout was dismissed.';
                                }
                            }
                        };

                        const rzp = new Razorpay(options);
                        rzp.open();
                    }
                } catch (e) {
                    this.errorMessage = 'Network error. Failed to finalize booking.';
                }
            }
        },

        async fetchSlots() {
            this.bookingData.slot = '';
            this.slots = [];
            if (!this.bookingData.date || !this.bookingData.service_id) {
                return;
            }

            try {
                const response = await fetch(`/api/available-slots?date=${this.bookingData.date}&service_id=${this.bookingData.service_id}`);
                const data = await response.json();
                if (data.slots) {
                    this.slots = data.slots;
                }
            } catch (e) {
                console.error('Failed to load slots.', e);
            }
        }
    };
}
</script>
