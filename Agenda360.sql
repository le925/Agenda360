-- ==============================================================================
-- Script SQL para Agenda360 — Directorio Personal de Contactos
-- Compatible con MySQL / MariaDB (XAMPP Local y Hosting Gratuito InfinityFree)
-- ==============================================================================
-- INSTRUCCIONES DE IMPORTACIÓN:
-- 1. En XAMPP Local: Crea la base de datos `Agenda360` y luego importa este archivo.
-- 2. En InfinityFree: Ingresa a phpMyAdmin, selecciona la base de datos `if0_43508106_Agenda360`
--    e importa este archivo directamente en la pestaña "Importar" o "SQL".
-- ==============================================================================

-- 1. Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- 2. Tabla de categorías (Mejora: Opción C)
CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- Inserción de las 5 categorías oficiales requeridas por la rúbrica
INSERT INTO categorias (id, nombre) VALUES
(1, 'Familia'),
(2, 'Amigos'),
(3, 'Trabajo'),
(4, 'Universidad'),
(5, 'Otro')
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);

-- 3. Tabla de contactos con Categorías (Opción C), Favoritos (Opción D), y Fecha de Creación (Opción B)
DROP TABLE IF EXISTS contactos;

CREATE TABLE contactos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_categoria INT NOT NULL DEFAULT 5,
    nombre VARCHAR(150) NOT NULL,
    telefono VARCHAR(30) NOT NULL,
    correo VARCHAR(150) NOT NULL,
    ciudad VARCHAR(100) NOT NULL,
    favorito TINYINT(1) NOT NULL DEFAULT 0,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_contactos_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES usuarios(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_contactos_categoria
        FOREIGN KEY (id_categoria)
        REFERENCES categorias(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    INDEX idx_contactos_usuario (id_usuario),
    INDEX idx_contactos_categoria (id_categoria),
    INDEX idx_contactos_favorito (favorito),
    INDEX idx_contactos_fecha (fecha_creacion)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- 4. Usuario demo para pruebas de evaluación académica
-- Correo: demo@agenda360.com | Contraseña: demo123456
INSERT INTO usuarios (id, nombre, correo, password, fecha_registro) VALUES
(99, 'Usuario Demo UTPL', 'demo@agenda360.com', '$2y$10$Q2iiLNHP18HM1QzEVX6am.7jtumsrZ3rzQxFvusampGcHnSpyrZGW', NOW())
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);

-- 5. Contactos de prueba demostrativos asignados al usuario demo
INSERT INTO contactos (id_usuario, id_categoria, nombre, telefono, correo, ciudad, favorito, fecha_creacion) VALUES
(99, 1, 'Carlos Solis', '0991234567', 'carlos.solis@familia.ec', 'Loja', 1, NOW() - INTERVAL 10 DAY),
(99, 2, 'Ana Martínez', '0987654321', 'ana.martinez@amigos.com', 'Cuenca', 1, NOW() - INTERVAL 5 DAY),
(99, 3, 'Ing. Roberto Gómez', '0978901234', 'rgomez@empresa.com', 'Quito', 0, NOW() - INTERVAL 3 DAY),
(99, 4, 'Valeria Torres', '0965432109', 'vtorres@utpl.edu.ec', 'Loja', 1, NOW() - INTERVAL 1 DAY),
(99, 5, 'Dr. Patricio Andrade', '0954321987', 'pandrade@salud.gob.ec', 'Guayaquil', 0, NOW());
