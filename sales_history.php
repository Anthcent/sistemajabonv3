<?php require_once 'includes/header.php'; ?>
<?php require_once 'includes/nav.php'; ?>
<!-- Chart.js local -->
<script src="assets/js/chart.js"></script>

<div x-data="salesHistory" class="w-full px-4 md:px-6 pb-20 pt-6">
    <!-- VIEW TOGGLE -->
    <div class="flex justify-center mb-8">
        <div class="flex bg-gray-100 dark:bg-black/20 p-1 rounded-2xl border border-gray-200 dark:border-white/5">
            <button @click="currentView = 'list'" :class="currentView === 'list' ? 'bg-white dark:bg-brand-600 shadow-lg text-brand-600 dark:text-white' : 'text-gray-500'" class="px-8 py-2.5 rounded-xl font-bold transition-all flex items-center gap-2">
                <i class="fa-solid fa-list"></i> Lista
            </button>
            <button @click="currentView = 'stats'; loadStats()" :class="currentView === 'stats' ? 'bg-white dark:bg-brand-600 shadow-lg text-brand-600 dark:text-white' : 'text-gray-500'" class="px-8 py-2.5 rounded-xl font-bold transition-all flex items-center gap-2">
                <i class="fa-solid fa-chart-line"></i> Estadísticas
            </button>
        </div>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-6">
        <div class="text-center md:text-left">
            <h1 class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-400 drop-shadow-sm">
                Historial de Ventas
            </h1>
            <p class="text-gray-400 font-light mt-1">Control y seguimiento de todas las transacciones.</p>
        </div>
        
        <div class="flex gap-2 bg-gray-100 dark:bg-black/20 p-1 rounded-xl border border-gray-200 dark:border-white/5">
            <button @click="dateFilter = 'today'" :class="dateFilter === 'today' ? 'bg-white dark:bg-brand-600 shadow-lg text-brand-600 dark:text-white' : 'text-gray-500 hover:text-white'" class="px-4 py-2 rounded-lg text-xs font-bold transition-all">HOY</button>
            <button @click="dateFilter = 'yesterday'" :class="dateFilter === 'yesterday' ? 'bg-white dark:bg-brand-600 shadow-lg text-brand-600 dark:text-white' : 'text-gray-500 hover:text-white'" class="px-4 py-2 rounded-lg text-xs font-bold transition-all">AYER</button>
            <button @click="dateFilter = '7days'" :class="dateFilter === '7days' ? 'bg-white dark:bg-brand-600 shadow-lg text-brand-600 dark:text-white' : 'text-gray-500 hover:text-white'" class="px-4 py-2 rounded-lg text-xs font-bold transition-all">7 DÍAS</button>
            <button @click="dateFilter = 'all'" :class="dateFilter === 'all' ? 'bg-white dark:bg-brand-600 shadow-lg text-brand-600 dark:text-white' : 'text-gray-500 hover:text-white'" class="px-4 py-2 rounded-lg text-xs font-bold transition-all">TODO</button>
            <button @click="dateFilter = 'custom'" :class="dateFilter === 'custom' ? 'bg-white dark:bg-brand-600 shadow-lg text-brand-600 dark:text-white' : 'text-gray-500 hover:text-white'" class="px-4 py-2 rounded-lg text-xs font-bold transition-all">RANGO</button>
        </div>
    </div>

    <!-- CUSTOM DATE RANGE INPUTS -->
    <div x-show="dateFilter === 'custom'" x-transition class="flex justify-center gap-4 mb-6">
        <div class="flex items-center gap-2 bg-white dark:bg-black/20 p-2 rounded-xl border border-gray-200 dark:border-white/5">
            <label class="text-xs font-bold text-gray-500 uppercase">Desde:</label>
            <input type="date" x-model="startDate" class="bg-transparent text-sm font-bold text-gray-900 dark:text-white focus:outline-none">
        </div>
        <div class="flex items-center gap-2 bg-white dark:bg-black/20 p-2 rounded-xl border border-gray-200 dark:border-white/5">
            <label class="text-xs font-bold text-gray-500 uppercase">Hasta:</label>
            <input type="date" x-model="endDate" class="bg-transparent text-sm font-bold text-gray-900 dark:text-white focus:outline-none">
        </div>
    </div>

    <!-- PAYMENT METHOD FILTERS -->
    <div class="flex flex-wrap justify-center gap-2 mb-8" x-show="currentView === 'list'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2">
        <button @click="paymentFilter = 'all'" 
            :class="paymentFilter === 'all' ? 'bg-brand-600 text-white shadow-lg ring-2 ring-brand-500 ring-offset-2 dark:ring-offset-gray-900 border-transparent' : 'bg-white dark:bg-black/20 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 border-gray-200 dark:border-white/5'"
            class="px-4 py-2 rounded-xl text-xs font-bold border transition-all flex items-center gap-2">
            <i class="fa-solid fa-layer-group"></i> TODOS
        </button>

        <button @click="paymentFilter = 'cash'" 
            :class="paymentFilter === 'cash' ? 'bg-emerald-500 text-white shadow-lg ring-2 ring-emerald-500 ring-offset-2 dark:ring-offset-gray-900 border-transparent' : 'bg-white dark:bg-black/20 text-gray-500 dark:text-gray-400 hover:bg-emerald-50 dark:hover:bg-emerald-500/10 border-gray-200 dark:border-white/5'"
            class="px-4 py-2 rounded-xl text-xs font-bold border transition-all flex items-center gap-2">
            <i class="fa-solid fa-money-bill-wave"></i> EFECTIVO $
        </button>

        <button @click="paymentFilter = 'cash_bs'" 
            :class="paymentFilter === 'cash_bs' ? 'bg-teal-500 text-white shadow-lg ring-2 ring-teal-500 ring-offset-2 dark:ring-offset-gray-900 border-transparent' : 'bg-white dark:bg-black/20 text-gray-500 dark:text-gray-400 hover:bg-teal-50 dark:hover:bg-teal-500/10 border-gray-200 dark:border-white/5'"
            class="px-4 py-2 rounded-xl text-xs font-bold border transition-all flex items-center gap-2">
            <i class="fa-solid fa-money-bill-1-wave"></i> EFECTIVO BS
        </button>

        <button @click="paymentFilter = 'biopago'" 
            :class="paymentFilter === 'biopago' ? 'bg-indigo-500 text-white shadow-lg ring-2 ring-indigo-500 ring-offset-2 dark:ring-offset-gray-900 border-transparent' : 'bg-white dark:bg-black/20 text-gray-500 dark:text-gray-400 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 border-gray-200 dark:border-white/5'"
            class="px-4 py-2 rounded-xl text-xs font-bold border transition-all flex items-center gap-2">
            <i class="fa-solid fa-fingerprint"></i> BIOPAGO
        </button>

        <button @click="paymentFilter = 'pago_movil'" 
            :class="paymentFilter === 'pago_movil' ? 'bg-purple-500 text-white shadow-lg ring-2 ring-purple-500 ring-offset-2 dark:ring-offset-gray-900 border-transparent' : 'bg-white dark:bg-black/20 text-gray-500 dark:text-gray-400 hover:bg-purple-50 dark:hover:bg-purple-500/10 border-gray-200 dark:border-white/5'"
            class="px-4 py-2 rounded-xl text-xs font-bold border transition-all flex items-center gap-2">
            <i class="fa-solid fa-mobile-screen-button"></i> PAGO MÓVIL
        </button>
        
        <button @click="paymentFilter = 'transferencia'" 
            :class="paymentFilter === 'transferencia' ? 'bg-orange-500 text-white shadow-lg ring-2 ring-orange-500 ring-offset-2 dark:ring-offset-gray-900 border-transparent' : 'bg-white dark:bg-black/20 text-gray-500 dark:text-gray-400 hover:bg-orange-50 dark:hover:bg-orange-500/10 border-gray-200 dark:border-white/5'"
            class="px-4 py-2 rounded-xl text-xs font-bold border transition-all flex items-center gap-2">
            <i class="fa-solid fa-building-columns"></i> TRANSF.
        </button>

        <button @click="paymentFilter = 'card'" 
            :class="paymentFilter === 'card' ? 'bg-blue-500 text-white shadow-lg ring-2 ring-blue-500 ring-offset-2 dark:ring-offset-gray-900 border-transparent' : 'bg-white dark:bg-black/20 text-gray-500 dark:text-gray-400 hover:bg-blue-50 dark:hover:bg-blue-500/10 border-gray-200 dark:border-white/5'"
            class="px-4 py-2 rounded-xl text-xs font-bold border transition-all flex items-center gap-2">
            <i class="fa-solid fa-credit-card"></i> TARJETA
        </button>
    </div>

    <!-- LIST VIEW CONTENT -->
    <div x-show="currentView === 'list'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4">


    <!-- METRICS CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <!-- Metric 1 -->
        <div class="glass p-6 rounded-2xl border border-gray-200 dark:border-white/5 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 opacity-5 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-money-bill-trend-up text-8xl"></i>
            </div>
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Total en Ventas</p>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-gray-900 dark:text-white">$ <span x-text="metrics.totalAmount"></span></span>
            </div>
            <div class="mt-4 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-[10px] font-bold text-emerald-500 uppercase" x-text="dateFilterLabel + (paymentFilter !== 'all' ? ' • ' + paymentFilterLabel : '')"></span>
            </div>
        </div>

        <!-- Metric 2 -->
        <div class="glass p-6 rounded-2xl border border-gray-200 dark:border-white/5 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 opacity-5 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-receipt text-8xl"></i>
            </div>
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Transacciones</p>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-gray-900 dark:text-white" x-text="metrics.count"></span>
                <span class="text-sm text-gray-500">tickets</span>
            </div>
             <div class="mt-4">
                <span class="text-[10px] font-bold text-gray-500 uppercase">Actividad Registrada</span>
            </div>
        </div>

        <!-- Metric 3 -->
        <div class="glass p-6 rounded-2xl border border-gray-200 dark:border-white/5 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 opacity-5 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-chart-pie text-8xl"></i>
            </div>
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Ticket Promedio</p>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-gray-900 dark:text-white">$ <span x-text="metrics.avg"></span></span>
            </div>
            <div class="mt-4">
                <span class="text-[10px] font-bold text-gray-500 uppercase">Rendimiento por Cliente</span>
            </div>
        </div>
    </div>

    <!-- History Table -->
    <div class="glass rounded-xl overflow-hidden border border-gray-200 dark:border-white/5">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-gray-600 dark:text-gray-400 min-w-[600px]">
                <thead class="bg-gray-100 dark:bg-black/40 text-[10px] uppercase font-bold text-gray-500 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-4">ID Venta</th>
                        <th class="px-6 py-4">Fecha / Hora</th>
                        <th class="px-6 py-4">Método</th>
                        <th class="px-6 py-4 text-right">Total ($)</th>
                        <th class="px-6 py-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    <template x-for="sale in filteredSales" :key="sale.id">
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors border-b border-gray-100 dark:border-gray-800 last:border-0 group">
                            <!-- Col 1: ID -->
                            <td class="px-6 py-5">
                                <div class="w-10 h-10 rounded-xl bg-brand-500/10 flex items-center justify-center text-brand-500 font-black text-xs border border-brand-500/20 shadow-sm">
                                    #<span x-text="sale.id"></span>
                                </div>
                            </td>
                            <!-- Col 2: FECHA / HORA -->
                            <td class="px-6 py-5">
                                <div class="flex flex-col">
                                    <span class="text-gray-700 dark:text-gray-300 text-sm font-bold" x-text="formatDate(sale.created_at).split(',')[0]"></span>
                                    <span class="text-[10px] text-gray-500 font-medium" x-text="formatDate(sale.created_at).split(',')[1]"></span>
                                </div>
                            </td>
                            <!-- Col 3: MÉTODO -->
                            <td class="px-6 py-5">
                                <template x-if="sale.payment_method === 'cash'">
                                    <div class="flex items-center gap-2 text-emerald-500">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center">
                                            <i class="fa-solid fa-money-bill-wave text-xs"></i>
                                        </div>
                                        <span class="text-[10px] font-black uppercase tracking-tight">Efectivo $</span>
                                    </div>
                                </template>
                                <template x-if="sale.payment_method === 'cash_bs'">
                                    <div class="flex items-center gap-2 text-teal-500">
                                        <div class="w-8 h-8 rounded-lg bg-teal-500/10 flex items-center justify-center">
                                            <i class="fa-solid fa-money-bill-1-wave text-xs"></i>
                                        </div>
                                        <span class="text-[10px] font-black uppercase tracking-tight">Efectivo Bs</span>
                                    </div>
                                </template>
                                <template x-if="sale.payment_method === 'biopago'">
                                    <div class="flex items-center gap-2 text-indigo-500">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-500/10 flex items-center justify-center">
                                            <i class="fa-solid fa-fingerprint text-xs"></i>
                                        </div>
                                        <span class="text-[10px] font-black uppercase tracking-tight">Biopago</span>
                                    </div>
                                </template>
                                <template x-if="sale.payment_method === 'pago_movil'">
                                    <div class="flex items-center gap-2 text-purple-500">
                                        <div class="w-8 h-8 rounded-lg bg-purple-500/10 flex items-center justify-center">
                                            <i class="fa-solid fa-mobile-screen-button text-xs"></i>
                                        </div>
                                        <span class="text-[10px] font-black uppercase tracking-tight">Pago Móvil</span>
                                    </div>
                                </template>
                                <template x-if="sale.payment_method === 'transferencia'">
                                    <div class="flex items-center gap-2 text-orange-500">
                                        <div class="w-8 h-8 rounded-lg bg-orange-500/10 flex items-center justify-center">
                                            <i class="fa-solid fa-building-columns text-xs"></i>
                                        </div>
                                        <span class="text-[10px] font-black uppercase tracking-tight">Transferencia</span>
                                    </div>
                                </template>
                                <template x-if="['cash','cash_bs','biopago','pago_movil','transferencia'].indexOf(sale.payment_method) === -1">
                                    <div class="flex items-center gap-2 text-blue-500">
                                        <div class="w-8 h-8 rounded-lg bg-blue-500/10 flex items-center justify-center">
                                            <i class="fa-solid fa-credit-card text-xs"></i>
                                        </div>
                                        <span class="text-[10px] font-black uppercase tracking-tight">Tarjeta</span>
                                    </div>
                                </template>
                            </td>
                            <!-- Col 4: TOTAL -->
                            <td class="px-6 py-5 text-right">
                                <p class="font-black text-gray-900 dark:text-white text-xl tracking-tight">$ <span x-text="parseFloat(sale.total_amount).toLocaleString()"></span></p>
                                <p class="text-[9px] text-emerald-500 font-bold uppercase">Pago Completado</p>
                            </td>
                            <!-- Col 5: ACCIONES -->
                            <td class="px-6 py-5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Print Button -->
                                    <button @click="printTicket(sale.id)" class="w-9 h-9 rounded-xl bg-white dark:bg-dark-surface text-gray-400 hover:text-brand-500 hover:shadow-lg border border-gray-200 dark:border-white/5 transition-all flex items-center justify-center" title="Imprimir Ticket">
                                        <i class="fa-solid fa-receipt text-sm"></i>
                                    </button>
                                    <!-- Details Button -->
                                    <button @click="viewDetails(sale)" class="w-9 h-9 rounded-xl bg-white dark:bg-dark-surface text-gray-400 hover:text-blue-500 hover:shadow-lg border border-gray-200 dark:border-white/5 transition-all flex items-center justify-center" title="Ver Detalles">
                                        <i class="fa-solid fa-eye text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="sales.length === 0">
                        <td colspan="4" class="py-12 text-center text-gray-500">
                            <i class="fa-solid fa-clock-rotate-left text-4xl mb-3 opacity-50"></i>
                            <p>No hay ventas registradas.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    </div>

    <!-- Details Modal: Premium Receipt Design -->
    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:leave="transition ease-in duration-200" style="display: none;">
        <div class="absolute inset-0 bg-slate-900/60 dark:bg-black/80 backdrop-blur-md" @click="showModal = false"></div>
        
        <div class="relative w-[95%] max-w-lg bg-white dark:bg-slate-900 rounded-[2rem] shadow-2xl overflow-hidden border border-white/20 max-h-[90vh] flex flex-col">
            <!-- Receipt Header Decor (Fixed) -->
            <div class="h-24 shrink-0 bg-gradient-to-br from-brand-500 to-emerald-500 flex items-center justify-center relative">
                <div class="w-16 h-16 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-white text-3xl shadow-inner border border-white/30 scale-110">
                    <i class="fa-solid fa-check"></i>
                </div>
                <!-- Cut effect circles -->
                <div class="absolute -bottom-3 left-0 right-0 flex justify-between px-4">
                    <template x-for="i in 10">
                        <div class="w-4 h-4 rounded-full bg-white dark:bg-slate-900"></div>
                    </template>
                </div>
            </div>

            <!-- Scrollable Content -->
            <div class="p-6 overflow-y-auto custom-scrollbar">
                <div class="text-center mb-6">
                    <h2 class="text-3xl font-black text-slate-900 dark:text-white mb-1">Venta Exitosa</h2>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest" x-text="'ORDEN ID: #' + currentSale.id"></p>
                    <div class="flex justify-center items-center gap-2 mt-2 text-[10px] font-bold text-gray-500 bg-gray-100 dark:bg-white/5 w-fit mx-auto px-3 py-1 rounded-full border border-gray-200 dark:border-white/5">
                        <i class="fa-regular fa-calendar"></i>
                        <span x-text="formatDate(currentSale.created_at)"></span>
                    </div>
                </div>

                <!-- Items -->
                <div class="space-y-4 mb-8 max-h-[350px] overflow-y-auto custom-scrollbar pr-2">
                    <template x-for="item in saleItems" :key="item.id">
                        <div class="flex justify-between items-start group p-2.5 rounded-2xl hover:bg-gray-50 dark:hover:bg-white/5 transition-all border border-transparent hover:border-gray-100 dark:hover:border-white/10">
                            <div class="flex gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-white/5 flex items-center justify-center text-gray-400 text-xl group-hover:text-brand-500 group-hover:bg-brand-500/10 transition-colors">
                                    <i class="fa-solid" :class="item.is_liquid ? 'fa-faucet-drip' : 'fa-box-open'"></i>
                                </div>
                                <div class="flex flex-col justify-center">
                                    <p class="text-base font-black text-slate-800 dark:text-slate-200 leading-tight mb-1" x-text="item.product_name"></p>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <!-- Quantity Badge -->
                                        <div class="flex items-center gap-1 text-[10px] font-bold bg-gray-100 dark:bg-white/10 px-2 py-0.5 rounded-lg text-gray-500">
                                            <i class="fa-solid fa-layer-group text-[9px]"></i>
                                            <span x-text="parseFloat(item.quantity).toFixed(2)"></span>
                                        </div>
                                        
                                        <!-- Rate Indicator -->
                                        <span class="text-[10px] px-2 py-0.5 rounded-lg font-bold uppercase tracking-wider border flex items-center gap-1" 
                                              :class="(item.use_special_rate == 1 || item.use_special_rate === true) ? 'bg-purple-50 text-purple-700 border-purple-200 dark:bg-purple-500/10 dark:text-purple-300 dark:border-purple-500/20' : 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-500/10 dark:text-blue-300 dark:border-blue-500/20'">
                                            <i class="fa-solid fa-tag text-[9px]"></i>
                                            <span x-text="(item.use_special_rate == 1 || item.use_special_rate === true) ? 'Tasa Esp.' : 'Tasa Global'"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-right flex flex-col justify-center h-full">
                                <p class="text-lg font-black text-emerald-600 dark:text-emerald-400 tracking-tight" x-text="'$ ' + parseFloat(item.subtotal).toFixed(2)"></p>
                                <p class="text-sm font-bold text-gray-600 dark:text-gray-400 mt-0.5">Bs <span x-text="(parseFloat(item.subtotal) * ((item.use_special_rate == 1 || item.use_special_rate === true) ? parseFloat(currentSale.exchange_rate_special) : parseFloat(currentSale.exchange_rate_global))).toLocaleString('es-VE', {minimumFractionDigits: 2})"></span></p>
                                <p class="text-[9px] text-gray-400 font-medium mt-0.5">PU: $ <span x-text="parseFloat(item.price || 0).toFixed(2)"></span></p>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Totals -->
                <div class="border-t-2 border-dashed border-gray-200 dark:border-white/10 pt-6 space-y-3">
                    <!-- Exchange Rates Info -->
                    <div class="grid grid-cols-2 gap-4 text-[10px] text-gray-500 mb-2">
                        <div class="bg-gray-50 dark:bg-white/5 p-2 rounded-lg text-center">
                            <span class="block font-bold">Tasa Global</span>
                            <span x-text="'Bs ' + parseFloat(currentSale.exchange_rate_global || 0).toLocaleString('es-VE')"></span>
                        </div>
                        <div class="bg-gray-50 dark:bg-white/5 p-2 rounded-lg text-center">
                            <span class="block font-bold text-purple-500">Tasa Especial</span>
                            <span x-text="'Bs ' + parseFloat(currentSale.exchange_rate_special || 0).toLocaleString('es-VE')"></span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center text-xs font-bold text-gray-500 uppercase tracking-tighter">
                        <span>Método de Pago</span>
                        <span class="text-brand-500" x-text="
                            currentSale.payment_method === 'cash' ? 'Efectivo $' : 
                            (currentSale.payment_method === 'cash_bs' ? 'Efectivo Bs' : 
                            (currentSale.payment_method === 'biopago' ? 'Biopago' : 
                            (currentSale.payment_method === 'pago_movil' ? 'Pago Móvil' : 
                            (currentSale.payment_method === 'transferencia' ? 'Transferencia' : 'Tarjeta'))))
                        "></span>
                    </div>
                     <div class="flex justify-between items-end">
                        <div class="flex flex-col">
                             <span class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-tighter mb-1">Total Pagado</span>
                             <span class="text-xs font-bold text-gray-400">Monto Global</span>
                        </div>
                        <div class="text-right">
                             <span class="block text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-teal-400 tracking-tighter mb-1">$ <span x-text="parseFloat(currentSale.total_amount).toFixed(2)"></span></span>
                             <div class="inline-flex items-center gap-2 bg-gray-100 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-lg px-3 py-1">
                                <span class="text-xs text-gray-400 font-bold">Total Bs:</span>
                                <span class="text-lg font-black text-gray-700 dark:text-gray-200 tracking-tight" x-text="totalBsExact"></span>
                             </div>
                             <div class="flex flex-col text-right">
                                <span class="text-xs text-gray-400 font-bold">Fecha:</span>
                                <span class="text-sm font-bold text-gray-900 dark:text-white" x-text="formatDate(currentSale.created_at)"></span>
                             </div>
                             
                             <!-- Reference Display -->
                             <template x-if="currentSale.payment_reference">
                                 <div class="mt-2 text-right">
                                    <span class="text-[10px] text-gray-400 font-bold uppercase block">Referencia:</span>
                                    <span class="text-xs font-mono font-bold px-2 py-0.5 rounded" 
                                          :class="{
                                              'bg-orange-100 dark:bg-orange-500/20 text-orange-600 dark:text-orange-400': currentSale.payment_method === 'transferencia',
                                              'bg-purple-100 dark:bg-purple-500/20 text-purple-600 dark:text-purple-400': currentSale.payment_method === 'pago_movil',
                                              'bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-gray-300': ['transferencia', 'pago_movil'].indexOf(currentSale.payment_method) === -1
                                          }"
                                          x-text="currentSale.payment_reference"></span>
                                 </div>
                             </template>
                        </div>
                    </div>

                    <!-- Change / Vuelto Info -->
                    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-white/5" x-show="currentSale.payment_method === 'cash' || currentSale.payment_method === 'cash_bs'">
                        <div class="flex justify-between items-center bg-emerald-50 dark:bg-emerald-500/10 p-3 rounded-xl border border-emerald-100 dark:border-emerald-500/20">
                            
                            <!-- CASH USD -->
                            <template x-if="currentSale.payment_method === 'cash'">
                                <div class="w-full flex justify-between items-center">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-gray-500 uppercase">Monto Recibido</span>
                                        <span class="text-lg font-black text-gray-900 dark:text-white">$ <span x-text="parseFloat(currentSale.amount_tendered).toFixed(2)"></span></span>
                                    </div>
                                    <div class="flex flex-col text-right">
                                        <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 uppercase">Cambio / Vuelto</span>
                                        <span class="text-xl font-black text-emerald-500">$ <span x-text="(parseFloat(currentSale.amount_tendered) - parseFloat(currentSale.total_amount)).toFixed(2)"></span></span>
                                    </div>
                                </div>
                            </template>

                            <!-- CASH BS -->
                            <template x-if="currentSale.payment_method === 'cash_bs'">
                                <div class="w-full flex justify-between items-center">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-gray-500 uppercase">Monto Recibido</span>
                                        <span class="text-lg font-black text-gray-900 dark:text-white">Bs <span x-text="parseFloat(currentSale.amount_tendered).toLocaleString('es-VE', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span></span>
                                    </div>
                                    <div class="flex flex-col text-right">
                                        <span class="text-[10px] font-bold text-teal-600 dark:text-teal-400 uppercase">Cambio / Vuelto</span>
                                        <span class="text-xl font-black text-teal-500">Bs <span x-text="(parseFloat(currentSale.amount_tendered) - totalBsRaw).toLocaleString('es-VE', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span></span>
                                    </div>
                                </div>
                            </template>

                        </div>
                    </div>
                </div>

                <!-- Action Button -->
                <button @click="printCurrentSale()" class="w-full mt-8 py-4 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-2xl font-black text-sm uppercase tracking-widest shadow-xl hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-3">
                    <i class="fa-solid fa-print"></i>
                    Reimprimir Ticket
                </button>
                
                <button @click="showModal = false" class="w-full mt-3 py-2 text-gray-400 font-bold text-[10px] uppercase tracking-widest hover:text-red-500 transition-colors">
                    Cerrar Detalle
                </button>
            </div>
        </div>
    </div>

    <!-- STATISTICS VIEW CONTENT -->
    <div x-show="currentView === 'stats'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" style="display: none;">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Chart 1: Sales Trend -->
            <div class="glass p-6 rounded-2xl border border-gray-200 dark:border-white/5 h-[400px]">
                <h3 class="text-lg font-bold mb-4 text-gray-700 dark:text-gray-300">Ventas (Últimos 7 días)</h3>
                <canvas id="trendChart"></canvas>
            </div>

            <!-- Chart 2: Top Products -->
            <div class="glass p-6 rounded-2xl border border-gray-200 dark:border-white/5 h-[400px]">
                <h3 class="text-lg font-bold mb-4 text-gray-700 dark:text-gray-300">Top 5 Productos Vendidos</h3>
                <canvas id="productsChart"></canvas>
            </div>

            <!-- Chart 3: Categories -->
            <div class="glass p-6 rounded-2xl border border-gray-200 dark:border-white/5 h-[400px]">
                <h3 class="text-lg font-bold mb-4 text-gray-700 dark:text-gray-300">Ventas por Categoría</h3>
                <canvas id="categoryChart"></canvas>
            </div>
            
            <!-- Quick Summary Stats (Vertical) -->
            <div class="space-y-6">
                <template x-for="p in paymentStats" :key="p.payment_method">
                    <div class="bg-gradient-to-r from-gray-100 to-white dark:from-gray-800 dark:to-gray-900 p-6 rounded-2xl border border-gray-200 dark:border-white/5 flex justify-between items-center">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl" 
                                 :class="{
                                    'bg-emerald-500/20 text-emerald-500': p.payment_method === 'cash',
                                    'bg-teal-500/20 text-teal-500': p.payment_method === 'cash_bs',
                                    'bg-indigo-500/20 text-indigo-500': p.payment_method === 'biopago',
                                    'bg-purple-500/20 text-purple-500': p.payment_method === 'pago_movil',
                                    'bg-orange-500/20 text-orange-500': p.payment_method === 'transferencia',
                                    'bg-blue-500/20 text-blue-500': ['cash','cash_bs','biopago','pago_movil','transferencia'].indexOf(p.payment_method) === -1
                                 }">
                                <i class="fa-solid" :class="{
                                    'fa-money-bill-wave': p.payment_method === 'cash',
                                    'fa-money-bill-1-wave': p.payment_method === 'cash_bs',
                                    'fa-fingerprint': p.payment_method === 'biopago',
                                    'fa-mobile-screen-button': p.payment_method === 'pago_movil',
                                    'fa-building-columns': p.payment_method === 'transferencia',
                                    'fa-credit-card': ['cash','cash_bs','biopago','pago_movil','transferencia'].indexOf(p.payment_method) === -1
                                }"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase" x-text="
                                    p.payment_method === 'cash' ? 'Efectivo $' : 
                                    (p.payment_method === 'cash_bs' ? 'Efectivo Bs' : 
                                    (p.payment_method === 'biopago' ? 'Biopago' : 
                                    (p.payment_method === 'pago_movil' ? 'Pago Móvil' : 
                                    (p.payment_method === 'transferencia' ? 'Transferencia' : 'Tarjeta'))))"></p>
                                <p class="text-lg font-black text-gray-900 dark:text-white" x-text="p.count + ' Transacciones'"></p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-black text-gray-900 dark:text-white">$ <span x-text="parseFloat(p.total).toFixed(2)"></span></p>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('salesHistory', () => ({
        sales: [],
        showModal: false,
        currentSale: {},
        saleItems: [],
        dateFilter: 'today', // today | yesterday | 7days | all | custom
        startDate: '',
        endDate: '',
        paymentFilter: 'all', // all | cash | biopago | pago_movil | transferencia | card
        currentView: 'list', // list | stats
        paymentStats: [],
        charts: {},

        init() {
            this.fetchSales();
            
            // Watch for filter changes to reload stats if visible
            this.$watch('dateFilter', () => {
                if (this.currentView === 'stats') {
                    this.loadStats();
                }
            });
        },

        async loadStats() {
            const res = await fetch(`api/stats.php?action=dashboard&range=${this.dateFilter}`);
            const json = await res.json();
            if (json.status === 'success') {
                const data = json.data;
                this.paymentStats = data.paymentMethods;
                
                this.$nextTick(() => {
                    this.renderCharts(data);
                });
            }
        },

        renderCharts(data) {
            // Destroy existing charts if any
            Object.values(this.charts).forEach(chart => chart.destroy());

            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#94a3b8' : '#475569';
            const gridColor = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';

            // 1. Trend Chart
            this.charts.trend = new Chart(document.getElementById('trendChart'), {
                type: 'line',
                data: {
                    labels: data.trend.map(t => t.date),
                    datasets: [{
                        label: 'Ventas ($)',
                        data: data.trend.map(t => t.total),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { grid: { color: gridColor }, ticks: { color: textColor } },
                        x: { grid: { display: false }, ticks: { color: textColor } }
                    }
                }
            });

            // 2. Products Chart
            this.charts.products = new Chart(document.getElementById('productsChart'), {
                type: 'bar',
                data: {
                    labels: data.topProducts.map(p => p.name),
                    datasets: [{
                        label: 'Cant. Vendida',
                        data: data.topProducts.map(p => p.total_qty),
                        backgroundColor: '#0ea5e9',
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { color: gridColor }, ticks: { color: textColor } },
                        y: { grid: { display: false }, ticks: { color: textColor } }
                    }
                }
            });

            // 3. Category Chart
            this.charts.category = new Chart(document.getElementById('categoryChart'), {
                type: 'doughnut',
                data: {
                    labels: data.categorySales.map(c => c.name),
                    datasets: [{
                        data: data.categorySales.map(c => c.total_amount),
                        backgroundColor: ['#6366f1', '#f59e0b', '#ec4899', '#10b981', '#0ea5e9'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            bottom: 25
                        }
                    },
                    plugins: {
                        legend: { 
                            position: 'bottom', 
                            labels: { 
                                color: textColor, 
                                padding: 25,
                                font: { size: window.innerWidth < 768 ? 10 : 12 },
                                usePointStyle: true,
                                boxWidth: 8
                            } 
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) label += ': ';
                                    if (context.parsed !== null) {
                                        label += new Intl.NumberFormat('en-US', { 
                                            style: 'currency', 
                                            currency: 'USD',
                                            maximumFractionDigits: 0 
                                        }).format(context.parsed);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    cutout: '70%'
                }
            });
        },

        async fetchSales() {
            const res = await fetch('api/sales.php?action=list');
            const json = await res.json();
            if(json.status === 'success') this.sales = json.data;
        },

        async viewDetails(sale) {
            this.currentSale = sale;
            this.saleItems = []; // clear previous
            this.showModal = true;
            
            // Fetch Items
            const res = await fetch(`api/sales.php?action=get_details&id=${sale.id}`);
            const json = await res.json();
            if(json.status === 'success') {
                this.saleItems = json.data;
            }
        },

        printCurrentSale() {
            this.printTicket(this.currentSale.id);
        },

        printTicket(id) {
            const printUrl = `ticket.php?id=${id}`;
            const printWindow = window.open(printUrl, '_blank', 'width=350,height=600');
            // Check if window opened (popup blocker)
            if (printWindow) {
                printWindow.onload = () => {
                   // printWindow.print();
                   // setTimeout(() => printWindow.close(), 1000);
                };
            }
        },

        formatDate(dateStr) {
            if(!dateStr) return '';
            return new Date(dateStr).toLocaleString('es-ES', { 
                year: 'numeric', month: '2-digit', day: '2-digit', 
                hour: '2-digit', minute: '2-digit' 
            });
        },

        get filteredSales() {
            const now = new Date();
            const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
            
            return this.sales.filter(s => {
                const saleDate = new Date(s.created_at);
                const compareDate = new Date(saleDate.getFullYear(), saleDate.getMonth(), saleDate.getDate());
                
                // Date Filter
                let dateMatch = false;
                if (this.dateFilter === 'today') {
                    dateMatch = compareDate.getTime() === today.getTime();
                } else if(this.dateFilter === 'yesterday') {
                    const yesterday = new Date(today);
                    yesterday.setDate(yesterday.getDate() - 1);
                    dateMatch = compareDate.getTime() === yesterday.getTime();
                } else if(this.dateFilter === '7days') {
                    const sevenDaysAgo = new Date(today);
                    sevenDaysAgo.setDate(sevenDaysAgo.getDate() - 7);
                    dateMatch = compareDate >= sevenDaysAgo;
                } else if(this.dateFilter === 'custom' && this.startDate && this.endDate) {
                   const start = new Date(this.startDate);
                   const end = new Date(this.endDate);
                   // Standardize to midnight for comparison
                   const startMid = new Date(start.getFullYear(), start.getMonth(), start.getDate() + 1); // +1 fix timezone offset if needed or use local
                   // Simplified: just string comparison if formatted or standard date obj
                   // Let's use the same compareDate approach
                   const customStart = new Date(start.getFullYear(), start.getMonth(), start.getDate());
                   const customEnd = new Date(end.getFullYear(), end.getMonth(), end.getDate());
                   
                   // Adjust for timezone offset parsing of inputs which are YYYY-MM-DD
                   const inputStart = new Date(this.startDate + 'T00:00:00');
                   const inputEnd = new Date(this.endDate + 'T23:59:59');

                   dateMatch = saleDate >= inputStart && saleDate <= inputEnd;

                } else {
                    dateMatch = this.dateFilter === 'all' || this.dateFilter === 'custom'; // show all if custom incomplete
                }

                // Payment Filter
                let paymentMatch = true;
                if (this.paymentFilter !== 'all') {
                    if (this.paymentFilter === 'card') {
                        paymentMatch = ['cash', 'cash_bs', 'biopago', 'pago_movil', 'transferencia'].indexOf(s.payment_method) === -1;
                    } else {
                        paymentMatch = s.payment_method === this.paymentFilter;
                    }
                }

                return dateMatch && paymentMatch;
            });
        },

        get metrics() {
            const list = this.filteredSales;
            const total = list.reduce((sum, s) => sum + parseFloat(s.total_amount), 0);
            return {
                totalAmount: total.toFixed(2),
                count: list.length,
                avg: list.length > 0 ? (total / list.length).toFixed(2) : '0.00'
            };
        },

        get dateFilterLabel() {
            const labels = {
                'today': 'Ventas de Hoy',
                'yesterday': 'Ventas de Ayer',
                '7days': 'Últimos 7 Días',
                'all': 'Histórico Total',
                'custom': 'Rango Personalizado'
            };
            return labels[this.dateFilter];
        },
        
        get paymentFilterLabel() {
            const labels = {
                'all': 'Todos los Métodos',
                'cash': 'Solo Efectivo $',
                'cash_bs': 'Solo Efectivo Bs',
                'biopago': 'Solo Biopago',
                'pago_movil': 'Solo Pago Móvil',
                'transferencia': 'Solo Transferencia',
                'card': 'Solo Tarjeta'
            };
            return labels[this.paymentFilter] || '';
        },

        get totalBsExact() {
            if(!this.saleItems || this.saleItems.length === 0) return '0,00';
            
            let total = 0;
            this.saleItems.forEach(item => {
                const isSpecial = (item.use_special_rate == 1 || item.use_special_rate === true);
                const rate = isSpecial ? parseFloat(this.currentSale.exchange_rate_special || 0) : parseFloat(this.currentSale.exchange_rate_global || 0);
                total += parseFloat(item.subtotal) * rate;
            });
            
            return total.toLocaleString('es-VE', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        },

        get totalBsRaw() {
            if(!this.saleItems || this.saleItems.length === 0) return 0;
            let total = 0;
            this.saleItems.forEach(item => {
                const isSpecial = (item.use_special_rate == 1 || item.use_special_rate === true);
                const rate = isSpecial ? parseFloat(this.currentSale.exchange_rate_special || 0) : parseFloat(this.currentSale.exchange_rate_global || 0);
                total += parseFloat(item.subtotal) * rate;
            });
            return total;
        }
    }));
});
</script>
<?php require_once 'includes/footer.php'; ?>
