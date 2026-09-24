<?php 
require_once 'includes/auth.php';
require_pin(); 
require_once 'includes/header.php'; 
?>
<?php require_once 'includes/nav.php'; ?>

<div x-data="catalogue" @refresh-catalogue.window="fetchProducts()" class="w-full px-4 md:px-8 pb-20 pt-8">
    
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-8 gap-6">
        <div class="w-full lg:w-auto text-center lg:text-left">
            <h1 class="text-3xl md:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400 drop-shadow-sm">
                Catálogo de Productos
            </h1>
            <p class="text-gray-500 mt-2 font-light text-base md:text-lg">Visualiza, filtra y administra todo tu stock.</p>
        </div>
        
        <div class="flex flex-col md:flex-row gap-4 w-full lg:w-auto">
            <!-- Search -->
            <div class="relative w-full md:w-64">
                    <i class="fa-solid fa-search absolute left-3 top-3.5 text-gray-400 dark:text-gray-500"></i>
                    <input x-model="search" type="text" placeholder="Buscar producto..." class="w-full bg-white dark:bg-dark-card border border-gray-200 dark:border-gray-700 rounded-xl py-2.5 pl-10 pr-4 text-gray-900 dark:text-white focus:outline-none focus:border-brand-500 transition-all shadow-lg">
            </div>
            
            <!-- Category Filter -->
            <div class="w-full md:w-48">
                <select x-model="filterCategory" class="w-full bg-white dark:bg-dark-card border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2.5 text-gray-900 dark:text-white focus:outline-none focus:border-brand-500 shadow-lg appearance-none">
                    <option value="">Todas las Categorías</option>
                    <template x-for="cat in categories" :key="cat.id">
                        <option :value="cat.id" x-text="cat.name"></option>
                    </template>
                </select>
            </div>



            <!-- New Button -->
            <a href="inventory.php" class="bg-brand-600 hover:bg-brand-500 text-white px-6 py-2.5 rounded-xl flex items-center justify-center gap-2 transition-all shadow-lg shadow-brand-500/20 font-bold whitespace-nowrap">
                <i class="fa-solid fa-plus"></i> <span class="hidden md:inline">Nuevo</span>
            </a>
        </div>
    </div>

    <!-- Layout Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Left Column: Main Catalogue (3 cols wide on large screens) -->
        <div class="lg:col-span-3">
             <!-- TABS NAVIGATION -->
             <div class="flex items-center gap-4 mb-8 bg-gray-100 dark:bg-black/20 p-1.5 rounded-2xl border border-gray-200 dark:border-white/5 w-fit">
                <button @click="viewMode = 'all'" 
                    class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center gap-2"
                    :class="viewMode === 'all' ? 'bg-white dark:bg-brand-600 text-brand-600 dark:text-white shadow-lg' : 'text-gray-500 hover:text-gray-700 dark:hover:text-white'">
                    <i class="fa-solid fa-list-ul"></i> Todo
                </button>
                <button @click="viewMode = 'sale'" 
                    class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center gap-2"
                    :class="viewMode === 'sale' ? 'bg-white dark:bg-blue-600 text-blue-600 dark:text-white shadow-lg' : 'text-gray-500 hover:text-gray-700 dark:hover:text-white'">
                    <i class="fa-solid fa-cart-shopping"></i> Para Venta
                </button>
                <button @click="viewMode = 'raw'" 
                    class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center gap-2"
                    :class="viewMode === 'raw' ? 'bg-white dark:bg-orange-600 text-orange-600 dark:text-white shadow-lg' : 'text-gray-500 hover:text-gray-700 dark:hover:text-white'">
                    <i class="fa-solid fa-flask"></i> Insumos
                </button>
                <button @click="viewMode = 'special'" 
                    class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center gap-2"
                    :class="viewMode === 'special' ? 'bg-white dark:bg-purple-600 text-purple-600 dark:text-white shadow-lg' : 'text-gray-500 hover:text-gray-700 dark:hover:text-white'">
                    <i class="fa-solid fa-star"></i> Tasa Especial
                </button>
            </div>

            <!-- PRODUCT GRID (CARDS) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 xl:grid-cols-3 gap-6">
                <template x-for="p in filteredProducts" :key="p.id">
                    <div class="glass rounded-2xl overflow-hidden border border-gray-200 dark:border-white/5 hover:border-brand-500/30 transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 group relative flex flex-col h-full">
                        
                        <!-- Image Header -->
                        <div class="relative h-48 w-full bg-gray-100 dark:bg-black/40 overflow-hidden">
                            <template x-if="p.image_path">
                                <img :src="p.image_path" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </template>
                            <template x-if="!p.image_path">
                                <div class="w-full h-full flex items-center justify-center text-gray-700">
                                     <i class="fa-solid fa-image text-4xl"></i>
                                </div>
                            </template>
                            
                            <!-- Badges -->
                            <div class="absolute top-3 left-3 flex flex-wrap gap-2">
                                 <span class="px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider backdrop-blur-md border border-white/10 shadow-lg" 
                                    :class="p.is_liquid == 1 ? 'bg-blue-600/80 text-white' : 'bg-emerald-600/80 text-white'">
                                    <i class="fa-solid" :class="p.is_liquid == 1 ? 'fa-droplet' : 'fa-cube'"></i> <span x-text="p.is_liquid == 1 ? 'LÍQ' : 'SOL'"></span>
                                 </span>
                                 <span class="px-2 py-1 rounded-md text-[10px] font-bold bg-gray-100 dark:bg-black/60 text-gray-700 dark:text-gray-300 backdrop-blur-md border border-gray-200 dark:border-white/10" x-text="p.category_name || 'General'"></span>
                                  
                                  <!-- Insumo Badge -->
                                  <template x-if="p.is_raw_material == 1">
                                     <span class="px-2 py-1 rounded-md text-[10px] font-bold bg-orange-500 text-white shadow-lg shadow-orange-500/30 animate-pulse">
                                        INSUMO
                                     </span>
                                  </template>
                            </div>

                            <!-- Actions Overlay (On Image Only) -->
                            <div class="absolute inset-0 bg-black/60 backdrop-blur-[2px] opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-3">
                                <a :href="'inventory.php?edit=' + p.id" class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center hover:bg-blue-500 hover:scale-110 transition-all shadow-lg shadow-blue-500/30" title="Editar">
                                    <i class="fa-solid fa-pen text-sm"></i>
                                </a>
                                <button @click="quickStock(p)" class="w-10 h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center hover:bg-emerald-500 hover:scale-110 transition-all shadow-lg shadow-emerald-500/30" title="Reponer Stock">
                                    <i class="fa-solid fa-plus text-sm"></i>
                                </button>
                                <button @click="deleteProduct(p.id)" class="w-10 h-10 rounded-full bg-red-600 text-white flex items-center justify-center hover:bg-red-500 hover:scale-110 transition-all shadow-lg shadow-red-500/30" title="Eliminar">
                                    <i class="fa-solid fa-trash text-sm"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-5 flex flex-col flex-grow">
                             <div class="flex justify-between items-start mb-2">
                                <div>
                                     <h3 class="font-bold text-gray-900 dark:text-white text-lg leading-tight mb-1 line-clamp-2" x-text="p.name"></h3>
                                     <p class="text-xs text-gray-500 font-mono" x-text="p.sku || '#'"></p>
                                </div>
                             </div>
                             
                              <!-- Stock & Price -->
                             <div class="mt-auto pt-4 flex items-end justify-between border-t border-gray-200 dark:border-white/5">
                                <div>
                                    <p class="text-[10px] uppercase text-gray-500 font-bold mb-0.5">Stock</p>
                                    <div class="flex items-baseline gap-1" :class="parseFloat(p.stock_quantity) <= parseFloat(p.min_stock) ? 'text-red-500 dark:text-red-400' : 'text-emerald-500 dark:text-emerald-400'">
                                        <span class="text-xl font-black" x-text="parseFloat(p.stock_quantity).toFixed(2)"></span>
                                        <span class="text-xs font-bold" x-text="p.display_unit"></span>
                                    </div>
                                </div>
                                <div class="text-right">
                                     <p class="text-[10px] uppercase text-gray-500 font-bold mb-0.5">Precio</p>
                                     <div class="flex flex-col items-end">
                                        <p class="text-lg font-black text-gray-900 dark:text-white leading-tight" x-text="formatPrice(p.price, p.use_special_rate)"></p>
                                        
                                        <!-- Interactive Special Rate Badge -->
                                        <template x-if="config.main_currency === 'USD' && p.is_raw_material == 0">
                                            <button @click.stop="toggleSpecialRate(p)" 
                                                class="text-[9px] font-bold px-3 py-1 rounded-lg border mt-1.5 transition-all duration-200 flex items-center gap-1.5 transform hover:scale-105 active:scale-95 shadow-sm"
                                                :class="p.use_special_rate == 1 
                                                    ? 'text-white bg-purple-600 border-purple-500 hover:bg-purple-500 hover:shadow-purple-500/30' 
                                                    : 'text-gray-500 bg-gray-50 dark:bg-white/5 border-gray-200 dark:border-white/10 hover:bg-gray-100 dark:hover:bg-white/10 hover:text-gray-700 dark:hover:text-white'">
                                                <i class="fa-solid" :class="p.use_special_rate == 1 ? 'fa-star' : 'fa-star-o'"></i>
                                                <span x-text="p.use_special_rate == 1 ? 'Tasa Especial' : 'Global'"></span>
                                            </button>
                                        </template>
                                     </div>
                                </div>
                             </div>
                        </div>
                        
                        </div>
                    </div>
                </template>
            </div>

             <!-- Empty State -->
            <div x-show="filteredProducts.length === 0" class="text-center py-20 opacity-50">
                 <i class="fa-solid fa-box-open text-6xl mb-4 text-gray-600"></i>
                 <p class="text-xl text-gray-400">No se encontraron productos</p>
            </div>
        </div>

        <!-- Right Column: Low Stock Sidebar -->
        <div class="lg:col-span-1 space-y-6 lg:sticky lg:top-8 h-fit order-first lg:order-last mb-6 lg:mb-0">
            
            <!-- Quick Stats (Moved top for mobile) -->
            <div class="glass rounded-xl p-6 border border-gray-200 dark:border-white/5">
                <h4 class="text-xs font-bold uppercase text-gray-500 tracking-wider mb-4">Resumen</h4>
                <div class="grid grid-cols-2 lg:grid-cols-2 gap-4">
                    <div class="bg-gray-50 dark:bg-white/5 rounded-lg p-3 text-center">
                        <span class="block text-2xl font-black text-gray-900 dark:text-white" x-text="products.length"></span>
                        <span class="text-[10px] text-gray-500 font-bold uppercase">Total Items</span>
                    </div>
                    <div class="bg-red-50 dark:bg-red-500/10 rounded-lg p-3 text-center border border-red-500/10">
                        <span class="block text-2xl font-black text-red-500" x-text="lowStockProducts.length"></span>
                        <span class="text-[10px] text-red-400 font-bold uppercase">Críticos</span>
                    </div>
                </div>
            </div>

            <!-- Sidebar Card (Collapsible on mobile maybe? For now just stacked) -->
            <div class="glass rounded-2xl p-6 border border-gray-200 dark:border-white/5" x-show="lowStockProducts.length > 0">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-red-500/10 flex items-center justify-center text-red-500 animate-pulse">
                        <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-lg">Stock Bajo</h3>
                        <p class="text-xs text-gray-500">Reponer pronto</p>
                    </div>
                </div>

                <div class="space-y-4 max-h-[400px] lg:max-h-[70vh] overflow-y-auto custom-scrollbar pr-2 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-1 gap-4 lg:gap-0 lg:space-y-4 display-block">
                    <template x-for="p in lowStockProducts" :key="'low-'+p.id">
                        <div class="flex items-center gap-4 group p-3 rounded-xl hover:bg-white/5 transition-colors border border-transparent hover:border-white/5 bg-gray-50/50 dark:bg-transparent">
                            <!-- Image -->
                            <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-white/5 overflow-hidden flex-shrink-0 relative">
                                <template x-if="p.image_path">
                                    <img :src="p.image_path" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!p.image_path">
                                    <div class="w-full h-full flex items-center justify-center bg-gray-800">
                                         <i class="fa-solid text-gray-500" :class="p.is_liquid == 1 ? 'fa-bottle-droplet' : 'fa-box'"></i>
                                    </div>
                                </template>
                                <div class="absolute inset-0 bg-red-500/20 mix-blend-overlay"></div>
                            </div>
                            
                            <div class="flex-grow min-w-0">
                                <h4 class="font-bold text-sm text-gray-800 dark:text-white truncate" x-text="p.name"></h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-xs font-mono text-red-400 bg-red-500/10 px-1.5 py-0.5 rounded border border-red-500/20">
                                        <span x-text="parseFloat(p.stock_quantity).toFixed(2)"></span> <span x-text="p.display_unit.substring(0,2)"></span>
                                    </span>
                                    <span class="text-[10px] text-gray-500">Min: <span x-text="p.min_stock"></span></span>
                                </div>
                            </div>
                            
                            <button @click="quickStock(p)" class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-white/10 text-gray-500 hover:text-emerald-500 hover:bg-emerald-500/10 transition-colors flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-plus text-xs"></i>
                            </button>
                        </div>
                    </template>
                </div>
            </div>
            
        </div>

    </div>




    <!-- Custom Restock Modal -->
    <div x-show="showStockModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-transition.opacity>
        <div @click.outside="showStockModal = false" class="bg-gray-900 border border-white/10 rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            
            <!-- Header -->
            <div class="px-6 py-4 border-b border-white/10 flex justify-between items-center bg-black/20">
                <div>
                    <h3 class="text-xl font-bold text-white">Reponer Stock</h3>
                    <p class="text-sm text-gray-400">Agregando a: <span class="text-brand-400 font-mono" x-text="stockForm.name"></span></p>
                </div>
                <button @click="showStockModal = false" class="text-gray-500 hover:text-white transition-colors"><i class="fa-solid fa-times text-xl"></i></button>
            </div>

            <!-- Body -->
            <div class="p-6">
                <div class="transition-all duration-300 border p-4 rounded-xl relative overflow-hidden"
                     :class="stockForm.is_liquid 
                        ? 'bg-blue-900/10 border-blue-500/20' 
                        : 'bg-emerald-900/10 border-emerald-500/20'">
                    
                    <!-- Background Icon -->
                    <div class="absolute right-0 top-0 p-4 opacity-10 pointer-events-none">
                         <i class="fa-solid text-6xl" :class="stockForm.is_liquid ? 'fa-faucet-drip' : 'fa-cubes'"></i>
                    </div>

                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-2">
                             <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                                  :class="stockForm.is_liquid ? 'bg-blue-500/20 text-blue-500' : 'bg-emerald-500/20 text-emerald-500'">
                                 <i class="fa-solid" :class="stockForm.is_liquid ? 'fa-glass-water' : 'fa-box'"></i>
                             </div>
                             <label class="block text-xs font-bold uppercase tracking-widest"
                                    :class="stockForm.is_liquid ? 'text-blue-400' : 'text-emerald-400'">
                                 Cantidad a Ingresar <span x-text="stockForm.is_liquid ? '(Líquido)' : '(Sólido)'" class="opacity-50"></span>
                             </label>
                        </div>
                        
                        <div class="flex items-end gap-4">
                            <!-- Main Input -->
                            <div class="flex-grow">
                                <input x-model="stockForm.amount" type="number" step="0.01" 
                                    class="w-full bg-transparent border-b-2 font-mono text-4xl font-bold focus:outline-none py-2 placeholder-white/10"
                                    :class="stockForm.is_liquid ? 'border-blue-500/50 text-blue-100 focus:border-blue-400' : 'border-emerald-500/50 text-emerald-100 focus:border-emerald-400'"
                                    placeholder="0.00" autofocus>
                            </div>
                            
                            <!-- Unit Label -->
                            <div class="pb-3 font-bold text-lg opacity-70 text-white" x-text="stockForm.unit"></div>
                        </div>

                        <!-- Quick Add Buttons -->
                        <div class="mt-6">
                            <!-- Liquid Visual Containers -->
                            <template x-if="stockForm.is_liquid">
                                <div>
                                    <p class="text-[10px] uppercase font-bold text-blue-300/70 mb-2 tracking-wider">Contenedores Comunes</p>
                                    <div class="grid grid-cols-3 sm:grid-cols-6 gap-2">
                                        <button @click="addToStockForm(0.250)" class="group flex flex-col items-center justify-center p-2 rounded-xl bg-blue-500/10 hover:bg-blue-500/30 border border-blue-500/10 hover:border-blue-500/50 transition-all active:scale-95">
                                            <i class="fa-solid fa-glass-water text-blue-400 text-lg mb-1 group-hover:-translate-y-1 transition-transform"></i>
                                            <span class="text-[10px] font-bold text-blue-200">250ml</span>
                                        </button>
                                        <button @click="addToStockForm(0.500)" class="group flex flex-col items-center justify-center p-2 rounded-xl bg-blue-500/10 hover:bg-blue-500/30 border border-blue-500/10 hover:border-blue-500/50 transition-all active:scale-95">
                                            <i class="fa-solid fa-bottle-water text-blue-400 text-xl mb-1 group-hover:-translate-y-1 transition-transform"></i>
                                            <span class="text-[10px] font-bold text-blue-200">500ml</span>
                                        </button>
                                        <button @click="addToStockForm(1.000)" class="group flex flex-col items-center justify-center p-2 rounded-xl bg-blue-500/10 hover:bg-blue-500/30 border border-blue-500/10 hover:border-blue-500/50 transition-all active:scale-95">
                                            <div class="relative">
                                                <i class="fa-solid fa-bottle-droplet text-blue-400 text-2xl mb-1 group-hover:-translate-y-1 transition-transform"></i>
                                                <span class="absolute -right-1 -bottom-0 text-[8px] font-black bg-blue-900 text-white px-1 rounded-full">1L</span>
                                            </div>
                                            <span class="text-[10px] font-bold text-blue-200">Litro</span>
                                        </button>
                                        <button @click="addToStockForm(3.785)" class="group flex flex-col items-center justify-center p-2 rounded-xl bg-blue-500/10 hover:bg-blue-500/30 border border-blue-500/10 hover:border-blue-500/50 transition-all active:scale-95">
                                            <i class="fa-solid fa-jug-detergent text-blue-400 text-2xl mb-1 group-hover:-translate-y-1 transition-transform"></i>
                                            <span class="text-[10px] font-bold text-blue-200">Galón</span>
                                        </button>
                                        <button @click="addToStockForm(5.000)" class="group flex flex-col items-center justify-center p-2 rounded-xl bg-blue-500/10 hover:bg-blue-500/30 border border-blue-500/10 hover:border-blue-500/50 transition-all active:scale-95">
                                            <i class="fa-solid fa-bucket text-blue-400 text-2xl mb-1 group-hover:-translate-y-1 transition-transform"></i>
                                            <span class="text-[10px] font-bold text-blue-200">5 Litros</span>
                                        </button>
                                        <button @click="addToStockForm(20.000)" class="group flex flex-col items-center justify-center p-2 rounded-xl bg-blue-500/10 hover:bg-blue-500/30 border border-blue-500/10 hover:border-blue-500/50 transition-all active:scale-95">
                                            <i class="fa-solid fa-drum-steelforce text-blue-400 text-2xl mb-1 group-hover:-translate-y-1 transition-transform"></i>
                                            <span class="text-[10px] font-bold text-blue-200">20 Litros</span>
                                        </button>
                                    </div>
                                </div>
                            </template>
                            
                            <!-- Solid Buttons -->
                            <template x-if="!stockForm.is_liquid">
                                <div>
                                    <p class="text-[10px] uppercase font-bold text-emerald-300/70 mb-2 tracking-wider">Agrupaciones</p>
                                    <div class="flex gap-2">
                                        <button @click="addToStockForm(1)" class="flex-1 py-3 rounded-xl bg-emerald-500/20 hover:bg-emerald-500/40 text-emerald-300 text-xs font-mono font-bold transition-colors border border-emerald-500/10 flex items-center justify-center gap-2 group">
                                            <i class="fa-solid fa-cube group-hover:scale-110 transition-transform"></i> +1
                                        </button>
                                        <button @click="addToStockForm(6)" class="flex-1 py-3 rounded-xl bg-emerald-500/20 hover:bg-emerald-500/40 text-emerald-300 text-xs font-mono font-bold transition-colors border border-emerald-500/10 flex items-center justify-center gap-2 group">
                                            <i class="fa-solid fa-box-open group-hover:scale-110 transition-transform"></i> +6
                                        </button>
                                        <button @click="addToStockForm(12)" class="flex-1 py-3 rounded-xl bg-emerald-500/20 hover:bg-emerald-500/40 text-emerald-300 text-xs font-mono font-bold transition-colors border border-emerald-500/10 flex items-center justify-center gap-2 group">
                                            <i class="fa-solid fa-boxes-stacked group-hover:scale-110 transition-transform"></i> +12
                                        </button>
                                    </div>
                                </div>
                            </template>
                            
                            <!-- Reset -->
                             <div class="flex items-center justify-end mt-2">
                                <button @click="stockForm.amount = ''" class="text-[10px] text-white/50 hover:text-white flex items-center gap-1 transition-colors">
                                    <i class="fa-solid fa-eraser"></i> Limpiar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-black/20 border-t border-white/10 flex justify-end gap-3">
                <button @click="showStockModal = false" class="px-4 py-2 rounded-xl text-gray-400 hover:text-white transition-colors">Cancelar</button>
                <button @click="confirmStock()" class="px-6 py-2 rounded-xl text-white font-bold shadow-lg transition-transform active:scale-95"
                    :class="stockForm.is_liquid ? 'bg-blue-600 hover:bg-blue-500 shadow-blue-500/20' : 'bg-emerald-600 hover:bg-emerald-500 shadow-emerald-500/20'">
                    Confirmar Ingreso
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {


    // Reuse similar logic but strictly for Catalogue View
    Alpine.data('catalogue', () => ({

        products: [],
        categories: [],
        search: '',
        filterCategory: '',
        viewMode: 'all', // all | sale | raw
        
        // Modal State
        showStockModal: false,
        showLowStockDetails: false,
        stockForm: {
            id: null,
            name: '',
            amount: '',
            is_liquid: false,
            unit: 'Unidad'
        },
        
        config: {}, // Store settings
        
        async init() {
            await this.fetchSettings();
            await this.fetchProducts();
            await this.fetchCategories();
            
            // Check for alert param
            const urlParams = new URLSearchParams(window.location.search);
            if(urlParams.has('show_alert')) {
                this.showLowStockDetails = true;
                // Optional: clean URL
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        },

        async fetchSettings() {
            const res = await fetch('api/settings.php');
            const json = await res.json();
            if (json.status === 'success') {
                this.config = {
                    ...json.data,
                    exchange_rate_global: parseFloat(json.data.exchange_rate_global),
                    exchange_rate_special: parseFloat(json.data.exchange_rate_special)
                };
            }
        },

        formatPrice(price, useSpecial = false) {
            const val = parseFloat(price);
            if (this.config.main_currency === 'USD') {
                // Return $X.XX (Bs X,XXX.XX)
                const rate = (useSpecial == 1 || useSpecial === true) ? this.config.exchange_rate_special : this.config.exchange_rate_global;
                const bs = val * rate;
                return `$${val.toFixed(2)} (Bs ${bs.toLocaleString('es-VE', { minimumFractionDigits: 2, maximumFractionDigits: 2 })})`;
            } else {
                return `${this.config.currency_symbol || 'Bs'} ${val.toFixed(2)}`;
            }
        },

        async fetchProducts() {
            const res = await fetch('api/products.php?action=list');
            const json = await res.json();
            if(json.status === 'success') this.products = json.data;
        },
        
        async fetchCategories() {
            const res = await fetch('api/categories.php?action=list');
            const json = await res.json();
            if(json.status === 'success') this.categories = json.data;
        },

        get filteredProducts() {
            let result = this.products;
            if(this.search) {
                const term = this.search.toLowerCase();
                result = result.filter(p => p.name.toLowerCase().includes(term) || (p.sku && p.sku.toLowerCase().includes(term)));
            }
            if(this.filterCategory) {
                result = result.filter(p => p.category_id == this.filterCategory);
            }

            // Tabs Logic
            if (this.viewMode === 'sale') {
                result = result.filter(p => p.is_raw_material == 0);
            } else if (this.viewMode === 'raw') {
                result = result.filter(p => p.is_raw_material == 1);
            } else if (this.viewMode === 'special') {
                result = result.filter(p => p.use_special_rate == 1);
            }

            return result;
        },

        get lowStockProducts() {
            return this.products.filter(p => parseFloat(p.stock_quantity) <= parseFloat(p.min_stock));
        },

        // --- Custom Modal Logic ---

        quickStock(product) {
            this.stockForm = {
                id: product.id,
                name: product.name,
                amount: '',
                is_liquid: product.is_liquid == 1,
                unit: product.display_unit
            };
            this.showStockModal = true;
        },

        addToStockForm(qty) {
            let current = parseFloat(this.stockForm.amount) || 0;
            this.stockForm.amount = (current + parseFloat(qty)).toFixed(3);
        },

        async confirmStock() {
             const amount = this.stockForm.amount;
             if (!amount || parseFloat(amount) <= 0) {
                 Swal.fire({ icon: 'error', title: 'Cantidad Inválida', toast: true, position: 'top-end', timer: 2000, showConfirmButton: false });
                 return;
             }

             const formData = new FormData();
             formData.append('action', 'update_stock');
             formData.append('id', this.stockForm.id);
             formData.append('amount', amount);
             
             await fetch('api/products.php', { method: 'POST', body: formData });
             
             Swal.fire({ icon: 'success', title: 'Stock Actualizado', toast: true, position: 'top-end', timer: 2000, showConfirmButton: false, background: '#1e293b', color: '#fff' });
             this.fetchProducts();
             this.showStockModal = false;
        },
        
        async deleteProduct(id) {
            const res = await Swal.fire({
                title: '¿Eliminar producto?',
                text: "No podrás revertir esto.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Sí, eliminar',
                background: Alpine.store('theme').isDark ? '#1e293b' : '#fff',
                color: Alpine.store('theme').isDark ? '#fff' : '#1e293b'
            });

            if (res.isConfirmed) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', id);
                await fetch('api/products.php', { method: 'POST', body: formData });
                Swal.fire({ icon: 'success', title: 'Eliminado', toast: true, position: 'top-end', timer: 2000, showConfirmButton: false, background: '#1e293b', color: '#fff' });
                this.fetchProducts();
            }
        },

        async toggleSpecialRate(product) {
            const current = product.use_special_rate == 1; 
            product.use_special_rate = current ? 0 : 1;
            
            const formData = new FormData();
            formData.append('action', 'toggle_special_rate');
            formData.append('id', product.id);
            formData.append('use_special_rate', product.use_special_rate);

            await fetch('api/products.php', { method: 'POST', body: formData });
            
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: product.use_special_rate ? 'success' : 'info',
                title: product.use_special_rate ? 'Usando Tasa Especial' : 'Usando Tasa Global',
                text: product.name,
                showConfirmButton: false, 
                timer: 1500,
                background: Alpine.store('theme').isDark ? '#1e293b' : '#fff', 
                color: Alpine.store('theme').isDark ? '#fff' : '#1e293b'
            });
        }
    }));
});
</script>
<?php require_once 'includes/footer.php'; ?>
