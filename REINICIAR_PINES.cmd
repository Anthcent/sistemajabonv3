@echo off
chcp 65001 >nul
title Reinicio de PINs de Seguridad - Sistema Jabones
color 0a

set "SCRIPT_DIR=%~dp0"
cd /d "%SCRIPT_DIR%"

:: 1. Detectar PHP
set "PHP_BIN="
where php.exe >nul 2>&1
if %ERRORLEVEL% equ 0 (
    set "PHP_BIN=php.exe"
) else if exist "C:\xampp\php\php.exe" (
    set "PHP_BIN=C:\xampp\php\php.exe"
) else if exist "..\php\php.exe" (
    set "PHP_BIN=..\php\php.exe"
) else if exist "D:\xampp\php\php.exe" (
    set "PHP_BIN=D:\xampp\php\php.exe"
) else (
    echo.
    echo ======================================================================
    echo  [ERROR] No se encontró el ejecutable php.exe de XAMPP.
    echo  Asegúrate de que XAMPP esté instalado en C:\xampp o PHP en tu PATH.
    echo ======================================================================
    echo.
    pause
    exit /b 1
)

:: 2. Ejecutar script PHP de reinicio
"%PHP_BIN%" "%SCRIPT_DIR%reset_pins.php" %*

echo.
echo Presiona cualquier tecla para cerrar esta ventana...
pause >nul
exit /b 0
