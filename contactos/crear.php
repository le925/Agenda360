<?php

session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/conexion.php";
require_once "../includes/mensajes.php";

// Consultar las categorías oficiales disponibles
$stmt = $conexion->query("SELECT id, nombre FROM categorias ORDER BY id ASC");
$categorias = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo contacto | Agenda360</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>

<body>

    <header class="navbar">
        <div class="contenedor navbar-contenido">
            <a href="listar.php" class="navbar-logo">
                Agenda<span>360</span>
            </a>

            <div class="navbar-usuario">
                <span><?= htmlspecialchars($_SESSION["usuario_nombre"]) ?></span>
                <a href="../auth/logout.php">Cerrar sesión</a>
            </div>
        </div>
    </header>

    <main class="contenedor dashboard">

        <section class="formulario-card">

            <div class="formulario-cabecera">
                <h1>Añadir nuevo contacto</h1>
                <p>Registra la información completa y clasifícala por categoría.</p>
            </div>

            <?php mostrarMensaje(); ?>

            <form action="guardar.php" method="POST">

                <div class="form-grupo">
                    <label for="nombre">Nombre completo *</label>
                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        placeholder="Ej. María López Solís"
                        maxlength="150"
                        required
                        autofocus
                    >
                </div>

                <div class="form-grid-dos">

                    <div class="form-grupo">
                        <label for="telefono">Teléfono / Celular *</label>
                        <input
                            type="text"
                            id="telefono"
                            name="telefono"
                            placeholder="Ej. 0987654321"
                            maxlength="30"
                            required
                        >
                    </div>

                    <div class="form-grupo">
                        <label for="correo">Correo electrónico *</label>
                        <input
                            type="email"
                            id="correo"
                            name="correo"
                            placeholder="Ej. maria@ejemplo.com"
                            maxlength="150"
                            required
                        >
                    </div>

                </div>

                <div class="form-grid-dos">

                    <div class="form-grupo">
                        <label for="ciudad">Ciudad de residencia *</label>
                        <input
                            type="text"
                            id="ciudad"
                            name="ciudad"
                            placeholder="Ej. Loja, Cuenca, Quito"
                            maxlength="100"
                            required
                        >
                    </div>

                    <div class="form-grupo">
                        <label for="id_categoria">Categoría *</label>
                        <select id="id_categoria" name="id_categoria" required>
                            <option value="">-- Selecciona una categoría --</option>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?= (int)$cat["id"] ?>">
                                    <?= htmlspecialchars($cat["nombre"]) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                </div>

                <div class="form-grupo-check">
                    <label class="check-contenedor">
                        <input type="checkbox" name="favorito" value="1" id="favorito">
                        <span class="check-custom"></span>
                        <span class="check-texto">
                            ⭐ <strong>Marcar como contacto favorito</strong> (acceso rápido y destacado)
                        </span>
                    </label>
                </div>

                <div class="formulario-acciones">
                    <a href="listar.php" class="btn btn-secundario">
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-principal">
                        Guardar contacto
                    </button>
                </div>

            </form>

        </section>

    </main>

</body>

</html>
