<?php $adminPage = 'marketing'; ?>
<div class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 lg:grid-cols-12 gap-8" x-data="{ currentTab: 'testimonials', openBlogModal: false }">
    
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
                <a href="/admin/profile" class="px-4 py-2.5 rounded-xl hover:bg-white/5 hover:text-brand-gold transition-colors flex items-center space-x-2 font-medium">
                    <span>Consultant Profiles</span>
                </a>
                <a href="/admin/marketing" class="px-4 py-2.5 rounded-xl flex items-center space-x-2 font-medium bg-brand-gold/10 text-brand-gold border border-brand-gold/15">
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
                <span class="text-xs uppercase tracking-[0.2em] text-brand-gold font-bold">Marketing & Growth</span>
                <h1 class="font-serif text-4xl text-brand-text">Creative Moderation Hub</h1>
            </div>
            
            <div class="flex space-x-2 bg-slate-100 p-1 rounded-full text-xs font-semibold">
                <button @click="currentTab = 'testimonials'" :class="currentTab === 'testimonials' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-800'" class="px-4 py-2 rounded-full transition-all">
                    Testimonials
                </button>
                <button @click="currentTab = 'blogs'" :class="currentTab === 'blogs' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-800'" class="px-4 py-2 rounded-full transition-all">
                    Blogs
                </button>
                <button @click="currentTab = 'leads'" :class="currentTab === 'leads' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-800'" class="px-4 py-2 rounded-full transition-all">
                    Leads Ingress
                </button>
            </div>
        </div>

        <!-- 1. Testimonials Mod Pane -->
        <div x-show="currentTab === 'testimonials'" class="space-y-6">
            <div class="glass bg-white rounded-3xl border border-brand-gold/15 shadow-sm overflow-hidden">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 border-b border-brand-gold/10 text-[10px] uppercase font-bold text-slate-400 tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Client</th>
                            <th class="px-6 py-4">Review Content</th>
                            <th class="px-6 py-4">Approved</th>
                            <th class="px-6 py-4">Featured</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-light">
                        <?php foreach ($testimonials as $tst): ?>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-800"><?= htmlspecialchars($tst['client_name']) ?></div>
                                    <div class="text-xs text-slate-400"><?= htmlspecialchars($tst['client_city'] ?? '') ?></div>
                                    <div class="text-brand-gold font-mono text-[10px] mt-0.5"><?= str_repeat('★', (int)$tst['rating']) ?></div>
                                </td>
                                <td class="px-6 py-4 text-xs max-w-sm">
                                    <div class="text-slate-600 italic">"<?= htmlspecialchars($tst['content_en']) ?>"</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase border <?= $tst['is_approved'] ? 'bg-green-50 border-green-200 text-green-600' : 'bg-slate-50 border-slate-200 text-slate-400' ?>">
                                        <?= $tst['is_approved'] ? 'approved' : 'pending' ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase border <?= $tst['is_featured'] ? 'bg-yellow-50 border-yellow-200 text-yellow-600' : 'bg-slate-50 border-slate-200 text-slate-400' ?>">
                                        <?= $tst['is_featured'] ? 'featured' : 'standard' ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2 text-xs">
                                    <form action="/admin/testimonials/approve/<?= (int)$tst['id'] ?>" method="POST" class="inline">
                                        <button type="submit" class="text-brand-purple hover:text-brand-pink font-semibold">
                                            <?= $tst['is_approved'] ? 'Disapprove' : 'Approve' ?>
                                        </button>
                                    </form>
                                    <form action="/admin/testimonials/feature/<?= (int)$tst['id'] ?>" method="POST" class="inline">
                                        <button type="submit" class="text-brand-gold hover:text-brand-pink font-semibold">
                                            Toggle Feature
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 2. Blogs Mod Pane -->
        <div x-show="currentTab === 'blogs'" class="space-y-6" style="display: none;">
            <div class="flex justify-between items-center">
                <h3 class="font-serif text-xl text-brand-text">Astrological & Architectural Logs</h3>
                <button @click="openBlogModal = true" class="px-4 py-2 rounded-full bg-brand-gold text-white text-xs font-semibold uppercase tracking-wider">
                    Add Post
                </button>
            </div>

            <div class="glass bg-white rounded-3xl border border-brand-gold/15 shadow-sm overflow-hidden">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 border-b border-brand-gold/10 text-[10px] uppercase font-bold text-slate-400 tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Cover / Title</th>
                            <th class="px-6 py-4">Excerpt</th>
                            <th class="px-6 py-4">State</th>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-light">
                        <?php foreach ($blogs as $post): ?>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <img src="<?= htmlspecialchars($post['cover_image']) ?>" class="w-10 h-10 rounded-lg object-cover border border-slate-100">
                                        <div class="font-semibold text-slate-800"><?= htmlspecialchars($post['title']) ?></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs max-w-xs text-slate-400 font-light">
                                    <?= htmlspecialchars($post['excerpt'] ?? '') ?>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase border <?= $post['is_published'] ? 'bg-green-50 border-green-200 text-green-600' : 'bg-slate-50 border-slate-200 text-slate-400' ?>">
                                        <?= $post['is_published'] ? 'published' : 'draft' ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs"><?= $post['published_at'] ? date('d M Y', strtotime($post['published_at'])) : '—' ?></td>
                                <td class="px-6 py-4 text-right text-xs">
                                    <form action="/admin/blog/edit/<?= (int)$post['id'] ?>" method="POST" class="inline">
                                        <button type="submit" class="text-brand-purple hover:text-brand-pink font-semibold">
                                            Toggle State
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 3. Leads Ingress Pane -->
        <div x-show="currentTab === 'leads'" class="space-y-6" style="display: none;">
            <div class="glass bg-white rounded-3xl border border-brand-gold/15 shadow-sm overflow-hidden">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 border-b border-brand-gold/10 text-[10px] uppercase font-bold text-slate-400 tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Contact Coordinates</th>
                            <th class="px-6 py-4">Intent Inquiry</th>
                            <th class="px-6 py-4">Ingress Date</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-light">
                        <?php foreach ($leads as $ld): ?>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-800"><?= htmlspecialchars($ld['name']) ?></div>
                                    <div class="text-xs text-slate-400 font-light"><?= htmlspecialchars($ld['email'] ?? '') ?></div>
                                    <div class="text-[10px] text-slate-500 font-mono"><?= htmlspecialchars($ld['phone'] ?? '') ?></div>
                                </td>
                                <td class="px-6 py-4 text-xs max-w-sm">
                                    <div class="font-semibold text-slate-500 text-[10px] uppercase tracking-wider mb-1">Source: <?= htmlspecialchars($ld['source'] ?? 'General') ?></div>
                                    <p class="text-slate-600 leading-relaxed font-light">"<?= htmlspecialchars($ld['message'] ?? '') ?>"</p>
                                </td>
                                <td class="px-6 py-4 text-xs"><?= date('d M Y', strtotime($ld['created_at'])) ?></td>
                                <td class="px-6 py-4 text-right text-xs">
                                    <!-- Use a simple indicator or status toggle -->
                                    <span class="text-slate-400 italic">Received</span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create Blog Modal -->
    <div x-show="openBlogModal" class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-6" style="display: none;">
        <div class="bg-white rounded-3xl max-w-md w-full p-8 border border-brand-gold/25 shadow-2xl relative space-y-6">
            <div class="space-y-1">
                <h3 class="font-serif text-2xl text-brand-text font-bold">Write Cosmic Post</h3>
                <p class="text-xs text-slate-400 font-light">Publish cosmic insights directly to seekers.</p>
            </div>

            <form action="/admin/blog/create" method="POST" class="space-y-4">
                <div class="space-y-1.5">
                    <label class="text-[10px] uppercase tracking-wider text-slate-400 font-bold block">Post Title</label>
                    <input type="text" name="title" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none">
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] uppercase tracking-wider text-slate-400 font-bold block">Custom slug</label>
                    <input type="text" name="slug" required placeholder="geometry-lattice-analysis" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none">
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] uppercase tracking-wider text-slate-400 font-bold block">Post Excerpt</label>
                    <input type="text" name="excerpt" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none">
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] uppercase tracking-wider text-slate-400 font-bold block">Markdown Content</label>
                    <textarea name="content" rows="5" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none"></textarea>
                </div>

                <div class="flex items-center space-x-3 pt-2">
                    <input type="checkbox" name="is_published" value="1" id="is_published" class="rounded border-slate-300">
                    <label for="is_published" class="text-xs font-semibold text-slate-600">Publish immediately</label>
                </div>

                <div class="flex space-x-3 pt-2">
                    <button type="button" @click="openBlogModal = false" class="w-1/2 py-2.5 rounded-full border border-slate-200 text-slate-500 text-xs font-semibold uppercase tracking-wider">
                        Cancel
                    </button>
                    <button type="submit" class="w-1/2 py-2.5 rounded-full bg-gradient-to-r from-brand-purple to-brand-pink text-white text-xs font-semibold uppercase tracking-wider shadow-md">
                        Deploy Post
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
