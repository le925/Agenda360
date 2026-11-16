<?php

$host = "127.0.0.1";
$usuario = "root";
$charset = "utf8mb4";

$opciones = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
];

// Soporte automático para entorno local (con contraseña) y entrega académica (XAMPP por defecto sin contraseña)
$posibles_passwords = ["Topglobalvims", ""];
$posibles_dbs = ["Agenda360", "agenda360", "AgendaPlus", "agenda_contactos"];

$conexion = null;

foreach ($posibles_passwords as $pass) {
    foreach ($posibles_dbs as $nombre_bd) {
        try {
            $dsn = "mysql:host={$host};dbname={$nombre_bd};charset={$charset}";
            $conexion = new PDO($dsn, $usuario, $pass, $opciones);
            break 2;
        } catch (PDOException $e) {
            continue;
        }
    }
}

if (!$conexion) {
    die("No fue posible conectar con la base de datos.");
}
