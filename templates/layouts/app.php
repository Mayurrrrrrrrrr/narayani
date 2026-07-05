<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Narayani Portal') ?></title>
    <meta name="description" content="<?= htmlspecialchars($meta_description ?? 'Narayani Portal - Pure Wellness and Sacred Transformation') ?>">
    <meta name="csrf-token" content="<?= htmlspecialchars(\App\Helpers\Csrf::generate()) ?>">
    
    <!-- Open Graph / Social previews -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= htmlspecialchars($title ?? 'Narayani Portal') ?>">
    <meta property="og:description" content="<?= htmlspecialchars($meta_description ?? 'Narayani Portal - Pure Wellness and Sacred Transformation') ?>">
    <meta property="og:image" content="<?= htmlspecialchars($og_image ?? 'https://narayani.yuktaa.com/generate-asset?type=geometry&seed=narayani-logo&w=1200&h=630') ?>">
    <meta property="og:url" content="https://narayani.yuktaa.com<?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? '') ?>">

    <?php if (isset($schema_json) && !empty($schema_json)): ?>
    <!-- Structured JSON-LD Microdata -->
    <script type="application/ld+json">
        <?= $schema_json ?>
    </script>
    <?php endif; ?>

    <?php
    $gaId = \App\Helpers\Env::get('GOOGLE_ANALYTICS_ID');
    $clarityId = \App\Helpers\Env::get('CLARITY_PROJECT_ID');
    ?>
    <?php if (!empty($gaId)): ?>
    <!-- Google Analytics (gtag.js) -->
    <script async nonce="<?= CSP_NONCE ?>" src="https://www.googletagmanager.com/gtag/js?id=<?= urlencode($gaId) ?>"></script>
    <script nonce="<?= CSP_NONCE ?>">
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?= htmlspecialchars($gaId, ENT_QUOTES) ?>');
    </script>
    <?php endif; ?>
    <?php if (!empty($clarityId)): ?>
    <!-- Microsoft Clarity -->
    <script type="text/javascript" nonce="<?= CSP_NONCE ?>">
        (function(c,l,a,r,i,t,y){
            c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
            t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
            y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
        })(window,document,"clarity","script","<?= htmlspecialchars($clarityId, ENT_QUOTES) ?>");
    </script>
    <?php endif; ?>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300..700;1,300..700&family=Manrope:wght@200..800&family=Noto+Sans+Devanagari:wght@100..900&family=Noto+Serif+Devanagari:wght@100..900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (Compiled Production Stylesheet) -->
    <link rel="stylesheet" href="/css/app.css">
    
    <!-- Alpine.js -->
    <script defer nonce="<?= CSP_NONCE ?>" src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- GSAP -->
    <script defer nonce="<?= CSP_NONCE ?>" src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script defer nonce="<?= CSP_NONCE ?>" src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    
    <style type="text/css">
        .glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(197, 160, 89, 0.18);
        }
        .text-glow {
            text-shadow: 0 0 10px rgba(197, 160, 89, 0.25);
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #FCFAF7;
        }
        ::-webkit-scrollbar-thumb {
            background: #C5A059;
            border-radius: 4px;
        }
        /* Custom line-height and layout constraints for Devanagari strings to prevent text overlaps */
        <?php if (\App\Helpers\Translator::getLocale() === 'hi'): ?>
        body, input, textarea, button, select {
            font-family: 'Noto Sans Devanagari', 'Manrope', sans-serif !important;
            line-height: 1.75 !important;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        h1, h2, h3, h4, h5, h6, .font-serif {
            font-family: 'Noto Serif Devanagari', 'Cormorant Garamond', serif !important;
            line-height: 1.9 !important;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        <?php endif; ?>
    </style>
</head>

<body class="bg-brand-bg text-brand-text min-h-screen flex flex-col overflow-x-hidden font-sans">
    
    <!-- Global Header -->
    <header class="fixed w-full top-0 z-50 glass border-b border-brand-gold/10" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <!-- Dynamic Vector Logo (Sacred Geometry Sri Yantra Motif) -->
            <a href="/" class="flex items-center space-x-3 group">
                <svg class="w-10 h-10 text-brand-gold transform transition-transform duration-700 group-hover:rotate-180" viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="1.5">
                    <!-- Outer Circle -->
                    <circle cx="50" cy="50" r="45" stroke-dasharray="3 3"/>
                    <!-- Star Tetrahedron / Sacred Motif -->
                    <polygon points="50,12 83,70 17,70" />
                    <polygon points="50,88 83,30 17,30" />
                    <!-- Inner Circle -->
                    <circle cx="50" cy="50" r="15"/>
                    <circle cx="50" cy="50" r="5" fill="currentColor"/>
                </svg>
                <div class="flex flex-col">
                    <span class="font-serif text-xl tracking-widest text-brand-gold group-hover:text-brand-pink transition-colors uppercase">Narayani</span>
                    <span class="text-[9px] uppercase tracking-[0.3em] text-slate-500">Sacred Portal</span>
                </div>
            </a>

            <!-- Navigation Links -->
            <nav class="hidden md:flex items-center space-x-8">
                <a href="/" class="text-sm font-medium tracking-wider text-slate-600 hover:text-brand-gold transition-colors uppercase"><?= t('home') ?></a>
                <a href="/services" class="text-sm font-medium tracking-wider text-slate-600 hover:text-brand-gold transition-colors uppercase"><?= t('services') ?></a>
                <a href="/about" class="text-sm font-medium tracking-wider text-slate-600 hover:text-brand-gold transition-colors uppercase"><?= t('about') ?></a>
                <a href="/contact" class="text-sm font-medium tracking-wider text-slate-600 hover:text-brand-gold transition-colors uppercase"><?= t('contact') ?></a>
                <a href="/dashboard" class="text-sm font-medium tracking-wider text-slate-600 hover:text-brand-gold transition-colors uppercase"><?= t('dashboard') ?></a>
            </nav>

            <!-- CTA Button & Language Switcher -->
            <div class="hidden md:flex items-center space-x-4">
                <!-- Language Switcher Dropdown / Toggle -->
                <div class="flex items-center space-x-1 border border-brand-gold/30 rounded-full p-1 bg-white/50 text-[10px] uppercase font-bold tracking-wider">
                    <a href="/locale?set=en" class="px-2 py-0.5 rounded-full transition-all <?= \App\Helpers\Translator::getLocale() === 'en' ? 'bg-brand-gold text-white shadow-sm' : 'text-slate-500 hover:text-brand-gold' ?>">EN</a>
                    <a href="/locale?set=hi" class="px-2 py-0.5 rounded-full transition-all <?= \App\Helpers\Translator::getLocale() === 'hi' ? 'bg-brand-gold text-white shadow-sm' : 'text-slate-500 hover:text-brand-gold' ?>">हिन्दी</a>
                </div>
                <a href="/admin/dashboard" class="text-xs uppercase tracking-wider text-slate-500 hover:text-brand-gold transition-colors">Admin Area</a>
                <a href="/contact" class="px-6 py-2.5 rounded-full border border-brand-gold text-brand-gold text-sm font-medium tracking-widest hover:bg-brand-gold hover:text-brand-bg transition-all duration-300 uppercase shadow-[0_0_15px_rgba(197,160,89,0.1)] hover:shadow-[0_0_25px_rgba(197,160,89,0.25)]">
                    <?= t('book_now') ?>
                </a>
            </div>


            <!-- Mobile Menu Toggle -->
            <button class="md:hidden text-slate-600 hover:text-brand-gold focus:outline-none" @click="mobileMenuOpen = !mobileMenuOpen">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
                    <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div class="md:hidden glass border-t border-brand-gold/10 px-6 py-8 space-y-4" x-show="mobileMenuOpen" x-transition>
            <a href="/" class="block text-sm font-medium tracking-wider text-slate-600 hover:text-brand-gold uppercase py-2"><?= t('home') ?></a>
            <a href="/services" class="block text-sm font-medium tracking-wider text-slate-600 hover:text-brand-gold uppercase py-2"><?= t('services') ?></a>
            <a href="/about" class="block text-sm font-medium tracking-wider text-slate-600 hover:text-brand-gold uppercase py-2"><?= t('about') ?></a>
            <a href="/contact" class="block text-sm font-medium tracking-wider text-slate-600 hover:text-brand-gold uppercase py-2"><?= t('contact') ?></a>
            <a href="/dashboard" class="block text-sm font-medium tracking-wider text-slate-600 hover:text-brand-gold uppercase py-2"><?= t('dashboard') ?></a>
            <a href="/admin/dashboard" class="block text-sm font-medium tracking-wider text-slate-500 hover:text-brand-gold uppercase py-2">Admin</a>
            
            <!-- Mobile Language Switcher -->
            <div class="flex items-center space-x-2 py-2 border-t border-b border-brand-gold/10">
                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Language:</span>
                <a href="/locale?set=en" class="px-3 py-1 rounded-full text-xs font-semibold <?= \App\Helpers\Translator::getLocale() === 'en' ? 'bg-brand-gold text-white' : 'text-slate-500' ?>">EN</a>
                <a href="/locale?set=hi" class="px-3 py-1 rounded-full text-xs font-semibold <?= \App\Helpers\Translator::getLocale() === 'hi' ? 'bg-brand-gold text-white' : 'text-slate-500' ?>">हिन्दी</a>
            </div>

            <a href="/contact" class="block w-full text-center py-3 rounded-full border border-brand-gold text-brand-gold text-sm font-medium tracking-widest uppercase mt-4">
                <?= t('book_now') ?>
            </a>
        </div>

    </header>

    <!-- Main Content Wrapper -->
    <main class="flex-grow pt-20">
        <?= $content ?>
    </main>

    <!-- Global Footer -->
    <footer class="bg-[#FAF6F0] border-t border-brand-gold/15 py-16 mt-auto">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-12">
            <div class="space-y-4">
                <div class="flex items-center space-x-3">
                    <svg class="w-8 h-8 text-brand-gold" viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="50" cy="50" r="45" stroke-dasharray="3 3"/>
                        <polygon points="50,12 83,70 17,70" />
                        <circle cx="50" cy="50" r="15"/>
                    </svg>
                    <span class="font-serif text-lg tracking-widest text-brand-gold uppercase">Narayani</span>
                </div>
                <p class="text-sm text-slate-600 leading-relaxed">
                    A cosmic sanctuary of transcendental geometry, bespoke wellness, and digital spiritual portals. Unveil your potential.
                </p>
            </div>
            
            <div>
                <h4 class="font-serif text-sm tracking-wider text-brand-gold uppercase mb-4"><?= t('services') ?></h4>
                <ul class="space-y-2 text-sm text-slate-600">
                    <li><a href="/" class="hover:text-brand-gold transition-colors"><?= t('home') ?></a></li>
                    <li><a href="/services" class="hover:text-brand-gold transition-colors"><?= t('services') ?></a></li>
                    <li><a href="/about" class="hover:text-brand-gold transition-colors"><?= t('about') ?></a></li>
                    <li><a href="/contact" class="hover:text-brand-gold transition-colors"><?= t('contact') ?></a></li>
                </ul>
            </div>


            <div>
                <h4 class="font-serif text-sm tracking-wider text-brand-gold uppercase mb-4">Cosmic Coordinates</h4>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Narayani Sanctuary<br>
                    Suite 108, Sri Yantra Enclave<br>
                    Bangalore, Karnataka, India
                </p>
            </div>

            <div class="space-y-4">
                <h4 class="font-serif text-sm tracking-wider text-brand-gold uppercase mb-4">Vibrations</h4>
                <div class="flex space-x-4">
                    <a href="https://instagram.com" target="_blank" rel="noopener" class="w-10 h-10 rounded-full border border-brand-gold/30 flex items-center justify-center text-slate-500 hover:text-brand-gold hover:border-brand-gold transition-colors">
                        <span class="sr-only">Instagram</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37zM17.5 6.5h.01"/></svg>
                    </a>
                    <a href="https://twitter.com" target="_blank" rel="noopener" class="w-10 h-10 rounded-full border border-brand-gold/30 flex items-center justify-center text-slate-500 hover:text-brand-gold hover:border-brand-gold transition-colors">
                        <span class="sr-only">Twitter</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/></svg>
                    </a>
                    <a href="https://youtube.com" target="_blank" rel="noopener" class="w-10 h-10 rounded-full border border-brand-gold/30 flex items-center justify-center text-slate-500 hover:text-brand-gold hover:border-brand-gold transition-colors">
                        <span class="sr-only">YouTube</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"/><polygon points="9.75,15.02 15.5,11.75 9.75,8.48"/></svg>
                    </a>
                </div>
                <p class="text-xs text-slate-400">
                    &copy; <?= date('Y') ?> Narayani Portal. <?= t('copyright') ?>
                </p>

            </div>
        </div>
    </footer>

    <!-- Sticky WhatsApp Action Button Overlay -->
    <div class="fixed bottom-4 right-4 md:bottom-6 md:right-6 z-40">
        <button id="whatsappStickyBtn" class="w-14 h-14 rounded-full bg-emerald-500 hover:bg-emerald-600 text-white flex items-center justify-center shadow-lg shadow-emerald-500/20 hover:scale-110 transition-transform active:scale-95 focus:outline-none relative group">
            <svg class="w-7 h-7 fill-current" viewBox="0 0 24 24">
                <path d="M12.012 2c-5.506 0-9.988 4.482-9.988 9.988 0 1.942.558 3.824 1.62 5.466l-1.62 5.922 6.072-1.59c1.584.864 3.366 1.314 5.184 1.314 5.508 0 9.99-4.482 9.99-9.988 0-2.664-1.038-5.166-2.928-7.056-1.89-1.89-4.392-2.928-7.056-2.928zm5.286 13.92c-.228.642-1.344 1.218-1.854 1.284-.462.06-1.068.108-3.036-.708-2.52-1.038-4.14-3.612-4.266-3.78-.126-.168-1.026-1.368-1.026-2.61 0-1.242.648-1.854.882-2.106.228-.252.51-.318.684-.318.174 0 .348.006.498.012.162.006.378-.06.594.462.222.534.762 1.848.828 1.98.066.132.108.288.018.468-.09.18-.18.288-.348.486-.168.192-.354.432-.51.582-.174.168-.354.348-.15.696.204.342.906 1.494 1.944 2.418 1.338 1.188 2.466 1.56 2.814 1.728.348.168.552.138.756-.096.204-.234.882-1.026 1.116-1.38.234-.354.468-.294.786-.174.318.12 2.016.954 2.364 1.128.348.174.582.258.666.402.084.144.084.834-.144 1.476z"/>
            </svg>
            <span class="absolute right-16 top-3 bg-slate-900 text-white text-[10px] uppercase font-bold tracking-wider px-3 py-1.5 rounded-lg opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-md">
                Chat on WhatsApp
            </span>
        </button>
    </div>

    <!-- Soft Exit-Intent Newsletter Modal -->
    <div id="exitIntentModal" class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-6" style="display: none;">
        <div class="bg-white rounded-3xl max-w-md w-full p-8 border border-brand-gold/25 shadow-2xl relative space-y-6">
            <button id="closeExitModalBtn" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 text-xl font-bold font-mono">×</button>
            
            <div class="space-y-2 text-center">
                <svg class="w-10 h-10 text-brand-gold mx-auto" viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="50" cy="50" r="45" stroke-dasharray="3 3"/>
                    <circle cx="50" cy="50" r="20"/>
                </svg>
                <h3 class="font-serif text-2xl text-brand-text font-bold"><?= t('exit_intent_title') ?></h3>
                <p class="text-xs text-slate-500 font-light max-w-sm mx-auto"><?= t('exit_intent_desc') ?></p>
            </div>

            <form id="exitIntentForm" class="space-y-4">
                <div class="space-y-1.5">
                    <label for="exit_name" class="text-[10px] uppercase tracking-wider text-slate-400 font-bold block"><?= t('seeker_name') ?></label>
                    <input type="text" id="exit_name" required placeholder="Mayur Sharma" 
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none">
                </div>

                <div class="space-y-1.5">
                    <label for="exit_email" class="text-[10px] uppercase tracking-wider text-slate-400 font-bold block"><?= t('email_coordinates') ?></label>
                    <input type="email" id="exit_email" required placeholder="mayur@domain.com" 
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none">
                </div>

                <button type="submit" class="w-full py-3 rounded-full bg-gradient-to-r from-brand-purple to-brand-pink text-white text-xs font-semibold tracking-wider uppercase shadow-md hover:opacity-90 transition-opacity">
                    <?= t('exit_intent_btn') ?>
                </button>
            </form>

            <div id="exitIntentSuccess" class="text-center py-4 space-y-2" style="display: none;">
                <span class="text-emerald-500 font-bold text-xl block font-serif">Coordinates Registered</span>
                <p class="text-xs text-slate-500 font-light">The mathematical Sri Yantra blueprint coordinates have been streamed to your inbox.</p>
            </div>
        </div>
    </div>


    <!-- GSAP Initializations & Dynamic Interaction Pipelines -->
    <script nonce="<?= CSP_NONCE ?>">
        document.addEventListener('DOMContentLoaded', () => {
            // Check for prefers-reduced-motion
            const prefersReducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

            if (!prefersReducedMotion) {
                // Register ScrollTrigger
                gsap.registerPlugin(ScrollTrigger);

                // Fade up section headings
                gsap.utils.toArray("h2.font-serif, h1.font-serif, section .text-center h2").forEach(heading => {
                    gsap.from(heading, {
                        y: 40,
                        opacity: 0,
                        duration: 1,
                        ease: "power2.out",
                        scrollTrigger: {
                            trigger: heading,
                            start: "top 85%",
                            toggleActions: "play none none none"
                        }
                    });
                });

                // Stagger entrance of service/testimonial cards
                const cards = gsap.utils.toArray(".grid > .glass, .grid > div.bg-white");
                if (cards.length > 0) {
                    gsap.from(cards, {
                        y: 50,
                        opacity: 0,
                        duration: 0.8,
                        stagger: 0.15,
                        ease: "power2.out",
                        scrollTrigger: {
                            trigger: cards[0].parentElement,
                            start: "top 80%",
                            toggleActions: "play none none none"
                        }
                    });
                }
            }

            if (!prefersReducedMotion) {
                gsap.from("header", {
                    y: -100,
                    opacity: 0,
                    duration: 1.2,
                    ease: "power4.out"
                });
            }

            // 1. WhatsApp Button Tracker
            const whatsappBtn = document.getElementById('whatsappStickyBtn');
            if (whatsappBtn) {
                whatsappBtn.addEventListener('click', () => {
                    // Log lead in background API before redirecting
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                    fetch('/api/log-lead', {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            name: 'WhatsApp Seeker',
                            source: 'WhatsApp Click',
                            message: 'Clicked WhatsApp sticky overlay button.'
                        })
                    }).finally(() => {
                        // Redirect to WhatsApp chat coordinates
                        window.open('https://api.whatsapp.com/send?phone=919876543210&text=Namaste!%20I%20would%20like%20to%20know%20more%20about%20your%20consultations.', '_blank');
                    });
                });
            }

            // 2. Soft Exit-Intent Modal Tracker
            const exitModal = document.getElementById('exitIntentModal');
            const closeBtn = document.getElementById('closeExitModalBtn');
            const exitForm = document.getElementById('exitIntentForm');
            const exitSuccess = document.getElementById('exitIntentSuccess');
            let exitIntentTriggered = false;

            // Check if user already signed up or closed
            const isSignedUp = document.cookie.split('; ').find(row => row.startsWith('exit_intent_signed='));

            if (!isSignedUp) {
                document.addEventListener('mouseleave', (e) => {
                    // Trigger when cursor leaves viewport top boundary (clientY < 15)
                    if (e.clientY < 15 && !exitIntentTriggered) {
                        exitIntentTriggered = true;
                        exitModal.style.display = 'flex';
                    }
                });
            }

            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    exitModal.style.display = 'none';
                    // Prevent showing again in current session
                    document.cookie = "exit_intent_signed=closed; path=/; max-age=86400";
                });
            }

            if (exitForm) {
                exitForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    const name = document.getElementById('exit_name').value;
                    const email = document.getElementById('exit_email').value;

                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                    fetch('/api/log-lead', {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            name: name,
                            email: email,
                            source: 'Exit Intent Newsletter',
                            message: 'Signed up via soft exit-intent newsletter modal.'
                        })
                    }).then(res => res.json()).then(data => {
                        if (data.success) {
                            exitForm.style.display = 'none';
                            exitSuccess.style.display = 'block';
                            // Store cookie to prevent recurring prompts
                            document.cookie = "exit_intent_signed=success; path=/; max-age=31536000";
                            setTimeout(() => {
                                exitModal.style.display = 'none';
                            }, 3000);
                        }
                    });
                });
            }
        });
    </script>
</body>
</html>
