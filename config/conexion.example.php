<?php

/**
 * Plantilla de referencia para la conexión a MySQL mediante PDO.
 * Compatible con XAMPP local y servidores de hosting (como InfinityFree).
 */

$host = "127.0.0.1";
$puerto = 3306;
$db = "Agenda360";
$usuario = "root";
$password = ""; // En XAMPP estándar va vacío. En hosting configurar credenciales del panel.
$charset = "utf8mb4";

$dsn = "mysql:host={$host};port={$puerto};dbname={$db};charset={$charset}";

$opciones = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false
];

try {
    $conexion = new PDO($dsn, $usuario, $password, $opciones);
} catch (PDOException $e) {
    die("No fue posible conectar con la base de datos.");
}
