<?php

/**
 * Configuración de conexión a Base de Datos mediante PDO
 * Soporta entorno Local (XAMPP) y Despliegue en Servidor de Hosting Gratuito (InfinityFree).
 */

$opciones = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
];

$http_host = $_SERVER['HTTP_HOST'] ?? '';
$es_hosting = (stripos($http_host, 'infinityfree.io') !== false || stripos($http_host, 'epizy.com') !== false);

$configuraciones = [];

if ($es_hosting) {
    // 1. Prioridad: Entorno de Producción en InfinityFree
    $configuraciones[] = [
        'host' => 'sql305.infinityfree.com',
        'port' => 3306,
        'db'   => 'if0_43508106_Agenda360',
        'user' => 'if0_43508106',
        'pass' => 'SolisLeo2005'
    ];
} else {
    // 2. Prioridad: Entorno Local (XAMPP) con contraseña personalizada y por defecto
    $configuraciones[] = [
        'host' => '127.0.0.1',
        'port' => 3306,
        'db'   => 'Agenda360',
        'user' => 'root',
        'pass' => 'Topglobalvims'
    ];
    $configuraciones[] = [
        'host' => '127.0.0.1',
        'port' => 3306,
        'db'   => 'Agenda360',
        'user' => 'root',
        'pass' => ''
    ];
    // Fallback adicional con nombre en minúsculas
    $configuraciones[] = [
        'host' => 'localhost',
        'port' => 3306,
        'db'   => 'agenda360',
        'user' => 'root',
        'pass' => 'Topglobalvims'
    ];
    $configuraciones[] = [
        'host' => 'localhost',
        'port' => 3306,
        'db'   => 'agenda360',
        'user' => 'root',
        'pass' => ''
    ];
    // Y si falla en localhost, fallback a InfinityFree por si se prueba remoto
    $configuraciones[] = [
        'host' => 'sql305.infinityfree.com',
        'port' => 3306,
        'db'   => 'if0_43508106_Agenda360',
        'user' => 'if0_43508106',
        'pass' => 'SolisLeo2005'
    ];
}

$conexion = null;

foreach ($configuraciones as $cfg) {
    try {
        $dsn = "mysql:host={$cfg['host']};port={$cfg['port']};dbname={$cfg['db']};charset=utf8mb4";
        $conexion = new PDO($dsn, $cfg['user'], $cfg['pass'], $opciones);
        break;
    } catch (PDOException $e) {
        // Intenta la siguiente configuración
        continue;
    }
}

if (!$conexion) {
    die("No fue posible conectar con la base de datos. Por favor verifica la configuración de MySQL.");
}
