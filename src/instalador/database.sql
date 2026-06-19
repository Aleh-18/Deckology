-- ==========================================================
-- DECKOLOGY - SCRIPT DE INSTALACIÓN (VERSIÓN FINAL LIMPIA)
-- ==========================================================

--  CREACIÓN DE TABLAS
-- ----------------------------------------------------------

-- Tabla: usuarios
CREATE TABLE usuarios (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    rol ENUM('admin', 'player') NOT NULL DEFAULT 'player',
    puntuacion INT DEFAULT 0,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    reset_token CHAR(64) DEFAULT NULL,
    reset_expires DATETIME DEFAULT NULL
);

-- Tabla: iconos
CREATE TABLE iconos (
    id_icono INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    codigo VARCHAR(20) NOT NULL
);

-- Tabla: zonas
CREATE TABLE zonas (
    id_zona INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    imagenZona VARCHAR(255),
    imagenCartas VARCHAR(255),
    imagenEventos VARCHAR(255),
    fondoZona VARCHAR(255) 
);

-- Tabla: eventos
CREATE TABLE eventos (
    id_evento INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion VARCHAR(255),
    dano TINYINT UNSIGNED NOT NULL DEFAULT 0,
    turnos_duracion INT NOT NULL,
    id_zona INT NOT NULL,
    id_icono INT,
    esta_en_carta BOOLEAN NOT NULL DEFAULT 0,
    FOREIGN KEY (id_zona) REFERENCES zonas(id_zona) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_icono) REFERENCES iconos(id_icono) ON DELETE SET NULL ON UPDATE CASCADE
);

-- Tabla: cartas
CREATE TABLE cartas (
    id_carta INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion VARCHAR(255),
    curacion TINYINT UNSIGNED NOT NULL,
    id_zona INT NOT NULL,
    elimina_id_evento INT,
    id_icono INT,
    FOREIGN KEY (id_zona) REFERENCES zonas(id_zona) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (elimina_id_evento) REFERENCES eventos(id_evento) ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (id_icono) REFERENCES iconos(id_icono) ON DELETE SET NULL ON UPDATE CASCADE
);

-- ==========================================================
-- INSERCIÓN DE DATOS
-- ==========================================================

--  INSERTAR ICONOS (40 Total)
INSERT INTO iconos (nombre, codigo) VALUES
('Reciclaje', '♻️'), ('Árbol', '🌳'), ('Agua Limpia', '💧'), ('Sol', '☀️'), ('Viento', '🌬️'),
('Corazón', '❤️'), ('Personas', '👥'), ('Libro', '📖'), ('Ley', '⚖️'), ('Energía', '⚡'),
('Contaminación', '☠️'), ('Fuego', '🔥'), ('Basura', '🗑️'), ('Plástico', '🧴'), ('Advertencia', '⚠️'),
('Prohibido', '🚫'), ('Petróleo', '🛢️'), ('Temperatura', '🌡️'), ('Sequía', '🏜️'), ('Peligro', '☣️'),
('Animal', '🐾'), ('Pez', '🐟'), ('Hoja', '🍃'), ('Flor', '🌸'), ('Tierra', '🌍'),
('Nube', '☁️'), ('Arcoíris', '🌈'), ('Montaña', '⛰️'), ('Volcán', '🌋'), ('Tornado', '🌪️'),
('Lluvia', '🌧️'), ('Planta', '🌱'), ('Flor Blanca', '🌼'), ('Abeja', '🐝'), ('Pájaro', '🐦'),
('Ballena', '🐋'), ('Tortuga', '🐢'), ('Cactus', '🌵'), ('Isla', '🏝️'), ('Burbuja', '🫧');

--  INSERTAR ZONAS
INSERT INTO zonas (nombre, imagenZona, imagenCartas, imagenEventos, fondoZona) VALUES
('Bosque', '../imagenes/zonas/Bosque.png', '../imagenes/cartas/cartaBosque.png', '../imagenes/eventos/eventoBosque.png', '../imagenes/fondo/fondoBosque.png'),   -- ID 1
('Ciudad', '../imagenes/zonas/Ciudad.png', '../imagenes/cartas/cartaCiudad.png', '../imagenes/eventos/eventoCiudad.png', '../imagenes/fondo/fondoCiudad.png'),   -- ID 2
('Mar', '../imagenes/zonas/Mar.png', '../imagenes/cartas/cartaMar.png', '../imagenes/eventos/eventoMar.png', '../imagenes/fondo/fondoMar.png'),              -- ID 3
('Desierto', '../imagenes/zonas/Desierto.png', '../imagenes/cartas/cartaDesierto.png', '../imagenes/eventos/eventoDesierto.png', '../imagenes/fondo/fondoDesierto.png'), -- ID 4
('Infinito', '../imagenes/zonas/Infinito.png', NULL, NULL, '../imagenes/fondo/fondoInfinito.png'); 

