<!-- Interactive Hero Section -->
<section class="relative min-h-[95vh] flex items-center justify-center overflow-hidden py-32 bg-[#FCFAF7]">
    <!-- Dynamic Starry Sky Background Served Programmatically from Asset Engine -->
    <div class="absolute inset-0 opacity-15 pointer-events-none select-none">
        <img src="/generate-asset?type=geometry&seed=starry&w=1200" class="w-full h-full object-cover" alt="Dynamic Constellation Background">
    </div>
    
    <!-- Abstract warm radial gradients -->
    <div class="absolute top-1/4 left-1/4 w-[40rem] h-[40rem] bg-brand-gold/5 rounded-full blur-[140px] pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-[35rem] h-[35rem] bg-brand-red/5 rounded-full blur-[140px] pointer-events-none"></div>

    <div class="max-w-6xl mx-auto px-6 text-center relative z-10 space-y-8">
        <!-- Logo Lockup -->
        <div class="inline-flex items-center space-x-3 bg-white/80 backdrop-blur-md px-6 py-2.5 rounded-full border border-brand-gold/20 shadow-sm mx-auto mb-4 animate-pulse">
            <img src="/generate-asset?type=logo&w=60" class="w-8 h-8" alt="Narayani Logo">
            <span class="text-xs uppercase tracking-[0.25em] text-brand-gold font-bold">Divine Wisdom & Sacred Motifs</span>
        </div>
        
        <!-- Primary / Secondary Headings -->
        <div class="space-y-4">
            <h1 class="font-serif text-5xl md:text-7xl lg:text-8xl tracking-tight text-brand-text leading-[1.1]">
                <?= t('hero_title') ?>
            </h1>
            
            <!-- Dynamic Subtitle -->
            <p class="font-devanagariSerif text-xl md:text-2xl text-brand-gold/90 font-medium tracking-wide">
                <?= t('hero_subtitle') ?>
            </p>
        </div>

        <p class="text-lg md:text-xl text-slate-600 max-w-3xl mx-auto font-light leading-relaxed">
            <?= t('hero_desc') ?>
        </p>

        <!-- CTAs -->
        <div class="pt-6 flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="/services" class="px-8 py-4 w-full sm:w-auto rounded-full bg-gradient-to-r from-brand-teal to-brand-red text-white font-semibold tracking-wider hover:-translate-y-1 hover:shadow-[0_4px_20px_rgba(0,105,92,0.3)] transition-all duration-300 uppercase shadow-lg shadow-brand-red/20 text-center">
                <?= t('explore_alignments') ?>
            </a>
            <a href="/booking" class="px-8 py-4 w-full sm:w-auto rounded-full glass border border-brand-gold/30 text-brand-gold font-semibold tracking-wider hover:bg-brand-gold/10 hover:-translate-y-1 hover:shadow-lg transition-all duration-300 uppercase text-center">
                <?= t('reserve_consultation') ?>
            </a>
        </div>

    </div>
</section>

<!-- Authority Strip (Programmatic Credentials) -->
<?php if (!empty($consultant)): ?>
    <section class="bg-white border-y border-brand-gold/15 py-10 shadow-sm relative z-20">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="text-center md:text-left space-y-1">
                    <span class="text-xs uppercase tracking-widest text-slate-400 font-semibold"><?= t('verified_credentials') ?></span>
                    <h3 class="font-serif text-xl text-brand-text font-bold"><?= t('acc_title') ?></h3>
                </div>

                
                <div class="flex flex-wrap justify-center items-center gap-4 md:gap-8">
                    <?php 
                    $credentials = json_decode($consultant['credentials'], true) ?: [];
                    foreach ($credentials as $cred): 
                    ?>
                        <div class="inline-flex items-center space-x-2 bg-[#FCFAF7] border border-brand-gold/15 px-4 py-2.5 rounded-xl shadow-sm">
                            <svg class="w-4 h-4 text-brand-gold" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <span class="text-xs font-semibold text-brand-text tracking-wide uppercase"><?= htmlspecialchars($cred) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Core Services Grid (Categories Load) -->
