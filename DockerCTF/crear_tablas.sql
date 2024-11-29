-- Crear la base de datos (si no existe)
CREATE DATABASE IF NOT EXISTS accounts;
USE accounts;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de retos
CREATE TABLE IF NOT EXISTS retos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero INT NOT NULL UNIQUE,
    titulo VARCHAR(255) NOT NULL,
    descripcion TEXT NOT NULL,
    flag VARCHAR(255) NOT NULL
);

-- Insertar ejemplos de retos
INSERT INTO retos (numero, titulo, descripcion, flag) VALUES
(1, 'Primer Reto', 'Encuentra la bandera escondida en la descripción: Flag está oculta.', 'FLAG{primer_reto}'),
(2, 'Segundo Reto', 'Sigue investigando, el desafío continúa. ¿Puedes encontrar la siguiente bandera?', 'FLAG{segundo_reto}');

