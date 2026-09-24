<?php
session_start();

$error = '';
$redirect = $_GET['redirect'] ?? 'insumos.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'config/database.php';

    $pin = $_POST['pin'] ?? '';
    
    try {
        $stmt = $pdo->query("SELECT manager_pin FROM system_settings LIMIT 1");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // Default PIN specifically for Manager if not set
        $stored_pin = $row['manager_pin'] ?? '4321';

        if ($pin === $stored_pin) {
            $_SESSION['insumos_unlocked'] = true;
            header("Location: " . urldecode($redirect));
            exit;
        } else {
            $error = 'PIN Gerencial Incorrecto';
        }
    } catch(Exception $e) {
        $error = 'Error de conexión';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Materia Prima - Acceso Restringido</title>
    <!-- Tailwind CSS -->
    <script src="assets/js/tailwindcss.js"></script>
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <!-- Alpine.js -->
    <script defer src="assets/js/alpine.js"></script>
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="bg-slate-900 h-screen flex items-center justify-center font-sans relative overflow-hidden">
    
    <!-- Background Accents -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
        <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-orange-600/20 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-blue-600/20 rounded-full blur-[100px]"></div>
    </div>

    <div class="glass p-8 rounded-2xl w-full max-w-sm text-center shadow-2xl mx-4 relative z-10 border-orange-500/20">
        <div class="w-24 h-24 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner relative ring-1 ring-orange-500/30">
            <i class="fa-solid fa-boxes-packing text-4xl text-orange-500"></i>
            <div class="absolute -top-1 -right-1 w-6 h-6 bg-orange-500 rounded-full animate-pulse border-2 border-slate-900 flex items-center justify-center">
                <i class="fa-solid fa-lock text-[10px] text-white"></i>
            </div>
        </div>

        <h1 class="text-2xl font-bold text-white mb-2">Control de Materia Prima</h1>
        <p class="text-gray-400 text-sm mb-6">Área restringida. Ingrese PIN Gerencial.</p>

        <?php if($error): ?>
            <div class="bg-red-500/10 border border-red-500/20 text-red-400 text-sm py-2 px-4 rounded-lg mb-4 animate-bounce">
                <i class="fa-solid fa-circle-exclamation mr-1"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4" x-data="{ pin: '' }">
            <div class="relative">
                <input type="password" name="pin" maxlength="6" autofocus required
                    class="w-full bg-black/30 border border-gray-700 rounded-xl py-3 px-4 text-center text-white text-2xl tracking-[0.5em] focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-all font-mono placeholder-gray-600"
                    placeholder="••••">
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-500 hover:to-red-500 text-white font-bold py-3.5 rounded-xl transition-all shadow-lg shadow-orange-500/20 flex items-center justify-center gap-2">
                <i class="fa-solid fa-key"></i> Autorizar Acceso
            </button>
        </form>

        <div class="mt-6 border-t border-white/5 pt-4">
            <a href="index.php" class="text-gray-500 hover:text-white text-sm transition-colors flex items-center justify-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Cancelar
            </a>
        </div>
    </div>

</body>
</html>
