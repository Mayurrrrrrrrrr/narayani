<?php $adminPage = 'dashboard'; ?>
<div class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 lg:grid-cols-12 gap-8">
    
    <!-- Admin Sidebar Navigation -->
    <div class="lg:col-span-3">
        <div class="glass bg-[#11101A] p-6 rounded-3xl border border-brand-gold/15 shadow-sm space-y-6 text-white">
            <div class="space-y-1">
                <span class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">CMS Manager</span>
                <h4 class="font-serif text-lg text-brand-gold font-bold">Acharya Admin</h4>
            </div>
            
            <nav class="flex flex-col space-y-2 text-sm text-slate-300">
                <a href="/admin" class="px-4 py-2.5 rounded-xl flex items-center space-x-2 font-medium bg-brand-gold/10 text-brand-gold border border-brand-gold/15">
                    <span>Overview Stats</span>
                </a>
                <a href="/admin/bookings" class="px-4 py-2.5 rounded-xl hover:bg-white/5 hover:text-brand-gold transition-colors flex items-center space-x-2 font-medium">
                    <span>Manage Bookings</span>
                </a>
                <a href="/admin/services" class="px-4 py-2.5 rounded-xl hover:bg-white/5 hover:text-brand-gold transition-colors flex items-center space-x-2 font-medium">
                    <span>Consultation Catalog</span>
                </a>
                <a href="/admin/profile" class="px-4 py-2.5 rounded-xl hover:bg-white/5 hover:text-brand-gold transition-colors flex items-center space-x-2 font-medium">
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
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1">
                <span class="text-xs uppercase tracking-[0.2em] text-brand-gold font-bold">Narayani CMS Control</span>
                <h1 class="font-serif text-4xl text-brand-text">Administrative Hub</h1>
            </div>
            <div class="flex items-center space-x-3 text-xs">
                <span class="px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 font-mono">system: active</span>
            </div>
        </div>

        <!-- Metrics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="glass bg-white p-6 rounded-2xl border border-brand-gold/15 shadow-sm text-center">
                <span class="text-[10px] uppercase tracking-wider text-slate-400 block font-bold">Monthly Earnings</span>
                <span class="text-3xl font-serif text-brand-gold font-bold mt-1 block">₹<?= number_format((float)$earnings, 2) ?></span>
            </div>
            <div class="glass bg-white p-6 rounded-2xl border border-brand-gold/15 shadow-sm text-center">
                <span class="text-[10px] uppercase tracking-wider text-slate-400 block font-bold">Active Bookings</span>
                <span class="text-3xl font-serif text-brand-gold font-bold mt-1 block"><?= (int)$activeBookingsCount ?></span>
            </div>
            <div class="glass bg-white p-6 rounded-2xl border border-brand-gold/15 shadow-sm text-center">
                <span class="text-[10px] uppercase tracking-wider text-slate-400 block font-bold">Total Seekers</span>
                <span class="text-3xl font-serif text-brand-gold font-bold mt-1 block"><?= (int)$seekersCount ?></span>
            </div>
            <div class="glass bg-white p-6 rounded-2xl border border-brand-gold/15 shadow-sm text-center">
                <span class="text-[10px] uppercase tracking-wider text-slate-400 block font-bold">Open Leads</span>
                <span class="text-3xl font-serif text-brand-purple font-bold mt-1 block"><?= (int)$leadsCount ?></span>
            </div>
        </div>

        <!-- Booking Trends Visual Chart -->
        <div class="glass bg-white p-8 rounded-3xl border border-brand-gold/15 shadow-sm space-y-6">
            <div class="space-y-1">
                <h3 class="font-serif text-xl text-brand-text font-bold">Booking Alignments Activity</h3>
                <p class="text-xs text-slate-400 font-light">Recent transaction trends and schedules pipeline</p>
            </div>
            
            <!-- Custom dynamic SVG Line Chart plotting booking history -->
            <div class="h-64 relative flex items-end">
                <?php if (!empty($chartData)): ?>
                    <svg class="w-full h-full" viewBox="0 0 500 200" preserveAspectRatio="none">
                        <?php 
                            $max = max(array_column($chartData, 'count')) ?: 1;
                            $points = [];
                            $widthStep = 500 / (count($chartData) - 1 ?: 1);
                            foreach ($chartData as $i => $data) {
                                $x = $i * $widthStep;
                                $y = 200 - ($data['count'] / $max * 150) - 25;
                                $points[] = "$x,$y";
                            }
                            $polyline = implode(' ', $points);
                        ?>
                        <!-- Grid lines -->
                        <line x1="0" y1="175" x2="500" y2="175" stroke="#E2E8F0" stroke-width="1" stroke-dasharray="4 4" />
                        <line x1="0" y1="100" x2="500" y2="100" stroke="#E2E8F0" stroke-width="1" stroke-dasharray="4 4" />
                        <line x1="0" y1="25" x2="500" y2="25" stroke="#E2E8F0" stroke-width="1" stroke-dasharray="4 4" />
                        
                        <!-- Line path -->
                        <polyline fill="none" stroke="url(#gradient-accent)" stroke-width="3" points="<?= $polyline ?>" />
                        
                        <!-- Accent Gradient -->
                        <defs>
                            <linearGradient id="gradient-accent" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" stop-color="#7B2FF7" />
                                <stop offset="100%" stop-color="#FF2E9A" />
                            </linearGradient>
                        </defs>

                        <!-- Plot Circles -->
                        <?php foreach ($chartData as $i => $data): 
                            $x = $i * $widthStep;
                            $y = 200 - ($data['count'] / $max * 150) - 25;
                        ?>
                            <circle cx="<?= $x ?>" cy="<?= $y ?>" r="5" fill="#FF2E9A" stroke="#FFFFFF" stroke-width="2" />
                        <?php endforeach; ?>
                    </svg>
                <?php endif; ?>
            </div>
            
            <div class="flex justify-between text-[10px] uppercase font-bold text-slate-400 tracking-wider pt-2 border-t border-slate-50">
                <?php foreach ($chartData as $data): ?>
                    <span><?= htmlspecialchars($data['date']) ?> (<?= (int)$data['count'] ?>)</span>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Sitemap Paths Overview -->
        <div class="glass bg-[#FCFAF7] p-8 rounded-3xl border border-brand-gold/15 space-y-4">
            <h3 class="font-serif text-xl text-brand-text border-b border-brand-gold/10 pb-4">Operational Sitemap Paths</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-xs font-mono text-brand-text">
                <div class="p-3 bg-white rounded border border-brand-gold/15 flex justify-between shadow-sm">
                    <span>GET /admin</span>
                    <span class="text-brand-pink font-semibold">Admin@dashboard</span>
                </div>
                <div class="p-3 bg-white rounded border border-brand-gold/15 flex justify-between shadow-sm">
                    <span>GET /admin/bookings</span>
                    <span class="text-brand-pink font-semibold">Admin@bookings</span>
                </div>
                <div class="p-3 bg-white rounded border border-brand-gold/15 flex justify-between shadow-sm">
                    <span>GET /admin/services</span>
                    <span class="text-brand-pink font-semibold">Admin@services</span>
                </div>
                <div class="p-3 bg-white rounded border border-brand-gold/15 flex justify-between shadow-sm">
                    <span>GET /admin/profile</span>
                    <span class="text-brand-pink font-semibold">Admin@profile</span>
                </div>
                <div class="p-3 bg-white rounded border border-brand-gold/15 flex justify-between shadow-sm">
                    <span>GET /admin/marketing</span>
                    <span class="text-brand-pink font-semibold">Admin@marketing</span>
                </div>
            </div>
        </div>
    </div>
</div>
