<?php $adminPage = 'profile'; ?>
<div class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 lg:grid-cols-12 gap-8" 
     x-data="{ 
        days: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
        schedule: <?= json_encode($availability) ?>,
        addSlot(day) {
            if (!this.schedule[day]) {
                this.schedule[day] = [];
            }
            this.schedule[day].push('09:00-12:00');
        },
        removeSlot(day, index) {
            this.schedule[day].splice(index, 1);
        },
        serializeSchedule() {
            return JSON.stringify(this.schedule);
        }
     }">
    
    <!-- Admin Sidebar Navigation -->
    <div class="lg:col-span-3">
        <div class="glass bg-[#11101A] p-6 rounded-3xl border border-brand-gold/15 shadow-sm space-y-6 text-white">
            <div class="space-y-1">
                <span class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">CMS Manager</span>
                <h4 class="font-serif text-lg text-brand-gold font-bold">Acharya Admin</h4>
            </div>
            
            <nav class="flex flex-col space-y-2 text-sm text-slate-300">
                <a href="/admin" class="px-4 py-2.5 rounded-xl hover:bg-white/5 hover:text-brand-gold transition-colors flex items-center space-x-2 font-medium">
                    <span>Overview Stats</span>
                </a>
                <a href="/admin/bookings" class="px-4 py-2.5 rounded-xl hover:bg-white/5 hover:text-brand-gold transition-colors flex items-center space-x-2 font-medium">
                    <span>Manage Bookings</span>
                </a>
                <a href="/admin/services" class="px-4 py-2.5 rounded-xl hover:bg-white/5 hover:text-brand-gold transition-colors flex items-center space-x-2 font-medium">
                    <span>Consultation Catalog</span>
                </a>
                <a href="/admin/profile" class="px-4 py-2.5 rounded-xl flex items-center space-x-2 font-medium bg-brand-gold/10 text-brand-gold border border-brand-gold/15">
                    <span>Consultant Profiles</span>
                </a>
                <a href="/admin/marketing" class="px-4 py-2.5 rounded-xl hover:bg-white/5 hover:text-brand-gold transition-colors flex items-center space-x-2 font-medium">
                    <span>Marketing Hub</span>
                </a>
                <a href="/admin/logout" class="px-4 py-2.5 rounded-xl hover:bg-red-500/10 text-red-400 transition-colors flex items-center space-x-2 font-medium">
                    <span>Exit CMS</span>
                </a>
            </nav>
        </div>
    </div>

    <!-- Main Workspace -->
    <div class="lg:col-span-9 space-y-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-6">
            <div class="space-y-1">
                <span class="text-xs uppercase tracking-[0.2em] text-brand-gold font-bold">Profile Coordinates</span>
                <h1 class="font-serif text-4xl text-brand-text">Dynamic Configuration Manager</h1>
            </div>
        </div>

        <?php if (!empty($success)): ?>
            <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 text-xs">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <form action="/admin/profile" method="POST" class="space-y-8" @submit="$refs.weeklyScheduleInput.value = serializeSchedule()">
            <!-- Hidden Input for weekly schedule JSON payload -->
            <input type="hidden" name="weekly_availability" x-ref="weeklyScheduleInput">

            <!-- Consultant Basic Bio Details -->
            <div class="bg-white p-8 rounded-3xl border border-brand-gold/15 shadow-sm space-y-6">
                <h3 class="font-serif text-xl text-brand-text border-b border-slate-50 pb-2">Consultant Meta</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label class="text-[10px] uppercase tracking-wider text-slate-400 font-bold block">Consultant Name</label>
                        <input type="text" name="name" required value="<?= htmlspecialchars($profile['name']) ?>" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] uppercase tracking-wider text-slate-400 font-bold block">Photo Anchor Link</label>
                        <input type="text" name="photo_url" required value="<?= htmlspecialchars($profile['photo_url']) ?>" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label class="text-[10px] uppercase tracking-wider text-slate-400 font-bold block">Tagline (English)</label>
                        <input type="text" name="tagline_en" value="<?= htmlspecialchars($profile['tagline_en'] ?? '') ?>" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] uppercase tracking-wider text-slate-400 font-bold block">Tagline (Hindi)</label>
                        <input type="text" name="tagline_hi" value="<?= htmlspecialchars($profile['tagline_hi'] ?? '') ?>" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] uppercase tracking-wider text-slate-400 font-bold block">Biography Narrative (English)</label>
                    <textarea name="bio_en" rows="4" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none"><?= htmlspecialchars($profile['bio_en'] ?? '') ?></textarea>
                </div>
                <div class="space-y-1.5">
                    <label class="text-[10px] uppercase tracking-wider text-slate-400 font-bold block">Biography Narrative (Hindi)</label>
                    <textarea name="bio_hi" rows="4" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none"><?= htmlspecialchars($profile['bio_hi'] ?? '') ?></textarea>
                </div>
            </div>

            <!-- Weekly Schedule Repeater Matrix -->
            <div class="bg-white p-8 rounded-3xl border border-brand-gold/15 shadow-sm space-y-6">
                <div class="space-y-1">
                    <h3 class="font-serif text-xl text-brand-text">Active Schedule Ranges Repeater</h3>
                    <p class="text-xs text-slate-400 font-light">Set hours for alignments. Ranges must follow the format `HH:MM-HH:MM` (e.g. `10:00-12:00` or `14:00-16:00`).</p>
                </div>

                <div class="space-y-6 divide-y divide-slate-100">
                    <template x-for="day in days" :key="day">
                        <div class="pt-6 flex flex-col md:flex-row md:items-start justify-between gap-4">
                            <div class="w-full md:w-1/4 pt-1.5">
                                <span class="font-serif font-bold text-slate-700" x-text="day"></span>
                            </div>
                            
                            <div class="w-full md:w-3/4 space-y-3">
                                <div class="flex flex-wrap gap-3">
                                    <template x-for="(slot, index) in schedule[day] || []" :key="index">
                                        <div class="inline-flex items-center space-x-2 bg-slate-50 border border-slate-200 px-3 py-1.5 rounded-xl">
                                            <input type="text" x-model="schedule[day][index]" placeholder="10:00-12:00" 
                                                   class="bg-transparent font-mono text-xs w-24 text-center focus:outline-none text-slate-700">
                                            <button type="button" @click="removeSlot(day, index)" class="text-red-500 hover:text-red-700 text-xs font-bold font-mono">×</button>
                                        </div>
                                    </template>

                                    <button type="button" @click="addSlot(day)" class="px-3 py-1.5 rounded-xl border border-dashed border-brand-gold/30 hover:border-brand-gold text-brand-gold text-xs font-semibold">
                                        + Add Range
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <button type="submit" class="px-6 py-3.5 rounded-full bg-gradient-to-r from-brand-purple to-brand-pink text-white text-xs font-semibold tracking-wider uppercase shadow-md">
                Deploy Settings Configurations
            </button>
        </form>
    </div>
</div>
