<section class="py-24 max-w-md mx-auto px-6">
    <div class="glass bg-white p-8 md:p-12 rounded-3xl border border-brand-gold/15 shadow-sm space-y-8 relative overflow-hidden">
        <div class="absolute -top-24 -right-24 w-60 h-60 bg-brand-gold/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="text-center space-y-2">
            <span class="text-xs uppercase tracking-[0.2em] text-brand-gold font-bold">Portal Access</span>
            <h1 class="font-serif text-3xl text-brand-text">Seeker Sign In</h1>
            <p class="text-xs text-slate-500 font-light">Access your coordinates, transaction logs, and profile records.</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="p-3 text-xs bg-red-50 border border-red-100 text-red-600 rounded-xl font-semibold">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="/login" method="POST" class="space-y-6">
            <?= \App\Helpers\Csrf::field() ?>
            <div class="space-y-2">
                <label class="text-xs uppercase tracking-wider font-semibold text-slate-500">Email Address</label>
                <input 
                    type="email" 
                    name="email" 
                    required 
                    placeholder="e.g. seeker@narayani.com" 
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-brand-gold text-slate-700 text-sm"
                >
            </div>

            <div class="space-y-2">
                <label class="text-xs uppercase tracking-wider font-semibold text-slate-500">Password Code</label>
                <input 
                    type="password" 
                    name="password" 
                    required 
                    placeholder="Enter security key" 
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-brand-gold text-slate-700 text-sm"
                >
            </div>

            <button 
                type="submit" 
                class="w-full py-3 rounded-full bg-gradient-to-r from-brand-teal to-brand-red text-white font-semibold text-xs tracking-wider uppercase shadow-md shadow-brand-red/20 hover:opacity-95 transition-opacity"
            >
                Enter Portal
            </button>
        </form>

        <div class="text-center border-t border-slate-100 pt-4 mt-6">
            <p class="text-[11px] text-slate-400 font-light">
                Demo Seeker Access: <strong>mayur@narayani.com</strong> / <strong>Narayani@2026</strong>
            </p>
        </div>
    </div>
</section>
