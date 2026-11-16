<?php
session_start();

if (isset($_SESSION["usuario_id"])) {
    header("Location: contactos/listar.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Agenda360 — Directorio Personal de Contactos</title>

    <link rel="stylesheet" href="css/estilo.css">
</head>

<body>

    <main class="pagina-inicio">

        <section class="contenedor">

            <div class="inicio-contenido">

                <div class="logo">
                    Agenda<span>360</span>
                </div>

                <h1>
                    Tu directorio de contactos,
                    <span>centralizado y protegido.</span>
                </h1>

                <p>
                    La forma más rápida, moderna y segura de gestionar tus contactos
                    personales con una visión completa en 360 grados.
                </p>

                <div class="inicio-botones">
                    <a href="auth/login.php" class="btn btn-principal">
                        Iniciar sesión
                    </a>

                    <a href="auth/registro.php" class="btn btn-secundario">
                        Crear cuenta
                    </a>
                </div>

            </div>

        </section>

    </main>

</body>

</html>
