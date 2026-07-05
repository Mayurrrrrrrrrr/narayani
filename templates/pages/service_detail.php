<?php
// Tailor preparation requirements dynamically based on category
$prepList = [];
if ($category && $category['slug'] === 'vastu') {
    $prepList = [
        t('prep_vastu_1'),
        t('prep_vastu_2'),
        t('prep_vastu_3'),
        t('prep_vastu_4')
    ];
} elseif ($category && $category['slug'] === 'jyotish') {
    $prepList = [
        t('prep_jyotish_1'),
        t('prep_jyotish_2'),
        t('prep_jyotish_3')
    ];
} else {
    $prepList = [
        t('prep_healing_1'),
        t('prep_healing_2'),
        t('prep_healing_3'),
        t('prep_healing_4')
    ];
}

// Structured session workflows
$workflows = [
    [
        'title' => t('wf_step1_title'),
        'desc' => t('wf_step1_desc')
    ],
    [
        'title' => t('wf_step2_title'),
        'desc' => t('wf_step2_desc')
    ],
    [
        'title' => t('wf_step3_title'),
        'desc' => t('wf_step3_desc')
    ],
    [
        'title' => t('wf_step4_title'),
        'desc' => t('wf_step4_desc')
    ]
];
?>

<!-- Breadcrumbs & Navigation -->
<nav class="max-w-7xl mx-auto px-6 pt-8 pb-4">
    <div class="flex items-center space-x-2 text-xs uppercase tracking-widest text-slate-400">
        <a href="/services" class="hover:text-brand-gold transition-colors"><?= t('services') ?></a>
        <span>/</span>
        <?php if ($category): ?>
            <a href="/services/<?= htmlspecialchars($category['slug']) ?>" class="hover:text-brand-gold transition-colors"><?= htmlspecialchars(db_trans($category, 'name')) ?></a>
            <span>/</span>
        <?php endif; ?>
        <span class="text-brand-gold font-semibold"><?= htmlspecialchars(db_trans($service, 'title')) ?></span>
    </div>
</nav>

