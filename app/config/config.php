<?php
// Configuración de Base de Datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // Deje vacío si no tiene clave en XAMPP
define('DB_NAME', 'ecommerce_papeleria');

// Rutas
define('APPROOT', dirname(dirname(__FILE__)));
// IMPORTANTE: Asegúrese de que esta ruta coincida con el nombre de su carpeta en htdocs
define('URLROOT', 'http://localhost/ecommerce_papeleria');
define('SITENAME', 'Papeleria todo arte');
