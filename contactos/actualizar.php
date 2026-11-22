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

$id           = $_POST["id"] ?? "";
$nombre       = trim($_POST["nombre"] ?? "");
$telefono     = trim($_POST["telefono"] ?? "");
$correo       = trim($_POST["correo"] ?? "");
$ciudad       = trim($_POST["ciudad"] ?? "");
$id_categoria = trim($_POST["id_categoria"] ?? "");
$favorito     = isset($_POST["favorito"]) ? 1 : 0;

if (!filter_var($id, FILTER_VALIDATE_INT) || (int)$id < 1) {
    guardarMensaje("error", "El contacto seleccionado no es válido.");
    header("Location: listar.php");
    exit;
}

if (
    $nombre === "" ||
    $telefono === "" ||
    $correo === "" ||
    $ciudad === "" ||
    $id_categoria === ""
) {
    guardarMensaje("error", "Todos los campos obligatorios deben completarse.");
    header("Location: editar.php?id=" . (int)$id);
    exit;
}

if (
    mb_strlen($nombre) > 150 ||
    mb_strlen($telefono) > 30 ||
    mb_strlen($correo) > 150 ||
    mb_strlen($ciudad) > 100
) {
    guardarMensaje("error", "Uno o más campos superan la longitud máxima permitida.");
    header("Location: editar.php?id=" . (int)$id);
    exit;
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    guardarMensaje("error", "Ingresa un correo electrónico válido.");
    header("Location: editar.php?id=" . (int)$id);
    exit;
}

if (!filter_var($id_categoria, FILTER_VALIDATE_INT) || (int)$id_categoria < 1) {
    guardarMensaje("error", "Selecciona una categoría válida.");
    header("Location: editar.php?id=" . (int)$id);
    exit;
}

// Validar que la categoría exista
$stmt_cat = $conexion->prepare("SELECT id FROM categorias WHERE id = ?");
$stmt_cat->execute([(int)$id_categoria]);
if (!$stmt_cat->fetch()) {
    guardarMensaje("error", "La categoría seleccionada no existe.");
    header("Location: editar.php?id=" . (int)$id);
    exit;
}

try {
    // Actualizar contacto asegurando la pertenencia al usuario autenticado
    $sql = "UPDATE contactos
            SET id_categoria = ?,
                nombre = ?,
                telefono = ?,
                correo = ?,
                ciudad = ?,
                favorito = ?
            WHERE id = ? AND id_usuario = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([
        (int)$id_categoria,
        $nombre,
        $telefono,
        $correo,
        $ciudad,
        $favorito,
        (int)$id,
        $_SESSION["usuario_id"]
    ]);

    if ($stmt->rowCount() === 0) {
        // Verificar si el contacto existe y pertenece al usuario
        $check = $conexion->prepare("SELECT id FROM contactos WHERE id = ? AND id_usuario = ?");
        $check->execute([(int)$id, $_SESSION["usuario_id"]]);
        if (!$check->fetch()) {
            guardarMensaje("error", "El contacto no existe o no tienes autorización para modificarlo.");
            header("Location: listar.php");
            exit;
        }
    }

    guardarMensaje("exito", "Contacto \"{$nombre}\" actualizado correctamente.");
    header("Location: listar.php");
    exit;
} catch (PDOException $e) {
    guardarMensaje("error", "No fue posible actualizar el contacto. Inténtalo nuevamente.");
    header("Location: editar.php?id=" . (int)$id);
    exit;
}
