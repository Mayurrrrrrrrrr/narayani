<?php
// Fallback values if database row is empty
$name = $consultant['name'] ?? 'Acharya Vinay Dev';
$tagline = db_trans($consultant, 'tagline');
if (empty($tagline)) {
    $tagline = t('about_tagline');
}
$photoUrl = $consultant['photo_url'] ?? '/generate-asset?type=placeholder&w=400&h=400&text=Acharya+Vinay';
$bio = db_trans($consultant, 'bio');
if (empty($bio)) {
    $bio = t('about_bio_default');
}
$credentials = json_decode($consultant['credentials'] ?? '[]', true) ?: ['Vastu Shastra Acharya', 'Jyotish Ratna', 'M.Sc Applied Cosmology'];
$modes = json_decode($consultant['modes'] ?? '[]', true) ?: ['Video Call', 'On-Site Visit', 'Audio Consultation'];
$availability = json_decode($consultant['weekly_availability'] ?? '[]', true) ?: [
    'Monday' => ['10:00-12:00', '14:00-16:00'],
    'Wednesday' => ['10:00-12:00', '15:00-18:00'],
    'Friday' => ['09:00-12:00']
];
?>

<section class="py-24 max-w-6xl mx-auto px-6 space-y-16">
    <!-- Header Block -->
    <div class="text-center max-w-3xl mx-auto space-y-4">
        <span class="text-xs uppercase tracking-[0.25em] text-brand-gold font-bold"><?= t('about_lineage') ?></span>
        <h1 class="font-serif text-5xl text-brand-text"><?= htmlspecialchars($name) ?></h1>
        <p class="font-devanagariSerif text-xl text-brand-gold font-medium"><?= htmlspecialchars($tagline) ?></p>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
        
        <!-- Left: Image & Credentials (Column Span 4) -->
        <div class="lg:col-span-4 space-y-8">
            <div class="glass bg-white p-6 rounded-3xl border border-brand-gold/15 shadow-sm text-center space-y-6">
                <div class="aspect-square rounded-2xl overflow-hidden border border-brand-gold/10 bg-[#FCFAF7] p-4 flex items-center justify-center">
                    <img src="<?= htmlspecialchars($photoUrl) ?>" class="max-h-full max-w-full object-contain" alt="<?= htmlspecialchars($name) ?>">
                </div>
                <div class="space-y-1">
                    <h3 class="font-serif text-xl text-brand-text font-bold"><?= htmlspecialchars($name) ?></h3>
                    <p class="text-xs text-slate-500 uppercase tracking-widest">Master Consultant</p>
                </div>
            </div>

            <!-- Dynamic Credentials Grid Badges -->
            <div class="glass bg-white p-6 rounded-3xl border border-brand-gold/15 shadow-sm space-y-4">
                <h4 class="font-serif text-lg text-brand-text font-bold border-b border-brand-gold/10 pb-2"><?= t('about_accreditations') ?></h4>
                <div class="grid grid-cols-1 gap-2.5">
                    <?php foreach ($credentials as $cred): ?>
                        <div class="flex items-center space-x-2.5 bg-[#FCFAF7] border border-brand-gold/10 px-3.5 py-2.5 rounded-xl">
                            <span class="w-1.5 h-1.5 rounded-full bg-brand-pink shrink-0"></span>
                            <span class="text-xs font-semibold text-brand-text tracking-wide uppercase"><?= htmlspecialchars($cred) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Right: Narrative & Availability (Column Span 8) -->
        <div class="lg:col-span-8 space-y-12">
            
            <!-- Narrative Section -->
            <div class="glass bg-white p-8 md:p-10 rounded-3xl border border-brand-gold/15 shadow-sm space-y-6 leading-relaxed font-light text-slate-600">
                <h3 class="font-serif text-2xl text-brand-text border-b border-brand-gold/10 pb-3"><?= t('about_narrative_title') ?></h3>
                
                <p>
                    <?= t('about_narrative_p1') ?>
                </p>

                <!-- Elegant Sepia Pull Quote with custom Gold borders -->
                <div class="border-y-2 border-brand-gold/30 my-8 py-6 px-4 bg-[#FCFAF7] text-center rounded-xl">
                    <p class="font-serif italic text-lg md:text-xl text-brand-text">
                        "<?= t('about_pullquote') ?>"
                    </p>
                    <span class="text-xs uppercase tracking-widest text-brand-gold font-bold block mt-2">&mdash; <?= htmlspecialchars($name) ?></span>
                </div>

                <p>
                    <?= t('about_narrative_p2') ?>
                </p>

                <div class="pt-4 border-t border-brand-gold/10">
                    <h4 class="font-serif text-lg text-brand-text font-bold mb-2"><?= t('about_trans_summary') ?></h4>
                    <p class="text-sm italic font-devanagariSerif text-slate-500 leading-relaxed">
                        <?= htmlspecialchars($bio) ?>
                    </p>
                </div>
            </div>

            <!-- Availability & Methods Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Modes / Methods -->
                <div class="glass bg-white p-6 rounded-3xl border border-brand-gold/15 shadow-sm space-y-4">
                    <h4 class="font-serif text-lg text-brand-text font-bold border-b border-brand-gold/10 pb-2"><?= t('consultation_method') ?></h4>
                    <ul class="space-y-3">
                        <?php foreach ($modes as $mode): ?>
                            <li class="flex items-center space-x-3 text-sm font-medium text-slate-600">
                                <svg class="w-4 h-4 text-brand-pink shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span><?= htmlspecialchars($mode) ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="pt-4 border-t border-brand-gold/10">
                        <span class="text-[10px] uppercase font-bold text-slate-400"><?= t('about_languages') ?></span>
                        <div class="flex gap-2 mt-1">
                            <span class="text-xs px-2.5 py-1 bg-[#FCFAF7] border border-brand-gold/15 rounded-lg text-brand-text font-medium">English</span>
                            <span class="text-xs px-2.5 py-1 bg-[#FCFAF7] border border-brand-gold/15 rounded-lg text-brand-text font-medium">Hindi (हिंदी)</span>
                            <span class="text-xs px-2.5 py-1 bg-[#FCFAF7] border border-brand-gold/15 rounded-lg text-brand-text font-medium">Sanskrit (संस्कृत)</span>
                        </div>
                    </div>
                </div>

                <!-- Hours -->
                <div class="glass bg-white p-6 rounded-3xl border border-brand-gold/15 shadow-sm space-y-4">
                    <h4 class="font-serif text-lg text-brand-text font-bold border-b border-brand-gold/10 pb-2"><?= t('about_hours') ?></h4>
                    <div class="space-y-2.5">
                        <?php foreach ($availability as $day => $slots): ?>
                            <div class="flex items-center justify-between text-sm">
                                <span class="font-semibold text-brand-text"><?= htmlspecialchars($day) ?></span>
                                <span class="text-slate-500 font-mono text-xs bg-[#FCFAF7] px-2 py-0.5 rounded border border-brand-gold/10">
                                    <?= htmlspecialchars(implode(', ', $slots)) ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
