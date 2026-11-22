<?php

session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/conexion.php";
require_once "../includes/mensajes.php";

$usuario_id = $_SESSION["usuario_id"];

// 1. Obtener lista oficial de categorías para el filtro
$stmt_cat = $conexion->query("SELECT id, nombre FROM categorias ORDER BY id ASC");
$categorias = $stmt_cat->fetchAll();

// 2. Parámetros de filtrado, búsqueda y ordenamiento
$busqueda         = trim($_GET["q"] ?? "");
$campo_busqueda   = trim($_GET["campo"] ?? "todos"); // todos | nombre | correo | ciudad
$filtro_categoria = trim($_GET["categoria"] ?? "");
$filtro_favorito  = trim($_GET["favorito"] ?? "");   // 1 | ""
$orden            = trim($_GET["orden"] ?? "fecha_desc"); // nombre_asc | nombre_desc | ciudad_asc | ciudad_desc | fecha_desc | fecha_asc

// 3. Construcción dinámica de consulta segura con sentencias preparadas
$condiciones = ["c.id_usuario = ?"];
$parametros  = [$usuario_id];

// Mejora: Opción A - Búsqueda por Nombre, Correo o Ciudad
if ($busqueda !== "") {
    $param_like = "%{$busqueda}%";
    if ($campo_busqueda === "nombre") {
        $condiciones[] = "c.nombre LIKE ?";
        $parametros[]  = $param_like;
    } elseif ($campo_busqueda === "correo") {
        $condiciones[] = "c.correo LIKE ?";
        $parametros[]  = $param_like;
    } elseif ($campo_busqueda === "ciudad") {
        $condiciones[] = "c.ciudad LIKE ?";
        $parametros[]  = $param_like;
    } else {
        $condiciones[] = "(c.nombre LIKE ? OR c.correo LIKE ? OR c.ciudad LIKE ?)";
        $parametros[]  = $param_like;
        $parametros[]  = $param_like;
        $parametros[]  = $param_like;
    }
}

// Mejora: Opción C - Filtro por Categorías
if ($filtro_categoria !== "" && filter_var($filtro_categoria, FILTER_VALIDATE_INT)) {
    $condiciones[] = "c.id_categoria = ?";
    $parametros[]  = (int)$filtro_categoria;
}

// Mejora: Opción D - Filtro de Favoritos
if ($filtro_favorito === "1") {
    $condiciones[] = "c.favorito = 1";
}

// Mejora: Opción B - Ordenamiento por Nombre, Ciudad o Fecha de Creación
$mapa_orden = [
    "nombre_asc"  => "c.nombre ASC",
    "nombre_desc" => "c.nombre DESC",
    "ciudad_asc"  => "c.ciudad ASC",
    "ciudad_desc" => "c.ciudad DESC",
    "fecha_desc"  => "c.fecha_creacion DESC",
    "fecha_asc"   => "c.fecha_creacion ASC"
];

$clausula_orden = $mapa_orden[$orden] ?? "c.fecha_creacion DESC";

// Consulta principal
$sql = "SELECT c.id, c.nombre, c.telefono, c.correo, c.ciudad, c.favorito, c.fecha_creacion,
               c.id_categoria, cat.nombre AS categoria_nombre
        FROM contactos c
        INNER JOIN categorias cat ON c.id_categoria = cat.id
        WHERE " . implode(" AND ", $condiciones) . "
        ORDER BY {$clausula_orden}";

$stmt = $conexion->prepare($sql);
$stmt->execute($parametros);
$contactos = $stmt->fetchAll();

// Métricas y totales del usuario para el panel
$stmt_totales = $conexion->prepare(
    "SELECT 
        COUNT(*) AS total,
        SUM(CASE WHEN favorito = 1 THEN 1 ELSE 0 END) AS favoritos
     FROM contactos
     WHERE id_usuario = ?"
);
$stmt_totales->execute([$usuario_id]);
$metricas = $stmt_totales->fetch() ?: ["total" => 0, "favoritos" => 0];

$hay_filtros_activos = ($busqueda !== "" || $filtro_categoria !== "" || $filtro_favorito === "1" || $orden !== "fecha_desc");

// Construir query string actual para enlaces de toggle favorito
$query_actual = $_GET;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis contactos | Agenda360</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>