<section class="py-24 bg-[#FCFAF7]">
    <div class="max-w-7xl mx-auto px-6 space-y-16">
        <div class="text-center max-w-3xl mx-auto space-y-4">
            <span class="text-xs uppercase tracking-[0.2em] text-brand-gold font-bold"><?= t('core_paths') ?></span>
            <h2 class="font-serif text-4xl md:text-5xl text-brand-text"><?= t('divine_realization_title') ?></h2>
            <p class="text-slate-600 font-light leading-relaxed">
                <?= t('divine_realization_desc') ?>
            </p>
        </div>


        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $cat): ?>
                    <!-- Service Category Preview Card -->
                    <div class="relative group">
                        <!-- Glowing aura background -->
                        <div class="absolute -inset-0.5 bg-gradient-to-tr from-brand-teal to-brand-gold rounded-3xl blur opacity-0 group-hover:opacity-15 transition duration-500"></div>
                        <div class="relative bg-white border border-brand-gold/15 p-8 rounded-3xl space-y-6 shadow-sm hover:shadow-lg hover:-translate-y-1 hover:shadow-[0_4px_20px_rgba(0,105,92,0.15)] transition-all duration-300 flex flex-col justify-between h-96">
                            <div class="space-y-4">
                                <!-- Dynamic Icon type mapping -->
                                <div class="w-14 h-14 rounded-2xl bg-brand-gold/10 border border-brand-gold/15 flex items-center justify-center text-brand-gold transform group-hover:scale-110 transition-transform duration-300">
                                    <?php if ($cat['slug'] === 'vastu'): ?>
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                                    <?php elseif ($cat['slug'] === 'geometry'): ?>
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M12 21a9 9 0 100-18 9 9 0 000 18z M12 3v18 M3 12h18"/></svg>
                                    <?php else: ?>
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m11.314 11.314l.707-.707"/></svg>
                                    <?php endif; ?>
                                </div>
                                <div class="space-y-1">
                                    <h3 class="font-serif text-2xl text-brand-text group-hover:text-brand-gold transition-colors"><?= htmlspecialchars(db_trans($cat, 'name')) ?></h3>
                                </div>
                                <p class="text-sm text-slate-600 font-light leading-relaxed"><?= htmlspecialchars(db_trans($cat, 'description')) ?></p>
                            </div>
                            <div class="pt-6">
                                <a href="/services" class="text-xs uppercase tracking-widest text-brand-gold font-bold hover:text-brand-red flex items-center space-x-2 group-hover:translate-x-1 transition-transform duration-300">
                                    <span><?= t('view_program') ?></span>
                                    <span>&rarr;</span>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-3 text-center text-slate-500 py-12"><?= t('no_categories') ?></div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Bio Excerpt (Profile Intro) -->
<?php if (!empty($consultant)): ?>
    <section class="py-24 bg-white border-y border-brand-gold/15">
        <div class="max-w-6xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <!-- Left Profile Image -->
            <div class="flex justify-center">
                <div class="relative group max-w-md w-full">
                    <!-- Gold shadow container -->
                    <div class="absolute -inset-2 bg-gradient-to-tr from-brand-gold to-brand-teal rounded-3xl blur opacity-10 group-hover:opacity-25 transition duration-500"></div>
                    <div class="relative aspect-square rounded-2xl overflow-hidden border-2 border-brand-gold/20 shadow-2xl bg-[#FCFAF7] p-8">
                        <img src="<?= htmlspecialchars($consultant['photo_url']) ?>" class="w-full h-full object-contain transform group-hover:scale-105 transition-transform duration-500" alt="<?= htmlspecialchars($consultant['name']) ?>" loading="lazy">
                    </div>
                </div>
            </div>

            <!-- Right Profile Info -->
            <div class="space-y-8">
                <div class="space-y-3">
                    <span class="text-xs uppercase tracking-[0.2em] text-brand-gold font-bold"><?= t('about') ?></span>
                    <h2 class="font-serif text-4xl text-brand-text"><?= htmlspecialchars($consultant['name']) ?></h2>
                    <p class="font-devanagariSerif text-lg text-brand-gold/90"><?= htmlspecialchars(db_trans($consultant, 'tagline')) ?></p>
                </div>

                <div class="space-y-4 text-slate-600 font-light leading-relaxed">
                    <p><?= htmlspecialchars(db_trans($consultant, 'bio')) ?></p>
                </div>

                <div class="pt-4">
                    <a href="/about" class="px-8 py-3.5 rounded-full border border-brand-gold text-brand-gold font-medium tracking-wider hover:bg-brand-gold hover:text-white transition-colors uppercase inline-block">
                        <?= t('read_more') ?>
                    </a>
                </div>
            </div>

        </div>
    </section>