<section class="max-w-7xl mx-auto px-6 pb-24 space-y-16">
    <!-- 1. Cover Header -->
    <div class="glass bg-white p-8 md:p-12 rounded-3xl border border-brand-gold/15 shadow-sm relative overflow-hidden grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
        <div class="absolute -top-24 -right-24 w-72 h-72 bg-brand-gold/5 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="lg:col-span-8 space-y-6">
            <div class="space-y-3">
                <?php if ($category): ?>
                    <a href="/services/<?= htmlspecialchars($category['slug']) ?>" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-brand-gold/10 text-brand-gold border border-brand-gold/15">
                        <?= htmlspecialchars(db_trans($category, 'name')) ?>
                    </a>
                <?php endif; ?>
                
                <h1 class="font-serif text-4xl md:text-5xl text-brand-text text-glow leading-tight">
                    <?= htmlspecialchars(db_trans($service, 'title')) ?>
                </h1>
                
                <p class="text-slate-500 font-light text-base leading-relaxed max-w-2xl">
                    <?= htmlspecialchars(db_trans($service, 'short_desc')) ?>
                </p>
            </div>

            <!-- Key metadata badges -->
            <div class="flex flex-wrap gap-4 text-xs font-semibold text-slate-600">
                <span class="inline-flex items-center space-x-1.5 px-3 py-2 rounded-xl bg-slate-50 border border-slate-100">
                    <svg class="w-4 h-4 text-brand-gold" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span><?= t('session_duration', ['mins' => htmlspecialchars((string)$service['duration'])]) ?></span>
                </span>
                <span class="inline-flex items-center space-x-1.5 px-3 py-2 rounded-xl bg-slate-50 border border-slate-100">
                    <svg class="w-4 h-4 text-brand-gold" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/></svg>
                    <span><?= t('verified_placement') ?></span>
                </span>
            </div>
        </div>

        <!-- Left: Fee lockup and dynamic action links -->
        <div class="lg:col-span-4 lg:border-l lg:border-brand-gold/15 lg:pl-8 flex flex-col justify-center space-y-6">
            <div class="space-y-1 text-center lg:text-left">
                <span class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold"><?= t('resonance_exchange') ?></span>
                <div class="text-3xl font-serif text-brand-gold font-bold">
                    ₹<?= number_format((float)$service['price_inr'], 2) ?>
                </div>
                <span class="text-xs text-slate-400 font-light block"><?= t('includes_report') ?></span>
            </div>
            
            <a href="/booking?service_id=<?= (int)$service['id'] ?>" class="w-full text-center px-8 py-4 rounded-full bg-gradient-to-r from-brand-purple to-brand-pink text-white font-semibold text-sm tracking-wider uppercase inline-block shadow-md shadow-brand-pink/20 hover:opacity-95 transition-opacity">
                <?= t('book_now_btn') ?>
            </a>
        </div>
    </div>

    <!-- 2. Detailed Matrix (Description & Side Column) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
        <!-- Main Column: Description and Workflow -->
        <div class="lg:col-span-8 space-y-12">
            <div class="glass bg-white p-8 md:p-10 rounded-3xl border border-brand-gold/15 shadow-sm space-y-6">
                <h3 class="font-serif text-2xl text-brand-text border-b border-brand-gold/10 pb-3"><?= t('pathway_details_title') ?></h3>
                <div class="text-slate-600 font-light leading-relaxed space-y-4 text-base">
                    <?= nl2br(htmlspecialchars(db_trans($service, 'long_desc'))) ?>
                </div>
            </div>

            <!-- Interactive Workflows Map -->
            <div class="glass bg-white p-8 md:p-10 rounded-3xl border border-brand-gold/15 shadow-sm space-y-8">
                <h3 class="font-serif text-2xl text-brand-text border-b border-brand-gold/10 pb-3"><?= t('pathway_align_title') ?></h3>
                
                <div class="relative pl-6 border-l border-brand-gold/25 space-y-8">
                    <?php foreach ($workflows as $index => $w): ?>
                        <div class="relative">
                            <!-- Bullet marker -->
                            <span class="absolute -left-[35px] top-1.5 w-4.5 h-4.5 rounded-full border-2 border-brand-gold bg-white flex items-center justify-center">
                                <span class="w-1.5 h-1.5 rounded-full bg-brand-gold"></span>
                            </span>
                            <div class="space-y-1">
                                <span class="text-[10px] uppercase font-bold text-brand-gold tracking-widest"><?= t('step_label', ['num' => $index + 1]) ?></span>
                                <h4 class="font-serif text-lg text-brand-text font-semibold"><?= htmlspecialchars($w['title']) ?></h4>
                                <p class="text-slate-500 font-light text-sm leading-relaxed"><?= htmlspecialchars($w['desc']) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Side Column: Preparation Requirements & Available Channels -->
        <div class="lg:col-span-4 space-y-8">
            <!-- Preparation List -->
            <div class="glass bg-white p-6 md:p-8 rounded-3xl border border-brand-gold/15 shadow-sm space-y-6">
                <h4 class="font-serif text-xl text-brand-text border-b border-brand-gold/10 pb-3"><?= t('prep_needed') ?></h4>
                <p class="text-xs text-slate-500 font-light leading-relaxed">
                    <?= t('prep_needed_desc') ?>
                </p>
                <ul class="space-y-4">
                    <?php foreach ($prepList as $item): ?>
                        <li class="flex items-start space-x-3 text-sm text-slate-600 font-light leading-relaxed">
                            <span class="w-5 h-5 rounded-full bg-brand-gold/10 text-brand-gold border border-brand-gold/15 flex-shrink-0 flex items-center justify-center text-xs mt-0.5">
                                ✓
                            </span>
                            <span><?= htmlspecialchars($item) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Available Channels Card -->
            <div class="glass bg-white p-6 md:p-8 rounded-3xl border border-brand-gold/15 shadow-sm space-y-4">
                <h4 class="font-serif text-xl text-brand-text border-b border-brand-gold/10 pb-3"><?= t('formats_avail') ?></h4>
                <div class="space-y-3">
                    <div class="flex items-center justify-between text-xs py-2 border-b border-slate-50">
                        <span class="text-slate-500 font-light"><?= t('intake_format') ?></span>
                        <span class="font-bold text-brand-text"><?= t('intake_format_val') ?></span>
                    </div>
                    <div class="flex items-center justify-between text-xs py-2 border-b border-slate-50">
                        <span class="text-slate-500 font-light"><?= t('consultation_method') ?></span>
                        <span class="font-bold text-brand-text"><?= t('consultation_method_val') ?></span>
                    </div>
                    <div class="flex items-center justify-between text-xs py-2">
                        <span class="text-slate-500 font-light"><?= t('support_period') ?></span>
                        <span class="font-bold text-brand-text"><?= t('support_period_val') ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Dynamic Related Cross-Sells -->
    <?php if (!empty($relatedServices)): ?>
        <div class="space-y-8 pt-8">
            <div class="text-center md:text-left space-y-1">
                <span class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold"><?= t('services') ?></span>
                <h3 class="font-serif text-3xl text-brand-text"><?= t('complementary_pathways') ?></h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php foreach ($relatedServices as $rel): ?>
                    <div class="glass bg-white p-6 rounded-3xl border border-brand-gold/15 hover:border-brand-gold/35 transition-all duration-300 shadow-sm flex flex-col justify-between h-80 group">
                        <div class="space-y-3">
                            <div class="flex items-start justify-between">
                                <h4 class="font-serif text-xl text-brand-text group-hover:text-brand-gold transition-colors font-semibold"><?= htmlspecialchars(db_trans($rel, 'title')) ?></h4>
                            </div>
                            <p class="text-slate-500 text-xs italic font-light line-clamp-2">
                                <?= htmlspecialchars(db_trans($rel, 'short_desc')) ?>
                            </p>
                        </div>

                        <div class="border-t border-brand-gold/10 pt-4 flex items-center justify-between">
                            <div class="space-y-0.5">
                                <span class="text-[9px] uppercase tracking-wider text-slate-400 font-semibold"><?= t('resonance_fee') ?></span>
                                <div class="text-base font-serif text-brand-gold font-bold">
                                    ₹<?= number_format((float)$rel['price_inr'], 2) ?>
                                </div>
                            </div>
                            
                            <a href="/services/<?= urlencode($rel['slug']) ?>" class="px-4 py-2 rounded-full border border-brand-gold text-brand-gold hover:bg-brand-gold hover:text-white transition-all text-[10px] font-semibold tracking-wider uppercase">
                                <?= t('view_details') ?>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</section>

<!-- 4. Interactive Sticky Booking Banner -->
<div class="fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur border-t border-brand-gold/15 p-4 flex items-center justify-between shadow-lg md:hidden">
    <div class="flex flex-col">
        <span class="text-[9px] uppercase tracking-wider text-slate-400 font-semibold leading-none"><?= t('resonance_fee') ?></span>
        <span class="text-lg font-serif text-brand-gold font-bold">₹<?= number_format((float)$service['price_inr'], 2) ?></span>
    </div>
    <a href="/booking?service_id=<?= (int)$service['id'] ?>" class="px-6 py-2.5 rounded-full bg-gradient-to-r from-brand-purple to-brand-pink text-white text-xs font-semibold tracking-wider uppercase shadow-md shadow-brand-pink/20">
        <?= t('book_now') ?>
    </a>
</div>
