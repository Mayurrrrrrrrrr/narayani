<section class="min-h-[80vh] flex items-center justify-center py-20 px-6 bg-[#07060B]">
    <div class="glass bg-[#11101A] max-w-md w-full p-10 rounded-3xl border border-brand-gold/20 shadow-2xl relative overflow-hidden">
        <!-- Floating Ambient Glows -->
        <div class="absolute -top-24 -left-24 w-48 h-48 bg-brand-purple/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-48 h-48 bg-brand-pink/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="text-center space-y-3 relative z-10">
            <svg class="w-12 h-12 text-brand-gold mx-auto" viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="1.5">
                <circle cx="50" cy="50" r="45" stroke-dasharray="3 3"/>
                <polygon points="50,12 83,70 17,70" />
                <circle cx="50" cy="50" r="15"/>
            </svg>
            <span class="text-xs uppercase tracking-[0.25em] text-brand-gold font-bold">Admin Portal</span>
            <h1 class="font-serif text-3xl text-white">Narayani CMS Access</h1>
            <p class="text-xs text-slate-400 font-light">Enter system authorization coordinates</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="mt-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-xs text-center">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="/admin/login" method="POST" class="mt-8 space-y-6 relative z-10">
            <?= \App\Helpers\Csrf::field() ?>
            <div class="space-y-1.5">
                <label for="email" class="text-[10px] uppercase tracking-wider text-slate-400 font-bold block">Authorized Email</label>
                <input type="email" id="email" name="email" required placeholder="admin@narayani.com" 
                       class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-brand-gold focus:ring-1 focus:ring-brand-gold transition-colors">
            </div>

            <div class="space-y-1.5">
                <div class="flex justify-between items-center">
                    <label for="password" class="text-[10px] uppercase tracking-wider text-slate-400 font-bold block">Access Password</label>
                </div>
                <input type="password" id="password" name="password" required placeholder="••••••••" 
                       class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-brand-gold focus:ring-1 focus:ring-brand-gold transition-colors">
            </div>

            <button type="submit" class="w-full py-3.5 rounded-full bg-gradient-to-r from-brand-purple to-brand-pink text-white text-xs font-semibold tracking-widest uppercase hover:opacity-90 transition-opacity shadow-lg shadow-brand-pink/15">
                Authorize Session
            </button>
        </form>
    </div>
</section>
