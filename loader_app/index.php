<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cargador Móvil - Jabones</title>
    <script src="assets/js/tailwindcss.js"></script>
    <script src="assets/js/sweetalert2.js"></script>
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <style>
        body { font-family: system-ui, sans-serif; }
    </style>
</head>
<body class="bg-gray-900 text-white min-h-screen flex flex-col items-center justify-center p-4">
    
    <div class="max-w-md w-full bg-gray-800 rounded-2xl shadow-2xl p-8 border border-gray-700 text-center">
        <div class="mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-600 mb-4 animate-pulse">
                <i class="fas fa-box-open text-3xl text-white"></i>
            </div>
            <h1 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500">
                Loader Móvil
            </h1>
            <p class="text-gray-400 mt-2">Sistema Rápido de Carga</p>
        </div>

        <div class="grid gap-4">
            <a href="add.php" class="group relative px-6 py-4 bg-gray-700 hover:bg-blue-600 rounded-xl transition-all duration-300 flex items-center justify-between border border-gray-600 hover:border-blue-400">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-lg bg-blue-500/20 flex items-center justify-center text-blue-400 group-hover:text-white group-hover:bg-white/20 mr-4">
                        <i class="fas fa-plus"></i>
                    </div>
                    <div class="text-left">
                        <h3 class="font-bold text-lg">Cargar Producto</h3>
                        <p class="text-xs text-gray-400 group-hover:text-blue-200">Foto + Datos</p>
                    </div>
                </div>
                <i class="fas fa-chevron-right text-gray-500 group-hover:text-white"></i>
            </a>

            <a href="list.php" class="group relative px-6 py-4 bg-gray-700 hover:bg-purple-600 rounded-xl transition-all duration-300 flex items-center justify-between border border-gray-600 hover:border-purple-400">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-lg bg-purple-500/20 flex items-center justify-center text-purple-400 group-hover:text-white group-hover:bg-white/20 mr-4">
                        <i class="fas fa-list"></i>
                    </div>
                    <div class="text-left">
                        <h3 class="font-bold text-lg">Ver Lista</h3>
                        <p class="text-xs text-gray-400 group-hover:text-purple-200">Exportar Datos</p>
                    </div>
                </div>
                <i class="fas fa-chevron-right text-gray-500 group-hover:text-white"></i>
            </a>
        </div>
        
        <div class="mt-8 text-xs text-gray-500">
            v1.0 Offline Capable
        </div>
    </div>

</body>
</html>