<?php endif; ?>

<!-- Testimonials Carousel Section -->
<?php if (!empty($testimonials)): ?>
    <section class="py-24 bg-[#FCFAF7] overflow-hidden">
        <div class="max-w-5xl mx-auto px-6 space-y-16 relative">
            <div class="text-center max-w-2xl mx-auto space-y-4">
                <span class="text-xs uppercase tracking-[0.2em] text-brand-gold font-bold">Reviews</span>
                <h2 class="font-serif text-3xl md:text-4xl text-brand-text"><?= t('featured_testimonials') ?></h2>
            </div>

            <!-- Slider Wrapper -->
            <div class="relative h-64 md:h-52 overflow-hidden flex items-center justify-center" id="carouselContainer">
                <?php foreach ($testimonials as $index => $test): ?>
                    <div class="absolute w-full max-w-3xl glass bg-white p-8 rounded-2xl border border-brand-gold/15 transition-all duration-700 opacity-0 transform translate-x-12 pointer-events-none" data-slide="<?= $index ?>">
                        <div class="space-y-4 text-center">
                            <!-- Star Rating -->
                            <div class="flex justify-center">
                                <?= \App\Helpers\UiHelper::renderStars((float)$test['rating']) ?>
                            </div>
                            
                            <p class="text-slate-600 italic font-serif text-lg md:text-xl">
                                "<?= htmlspecialchars(db_trans($test, 'content')) ?>"
                            </p>
                            
                            <div class="text-xs uppercase tracking-widest font-bold text-slate-500">
                                <?= htmlspecialchars($test['client_name']) ?> &mdash; <?= htmlspecialchars($test['client_city']) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Carousel Indicators -->
            <div class="flex justify-center space-x-3 mt-8">
                <?php foreach ($testimonials as $index => $test): ?>
                    <button class="w-2.5 h-2.5 rounded-full bg-slate-300 hover:bg-brand-gold transition-colors focus:outline-none" data-bullet="<?= $index ?>" aria-label="Go to slide <?= $index + 1 ?>"></button>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Lead Magnets Grid -->
