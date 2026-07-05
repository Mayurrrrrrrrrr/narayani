<?php $adminPage = 'services'; ?>
<div class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 lg:grid-cols-12 gap-8">
    
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
                <a href="/admin/services" class="px-4 py-2.5 rounded-xl flex items-center space-x-2 font-medium bg-brand-gold/10 text-brand-gold border border-brand-gold/15">
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
        <?php if ($action === 'create' || $action === 'edit'): ?>
            <!-- Create or Edit Form -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-6">
                <div class="space-y-1">
                    <span class="text-xs uppercase tracking-[0.2em] text-brand-gold font-bold">Catalog Management</span>
                    <h1 class="font-serif text-4xl text-brand-text"><?= $action === 'create' ? 'Create Cosmic Offering' : 'Edit Service Alignment' ?></h1>
                </div>
                <div>
                    <a href="/admin/services" class="px-5 py-2 rounded-full border border-slate-300 text-slate-600 text-xs font-semibold uppercase tracking-wider">
                        Back to Catalog
                    </a>
                </div>
            </div>

            <form action="<?= $action === 'create' ? '/admin/services/create' : '/admin/services/edit/' . (int)$service['id'] ?>" method="POST" class="bg-white p-8 rounded-3xl border border-brand-gold/15 shadow-sm space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label class="text-[10px] uppercase tracking-wider text-slate-400 font-bold block">Service Category</label>
                        <select name="category_id" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none">
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= (int)$cat['id'] ?>" <?= isset($service) && (int)$service['category_id'] === (int)$cat['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['name_en']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] uppercase tracking-wider text-slate-400 font-bold block">Custom URL Slug</label>
                        <input type="text" name="slug" required value="<?= isset($service) ? htmlspecialchars($service['slug']) : '' ?>" placeholder="vastu-residential"
                               class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] uppercase tracking-wider text-slate-400 font-bold block">Offering Title</label>
                    <input type="text" name="title" required value="<?= isset($service) ? htmlspecialchars($service['title']) : '' ?>" placeholder="Residential Vastu Audit"
                           class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label class="text-[10px] uppercase tracking-wider text-slate-400 font-bold block">Duration (Minutes)</label>
                        <input type="number" name="duration" required value="<?= isset($service) ? (int)$service['duration'] : 60 ?>" placeholder="90"
                               class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] uppercase tracking-wider text-slate-400 font-bold block">Resonance Fee (INR)</label>
                        <input type="number" step="0.01" name="price_inr" required value="<?= isset($service) ? (float)$service['price_inr'] : '' ?>" placeholder="5100.00"
                               class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] uppercase tracking-wider text-slate-400 font-bold block">Short Description</label>
                    <textarea name="short_desc" rows="3" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none"><?= isset($service) ? htmlspecialchars($service['short_desc']) : '' ?></textarea>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] uppercase tracking-wider text-slate-400 font-bold block">Long Description Details</label>
                    <textarea name="long_desc" rows="6" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none"><?= isset($service) ? htmlspecialchars($service['long_desc']) : '' ?></textarea>
                </div>

                <div class="flex items-center space-x-3 pt-2">
                    <input type="checkbox" name="is_active" value="1" id="is_active" <?= !isset($service) || $service['is_active'] ? 'checked' : '' ?> class="rounded border-slate-300">
                    <label for="is_active" class="text-xs font-semibold text-slate-600">This offering is active and visible to seekers</label>
                </div>

                <button type="submit" class="px-6 py-3 rounded-full bg-gradient-to-r from-brand-purple to-brand-pink text-white text-xs font-semibold tracking-wider uppercase shadow-md">
                    Save Offering Coordinates
                </button>
            </form>

        <?php else: ?>
            <!-- Catalog List View -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-6">
                <div class="space-y-1">
                    <span class="text-xs uppercase tracking-[0.2em] text-brand-gold font-bold">Consultation offerings</span>
                    <h1 class="font-serif text-4xl text-brand-text">Active Service Catalog</h1>
                </div>
                <div>
                    <a href="/admin/services/create" class="px-5 py-2.5 rounded-full bg-brand-gold text-white text-xs font-semibold tracking-wider uppercase inline-flex items-center space-x-2 shadow-md">
                        <span>Add Offering</span>
                    </a>
                </div>
            </div>

            <div class="glass bg-white rounded-3xl border border-brand-gold/15 shadow-sm overflow-hidden">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 border-b border-brand-gold/10 text-[10px] uppercase font-bold text-slate-400 tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Title / Category</th>
                            <th class="px-6 py-4">Duration</th>
                            <th class="px-6 py-4">Resonance Fee</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-light">
                        <?php foreach ($services as $srv): ?>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-800"><?= htmlspecialchars($srv['title']) ?></div>
                                    <div class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold"><?= htmlspecialchars($srv['category_name']) ?></div>
                                </td>
                                <td class="px-6 py-4 text-xs"><?= (int)$srv['duration'] ?> minutes</td>
                                <td class="px-6 py-4 font-semibold text-brand-gold">₹<?= number_format((float)$srv['price_inr'], 2) ?></td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider border <?= $srv['is_active'] ? 'bg-green-50 border-green-200 text-green-600' : 'bg-slate-100 border-slate-200 text-slate-500' ?>">
                                        <?= $srv['is_active'] ? 'active' : 'inactive' ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right space-x-3 text-xs">
                                    <a href="/admin/services/edit/<?= (int)$srv['id'] ?>" class="text-brand-purple hover:text-brand-pink font-semibold">Edit</a>
                                    <form action="/admin/services/delete/<?= (int)$srv['id'] ?>" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to retire this offering?')">
                                        <button type="submit" class="text-red-500 hover:text-red-700 font-semibold bg-transparent border-0 p-0 cursor-pointer">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
