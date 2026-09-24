<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Carga - Loader</title>
    <script src="assets/js/tailwindcss.js"></script>
    <script src="assets/js/sweetalert2.js"></script>
    <link rel="stylesheet" href="assets/css/fontawesome.css">
</head>
<body class="bg-gray-900 text-white min-h-screen pb-20">

    <!-- Header -->
    <div class="fixed top-0 left-0 right-0 bg-gray-800/90 backdrop-blur-md border-b border-gray-700 p-4 z-50 flex items-center justify-between">
        <a href="index.php" class="text-gray-400 hover:text-white transition-colors">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h1 class="text-lg font-bold">Productos Cargados</h1>
        <button onclick="exportData()" class="text-blue-400 hover:text-blue-300 font-bold text-sm flex items-center gap-2">
            <i class="fas fa-download"></i> Exportar
        </button>
    </div>

    <!-- List -->
    <div class="p-4 mt-16 max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php
        require_once 'config/database.php';
        $stmt = $pdo->query("SELECT * FROM loader_products ORDER BY id DESC");
        $products = $stmt->fetchAll();

        if (count($products) > 0) {
            foreach($products as $prod) {
                $img = $prod['image_path'] ? $prod['image_path'] : 'https://via.placeholder.com/150?text=No+Image';
                ?>
                <div class="bg-gray-800 rounded-xl overflow-hidden shadow-lg border border-gray-700 flex">
                    <div class="w-24 h-24 bg-gray-700 flex-shrink-0">
                        <img src="<?php echo htmlspecialchars($img); ?>" class="w-full h-full object-cover">
                    </div>
                    <div class="p-4 flex-1 flex flex-col justify-center">
                        <h3 class="font-bold text-lg leading-tight mb-1"><?php echo htmlspecialchars($prod['name']); ?></h3>
                        <div class="text-sm text-gray-400 flex justify-between items-center">
                            <span>$<?php echo number_format($prod['price'], 2); ?></span>
                            <span class="bg-gray-700 px-2 py-0.5 rounded text-xs">Stock: <?php echo $prod['stock_quantity']; ?></span>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo '<div class="col-span-full text-center py-20 text-gray-500"><i class="fas fa-box-open text-4xl mb-4"></i><p>No hay productos cargados aún.</p></div>';
        }
        ?>
    </div>

    <!-- Floating Add Button -->
    <a href="add.php" class="fixed bottom-6 right-6 w-14 h-14 bg-blue-600 hover:bg-blue-500 rounded-full shadow-lg shadow-blue-600/30 flex items-center justify-center text-white text-xl transition-transform hover:scale-110 active:scale-95">
        <i class="fas fa-plus"></i>
    </a>

    <script>
        function exportData() {
            Swal.fire({
                title: '¿Exportar Datos?',
                text: "Se descargará un archivo ZIP con las imágenes y datos para importar en el sistema principal.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, descargar',
                background: '#1f2937',
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'export.php';
                }
            })
        }
    </script>
</body>
</html>
