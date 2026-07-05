<section class="py-24 max-w-4xl mx-auto px-6 space-y-12">
    <div class="text-center space-y-4">
        <span class="text-xs uppercase tracking-[0.2em] text-brand-gold font-bold"><?= t('connect') ?></span>
        <h1 class="font-serif text-4xl md:text-5xl text-brand-text"><?= t('initiate_resonance') ?></h1>
        <p class="text-slate-600 font-light max-w-xl mx-auto">
            <?= t('contact_desc') ?>
        </p>
    </div>

    <div class="glass bg-white p-10 rounded-2xl border border-brand-gold/15 max-w-xl mx-auto shadow-sm">
        <?php if (isset($_SESSION['contact_success'])): ?>
            <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 text-xs font-semibold">
                <?= htmlspecialchars($_SESSION['contact_success']); unset($_SESSION['contact_success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['contact_error'])): ?>
            <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-500 text-xs font-semibold">
                <?= htmlspecialchars($_SESSION['contact_error']); unset($_SESSION['contact_error']); ?>
            </div>
        <?php endif; ?>

        <form action="/contact" method="POST" class="space-y-6">
            <div>
                <label class="block text-xs uppercase tracking-wider text-brand-gold font-semibold mb-2"><?= t('seeker_name') ?></label>
                <input type="text" name="name" required class="w-full bg-[#FCFAF7] border border-brand-gold/20 rounded-xl px-4 py-3 text-brand-text focus:outline-none focus:border-brand-gold transition-colors" placeholder="Your name">
            </div>
            
            <div>
                <label class="block text-xs uppercase tracking-wider text-brand-gold font-semibold mb-2"><?= t('email_coordinates') ?></label>
                <input type="email" name="email" required class="w-full bg-[#FCFAF7] border border-brand-gold/20 rounded-xl px-4 py-3 text-brand-text focus:outline-none focus:border-brand-gold transition-colors" placeholder="you@domain.com">
            </div>

            <div>
                <label class="block text-xs uppercase tracking-wider text-brand-gold font-semibold mb-2"><?= t('phone_number') ?></label>
                <input type="text" name="phone" class="w-full bg-[#FCFAF7] border border-brand-gold/20 rounded-xl px-4 py-3 text-brand-text focus:outline-none focus:border-brand-gold transition-colors" placeholder="+1234567890">
            </div>

            <div>
                <label class="block text-xs uppercase tracking-wider text-brand-gold font-semibold mb-2"><?= t('message') ?></label>
                <textarea name="message" required rows="4" class="w-full bg-[#FCFAF7] border border-brand-gold/20 rounded-xl px-4 py-3 text-brand-text focus:outline-none focus:border-brand-gold transition-colors" placeholder="Describe your cosmic intent..."></textarea>
            </div>

            <button type="submit" class="w-full py-4 rounded-xl bg-gradient-to-r from-brand-purple to-brand-pink text-white font-bold tracking-wider hover:opacity-90 transition-opacity uppercase shadow-lg shadow-brand-pink/20">
                <?= t('contact_btn') ?>
            </button>
        </form>
    </div>
</section>
