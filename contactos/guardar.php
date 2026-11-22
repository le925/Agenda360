<?php

session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/conexion.php";
require_once "../includes/mensajes.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: listar.php");
    exit;
}

$nombre       = trim($_POST["nombre"] ?? "");
$telefono     = trim($_POST["telefono"] ?? "");
$correo       = trim($_POST["correo"] ?? "");
$ciudad       = trim($_POST["ciudad"] ?? "");
$id_categoria = trim($_POST["id_categoria"] ?? "");
$favorito     = isset($_POST["favorito"]) ? 1 : 0;

if (
    $nombre === "" ||
    $telefono === "" ||
    $correo === "" ||
    $ciudad === "" ||
    $id_categoria === ""
) {
    guardarMensaje("error", "Todos los campos obligatorios deben completarse.");
    header("Location: crear.php");
    exit;
}

if (
    mb_strlen($nombre) > 150 ||
    mb_strlen($telefono) > 30 ||
    mb_strlen($correo) > 150 ||
    mb_strlen($ciudad) > 100
) {
    guardarMensaje("error", "Uno o más campos superan la longitud máxima permitida.");
    header("Location: crear.php");
    exit;
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    guardarMensaje("error", "Ingresa un correo electrónico válido.");
    header("Location: crear.php");
    exit;
}

if (!filter_var($id_categoria, FILTER_VALIDATE_INT) || (int)$id_categoria < 1) {
    guardarMensaje("error", "Selecciona una categoría válida.");
    header("Location: crear.php");
    exit;
}

// Validar que la categoría exista en la base de datos
$stmt_cat = $conexion->prepare("SELECT id FROM categorias WHERE id = ?");
$stmt_cat->execute([(int)$id_categoria]);
if (!$stmt_cat->fetch()) {
    guardarMensaje("error", "La categoría seleccionada no existe.");
    header("Location: crear.php");
    exit;
}

try {
    $sql = "INSERT INTO contactos
            (id_usuario, id_categoria, nombre, telefono, correo, ciudad, favorito, fecha_creacion)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([
        $_SESSION["usuario_id"],
        (int)$id_categoria,
        $nombre,
        $telefono,
        $correo,
        $ciudad,
        $favorito
    ]);

    guardarMensaje("exito", "Contacto \"{$nombre}\" registrado exitosamente.");
    header("Location: listar.php");
    exit;
} catch (PDOException $e) {
    guardarMensaje("error", "No fue posible guardar el contacto. Inténtalo de nuevo.");
    header("Location: crear.php");
    exit;
}
