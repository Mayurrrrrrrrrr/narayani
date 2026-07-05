<?php
$postTitle = db_trans($post, 'title');
$currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
$whatsappLink = 'https://api.whatsapp.com/send?text=' . urlencode($postTitle . ' - ' . $currentUrl);
$telegramLink = 'https://t.me/share/url?url=' . urlencode($currentUrl) . '&text=' . urlencode($postTitle);
$emailLink = 'mailto:?subject=' . urlencode('Narayani Wisdom: ' . $postTitle) . '&body=' . urlencode("Explore this insightful article on the Narayani Portal:\n\n" . $currentUrl);
?>
<article class="py-16 max-w-7xl mx-auto px-6">
    <!-- Article Header -->
    <header class="max-w-3xl mx-auto text-center space-y-6 pb-12 border-b border-brand-gold/10">
        <?php if (!empty($post['category_name_en'])): ?>
            <span class="px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-brand-gold/10 text-brand-gold border border-brand-gold/15 inline-block">
                <?= htmlspecialchars(db_trans($post, 'category_name')) ?>
            </span>
        <?php endif; ?>

        <h1 class="font-serif text-3xl md:text-5xl text-brand-text font-bold leading-tight">
            <?= htmlspecialchars($postTitle) ?>
        </h1>

        <div class="flex items-center justify-center space-x-2 text-xs text-slate-400 font-light">
            <span>Published: <?= $post['published_at'] ? date('d M Y', strtotime($post['published_at'])) : 'Draft' ?></span>
            <span>•</span>
            <span>By Narayani Wisdom Circle</span>
        </div>
    </header>

    <!-- Content Matrix Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 mt-12">
        <!-- Sticky Sidebar: Table of Contents -->
        <aside class="lg:col-span-3 lg:sticky lg:top-24 h-fit space-y-6">
            <?php if (!empty($toc)): ?>
                <div class="glass bg-white p-6 rounded-3xl border border-brand-gold/15 shadow-sm space-y-4">
                    <span class="text-[10px] uppercase tracking-wider text-brand-gold font-bold block border-b border-slate-50 pb-2">
                        <?= t('table_of_contents') ?>
                    </span>
                    <nav class="flex flex-col space-y-2.5 text-xs text-slate-500 font-medium">
                        <?php foreach ($toc as $heading): ?>
                            <a href="#<?= htmlspecialchars($heading['id']) ?>" class="hover:text-brand-gold transition-colors hover:underline">
                                <?= htmlspecialchars($heading['text']) ?>
                            </a>
                        <?php endforeach; ?>
                    </nav>
                </div>
            <?php endif; ?>

            <!-- Share Buttons Widget -->
            <div class="glass bg-white p-6 rounded-3xl border border-brand-gold/15 shadow-sm space-y-4">
                <span class="text-[10px] uppercase tracking-wider text-slate-400 font-bold block border-b border-slate-50 pb-2">
                    <?= t('share') ?>
                </span>
                <div class="flex items-center space-x-4 text-xs font-semibold text-slate-600">
                    <a href="<?= $whatsappLink ?>" target="_blank" rel="noopener" class="hover:text-emerald-500 transition-colors flex items-center space-x-1.5">
                        <span>WhatsApp</span>
                    </a>
                    <span>•</span>
                    <a href="<?= $telegramLink ?>" target="_blank" rel="noopener" class="hover:text-sky-500 transition-colors flex items-center space-x-1.5">
                        <span>Telegram</span>
                    </a>
                    <span>•</span>
                    <a href="<?= $emailLink ?>" class="hover:text-brand-pink transition-colors flex items-center space-x-1.5">
                        <span>Email</span>
                    </a>
                </div>
            </div>
        </aside>

        <!-- Center Article Reader Area -->
        <div class="lg:col-span-9 space-y-12">
            <!-- Article Image -->
            <div class="w-full aspect-[21/9] bg-slate-100 rounded-3xl overflow-hidden border border-brand-gold/10">
                <img src="<?= !empty($post['cover_image']) ? $post['cover_image'] : '/generate-asset?type=geometry&seed=' . urlencode($post['slug']) . '&w=1200&h=600' ?>" 
                     alt="<?= htmlspecialchars($postTitle) ?>" class="w-full h-full object-cover">
            </div>

            <!-- Content Parser Output -->
            <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed space-y-6 font-light">
                <?= $contentHtml ?>
            </div>

            <!-- Footer Targeted Booking CTA Component -->
            <?php if (!empty($relatedServices)): ?>
                <div class="border-t border-brand-gold/10 pt-12 space-y-6">
                    <div class="space-y-1">
                        <span class="text-xs uppercase tracking-widest text-brand-gold font-bold"><?= t('related_services') ?></span>
                        <h3 class="font-serif text-2xl text-brand-text font-bold">Integrate these sacred coordinates</h3>
                        <p class="text-xs text-slate-400 font-light">Bring balance to your lifestyle parameters through Acharya's custom pathways.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <?php foreach ($relatedServices as $srv): ?>
                            <div class="glass bg-white p-6 rounded-3xl border border-brand-gold/15 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow">
                                <div class="space-y-2">
                                    <h4 class="font-serif text-lg text-brand-text font-bold leading-snug"><?= htmlspecialchars(db_trans($srv, 'title')) ?></h4>
                                    <p class="text-xs text-slate-400 font-light leading-relaxed line-clamp-2"><?= htmlspecialchars(db_trans($srv, 'short_desc')) ?></p>
                                </div>
                                <div class="border-t border-slate-50 pt-4 mt-4 flex items-center justify-between">
                                    <span class="text-xs font-semibold text-brand-gold">₹<?= number_format((float)$srv['price_inr'], 2) ?></span>
                                    <a href="/booking?service_id=<?= (int)$srv['id'] ?>" class="text-[10px] uppercase font-bold text-brand-gold hover:text-brand-pink tracking-wider">
                                        <?= t('book_consultation') ?>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</article>
