<?php
// Get current page
$currentPage = basename($_SERVER['PHP_SELF']);

// Check for low stock items for the alert badge
require_once __DIR__ . '/../config/database.php';
$lowStockCount = 0;
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM products WHERE stock_quantity <= min_stock");
    $lowStockCount = $stmt->fetchColumn();
} catch (Exception $e) { /* Ignore */ }
?>
<nav class="fixed top-0 w-full z-40 transition-all duration-300 border-b border-gray-200/50 dark:border-white/5 bg-white/80 dark:bg-[#0f172a]/80 backdrop-blur-xl" 
     x-data="{ scrolled: false, mobileMenuOpen: false }" 
     @scroll.window="scrolled = (window.pageYOffset > 20)"
     :class="{ 'shadow-md shadow-gray-200/50 dark:shadow-black/20': scrolled }">
    
    <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-18"> <!-- h-18 for slightly taller navbar -->
            
            <!-- Logo area -->
            <div class="flex items-center gap-3 shrink-0">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-brand-400 to-blue-600 flex items-center justify-center shadow-lg shadow-brand-500/30 ring-1 ring-white/10">
                    <i class="fa-solid fa-bottle-droplet text-white text-sm"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-xl font-black tracking-tight leading-none bg-clip-text text-transparent bg-gradient-to-r from-gray-900 via-gray-700 to-gray-800 dark:from-white dark:via-gray-200 dark:to-gray-400">
                        Jabones<span class="text-brand-500">POS</span>
                    </span>
                    <span class="text-[9px] font-bold text-gray-400 tracking-widest uppercase">Premium System</span>
                </div>
            </div>
            
            <!-- Desktop Links -->
            <div class="hidden md:flex items-center justify-center flex-1 px-8">
                <div class="flex items-center space-x-1 p-1 rounded-full bg-gray-100/50 dark:bg-white/5 border border-transparent dark:border-white/5 backdrop-blur-sm">
                    <!-- Core Links -->
                    <a href="index.php" class="<?php echo $currentPage == 'index.php' ? 'bg-white dark:bg-brand-600 text-brand-600 dark:text-white shadow-sm' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white hover:bg-gray-200/50 dark:hover:bg-white/5'; ?> px-4 py-2 rounded-full text-xs font-bold transition-all duration-200 flex items-center gap-2">
                        <i class="fa-solid fa-cash-register"></i> P. Venta
                    </a>

                    <a href="sales_history.php" class="<?php echo $currentPage == 'sales_history.php' ? 'bg-white dark:bg-brand-600 text-brand-600 dark:text-white shadow-sm' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white hover:bg-gray-200/50 dark:hover:bg-white/5'; ?> px-4 py-2 rounded-full text-xs font-bold transition-all duration-200 flex items-center gap-2">
                        <i class="fa-solid fa-clock-rotate-left"></i> Historial
                    </a>

                    <a href="insumos.php" class="<?php echo $currentPage == 'insumos.php' ? 'bg-white dark:bg-brand-600 text-brand-600 dark:text-white shadow-sm' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white hover:bg-gray-200/50 dark:hover:bg-white/5'; ?> px-4 py-2 rounded-full text-xs font-bold transition-all duration-200 flex items-center gap-2">
                        <i class="fa-solid fa-boxes-packing"></i> Materia Prima
                    </a>

                    <!-- Admin Group Separator -->
                    <div x-show="$store.nav.adminMode" class="w-px h-4 bg-gray-300 dark:bg-white/10 mx-2"></div>
                    
                    <!-- Admin Links -->
                    <template x-if="$store.nav.adminMode">
                        <div class="flex items-center space-x-1">
                            <a href="inventory.php" class="<?php echo $currentPage == 'inventory.php' ? 'bg-white dark:bg-brand-600 text-brand-600 dark:text-white shadow-sm' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white hover:bg-gray-200/50 dark:hover:bg-white/5'; ?> px-4 py-2 rounded-full text-xs font-bold transition-all duration-200 flex items-center gap-2">
                                <i class="fa-solid fa-plus-circle"></i> Nuevo
                            </a>

                            <a href="catalogue.php" class="<?php echo $currentPage == 'catalogue.php' ? 'bg-white dark:bg-brand-600 text-brand-600 dark:text-white shadow-sm' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white hover:bg-gray-200/50 dark:hover:bg-white/5'; ?> px-4 py-2 rounded-full text-xs font-bold transition-all duration-200 flex items-center gap-2">
                                <i class="fa-solid fa-list-ul"></i> Catálogo
                            </a>

                            <a href="categories.php" class="<?php echo $currentPage == 'categories.php' ? 'bg-white dark:bg-brand-600 text-brand-600 dark:text-white shadow-sm' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white hover:bg-gray-200/50 dark:hover:bg-white/5'; ?> px-4 py-2 rounded-full text-xs font-bold transition-all duration-200 flex items-center gap-2">
                                <i class="fa-solid fa-tags"></i>
                            </a>
                            
                            <a href="settings.php" class="<?php echo $currentPage == 'settings.php' ? 'bg-white dark:bg-brand-600 text-brand-600 dark:text-white shadow-sm' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white hover:bg-gray-200/50 dark:hover:bg-white/5'; ?> px-4 py-2 rounded-full text-xs font-bold transition-all duration-200 flex items-center gap-2">
                                <i class="fa-solid fa-gear"></i>
                            </a>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Right Controls -->
            <div class="flex items-center gap-3 shrink-0">
                
                <!-- Low Stock Alert -->
                <?php if($lowStockCount > 0): ?>
                    <button @click="$dispatch('open-low-stock-global')"
                        class="hidden md:flex relative px-3 py-1.5 rounded-full bg-red-500/10 border border-red-500/20 text-red-500 hover:bg-red-500 hover:text-white transition-all items-center gap-2 group overflow-hidden"
                        title="Ver Stock Bajo">
                        <!-- Pulse effect -->
                        <span class="absolute inset-0 bg-red-400/20 animate-ping rounded-full"></span>
                        
                        <div class="relative z-10 flex items-center gap-2">
                            <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                            <span class="text-xs font-black"><?php echo $lowStockCount; ?></span>
                            <span class="text-[10px] font-bold uppercase tracking-wide opacity-80">Críticos</span>
                        </div>
                    </button>
                    <!-- Mobile Icon Only -->
                    <button @click="$dispatch('open-low-stock-global')" class="md:hidden w-9 h-9 rounded-full bg-red-500/10 border border-red-500/20 text-red-500 flex items-center justify-center relative">
                         <span class="absolute top-0 right-0 w-2.5 h-2.5 bg-red-500 rounded-full border border-gray-900"></span>
                         <i class="fa-solid fa-exclamation"></i>
                    </button>
                <?php endif; ?>

                <div class="h-6 w-px bg-gray-200 dark:bg-white/10 hidden md:block"></div>

                <!-- RATE WIDGET -->
                <div x-data="rateWidget" class="hidden md:flex items-center gap-2 bg-gray-100 dark:bg-white/5 px-3 py-1.5 rounded-xl border border-transparent focus-within:border-brand-500 focus-within:bg-white dark:focus-within:bg-black/40 transition-all">
                    <span class="text-xs font-bold text-gray-500">Bs/$ BCV</span>
                    <input x-ref="rateInput" 
                           type="number" 
                           step="0.01" 
                           x-model="rate" 
                           @keydown.enter="updateRate()"
                           class="w-16 bg-transparent text-sm font-black text-gray-900 dark:text-white focus:outline-none text-right appearance-none"
                           :disabled="loading">
                    <button @click="updateRate()" class="text-xs text-brand-500 hover:text-brand-600" :class="{'opacity-50': loading}">
                        <i class="fa-solid" :class="loading ? 'fa-spinner fa-spin' : 'fa-check'"></i>
                    </button>
                </div>

                <div class="h-6 w-px bg-gray-200 dark:bg-white/10 hidden md:block"></div>

                <!-- Theme Toggle -->
                <button @click="$store.theme.toggle()" 
                    class="w-9 h-9 rounded-full flex items-center justify-center transition-all bg-gray-100 hover:bg-gray-200 dark:bg-white/5 dark:hover:bg-white/10 text-gray-500 dark:text-gray-400 hover:scale-105 active:scale-95" 
                    title="Cambiar Tema">
                    <i class="fa-solid text-sm" :class="$store.theme.isDark ? 'fa-sun text-amber-400' : 'fa-moon text-blue-600'"></i>
                </button>

                <!-- Insumos Lock (Specific) -->
                <?php if ($currentPage == 'insumos.php'): ?>
                    <?php $insumosUnlocked = isset($_SESSION['insumos_unlocked']) && $_SESSION['insumos_unlocked']; ?>
                    <button onclick="<?php echo $insumosUnlocked ? "window.location.href='lock_insumos.php'" : "window.location.reload()"; ?>" 
                        class="w-9 h-9 rounded-full flex items-center justify-center transition-all hover:scale-105 active:scale-95 <?php echo $insumosUnlocked ? 'bg-orange-500/10 text-orange-500 hover:bg-orange-500/20' : 'bg-gray-100 dark:bg-white/5 text-gray-500'; ?>"
                        title="<?php echo $insumosUnlocked ? 'Bloquear Insumos' : 'Desbloquear Insumos'; ?>">
                        <i class="fa-solid text-sm <?php echo $insumosUnlocked ? 'fa-lock-open' : 'fa-lock'; ?>"></i>
                    </button>
                <?php endif; ?>

                <!-- Lock Toggle -->
                <button @click="$store.nav.toggle()" 
                    class="w-9 h-9 rounded-full flex items-center justify-center transition-all hover:scale-105 active:scale-95" 
                    :class="$store.nav.adminMode ? 'bg-red-500/10 text-red-500 hover:bg-red-500/20' : 'bg-brand-500/10 text-brand-500 hover:bg-brand-500/20'"
                    :title="$store.nav.adminMode ? 'Cerrar Sesión Admin' : 'Desbloquear Menú'">
                    <i class="fa-solid text-sm" :class="$store.nav.adminMode ? 'fa-lock-open' : 'fa-lock'"></i>
                </button>

                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="md:hidden ml-1 p-2 text-gray-500 dark:text-gray-400 focus:outline-none">
                    <i class="fa-solid fa-bars text-xl" :class="{'hidden': mobileMenuOpen, 'block': !mobileMenuOpen }"></i>
                    <i class="fa-solid fa-xmark text-xl hidden" :class="{'block': mobileMenuOpen, 'hidden': !mobileMenuOpen }"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="md:hidden border-t border-gray-200 dark:border-white/5 bg-white/95 dark:bg-[#0f172a]/95 backdrop-blur-xl" 
         x-show="mobileMenuOpen" 
         x-collapse>
        <div class="px-4 pt-4 pb-6 space-y-2">
            <a href="index.php" class="<?php echo $currentPage == 'index.php' ? 'bg-brand-500/10 text-brand-600 dark:text-brand-400' : 'text-gray-600 dark:text-gray-400'; ?> block px-4 py-3 rounded-xl font-bold text-sm flex items-center gap-3">
                <i class="fa-solid fa-cash-register w-5"></i> Punto de Venta
            </a>
            <a href="sales_history.php" class="<?php echo $currentPage == 'sales_history.php' ? 'bg-brand-500/10 text-brand-600 dark:text-brand-400' : 'text-gray-600 dark:text-gray-400'; ?> block px-4 py-3 rounded-xl font-bold text-sm flex items-center gap-3">
                <i class="fa-solid fa-clock-rotate-left w-5"></i> Historial
            </a>
            <a href="insumos.php" class="<?php echo $currentPage == 'insumos.php' ? 'bg-brand-500/10 text-brand-600 dark:text-brand-400' : 'text-gray-600 dark:text-gray-400'; ?> block px-4 py-3 rounded-xl font-bold text-sm flex items-center gap-3">
                <i class="fa-solid fa-boxes-packing w-5"></i> Materia Prima
            </a>
            
            <div x-show="$store.nav.adminMode" class="border-t border-gray-200 dark:border-white/5 pt-2 mt-2 space-y-2">
                <p class="px-4 text-[10px] font-black uppercase text-gray-400 tracking-widest">Administración</p>
                <a href="inventory.php" class="text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 block px-4 py-3 rounded-xl font-medium text-sm flex items-center gap-3">
                    <i class="fa-solid fa-plus-circle w-5"></i> Nuevo Producto
                </a>
                <a href="catalogue.php" class="text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 block px-4 py-3 rounded-xl font-medium text-sm flex items-center gap-3">
                    <i class="fa-solid fa-list-ul w-5"></i> Catálogo
                </a>
                <a href="categories.php" class="text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 block px-4 py-3 rounded-xl font-medium text-sm flex items-center gap-3">
                    <i class="fa-solid fa-tags w-5"></i> Categorías
                </a>
                <a href="settings.php" class="text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 block px-4 py-3 rounded-xl font-medium text-sm flex items-center gap-3">
                    <i class="fa-solid fa-gear w-5"></i> Configuración
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- ALERT / TOAST CONTAINER -->
<div class="fixed top-24 right-4 z-50 flex flex-col gap-2 pointer-events-none">
    <template x-for="notif in $store.nav.notifications" :key="notif.id">
        <div x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-x-8"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-end="opacity-0 scale-90"
             class="pointer-events-auto bg-slate-900 text-white dark:bg-white dark:text-slate-900 px-4 py-3 rounded-xl shadow-2xl flex items-center gap-3 min-w-[300px] border border-white/10">
            <div :class="notif.type === 'error' ? 'bg-red-500' : 'bg-emerald-500'" class="w-2 h-2 rounded-full"></div>
            <div>
                <p class="text-sm font-bold" x-text="notif.message"></p>
                <p class="text-[10px] opacity-70" x-text="notif.subtext"></p>
            </div>
        </div>
    </template>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.store('nav', {
        adminMode: <?php echo isset($_SESSION['admin_unlocked']) && $_SESSION['admin_unlocked'] ? 'true' : 'false'; ?>,
        notifications: [],
        
        toggle() {
            if (this.adminMode) {
                // Logout Logic if needed, or just redirect to lock
                window.location.href = 'lock.php';
            } else {
                window.location.href = 'pin_access.php';
            }
        },

        notify(type, message, subtext = '') {
            const id = Date.now();
            this.notifications.push({ id, type, message, subtext });
            setTimeout(() => {
                this.notifications = this.notifications.filter(n => n.id !== id);
            }, 4000);
        }
    });

    Alpine.data('rateWidget', () => ({
        rate: 0,
        loading: false,

        async init() {
            await this.fetchRate();
        },

        async fetchRate() {
            try {
                const res = await fetch('api/settings.php');
                const json = await res.json();
                if(json.status === 'success') {
                    this.rate = parseFloat(json.data.exchange_rate_global);
                }
            } catch(e) {}
        },

        async updateRate() {
            if(this.rate <= 0) return;
            this.loading = true;

            const formData = new FormData();
            formData.append('action', 'quick_update_rate');
            formData.append('rate', this.rate);

            try {
                const res = await fetch('api/settings.php', { method: 'POST', body: formData });
                const json = await res.json();

                if(json.status === 'success') {
                    Alpine.store('nav').notify('success', 'Tasa Actualizada', json.message);
                    this.rate = parseFloat(json.new_rate); // Sync
                    // Dispatch event for other components to reload if needed
                    window.dispatchEvent(new CustomEvent('rate-updated', { detail: this.rate }));
                    
                    // Redirect to index.php after a short delay to allow the notification to be seen
                    setTimeout(() => {
                        window.location.href = 'index.php';
                    }, 500);
                } else {
                     Alpine.store('nav').notify('error', 'Error', json.message);
                     // Revert on error to fetching fresh
                     this.fetchRate();
                }
            } catch(e) {
                 Alpine.store('nav').notify('error', 'Error de Conexión');
            } finally {
                this.loading = false;
                // Defocus input
                this.$refs.rateInput.blur();
            }
        }
    }));
});
</script>

<div class="h-20"></div> <!-- Spacer for fixed header -->
