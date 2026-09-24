@echo off
chcp 65001 >nul
title Instalador de Base de Datos - Sistema Jabones
color 0b

:: Obtener directorio actual
set "SCRIPT_DIR=%~dp0"
cd /d "%SCRIPT_DIR%"

echo ======================================================================
echo          INSTALADOR AUTOMÁTICO DE BASE DE DATOS - SISTEMA JABONES
echo ======================================================================
echo.

:: 1. Detectar mysql.exe
set "MYSQL_BIN="
where mysql.exe >nul 2>&1
if %ERRORLEVEL% equ 0 (
    set "MYSQL_BIN=mysql.exe"
) else if exist "C:\xampp\mysql\bin\mysql.exe" (
    set "MYSQL_BIN=C:\xampp\mysql\bin\mysql.exe"
) else if exist "..\..\mysql\bin\mysql.exe" (
    set "MYSQL_BIN=..\..\mysql\bin\mysql.exe"
) else if exist "D:\xampp\mysql\bin\mysql.exe" (
    set "MYSQL_BIN=D:\xampp\mysql\bin\mysql.exe"
) else (
    echo [ERROR] No se encontró el ejecutable mysql.exe de XAMPP.
    echo Asegúrate de tener XAMPP instalado en C:\xampp o MySQL en tu variable PATH.
    echo.
    pause
    exit /b 1
)

:: 2. Probar conexión a MySQL
"%MYSQL_BIN%" -u root -e "SELECT 1;" >nul 2>&1
if %ERRORLEVEL% neq 0 (
    echo [ALERTA] No se pudo conectar a MySQL en localhost:3306 con usuario 'root'.
    echo Por favor asegúrate de que MySQL esté INICIADO en tu panel de control de XAMPP.
    echo.
    pause
    exit /b 1
)

echo [OK] Servidor MySQL detectado y conectado correctamente.
echo.
echo Selecciona la opción que deseas importar:
echo.
echo   [1] Base de Datos COMPLETA (Recomendado)
echo       - Incluye todos los productos, categorías, ventas y configuraciones actuales.
echo.
echo   [2] Base de Datos LIMPIA (Para empezar desde cero)
echo       - Estructura lista con categorías base y PINs por defecto (1234/4321), sin ventas ni productos.
echo.
echo   [3] Instalar TODO COMPLETO (Sistema POS + Loader Móvil)
echo       - Instala 'jabones_pos_db' completa y 'jabones_loader_db'.
echo.
echo   [4] Solo Base de Datos de Loader Móvil (jabones_loader_db)
echo.
echo   [5] Cancelar y Salir
echo.
set /p OPCION="Ingresa el número de tu opción (1-5): "

if "%OPCION%"=="1" goto instalar_completa
if "%OPCION%"=="2" goto instalar_limpia
if "%OPCION%"=="3" goto instalar_todo
if "%OPCION%"=="4" goto instalar_loader
if "%OPCION%"=="5" goto salir

echo Opción no válida.
pause
exit /b 0

:instalar_completa
echo.
echo [PROCESANDO] Importando base de datos completa (jabones_pos_db_completa.sql)...
"%MYSQL_BIN%" -u root < "jabones_pos_db_completa.sql"
if %ERRORLEVEL% equ 0 (
    echo.
    echo ======================================================================
    echo  ¡ÉXITO! Base de datos 'jabones_pos_db' instalada y lista para usar.
    echo ======================================================================
) else (
    echo.
    echo [ERROR] Ocurrió un problema durante la importación.
)
goto fin

:instalar_limpia
echo.
echo [PROCESANDO] Importando base de datos limpia (jabones_pos_db_limpia.sql)...
"%MYSQL_BIN%" -u root < "jabones_pos_db_limpia.sql"
if %ERRORLEVEL% equ 0 (
    echo.
    echo ======================================================================
    echo  ¡ÉXITO! Base de datos limpia instalada con éxito.
    echo  PIN Admin por defecto: 1234
    echo  PIN Gerencial por defecto: 4321
    echo ======================================================================
) else (
    echo.
    echo [ERROR] Ocurrió un problema durante la importación.
)
goto fin

:instalar_todo
echo.
echo [PROCESANDO] Importando todas las bases de datos (INSTALAR_TODO_COMPLETO.sql)...
"%MYSQL_BIN%" -u root < "INSTALAR_TODO_COMPLETO.sql"
if %ERRORLEVEL% equ 0 (
    echo.
    echo ======================================================================
    echo  ¡ÉXITO! Ambas bases de datos ('jabones_pos_db' y 'jabones_loader_db')
    echo  han sido creadas e importadas correctamente.
    echo ======================================================================
) else (
    echo.
    echo [ERROR] Ocurrió un problema durante la importación.
)
goto fin

:instalar_loader
echo.
echo [PROCESANDO] Importando base de datos del Loader Móvil (jabones_loader_db.sql)...
"%MYSQL_BIN%" -u root < "jabones_loader_db.sql"
if %ERRORLEVEL% equ 0 (
    echo.
    echo ======================================================================
    echo  ¡ÉXITO! Base de datos 'jabones_loader_db' instalada correctamente.
    echo ======================================================================
) else (
    echo.
    echo [ERROR] Ocurrió un problema durante la importación.
)
goto fin

:fin
echo.
echo Ya puedes ingresar a tu sistema en el navegador:
echo   - Sistema Principal: http://localhost/sistemajabonv2/
echo   - Loader Móvil:      http://localhost/sistemajabonv2/loader_app/
echo.
pause
exit /b 0

:salir
echo Cancelado por el usuario.
exit /b 0
