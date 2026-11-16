<?php
session_start();

if (isset($_SESSION["usuario_id"])) {
    header("Location: ../contactos/listar.php");
    exit;
}

require_once "../includes/mensajes.php";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Crear cuenta | Agenda360</title>

    <link rel="stylesheet" href="../css/estilo.css">
</head>

<body>

    <main class="pagina-auth">

        <section class="auth-contenedor">

            <div class="auth-marca">
                <a href="../index.php">
                    Agenda<span>360</span>
                </a>
            </div>

            <div class="auth-cabecera">

                <h1>Registro de usuario</h1>

                <p>
                    Únete a Agenda360 y organiza tus contactos con total privacidad.
                </p>

            </div> 

            <?php mostrarMensaje(); ?>

            <form action="registrar.php" method="POST">

                <div class="form-grupo">

                    <label for="nombre">
                        Nombre completo
                    </label>

                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        placeholder="Ej. Juan Pérez"
                        maxlength="100"
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
                        placeholder="tucorreo@ejemplo.com"
                        maxlength="150"
                        required
                    >

                </div>

                <div class="form-grupo">

                    <label for="password">
                        Contraseña
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Mínimo 6 caracteres"
                        minlength="6"
                        maxlength="72"
                        required
                    >

                </div>

                <div class="form-grupo">

                    <label for="password_confirmar">
                        Confirmar contraseña
                    </label>

                    <input
                        type="password"
                        id="password_confirmar"
                        name="password_confirmar"
                        placeholder="Repite tu contraseña"
                        minlength="6"
                        maxlength="72"
                        required
                    >

                </div>

                <button
                    type="submit"
                    class="btn btn-principal btn-completo">
                    Registrar cuenta
                </button>

            </form>

            <div class="auth-pie">

                <p>
                    ¿Ya tienes una cuenta registrada?
                    <a href="login.php">
                        Inicia sesión
                    </a>
                </p>

            </div>

        </section>

    </main>

</body>

</html>
