<?php $activePage = 'bookings'; ?>
<div class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 lg:grid-cols-12 gap-8">
    
    <!-- Left Navigation Sidebar -->
    <div class="lg:col-span-3">
        <div class="glass bg-white p-6 rounded-3xl border border-brand-gold/15 shadow-sm space-y-6">
            <div class="space-y-1">
                <span class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Seeker Profile</span>
                <h4 class="font-serif text-lg text-brand-text font-bold"><?= htmlspecialchars($_SESSION['user_name']) ?></h4>
            </div>
            
            <nav class="flex flex-col space-y-2 text-sm text-slate-600">
                <a href="/dashboard" class="px-4 py-2.5 rounded-xl hover:bg-slate-50 hover:text-brand-gold transition-colors flex items-center space-x-2 font-medium">
                    <span>Dashboard Coordinates</span>
                </a>
                <a href="/dashboard/bookings" class="px-4 py-2.5 rounded-xl flex items-center space-x-2 font-medium bg-brand-gold/10 text-brand-gold font-bold border border-brand-gold/10">
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
            <span class="text-xs uppercase tracking-[0.2em] text-brand-gold font-bold">Seeker Ledger</span>
            <h1 class="font-serif text-4xl text-brand-text">Booking Interactions</h1>
        </div>

        <?php if (!empty($bookings)): ?>
            <div class="glass bg-white rounded-3xl border border-brand-gold/15 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 border-b border-brand-gold/10 text-[10px] uppercase font-bold text-slate-400 tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Booking Ref</th>
                                <th class="px-6 py-4">Pathway Alignment</th>
                                <th class="px-6 py-4">Session Date</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Resonance Fee</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-light">
                            <?php foreach ($bookings as $bkg): ?>
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 font-mono text-xs font-semibold text-slate-700">#BKG-<?= (int)$bkg['id'] ?></td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-700"><?= htmlspecialchars($bkg['title']) ?></div>
                                        <div class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold"><?= htmlspecialchars($bkg['consultation_mode']) ?></div>
                                    </td>
                                    <td class="px-6 py-4 text-xs"><?= date('d M Y @ H:i', strtotime($bkg['scheduled_at'])) ?></td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider border <?= $bkg['status'] === 'confirmed' ? 'bg-green-50 border-green-200 text-green-600' : 'bg-brand-gold/10 border-brand-gold/15 text-brand-gold' ?>">
                                            <?= htmlspecialchars($bkg['status']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-brand-gold">₹<?= number_format((float)$bkg['price_inr'], 2) ?></td>
                                    <td class="px-6 py-4 text-right space-x-3 text-xs">
                                        <?php if ($bkg['status'] === 'confirmed'): ?>
                                            <a href="/booking/receipt/<?= (int)$bkg['id'] ?>" class="text-brand-gold hover:text-brand-pink font-semibold">
                                                Invoice PDF
                                            </a>
                                            <?php if (!empty($bkg['report_path']) && file_exists(dirname(__DIR__, 2) . $bkg['report_path'])): ?>
                                                <a href="<?= htmlspecialchars($bkg['report_path']) ?>" download class="text-brand-purple hover:text-brand-pink font-semibold">
                                                    Report
                                                </a>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-slate-400 font-light italic">Pending Payment</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-16 border border-dashed border-slate-200 rounded-3xl bg-white/50 glass">
                <span class="text-brand-gold font-bold text-glow text-3xl font-serif">No Bookings</span>
                <p class="text-xs text-slate-500 font-light max-w-xs mx-auto mt-2">You do not have any transaction history yet.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
