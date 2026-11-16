<?php

session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../includes/mensajes.php";

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

                <span>
                    <?= htmlspecialchars($_SESSION["usuario_nombre"]) ?>
                </span>

                <a href="../auth/logout.php">
                    Cerrar sesión
                </a>

            </div>

        </div>

    </header>

    <main class="contenedor dashboard">

        <section class="formulario-card">

            <div class="formulario-cabecera">

                <h1>Añadir contacto</h1>

                <p>
                    Registra los datos de una nueva persona en tu directorio.
                </p>

            </div>

            <?php mostrarMensaje(); ?> 
            
            <form action="guardar.php" method="POST">

                <div class="form-grupo">

                    <label for="nombre">
                        Nombre completo
                    </label>

                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        placeholder="Ej. María López"
                        maxlength="150"
                        required
                    >

                </div>

                <div class="form-grupo">

                    <label for="telefono">
                        Teléfono
                    </label>

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

                    <label for="correo">
                        Correo electrónico
                    </label>

                    <input
                        type="email"
                        id="correo"
                        name="correo"
                        placeholder="Ej. maria@correo.com"
                        maxlength="150"
                        required
                    >

                </div>

                <div class="form-grupo">

                    <label for="ciudad">
                        Ciudad
                    </label>

                    <input
                        type="text"
                        id="ciudad"
                        name="ciudad"
                        placeholder="Ej. Cuenca"
                        required
                    >

                </div>

                <div class="formulario-acciones">

                    <a
                        href="listar.php"
                        class="btn btn-secundario">
                        Cancelar
                    </a>

                    <button
                        type="submit"
                        class="btn btn-principal">
                        Guardar contacto
                    </button>

                </div>

            </form>

        </section>

    </main>

</body>

</html>
