<?php $activePage = 'profile'; ?>
<div class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 lg:grid-cols-12 gap-8">
    
    <!-- Left Navigation Sidebar -->
    <div class="lg:col-span-3">
        <div class="glass bg-white p-6 rounded-3xl border border-brand-gold/15 shadow-sm space-y-6">
            <div class="space-y-1">
                <span class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Seeker Profile</span>
                <h4 class="font-serif text-lg text-brand-text font-bold"><?= htmlspecialchars($_SESSION['user_name']) ?></h4>
            </div>
            
            <nav class="flex flex-col space-y-2 text-sm text-slate-600">
                <a href="/dashboard" class="px-4 py-2.5 rounded-xl hover:bg-slate-50 hover:text-brand-gold transition-colors flex items-center space-x-2 font-medium">
                    <span>Dashboard Coordinates</span>
                </a>
                <a href="/dashboard/bookings" class="px-4 py-2.5 rounded-xl hover:bg-slate-50 hover:text-brand-gold transition-colors flex items-center space-x-2 font-medium">
                    <span>Booking Interactions</span>
                </a>
                <a href="/dashboard/profile" class="px-4 py-2.5 rounded-xl flex items-center space-x-2 font-medium bg-brand-gold/10 text-brand-gold font-bold border border-brand-gold/10">
                    <span>Edit Seeker Specs</span>
                </a>
                <a href="/logout" class="px-4 py-2.5 rounded-xl hover:bg-red-50 text-red-500 transition-colors flex items-center space-x-2 font-medium">
                    <span>Exit Portal</span>
                </a>
            </nav>
        </div>
    </div>

    <!-- Right Content Area -->
    <div class="lg:col-span-9 space-y-8">
        <div class="space-y-2">
            <span class="text-xs uppercase tracking-[0.2em] text-brand-gold font-bold">Seeker Profile settings</span>
            <h1 class="font-serif text-4xl text-brand-text">Account Control Panel</h1>
        </div>

        <?php if (!empty($success)): ?>
            <div class="p-4 text-sm bg-green-50 border border-green-100 text-green-600 rounded-2xl font-semibold">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <div class="glass bg-white p-8 md:p-10 rounded-3xl border border-brand-gold/15 shadow-sm">
            <form action="/dashboard/profile" method="POST" class="space-y-8">
                <?= \App\Helpers\Csrf::field() ?>
                <!-- 1. Contact Specs -->
                <div class="space-y-4">
                    <h3 class="font-serif text-xl text-brand-text border-b border-brand-gold/10 pb-2">Seeker Contact Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs uppercase tracking-wider font-semibold text-slate-500">Full Name</label>
                            <input 
                                type="text" 
                                name="name" 
                                required 
                                value="<?= htmlspecialchars($user['name'] ?? '') ?>"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-brand-gold text-slate-700 text-sm"
                            >
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs uppercase tracking-wider font-semibold text-slate-500">Email Address (Read-only)</label>
                            <input 
                                type="email" 
                                disabled 
                                value="<?= htmlspecialchars($user['email'] ?? '') ?>"
                                class="w-full px-4 py-3 rounded-xl border border-slate-100 bg-slate-50 text-slate-400 text-sm cursor-not-allowed"
                            >
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs uppercase tracking-wider font-semibold text-slate-500">Phone Connection</label>
                            <input 
                                type="text" 
                                name="phone" 
                                value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-brand-gold text-slate-700 text-sm"
                            >
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs uppercase tracking-wider font-semibold text-slate-500">Residence City</label>
                            <input 
                                type="text" 
                                name="city" 
                                value="<?= htmlspecialchars($user['city'] ?? '') ?>"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-brand-gold text-slate-700 text-sm"
                            >
                        </div>
                    </div>
                </div>

                <!-- 2. Default Birth Coordinates -->
                <div class="space-y-4">
                    <div class="space-y-1">
                        <h3 class="font-serif text-xl text-brand-text border-b border-brand-gold/10 pb-2">Default Birth Coordinates</h3>
                        <p class="text-xs text-slate-400 font-light">Saving these fields allows you to auto-complete intake questions on future Vedic and Jyotish checkouts.</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs uppercase tracking-wider font-semibold text-slate-500">Date of Birth</label>
                            <input 
                                type="date" 
                                name="birth_date" 
                                value="<?= htmlspecialchars($birthDetails['birth_date'] ?? '') ?>"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-brand-gold text-slate-700 text-sm"
                            >
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs uppercase tracking-wider font-semibold text-slate-500">Accurate Time of Birth</label>
                            <input 
                                type="time" 
                                name="birth_time" 
                                value="<?= htmlspecialchars($birthDetails['birth_time'] ?? '') ?>"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-brand-gold text-slate-700 text-sm"
                            >
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs uppercase tracking-wider font-semibold text-slate-500">City / Location of Birth</label>
                            <input 
                                type="text" 
                                name="birth_city" 
                                placeholder="e.g. Mumbai, India"
                                value="<?= htmlspecialchars($birthDetails['birth_city'] ?? '') ?>"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-brand-gold text-slate-700 text-sm"
                            >
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <button 
                        type="submit" 
                        class="px-8 py-3 rounded-full bg-brand-gold text-white font-semibold text-xs tracking-wider uppercase shadow-md hover:opacity-95 transition-opacity"
                    >
                        Save Coordinates
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
