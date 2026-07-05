<section class="py-24 max-w-7xl mx-auto px-6 space-y-16">
    <!-- Breadcrumb & Header -->
    <div class="space-y-4">
        <a href="/services" class="text-xs uppercase tracking-widest text-brand-gold hover:text-brand-pink font-semibold inline-flex items-center space-x-2">
            <span><?= t('return_to_pathways') ?></span>
        </a>
        
        <div class="glass bg-white p-8 md:p-12 rounded-3xl border border-brand-gold/15 shadow-sm space-y-4">
            <span class="text-xs uppercase tracking-[0.2em] text-brand-gold font-bold"><?= t('category_detail') ?></span>
            <h1 class="font-serif text-4xl md:text-5xl text-brand-text"><?= htmlspecialchars(db_trans($category, 'name')) ?></h1>
            <p class="text-slate-600 font-light leading-relaxed max-w-4xl text-base">
                <?= htmlspecialchars(db_trans($category, 'description')) ?>
            </p>
        </div>
    </div>

    <!-- Active Services Grid -->
    <div class="space-y-8">
        <h3 class="font-serif text-2xl text-brand-text border-b border-brand-gold/10 pb-3"><?= t('available_consultations') ?></h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <?php if (!empty($services)): ?>
                <?php foreach ($services as $srv): ?>
                    <!-- Service Detail Card -->
                    <div class="glass bg-white p-8 rounded-3xl border border-brand-gold/15 hover:border-brand-gold/35 transition-all duration-300 shadow-sm flex flex-col justify-between h-96 group">
                        
                        <div class="space-y-4">
                            <div class="flex items-start justify-between">
                                <h4 class="font-serif text-2xl text-brand-text group-hover:text-brand-gold transition-colors"><?= htmlspecialchars(db_trans($srv, 'title')) ?></h4>
                                <span class="bg-[#FCFAF7] border border-brand-gold/10 px-3 py-1 rounded-full text-xs font-semibold text-brand-gold">
                                    <?= t('duration_mins', ['mins' => htmlspecialchars((string)$srv['duration'])]) ?>
                                </span>
                            </div>
                            
                            <p class="text-slate-500 text-sm italic font-light">
                                <?= htmlspecialchars(db_trans($srv, 'short_desc')) ?>
                            </p>
                            
                            <p class="text-slate-600 text-sm font-light leading-relaxed line-clamp-3">
                                <?= htmlspecialchars(db_trans($srv, 'long_desc')) ?>
                            </p>
                        </div>

                        <div class="border-t border-brand-gold/10 pt-6 flex items-center justify-between">
                            <div class="space-y-0.5">
                                <span class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold"><?= t('resonance_fee') ?></span>
                                <div class="text-xl font-serif text-brand-gold font-bold">
                                    ₹<?= number_format((float)$srv['price_inr'], 2) ?>
                                </div>
                            </div>
                            
                            <a href="/booking?service_id=<?= urlencode((string)$srv['id']) ?>" class="px-6 py-3 rounded-full bg-gradient-to-r from-brand-purple to-brand-pink text-white font-semibold text-xs tracking-wider uppercase inline-block shadow-md shadow-brand-pink/15">
                                <?= t('book_consultation') ?>
                            </a>
                        </div>

                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-2 text-center text-slate-500 py-12"><?= t('no_services_found') ?></div>
            <?php endif; ?>
        </div>
    </div>
</section>
