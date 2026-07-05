<?php $activePage = 'home'; ?>
<div class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 lg:grid-cols-12 gap-8">
    
    <!-- Left Navigation Sidebar -->
    <div class="lg:col-span-3">
        <div class="glass bg-white p-6 rounded-3xl border border-brand-gold/15 shadow-sm space-y-6">
            <div class="space-y-1">
                <span class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Seeker Profile</span>
                <h4 class="font-serif text-lg text-brand-text font-bold"><?= htmlspecialchars($userName) ?></h4>
            </div>
            
            <nav class="flex flex-col space-y-2 text-sm text-slate-600">
                <a href="/dashboard" class="px-4 py-2.5 rounded-xl flex items-center space-x-2 font-medium bg-brand-gold/10 text-brand-gold font-bold border border-brand-gold/10">
                    <span>Dashboard Coordinates</span>
                </a>
                <a href="/dashboard/bookings" class="px-4 py-2.5 rounded-xl hover:bg-slate-50 hover:text-brand-gold transition-colors flex items-center space-x-2 font-medium">
                    <span>Booking Interactions</span>
                </a>
                <a href="/dashboard/profile" class="px-4 py-2.5 rounded-xl hover:bg-slate-50 hover:text-brand-gold transition-colors flex items-center space-x-2 font-medium">
                    <span>Edit Seeker Specs</span>
                </a>
                <a href="/logout" class="px-4 py-2.5 rounded-xl hover:bg-red-50 text-red-500 transition-colors flex items-center space-x-2 font-medium">
                    <span>Exit Portal</span>
                </a>
            </nav>
        </div>
    </div>

    <!-- Right Content Area -->
    <div class="lg:col-span-9 space-y-8">
        <div class="space-y-2">
            <span class="text-xs uppercase tracking-[0.2em] text-brand-gold font-bold">Portal Coordinates</span>
            <h1 class="font-serif text-4xl text-brand-text">Active Alignment Schedules</h1>
        </div>

        <?php if (!empty($bookings)): ?>
            <!-- Highlight Upcoming Session (the closest one) -->
            <?php 
                $next = $bookings[0]; 
                $intake = json_decode($next['intake_data'] ?? '[]', true);
                $isOnline = str_contains(strtolower($next['consultation_mode']), 'video') || str_contains(strtolower($next['consultation_mode']), 'audio') || str_contains(strtolower($next['consultation_mode']), 'call');
                $meetingLink = 'https://meet.google.com/vrw-mock-alignment';
                $address = $intake['address'] ?? 'Suite 108, Sri Yantra Enclave, Bangalore';
                $mapsLink = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($address);
            ?>
            <div class="glass bg-white p-8 md:p-10 rounded-3xl border-2 border-brand-gold/20 shadow-md space-y-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 px-4 py-1.5 bg-brand-gold text-white text-[9px] uppercase tracking-widest font-semibold rounded-bl-2xl">
                    Immediate Coordinates
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                    <div class="space-y-4">
                        <div class="space-y-1">
                            <span class="text-[10px] uppercase tracking-wider text-brand-gold font-bold"><?= htmlspecialchars($next['consultation_mode']) ?></span>
                            <h2 class="font-serif text-2xl md:text-3xl text-brand-text font-bold"><?= htmlspecialchars($next['title']) ?></h2>
                            <p class="text-xs text-slate-400 font-light">Scheduled for: <?= date('d M Y @ H:i', strtotime($next['scheduled_at'])) ?></p>
                        </div>

                        <!-- Sticky Meeting Actions -->
                        <div class="pt-2">
                            <?php if ($isOnline): ?>
                                <a href="<?= $meetingLink ?>" target="_blank" class="px-6 py-3 rounded-full bg-gradient-to-r from-brand-purple to-brand-pink text-white text-xs font-semibold tracking-wider uppercase inline-flex items-center space-x-2 shadow-md shadow-brand-pink/20">
                                    <span>Enter Virtual Portal</span>
                                </a>
                            <?php else: ?>
                                <a href="<?= $mapsLink ?>" target="_blank" class="px-6 py-3 rounded-full border border-brand-gold text-brand-gold text-xs font-semibold tracking-wider uppercase inline-flex items-center space-x-2 hover:bg-brand-gold hover:text-white transition-colors">
                                    <span>Navigate to Address</span>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Precision Countdown Block -->
                    <div class="bg-slate-50 border border-slate-100 p-6 rounded-2xl flex flex-col items-center justify-center space-y-4" x-data="countdown('<?= $next['scheduled_at'] ?>')">
                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Session Alignment Begins In:</span>
                        
                        <div class="flex space-x-3 text-center">
                            <div class="bg-white px-3 py-2 rounded-lg border border-slate-200 min-w-[50px]">
                                <span class="font-mono text-xl font-bold text-brand-text" x-text="timeLeft.days">00</span>
                                <span class="text-[8px] uppercase tracking-wide text-slate-400 block font-semibold">Days</span>
                            </div>
                            <div class="bg-white px-3 py-2 rounded-lg border border-slate-200 min-w-[50px]">
                                <span class="font-mono text-xl font-bold text-brand-text" x-text="timeLeft.hours">00</span>
                                <span class="text-[8px] uppercase tracking-wide text-slate-400 block font-semibold">Hours</span>
                            </div>
                            <div class="bg-white px-3 py-2 rounded-lg border border-slate-200 min-w-[50px]">
                                <span class="font-mono text-xl font-bold text-brand-text" x-text="timeLeft.mins">00</span>
                                <span class="text-[8px] uppercase tracking-wide text-slate-400 block font-semibold">Mins</span>
                            </div>
                            <div class="bg-white px-3 py-2 rounded-lg border border-slate-200 min-w-[50px]">
                                <span class="font-mono text-xl font-bold text-brand-text animate-pulse" x-text="timeLeft.secs">00</span>
                                <span class="text-[8px] uppercase tracking-wide text-slate-400 block font-semibold">Secs</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- List of other upcoming schedules -->
            <?php if (count($bookings) > 1): ?>
                <div class="space-y-4">
                    <h3 class="font-serif text-xl text-brand-text border-b border-brand-gold/10 pb-2">Further Alignment Pipelines</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php foreach (array_slice($bookings, 1) as $bkg): ?>
                            <div class="glass bg-white p-6 rounded-2xl border border-brand-gold/10 shadow-sm flex flex-col justify-between">
                                <div class="space-y-2">
                                    <div class="flex items-start justify-between">
                                        <h4 class="font-serif text-lg text-brand-text font-bold"><?= htmlspecialchars($bkg['title']) ?></h4>
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-brand-gold/10 text-brand-gold border border-brand-gold/10">
                                            <?= htmlspecialchars($bkg['status']) ?>
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-500 font-light">Scheduled: <?= date('d M Y @ H:i', strtotime($bkg['scheduled_at'])) ?></p>
                                </div>
                                <div class="border-t border-slate-50 pt-4 mt-4 flex items-center justify-between">
                                    <span class="text-[10px] uppercase font-bold text-slate-400"><?= htmlspecialchars($bkg['consultation_mode']) ?></span>
                                    <a href="/booking/receipt/<?= (int)$bkg['id'] ?>" class="text-[10px] uppercase font-semibold text-brand-gold hover:text-brand-pink tracking-wider">
                                        Receipt PDF
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <!-- Fallback State -->
            <div class="text-center py-16 border border-dashed border-slate-200 rounded-3xl bg-white/50 glass">
                <span class="text-brand-gold font-bold text-glow text-3xl font-serif">Empty Cosmos</span>
                <p class="text-xs text-slate-500 font-light max-w-xs mx-auto mt-2">You currently have no active or scheduled alignment consultations. Ready to initiate?</p>
                <div class="pt-6">
                    <a href="/services" class="px-6 py-2.5 rounded-full bg-gradient-to-r from-brand-purple to-brand-pink text-white text-xs font-semibold tracking-wider uppercase inline-block shadow-md shadow-brand-pink/20">
                        Initiate Sacred Path
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <!-- Feedback Submission Pipeline -->
        <div class="glass bg-white p-8 rounded-3xl border border-brand-gold/15 shadow-sm space-y-6 mt-8">
            <div class="space-y-1">
                <span class="text-xs uppercase tracking-widest text-brand-gold font-bold">Feedback Coordinates</span>
                <h3 class="font-serif text-2xl text-brand-text font-bold">Share Your Resonance</h3>
                <p class="text-xs text-slate-400 font-light">Submit feedback on your completed alignments to guide other seekers.</p>
            </div>

            <?php if (isset($_SESSION['review_success'])): ?>
                <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 text-xs font-semibold">
                    <?= htmlspecialchars($_SESSION['review_success']); unset($_SESSION['review_success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['review_error'])): ?>
                <div class="p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-500 text-xs font-semibold">
                    <?= htmlspecialchars($_SESSION['review_error']); unset($_SESSION['review_error']); ?>
                </div>
            <?php endif; ?>

            <form action="/dashboard/review" method="POST" class="space-y-4">
                <?= \App\Helpers\Csrf::field() ?>
                <div class="space-y-2">
                    <label class="text-[10px] uppercase tracking-wider text-slate-400 font-bold block">Rate Alignment (Stars)</label>
                    <div class="flex items-center space-x-4">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <label class="flex items-center space-x-1 cursor-pointer">
                                <input type="radio" name="rating" value="<?= $i ?>" <?= $i === 5 ? 'checked' : '' ?> class="text-brand-gold focus:ring-brand-gold">
                                <span class="text-xs font-semibold text-slate-700 font-mono"><?= $i ?> ★</span>
                            </label>
                        <?php endfor; ?>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label for="content" class="text-[10px] uppercase tracking-wider text-slate-400 font-bold block">Feedback Message</label>
                    <textarea name="content" id="content" required rows="4" placeholder="Describe the energy corrections, spatial shifts, or astrology resonance you experienced..." 
                              class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:border-brand-gold focus:ring-1 focus:ring-brand-gold transition-colors"></textarea>
                </div>

                <button type="submit" class="px-6 py-2.5 rounded-full bg-gradient-to-r from-brand-purple to-brand-pink text-white text-xs font-semibold tracking-wider uppercase shadow-md hover:opacity-90 transition-opacity">
                    Deploy Feedback
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function countdown(scheduledAt) {
    return {
        targetDate: new Date(scheduledAt.replace(/-/g, "/")).getTime(),
        timeLeft: { days: '00', hours: '00', mins: '00', secs: '00' },
        
        init() {
            this.updateTime();
            setInterval(() => this.updateTime(), 1000);
        },
        
        updateTime() {
            const now = new Date().getTime();
            const distance = this.targetDate - now;
            
            if (distance < 0) {
                this.timeLeft = { days: '00', hours: '00', mins: '00', secs: '00' };
                return;
            }
            
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            this.timeLeft = {
                days: days.toString().padStart(2, '0'),
                hours: hours.toString().padStart(2, '0'),
                mins: minutes.toString().padStart(2, '0'),
                secs: seconds.toString().padStart(2, '0')
            };
        }
    };
}
</script>