-- Ajuste para la zona infinito (Mover ID 5 a ID 0)
-- Uso como bandera para el modo infinito
UPDATE zonas SET id_zona = 0 WHERE nombre = "Infinito";

--  INSERTAR EVENTOS (PROBLEMAS)
-- --- EVENTOS BOSQUE ---
INSERT INTO eventos (nombre, descripcion, dano, turnos_duracion, id_zona, id_icono, esta_en_carta) VALUES
('Plaga Forestal', 'Insectos que matan árboles debilitados por el estrés térmico.', 8, 4, 1, 20, 0),
('Fragmentación', 'Carreteras que aíslan poblaciones animales, impidiendo su reproducción.', 10, 5, 1, 16, 0),
('Vertidos Tóxicos', 'Químicos industriales filtrados que envenenan el agua subterránea.', 10, 3, 1, 20, 0),
('Suelo Erosionado', 'La tierra pierde su capa fértil y se vuelve estéril.', 8, 4, 1, 19, 0),
('Flora Invasora', 'Plantas exóticas que compiten deslealmente con las nativas.', 8, 4, 1, 15, 0),
('Desajuste Fenológico', 'Las plantas florecen antes de que lleguen sus polinizadores.', 15, 6, 1, 18, 0),
('Tala Ilegal', 'Mafias que extraen maderas preciosas sin control.', 15, 4, 1, 17, 0),
('Contaminación Lumínica', 'Luz artificial que desorienta aves y altera ciclos nocturnos.', 8, 3, 1, 10, 0),
('Mega-Incendio', 'Fuego de alta intensidad, imposible de apagar solo con agua.', 17, 3, 1, 12, 0),
('Monocultivo Verde', 'Plantaciones de una sola especie donde no vive fauna local.', 10, 5, 1, 11, 0);

-- --- EVENTOS CIUDAD ---
INSERT INTO eventos (nombre, descripcion, dano, turnos_duracion, id_zona, id_icono, esta_en_carta) VALUES
('Ansiedad Urbana', 'Estrés crónico derivado del ruido y la falta de pausa.', 12, 4, 2, 15, 0),
('Fast Fashion', 'Ropa de baja calidad que genera montañas de basura textil.', 15, 5, 2, 13, 0),
('Obsolescencia', 'Aparatos diseñados para romperse y forzar nueva compra.', 15, 3, 2, 11, 0),
('Aislamiento Social', 'Soledad no deseada y ruptura de los lazos vecinales.', 10, 4, 2, 7, 0),
('Comida Chatarra', 'Dieta ultraprocesada que daña la salud física y mental.', 12, 3, 2, 19, 0),
('Esmog Tóxico', 'Nube de polución que causa enfermedades respiratorias graves.', 15, 5, 2, 11, 0),
('Inundación Urbana', 'El asfalto impide que la tierra absorba la lluvia torrencial.', 15, 3, 2, 30, 0),
('Isla de Calor', 'El cemento retiene el calor, elevando la temperatura nocturna.', 10, 4, 2, 18, 0),
('Tráfico Colapsado', 'Congestión que roba tiempo de vida y genera gases nocivos.', 10, 3, 2, 16, 0),
('Publicidad Invasiva', 'Estímulos constantes para crear necesidades falsas.', 8, 4, 2, 9, 0);

