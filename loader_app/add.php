<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cargar Producto - Loader</title>
    <script src="assets/js/tailwindcss.js"></script>
    <script src="assets/js/sweetalert2.js"></script>
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <style>
        body { font-family: system-ui, sans-serif; }
        input[type="file"] { display: none; }
        .form-input {
            width: 100%;
            background-color: #1f2937;
            border: 1px solid #374151;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            color: white;
            transition: all 0.2s;
        }
        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        }
        .form-label {
            display: block;
            color: #9ca3af;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }
    </style>
</head>
<body class="bg-gray-900 text-white min-h-screen pb-24">

    <!-- Header -->
    <div class="fixed top-0 left-0 right-0 bg-gray-800/95 backdrop-blur border-b border-gray-700 p-4 z-50 flex items-center justify-between shadow-lg">
        <a href="index.php" class="text-gray-400 hover:text-white transition-colors p-2">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h1 class="text-lg font-bold">Nuevo Producto</h1>
        <div class="w-8"></div>
    </div>

    <!-- Camera Modal -->
    <div id="cameraModal" class="hidden fixed inset-0 z-[60] bg-black bg-opacity-90 flex flex-col items-center justify-center p-4">
        <div class="relative w-full max-w-lg bg-gray-900 rounded-2xl overflow-hidden shadow-2xl border border-gray-700">
            <video id="videoElement" autoplay playsinline class="w-full h-auto bg-black"></video>
            <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-4">
                <button type="button" onclick="closeCamera()" class="p-3 rounded-full bg-red-600 text-white hover:bg-red-500 shadow-lg">
                    <i class="fas fa-times"></i>
                </button>
                <button type="button" onclick="takeSnapshot()" class="p-3 px-6 rounded-full bg-white text-black hover:bg-gray-200 font-bold shadow-lg">
                    <i class="fas fa-camera"></i> CAPTURAR
                </button>
            </div>
        </div>
        <p class="text-gray-400 mt-4 text-sm">Asegúrate de permitir el acceso a la cámara</p>
    </div>

    <form id="productForm" class="p-4 mt-20 max-w-lg mx-auto space-y-6">
        
        <!-- Image Section -->
        <div class="flex flex-col items-center gap-4">
             <!-- Preview Area -->
            <div class="w-full aspect-[4/3] bg-gray-800 rounded-2xl border-2 border-dashed border-gray-600 overflow-hidden relative group">
                <img id="imagePreview" class="hidden w-full h-full object-cover absolute inset-0 z-10">
                
                <!-- Controls Overlay (Always Visible if No Image, Hover if Image) -->
                <div id="uploadControls" class="absolute inset-0 z-20 flex flex-col items-center justify-center gap-3 transition-opacity duration-200">
                    <div class="text-gray-400 mb-2 font-medium">Selecciona una opción:</div>
                    
                    <div class="flex gap-4">
                        <!-- Open Camera (PC/Webcam) -->
                        <button type="button" onclick="startCamera()" class="flex flex-col items-center justify-center w-24 h-24 bg-gray-700 hover:bg-blue-600 hover:text-white rounded-xl transition-all shadow-md group-hover:scale-105">
                            <i class="fas fa-video text-2xl mb-2"></i>
                            <span class="text-xs">Webcam</span>
                        </button>

                        <!-- Upload File (Mobile Camera/Disk) -->
                        <label for="imageInput" class="flex flex-col items-center justify-center w-24 h-24 bg-gray-700 hover:bg-purple-600 hover:text-white rounded-xl transition-all shadow-md cursor-pointer group-hover:scale-105">
                            <i class="fas fa-folder-open text-2xl mb-2"></i>
                            <span class="text-xs">Archivo</span>
                        </label>
                    </div>
                </div>
            </div>
            
            <input type="file" id="imageInput" name="image" accept="image/*" class="hidden">
        </div>

        <!-- Main Info -->
        <div class="space-y-4 bg-gray-800/50 p-4 rounded-2xl border border-gray-700/50">
            <div>
                <label class="form-label">Nombre del Producto</label>
                <input type="text" name="name" required class="form-input text-lg font-medium" placeholder="Ej. Jabón Líquido">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Marca</label>
                    <input type="text" name="brand" class="form-input" placeholder="Ej. Ariel">
                </div>
                 <div>
                    <label class="form-label">Categoría</label>
                    <div class="relative">
                        <select name="category_id" required class="form-input appearance-none">
                            <option value="" disabled selected>Seleccionar</option>
                            <option value="1">Detergentes</option>
                            <option value="2">Suavizantes</option>
                            <option value="3">Limpiadores</option>
                            <option value="4">Automotriz</option>
                            <option value="5">Accesorios</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-3 top-3.5 text-gray-500 pointer-events-none"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pricing & Stock -->
        <div class="space-y-4 bg-gray-800/50 p-4 rounded-2xl border border-gray-700/50">
            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Inventario y Precios</h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label text-blue-400">Precio Venta ($)</label>
                    <input type="number" name="price" step="0.01" required class="form-input font-bold" placeholder="0.00">
                </div>
                <div>
                    <label class="form-label">Costo ($)</label>
                    <input type="number" name="cost_price" step="0.01" class="form-input" placeholder="0.00">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Stock Inicial</label>
                    <input type="number" name="stock_quantity" step="0.01" required class="form-input" placeholder="0">
                </div>
                <div>
                    <label class="form-label">Stock Mínimo</label>
                    <input type="number" name="min_stock" step="0.01" value="10" class="form-input" placeholder="10">
                </div>
            </div>
        </div>

        <!-- Details -->
        <div class="space-y-4 bg-gray-800/50 p-4 rounded-2xl border border-gray-700/50">
            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Detalles</h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Unidad</label>
                    <select name="display_unit" class="form-input">
                        <option value="Litro">Litro (L)</option>
                        <option value="Unidad">Unidad (U)</option>
                        <option value="Kg">Kilogramo (Kg)</option>
                        <option value="Galon">Galón</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">¿Es Líquido?</label>
                    <div class="flex bg-gray-900 rounded-xl p-1 border border-gray-700">
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="is_liquid" value="1" checked class="peer sr-only">
                            <span class="block text-center py-2 rounded-lg text-sm transition-all peer-checked:bg-blue-600 peer-checked:text-white text-gray-400 hover:bg-gray-800">Sí</span>
                        </label>
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="is_liquid" value="0" class="peer sr-only">
                            <span class="block text-center py-2 rounded-lg text-sm transition-all peer-checked:bg-blue-600 peer-checked:text-white text-gray-400 hover:bg-gray-800">No</span>
                        </label>
                    </div>
                </div>
            </div>
            
            <div>
                <label class="form-label">SKU / Código</label>
                <div class="relative">
                    <input type="text" name="sku" class="form-input pl-10" placeholder="Escanear...">
                    <i class="fas fa-barcode absolute left-3 top-3.5 text-gray-500"></i>
                </div>
            </div>
        </div>

    </form>

    <!-- Floating Submit Button -->
    <div class="fixed bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-gray-900 via-gray-900 to-transparent">
        <button type="submit" form="productForm" id="btnSubmit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-600/20 active:scale-95 transition-all flex items-center justify-center gap-2 text-lg">
            <i class="fas fa-save"></i>
            <span>Guardar Producto</span>
        </button>
    </div>

    <script>
        const imageInput = document.getElementById('imageInput');
        const imagePreview = document.getElementById('imagePreview');
        const uploadControls = document.getElementById('uploadControls');
        const cameraModal = document.getElementById('cameraModal');
        const video = document.getElementById('videoElement');
        let currentStream = null;
        let capturedBlob = null; // Store blob from webcam

        // 1. File Upload Logic
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Clear webcam blob if file selected
                capturedBlob = null;
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                    uploadControls.classList.add('opacity-0', 'hover:opacity-100'); // Hide controls but show on hover
                }
                reader.readAsDataURL(file);
            }
        });

        // 2. Webcam Logic
        async function startCamera() {
            try {
                cameraModal.classList.remove('hidden');
                currentStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
                video.srcObject = currentStream;
            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Cámara',
                    text: 'No se pudo acceder a la cámara. Verifica permisos o usa un dispositivo seguro (HTTPS/Localhost).',
                    background: '#1f2937', color: '#fff'
                });
                closeCamera();
            }
        }

        function closeCamera() {
            cameraModal.classList.add('hidden');
            if (currentStream) {
                currentStream.getTracks().forEach(track => track.stop());
                currentStream = null;
            }
        }

        function takeSnapshot() {
            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);
            
            canvas.toBlob(blob => {
                capturedBlob = blob; // Save blob
                imageInput.value = ''; // Clear file input
                
                // Show preview
                imagePreview.src = URL.createObjectURL(blob);
                imagePreview.classList.remove('hidden');
                uploadControls.classList.add('opacity-0', 'hover:opacity-100');
                
                closeCamera();
            }, 'image/jpeg', 0.8);
        }

        // 3. Form Submit Logic
        document.getElementById('productForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSubmit');
            const originalContent = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Guardando...';

            const formData = new FormData(this);

            // Append Webcam Blob if exists (and no file selected)
            if (capturedBlob && imageInput.files.length === 0) {
                formData.append('image', capturedBlob, 'webcam_' + Date.now() + '.jpg');
            }

            try {
                const response = await fetch('save_product.php', { method: 'POST', body: formData });
                const text = await response.text();
                let result;
                try {
                    result = JSON.parse(text);
                } catch (err) {
                    throw new Error("Respuesta inválida: " + text.substring(0, 50));
                }

                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Guardado!',
                        background: '#1f2937', color: '#fff',
                        timer: 1500, showConfirmButton: false
                    }).then(() => {
                        this.reset();
                        imagePreview.classList.add('hidden');
                        uploadControls.classList.remove('opacity-0', 'hover:opacity-100');
                        capturedBlob = null;
                        btn.disabled = false;
                        btn.innerHTML = originalContent;
                    });
                } else {
                    throw new Error(result.message);
                }
            } catch (error) {
                console.error(error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message,
                    background: '#1f2937', color: '#fff'
                });
                btn.disabled = false;
                btn.innerHTML = originalContent;
            }
        });
    </script>
</body>
</html>
