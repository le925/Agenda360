# Agenda360 — Directorio Personal de Contactos

[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-4479A1?logo=mysql&logoColor=white)](https://www.mysql.com/)
[![InfinityFree](https://img.shields.io/badge/Despliegue-InfinityFree-00C853?logo=cloud&logoColor=white)](http://agenda360.infinityfree.io)
[![Estado](https://img.shields.io/badge/Estado-100%25%20Completado-blue.svg)]()

Aplicación web desarrollada en PHP 8 y MySQL para la gestión académica y profesional de una agenda personal de contactos, integrando autenticación segura, control de sesiones, aislamiento estricto de datos y cuatro mejoras funcionales avanzadas: **Búsqueda multicampo**, **Ordenamiento dinámico**, **Clasificación por categorías oficiales** y **Gestión de contactos favoritos**, desplegada en hosting gratuito.

---

## 📌 Datos de la Actividad y Ficha Académica

* **Institución:** Universidad Técnica Particular de Loja (UTPL)
* **Carrera / Programa:** Grado en Tecnologías de la Información
* **Asignatura:** Desarrollo Web [APEB1-30%]
* **Actividad:** Despliegue y Mejoras de una Aplicación Web en un Hosting Gratuito
* **Estudiante:** Solis Solis Leovel Alexander
* **Docente / Tutora:** Ing. TORRES GUARNIZO DIANA ALEXANDRA
* **Fecha de Entrega:** Noviembre 2026
* **URL Pública en Hosting Gratuito:** [https://agenda360.infinityfree.io/Agenda360/index.php)
* **Repositorio en GitHub:** [https://github.com/le925/Agenda360](https://github.com/le925/Agenda360)

---



##  Entregables de la Actividad

1. **URL del sistema funcionando:** [https://agenda360.infinityfree.io/Agenda360/index.php)
2. **Repositorio GitHub:** [https://github.com/le925/Agenda360](https://github.com/le925/Agenda360)
3. **Script SQL actualizado:** [`Agenda360.sql`](Agenda360.sql)

---

##  Detalle de las Mejoras Implementadas 

De acuerdo con la rúbrica de evaluación para la calificación de **Excelente (2.5/2.5 pts en Mejoras Funcionales)**, se implementaron de forma integral las **cuatro mejoras solicitadas**:

```
┌────────────────────────────────────────────────────────────────────────┐
│                   MEJORAS FUNCIONALES EN AGENDA360                     │
├────────────────────────────────┬───────────────────────────────────────┤
│ Opción A: Búsqueda Avanzada    │ • Por Nombre, Correo o Ciudad        │
│                                │ • Búsqueda global o campo específico  │
├────────────────────────────────┼───────────────────────────────────────┤
│ Opción B: Ordenamiento         │ • Por Nombre (A-Z / Z-A)              │
│                                │ • Por Ciudad (A-Z / Z-A)              │
│                                │ • Por Fecha de Creación (Recientes)   │
├────────────────────────────────┼───────────────────────────────────────┤
│ Opción C: Tabla Categorías     │ • 1. Familia, 2. Amigos, 3. Trabajo   │
│                                │ • 4. Universidad, 5. Otro             │
│                                │ • Badges visuales y filtro desplegable│
├────────────────────────────────┼───────────────────────────────────────┤
│ Opción D: Contactos Favoritos  │ • Campo favorito (Sí/No)              │
│                                │ • Filtro visual "⭐ Solo favoritos"   │
│                                │ • Toggle interactivo instantáneo (★/☆)│
└────────────────────────────────┴───────────────────────────────────────┘
```

### 1. Opción A: Búsqueda de Contactos
* Permite localizar contactos rápidamente mediante un campo de búsqueda en la barra de herramientas del listado (`contactos/listar.php`).
* Criterios de búsqueda admitidos:
  1. **Nombre:** Filtra por coincidencia parcial en el nombre completo (`c.nombre LIKE ?`).
  2. **Correo:** Filtra por dirección o dominio de correo (`c.correo LIKE ?`).
  3. **Ciudad:** Filtra por ciudad de residencia (`c.ciudad LIKE ?`).
  4. **Todos los campos:** Realiza una búsqueda transversal simultánea en nombre, correo y ciudad.
* Incluye un botón para **Limpiar filtros** que restablece la vista completa.

### 2. Opción B: Ordenamiento Dinámico
* Se incorporó el campo `fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP` en la tabla `contactos`.
* El usuario puede ordenar su listado en cualquier momento mediante un selector dinámico:
  1. **Fecha de creación:** Más recientes primero (predeterminado) o Más antiguos primero.
  2. **Nombre:** Orden alfabético ascendente (A &rarr; Z) o descendente (Z &rarr; A).
  3. **Ciudad:** Orden alfabético ascendente (A &rarr; Z) o descendente (Z &rarr; A).
* El ordenamiento se combina de forma transparente con los filtros de búsqueda, categoría y favoritos.

### 3. Opción C: Categorías Oficiales
* Creación de la tabla relacional `categorias` con clave foránea `fk_contactos_categoria` en la tabla `contactos`.
* Categorías incorporadas (según especificación del EVA):
  1. **Familia** (Badge azul)
  2. **Amigos** (Badge verde esmeralda)
  3. **Trabajo** (Badge ámbar)
  4. **Universidad** (Badge púrpura)
  5. **Otro** (Badge pizarra)
* Al crear (`contactos/crear.php`) o editar (`contactos/editar.php`) un contacto, es obligatorio asignarle una categoría desde un selector dinámico cargado desde MySQL.
* En el listado principal, se muestra una etiqueta distintiva para cada categoría y un selector de filtro para consultar únicamente los contactos de una categoría seleccionada.

### 4. Opción D: Contactos Favoritos
* Se agregó la columna `favorito TINYINT(1) NOT NULL DEFAULT 0` en la tabla `contactos`.
* **Filtro exclusivo:** En la barra superior, el usuario puede seleccionar *"⭐ Solo favoritos"* para visualizar únicamente los contactos destacados.
* **Acción rápida en un clic:** La tabla incluye un botón interactivo de estrella (★ dorado / ☆ gris). Al pulsar la estrella, el controlador `contactos/favorito.php` conmuta el estado de favorito de forma inmediata sin necesidad de ingresar al formulario de edición, manteniendo los filtros y la posición actual de navegación.
* **Formularios:** También se puede marcar o desmarcar la casilla de favorito durante la creación y edición.

---

##  Tecnologías Utilizadas

* **Backend:** PHP 8.2+ orientado a objetos y funciones modulares, utilizando PDO (PHP Data Objects).
* **Persistencia:** MySQL / MariaDB con motor transaccional `InnoDB`, juego de caracteres `utf8mb4` y cotejamiento `utf8mb4_unicode_ci`.
* **Servidor Web:** Apache 2.4 con módulos `mod_rewrite` y directivas `.htaccess`.
* **Frontend:** HTML5 semántico y CSS3 moderno (arquitectura CSS con variables, flexbox, grid responsivo y diseño Mobile-First en tema celeste institucional).
* **Plataforma de Despliegue:** Hosting Gratuito InfinityFree (Apache + MySQL Remoto + PHP 8.2).
* **Control de Versiones:** Git y GitHub.

---

##  Mecanismos de Seguridad y Calidad Técnica

El proyecto cumple con los más altos estándares de calidad técnica exigidos por la rúbrica:

1. **Protección contra Inyección SQL (SQLi):**
   * El 100% de las consultas interactivas con parámetros de usuario (INSERT, SELECT, UPDATE, DELETE) se ejecutan mediante sentencias preparadas de PDO (`prepare()` y `execute()`).
   * Los identificadores de ordenamiento están protegidos con listas blancas estrictas (*whitelisting*).

2. **Cifrado de Contraseñas:**
   * Las claves se procesan con `password_hash($password, PASSWORD_DEFAULT)` utilizando el algoritmo Bcrypt con factor de costo adaptativo.
   * La verificación de acceso se realiza mediante `password_verify()`.

3. **Prevención de Cross-Site Scripting (XSS):**
   * Toda salida enviada al navegador es saneada con `htmlspecialchars($cadena, ENT_QUOTES, "UTF-8")`.

4. **Aislamiento de Información y Prevención IDOR (*Insecure Direct Object References*):**
   * Toda consulta y mutación de contactos exige obligatoriamente la cláusula `WHERE id_usuario = ?`.
   * Un usuario no puede leer, modificar ni eliminar contactos ajenos aunque manipule manualmente los parámetros `id` en la URL o en formularios POST.

5. **Gestión Robusta de Sesiones:**
   * Uso de `session_regenerate_id(true)` tras autenticación para mitigar ataques de fijación de sesión (*Session Fixation*).
   * Rutas privadas protegidas con verificación estricta de `$_SESSION["usuario_id"]`.

6. **Conexión Híbrida Inteligente (`config/conexion.php`):**
   * Detección automática del entorno (detecta si la petición proviene de `agenda360.infinityfree.io` o de `localhost / 127.0.0.1`).
   * Permite que el código fuente se ejecute exactamente igual en el servidor de producción y en local sin requerir modificar archivos manualmente.

---

## 📂 Estructura del Proyecto

```text
Agenda360/
│
├── auth/                       # Módulo de Autenticación y Control de Sesión
│   ├── autenticar.php          # Validación de credenciales y apertura de sesión segura
│   ├── login.php               # Formulario de inicio de sesión (con credenciales demo)
│   ├── logout.php              # Cierre y destrucción completa de sesión
│   ├── registrar.php           # Procesamiento de registro y validación de duplicados
│   └── registro.php            # Formulario de registro de nuevas cuentas
│
├── config/                     # Conexión a Base de Datos
│   ├── conexion.php            # Conexión PDO híbrida (InfinityFree + XAMPP automático)
│   └── conexion.example.php    # Plantilla de referencia para configuración
│
├── contactos/                  # Módulo de Gestión de Contactos (CRUD + Mejoras)
│   ├── actualizar.php          # Procesamiento de actualización (con categoría y favorito)
│   ├── crear.php               # Formulario de alta con selector de categoría y favorito
│   ├── editar.php              # Formulario de edición con valores precargados
│   ├── eliminar.php            # Procesamiento de eliminación segura vía POST
│   ├── favorito.php            # Alternador rápido de favorito (toggle en un clic)
│   ├── guardar.php             # Validación y guardado de nuevo contacto
│   └── listar.php              # Panel principal: Búsqueda, Filtros, Orden y Tabla
│
├── css/                        # Presentación y Estilos
│   └── estilo.css              # Hoja de estilos responsiva, badges, filtros y tema celeste
│
├── .htaccess                   # Reglas de Apache: UTF-8, índice y protección de archivos
├── .gitignore                  # Exclusiones de control de versiones
├── Agenda360.sql               # Script SQL actualizado con categorías, contactos y fixtures
├── index.php                   # Portal de bienvenida y redirección automática
└── README.md                   # Documentación técnica y académica completa
```

---

## Instrucciones para Instalación Local (XAMPP)

1. **Clonar el repositorio:**
   ```bash
   cd C:\xampp\htdocs
   git clone https://github.com/le925/Agenda360.git
   ```

2. **Iniciar servicios:**
   * Abrir el Panel de Control de XAMPP e iniciar los módulos **Apache** y **MySQL**.

3. **Importar la Base de Datos:**
   * Abrir en el navegador: [http://localhost/phpmyadmin/](http://localhost/phpmyadmin/).
   * Crear la base de datos `Agenda360` con cotejamiento `utf8mb4_unicode_ci`.
   * Seleccionar la base `Agenda360`, dirigirse a la pestaña **Importar**, seleccionar el archivo `Agenda360.sql` y hacer clic en **Importar**.

4. **Ejecutar la aplicación:**
   * Ingresar en el navegador a: [http://localhost/Agenda360/](http://localhost/Agenda360/)
   * Iniciar sesión con el usuario demo (`demo@agenda360.com` / `12345678`) o crear una nueva cuenta.

---

## 🌐 Instrucciones de Despliegue en Hosting Gratuito (InfinityFree)

1. **Crear cuenta y dominio:**
   * Se configuró la cuenta `if0_43508106` con el dominio `agenda360.infinityfree.io`.
2. **Crear la Base de Datos MySQL:**
   * En el panel de control de InfinityFree, dirigirse a **MySQL Databases**.
   * Se creó la base de datos `if0_43508106_Agenda360` en el host `sql305.infinityfree.com`.
3. **Importar Script SQL:**
   * Abrir phpMyAdmin desde el panel de InfinityFree.
   * Seleccionar la base `if0_43508106_Agenda360` e importar el archivo `Agenda360.sql`.
4. **Subir los Archivos:**
   * Acceder al **File Manager** (MonstaFTP) o mediante cliente FTP (FileZilla) con las credenciales de la cuenta.
   * Cargar el contenido del repositorio dentro de la carpeta `/htdocs/`.
5. **Verificación:**
   * Abrir [http://agenda360.infinityfree.io](http://agenda360.infinityfree.io) en cualquier dispositivo conectado a Internet.

---

