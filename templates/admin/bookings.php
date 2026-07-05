<?php $adminPage = 'bookings'; ?>
<div class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 lg:grid-cols-12 gap-8" x-data="{ openUploadModal: false, selectedBookingId: null }">
    
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
                <a href="/admin/bookings" class="px-4 py-2.5 rounded-xl flex items-center space-x-2 font-medium bg-brand-gold/10 text-brand-gold border border-brand-gold/15">
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
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-6">
            <div class="space-y-1">
                <span class="text-xs uppercase tracking-[0.2em] text-brand-gold font-bold">Appointment Pipelines</span>
                <h1 class="font-serif text-4xl text-brand-text">Booking Records Manager</h1>
            </div>
            <div>
                <a href="/admin/bookings/export?status=<?= urlencode($filterStatus) ?>" class="px-5 py-2.5 rounded-full bg-brand-gold text-white text-xs font-semibold tracking-wider uppercase inline-flex items-center space-x-2 shadow-md">
                    <span>Export CSV</span>
                </a>
            </div>
        </div>

        <!-- Filter bar -->
        <form method="GET" action="/admin/bookings" class="flex flex-col md:flex-row gap-4 items-center bg-white p-4 rounded-2xl border border-brand-gold/10 shadow-sm text-sm">
            <div class="w-full md:w-1/3 space-y-1">
                <label class="text-[10px] uppercase font-bold text-slate-400 block">Status Filter</label>
                <select name="status" onchange="this.form.submit()" class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 focus:outline-none text-slate-700">
                    <option value="">All Statuses</option>
                    <option value="pending" <?= $filterStatus === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="confirmed" <?= $filterStatus === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                    <option value="completed" <?= $filterStatus === 'completed' ? 'selected' : '' ?>>Completed</option>
                    <option value="cancelled" <?= $filterStatus === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                </select>
            </div>
            <div class="w-full md:w-2/3 space-y-1">
                <label class="text-[10px] uppercase font-bold text-slate-400 block">Search Seeker or Service</label>
                <div class="flex gap-2">
                    <input type="text" name="search" value="<?= htmlspecialchars($searchQuery) ?>" placeholder="Search by name, email, or service title..." 
                           class="w-full px-4 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-700 placeholder-slate-400 focus:outline-none">
                    <button type="submit" class="px-5 py-2 bg-slate-800 text-white rounded-xl text-xs uppercase font-semibold">Search</button>
                </div>
            </div>
        </form>

        <?php if (!empty($bookings)): ?>
            <div class="glass bg-white rounded-3xl border border-brand-gold/15 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 border-b border-brand-gold/10 text-[10px] uppercase font-bold text-slate-400 tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Ref</th>
                                <th class="px-6 py-4">Seeker Info</th>
                                <th class="px-6 py-4">Alignment Target</th>
                                <th class="px-6 py-4">Date & Time</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Operations</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-light">
                            <?php foreach ($bookings as $bkg): ?>
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 font-mono text-xs font-semibold text-slate-700">#BKG-<?= (int)$bkg['id'] ?></td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-800"><?= htmlspecialchars($bkg['seeker_name']) ?></div>
                                        <div class="text-xs text-slate-400 font-light"><?= htmlspecialchars($bkg['seeker_email']) ?></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-700"><?= htmlspecialchars($bkg['service_title']) ?></div>
                                        <div class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold"><?= htmlspecialchars($bkg['consultation_mode']) ?></div>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-mono"><?= date('d M Y @ H:i', strtotime($bkg['scheduled_at'])) ?></td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider border <?= $bkg['status'] === 'confirmed' ? 'bg-green-50 border-green-200 text-green-600' : ($bkg['status'] === 'cancelled' ? 'bg-red-50 border-red-200 text-red-600' : 'bg-brand-gold/10 border-brand-gold/15 text-brand-gold') ?>">
                                            <?= htmlspecialchars($bkg['status']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2 text-xs">
                                        <button @click="selectedBookingId = <?= (int)$bkg['id'] ?>; openUploadModal = true" class="text-brand-purple hover:text-brand-pink font-semibold">
                                            Upload PDF
                                        </button>
                                        <?php if (!empty($bkg['report_path'])): ?>
                                            <a href="<?= \App\Helpers\UrlSigner::generateSignedUrl((int)$bkg['id']) ?>" download class="text-brand-gold hover:text-brand-pink font-semibold">
                                                Download Report
                                            </a>
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
                <span class="text-brand-gold font-bold text-glow text-3xl font-serif">No Bookings Found</span>
                <p class="text-xs text-slate-500 font-light max-w-xs mx-auto mt-2">Adjust your search parameters or select a different pipeline status.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Upload PDF Report Modal -->
    <div x-show="openUploadModal" class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-6" style="display: none;">
        <div class="bg-white rounded-3xl max-w-md w-full p-8 border border-brand-gold/25 shadow-2xl relative space-y-6">
            <div class="space-y-1">
                <h3 class="font-serif text-2xl text-brand-text font-bold">Upload Cosmic Report</h3>
                <p class="text-xs text-slate-400 font-light">Select Astro / Vastu audit PDF documents to deploy to the seeker dashboard.</p>
            </div>

            <form action="/admin/bookings/upload-report" method="POST" enctype="multipart/form-data" class="space-y-4">
                <?= \App\Helpers\Csrf::field() ?>
                <input type="hidden" name="booking_id" :value="selectedBookingId">
                
                <div class="border-2 border-dashed border-slate-200 rounded-2xl p-6 text-center hover:border-brand-gold/40 transition-colors">
                    <input type="file" name="report_file" accept=".pdf" required class="mx-auto block text-xs text-slate-500">
                </div>

                <div class="flex space-x-3 pt-2">
                    <button type="button" @click="openUploadModal = false" class="w-1/2 py-2.5 rounded-full border border-slate-200 text-slate-500 text-xs font-semibold uppercase tracking-wider">
                        Cancel
                    </button>
                    <button type="submit" class="w-1/2 py-2.5 rounded-full bg-gradient-to-r from-brand-purple to-brand-pink text-white text-xs font-semibold uppercase tracking-wider shadow-md">
                        Deploy Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
