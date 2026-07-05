<section class="py-16 max-w-7xl mx-auto px-6 space-y-12">
    <!-- Header Section -->
    <div class="text-center space-y-4 max-w-xl mx-auto">
        <span class="text-xs uppercase tracking-[0.25em] text-brand-gold font-bold"><?= t('wisdom_logs') ?></span>
        <h1 class="font-serif text-4xl md:text-5xl text-brand-text"><?= t('cosmic_chronicles') ?></h1>
        <p class="text-slate-500 font-light text-sm"><?= t('blog_desc') ?></p>
    </div>

    <!-- Category Filters -->
    <div class="flex flex-wrap justify-center gap-3">
        <a href="/blog" class="px-5 py-2.5 rounded-full text-xs font-semibold uppercase tracking-wider transition-all border <?= empty($selectedCategorySlug) ? 'bg-brand-gold text-white border-brand-gold shadow-md' : 'bg-white text-slate-600 border-slate-200 hover:border-brand-gold/40' ?>">
            <?= t('all_wisdom') ?>
        </a>
        <?php foreach ($categories as $cat): ?>
            <a href="/blog?category=<?= urlencode($cat['slug']) ?>" class="px-5 py-2.5 rounded-full text-xs font-semibold uppercase tracking-wider transition-all border <?= $selectedCategorySlug === $cat['slug'] ? 'bg-brand-gold text-white border-brand-gold shadow-md' : 'bg-white text-slate-600 border-slate-200 hover:border-brand-gold/40' ?>">
                <?= htmlspecialchars(db_trans($cat, 'name')) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Blog Posts Grid -->
    <?php if (!empty($posts)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($posts as $post): ?>
                <?php 
                    // Dynamic thumbnail generation using cosmic geometry generator
                    $thumbnail = !empty($post['cover_image']) ? $post['cover_image'] : '/generate-asset?type=geometry&seed=' . urlencode($post['slug']) . '&w=600&h=400';
                ?>
                <article class="glass bg-white rounded-3xl border border-brand-gold/15 shadow-sm overflow-hidden flex flex-col justify-between hover:shadow-md transition-shadow group">
                    <div class="space-y-4">
                        <!-- Post Thumbnail -->
                        <div class="aspect-[16/10] w-full bg-slate-100 overflow-hidden relative border-b border-brand-gold/10">
                            <img src="<?= htmlspecialchars($thumbnail) ?>" alt="<?= htmlspecialchars(db_trans($post, 'title')) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                            <?php if (!empty($post['category_name_en'])): ?>
                                <span class="absolute top-4 left-4 px-3 py-1 bg-white/90 backdrop-blur-sm rounded-full text-[9px] font-bold uppercase tracking-wider text-brand-gold border border-brand-gold/15">
                                    <?= htmlspecialchars(db_trans($post, 'category_name')) ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Card Content -->
                        <div class="px-6 space-y-2">
                            <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">
                                <?= $post['published_at'] ? date('d M Y', strtotime($post['published_at'])) : 'Draft' ?>
                            </span>
                            <h3 class="font-serif text-xl text-brand-text font-bold leading-snug group-hover:text-brand-gold transition-colors">
                                <a href="/blog/<?= htmlspecialchars($post['slug']) ?>">
                                    <?= htmlspecialchars(db_trans($post, 'title')) ?>
                                </a>
                            </h3>
                            <p class="text-xs text-slate-500 font-light leading-relaxed line-clamp-3">
                                <?= htmlspecialchars(db_trans($post, 'excerpt') ?? '') ?>
                            </p>
                        </div>
                    </div>

                    <div class="px-6 pb-6 pt-4 border-t border-slate-50 mt-4 flex items-center justify-between">
                        <a href="/blog/<?= htmlspecialchars($post['slug']) ?>" class="text-xs uppercase font-semibold text-brand-gold hover:text-brand-red tracking-wider inline-flex items-center space-x-1">
                            <span><?= t('read_article') ?></span>
                            <span>→</span>
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <!-- Pagination Controls -->
        <?php if ($totalPages > 1): ?>
            <div class="flex justify-center items-center space-x-4 pt-8">
                <?php if ($page > 1): ?>
                    <a href="/blog?page=<?= $page - 1 ?><?= !empty($selectedCategorySlug) ? '&category=' . urlencode($selectedCategorySlug) : '' ?>" class="px-4 py-2 rounded-full border border-slate-200 bg-white text-xs font-semibold uppercase tracking-wider text-slate-600 hover:border-brand-gold hover:text-brand-gold transition-colors">
                        <?= t('previous_btn') ?>
                    </a>
                <?php else: ?>
                    <span class="px-4 py-2 rounded-full border border-slate-100 bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-300 cursor-not-allowed">
                        <?= t('previous_btn') ?>
                    </span>
                <?php endif; ?>

                <span class="text-xs font-semibold text-slate-500">
                    <?= t('page_indicator', ['page' => $page, 'total' => $totalPages]) ?>
                </span>

                <?php if ($page < $totalPages): ?>
                    <a href="/blog?page=<?= $page + 1 ?><?= !empty($selectedCategorySlug) ? '&category=' . urlencode($selectedCategorySlug) : '' ?>" class="px-4 py-2 rounded-full border border-slate-200 bg-white text-xs font-semibold uppercase tracking-wider text-slate-600 hover:border-brand-gold hover:text-brand-gold transition-colors">
                        <?= t('next_btn') ?>
                    </a>
                <?php else: ?>
                    <span class="px-4 py-2 rounded-full border border-slate-100 bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-300 cursor-not-allowed">
                        <?= t('next_btn') ?>
                    </span>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <div class="text-center py-20 border border-dashed border-slate-200 rounded-3xl bg-white/50 glass max-w-md mx-auto">
            <span class="text-brand-gold font-bold text-glow text-3xl font-serif"><?= t('quiet_cosmos') ?></span>
            <p class="text-xs text-slate-500 font-light max-w-xs mx-auto mt-2"><?= t('no_articles') ?></p>
        </div>
    <?php endif; ?>
</section>
