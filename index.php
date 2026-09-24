<?php 
session_start();
require_once 'includes/header.php'; ?>
<?php require_once 'includes/nav.php'; ?>



<div x-data="pos" class="w-full px-2 md:px-6 h-[calc(100vh-60px)] md:h-[calc(100vh-80px)] flex flex-col lg:flex-row gap-3 md:gap-4 pt-2 md:pt-4 pb-2 md:pb-4">
    
    <!-- Left Column: Products -->
    <div class="lg:w-2/3 flex flex-col h-full bg-transparent dark:bg-dark-bg">
        <!-- Search Bar -->
        <div class="mb-4 relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fa-solid fa-search text-gray-400 text-lg"></i>
            </div>
            <input x-model="search" type="text" placeholder="Buscar..." 
                class="w-full bg-white dark:bg-dark-card border border-gray-200 dark:border-white/5 rounded-2xl py-3 md:py-4 pl-12 pr-4 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-500 shadow-lg text-base md:text-lg">
        </div>

        <!-- Favorites Section (Premium Horizontal) -->
        <div x-show="!search && filteredFavorites.length > 0" x-cloak class="mb-5 animate-fade-in-down px-1">
            <h3 class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-3 flex items-center gap-2 pl-1">
                <i class="fa-solid fa-heart text-brand-500"></i> Favoritos
            </h3>
            
            <div class="relative group/scroll">
                <div class="flex gap-3 overflow-x-auto pb-4 pt-1 px-1 custom-scrollbar snap-x scroll-smooth">
                    <template x-for="fav in filteredFavorites" :key="'fav-'+fav.id">
                        <!-- Horizontal Card: 50/50 Split (Compacted) -->
                        <div @click="addToCart(fav)" class="snap-start flex-shrink-0 w-52 h-20 flex bg-white dark:bg-dark-card rounded-xl border border-gray-200 dark:border-white/10 shadow-sm hover:shadow-lg hover:border-brand-500/30 hover:-translate-y-0.5 transition-all duration-300 overflow-hidden group cursor-pointer">
                            
                            <!-- Left: Image (50%) -->
                            <div class="w-1/2 relative bg-gray-50 dark:bg-dark-bg/50 overflow-hidden">
                                <template x-if="fav.image_path">
                                    <img :src="fav.image_path" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                </template>
                                <template x-if="!fav.image_path">
                                    <div class="w-full h-full flex items-center justify-center">
                                         <i class="fa-solid text-3xl text-gray-400/50" :class="fav.is_liquid == 1 ? 'fa-faucet-drip' : 'fa-box'"></i>
                                    </div>
                                </template>
                                
                                <!-- Special Rate Badge (Mini) -->
                                <template x-if="config.main_currency === 'USD' && fav.use_special_rate == 1">
                                    <div class="absolute top-1 left-1">
                                        <div class="px-1.5 py-0.5 bg-purple-500/20 backdrop-blur-sm rounded text-[8px] font-black text-purple-600 dark:text-purple-300 border border-purple-500/10 shadow-sm">
                                            <i class="fa-solid fa-star text-[6px]"></i>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Right: Info (50%) -->
                            <div class="w-1/2 p-1.5 flex flex-col justify-between">
                                <div>
                                    <h4 class="text-[9px] font-bold text-gray-800 dark:text-gray-200 leading-tight mb-0.5 line-clamp-1" x-text="fav.name"></h4>
                                    
                                    <div class="text-[10px] font-black text-gray-900 dark:text-white leading-none mb-0.5">
                                        <span x-text="formatPrice(fav.price, fav.use_special_rate).split('(')[0]"></span>
                                    </div>
                                    <div class="text-[8px] text-gray-400 font-medium">
                                        <span x-text="fav.display_unit"></span>
                                    </div>
                                </div>
                                
                                <!-- Buttons -->
                                <div class="flex gap-1 mt-0.5">
                                    <button @click.stop="addToCart(fav)" class="flex-1 bg-brand-50 dark:bg-brand-500/10 hover:bg-brand-500 hover:text-white text-brand-600 dark:text-brand-400 h-5 rounded text-[9px] transition-colors flex items-center justify-center border border-brand-200 dark:border-brand-500/20">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                    <button @click.stop="openCalculator(fav)" class="flex-1 bg-blue-50 dark:bg-blue-500/10 hover:bg-blue-500 hover:text-white text-blue-500 dark:text-blue-400 h-5 rounded text-[9px] transition-colors flex items-center justify-center border border-blue-200 dark:border-blue-500/20">
                                        <i class="fa-solid fa-calculator"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Products Grid (Solid - No Blur) -->
        <div class="flex-grow overflow-y-auto pr-1 md:pr-2 custom-scrollbar pb-20 lg:pb-0 relative"
             x-cloak>
             <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-3 md:gap-4">
                <template x-for="product in filteredProducts" :key="product.id">
                     <div @click="addToCart(product)" class="bg-white dark:bg-dark-card rounded-2xl border border-gray-200 dark:border-white/5 group hover:border-brand-500 hover:ring-1 hover:ring-brand-500 transition-all duration-200 relative overflow-hidden flex flex-col h-auto cursor-pointer shadow hover:shadow-lg">
                        
                        <!-- Image Area (Top 40%) -->
                        <div class="relative h-24 md:h-28 bg-gray-50 dark:bg-gray-800 border-b border-gray-100 dark:border-white/5">
                            <template x-if="product.image_path">
                                <img :src="product.image_path" class="w-full h-full object-cover">
                            </template>
                            
                            <template x-if="!product.image_path">
                                <div class="w-full h-full flex flex-col items-center justify-center">
                                    <i class="fa-solid text-4xl mb-2 text-gray-300 dark:text-gray-600" 
                                        :class="product.is_liquid == 1 ? 'fa-faucet-drip' : 'fa-box'"></i>
                                </div>
                            </template>

                            <!-- Solid Badges (Top Left) -->
                            <div class="absolute top-2 left-2 z-20 flex gap-1">
                                <template x-if="config.main_currency === 'USD' && product.use_special_rate == 1">
                                    <span class="bg-purple-600 text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-sm flex items-center gap-1">
                                        <i class="fa-solid fa-star text-[8px]"></i> Especial
                                    </span>
                                </template>
                            </div>
                            
                            <!-- Favorite (Top Right - Solid) -->
                            <button @click.stop="toggleFavorite(product)" class="absolute top-2 right-2 z-20 w-7 h-7 flex items-center justify-center rounded-full bg-white dark:bg-gray-700 shadow-sm border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
                                :class="product.is_favorite == 1 ? '!text-red-500 !border-red-100' : 'text-gray-400 dark:text-gray-400'">
                                <i class="fa-solid fa-heart text-xs" :class="product.is_favorite == 1 ? 'animate-heartbeat' : ''"></i>
                            </button>
                        </div>

                        <!-- Content Body (Bottom 60%) -->
                        <div class="p-2.5 flex flex-col flex-grow bg-white dark:bg-dark-card">
                            <div class="flex-grow">
                                <div class="flex justify-between items-start gap-2 mb-1">
                                    <p class="text-[10px] bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-300 px-2 py-0.5 rounded font-bold uppercase tracking-wider truncate max-w-[65%]" x-text="product.category || 'General'"></p>
                                    <span class="text-[10px] font-mono font-bold px-1.5 py-0.5 rounded bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600"
                                        :class="parseFloat(product.stock_quantity) < parseFloat(product.min_stock) ? '!bg-red-100 !text-red-600 !border-red-200' : ''">
                                        <span x-text="parseFloat(product.stock_quantity).toFixed(2)"></span> 
                                        <span x-text="product.is_liquid == 1 ? 'L' : 'U'" class="text-[9px] opacity-70"></span>
                                    </span>
                                </div>
                                
                                <h3 class="font-bold text-gray-800 dark:text-gray-100 text-xs leading-snug mb-1.5 line-clamp-2 h-8" x-text="product.name" :title="product.name"></h3>
                                
                                <div class="flex items-baseline gap-1 mt-auto">
                                    <span class="text-xs text-brand-600 dark:text-brand-400 font-bold self-start mt-0.5" x-text="config.currency_symbol || '$'"></span>
                                    <span class="text-2xl font-black text-gray-900 dark:text-white tracking-tight" x-text="formatPrice(product.price, product.use_special_rate).split('(')[0].replace(/[^\d.,]/g, '')"></span>
                                    <span class="text-[10px] text-brand-500/60 font-bold ml-1 self-end mb-1" x-text="product.display_unit"></span>
                                </div>
                                
                                <!-- Bs Price (Badge Style) -->
                                <div class="mt-1 flex justify-end" x-show="config.main_currency === 'USD'">
                                    <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-gray-50 dark:bg-white/5 text-gray-500 dark:text-gray-400 border border-gray-100 dark:border-white/5 flex items-center gap-1">
                                        <span class="opacity-50 text-[9px]">Bs</span> 
                                        <span x-text="formatPrice(product.price, product.use_special_rate).split('(')[1]?.replace(')', '').replace('Bs ', '')"></span>
                                    </span>
                                </div>
                            </div>

                            <!-- Solid Action Bar -->
                            <div class="grid grid-cols-4 gap-2 mt-2 pt-2 border-t border-gray-100 dark:border-gray-700/50">
                                <button @click.stop="openCalculator(product)" class="col-span-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 text-gray-400 hover:text-brand-600 hover:border-brand-500 rounded-md text-xs font-bold transition-colors flex items-center justify-center aspect-square shadow-sm">
                                    <i class="fa-solid fa-calculator"></i>
                                </button>
                                
                                <button @click.stop="addToCart(product)" class="col-span-3 bg-brand-600 hover:bg-brand-700 text-white rounded-md text-xs font-bold shadow-md transition-all flex items-center justify-center gap-1.5 active:translate-y-0.5 active:shadow-sm">
                                    <span>Agregar</span>
                                    <i class="fa-solid fa-plus-circle text-[10px]"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            
            <div x-show="products.length === 0" class="text-center py-20 opacity-50 animate-pulse">
                <i class="fa-solid fa-spinner fa-spin text-4xl mb-4"></i>
                <p>Cargando productos...</p>
            </div>
        </div>
    </div>

    <!-- Right Column: Cart (Mobile Optimized) -->
    <div class="lg:w-1/3 flex flex-col lg:h-full bg-white dark:bg-dark-card rounded-t-3xl lg:rounded-3xl border-t lg:border border-gray-200 dark:border-white/5 shadow-[0_-10px_40px_rgba(0,0,0,0.2)] lg:shadow-2xl overflow-hidden z-30 fixed lg:static bottom-0 left-0 right-0 h-[80px] lg:h-full transition-all duration-300"
         :class="{'h-[80vh]': showMobileCart}">
        
        <!-- Cart Toggle (Mobile Only) -->
        <div @click="showMobileCart = !showMobileCart" class="lg:hidden w-full flex justify-center pt-2 pb-1 cursor-pointer">
            <div class="w-12 h-1.5 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
        </div>

        <!-- Cart Header -->
        <div class="px-6 py-3 lg:p-6 border-b border-gray-200 dark:border-white/5 bg-gradient-to-r from-gray-50 to-white dark:from-gray-900 dark:to-gray-800 flex justify-between items-center cursor-pointer lg:cursor-default"
             @click="if(window.innerWidth < 1024) showMobileCart = !showMobileCart">
            <h2 class="text-lg lg:text-xl font-bold flex items-center gap-3 text-gray-900 dark:text-white">
                <div class="bg-brand-500 p-2 rounded-lg text-white">
                    <i class="fa-solid fa-cart-shopping text-sm lg:text-base"></i>
                </div>
                <span>Ticket Actual.</span>
                <span class="lg:hidden text-sm bg-brand-100 text-brand-600 px-2 py-0.5 rounded-full" x-show="cart.length > 0">
                    <span x-text="cart.length"></span> items
                </span>
            </h2>
            <div class="lg:hidden flex flex-col items-end">
                <div class="font-bold text-brand-500" x-text="'$' + parseFloat(cartTotal).toFixed(2)"></div>
                <div class="text-[10px] font-bold text-gray-500 dark:text-gray-400" x-show="config.main_currency === 'USD' && cart.length > 0">
                    Bs <span x-text="cartTotalBs"></span>
                </div>
            </div>
        </div>

        <!-- Cart Items List -->
        <div class="flex-grow overflow-y-auto p-4 space-y-4 custom-scrollbar"
             x-show="showMobileCart || window.innerWidth >= 1024"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100">
             <template x-for="(item, index) in cart" :key="index">
                <div class="bg-gray-50 dark:bg-dark-bg rounded-xl p-4 border border-gray-200 dark:border-white/5 relative group animate-fade-in-right">
                    
                    <!-- Remove Button -->
                    <button @click="removeFromCart(index)" class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-400 text-white rounded-full w-7 h-7 flex items-center justify-center shadow-lg transition-transform hover:scale-110 z-10">
                        <i class="fa-solid fa-trash text-xs"></i>
                    </button>
                    
                    <div class="flex justify-between items-start mb-3">
                        <div class="w-2/3">
                            <h4 class="font-bold text-gray-800 dark:text-white text-sm" x-text="item.name"></h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <button @click.stop="decrementItem(index)" class="w-6 h-6 rounded bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 flex items-center justify-center text-gray-700 dark:text-white transition-colors">
                                        <i class="fa-solid fa-minus text-[10px]"></i>
                                    </button>
                                    <span class="font-mono text-gray-800 dark:text-white text-md min-w-[3rem] text-center">
                                        <span x-text="parseFloat(item.quantity_to_sell).toFixed(2)"></span> <span x-text="item.display_unit.substring(0,3)" class="text-[10px] uppercase text-gray-500"></span>
                                    </span>
                                    <button @click.stop="incrementItem(index)" class="w-6 h-6 rounded bg-brand-100 dark:bg-brand-500/20 hover:bg-brand-200 dark:hover:bg-brand-500/40 text-brand-600 dark:text-brand-400 flex items-center justify-center transition-colors">
                                        <i class="fa-solid fa-plus text-[10px]"></i>
                                    </button>
                                </div>
                                    x <span x-text="formatPrice(item.price, item.use_special_rate)"></span>
                                    <template x-if="config.main_currency === 'USD' && item.use_special_rate == 1">
                                        <i class="fa-solid fa-star text-purple-500 text-[8px] ml-1" title="Tasa Especial"></i>
                                    </template>
                                    <div class="text-[9px] text-gray-500 dark:text-gray-400 font-medium" x-show="config.main_currency === 'USD'">
                                        <span x-text="'Bs ' + (parseFloat(item.subtotal) * (item.use_special_rate == 1 ? config.exchange_rate_special : config.exchange_rate_global)).toLocaleString('es-VE', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span>
                                    </div>
                                </div>
                        </div>
                        <div class="text-right">
                             <div class="relative">
                                <input type="number" step="0.5" 
                                    :value="item.subtotal" 
                                    @input="updateByAmount(index, $el.value)"
                                    class="w-20 bg-gray-200 dark:bg-black/40 border border-gray-300 dark:border-gray-700 rounded text-right px-2 py-0.5 text-sm text-gray-900 dark:text-white focus:border-brand-500 focus:outline-none font-bold">
                            </div>
                        </div>
                    </div>
                </div>
             </template>

             <div x-show="cart.length === 0" class="h-full flex flex-col items-center justify-center text-gray-600 opacity-60">
                 <i class="fa-solid fa-cash-register text-5xl mb-4"></i>
                 <p class="text-sm">Agrega productos para comenzar</p>
             </div>
        </div>

        <!-- Pay Section -->
        <div class="p-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-white/5"
             :class="{'hidden': !showMobileCart && window.innerWidth < 1024, 'block': showMobileCart || window.innerWidth >= 1024}"
             x-effect="window.innerWidth" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">
            <div class="flex flex-col gap-0.5 mb-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-500 dark:text-gray-400 font-medium text-sm">Total a Pagar</span>
                    <span class="text-2xl font-black text-gray-900 dark:text-white tracking-tighter"><span x-text="config.currency_symbol || '$'"></span> <span x-text="cartTotal"></span></span>
                </div>
                <!-- Total in Bs -->
                <div class="flex justify-end" x-show="config.main_currency === 'USD' && cart.length > 0">
                    <span class="text-sm font-bold text-brand-500/80 dark:text-brand-400/80 bg-brand-50/50 dark:bg-brand-500/5 px-2 py-0.5 rounded-lg border border-brand-100 dark:border-brand-500/10">
                        Bs <span x-text="cartTotalBs"></span>
                    </span>
                </div>
            </div>
            
            <button @click="openCheckout()" 
                type="button"
                :disabled="cart.length === 0"
                class="w-full bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-500 hover:to-indigo-500 text-white font-bold py-3 rounded-xl transition-all shadow-md disabled:opacity-50 disabled:cursor-not-allowed text-base flex justify-center items-center gap-2 transform active:scale-95 z-50 relative">
                <span>Cobrar</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </button>
        </div>
    </div>

    <!-- CALCULATOR MODAL -->
    <!-- CALCULATOR MODAL -->
    <template x-teleport="body">
        <div x-show="showCalculator" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/90 backdrop-blur-md" @click="showCalculator = false"></div>
            <div class="glass relative w-full max-w-sm bg-white dark:bg-dark-card rounded-3xl p-6 shadow-2xl transform transition-all animate-zoom-in">
                
                <div class="text-center mb-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1" x-text="calcProduct.name"></h3>
                    <p class="text-sm text-brand-400">Precio: <span x-text="formatPrice(calcProduct.price, calcProduct.use_special_rate)"></span> / <span x-text="calcProduct.display_unit"></span></p>
                </div>

                <!-- The Big Inputs -->
                <div class="space-y-6">
                    <!-- Group 1: Money (USD) -->
                    <div class="relative group">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1">Monto a Vender ($)</label>
                        <div class="flex items-center bg-gray-50 dark:bg-dark-bg border-2 border-brand-500/50 rounded-xl overflow-hidden focus-within:border-brand-500 transition-colors">
                            <div class="px-4 text-brand-500 font-bold text-xl" x-text="config.currency_symbol || '$'"></div>
                            <input x-model="calcAmount" @input="updateCalcByAmount()" type="number" step="0.1" class="w-full bg-transparent border-none text-gray-900 dark:text-white text-3xl font-bold p-3 focus:ring-0 text-right placeholder-gray-400 dark:placeholder-gray-700" placeholder="0.00">
                        </div>
                    </div>

                    <!-- Group 2: Money (Bs) -->
                    <div class="relative group" x-show="config.main_currency === 'USD'">
                         <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1">Monto a Vender (Bs)</label>
                         <div class="flex items-center bg-gray-50 dark:bg-dark-bg border-2 border-indigo-500/30 rounded-xl overflow-hidden focus-within:border-indigo-500 transition-colors">
                            <div class="px-4 text-indigo-500 font-bold text-xl">Bs</div>
                            <input x-model="calcAmountBs" @input="updateCalcByBs()" type="number" step="0.1" class="w-full bg-transparent border-none text-gray-900 dark:text-white text-3xl font-bold p-3 focus:ring-0 text-right placeholder-gray-400 dark:placeholder-gray-700" placeholder="0,00">
                         </div>
                    </div>

                    <!-- Group 2: Quantity -->
                    <div class="relative group">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1">Cantidad (<span x-text="calcProduct.display_unit"></span>)</label>
                        <div class="flex items-center bg-gray-50 dark:bg-dark-bg border-2 border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden focus-within:border-gray-400 dark:focus-within:border-white/50 transition-colors">
                            <div class="px-4 text-gray-500 font-bold text-xl"><i class="fa-solid fa-scale-balanced"></i></div>
                            <input x-model="calcQty" @input="updateCalcByQty()" type="number" step="0.001" class="w-full bg-transparent border-none text-gray-900 dark:text-white text-3xl font-bold p-3 focus:ring-0 text-right placeholder-gray-400 dark:placeholder-gray-700" placeholder="0.000">
                        </div>
                    </div>
                </div>

                <!-- Quick Fractions (Only for Liquids) -->
                <div class="grid grid-cols-4 gap-2 mt-6" x-show="calcProduct.is_liquid == 1">
                    <button @click="setFraction(0.25)" class="py-2 rounded-lg bg-gray-800 hover:bg-gray-700 text-xs text-gray-300 font-mono transition-colors">1/4 L</button>
                    <button @click="setFraction(0.5)" class="py-2 rounded-lg bg-gray-800 hover:bg-gray-700 text-xs text-gray-300 font-mono transition-colors">1/2 L</button>
                    <button @click="setFraction(1.0)" class="py-2 rounded-lg bg-gray-800 hover:bg-gray-700 text-xs text-gray-300 font-mono transition-colors">1 L</button>
                     <button @click="setFraction(2.0)" class="py-2 rounded-lg bg-gray-800 hover:bg-gray-700 text-xs text-gray-300 font-mono transition-colors">2 L</button>
                </div>

                <!-- Action -->
                <button @click="addToCartFromCalc()" class="w-full mt-8 bg-brand-600 hover:bg-brand-500 text-white font-bold py-4 rounded-xl shadow-lg transition-all text-lg flex justify-center items-center gap-2">
                    <i class="fa-solid fa-plus-circle"></i> Agregar a la Venta
                </button>
                
                <button @click="showCalculator = false" class="absolute top-4 right-4 text-gray-500 hover:text-white">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>
        </div>
    </template>



    <!-- CHECKOUT MODAL -->
    <!-- CHECKOUT MODAL -->
    <template x-teleport="body">
        <div x-show="showCheckoutModal" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/90 backdrop-blur-md" @click="showCheckoutModal = false"></div>
            <div class="glass relative w-full max-w-2xl bg-white dark:bg-dark-card rounded-3xl p-6 shadow-2xl transform transition-all animate-zoom-in flex flex-col md:flex-row gap-6">
                
                <!-- Left: Order Summary -->
                <div class="w-full md:w-1/2 border-b md:border-b-0 md:border-r border-gray-200 dark:border-white/10 pr-0 md:pr-6 pb-6 md:pb-0">
                    <h3 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-list-check text-brand-500"></i> Resumen del Pedido
                    </h3>
                    <div class="space-y-3 max-h-[300px] overflow-y-auto custom-scrollbar pr-2">
                        <template x-for="item in cart" :key="item.id">
                            <div class="flex justify-between items-center text-sm">
                                <div>
                                    <p class="text-gray-700 dark:text-gray-300 font-medium" x-text="item.name"></p>
                                    <p class="text-xs text-gray-500">
                                        <span x-text="parseFloat(item.quantity_to_sell).toFixed(2)"></span> x $ <span x-text="item.price"></span>
                                    </p>
                                </div>
                                <span class="text-brand-400 font-bold">$ <span x-text="item.subtotal"></span></span>
                            </div>
                        </template>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-white/10">
                        <div class="flex justify-between items-end mb-1">
                            <span class="text-gray-500 dark:text-gray-400">Total a Pagar</span>
                            <span class="text-2xl font-black text-gray-900 dark:text-white">$ <span x-text="cartTotal"></span></span>
                        </div>
                        <div class="flex justify-end" x-show="config.main_currency === 'USD'">
                            <span class="text-sm font-bold text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-white/5 px-2 py-0.5 rounded border border-gray-200 dark:border-white/5">
                                Bs <span x-text="cartTotalBs"></span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Right: Payment -->
                <div class="w-full md:w-1/2 flex flex-col">
                     <h3 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-wallet text-emerald-500"></i> Método de Pago
                    </h3>
                    
                    <!-- Payment Method Toggle -->
                    <!-- Payment Method Toggle -->
                    <div class="grid grid-cols-3 gap-2 mb-6 overflow-x-auto pb-2">
                        <!-- BIOPAGO -->
                        <button @click="paymentMethod = 'biopago'; updateChange()" 
                            class="p-2 rounded-xl border transition-all flex flex-col items-center gap-1.5 min-w-[70px]"
                            :class="paymentMethod === 'biopago' ? 'bg-indigo-500/20 border-indigo-500 text-indigo-700 dark:text-white' : 'bg-gray-100 dark:bg-gray-800 border-transparent text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700'">
                            <i class="fa-solid fa-fingerprint text-lg"></i>
                            <span class="text-[9px] md:text-[10px] font-bold leading-tight">Biopago</span>
                        </button>

                        <!-- PAGO MOVIL -->
                        <button @click="paymentMethod = 'pago_movil'; updateChange()" 
                            class="p-2 rounded-xl border transition-all flex flex-col items-center gap-1.5 min-w-[70px]"
                            :class="paymentMethod === 'pago_movil' ? 'bg-purple-500/20 border-purple-500 text-purple-700 dark:text-white' : 'bg-gray-100 dark:bg-gray-800 border-transparent text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700'">
                            <i class="fa-solid fa-mobile-screen-button text-lg"></i>
                            <span class="text-[9px] md:text-[10px] font-bold leading-tight">P. Móvil</span>
                        </button>

                        <!-- CASH -->
                        <button @click="paymentMethod = 'cash'; updateChange()" 
                            class="p-2 rounded-xl border transition-all flex flex-col items-center gap-1.5 min-w-[70px]"
                            :class="paymentMethod === 'cash' ? 'bg-emerald-500/20 border-emerald-500 text-emerald-700 dark:text-white' : 'bg-gray-100 dark:bg-gray-800 border-transparent text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700'">
                            <i class="fa-solid fa-money-bill-wave text-lg"></i>
                            <span class="text-[9px] md:text-[10px] font-bold leading-tight">Efectivo $</span>
                        </button>

                         <!-- CASH BS -->
                        <button @click="paymentMethod = 'cash_bs'; updateChange()" 
                            class="p-2 rounded-xl border transition-all flex flex-col items-center gap-1.5 min-w-[70px]"
                            :class="paymentMethod === 'cash_bs' ? 'bg-teal-500/20 border-teal-500 text-teal-700 dark:text-white' : 'bg-gray-100 dark:bg-gray-800 border-transparent text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700'">
                            <i class="fa-solid fa-money-bill-1-wave text-lg"></i>
                            <span class="text-[9px] md:text-[10px] font-bold leading-tight">Efectivo Bs</span>
                        </button>

                        <!-- TRANSFERENCIA -->
                        <button @click="paymentMethod = 'transferencia'; updateChange()" 
                            class="p-2 rounded-xl border transition-all flex flex-col items-center gap-1.5 min-w-[70px]"
                            :class="paymentMethod === 'transferencia' ? 'bg-orange-500/20 border-orange-500 text-orange-700 dark:text-white' : 'bg-gray-100 dark:bg-gray-800 border-transparent text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700'">
                            <i class="fa-solid fa-building-columns text-lg"></i>
                            <span class="text-[9px] md:text-[10px] font-bold leading-tight">Transfer</span>
                        </button>

                        <!-- CARD/QR -->
                        <button @click="paymentMethod = 'card'"
                            class="p-2 rounded-xl border transition-all flex flex-col items-center gap-1.5 min-w-[70px]"
                            :class="paymentMethod === 'card' ? 'bg-blue-500/20 border-blue-500 text-blue-700 dark:text-white' : 'bg-gray-100 dark:bg-gray-800 border-transparent text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700'">
                            <i class="fa-solid fa-credit-card text-lg"></i>
                            <span class="text-[9px] md:text-[10px] font-bold leading-tight">Tarjeta</span>
                        </button>
                    </div>

                    <!-- Cash Payment Inputs ($) -->
                    <div x-show="paymentMethod === 'cash'" class="space-y-4 animate-fade-in">
                         <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Monto Recibido ($)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-3 text-emerald-500 font-bold">$</span>
                                <input x-ref="payInput" x-model="paymentAmount" @input="updateChange()" type="number" step="0.1" 
                                    class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl py-3 pl-10 pr-4 text-gray-900 dark:text-white font-bold text-lg focus:border-emerald-500 focus:outline-none placeholder-gray-400 dark:placeholder-gray-600"
                                    placeholder="0.00">
                            </div>
                         </div>
                         
                         <div class="bg-gray-100 dark:bg-gray-800/50 p-4 rounded-xl border border-gray-200 dark:border-white/5">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400 text-sm">Cambio / Vuelto</span>
                                <span class="text-xl font-bold" :class="change < 0 ? 'text-red-400' : 'text-emerald-400'">
                                    $ <span x-text="change"></span>
                                </span>
                            </div>
                         </div>
                    </div>

                    <!-- Cash Payment Inputs (Bs) -->
                    <div x-show="paymentMethod === 'cash_bs'" class="space-y-4 animate-fade-in">
                         <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Monto Recibido (Bs)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-3 text-teal-500 font-bold">Bs</span>
                                <input x-ref="payInputBs" x-model="paymentAmount" @input="updateChange()" type="number" step="0.1" 
                                    class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl py-3 pl-10 pr-4 text-gray-900 dark:text-white font-bold text-lg focus:border-teal-500 focus:outline-none placeholder-gray-400 dark:placeholder-gray-600"
                                    placeholder="0,00">
                            </div>
                         </div>
                         
                         <div class="bg-gray-100 dark:bg-gray-800/50 p-4 rounded-xl border border-gray-200 dark:border-white/5">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400 text-sm">Cambio / Vuelto</span>
                                <span class="text-xl font-bold" :class="parseFloat(change.toString().replace('.','').replace(',','.')) < 0 ? 'text-red-400' : 'text-teal-400'">
                                    Bs <span x-text="change"></span>
                                </span>
                            </div>
                         </div>
                    </div>
                    
                    <!-- Card/Biopago Info -->
                    <div x-show="paymentMethod !== 'cash' && paymentMethod !== 'cash_bs'" class="flex-grow flex flex-col items-center justify-center text-center p-4 rounded-xl border animate-fade-in"
                        :class="{
                            'bg-indigo-500/5 border-indigo-500/20': paymentMethod === 'biopago',
                            'bg-purple-500/5 border-purple-500/20': paymentMethod === 'pago_movil',
                            'bg-orange-500/5 border-orange-500/20': paymentMethod === 'transferencia',
                            'bg-blue-500/5 border-blue-500/20': paymentMethod === 'card'
                        }">
                        
                        <i class="fa-solid text-4xl mb-3" 
                            :class="{
                                'fa-fingerprint text-indigo-400': paymentMethod === 'biopago',
                                'fa-mobile-screen-button text-purple-400': paymentMethod === 'pago_movil',
                                'fa-building-columns text-orange-400': paymentMethod === 'transferencia',
                                'fa-credit-card text-blue-400': paymentMethod === 'card'
                            }"></i>
                        
                        <p class="text-sm" 
                            :class="{
                                'text-indigo-200': paymentMethod === 'biopago',
                                'text-purple-200': paymentMethod === 'pago_movil',
                                'text-orange-200': paymentMethod === 'transferencia',
                                'text-blue-200': paymentMethod === 'card'
                            }">
                            <span x-text="
                                paymentMethod === 'biopago' ? 'Registrar pago mediante Biopago.' : 
                                (paymentMethod === 'pago_movil' ? 'Registrar pago mediante Pago Móvil.' : 
                                (paymentMethod === 'transferencia' ? 'Registrar pago mediante Transferencia.' : 
                                'Confirma el pago con Tarjeta.'))
                            "></span>
                        </p>
                    </div>

                    <!-- Reference Input (Pago Movil/Transfer) -->
                    <div x-show="['pago_movil','transferencia'].includes(paymentMethod)" class="mb-4 animate-fade-in">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Referencia / N° Transacción</label>
                        <input x-model="paymentReference" type="text" 
                             class="w-full bg-indigo-50 dark:bg-gray-900 border border-indigo-200 dark:border-indigo-500/30 rounded-xl py-3 px-4 text-gray-900 dark:text-white font-bold focus:border-indigo-500 focus:outline-none placeholder-indigo-300 dark:placeholder-gray-600 transition-colors"
                             :class="{
                                '!bg-orange-50 dark:!bg-orange-900/20 !border-orange-200 dark:!border-orange-500/50 focus:!border-orange-500 text-orange-900 dark:text-orange-200': paymentMethod === 'transferencia',
                                '!bg-purple-50 dark:!bg-purple-900/20 !border-purple-200 dark:!border-purple-500/50 focus:!border-purple-500 text-purple-900 dark:text-purple-200': paymentMethod === 'pago_movil'
                             }"
                             placeholder="Ej: 12345678">
                    </div>

                    <div class="mt-auto pt-6">
                        <button @click="processSale()" 
                            :disabled="paymentMethod === 'cash' && (parseFloat(change) < 0 || !paymentAmount)"
                            class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold py-4 rounded-xl shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed flex justify-center items-center gap-2">
                            <span>Confirmar Pago</span>
                            <i class="fa-solid fa-check-circle"></i>
                        </button>
                        <button @click="showCheckoutModal = false" class="w-full mt-3 py-2 text-gray-500 hover:text-white text-sm transition-colors">
                            Cancelar
                        </button>
                    </div>
                </div>

                <button @click="showCheckoutModal = false" class="absolute top-4 right-4 text-gray-500 hover:text-white md:hidden">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>
        </div>
    </template>