<body>

    <header class="navbar">
        <div class="contenedor navbar-contenido">
            <a href="listar.php" class="navbar-logo">
                Agenda<span>360</span>
            </a>

            <div class="navbar-usuario">
                <span class="usuario-saludo">
                    Hola, <strong><?= htmlspecialchars($_SESSION["usuario_nombre"]) ?></strong>
                </span>
                <a href="../auth/logout.php" class="btn-logout">Cerrar sesión</a>
            </div>
        </div>
    </header>

    <main class="contenedor dashboard">

        <?php mostrarMensaje(); ?>

        <!-- Cabecera principal -->
        <div class="dashboard-cabecera">
            <div>
                <p class="dashboard-saludo">Panel de Control &bull; Agenda360</p>
                <h1>Directorio Personal</h1>
                <div class="dashboard-correo">
                    <span>Usuario:</span> <?= htmlspecialchars($_SESSION["usuario_correo"] ?? "") ?>
                </div>
            </div>

            <div class="dashboard-acciones">
                <a href="crear.php" class="btn btn-principal">
                    + Nuevo contacto
                </a>
            </div>
        </div>

        <!-- Tarjetas de métricas -->
        <section class="estadisticas">
            <div class="estadistica-card">
                <div class="stat-icono">👥</div>
                <div>
                    <p>Total de contactos</p>
                    <strong><?= (int)$metricas["total"] ?></strong>
                </div>
            </div>

            <div class="estadistica-card">
                <div class="stat-icono">⭐</div>
                <div>
                    <p>Contactos favoritos</p>
                    <strong><?= (int)$metricas["favoritos"] ?></strong>
                </div>
            </div>

            <div class="estadistica-card">
                <div class="stat-icono">🔍</div>
                <div>
                    <p>Resultados visibles</p>
                    <strong><?= count($contactos) ?></strong>
                </div>
            </div>
        </section>

        <!-- Barra de herramientas: Búsqueda (Opción A), Ordenamiento (Opción B), Categorías (Opción C) y Favoritos (Opción D) -->
        <section class="filtros-contenedor">
            <form action="listar.php" method="GET" class="filtros-formulario">

                <!-- Opción A: Búsqueda -->
                <div class="filtro-campo filtro-buscar">
                    <label for="q">Buscar contactos</label>
                    <div class="input-buscar-grupo">
                        <input
                            type="text"
                            id="q"
                            name="q"
                            placeholder="Buscar por nombre, correo o ciudad..."
                            value="<?= htmlspecialchars($busqueda) ?>"
                        >
                        <select name="campo" id="campo" title="Campo específico de búsqueda">
                            <option value="todos" <?= ($campo_busqueda === "todos") ? "selected" : "" ?>>Todos los campos</option>
                            <option value="nombre" <?= ($campo_busqueda === "nombre") ? "selected" : "" ?>>Nombre</option>
                            <option value="correo" <?= ($campo_busqueda === "correo") ? "selected" : "" ?>>Correo</option>
                            <option value="ciudad" <?= ($campo_busqueda === "ciudad") ? "selected" : "" ?>>Ciudad</option>
                        </select>
                    </div>
                </div>

                <!-- Opción C: Categorías -->
                <div class="filtro-campo">
                    <label for="categoria">Categoría</label>
                    <select name="categoria" id="categoria">
                        <option value="">Todas las categorías</option>
                        <?php foreach ($categorias as $cat): ?>
                            <option
                                value="<?= (int)$cat["id"] ?>"
                                <?= ($filtro_categoria === (string)$cat["id"]) ? "selected" : "" ?>
                            >
                                <?= htmlspecialchars($cat["nombre"]) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Opción D: Favoritos -->
                <div class="filtro-campo">
                    <label for="favorito">Favoritos</label>
                    <select name="favorito" id="favorito">
                        <option value="">Todos los contactos</option>
                        <option value="1" <?= ($filtro_favorito === "1") ? "selected" : "" ?>>⭐ Solo favoritos</option>
                    </select>
                </div>

                <!-- Opción B: Ordenamiento -->
                <div class="filtro-campo">
                    <label for="orden">Ordenar por</label>
                    <select name="orden" id="orden">
                        <option value="fecha_desc" <?= ($orden === "fecha_desc") ? "selected" : "" ?>>Fecha: Más recientes</option>
                        <option value="fecha_asc" <?= ($orden === "fecha_asc") ? "selected" : "" ?>>Fecha: Más antiguos</option>
                        <option value="nombre_asc" <?= ($orden === "nombre_asc") ? "selected" : "" ?>>Nombre (A &rarr; Z)</option>
                        <option value="nombre_desc" <?= ($orden === "nombre_desc") ? "selected" : "" ?>>Nombre (Z &rarr; A)</option>
                        <option value="ciudad_asc" <?= ($orden === "ciudad_asc") ? "selected" : "" ?>>Ciudad (A &rarr; Z)</option>
                        <option value="ciudad_desc" <?= ($orden === "ciudad_desc") ? "selected" : "" ?>>Ciudad (Z &rarr; A)</option>
                    </select>
                </div>

                <div class="filtro-botones">
                    <button type="submit" class="btn btn-principal btn-filtrar">
                        Aplicar filtros
                    </button>
                    <?php if ($hay_filtros_activos): ?>
                        <a href="listar.php" class="btn btn-secundario btn-limpiar" title="Restablecer filtros">
                            Limpiar
                        </a>
                    <?php endif; ?>
                </div>

            </form>
        </section>

        <!-- Listado de Contactos -->
        <section class="contactos-seccion">

            <div class="seccion-cabecera">
                <div>
                    <h2>Contactos Registrados</h2>
                    <p>
                        <?php if ($hay_filtros_activos): ?>
                            Mostrando <?= count($contactos) ?> resultado(s) según los filtros aplicados.
                        <?php else: ?>
                            Directorio privado y seguro vinculado a tu cuenta.
                        <?php endif; ?>
                    </p>
                </div>
            </div>

            <?php if (empty($contactos)): ?>

                <div class="estado-vacio">
                    <?php if ($hay_filtros_activos): ?>
                        <div class="estado-vacio-icono">🔍</div>
                        <h3>No se encontraron coincidencias</h3>
                        <p>No hay contactos que coincidan con los criterios de búsqueda o filtros seleccionados.</p>
                        <a href="listar.php" class="btn btn-secundario">Restablecer filtros</a>
                    <?php else: ?>
                        <div class="estado-vacio-icono">📇</div>
                        <h3>No tienes contactos guardados todavía</h3>
                        <p>Comienza agregando tu primer contacto para organizarlo por categorías y favoritos.</p>
                        <a href="crear.php" class="btn btn-principal">+ Añadir mi primer contacto</a>
                    <?php endif; ?>
                </div>

            <?php else: ?>

                <div class="tabla-contenedor">
                    <table class="tabla-contactos">
                        <thead>
                            <tr>
                                <th class="th-favorito" title="Favorito">Fav</th>
                                <th>Nombre</th>
                                <th>Categoría</th>
                                <th>Teléfono</th>
                                <th>Correo</th>
                                <th>Ciudad</th>
                                <th>Fecha Registro</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($contactos as $c): ?>
                                <?php
                                $es_fav = ((int)$c["favorito"] === 1);
                                $cat_nombre = htmlspecialchars($c["categoria_nombre"]);
                                $cat_clase = "badge-cat-" . strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $c["categoria_nombre"]));
                                
                                // Generar URL para alternar favorito conservando la query actual
                                $params_toggle = $query_actual;
                                $params_toggle["id"] = $c["id"];
                                $url_toggle = "favorito.php?" . http_build_query($params_toggle);
                                ?>
                                <tr class="<?= $es_fav ? 'fila-favorito' : '' ?>">
                                    <td class="td-favorito text-center">
                                        <a
                                            href="<?= $url_toggle ?>"
                                            class="btn-favorito <?= $es_fav ? 'activo' : '' ?>"
                                            title="<?= $es_fav ? 'Quitar de favoritos' : 'Marcar como favorito' ?>"
                                        >
                                            <?= $es_fav ? '★' : '☆' ?>
                                        </a>
                                    </td>

                                    <td class="td-nombre">
                                        <strong><?= htmlspecialchars($c["nombre"], ENT_QUOTES, "UTF-8") ?></strong>
                                    </td>

                                    <td>
                                        <span class="badge-categoria <?= $cat_clase ?>">
                                            <?= $cat_nombre ?>
                                        </span>
                                    </td>

                                    <td>
                                        <a href="tel:<?= htmlspecialchars($c["telefono"]) ?>" class="enlace-contacto">
                                            <?= htmlspecialchars($c["telefono"]) ?>
                                        </a>
                                    </td>

                                    <td>
                                        <a href="mailto:<?= htmlspecialchars($c["correo"]) ?>" class="enlace-contacto">
                                            <?= htmlspecialchars($c["correo"]) ?>
                                        </a>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($c["ciudad"]) ?>
                                    </td>

                                    <td class="td-fecha">
                                        <span title="<?= htmlspecialchars($c["fecha_creacion"]) ?>">
                                            <?= date("d/m/Y", strtotime($c["fecha_creacion"])) ?>
                                        </span>
                                    </td>

                                    <td class="acciones">
                                        <a
                                            href="editar.php?id=<?= (int)$c["id"] ?>"
                                            class="accion-editar"
                                            title="Editar información"
                                        >
                                            Editar
                                        </a>

                                        <form
                                            action="eliminar.php"
                                            method="POST"
                                            onsubmit="return confirm('¿Confirmas que deseas eliminar el contacto <?= addslashes($c["nombre"]) ?>?');"
                                            style="display:inline;"
                                        >
                                            <input type="hidden" name="id" value="<?= (int)$c["id"] ?>">
                                            <button type="submit" class="accion-eliminar" title="Eliminar contacto">
                                                Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            <?php endif; ?>

        </section>

    </main>

</body>

</html>
