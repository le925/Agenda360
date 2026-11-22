<?php
session_start();

require_once "../includes/mensajes.php";

if (isset($_GET["cerrada"]) && $_GET["cerrada"] === "1") {
    guardarMensaje("exito", "Has cerrado sesión correctamente.");
    header("Location: login.php");
    exit;
}

if (isset($_SESSION["usuario_id"])) {
    header("Location: ../contactos/listar.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Iniciar sesión | Agenda360</title>

    <link rel="stylesheet" href="../css/estilo.css">
</head>

<body>

    <main class="pagina-auth">

        <section class="auth-contenedor">

            <div class="auth-marca">
                <a href="../index.php">Agenda<span>360</span></a>
            </div>

            <div class="auth-cabecera">
                <h1>Acceso a tu cuenta</h1>
                <p>Ingresa tus datos para acceder a tu panel de contactos.</p>
            </div>

            <?php mostrarMensaje(); ?>

            <div class="alerta-demo" style="background:#f0f9ff; border:1px solid #bae6fd; border-radius:8px; padding:12px 14px; margin-bottom:20px; font-size:13px; color:#0369a1;">
                <strong>💡 Acceso Demo para Evaluación:</strong><br>
                <span>Correo: <code>demo@agenda360.com</code></span><br>
                <span>Contraseña: <code>12345678</code></span>
            </div>

            <form action="autenticar.php" method="POST">

                <div class="form-grupo">
                    <label for="correo">Correo electrónico</label>

                    <input
                        type="email"
                        id="correo"
                        name="correo"
                        placeholder="tucorreo@ejemplo.com"
                        maxlength="150"
                        autocomplete="email"
                        required
                    >
                </div>

                <div class="form-grupo">
                    <label for="password">Contraseña</label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Ingresa tu contraseña"
                        autocomplete="current-password"
                        required
                    >
                </div>

                <button
                    type="submit"
                    class="btn btn-principal btn-completo"
                >
                    Entrar a mi agenda
                </button>

            </form>

            <div class="auth-pie">
                <p>
                    ¿Aún no te has registrado?
                    <a href="registro.php">Crea tu cuenta aquí</a>
                </p>
            </div>

        </section>

    </main>

</body>

</html>