</div>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('pos', () => ({
        products: [],
        cart: [],
        search: '',
        showMobileCart: false, // Control del carrito móvil
        
        // Calculator State
        showCalculator: false,
        calcProduct: {},
        calcAmount: '', // Money USD
        calcAmountBs: '', // Money Bs
        calcQty: '',
        
        config: {}, // Store settings

        async init() {
            await this.fetchSettings();
            await this.fetchProducts();
        },

        async fetchSettings() {
            try {
                const res = await fetch(`api/settings.php?_t=${new Date().getTime()}`);
                if (!res.ok) throw new Error('Network error');
                const json = await res.json();
                if (json.status === 'success') {
                    this.config = {
                        ...json.data,
                        exchange_rate_global: parseFloat(json.data.exchange_rate_global),
                        exchange_rate_special: parseFloat(json.data.exchange_rate_special)
                    };
                }
            } catch (e) {
                console.error("Error fetching settings:", e);
                // Fallback attempt
                this.config = { main_currency: 'USD', currency_symbol: '$', exchange_rate_global: 1, exchange_rate_special: 1 };
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
        showCheckoutModal: false,
        paymentMethod: 'cash', // cash, card, qr
        paymentReference: '',
        paymentAmount: '',
        change: 0,
        tempSaleId: null, // For printing after success
        
        async init() {
            await this.fetchSettings();
            await this.fetchProducts();
        },

        async fetchProducts() {
            const res = await fetch(`api/products.php?action=list&type=for_sale&_t=${new Date().getTime()}`);
            const json = await res.json();
            if(json.status === 'success') this.products = json.data;
        },

        get filteredProducts() {
            if(!this.search) return this.products;
            return this.products.filter(p => p.name.toLowerCase().includes(this.search.toLowerCase()));
        },

        get filteredFavorites() {
            return this.products.filter(p => p.is_favorite == 1);
        },

        async toggleFavorite(product) {
            // Optimistic update
            const newState = product.is_favorite == 1 ? 0 : 1;
            product.is_favorite = newState;

            const formData = new FormData();
            formData.append('action', 'toggle_favorite');
            formData.append('id', product.id);
            formData.append('is_favorite', newState);
            
            await fetch('api/products.php', { method: 'POST', body: formData });
        },

        // --- Core POS Logic ---
        
        addToCart(product) {
            this.addFinalToCart(product, 1, parseFloat(product.price));
            notify('success', 'Agregado', `1 ${product.display_unit} de ${product.name}`);
        },

        addFinalToCart(product, qty, subtotal) {
             const existing = this.cart.find(i => i.id === product.id);
             if(existing) {
                 // Update existing
                 let newQty = parseFloat(existing.quantity_to_sell) + parseFloat(qty);
                 existing.quantity_to_sell = newQty.toFixed(4);
                 existing.subtotal = (parseFloat(existing.subtotal) + parseFloat(subtotal)).toFixed(2);
             } else {
                 // Add new
                 this.cart.push({
                     ...product,
                     quantity_to_sell: parseFloat(qty).toFixed(4),
                     subtotal: parseFloat(subtotal).toFixed(2)
                 });
             }
        },

        removeFromCart(index) {
            this.cart.splice(index, 1);
        },

        decrementItem(index) {
            const item = this.cart[index];
            let currentQty = parseFloat(item.quantity_to_sell);
            let unitPrice = parseFloat(item.price);

            if(currentQty > 1) {
                // If more than 1, decrement by 1
                let newQty = currentQty - 1;
                item.quantity_to_sell = newQty.toFixed(4);
                item.subtotal = (newQty * unitPrice).toFixed(2);
            } else {
                this.removeFromCart(index);
            }
        },

        incrementItem(index) {
            const item = this.cart[index];
            let currentQty = parseFloat(item.quantity_to_sell);
            let unitPrice = parseFloat(item.price);
            
            // Increment by 1
            let newQty = currentQty + 1;
            item.quantity_to_sell = newQty.toFixed(4);
            item.subtotal = (newQty * unitPrice).toFixed(2);
        },

        get cartTotal() {
            return this.cart.reduce((sum, item) => sum + parseFloat(item.subtotal || 0), 0).toFixed(2);
        },

        get cartTotalBs() {
             return this.cart.reduce((sum, item) => {
                 const rate = (item.use_special_rate == 1 || item.use_special_rate === true) 
                              ? this.config.exchange_rate_special 
                              : this.config.exchange_rate_global;
                 return sum + (parseFloat(item.subtotal || 0) * rate);
             }, 0).toLocaleString('es-VE', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        },
        
        // --- Calculator Logic ---

        openCalculator(product) {
            this.calcProduct = product;
            this.calcQty = 1;
            this.calcAmount = parseFloat(product.price).toFixed(2);
            
            // Initial Sync Bs
            const rate = (product.use_special_rate == 1 || product.use_special_rate === true) ? this.config.exchange_rate_special : this.config.exchange_rate_global;
            this.calcAmountBs = (parseFloat(this.calcAmount) * rate).toFixed(2);

            this.showCalculator = true;
        },

        updateCalcByAmount() {
            // User types Money -> Calc Qty
            let price = parseFloat(this.calcProduct.price);
            if(price > 0 && this.calcAmount) {
                this.calcQty = (parseFloat(this.calcAmount) / price).toFixed(4);
                
                // Sync Bs
                const rate = (this.calcProduct.use_special_rate == 1 || this.calcProduct.use_special_rate === true) ? this.config.exchange_rate_special : this.config.exchange_rate_global;
                this.calcAmountBs = (parseFloat(this.calcAmount) * rate).toFixed(2);
            } else {
                this.calcAmountBs = '';
                this.calcQty = '';
            }
        },

        updateCalcByQty() {
            // User types Qty -> Calc Money
            let price = parseFloat(this.calcProduct.price);
            if(price > 0 && this.calcQty) {
                this.calcAmount = (parseFloat(this.calcQty) * price).toFixed(2);

                // Sync Bs
                const rate = (this.calcProduct.use_special_rate == 1 || this.calcProduct.use_special_rate === true) ? this.config.exchange_rate_special : this.config.exchange_rate_global;
                this.calcAmountBs = (parseFloat(this.calcAmount) * rate).toFixed(2);
            } else {
                this.calcAmount = '';
                this.calcAmountBs = '';
            }
        },
        
        setFraction(fraction) {
            this.calcQty = fraction;
            this.updateCalcByQty();
        },


        updateCalcByBs() {
             // User types Bs -> Calc USD and Qty
             const rate = (this.calcProduct.use_special_rate == 1 || this.calcProduct.use_special_rate === true) ? this.config.exchange_rate_special : this.config.exchange_rate_global;
             if(rate > 0 && this.calcAmountBs) {
                 // Bs -> USD
                 this.calcAmount = (parseFloat(this.calcAmountBs) / rate).toFixed(2);
                 // USD -> Qty
                 let price = parseFloat(this.calcProduct.price);
                 if(price > 0) {
                     this.calcQty = (parseFloat(this.calcAmount) / price).toFixed(4);
                 }
             } else {
                 this.calcAmount = '';
                 this.calcQty = '';
             }
        },

        addToCartFromCalc() {
            // Validate
            if(parseFloat(this.calcAmount) <= 0 || parseFloat(this.calcQty) <= 0) {
                 notify('error', 'Valor Inválido');
                 return;
            }
            
            this.addFinalToCart(this.calcProduct, this.calcQty, this.calcAmount);
            this.showCalculator = false;
            notify('success', 'Agregado', `$ ${this.calcAmount} de ${this.calcProduct.name}`);
        },

        updateByAmount(index, amount) {
             const item = this.cart[index];
             const price = parseFloat(item.price);
             if(price > 0) {
                 item.quantity_to_sell = (parseFloat(amount) / price).toFixed(4);
                 item.subtotal = amount;
             }
         },

        // --- Checkout Logic ---

        openCheckout() {
            try {
                // Safety check
                if(!this.cart || this.cart.length === 0) return;
                
                // Stock Check
                for(let item of this.cart) {
                    if(item.stock_quantity !== undefined && item.stock_quantity !== null) {
                         if(parseFloat(item.quantity_to_sell) > parseFloat(item.stock_quantity)) {
                            Swal.fire({ icon: 'error', title: 'Stock Insuficiente', text: `Solo tienes ${item.stock_quantity} ${item.display_unit} de ${item.name}` });
                            return;
                        }
                    }
                }

                this.paymentMethod = 'cash';
                this.paymentReference = '';
                this.paymentAmount = '';
                this.change = 0;
                this.showCheckoutModal = true;
                
                // Focus input after modal opens
                setTimeout(() => {
                    if(this.$refs.payInput) this.$refs.payInput.focus();
                }, 100);
            } catch (e) {
                console.error("Checkout Error:", e);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Ocurrió un error al abrir la caja. Por favor recarga la página.' });
            }
        },

        updateChange() {
            if (this.paymentAmount) {
                if (this.paymentMethod === 'cash_bs') {
                    // Change in Bs
                    // Remove commas if present (though input type number usually handles dot)
                    // If cartTotalBs has commas, it's a string. We need a raw float value.
                    // We need to re-calculate total Bs here to be sure or parse the string.
                    // cartTotalBs getter returns string. Let's recalculate locally or create a helper.
                    
                    const totalBs = this.cart.reduce((sum, item) => {
                         const rate = (item.use_special_rate == 1 || item.use_special_rate === true) 
                                      ? this.config.exchange_rate_special 
                                      : this.config.exchange_rate_global;
                         return sum + (parseFloat(item.subtotal || 0) * rate);
                    }, 0);
                    
                    this.change = (parseFloat(this.paymentAmount) - totalBs).toLocaleString('es-VE', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                    
                } else {
                    this.change = (parseFloat(this.paymentAmount) - parseFloat(this.cartTotal)).toFixed(2);
                }
            } else {
                this.change = 0;
            }
        },

        async processSale() {
            // Validate Payment
            if (this.paymentMethod === 'cash') {
                if (parseFloat(this.paymentAmount) < parseFloat(this.cartTotal)) {
                     Swal.fire({ icon: 'error', title: 'Monto Insuficiente', text: 'El monto recibido es menor al total.' });
                     return;
                }
            } else if (this.paymentMethod === 'cash_bs') {
                const totalBs = this.cart.reduce((sum, item) => {
                     const rate = (item.use_special_rate == 1 || item.use_special_rate === true) 
                                  ? this.config.exchange_rate_special 
                                  : this.config.exchange_rate_global;
                     return sum + (parseFloat(item.subtotal || 0) * rate);
                }, 0);
                
                if (parseFloat(this.paymentAmount) < totalBs) {
                     Swal.fire({ icon: 'error', title: 'Monto Insuficiente', text: 'El monto en Bs es menor al total.' });
                     return;
                }
            } else {
                // Card/QR/Biopago: Assume exact amount or just proceed
                this.paymentAmount = this.cartTotal;
            }

            const payload = {
                cart: this.cart,
                payment_method: this.paymentMethod,
                payment_reference: this.paymentReference,
                amount_tendered: this.paymentAmount || this.cartTotal
            };

            const res = await fetch('api/sales.php?action=save', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const json = await res.json();
            
            if(json.status === 'success') {
                this.showCheckoutModal = false;
                this.tempSaleId = json.sale_id;
                
                // Success Action
                Swal.fire({
                    icon: 'success',
                    title: '¡Venta Exitosa!',
                    html: `
                        <p class="mb-4">Total Cobrado: <b>$ ${this.cartTotal}</b></p>
                        ${this.paymentMethod === 'cash' ? `<p class="text-lg">Cambio: <b class="text-emerald-400">$ ${this.change}</b></p>` : ''}
                        ${this.paymentMethod === 'cash_bs' ? `<p class="text-lg">Cambio: <b class="text-emerald-400">Bs ${this.change}</b></p>` : ''}
                    `,
                    showCancelButton: true,
                    confirmButtonText: '<i class="fa-solid fa-print"></i> Imprimir Ticket',
                    cancelButtonText: 'Cerrar',
                    background: '#0f172a',
                    color: '#fff',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.printTicket(this.tempSaleId);
                    }
                    this.cart = [];
                    this.fetchProducts();
                });

            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: json.message });
            }
        },
        
        printTicket(id) {
            const width = 350;
            const height = 600;
            const left = (screen.width - width) / 2;
            const top = (screen.height - height) / 2;
            window.open(`ticket.php?id=${id}`, '_blank', `width=${width},height=${height},top=${top},left=${left}`);
        }

    }));
});


// Helper Notification Function
function notify(icon, title, text = '') {
    const isDark = document.documentElement.classList.contains('dark');
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: icon,
        title: title,
        text: text,
        showConfirmButton: false,
        timer: 2000,
        background: isDark ? '#1e293b' : '#fff',
        color: isDark ? '#fff' : '#1e293b'
    });
}
</script>
</body>
</html>


