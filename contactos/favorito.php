<?php

session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/conexion.php";
require_once "../includes/mensajes.php";

$id = $_REQUEST["id"] ?? "";

if (!filter_var($id, FILTER_VALIDATE_INT) || (int)$id < 1) {
    guardarMensaje("error", "Contacto no válido.");
    header("Location: listar.php");
    exit;
}

try {
    // Alternar el estado de favorito (1 -> 0, 0 -> 1) asegurando pertenencia al usuario en sesión
    $sql = "UPDATE contactos
            SET favorito = IF(favorito = 1, 0, 1)
            WHERE id = ? AND id_usuario = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([(int)$id, $_SESSION["usuario_id"]]);

    if ($stmt->rowCount() > 0) {
        // Consultar el nuevo estado para personalizar el mensaje
        $stmt_estado = $conexion->prepare("SELECT favorito, nombre FROM contactos WHERE id = ?");
        $stmt_estado->execute([(int)$id]);
        $row = $stmt_estado->fetch();

        if ($row && $row["favorito"] == 1) {
            guardarMensaje("exito", "Contacto \"{$row['nombre']}\" añadido a tus Favoritos ⭐.");
        } else {
            guardarMensaje("exito", "Contacto \"{$row['nombre']}\" retirado de Favoritos.");
        }
    } else {
        guardarMensaje("error", "No fue posible actualizar el contacto.");
    }
} catch (PDOException $e) {
    guardarMensaje("error", "Error en el servidor al actualizar el estado de favorito.");
}

// Redireccionar conservando los filtros si existen
$retorno = "listar.php";
if (!empty($_SERVER["QUERY_STRING"])) {
    // Remover parámetro id para no dejar basura en la query
    parse_str($_SERVER["QUERY_STRING"], $query_params);
    unset($query_params["id"]);
    if (!empty($query_params)) {
        $retorno .= "?" . http_build_query($query_params);
    }
}

header("Location: " . $retorno);
exit;
