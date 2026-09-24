<?php
session_start();
// Security Gate
if (!isset($_SESSION['insumos_unlocked']) || $_SESSION['insumos_unlocked'] !== true) {
    header("Location: pin_insumos.php?redirect=" . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

require_once 'includes/header.php';
require_once 'includes/nav.php';
?>

<div x-data="insumoControl()" class="w-full px-4 md:px-8 pb-20 pt-8 min-h-screen">

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-6">
        <div class="text-center md:text-left">
            <h1 class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-red-500 drop-shadow-sm flex items-center gap-3 justify-center md:justify-start">
               <i class="fa-solid fa-boxes-packing text-orange-500"></i> Control de Materia Prima
            </h1>
            <p class="text-gray-400 font-light mt-2 text-lg">Gestión de consumo interno y bajas administrativas.</p>
        </div>
        
        <!-- Action Status -->
        <div class="flex items-center gap-3">
            <button @click="openHistory()" class="px-5 py-2.5 rounded-xl bg-gray-800 dark:bg-white/10 hover:bg-gray-700 dark:hover:bg-white/20 text-white font-bold text-xs uppercase tracking-wide transition-all flex items-center gap-2 border border-white/5 shadow-lg">
                <i class="fa-solid fa-clock-rotate-left"></i> Historial
            </button>
            <div class="glass px-6 py-3 rounded-full border border-orange-500/20 flex items-center gap-3">
                <div class="w-3 h-3 rounded-full bg-orange-500 animate-pulse"></div>
                <span class="text-orange-900 dark:text-orange-100 font-bold uppercase text-xs tracking-wider md:block hidden">Modo Gerencial Activo</span>
            </div>
        </div>
    </div>

    <!-- Search Section -->
    <div class="max-w-3xl mx-auto mb-12 relative z-20">
        <div class="relative group">
            <div class="absolute -inset-1 bg-gradient-to-r from-orange-600 to-red-600 rounded-2xl blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
            <div class="relative">
                <i class="fa-solid fa-search absolute left-5 top-5 text-gray-400 text-xl group-focus-within:text-orange-500 transition-colors"></i>
                <input 
                    type="text" 
                    x-model="searchQuery" 
                    @input.debounce.300ms="searchProducts()"
                    class="w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl py-5 pl-14 pr-6 text-lg text-gray-900 dark:text-white placeholder-gray-400 shadow-xl focus:outline-none focus:ring-2 focus:ring-orange-500/50 transition-all"
                    placeholder="Buscar materia prima por nombre, código o SKU..."
                    autofocus
                >
                <!-- Loading Spinner -->
                <div x-show="loading" class="absolute right-5 top-5">
                    <i class="fa-solid fa-circle-notch fa-spin text-orange-500 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <template x-for="product in products" :key="product.id">
            <div class="glass flex flex-col h-full bg-white dark:bg-[#1e293b] border border-gray-200 dark:border-gray-700/50 rounded-2xl p-5 hover:border-orange-500/50 hover:shadow-xl hover:shadow-orange-500/10 transition-all duration-300 group relative overflow-hidden">
                
                <!-- Background Gradient Effect -->
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-orange-500 to-red-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>

                <!-- Top Row: Icons & Badges -->
                <div class="flex justify-between items-start mb-6">
                    <!-- Icon: Blue for Liquid, Green for Solid -->
                    <div class="w-10 h-10 rounded-xl bg-gray-50 dark:bg-gray-800/80 flex items-center justify-center border border-gray-100 dark:border-gray-700 transition-colors shadow-sm"
                         :class="product.is_liquid == 1 ? 'text-sky-500 dark:text-sky-400 group-hover:text-sky-600' : 'text-emerald-500 dark:text-emerald-400 group-hover:text-emerald-600'">
                         <i class="fa-solid text-lg" :class="product.is_liquid == 1 ? 'fa-bottle-droplet' : 'fa-box-open'"></i>
                    </div>
                    
                    <span class="px-3 py-1.5 rounded-lg text-xs font-bold border backdrop-blur-md"
                          :class="parseFloat(product.stock_quantity) > 0 ? (product.is_liquid == 1 ? 'bg-sky-500/10 text-sky-600 border-sky-500/20' : 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20') : 'bg-red-500/10 text-red-500 border-red-500/20'">
                        <span x-text="formatNumber(product.stock_quantity)"></span>
                        <span class="text-[10px] uppercase opacity-70 ml-1" x-text="product.display_unit.substring(0,3)"></span>
                    </span>
                </div>

                <!-- Product Info (Centered) -->
                <div class="flex-grow flex flex-col items-center text-center mb-6 z-10">
                    <!-- Image with Ring -->
                    <div class="relative w-24 h-24 mb-4 group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 rounded-full border-2 border-gray-100 dark:border-gray-700 group-hover:border-orange-500/50 transition-colors"></div>
                        <div class="w-full h-full rounded-full overflow-hidden p-1">
                             <img :src="product.image_path && product.image_path !== 'null' ? product.image_path : 'assets/img/no-image.png'" 
                                  class="w-full h-full object-cover rounded-full bg-gray-100 dark:bg-gray-800"
                                  onerror="this.src='https://ui-avatars.com/api/?name=' + this.alt + '&background=random'">
                        </div>
                    </div>
                    
                    <h3 class="font-bold text-gray-900 dark:text-gray-100 text-lg leading-snug mb-2 line-clamp-2 px-2" x-text="product.name"></h3>
                    
                    <!-- Code Badge -->
                    <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-md bg-gray-100 dark:bg-black/20 text-xs text-gray-500 dark:text-gray-400 font-mono border border-gray-200 dark:border-white/5">
                        <i class="fa-solid fa-barcode opacity-50"></i>
                        <span x-text="(product.sku && product.sku !== 'null') ? product.sku : (product.barcode && product.barcode !== 'null' ? product.barcode : 'S/C')"></span>
                    </div>
                </div>

                <!-- Action Button -->
                <button @click="openModal(product)" class="w-full py-3 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-600 dark:text-gray-300 font-bold text-sm hover:bg-gradient-to-r hover:from-orange-600 hover:to-red-600 hover:border-transparent hover:text-white transition-all flex items-center justify-center gap-2 group/btn shadow-sm hover:shadow-orange-500/20">
                    <span>Dar de Baja</span>
                    <i class="fa-solid fa-arrow-right-from-bracket opacity-50 group-hover/btn:opacity-100 transition-opacity transform group-hover/btn:translate-x-1"></i>
                </button>
            </div>
        </template>
    </div>

    <!-- Empty State -->
    <div x-show="products.length === 0 && searchQuery !== '' && !loading" class="text-center py-20 opacity-50">
        <i class="fa-solid fa-box-open text-6xl text-gray-600 mb-4"></i>
        <p class="text-xl font-medium text-gray-400">No se encontraron productos</p>
    </div>
    
    <div x-show="products.length === 0 && searchQuery === ''" class="text-center py-20 opacity-30">
        <i class="fa-solid fa-magnifying-glass text-6xl text-gray-600 mb-4"></i>
        <p class="text-lg font-medium text-gray-400">Utiliza el buscador para encontrar insumos</p>
    </div>


    <!-- DECREMENT MODAL -->
    <div x-show="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center px-4" style="display: none;">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm transition-opacity" @click="modalOpen = false"></div>
        
        <!-- Modal Content -->
        <div class="bg-white dark:bg-gray-900 w-full max-w-md rounded-2xl shadow-2xl relative z-10 overflow-hidden border border-gray-200 dark:border-gray-700 animate-fade-in-up">
            
            <div class="bg-gradient-to-r from-orange-500 to-red-600 p-6 text-white text-center">
                <i class="fa-solid fa-circle-minus text-4xl mb-2 opacity-90"></i>
                <h2 class="text-xl font-black uppercase tracking-wide">Baja de Inventario</h2>
                <p class="text-orange-100 text-sm font-medium mt-1">
                    Producto: <span class="font-bold text-white underline" x-text="selectedProduct?.name"></span>
                </p>
                <p class="text-orange-100 text-xs mt-1">Stock Actual: <span class="font-mono text-white" x-text="selectedProduct ? formatNumber(selectedProduct.stock_quantity) : 0"></span></p>
            </div>

            <div class="p-8 space-y-6">
                <!-- Quantity Input -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Cantidad a Retirar</label>
                    <div class="flex items-center gap-3">
                         <button @click="adjustQty(-1)" class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-800 text-gray-500 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors flex items-center justify-center">
                            <i class="fa-solid fa-minus"></i>
                        </button>
                        <input x-model="qty" type="number" step="0.01" class="flex-1 bg-gray-50 dark:bg-black/20 border-2 border-orange-500/20 rounded-xl py-3 text-center text-2xl font-bold text-gray-900 dark:text-white focus:border-orange-500 focus:outline-none font-mono">
                        <button @click="adjustQty(1)" class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-800 text-gray-500 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors flex items-center justify-center">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                </div>

                <!-- Reason Select -->
                <div> <!-- Updated Wrapper -->
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Motivo de Baja</label>
                    <div class="grid grid-cols-2 gap-3">
                        <template x-for="r in ['Consumo Interno', 'Merma', 'Vencimiento', 'Ajuste de Inventario', 'Uso Gerencial', 'Donacion']">
                            <button @click="reason = r" 
                                class="px-4 py-3 rounded-xl text-xs font-bold border transition-all duration-200 flex items-center gap-2 justify-center"
                                :class="reason === r 
                                    ? 'bg-orange-500 text-white border-orange-500 shadow-lg shadow-orange-500/20 transform scale-[1.02]' 
                                    : 'bg-white dark:bg-white/5 border-gray-200 dark:border-white/10 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/10'">
                                <i class="fa-solid" :class="{
                                    'Consumo Interno': 'fa-broom',
                                    'Merma': 'fa-trash-can',
                                    'Vencimiento': 'fa-calendar-xmark',
                                    'Ajuste de Inventario': 'fa-scale-balanced',
                                    'Uso Gerencial': 'fa-user-tie',
                                    'Donacion': 'fa-hand-holding-heart'
                                }[r] || 'fa-circle'"></i>
                                <span x-text="r"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <div class="flex gap-4 pt-2">
                    <button @click="modalOpen = false" class="flex-1 py-3 rounded-xl border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400 font-bold hover:bg-gray-50 dark:hover:bg-gray-800">
                        Cancelar
                    </button>
                    <button @click="submitDecrement()" class="flex-1 py-3 rounded-xl bg-orange-600 hover:bg-orange-500 text-white font-bold shadow-lg shadow-orange-500/20 transition-transform active:scale-95 flex items-center justify-center gap-2">
                        <span x-show="!processing">Confirmar Baja</span>
                        <i x-show="processing" class="fa-solid fa-circle-notch fa-spin"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- HISTORY MODAL -->
    <div x-show="historyOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-md" @click="historyOpen = false"></div>
        
        <div class="bg-white dark:bg-gray-900 w-full max-w-4xl rounded-2xl shadow-2xl relative z-10 overflow-hidden border border-gray-200 dark:border-gray-700 h-[80vh] flex flex-col animate-zoom-in">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-black/20">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-orange-500"></i> Historial de Movimientos
                </h3>
                <button @click="historyOpen = false" class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-500 dark:text-gray-300 flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>

            <!-- Content -->
            <div class="flex-1 overflow-y-auto p-0 custom-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead class="sticky top-0 bg-gray-100 dark:bg-gray-800 text-xs font-bold text-gray-500 uppercase tracking-wider z-10 shadow-sm">
                        <tr>
                            <th class="px-6 py-3">Fecha</th>
                            <th class="px-6 py-3">Insumo-Materia Prima</th>
                            <th class="px-6 py-3">Motivo</th>
                            <th class="px-6 py-3 text-right">Cantidad</th>
                            <th class="px-6 py-3 text-right">Stock Final</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <template x-for="log in historyLogs" :key="log.id">
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                <td class="px-6 py-4 text-xs font-mono text-gray-500" x-text="formatDate(log.created_at)"></td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-gray-900 dark:text-white" x-text="parseDesc(log.description)"></p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wide border"
                                        :class="{
                                            'bg-orange-100 text-orange-600 border-orange-200': parseDetails(log.details).reason === 'Consumo Interno',
                                            'bg-red-100 text-red-600 border-red-200': parseDetails(log.details).reason === 'Merma' || parseDetails(log.details).reason === 'Vencimiento',
                                            'bg-blue-100 text-blue-600 border-blue-200': parseDetails(log.details).reason === 'Ajuste de Inventario',
                                            'bg-gray-100 text-gray-600 border-gray-200': !parseDetails(log.details).reason
                                        }">
                                        <span x-text="parseDetails(log.details).reason || 'Desconocido'"></span>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-red-500 font-mono">
                                    <span x-text="parseDetails(log.details).decremented"></span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm text-gray-500 font-mono">
                                    <span x-text="parseDetails(log.details).new_stock"></span>
                                </td>
                            </tr>
                        </template>
                        
                        <template x-if="historyLogs.length === 0 && !historyLoading">
                             <tr>
                                 <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                     <i class="fa-solid fa-clipboard-list text-4xl mb-2 opacity-50"></i>
                                     <p>No hay registros recientes</p>
                                 </td>
                             </tr>
                        </template>
                        
                        <template x-if="historyLoading">
                             <tr>
                                 <td colspan="5" class="px-6 py-12 text-center text-gray-400 animate-pulse">
                                     Cargando...
                                 </td>
                             </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('insumoControl', () => ({
        searchQuery: '',
        products: [],
        loading: false,
        modalOpen: false,
        selectedProduct: null,
        qty: 1,
        reason: 'Consumo Interno',
        processing: false,
        
        // History State
        historyOpen: false,
        historyLogs: [],
        historyLoading: false,

        init() {
            this.searchProducts();
        },

        async searchProducts() {
            // Allow empty search to fetch all
            this.loading = true;
            try {
                const res = await fetch(`api/insumos.php?action=search&q=${encodeURIComponent(this.searchQuery)}`);
                const json = await res.json();
                if (json.status === 'success') {
                    this.products = json.data;
                }
            } catch (e) {
                console.error(e);
            } finally {
                this.loading = false;
            }
        },

        openModal(product) {
            this.selectedProduct = product;
            this.qty = 1;
            this.reason = 'Consumo Interno';
            this.modalOpen = true;
        },

        adjustQty(amount) {
            const newVal = parseFloat(this.qty) + amount;
            if (newVal > 0) this.qty = newVal;
        },

        formatNumber(num) {
            return parseFloat(num).toFixed(2);
        },
        
        // --- History Logic ---
        async openHistory() {
            this.historyOpen = true;
            this.historyLoading = true;
            this.historyLogs = [];
            
            try {
                const res = await fetch('api/insumos.php?action=history');
                const json = await res.json();
                if(json.status === 'success') {
                    this.historyLogs = json.data;
                }
            } catch(e) {
                console.error(e);
            } finally {
                this.historyLoading = false;
            }
        },
        
        formatDate(dateStr) {
            if(!dateStr) return '';
            const d = new Date(dateStr);
            return d.toLocaleString('es-VE');
        },
        
        parseDetails(details) {
            try {
                return (typeof details === 'string') ? JSON.parse(details) : details;
            } catch(e) {
                return {};
            }
        },
        
        parseDesc(desc) {
             // Description format: "Baja por Insumo: Product Name (Reason)"
             // We want just "Product Name"
             return desc.replace('Baja por Insumo: ', '').split('(')[0].trim();
        },

        async submitDecrement() {
             if (!this.selectedProduct || this.qty <= 0) return;
             
             if (this.qty > parseFloat(this.selectedProduct.stock_quantity)) {
                 const result = await Swal.fire({
                    title: 'Stock Insuficiente',
                    text: 'La cantidad a retirar excede el stock actual. ¿Deseas continuar y dejar el inventario en negativo?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ea580c', // Orange-600
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Sí, continuar',
                    cancelButtonText: 'Cancelar',
                    background: '#1e293b',
                    color: '#fff'
                 });
                 
                 if (!result.isConfirmed) return;
             }

             this.processing = true;
             const formData = new FormData();
             formData.append('product_id', this.selectedProduct.id);
             formData.append('quantity', this.qty);
             formData.append('reason', this.reason);

             try {
                const res = await fetch(`api/insumos.php?action=decrement`, {
                    method: 'POST',
                    body: formData
                });
                const json = await res.json();
                
                if (json.status === 'success') {
                    // Update local list
                    this.selectedProduct.stock_quantity = json.new_stock;
                    this.modalOpen = false;
                    
                    // Styled Success Message
                    Swal.fire({
                        icon: 'success',
                        title: 'Baja Exitosa',
                        text: 'El inventario ha sido actualizado correctamente.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        background: '#1e293b',
                        color: '#fff'
                    });
                    
                    this.searchProducts(); // Refresh list to ensure data integrity
                } else {
                     Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: json.message,
                        background: '#1e293b',
                        color: '#fff'
                    });
                }
             } catch (e) {
                 Swal.fire({
                    icon: 'error',
                    title: 'Error de Conexión',
                    text: 'No se pudo procesar la solicitud.',
                    background: '#1e293b',
                    color: '#fff'
                });
             } finally {
                 this.processing = false;
             }
        }
    }));
});
</script>

<?php require_once 'includes/footer.php'; ?>
