<?php require_once 'includes/auth.php'; require_pin(); require_once 'includes/header.php'; ?>
<?php require_once 'includes/nav.php'; ?>

<div x-data="movementHistory" class="w-full px-4 md:px-8 pb-20 pt-8">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-6">
        <div>
            <h1 class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-pink-500 drop-shadow-sm">
                Historial de Movimientos
            </h1>
            <p class="text-gray-400 font-light mt-1">Registro detallado de acciones y cambios en el sistema.</p>
        </div>
        <a href="settings.php" class="bg-gray-100 dark:bg-white/5 text-gray-500 hover:text-white hover:bg-gray-600 px-4 py-2 rounded-xl transition-all font-bold text-sm flex items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i> Volver
        </a>
    </div>

    <!-- FILTERS -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Search -->
        <div class="md:col-span-2 relative">
            <i class="fa-solid fa-search absolute left-4 top-3.5 text-gray-400"></i>
            <input x-model="filters.search" @input.debounce.500ms="fetchLogs()" type="text" 
                   class="w-full pl-10 pr-4 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl focus:outline-none focus:border-brand-500 text-sm text-gray-700 dark:text-gray-200" 
                   placeholder="Buscar en descripción...">
        </div>

        <!-- Type Filter -->
        <div>
            <select x-model="filters.type" @change="fetchLogs()" class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl focus:outline-none focus:border-brand-500 text-sm text-gray-700 dark:text-gray-200 appearance-none cursor-pointer">
                <option value="all">Todos los Tipos</option>
                <option value="SALE">Ventas</option>
                <option value="PRICE_UPDATE">Cambios de Precio</option>
                <option value="STOCK_UPDATE">Ajustes de Stock</option>
                <option value="PRODUCT_UPDATE">Edición Producto</option>
                <option value="PRODUCT_CREATE">Nuevos Productos</option>
                <option value="SETTINGS_UPDATE">Configuración</option>
            </select>
        </div>

        <!-- Date Filter (Simple) -->
        <div class="flex gap-2">
            <input x-model="filters.date_from" @change="fetchLogs()" type="date" class="w-full px-2 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl focus:outline-none focus:border-brand-500 text-xs text-gray-700 dark:text-gray-200">
             <input x-model="filters.date_to" @change="fetchLogs()" type="date" class="w-full px-2 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl focus:outline-none focus:border-brand-500 text-xs text-gray-700 dark:text-gray-200">
        </div>
    </div>

    <!-- TABLE -->
    <div class="glass p-6 rounded-2xl border border-gray-200 dark:border-white/5 shadow-xl relative overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-gray-600 dark:text-gray-400">
                <thead class="bg-gray-50 dark:bg-black/20 text-[10px] uppercase font-bold text-gray-400">
                    <tr>
                        <th class="px-6 py-4">Fecha / Hora</th>
                        <th class="px-6 py-4">Tipo</th>
                        <th class="px-6 py-4">Descripción</th>
                        <th class="px-6 py-4">Detalles</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <template x-for="log in logs" :key="log.id">
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-gray-700 dark:text-gray-300" x-text="formatDate(log.created_at).date"></span>
                                    <span class="text-xs text-gray-500" x-text="formatDate(log.created_at).time"></span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-[10px] font-bold px-2 py-1 rounded bg-gray-100 dark:bg-white/5 text-gray-500 border border-gray-200 dark:border-white/10"
                                      :class="getTypeColor(log.type)" x-text="formatType(log.type)"></span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-800 dark:text-gray-200" x-text="log.description"></p>
                            </td>
                            <td class="px-6 py-4">
                                <template x-if="log.details && log.details !== 'null'">
                                    <button @click="viewDetails(log)" class="text-brand-500 hover:text-brand-400 text-xs font-bold flex items-center gap-1">
                                        <i class="fa-solid fa-eye"></i> Ver
                                    </button>
                                </template>
                                <template x-if="!log.details || log.details === 'null'">
                                    <span class="text-xs text-gray-400">-</span>
                                </template>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="logs.length === 0">
                        <td colspan="4" class="text-center py-12 text-gray-500">
                            No hay movimientos registrados.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Details Modal -->
    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="showModal = false"></div>
        <div class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-2xl shadow-2xl p-6 border border-white/10 overflow-hidden">
            <div class="flex justify-between items-center mb-6 border-b border-gray-100 dark:border-white/5 pb-4">
                <div>
                     <h3 class="text-xl font-black text-gray-900 dark:text-white" x-text="formatType(currentLog.type)"></h3>
                     <p class="text-xs text-gray-400 font-bold uppercase tracking-widest" x-text="formatDate(currentLog.created_at).date + ' ' + formatDate(currentLog.created_at).time"></p>
                </div>
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl bg-gray-100 dark:bg-white/5">
                    <i class="fa-solid fa-info-circle text-gray-400"></i>
                </div>
            </div>

            <!-- Content Container -->
            <div class="space-y-4 max-h-[60vh] overflow-y-auto custom-scrollbar">
                
                <!-- PRICE UPDATES -->
                <template x-if="currentLog.type === 'PRICE_UPDATE' && parsedDetails.old">
                    <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-4 flex items-center justify-between">
                        <div class="text-center">
                            <p class="text-[10px] uppercase font-bold text-gray-400">Precio Anterior</p>
                            <p class="text-xl font-bold text-red-500 line-through decoration-2" x-text="'$ ' + parseFloat(parsedDetails.old).toFixed(2)"></p>
                            <template x-if="parsedDetails.old_bs">
                                <p class="text-xs text-red-400 font-bold line-through">Bs <span x-text="parseFloat(parsedDetails.old_bs).toLocaleString('es-VE')"></span></p>
                            </template>
                        </div>
                        <i class="fa-solid fa-arrow-right text-gray-300"></i>
                        <div class="text-center">
                            <p class="text-[10px] uppercase font-bold text-gray-400">Precio Nuevo</p>
                            <p class="text-2xl font-black text-emerald-500" x-text="'$ ' + parseFloat(parsedDetails.new).toFixed(2)"></p>
                            <template x-if="parsedDetails.new_bs">
                                <p class="text-sm text-emerald-600 font-black">Bs <span x-text="parseFloat(parsedDetails.new_bs).toLocaleString('es-VE')"></span></p>
                            </template>
                        </div>
                    </div>
                </template>

                 <!-- STOCK UPDATES -->
                 <template x-if="currentLog.type === 'STOCK_UPDATE'">
                    <div class="bg-blue-50 dark:bg-blue-900/10 rounded-xl p-4 text-center border border-blue-100 dark:border-blue-500/20">
                        <p class="text-[10px] uppercase font-bold text-blue-400 mb-1">Cantidad Ajustada</p>
                         <p class="text-3xl font-black text-blue-500" x-text="(parseFloat(parsedDetails.amount) > 0 ? '+' : '') + parseFloat(parsedDetails.amount)"></p>
                    </div>
                </template>

                <!-- SALE -->
                <template x-if="currentLog.type === 'SALE'">
                     <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-4">
                            <p class="text-[10px] uppercase font-bold text-gray-400">Total Venta</p>
                            <p class="text-2xl font-black text-emerald-500" x-text="'$ ' + parseFloat(parsedDetails.amount).toFixed(2)"></p>
                            <template x-if="parsedDetails.bs_amount">
                                <p class="text-sm font-bold text-gray-500 mt-1">Bs <span x-text="parseFloat(parsedDetails.bs_amount).toLocaleString('es-VE')"></span></p>
                            </template>
                        </div>
                        <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-4">
                            <p class="text-[10px] uppercase font-bold text-gray-400">Método Pago</p>
                            <p class="text-xl font-bold text-gray-700 dark:text-white uppercase" x-text="parsedDetails.method === 'cash' ? 'Efectivo' : 'Tarjeta/QR'"></p>
                        </div>
                     </div>
                </template>

                <!-- SETTINGS & OTHERS (Generic Key-Value) -->
                <template x-if="['SETTINGS_UPDATE', 'PRODUCT_UPDATE', 'PRODUCT_CREATE'].includes(currentLog.type)">
                     <div class="grid grid-cols-1 gap-2">
                        <template x-for="(val, key) in parsedDetails" :key="key">
                            <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-3 border border-gray-100 dark:border-white/5">
                                <p class="text-[10px] uppercase font-bold text-gray-400 mb-1" x-text="formatKey(key)"></p>
                                
                                <template x-if="typeof val === 'object' && val !== null && val.old !== undefined">
                                     <div class="flex items-center gap-2 text-sm">
                                         <span class="text-red-400 line-through" x-text="val.old"></span>
                                         <i class="fa-solid fa-arrow-right text-[10px] text-gray-500"></i>
                                         <span class="text-emerald-500 font-bold" x-text="val.new"></span>
                                     </div>
                                </template>
                                <template x-if="typeof val !== 'object' || val === null || val.old === undefined">
                                     <p class="text-sm font-bold text-gray-300" x-text="val"></p>
                                </template>
                            </div>
                        </template>
                     </div>
                </template>

            </div>

            <button @click="showModal = false" class="mt-6 w-full py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-xl font-bold hover:scale-[1.02] transition-transform">Cerrar Detalle</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('movementHistory', () => ({
        logs: [],
        showModal: false,
        currentDetails: '', // Raw string if needed, but we use parsedDetails object
        parsedDetails: {},
        currentLog: {},
        filters: {
            search: '',
            type: 'all',
            date_from: '',
            date_to: ''
        },

        init() {
            this.fetchLogs();
        },

        async fetchLogs() {
            try {
                // Build Query String
                const params = new URLSearchParams({
                    action: 'list',
                    search: this.filters.search,
                    type: this.filters.type,
                    date_from: this.filters.date_from,
                    date_to: this.filters.date_to
                });

                console.log('Fetching logs...', params.toString());
                const res = await fetch(`api/history.php?${params.toString()}`);
                const text = await res.text();
                // console.log('Raw response:', text);
                
                try {
                    const json = JSON.parse(text);
                    if (json.status === 'success') {
                        this.logs = json.data;
                        console.log('Logs loaded:', this.logs.length);
                    } else {
                        console.error('API Error:', json.message);
                    }
                } catch(e) {
                     console.error('JSON Parse Error:', text);
                }
            } catch (e) {
                console.error('Fetch Error:', e);
            }
        },

        viewDetails(log) {
            this.currentLog = log;
            try {
                let data = log.details;
                if (typeof data === 'string') {
                    data = JSON.parse(data);
                }
                this.parsedDetails = data || {};
                this.showModal = true;
            } catch (e) {
                this.parsedDetails = {};
                this.showModal = true;
            }
        },

        formatKey(key) {
            const map = {
                'company_name': 'Nombre Empresa',
                'exchange_rate_global': 'Tasa Global',
                'exchange_rate_special': 'Tasa Especial',
                'main_currency': 'Moneda Principal',
                'price': 'Precio',
                'stock': 'Stock Inicial',
                'address': 'Dirección',
                'phone': 'Teléfono',
                'nit_ruc_nif': 'RIF / NIT',
                'items_changed': 'Campos Modificados'
            };
            return map[key] || key.replace(/_/g, ' ');
        },

        formatDate(dateStr) {
            if(!dateStr) return {date:'', time:''};
            // Fix for Safari/Firefox if SQL uses space
            const safeDate = dateStr.replace(' ', 'T');
            const d = new Date(safeDate);
            if(isNaN(d.getTime())) return {date: dateStr, time: ''}; // Fallback

            return {
                date: d.toLocaleDateString('es-VE'),
                time: d.toLocaleTimeString('es-VE', {hour: '2-digit', minute:'2-digit'})
            };
        },

        formatType(type) {
            if(!type) return '';
            const map = {
                'PRICE_UPDATE': 'Cambio Precio',
                'STOCK_UPDATE': 'Ajuste Stock',
                'PRODUCT_UPDATE': 'Edición Producto',
                'PRODUCT_CREATE': 'Nuevo Producto',
                'PRODUCT_DELETE': 'Producto Eliminado',
                'SALE': 'Venta',
                'SETTINGS_UPDATE': 'Configuración'
            };
            return map[type] || type;
        },

        getTypeColor(type) {
            if(!type) return 'text-gray-500';
            if(type.includes('PRICE') || type.includes('SETTINGS')) return 'text-orange-500 border-orange-200 bg-orange-50 dark:bg-orange-900/20 dark:border-orange-500/30';
            if(type.includes('STOCK')) return 'text-blue-500 border-blue-200 bg-blue-50 dark:bg-blue-900/20 dark:border-blue-500/30';
            if(type.includes('SALE')) return 'text-emerald-500 border-emerald-200 bg-emerald-50 dark:bg-emerald-900/20 dark:border-emerald-500/30';
            if(type.includes('DELETE')) return 'text-red-500 border-red-200 bg-red-50 dark:bg-red-900/20 dark:border-red-500/30';
            return 'text-gray-500';
        }
    }));
});
</script>
<?php require_once 'includes/footer.php'; ?>