<section class="py-24 bg-white border-t border-brand-gold/15">
    <div class="max-w-7xl mx-auto px-6 space-y-16">
        <div class="text-center max-w-3xl mx-auto space-y-4">
            <span class="text-xs uppercase tracking-[0.2em] text-brand-gold font-bold">Interactive Utilities</span>
            <h2 class="font-serif text-3xl md:text-4xl text-brand-text">Assess Your Cosmic Alignment</h2>
            <p class="text-slate-600 font-light leading-relaxed">
                Test your residential harmony index or planetary positions using our complimentary digital analysis modules.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            <!-- Vastu Score Card -->
            <div class="glass bg-[#FCFAF7] p-8 rounded-3xl border border-brand-gold/15 space-y-6 flex flex-col justify-between hover:shadow-lg hover:-translate-y-1 hover:shadow-[0_4px_20px_rgba(0,105,92,0.15)] transition-all duration-300">
                <div class="space-y-4">
                    <span class="text-[10px] font-mono uppercase text-brand-red font-semibold">Complimentary Tool</span>
                    <h4 class="font-serif text-2xl text-brand-text"><?= t('vastu_score_tool') ?></h4>
                    <p class="text-sm text-slate-600 leading-relaxed font-light">
                        Provide your main entrance direction and property shapes to receive a personalized compatibility index report calculated using spatial algorithms.
                    </p>
                </div>
                <div class="pt-6">
                    <a href="/tools/vastu-score" class="px-6 py-3 rounded-full bg-brand-gold text-white font-medium text-xs uppercase tracking-wider inline-block hover:-translate-y-0.5 hover:shadow-[0_4px_15px_rgba(0,105,92,0.3)] transition-all duration-300">
                        <?= t('submit') ?>
                    </a>
                </div>
            </div>

            <!-- Natal Sign Finder Card -->
            <div class="glass bg-[#FCFAF7] p-8 rounded-3xl border border-brand-gold/15 space-y-6 flex flex-col justify-between hover:shadow-lg hover:-translate-y-1 hover:shadow-[0_4px_20px_rgba(0,105,92,0.15)] transition-all duration-300">
                <div class="space-y-4">
                    <span class="text-[10px] font-mono uppercase text-brand-red font-semibold">Complimentary Tool</span>
                    <h4 class="font-serif text-2xl text-brand-text"><?= t('cosmic_sign_finder') ?></h4>
                    <p class="text-sm text-slate-600 leading-relaxed font-light">
                        Submit your exact birth coordinate data to map moon positions and locate corresponding constellations and planetary transits.
                    </p>
                </div>
                <div class="pt-6">
                    <a href="/tools/sun-moon-sign" class="px-6 py-3 rounded-full bg-brand-gold text-white font-medium text-xs uppercase tracking-wider inline-block hover:-translate-y-0.5 hover:shadow-[0_4px_15px_rgba(0,105,92,0.3)] transition-all duration-300">
                        <?= t('submit') ?>
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- Testimonial Carousel Script -->
<script nonce="<?= CSP_NONCE ?>">
    document.addEventListener('DOMContentLoaded', () => {
        let currentSlide = 0;
        const slides = document.querySelectorAll('[data-slide]');
        const bullets = document.querySelectorAll('[data-bullet]');
        const totalSlides = slides.length;

        if (totalSlides === 0) return;

        function showSlide(index) {
            slides.forEach((slide, idx) => {
                if (idx === index) {
                    slide.classList.remove('opacity-0', 'translate-x-12', 'pointer-events-none');
                    slide.classList.add('opacity-100', 'translate-x-0');
                    slide.style.zIndex = "10";
                } else {
                    slide.classList.add('opacity-0', 'translate-x-12', 'pointer-events-none');
                    slide.classList.remove('opacity-100', 'translate-x-0');
                    slide.style.zIndex = "0";
                }
            });

            bullets.forEach((bullet, idx) => {
                if (idx === index) {
                    bullet.classList.remove('bg-slate-300');
                    bullet.classList.add('bg-brand-gold');
                } else {
                    bullet.classList.add('bg-slate-300');
                    bullet.classList.remove('bg-brand-gold');
                }
            });
        }

        // Initialize Carousel
        showSlide(currentSlide);

        // Auto-rotation (every 5 seconds)
        let timer = setInterval(() => {
            currentSlide = (currentSlide + 1) % totalSlides;
            showSlide(currentSlide);
        }, 5000);

        // Bullet actions
        bullets.forEach((bullet, idx) => {
            bullet.addEventListener('click', () => {
                clearInterval(timer);
                currentSlide = idx;
                showSlide(currentSlide);
                // Restart timer
                timer = setInterval(() => {
                    currentSlide = (currentSlide + 1) % totalSlides;
                    showSlide(currentSlide);
                }, 5000);
            });
        });
    });
</script>
