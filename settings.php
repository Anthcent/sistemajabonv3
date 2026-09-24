<?php
require_once 'includes/auth.php';
require_pin();
require_once 'includes/header.php';
?>
<?php require_once 'includes/nav.php'; ?>

<div x-data="settings" class="w-full px-4 md:px-8 pb-20 pt-8">

    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-6">
        <div class="text-center md:text-left">
            <h1 class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400 drop-shadow-sm">
                Configuración del Sistema
            </h1>
            <p class="text-gray-400 font-light mt-1">Personaliza la información de tu negocio y monedas.</p>
        </div>
    </div>

    <!-- MAIN FORM -->
    <div class="glass p-8 rounded-2xl border border-gray-200 dark:border-white/5 shadow-xl relative overflow-hidden mb-12 max-w-4xl mx-auto">
        <div class="absolute top-0 right-0 p-4 opacity-5 pointer-events-none">
            <i class="fa-solid fa-cogs text-9xl"></i>
        </div>

        <!-- History Link -->
        <div class="mb-8 flex justify-end">
            <a href="movements.php" class="bg-gray-100 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-600 dark:text-gray-300 px-4 py-2 rounded-xl flex items-center gap-2 font-bold text-sm hover:bg-gray-200 dark:hover:bg-white/10 transition-colors">
                <i class="fa-solid fa-clock-rotate-left"></i> Ver Historial de Movimientos
            </a>
        </div>

        <div class="space-y-8">
            
            <!-- Section: Company Info -->
            <div class="border-b border-gray-200 dark:border-white/10 pb-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-building text-brand-400"></i> Información del Negocio
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Nombre de la Empresa</label>
                        <input x-model="config.company_name" type="text" class="w-full bg-white dark:bg-dark-bg/50 border border-gray-300 dark:border-gray-700 rounded-xl py-2.5 px-4 text-gray-900 dark:text-white focus:border-brand-500 focus:outline-none placeholder-gray-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">RIF / NIT / Identificador</label>
                        <input x-model="config.nit_ruc_nif" type="text" class="w-full bg-white dark:bg-dark-bg/50 border border-gray-300 dark:border-gray-700 rounded-xl py-2.5 px-4 text-gray-900 dark:text-white focus:border-brand-500 focus:outline-none placeholder-gray-400">
                    </div>
                     <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Dirección</label>
                        <textarea x-model="config.address" rows="2" class="w-full bg-white dark:bg-dark-bg/50 border border-gray-300 dark:border-gray-700 rounded-xl py-2.5 px-4 text-gray-900 dark:text-white focus:border-brand-500 focus:outline-none placeholder-gray-400"></textarea>
                    </div>
                     <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Teléfono</label>
                        <input x-model="config.phone" type="text" class="w-full bg-white dark:bg-dark-bg/50 border border-gray-300 dark:border-gray-700 rounded-xl py-2.5 px-4 text-gray-900 dark:text-white focus:border-brand-500 focus:outline-none placeholder-gray-400">
                    </div>
                </div>
            </div>

            <!-- Section: Currency -->
            <div class="border-b border-gray-200 dark:border-white/10 pb-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-coins text-yellow-400"></i> Moneda y Tasas
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                         <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Moneda Principal</label>
                         <div class="flex items-center gap-4 bg-gray-50 dark:bg-black/20 p-2 rounded-xl border border-gray-200 dark:border-gray-700">
                             <button @click="config.main_currency = 'VES'" 
                                class="flex-1 py-2 rounded-lg font-bold text-sm transition-all"
                                :class="config.main_currency === 'VES' ? 'bg-indigo-600 text-white shadow-lg' : 'text-gray-500 hover:text-gray-700'">
                                Bolívares (Bs)
                             </button>
                             <button @click="config.main_currency = 'USD'" 
                                class="flex-1 py-2 rounded-lg font-bold text-sm transition-all"
                                :class="config.main_currency === 'USD' ? 'bg-emerald-600 text-white shadow-lg' : 'text-gray-500 hover:text-gray-700'">
                                Dólares ($)
                             </button>
                         </div>
                    </div>

                    <!-- SHOW ONLY IF USD IS SELECTED -->
                    <div x-show="config.main_currency === 'USD'" class="grid grid-cols-1 gap-4 animate-fade-in-up">
                        <div class="bg-blue-900/20 border border-blue-500/30 p-4 rounded-xl">
                            <label class="block text-xs font-bold text-blue-400 uppercase tracking-widest mb-2">Tasa de Cambio Global (Bs/$) - BCV</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-blue-400 font-bold">Bs</span>
                                <input x-model="config.exchange_rate_global" type="number" step="0.01" class="w-full bg-transparent border-b border-blue-500 text-white text-xl font-mono focus:outline-none pl-10 py-1">
                            </div>
                            <p class="text-[10px] text-blue-300 mt-2">Esta tasa se usará para calcular el precio en Bolívares de todos los productos.</p>
                        </div>
                        
                        <div class="bg-purple-900/20 border border-purple-500/30 p-4 rounded-xl">
                            <label class="block text-xs font-bold text-purple-400 uppercase tracking-widest mb-2">Tasa Especial (Bs/$)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-purple-400 font-bold">Bs</span>
                                <input x-model="config.exchange_rate_special" type="number" step="0.01" class="w-full bg-transparent border-b border-purple-500 text-white text-xl font-mono focus:outline-none pl-10 py-1">
                            </div>
                             <p class="text-[10px] text-purple-300 mt-2">Uso exclusivo para productos marcados con "Tasa Especial".</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section: Security -->
            <div class="border-b border-gray-200 dark:border-white/10 pb-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-user-shield text-red-500"></i> Seguridad
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-red-900/10 border border-red-500/20 p-4 rounded-xl">
                        <label class="block text-xs font-bold text-red-500 uppercase tracking-widest mb-2">PIN Administrativo (Acceso)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-3 text-red-400"><i class="fa-solid fa-key"></i></span>
                            <input x-model="config.admin_pin" type="text" class="w-full bg-white dark:bg-dark-bg/50 border border-red-200 dark:border-red-500/30 rounded-xl py-2.5 pl-10 pr-4 text-gray-900 dark:text-white focus:border-red-500 focus:outline-none placeholder-gray-400 tracking-widest font-mono" placeholder="1234">
                        </div>
                        <p class="text-[10px] text-red-400 mt-2">Este PIN se solicita para desbloquear el menú de administración. Por defecto es 1234.</p>
                    </div>

                    <div class="bg-orange-900/10 border border-orange-500/20 p-4 rounded-xl">
                        <label class="block text-xs font-bold text-orange-500 uppercase tracking-widest mb-2">PIN Gerencial (Insumos)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-3 text-orange-400"><i class="fa-solid fa-user-lock"></i></span>
                            <input x-model="config.manager_pin" type="text" class="w-full bg-white dark:bg-dark-bg/50 border border-orange-200 dark:border-orange-500/30 rounded-xl py-2.5 pl-10 pr-4 text-gray-900 dark:text-white focus:border-orange-500 focus:outline-none placeholder-gray-400 tracking-widest font-mono" placeholder="4321">
                        </div>
                        <p class="text-[10px] text-orange-400 mt-2">PIN exclusivo para autorizar bajas de inventario por insumos.</p>
                    </div>
                </div>
            </div>

            <!-- Section: Backups -->
            <div class="border-b border-gray-200 dark:border-white/10 pb-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-server text-cyan-500"></i> Copias de Seguridad
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                    <!-- Config -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Ruta de Respaldo</label>
                            <input x-model="config.backup_path" type="text" placeholder="backups/" class="w-full bg-white dark:bg-dark-bg/50 border border-gray-300 dark:border-gray-700 rounded-xl py-2.5 px-4 text-gray-900 dark:text-white focus:border-cyan-500 focus:outline-none font-mono text-sm">
                            <p class="text-[10px] text-gray-400 mt-1">Directorio donde se guardarán los archivos .sql y .zip</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Frecuencia Automática (Horas)</label>
                            <input x-model="config.backup_frequency" type="number" min="1" class="w-full bg-white dark:bg-dark-bg/50 border border-gray-300 dark:border-gray-700 rounded-xl py-2.5 px-4 text-gray-900 dark:text-white focus:border-cyan-500 focus:outline-none">
                        </div>
                    </div>

                    <!-- Actions & Status -->
                    <div class="bg-cyan-900/10 border border-cyan-500/20 p-5 rounded-xl space-y-4">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500 dark:text-gray-400 font-medium">Último Respaldo BD:</span>
                            <span class="font-mono font-bold text-gray-900 dark:text-white" x-text="formatDate(config.last_db_backup) || 'Nunca'"></span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500 dark:text-gray-400 font-medium">Último Respaldo Completo:</span>
                            <span class="font-mono font-bold text-gray-900 dark:text-white" x-text="formatDate(config.last_full_backup) || 'Nunca'"></span>
                        </div>
                        
                        <hr class="border-cyan-500/20">

                        <button @click="manualBackup()" :disabled="backupProcessing" class="w-full bg-cyan-600 hover:bg-cyan-500 text-white font-bold py-3 rounded-lg shadow-lg shadow-cyan-500/20 transition-all flex items-center justify-center gap-2">
                             <span x-show="!backupProcessing">
                                <i class="fa-solid fa-floppy-disk"></i> Realizar Respaldo Ahora
                             </span>
                             <span x-show="backupProcessing" class="flex items-center gap-2">
                                <i class="fa-solid fa-circle-notch fa-spin"></i> Procesando...
                             </span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Section: History & Restore -->
            <div class="border-b border-gray-200 dark:border-white/10 pb-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-yellow-500"></i> Historial y Restauración
                </h2>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- History List -->
                    <div class="lg:col-span-2">
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4">Archivos Disponibles</h3>
                        <div class="bg-white dark:bg-black/20 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden max-h-96 overflow-y-auto custom-scrollbar">
                            <table class="w-full text-left">
                                <thead class="sticky top-0 bg-gray-50 dark:bg-white/5 text-xs font-bold text-gray-500 uppercase z-10 shadow-sm backdrop-blur-md">
                                    <tr>
                                        <th class="px-4 py-3">Archivo</th>
                                        <th class="px-4 py-3">Fecha</th>
                                        <th class="px-4 py-3 text-right">Tamaño</th>
                                        <th class="px-4 py-3 text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <template x-for="file in backups" :key="file.name">
                                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group">
                                            <td class="px-4 py-3">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white font-bold text-xs shadow-sm"
                                                         :class="file.type === 'db' ? 'bg-indigo-500' : 'bg-emerald-500'">
                                                        <i class="fa-solid" :class="file.type === 'db' ? 'fa-database' : 'fa-images'"></i>
                                                    </div>
                                                    <span class="text-xs font-mono font-medium text-gray-700 dark:text-gray-300" x-text="file.name"></span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-xs text-gray-500" x-text="file.date_formatted"></td>
                                            <td class="px-4 py-3 text-xs font-mono text-gray-500 text-right" x-text="file.size_formatted"></td>
                                            <td class="px-4 py-3 text-right">
                                                <div class="flex justify-end gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                                    <!-- Download -->
                                                    <a :href="'api/backup.php?action=download&filename=' + file.name" class="w-7 h-7 rounded bg-gray-100 dark:bg-white/10 hover:bg-blue-500 hover:text-white text-gray-500 flex items-center justify-center transition-colors" title="Descargar">
                                                        <i class="fa-solid fa-download text-xs"></i>
                                                    </a>
                                                    <!-- Restore -->
                                                    <button @click="restoreBackup(file.name)" class="w-7 h-7 rounded bg-gray-100 dark:bg-white/10 hover:bg-yellow-500 hover:text-white text-gray-500 flex items-center justify-center transition-colors" title="Restaurar al Sistema">
                                                        <i class="fa-solid fa-rotate-left text-xs"></i>
                                                    </button>
                                                    <!-- Delete -->
                                                    <button @click="deleteBackup(file.name)" class="w-7 h-7 rounded bg-gray-100 dark:bg-white/10 hover:bg-red-500 hover:text-white text-gray-500 flex items-center justify-center transition-colors" title="Eliminar">
                                                        <i class="fa-solid fa-trash text-xs"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr x-show="backups.length === 0">
                                        <td colspan="4" class="px-4 py-8 text-center text-gray-400 text-xs">
                                            <i class="fa-solid fa-folder-open text-2xl mb-2 opacity-30"></i>
                                            <p>No hay respaldos disponibles</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Upload Restore -->
                    <div>
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4">Importar Localmente</h3>
                        <div class="bg-yellow-900/10 border border-yellow-500/20 p-6 rounded-xl text-center space-y-4 hover:border-yellow-500/40 transition-colors">
                            <div class="w-16 h-16 rounded-full bg-yellow-500/20 text-yellow-500 flex items-center justify-center mx-auto mb-2 animate-pulse-slow">
                                <i class="fa-solid fa-cloud-arrow-up text-2xl"></i>
                            </div>
                            <h4 class="text-yellow-600 dark:text-yellow-400 font-bold text-sm">Subir Archivo de Respaldo</h4>
                            <p class="text-[10px] text-yellow-600/70 dark:text-yellow-400/70 leading-relaxed px-4">
                                Selecciona un archivo <strong>.sql</strong> o <strong>.zip</strong>.
                                <br><span class="font-bold text-red-500 mt-1 block">⚠️ Sobrescribirá los datos actuales.</span>
                            </p>
                            
                            <label class="block w-full cursor-pointer group">
                                <input type="file" class="hidden" @change="uploadRestore($event)" accept=".sql,.zip">
                                <div class="w-full py-3 rounded-lg border-2 border-dashed border-yellow-500/30 group-hover:border-yellow-500 text-yellow-600 dark:text-yellow-400 font-bold text-xs transition-colors flex items-center justify-center gap-2 bg-yellow-500/5 group-hover:bg-yellow-500/10">
                                    <span x-show="!restoreUploading">Seleccionar Archivo</span>
                                    <span x-show="restoreUploading"><i class="fa-solid fa-circle-notch fa-spin"></i> Subiendo...</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <button @click="saveSettings()" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold py-4 rounded-xl shadow-lg transition-transform active:scale-95 flex items-center justify-center gap-2 text-lg">
                <i class="fa-solid fa-save"></i> Guardar Configuración
            </button>
        </div>
    </div>
</div>

<script src="assets/js/app.js?v=<?php echo time(); ?>"></script>
<?php require_once 'includes/footer.php'; ?>