-- --- EVENTOS MAR ---
INSERT INTO eventos (nombre, descripcion, dano, turnos_duracion, id_zona, id_icono, esta_en_carta) VALUES
('Acidificación', 'El exceso de CO2 vuelve el agua ácida, disolviendo conchas.', 8, 4, 3, 20, 0),
('Microplásticos', 'Partículas invisibles que confunden a los peces con alimento.', 15, 6, 3, 14, 0),
('Minería Submarina', 'Máquinas gigantes que remueven el fondo buscando metales.', 12, 3, 3, 11, 0),
('Blanqueamiento', 'El coral expulsa sus algas vitales por el calor del agua.', 15, 5, 3, 18, 0),
('Zona Muerta', 'Áreas sin oxígeno por culpa de fertilizantes agrícolas.', 12, 5, 3, 20, 0),
('Vertido de Crudo', 'Fugas de petróleo que impregnan aves y costas.', 27, 4, 3, 17, 0),
('Pesca Fantasma', 'Redes abandonadas que siguen atrapando animales por décadas.', 15, 5, 3, 13, 0),
('Pesca de Arrastre', 'Redes con plomos que destruyen el hábitat del fondo marino.', 22, 3, 3, 19, 0),
('Turismo Irresponsable', 'Anclas y buzos que rompen corales y molestan fauna.', 10, 3, 3, 15, 0),
('Especies Invasoras', 'Animales traídos en cascos de barcos que alteran el equilibrio.', 15, 4, 3, 12, 0);

-- --- EVENTOS DESIERTO ---
INSERT INTO eventos (nombre, descripcion, dano, turnos_duracion, id_zona, id_icono, esta_en_carta) VALUES
('Avance del Desierto', 'La arena invade tierras que antes eran fértiles.', 10, 5, 4, 19, 0),
('Tormenta de Polvo', 'Aire irrespirable que transporta enfermedades y arena.', 12, 3, 4, 30, 0),
('Salinización', 'Riego incorrecto que deja sales tóxicas en el suelo.', 12, 4, 4, 20, 0),
('Caza Furtiva', 'Matanza de animales raros para trofeos o medicina tradicional.', 22, 4, 4, 15, 0),
('Crisis Hídrica', 'Falta de agua potable por mala gestión y sequía extrema.', 17, 6, 4, 18, 0),
('Sobrepastoreo', 'Ganado que come las plantas antes de que den semillas.', 15, 4, 4, 21, 0),
('Acuífero Agotado', 'Pozos ilegales que extraen agua más rápido de lo que se recarga.', 22, 5, 4, 19, 0),
('Erosión Eólica', 'El viento se lleva la capa fértil de suelos desnudos.', 12, 4, 4, 5, 0),
('Éxodo Climático', 'Familias obligadas a emigrar porque la tierra ya no produce.', 12, 4, 4, 7, 0),
('Rally Off-Road', 'Vehículos recreativos que destrozan la superficie del desierto.', 10, 2, 4, 11, 0);


-- --- CARTAS BOSQUE (Soluciones 1-10) ---
INSERT INTO cartas (nombre, descripcion, curacion, id_zona, elimina_id_evento, id_icono) VALUES
('Control Biológico', 'Introducir insectos benéficos en lugar de pesticidas.', 12, 1, 1, 21),
('Corredor de Vida', 'Puentes naturales que reconectan zonas aisladas.', 12, 1, 2, 23),
('Bio-Remediación', 'Uso de hongos y bacterias para limpiar suelos tóxicos.', 10, 1, 3, 3),
('Agricultura Regenerativa', 'Técnicas que devuelven nutrientes y vida al suelo.', 14, 1, 4, 2),
('Restauración Nativa', 'Retirada manual de invasoras y siembra de especies locales.', 12, 1, 5, 7),
('Polinización Asistida', 'Ayuda manual y protección de colmenas silvestres.', 12, 1, 6, 26),
('Trazabilidad de Madera', 'Sellos tecnológicos que certifican que la madera es legal.', 12, 1, 7, 8),
('Cielos Oscuros', 'Regulación de luces LED y horarios de apagado nocturno.', 10, 1, 8, 4),
('Pastoreo Preventivo', 'Rebaños controlados que limpian el monte para evitar incendios.', 10, 1, 9, 21),
('Diversificación', 'Plantar mezcla de especies para crear un ecosistema real.', 10, 1, 10, 2);

-- --- CARTAS CIUDAD (Soluciones 11-20) ---
INSERT INTO cartas (nombre, descripcion, curacion, id_zona, elimina_id_evento, id_icono) VALUES
('Salud Mental Pública', 'Acceso gratuito a psicología y espacios verdes de calma.', 12, 2, 11, 6),
('Moda Sostenible', 'Fomento de ropa ética, duradera y de segunda mano.', 12, 2, 12, 1),
('Derecho a Reparar', 'Leyes que obligan a las empresas a permitir arreglos.', 15, 2, 13, 8),
('Centro Comunitario', 'Espacios vecinales gratuitos para combatir la soledad.', 14, 2, 14, 7),
('Huerto Urbano', 'Alimentos frescos locales y conexión con la naturaleza.', 10, 2, 15, 32),
('Transporte Verde', 'Movilidad eléctrica y pública masiva para limpiar el aire.', 15, 2, 16, 5),
('Ciudad Esponja', 'Pavimentos permeables que absorben el agua de lluvia.', 10, 2, 17, 3),
('Techo Verde', 'Jardines en azoteas que aíslan y refrescan el edificio.', 10, 2, 18, 23),
('Carril Bici', 'Infraestructura segura y conectada para bicicletas.', 12, 2, 19, 1),
('Consumo Consciente', 'Educación crítica para diferenciar necesidad de deseo.', 10, 2, 20, 8);

