<!DOCTYPE html>
<html lang="es" x-data :class="{ 'dark': $store.theme.isDark }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Jabones Premium</title>
    <!-- Tailwind CSS -->
    <script src="assets/js/tailwindcss.js"></script>
    <script>
        // Check local storage immediately to avoid FOUC
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            900: '#0c4a6e',
                        },
                        dark: {
                            bg: '#0f172a', /* slate-900 */
                            card: '#1e293b', /* slate-800 */
                            surface: '#334155',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <!-- SweetAlert2 -->
    <script src="assets/js/sweetalert2.js"></script>
    <!-- Alpine.js -->
    <script defer src="assets/js/alpine.js"></script>
    
    <!-- Theme Store -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', {
                isDark: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
                
                toggle() {
                    this.isDark = !this.isDark;
                    localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
                    if (this.isDark) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                }
            });

            Alpine.store('nav', {
                adminMode: localStorage.getItem('adminMode') === 'true',
                showPinModal: false,
                pin: '',
                loading: false,

                toggle() {
                    if (this.adminMode) {
                        this.adminMode = false;
                        localStorage.setItem('adminMode', 'false');
                        this.showToast('info', 'Modo Ventas (Bloqueado)');
                        // Redirect to lock.php to ensure PHP session is killed
                        window.location.href = 'lock.php';
                    } else {
                        this.pin = '';
                        this.showPinModal = true;
                        setTimeout(() => document.getElementById('pinInput')?.focus(), 100);
                    }
                },

                async verifyPin() {
                    if (!this.pin) return;
                    this.loading = true;
                    
                    try {
                        const formData = new FormData();
                        formData.append('action', 'check_pin');
                        formData.append('pin', this.pin);

                        const res = await fetch('api/settings.php', {
                            method: 'POST',
                            body: formData
                        });
                        const data = await res.json();

                        if (data.status === 'success') {
                            this.adminMode = true;
                            localStorage.setItem('adminMode', 'true');
                            this.showPinModal = false;
                            this.showToast('success', 'Modo Administrador Activado');
                            // Reload to enable PHP protected routes if stuck on a public page
                            // or just stay here. User can now navigate.
                        } else {
                            this.showToast('error', data.message || 'PIN Incorrecto');
                            this.pin = '';
                            document.getElementById('pinInput')?.focus();
                        }
                    } catch (e) {
                        this.showToast('error', 'Error de conexión');
                    } finally {
                        this.loading = false;
                    }
                },

                closeModal() {
                    this.showPinModal = false;
                    this.pin = '';
                },

                showToast(icon, title) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: icon,
                        title: title,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        });
    </script>

    <!-- Google Fonts -->
    <!-- Google Fonts Removed for Offline Mode -->
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    
    <style>
        body { font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; }
        
        /* Glass Effect - Dark Mode Default */
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        .dark .glass {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .glass-panel {
            background: rgba(0, 0, 0, 0.02);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        .dark .glass-panel {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        .dark ::-webkit-scrollbar-track { background: #0f172a; }
        
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .dark ::-webkit-scrollbar-thumb { background: #334155; }
        
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .dark ::-webkit-scrollbar-thumb:hover { background: #475569; }

        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 dark:bg-dark-bg dark:text-white min-h-screen flex flex-col overflow-x-hidden selection:bg-brand-500 selection:text-white transition-colors duration-300">

<!-- PIN Modal -->
<div x-data x-show="$store.nav.showPinModal" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0">
    
    <div class="bg-gray-900 border border-gray-700 rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden" 
         @click.away="$store.nav.closeModal()">
        
        <!-- Header / Icon -->
        <div class="pt-8 pb-4 text-center">
            <div class="relative inline-block">
                <div class="w-16 h-16 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-2 border border-gray-700">
                    <i class="fa-solid fa-lock text-2xl text-gray-400"></i>
                </div>
                <!-- Red dot -->
                <span class="absolute top-0 right-0 w-4 h-4 bg-red-500 border-2 border-gray-900 rounded-full"></span>
            </div>
            <h3 class="text-xl font-bold text-white mt-2">Acceso Restringido</h3>
            <p class="text-gray-400 text-sm mt-1 px-6">Ingresa el PIN de seguridad para acceder a este módulo.</p>
        </div>

        <!-- Body -->
        <div class="px-6 pb-6">
            <div class="mb-4">
                <input type="password" id="pinInput" x-model="$store.nav.pin" 
                       @keyup.enter="$store.nav.verifyPin()"
                       class="w-full bg-gray-800 border border-gray-700 text-white text-center text-2xl tracking-[0.5em] rounded-lg py-3 focus:ring-2 focus:ring-brand-500 focus:border-transparent outline-none transition-all placeholder-gray-600"
                       placeholder="••••" maxlength="8">
            </div>

            <button @click="$store.nav.verifyPin()" 
                    class="w-full bg-brand-600 hover:bg-brand-500 text-white font-bold py-3 rounded-xl shadow-lg shadow-brand-500/20 transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2"
                    :disabled="$store.nav.loading">
                <i class="fa-solid fa-lock-open" x-show="!$store.nav.loading"></i>
                <svg x-show="$store.nav.loading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span x-text="$store.nav.loading ? 'Verificando...' : 'Desbloquear'"></span>
            </button>
            
            <button @click="$store.nav.closeModal()" class="w-full mt-4 text-gray-500 hover:text-white text-sm transition-colors flex items-center justify-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Volver al Punto de Venta
            </button>
        </div>
    </div>
</div>

<!-- Global Low Stock Modal -->
<div x-data="globalLowStock" @open-low-stock-global.window="openModal()">
    <!-- Main Modal -->
    <div x-show="isOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-end sm:items-center justify-center sm:p-4 bg-black/60 backdrop-blur-sm" x-transition.opacity>
        <div @click.outside="isOpen = false" 
             class="bg-gray-900 border border-t border-white/10 rounded-t-2xl sm:rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all max-h-[85vh] flex flex-col pt-safe-top"
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="translate-y-full sm:translate-y-10 sm:opacity-0 sm:scale-95" 
             x-transition:enter-end="translate-y-0 sm:translate-y-0 sm:opacity-100 sm:scale-100">
            
            <!-- Header -->
            <div class="px-6 py-4 border-b border-white/10 flex justify-between items-center bg-black/20 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-red-500/10 flex items-center justify-center text-red-500">
                        <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white">Alertas de Stock</h3>
                        <p class="text-xs text-red-400 font-mono" x-show="products.length > 0"><span x-text="products.length"></span> Productos Críticos</p>
                    </div>
                </div>
                <button @click="isOpen = false" class="text-gray-500 hover:text-white transition-colors bg-white/5 w-8 h-8 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>

            <!-- List Body -->
            <div class="p-4 overflow-y-auto custom-scrollbar flex-grow space-y-3">
                <template x-for="p in products" :key="'global-low-'+p.id">
                    <div class="flex items-center gap-4 group p-3 rounded-xl transition-all relative overflow-hidden"
                         :class="p.is_raw_material == 1 ? 'bg-orange-500/10 border border-orange-500/30' : 'bg-white/5 border border-white/5 hover:border-brand-500/30'">
                         
                         <!-- Insumo Watermark/Badge -->
                         <div x-show="p.is_raw_material == 1" class="absolute -right-2 -top-2 text-orange-500/10 z-0">
                            <i class="fa-solid fa-boxes-packing text-5xl transform rotate-12"></i>
                         </div>
                        <!-- Image or Solid Placeholder -->
                        <div class="w-14 h-14 rounded-lg overflow-hidden flex-shrink-0 relative"
                             :class="!p.image_path ? 'bg-gray-700 flex items-center justify-center' : 'bg-gray-900/50'">
                            
                            <template x-if="p.image_path">
                                <img :src="p.image_path" class="w-full h-full object-cover">
                            </template>
                            
                            <template x-if="!p.image_path">
                                <i class="fa-solid text-gray-500 text-xl" :class="p.is_liquid == 1 ? 'fa-bottle-droplet' : 'fa-box'"></i>
                            </template>
                             
                             <!-- Type Badge -->
                             <div class="absolute bottom-0 right-0 px-1 rounded-tl-md backdrop-blur-sm z-10"
                                  :class="p.is_raw_material == 1 ? 'bg-orange-600/80 text-white' : 'bg-black/60 text-white/80'">
                                <i class="fa-solid text-[10px]" :class="p.is_raw_material == 1 ? 'fa-boxes-packing' : (p.is_liquid == 1 ? 'fa-droplet' : 'fa-cube')"></i>
                             </div>
                        </div>
                        
                        <div class="flex-grow min-w-0 z-10">
                            <h4 class="font-bold text-sm truncate flex items-center gap-2" 
                                :class="p.is_raw_material == 1 ? 'text-orange-400' : 'text-white'">
                                <span x-text="p.name"></span>
                                <span x-show="p.is_raw_material == 1" class="text-[9px] bg-orange-500 text-white px-1.5 py-0.5 rounded uppercase font-black tracking-wider">Materia Prima</span>
                            </h4>
                            <div class="flex items-center gap-3 mt-1.5">
                                <div class="flex flex-col">
                                    <span class="text-[10px] uppercase text-gray-500 font-bold">Actual</span>
                                    <span class="text-sm font-mono font-bold text-red-400">
                                        <span x-text="parseFloat(p.stock_quantity).toFixed(2)"></span> <span x-text="p.display_unit.substring(0,2)"></span>
                                    </span>
                                </div>
                                <div class="w-px h-6 bg-white/10"></div>
                                <div class="flex flex-col">
                                    <span class="text-[10px] uppercase text-gray-500 font-bold">Mínimo</span>
                                    <span class="text-sm font-mono text-gray-400" x-text="p.min_stock"></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Restock Button -->
                        <div x-show="$store.nav.adminMode">
                            <button @click="quickStock(p)" 
                                class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-lg shadow-emerald-500/20 active:scale-95 transition-all flex flex-col items-center gap-0.5">
                                <i class="fa-solid fa-plus text-sm"></i>
                                <span>Reponer</span>
                            </button>
                        </div>
                        <div x-show="!$store.nav.adminMode" class="opacity-50 grayscale">
                             <i class="fa-solid fa-lock text-gray-500"></i>
                        </div>
                    </div>
                </template>

                <div x-show="products.length === 0" class="text-center py-12 opacity-50">
                    <i class="fa-solid fa-check-circle text-5xl mb-4 text-emerald-500/50"></i>
                    <p class="text-lg text-white font-bold">¡Todo Excelente!</p>
                    <p class="text-sm text-gray-500">No hay productos con stock crítico</p>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="p-4 border-t border-white/10 bg-black/20 text-center">
                <p x-show="$store.nav.adminMode" class="text-[10px] text-emerald-400/70">Sistema Desbloqueado: Puede reponer stock.</p>
                <p x-show="!$store.nav.adminMode" class="text-[10px] text-red-400/70 font-bold"><i class="fa-solid fa-lock mr-1"></i> Sistema Bloqueado: Solo lectura.</p>
            </div>
        </div>
    </div>
    
    <!-- Mini Restock Modal -->
    <div x-show="showRestockForm" style="display: none;" class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-black/80 backdrop-blur-md pointer-events-auto" x-transition.opacity>
         <div @click.outside="showRestockForm = false" class="bg-gray-900 border border-emerald-500/30 rounded-2xl shadow-2xl w-full max-w-sm p-6 relative">
             <h3 class="text-white font-bold mb-1">Reponer Stock Rápido</h3>
             <p class="text-xs text-gray-400 mb-4" x-text="targetProduct.name"></p>
             
             <div class="flex gap-2 mb-4">
                 <input type="number" x-model="restockAmount" class="w-full bg-black/40 border border-white/10 rounded-lg px-3 py-2 text-white font-mono text-xl focus:border-emerald-500 focus:outline-none" placeholder="0.00" autofocus>
                 <div class="flex items-center justify-center bg-white/5 rounded-lg px-3 border border-white/5 text-gray-400 text-xs font-bold" x-text="targetProduct.display_unit"></div>
             </div>
             
             <div class="flex justify-end gap-2">
                 <button @click="showRestockForm = false" class="px-3 py-2 rounded-lg text-gray-400 hover:text-white text-xs">Cancelar</button>
                 <button @click="confirmRestock()" class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-lg shadow-emerald-500/20">Confirmar</button>
             </div>
         </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('globalLowStock', () => ({
        isOpen: false,
        products: [],
        showRestockForm: false,
        targetProduct: {},
        restockAmount: '',

        async init() {
            try {
                await this.fetchLowStock();
            } catch(e) {
                console.warn('LowStock Init Error', e);
            }
        },

        async openModal() {
            await this.fetchLowStock();
            this.isOpen = true;
        },

        async fetchLowStock() {
            try {
                const res = await fetch('api/products.php?action=list');
                if (!res.ok) throw new Error('Network response was not ok');
                const json = await res.json();
                if(json && json.status === 'success' && Array.isArray(json.data)) {
                    this.products = json.data.filter(p => parseFloat(p.stock_quantity) <= parseFloat(p.min_stock));
                }
            } catch(e) { 
                console.error('Error fetching low stock', e); 
                // Don't crash the component
            }
        },

        quickStock(product) {
            this.targetProduct = product;
            this.restockAmount = '';
            this.showRestockForm = true;
        },

        async confirmRestock() {
             if (!this.restockAmount || parseFloat(this.restockAmount) <= 0) return;

             try {
                const formData = new FormData();
                formData.append('action', 'update_stock');
                formData.append('id', this.targetProduct.id);
                formData.append('amount', this.restockAmount);
                
                await fetch('api/products.php', { method: 'POST', body: formData });
                
                Swal.fire({ icon: 'success', title: 'Stock Actualizado', toast: true, position: 'top-end', timer: 1500, showConfirmButton: false, background: '#1e293b', color: '#fff' });
                
                this.showRestockForm = false;
                await this.fetchLowStock();
                window.dispatchEvent(new CustomEvent('refresh-catalogue'));
             } catch(e) {
                 Swal.fire({ icon: 'error', title: 'Error al actualizar', toast: true, position: 'top-end', timer: 2000 });
             }
        }
    }));
});
</script>
