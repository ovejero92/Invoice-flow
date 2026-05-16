/**
 * Diagnostico rapido: confirma que PHP puede cargar mbstring (requerido por Laravel).
 * Uso (PowerShell): php herramientas-diagnostico-php.php
 * O: & "ruta\a\php.exe" herramientas-diagnostico-php.php
 */
<?php

$ok = function_exists('mb_split');
echo $ok ? "OK: mbstring cargada (mb_split existe).\n" : "FALLO: mbstring NO esta cargada.\n";

if (! $ok) {
    echo "\nSi en la consola de 'php artisan serve' ves el mismo error, en Windows suele ser:\n";
    echo "  - 'Una directiva de Control de aplicaciones bloqueo este archivo' al cargar php_mbstring.dll\n";
    echo "\nPrueba en Windows 11:\n";
    echo "  Configuracion > Privacidad y seguridad > Seguridad de Windows\n";
    echo "  > Proteccion de aplicaciones y explorador > Control inteligente de aplicaciones\n";
    echo "  Desactivarlo o ponerlo en modo evaluacion, luego volve a abrir la terminal.\n";
    echo "\nO en Defender: exclusiones para la carpeta de PHP (WinGet o C:\\Dev\\tools\\php).\n";
}
exit($ok ? 0 : 1);