-- --- CARTAS MAR (Soluciones 21-30) ---
INSERT INTO cartas (nombre, descripcion, curacion, id_zona, elimina_id_evento, id_icono) VALUES
('Cultivo de Algas', 'Las macroalgas absorben CO2 y reducen la acidez local.', 10, 3, 21, 32),
('Prohibición Plásticos', 'Eliminación legal estricta de plásticos de un solo uso.', 12, 3, 22, 16),
('Tratado de Alta Mar', 'Protección legal internacional contra la minería submarina.', 12, 3, 23, 9),
('Super-Corales', 'Cría científica de corales resistentes a altas temperaturas.', 15, 3, 24, 22),
('Filtros Agrícolas', 'Barreras vegetales que evitan que nitratos lleguen al mar.', 14, 3, 25, 3),
('Esponjas Nano', 'Materiales avanzados que absorben solo aceite, no agua.', 15, 3, 26, 2),
('Buzos Recolectores', 'Equipos especializados en retirar redes fantasma del fondo.', 12, 3, 27, 7),
('Pesca Artesanal', 'Apoyo a pescadores locales que usan métodos sostenibles.', 15, 3, 28, 22),
('Boyas Ecológicas', 'Sistemas de amarre que flotan y no tocan el fondo marino.', 5, 3, 29, 3),
('Agua de Lastre Limpia', 'Tratamiento del agua de los barcos para no mover especies.', 10, 3, 30, 2);

-- --- CARTAS DESIERTO (Soluciones 31-40) ---
INSERT INTO cartas (nombre, descripcion, curacion, id_zona, elimina_id_evento, id_icono) VALUES
('Gran Muralla Verde', 'Barrera continental de árboles para frenar la arena.', 12, 4, 31, 2),
('Fijación de Dunas', 'Plantas pioneras que atrapan el suelo y cortan el viento.', 12, 4, 32, 23),
('Tecnología de Riego', 'Sistemas inteligentes por goteo que ahorran agua y evitan sal.', 10, 4, 33, 3),
('Ecoturismo Responsable', 'Ingresos alternativos para locales que sustituyen la caza.', 15, 4, 34, 15),
('Cosecha de Lluvia', 'Sistemas para capturar y almacenar cada gota que cae.', 12, 4, 35, 3),
('Ganadería Rotativa', 'Mover animales constantemente para regenerar el pasto.', 12, 4, 36, 21),
('Clausura de Pozos', 'Control legal estricto sobre la extracción de agua profunda.', 15, 4, 37, 9),
('Agricultura sin Labranza', 'Sembrar sin arar para proteger la estructura del suelo.', 8, 4, 38, 38),
('Ayuda Humanitaria', 'Refugios y recursos dignos para comunidades desplazadas.', 12, 4, 39, 6),
('Zonas de Exclusión', 'Áreas prohibidas para vehículos para permitir regeneración.', 8, 4, 40, 16);
-- Usuario admin por defecto (email: admin@deckology.local / contraseña: admin)
INSERT IGNORE INTO usuarios (nombre, contrasena, email, rol, puntuacion, fecha_registro) VALUES
('Admin', '$2y$10$5nkOMF0VCqeWB.qbr5m35.pbEUFh12Pz6.jnDO2fVDjqK2lyiWWru', 'admin@deckology.local', 'admin', 0, NOW());
-- Usuario jugador por defecto (email: jugador@deckology.local / contraseña: jugador)
INSERT IGNORE INTO usuarios (nombre, contrasena, email, rol, puntuacion, fecha_registro) VALUES
('jugador', '$2y$10$AGY3VtclRjqLrwWmh.5psOdSJrnl6IaNM2Gnb6/pZV.lCx8q/3xry', 'jugador@deckology.local', 'player', 0, NOW());
