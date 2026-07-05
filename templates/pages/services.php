<section class="py-24 max-w-7xl mx-auto px-6 space-y-16">
    <!-- Hub Header -->
    <div class="text-center max-w-3xl mx-auto space-y-4">
        <span class="text-xs uppercase tracking-[0.2em] text-brand-gold font-bold"><?= t('offerings_hub') ?></span>
        <h1 class="font-serif text-5xl text-brand-text"><?= t('alignment_pathways') ?></h1>
        <p class="text-slate-600 font-light leading-relaxed">
            <?= t('alignment_pathways_desc') ?>
        </p>
    </div>

    <!-- Immersive Category Blocks -->
    <div class="space-y-12">
        <?php if (!empty($categories)): ?>
            <?php foreach ($categories as $cat): ?>
                <div class="glass bg-white p-8 md:p-12 rounded-3xl border border-brand-gold/15 shadow-sm hover:shadow-lg hover:-translate-y-1 hover:shadow-[0_4px_20px_rgba(0,105,92,0.15)] transition-all duration-300 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    
                    <!-- Left: Details & Counter -->
                    <div class="lg:col-span-8 space-y-6">
                        <div class="space-y-2">
                            <!-- Dynamic Counter Badge -->
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-brand-gold/10 text-brand-gold border border-brand-gold/15">
                                <?= t('offerings_active', ['count' => (int)$cat['service_count'], 'name' => htmlspecialchars(db_trans($cat, 'name'))]) ?>
                            </span>
                            <h2 class="font-serif text-3xl md:text-4xl text-brand-text"><?= htmlspecialchars(db_trans($cat, 'name')) ?></h2>
                        </div>
                        
                        <p class="text-slate-600 font-light leading-relaxed text-base">
                            <?= htmlspecialchars(db_trans($cat, 'description')) ?>
                        </p>

                        <div class="pt-4">
                            <a href="/services/<?= urlencode($cat['slug']) ?>" class="px-6 py-3 rounded-full bg-gradient-to-r from-brand-teal to-brand-red text-white font-semibold text-xs tracking-wider uppercase inline-block shadow-md shadow-brand-red/15">
                                <?= t('explore_category', ['name' => htmlspecialchars(db_trans($cat, 'name'))]) ?> &rarr;
                            </a>
                        </div>
                    </div>

                    <!-- Right: Dynamic Visual representation served from our Asset Engine -->
                    <div class="lg:col-span-4 flex justify-center">
                        <div class="w-48 h-48 md:w-56 md:h-56 glass bg-[#FCFAF7] border border-brand-gold/15 rounded-2xl p-6 flex items-center justify-center overflow-hidden">
                            <?php if ($cat['slug'] === 'vastu'): ?>
                                <img src="/generate-asset?type=placeholder&w=200&h=200&text=Vastu&color=%23C5A059" class="max-h-full max-w-full object-contain" alt="Vastu Logo" loading="lazy">
                            <?php elseif ($cat['slug'] === 'jyotish'): ?>
                                <img src="/generate-asset?type=geometry&seed=jyotish&w=200" class="max-h-full max-w-full object-contain" alt="Jyotish Logo" loading="lazy">
                            <?php else: ?>
                                <img src="/generate-asset?type=logo&w=200" class="max-h-full max-w-full object-contain" alt="Healing Logo" loading="lazy">
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center text-slate-500 py-12"><?= t('no_categories') ?></div>
        <?php endif; ?>
    </div>
</section>
