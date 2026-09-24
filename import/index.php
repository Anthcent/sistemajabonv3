<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Importar Productos - Sistema Principal</title>
    <!-- Use main system assets -->
    <script src="../assets/js/tailwindcss.js"></script>
    <script src="../assets/js/sweetalert2.js"></script>
    <link rel="stylesheet" href="../assets/css/fontawesome.css">
    <style>
        body { font-family: system-ui, sans-serif; }
        .glass {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center p-4">

    <div class="max-w-xl w-full">
        <!-- Breadcrumb / Back -->
        <a href="../index.php" class="inline-flex items-center text-gray-400 hover:text-white mb-6 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Volver al Dashboard
        </a>

        <div class="glass rounded-2xl p-8 shadow-2xl relative overflow-hidden">
            <!-- Decorative Glow -->
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 bg-blue-500 rounded-full blur-3xl opacity-20"></div>
            <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-32 h-32 bg-purple-500 rounded-full blur-3xl opacity-20"></div>

            <div class="relative z-10 text-center">
                <div class="w-20 h-20 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-6 border border-gray-700 shadow-lg">
                    <i class="fas fa-file-import text-3xl text-blue-400"></i>
                </div>
                
                <h1 class="text-3xl font-bold mb-2">Importar Productos</h1>
                <p class="text-gray-400 mb-8">Sube el archivo ZIP generado por la App Móvil</p>

                <form id="importForm" class="space-y-6">
                    
                    <div class="relative">
                        <label for="zipFile" class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-gray-600 rounded-xl hover:border-blue-500 hover:bg-gray-800/50 transition-all cursor-pointer group">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <i class="fas fa-cloud-upload-alt text-4xl text-gray-500 group-hover:text-blue-400 mb-3 transition-colors"></i>
                                <p class="mb-2 text-sm text-gray-400"><span class="font-semibold text-white">Haz clic</span> o arrastra el archivo aquí</p>
                                <p class="text-xs text-gray-500">Formato: .ZIP (Backup Loader)</p>
                            </div>
                            <input id="zipFile" name="zipFile" type="file" class="hidden" accept=".zip" required />
                        </label>
                        
                        <!-- File Name Display -->
                        <div id="fileName" class="hidden mt-2 p-3 bg-blue-900/30 border border-blue-500/30 rounded-lg text-blue-200 text-sm flex items-center justify-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span class="truncate max-w-xs">archivo.zip</span>
                        </div>
                    </div>

                    <button type="submit" id="btnImport" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-500/20 transform hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                        <span>Iniciar Importación</span>
                        <i class="fas fa-bolt"></i>
                    </button>
                    
                </form>

                <div class="mt-8 pt-6 border-t border-gray-800 flex justify-center gap-8 text-sm text-gray-500">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-database text-green-500"></i>
                        <span>Actualiza DB</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-images text-purple-500"></i>
                        <span>Mueve Imágenes</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const fileInput = document.getElementById('zipFile');
        const fileNameDisplay = document.getElementById('fileName');
        const fileNameSpan = fileNameDisplay.querySelector('span');

        fileInput.addEventListener('change', function(e) {
            if (this.files && this.files.length > 0) {
                fileNameSpan.textContent = this.files[0].name;
                fileNameDisplay.classList.remove('hidden');
            }
        });

        document.getElementById('importForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const btn = document.getElementById('btnImport');
            const originalContent = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Procesando...';

            const formData = new FormData(this);

            try {
                const response = await fetch('process.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();

                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Importación Terminada!',
                        html: `
                            <div class="text-left">
                                <p class="mb-2">✅ <b>Importados:</b> ${result.count}</p>
                                <p class="text-orange-400">⚠️ <b>Omitidos (Duplicados):</b> ${result.skipped}</p>
                            </div>
                        `,
                        background: '#1f2937', color: '#fff',
                        confirmButtonText: 'Ver Productos',
                        confirmButtonColor: '#3b82f6'
                    }).then((r) => {
                         if(r.isConfirmed) window.location.href = '../index.php'; 
                    });
                } else {
                    throw new Error(result.message);
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message,
                    background: '#1f2937', color: '#fff'
                });
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalContent;
            }
        });
    </script>
</body>
</html>
