<?php
/**
 * CLI Script para Reinicio y Gestión de PINs de Seguridad
 * Sistema de Ventas de Jabones V2
 */

// Asegurar ejecución desde línea de comandos o web con autorización
$is_cli = (php_sapi_name() === 'cli');

require_once __DIR__ . '/config/database.php';

$pin_file = __DIR__ . '/PIN_ACTUAL.txt';

// Función para limpiar pantalla si es CLI
function clear_cli() {
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        system('cls');
    } else {
        system('clear');
    }
}

// 1. Eliminar archivo de PIN anterior si existe
if (file_exists($pin_file)) {
    @unlink($pin_file);
    $deleted_previous = true;
} else {
    $deleted_previous = false;
}

// También limpiar posibles nombres alternativos antiguos
foreach (['PIN_SEGURIDAD.txt', 'PIN_ANTERIOR.txt', 'CLAVES_PIN.txt'] as $alt) {
    if (file_exists(__DIR__ . '/' . $alt)) {
        @unlink(__DIR__ . '/' . $alt);
    }
}

$mode = $argv[1] ?? null;
$custom_admin = $argv[2] ?? null;
$custom_manager = $argv[3] ?? null;

if (!$mode && $is_cli) {
    echo "======================================================================\n";
    echo "       REINICIO Y GESTION DE PINES DE SEGURIDAD - SISTEMA JABON\n";
    echo "======================================================================\n\n";

    if ($deleted_previous) {
        echo " [i] Se ha eliminado el archivo de PIN anterior (PIN_ACTUAL.txt).\n\n";
    }

    echo " Selecciona una opción:\n\n";
    echo "   [1] Generar NUEVOS PINs aleatorios automáticos (Recomendado)\n";
    echo "   [2] Ingresar nuevos PINs manualmente\n";
    echo "   [3] Restaurar PINs por defecto (Admin: 1234 | Insumos: 4321)\n";
    echo "   [4] Cancelar y Salir\n\n";
    echo " Ingresa tu opción (1-4): ";

    $handle = fopen("php://stdin", "r");
    $choice = trim(fgets($handle));

    if ($choice === '1') {
        $mode = 'random';
    } elseif ($choice === '2') {
        $mode = 'manual';
        echo "\n Ingrese el nuevo PIN Administrativo (ej. 4 dígitos): ";
        $custom_admin = trim(fgets($handle));
        echo " Ingrese el nuevo PIN Gerencial/Insumos (ej. 4 dígitos): ";
        $custom_manager = trim(fgets($handle));
    } elseif ($choice === '3') {
        $mode = 'default';
    } else {
        echo "\n Operación cancelada.\n";
        exit(0);
    }
}

// Procesar según modo
$new_admin_pin = '1234';
$new_manager_pin = '4321';

if ($mode === 'random' || !$mode) {
    // Generar PINs aleatorios de 4 dígitos distintos
    $new_admin_pin = sprintf("%04d", random_int(1000, 9999));
    do {
        $new_manager_pin = sprintf("%04d", random_int(1000, 9999));
    } while ($new_manager_pin === $new_admin_pin);
} elseif ($mode === 'manual') {
    $new_admin_pin = !empty($custom_admin) ? preg_replace('/\D/', '', $custom_admin) : sprintf("%04d", random_int(1000, 9999));
    $new_manager_pin = !empty($custom_manager) ? preg_replace('/\D/', '', $custom_manager) : sprintf("%04d", random_int(1000, 9999));
    if (strlen($new_admin_pin) < 4) $new_admin_pin = str_pad($new_admin_pin, 4, '0', STR_PAD_LEFT);
    if (strlen($new_manager_pin) < 4) $new_manager_pin = str_pad($new_manager_pin, 4, '0', STR_PAD_LEFT);
} elseif ($mode === 'default') {
    $new_admin_pin = '1234';
    $new_manager_pin = '4321';
}

// 2. Actualizar en Base de Datos
try {
    // Verificar si existe la tabla system_settings
    $stmt = $pdo->query("SHOW TABLES LIKE 'system_settings'");
    if ($stmt->rowCount() > 0) {
        $check = $pdo->query("SELECT id FROM system_settings LIMIT 1");
        if ($check->rowCount() > 0) {
            $update = $pdo->prepare("UPDATE system_settings SET admin_pin = :admin_pin, manager_pin = :manager_pin WHERE id = 1");
            $update->execute([
                ':admin_pin' => $new_admin_pin,
                ':manager_pin' => $new_manager_pin
            ]);
        } else {
            $insert = $pdo->prepare("INSERT INTO system_settings (id, admin_pin, manager_pin) VALUES (1, :admin_pin, :manager_pin)");
            $insert->execute([
                ':admin_pin' => $new_admin_pin,
                ':manager_pin' => $new_manager_pin
            ]);
        }
    } else {
        throw new Exception("La tabla system_settings no existe en la base de datos.");
    }
} catch (Exception $e) {
    echo "\n [ERROR DB] No se pudo actualizar la base de datos: " . $e->getMessage() . "\n";
    exit(1);
}

// 3. Crear el nuevo archivo en la carpeta
$fecha = date('d/m/Y H:i:s');
$contenido_archivo = <<<TXT
========================================================================
             SISTEMA JABON V2 - PINES DE SEGURIDAD ACTUALES
========================================================================
Fecha y Hora de Generación: {$fecha}

------------------------------------------------------------------------
1. PIN ADMINISTRATIVO (ACCESO GENERAL)
------------------------------------------------------------------------
   PIN: {$new_admin_pin}
   
   Permite el acceso a:
   - Gestión de Inventario (Productos, Stock, Precios)
   - Catálogo Digital
   - Historial de Ventas y Detalles de Transacciones
   - Ajustes y Configuración del Negocio

------------------------------------------------------------------------
2. PIN GERENCIAL (CONTROL DE MATERIA PRIMA / INSUMOS)
------------------------------------------------------------------------
   PIN: {$new_manager_pin}
   
   Permite el acceso a:
   - Módulo de Insumos y Materia Prima
   - Autorización de Bajas de Inventario por Elaboración

------------------------------------------------------------------------
NOTA DE SEGURIDAD:
- Cada vez que ejecutes 'REINICIAR_PINES.cmd', el PIN anterior se borrará
  de la base de datos y este archivo será reemplazado con los nuevos PINs.
- Guarda este archivo o anota los PINs en un lugar seguro.
========================================================================
TXT;

file_put_contents($pin_file, $contenido_archivo);

// 4. Mostrar confirmación en pantalla
echo "\n======================================================================\n";
echo "           ¡PINES REINICIADOS Y ACTUALIZADOS CON ÉXITO!\n";
echo "======================================================================\n\n";
if ($deleted_previous) {
    echo "  [x] El PIN anterior fue ELIMINADO de la carpeta y de la base de datos.\n";
}
echo "  [+] NUEVO PIN ADMINISTRATIVO :  [ {$new_admin_pin} ]\n";
echo "  [+] NUEVO PIN GERENCIAL      :  [ {$new_manager_pin} ]\n\n";
echo "  [✓] Archivo generado en la carpeta: PIN_ACTUAL.txt\n";
echo "  [✓] Base de datos 'system_settings' actualizada correctamente.\n\n";
echo "======================================================================\n";
