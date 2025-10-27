show databases;
create database saatdevtest2;
use saatdevtest2;


select * from alum_emergencia2;

show tables;
select * from antecedentes_familiares;
--
select * from alum_familia2;

CREATE TABLE `anios2` (
   `id` int NOT NULL AUTO_INCREMENT,
   `anio` year NOT NULL,
   `descripcion` varchar(100) DEFAULT NULL,
   PRIMARY KEY (`id`),
   UNIQUE KEY `anio` (`anio`)
 );

SHOW CREATE TABLE anios2;
SHOW CREATE TABLE alum_asistencia2;
SHOW CREATE TABLE matriculas2;
SHOW CREATE TABLE alum_emergencia2;
SHOW CREATE TABLE alum_familia2;  
SHOW CREATE TABLE alum_notas2;
SHOW CREATE TABLE alumnos2;
SHOW CREATE TABLE antecedentes_familiares;
SHOW CREATE TABLE asignaturas2;
SHOW CREATE TABLE cursos2;
SHOW CREATE TABLE curso_asignaturas2;
SHOW CREATE TABLE roles2;
SHOW CREATE TABLE usuarios2;

select * from alum_familia2;

-- |||||||||||||||||||||||||||||||||||||||||
--
-- 		Alumno Años
-- 
-- |||||||||||||||||||||||||||||||||||||||||
CREATE TABLE `anios2` (
   `id` int NOT NULL AUTO_INCREMENT,
   `anio` year NOT NULL,
   `descripcion` varchar(100) DEFAULT NULL,
   PRIMARY KEY (`id`),
   UNIQUE KEY `anio` (`anio`)
 );
 
 




-- |||||||||||||||||||||||||||||||||||||||||
--
-- 		Matricula
-- 
-- |||||||||||||||||||||||||||||||||||||||||
CREATE TABLE `matriculas2` (
`id` int NOT NULL AUTO_INCREMENT,
`alumno_id` int NOT NULL,
`curso_id` int NOT NULL,
`anio_id` int NOT NULL,
`fecha_matricula` date DEFAULT NULL,
PRIMARY KEY (`id`),
KEY `anio_id` (`anio_id`),
KEY `idx_matricula_alumno` (`alumno_id`),
KEY `idx_matricula_curso` (`curso_id`),
CONSTRAINT `matriculas2_ibfk_1` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos2` (`id`) ON DELETE CASCADE,
CONSTRAINT `matriculas2_ibfk_2` FOREIGN KEY (`curso_id`) REFERENCES `cursos2` (`id`) ON DELETE CASCADE,
CONSTRAINT `matriculas2_ibfk_3` FOREIGN KEY (`anio_id`) REFERENCES `anios2` (`id`) ON DELETE CASCADE
);

-- |||||||||||||||||||||||||||||||||||||||||
--
-- 		Alumno Emergencia
-- 
-- |||||||||||||||||||||||||||||||||||||||||

CREATE TABLE `alum_emergencia2` (
   `id` int NOT NULL AUTO_INCREMENT,
   `alumno_id` int NULL,
   `nombre_contacto` varchar(100) NULL,
   `telefono` varchar(20) NULL,
   `direccion` varchar(70) NULL,
   `relacion` enum('Madre','Padre','Tutor Legal','Representante','Apoderado','Hermana/Hermano') DEFAULT NULL,
   PRIMARY KEY (`id`),
   KEY `fk_emergencia_alumno` (`alumno_id`),
   CONSTRAINT `fk_emergencia_alumno` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos2` (`id`)
 );


-- |||||||||||||||||||||||||||||||||||||||||
--
-- 		Alumno Asistencia
-- 
-- |||||||||||||||||||||||||||||||||||||||||

CREATE TABLE `alum_asistencia2`(
  `id` int NOT NULL AUTO_INCREMENT,
  `matricula_id` int NOT NULL,
  `fecha` date NOT NULL,
  `presente` tinyint(1) DEFAULT 1,
  `observaciones` varchar(255) NULL,
  PRIMARY KEY (`id`),
  KEY `idx_asistencia_matricula` (`matricula_id`),
  CONSTRAINT `alum_asistencia2_ibfk_1` FOREIGN KEY (`matricula_id`) REFERENCES `matriculas2` (`id`) ON DELETE CASCADE
  );
  
  select * from cursos2;
  
-- |||||||||||||||||||||||||||||||||||||||||
--
-- 		Alumno Familia
-- 
-- |||||||||||||||||||||||||||||||||||||||||
CREATE TABLE `alum_familia2` (
`id` int NOT NULL AUTO_INCREMENT,
`alumno_id` int DEFAULT NULL,
`nombre` varchar(100) DEFAULT NULL,
`relacion` enum('Madre','Padre','Tutor Legal','Representante','Apoderado') DEFAULT NULL,
`telefono` varchar(20) DEFAULT NULL,
`email` varchar(100) DEFAULT NULL,
PRIMARY KEY (`id`),
KEY `fk_familia_alumno` (`alumno_id`),
CONSTRAINT `fk_familia_alumno` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos2` (`id`)
);


-- |||||||||||||||||||||||||||||||||||||||||
--
-- 		Notas
-- 
-- |||||||||||||||||||||||||||||||||||||||||
CREATE TABLE `alum_notas2` (
`id` int NOT NULL AUTO_INCREMENT,
`matricula_id` int NOT NULL,
`asignatura_id` int NOT NULL,
`nota` decimal(4,2) DEFAULT NULL,
`fecha` date DEFAULT NULL,
PRIMARY KEY (`id`),
KEY `asignatura_id` (`asignatura_id`),
KEY `idx_notas_matricula` (`matricula_id`),
CONSTRAINT `alum_notas2_ibfk_1` FOREIGN KEY (`matricula_id`) REFERENCES `matriculas2` (`id`) ON DELETE CASCADE,
CONSTRAINT `alum_notas2_ibfk_2` FOREIGN KEY (`asignatura_id`) REFERENCES `asignaturas2` (`id`) ON DELETE CASCADE
);


-- |||||||||||||||||||||||||||||||||||||||||
--
-- 		Alumnos
-- 
-- |||||||||||||||||||||||||||||||||||||||||


CREATE TABLE `alumnos2` (
`id` int NOT NULL AUTO_INCREMENT,
`run` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
`codver` varchar(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
`apepat` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
`apemat` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`fechanac` date DEFAULT NULL,
`mayoredad` enum('No','Si') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`numerohijos` int DEFAULT NULL,
`telefono` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`celular` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`sexo` enum('F','M') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`created_at` date NOT NULL DEFAULT (curdate()),
`nacionalidades` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`region` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
`ciudad` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`direccion` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`cod_etnia` enum(
	'No pertenece a ningún Pueblo Originario',
    'Aymara',
    'Likanantai( Atacameño )',
    'Colla',
    'Diaguita',
    'Quechua',
    'Rapa Nui',
    'Mapuche',
    'Kawésqar',
    'Yagán',
    'Otro',
    'No Registra
    ') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'No Registra',
`deleted_at` datetime DEFAULT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `run` (`run`)
);

 
 -- |||||||||||||||||||||||||||||||||||||||||
 --
 -- 	Antecedentes escolares
 --
 -- |||||||||||||||||||||||||||||||||||||||||
 create table antecedente_escolar(
id int auto_increment primary key,
procedencia_colegio varchar(100) null,
comuna varchar(100) null,
ultimo_curso enum(	'1ro basico',
					'2do basico',
					'3ro basico',
					'4to basico',
					'5to basico',
					'6to basico',
					'7mo basico',
					'8vo basico',
					'1ro medio',
					'2do medio',
					'3ro Medio',
					'4to Medio'),
ultimo_anio_cursado varchar(4) null,
cursos_repetidos int null default(0),
pertenece_20 boolean null,
informe_20 boolean null,
embarazo boolean null,
semanas int null,
info_salud varchar(200) null,
eva_psico varchar(80) null,
prob_apren enum('Sin', 'Con', 'Desconocido') null,
pie enum('Si', 'No', 'No se sabe') null,
chile_solidario boolean null,
chile_solidario_cual enum('Prioritario', 'Preferente', 'Incremento', 'Pro-Retención') null,
fonasa varchar(30) null,
grupo_fonasa enum('Ninguno','A','B','C','D'),
isapre enum('Ninguno', 'BANCA MEDICA', 'CRUZ BLANCA', 'COLMENA', 'MAS VIDA', 'CON SALUD', 'VIDA TRES', 'DIPRECA' ) null,
seguro_salud varchar(30) null
);
 
 SELECT id, alumno_id, procedencia_colegio 
FROM antecedente_escolar 
WHERE alumno_id = 19;

SELECT id, alumno_id, procedencia_colegio, comuna 
FROM antecedente_escolar 
ORDER BY id DESC ;

SELECT id, alumno_id, procedencia_colegio, comuna FROM antecedente_escolar ORDER BY id DESC;


-- |||||||||||||||||||||||||||||||||||||||||
--
-- 		Antecedentes
-- 
-- |||||||||||||||||||||||||||||||||||||||||
CREATE TABLE `antecedentes_familiares` (
   `id` int NOT NULL AUTO_INCREMENT,
   `alumno_id` int NOT NULL,
   `padre` enum('Basica Incompleta','Basica Completa','Media Incompleta','Media Completa','Técnico Incompleta','Técnico Completa','Superior Incompleta','Superior Completa','Desconocido') DEFAULT 'Desconocido',
   `nivel_ciclo_p` varchar(40) DEFAULT NULL,
   `madre` enum('Basica Incompleta','Basica Completa','Media Incompleta','Media Completa','Técnico Incompleta','Técnico Completa','Superior Incompleta','Superior Completa','Desconocido') DEFAULT 'Desconocido',
   `nivel_ciclo_m` varchar(40) DEFAULT NULL,
   PRIMARY KEY (`id`),
   KEY `fk_antecedentes_alumno` (`alumno_id`),
   CONSTRAINT `fk_antecedentes_alumno` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos2` (`id`)
 );
 
 
-- |||||||||||||||||||||||||||||||||||||||||
--
-- 		Asignatura
-- 
-- |||||||||||||||||||||||||||||||||||||||||
 CREATE TABLE `asignaturas2` (
   `id` int NOT NULL AUTO_INCREMENT,
   `nombre` varchar(50) NOT NULL,
   `descp` varchar(200) DEFAULT NULL,
   PRIMARY KEY (`id`),
   UNIQUE KEY `nombre` (`nombre`)
 );
 
 -- |||||||||||||||||||||||||||||||||||||||||
--
-- 		Cursos
-- 
-- |||||||||||||||||||||||||||||||||||||||||
 CREATE TABLE `cursos2` (
   `id` int NOT NULL AUTO_INCREMENT,
   `nombre` varchar(50) NOT NULL,
   PRIMARY KEY (`id`)
 );
 
 
-- |||||||||||||||||||||||||||||||||||||||||
--
-- 		Cursos asignatura
-- 
-- |||||||||||||||||||||||||||||||||||||||||
CREATE TABLE `curso_asignaturas2` (
   `id` int NOT NULL AUTO_INCREMENT,
   `curso_id` int NOT NULL,
   `asignatura_id` int NOT NULL,
   PRIMARY KEY (`id`),
   KEY `curso_id` (`curso_id`),
   KEY `asignatura_id` (`asignatura_id`),
   CONSTRAINT `curso_asignaturas2_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos2` (`id`),
   CONSTRAINT `curso_asignaturas2_ibfk_2` FOREIGN KEY (`asignatura_id`) REFERENCES `asignaturas2` (`id`)
 );

-- |||||||||||||||||||||||||||||||||||||||||
--
-- 		Roles
-- 
-- |||||||||||||||||||||||||||||||||||||||||
CREATE TABLE `roles2` (
   `id` int NOT NULL AUTO_INCREMENT,
   `nombre` varchar(50) NOT NULL,
   PRIMARY KEY (`id`),
   UNIQUE KEY `nombre` (`nombre`)
 );

-- |||||||||||||||||||||||||||||||||||||||||
--
-- 		Usuarios
-- 
-- |||||||||||||||||||||||||||||||||||||||||
CREATE TABLE `usuarios2` (
   `id` int NOT NULL AUTO_INCREMENT,
   `username` varchar(50) NOT NULL,
   `password` varchar(255) NOT NULL,
   `rol_id` int NOT NULL,
   `nombre` varchar(100) NOT NULL,
   `ape_paterno` varchar(30) DEFAULT NULL,
   `ape_materno` varchar(30) DEFAULT NULL,
   `run` varchar(12) NOT NULL,
   `email` varchar(100) DEFAULT NULL,
   `numero_telefonico` varchar(12) NOT NULL,
   `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
   PRIMARY KEY (`id`),
   UNIQUE KEY `username` (`username`),
   UNIQUE KEY `run` (`run`),
   UNIQUE KEY `numero_telefonico` (`numero_telefonico`),
   UNIQUE KEY `email` (`email`),
   KEY `rol_id` (`rol_id`),
   CONSTRAINT `usuarios2_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles2` (`id`)
 );
 
 
 
 -- Tablas de inventario para el colegio, agregados el proyecto saat
 
 CREATE TABLE nivel_educativo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) UNIQUE
);

insert into nivel_educativo(nombre) values('Parvularia');
insert into nivel_educativo(nombre) values('Básica');
insert into nivel_educativo(nombre) values('Media');
insert into nivel_educativo(nombre) values('No Aplica');

CREATE TABLE `individualizacion` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `nombre` VARCHAR(255) NOT NULL,
    `codigo_general` VARCHAR(50) NOT NULL,
    `codigo_especifico` INT NOT NULL,
    PRIMARY KEY (`id`)
);

INSERT INTO individualizacion (descripcion) VALUES
('Silla - estructura fierro con tapiz cuerina'),
('Silla - ejecutiva con ruedas'),
('Mesa - redonda fierro melamina'),
('Mesa - estudiante metal madera tipo 5'),
('Escritorio - madera 2 cajones'),
('Notebook - HP 240 G7'),
('Notebook - Lenovo E-4155'),
('Computador de escritorio - HP modelo 280'),
('Monitor - Samsung B1630N'),
('Monitor - HP V194'),
('Impresora multifuncional - Epson L380'),
('Impresora multifuncional - Brother DCP-T500W'),
('Proyector - NEC NP-VE303'),
('Parlante - Channel 2.1 TV Soundbar'),
('Estufa - a gas marca Kendal con cilindro'),
('Pizarra acrílica - grande'),
('Extintor'),
('Basurero - metálico redondo'),
('Refrigerador - Daewoo 2 puertas'),
('Horno eléctrico - Thomas grande'),
('Ventilador - pedestal marca Groven'),
('Telón - con base Dinon'),
('Camilla - fierro con cuerina'),
('Cámara - Sony HDR-CX440'),
('Máquina de escribir - Underwood'),
('Guitarra acústica - Mercury'),
('Micrófono - Carverpro PCT18'),
('Tablet - Lenovo TB 8505'),
('Router - TP-Link'),
('Escalera - aluminio 3 peldaños'),
('Caja de herramientas - juegos de llaves 14 piezas marca Force');


-- asegurar que no se repitan codigo general + codigo especifico 
ALTER TABLE categorizacion 
ADD UNIQUE KEY unique_codigo (codigo_general, codigo_especifico);


    

insert into categorizacion(nombre) values('Implementos deportivos');
insert into categorizacion(nombre) values('Implementos de laboratorio');
insert into categorizacion(nombre) values('Instrumentos musicales y/o artísticos');
insert into categorizacion(nombre) values('Libros y revistas');
insert into categorizacion(nombre) values('Equipos informáticos');
insert into categorizacion(nombre) values('Equipos multicopiadores');
insert into categorizacion(nombre) values('Equipos de amplificación y sonido');
insert into categorizacion(nombre) values('Equipos de climatización: calefacción, ventilación y aire acondicionado');
insert into categorizacion(nombre) values('Útiles escolares, equipamiento especializado de Liceos Técnicos Profesionales o materiales de oficina');
insert into categorizacion(nombre) values('Materiales y útiles de aseo');
insert into categorizacion(nombre) values('Mobiliario escolar fuera de la sala de clase');
insert into categorizacion(nombre) values('Mobiliario escolar dentro de la sala de clase');
insert into categorizacion(nombre) values('Mobiliario no pedagógico o de oficina');
insert into categorizacion(nombre) values('Otros equipos, materiales o insumos');

CREATE TABLE estado_conservacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) UNIQUE
);

insert into estado_conservacion(nombre) values('Nuevo');
insert into estado_conservacion(nombre) values('Con uso');
insert into estado_conservacion(nombre) values('Descartable');

CREATE TABLE lugar_fisico (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) UNIQUE
);

insert into lugar_fisico(nombre) values('recepción');
insert into lugar_fisico(nombre) values('oficina portería');
insert into lugar_fisico(nombre) values('sala computación');
insert into lugar_fisico(nombre) values('Oficina Director');
insert into lugar_fisico(nombre) values('Sala de archivos');
insert into lugar_fisico(nombre) values('sala taller párvulo');
insert into lugar_fisico(nombre) values('oficina SIGE');
insert into lugar_fisico(nombre) values('Inspectoría');
insert into lugar_fisico(nombre) values('sala de profesores');
insert into lugar_fisico(nombre) values('Bodega sala de profesores');
insert into lugar_fisico(nombre) values('U.T.P');
insert into lugar_fisico(nombre) values('Comedor Funcionarios');
insert into lugar_fisico(nombre) values('Bodega cocina');
insert into lugar_fisico(nombre) values('Biblioteca');
insert into lugar_fisico(nombre) values('Bodega Biblioteca');
insert into lugar_fisico(nombre) values('Cuarto Eléctrico');
insert into lugar_fisico(nombre) values('Tercero eléctrico ');
insert into lugar_fisico(nombre) values('P.I.E');
insert into lugar_fisico(nombre) values('Bodega de Útiles');
insert into lugar_fisico(nombre) values('Laboratorio');
insert into lugar_fisico(nombre) values('Auditorio');
insert into lugar_fisico(nombre) values('Comedor Estudiantes');
insert into lugar_fisico(nombre) values('Sala Tercero Párvulo');
insert into lugar_fisico(nombre) values('Bodega Sur ');
insert into lugar_fisico(nombre) values('7° y 8°');
insert into lugar_fisico(nombre) values('Bodega S.A.A.T.');
insert into lugar_fisico(nombre) values('Baño Personal establecimiento');
insert into lugar_fisico(nombre) values('Taller de eléctricidad número 1');
insert into lugar_fisico(nombre) values('Taller de eléctricidad número 2');
insert into lugar_fisico(nombre) values('Enfermería y sala de contención');
insert into lugar_fisico(nombre) values('1° y 2° Eléctrico');
insert into lugar_fisico(nombre) values('Taller eléctrico');
insert into lugar_fisico(nombre) values('Taller oficio');
insert into lugar_fisico(nombre) values('Patio');




CREATE TABLE procedencia (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo ENUM('Inversión','Donación', 'Sin información'),
    donador_fondo VARCHAR(100),
    fecha_adquisicion varchar(50)
);


CREATE TABLE `inventario` (
   `id` int NOT NULL AUTO_INCREMENT,
   `nivel_id` int NOT NULL,
   `individualizacion_id` int NOT NULL,
   `categorizacion_id` int NOT NULL,
   `cantidad` int DEFAULT '1',
   `estado_id` int NOT NULL,
   `lugar_id` int NOT NULL,
   `procedencia_id` int NOT NULL,
   PRIMARY KEY (`id`),
   KEY `nivel_id` (`nivel_id`),
   KEY `estado_id` (`estado_id`),
   KEY `lugar_id` (`lugar_id`),
   KEY `procedencia_id` (`procedencia_id`),
   KEY `inventario_ibfk_2` (`individualizacion_id`),
   KEY `inventario_ibfk_3` (`categorizacion_id`),
   CONSTRAINT `inventario_ibfk_1` FOREIGN KEY (`nivel_id`) REFERENCES `nivel_educativo` (`id`),
   CONSTRAINT `inventario_ibfk_2` FOREIGN KEY (`individualizacion_id`) REFERENCES `individualizacion` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
   CONSTRAINT `inventario_ibfk_3` FOREIGN KEY (`categorizacion_id`) REFERENCES `categorizacion` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
   CONSTRAINT `inventario_ibfk_4` FOREIGN KEY (`estado_id`) REFERENCES `estado_conservacion` (`id`),
   CONSTRAINT `inventario_ibfk_5` FOREIGN KEY (`lugar_id`) REFERENCES `lugar_fisico` (`id`),
   CONSTRAINT `inventario_ibfk_6` FOREIGN KEY (`procedencia_id`) REFERENCES `procedencia` (`id`)
 );

select * from inventario;

drop table inventario;

show create table inventario;
select * from inventario;


CREATE TABLE categorizacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descripcion VARCHAR(255) NOT NULL
);

insert into categorizacion(descripcion) values('Implementos deportivos');
insert into categorizacion(descripcion) values('Implementos de laboratorio');
insert into categorizacion(descripcion) values('Instrumentos musicales y/o artíscos');
insert into categorizacion(descripcion) values('Libros y revistas');
insert into categorizacion(descripcion) values('Equipos informática');
insert into categorizacion(descripcion) values('Equipos multicopiadores');
insert into categorizacion(descripcion) values('Equipos de amplificación y sonido');
insert into categorizacion(descripcion) values('Equipos de climatización: calfación, ventilación, y aire acondicionado');
insert into categorizacion(descripcion) values('Útiles escolares, equipamiento especializados de Liceos Técnico Profecionales o material de oficina');
insert into categorizacion(descripcion) values('Materiales y útiles de aseo');
insert into categorizacion(descripcion) values('Mobiliario escolar fuera de la sala de clase');
insert into categorizacion(descripcion) values('Mobiliario escolar dentro de la sala de clase');
insert into categorizacion(descripcion) values('Mobiliario no pedagógico o de oficina');
insert into categorizacion(descripcion) values('Otros equipos, materiales o insumos');


RENAME TABLE individualizacion TO individualizacion2;
RENAME TABLE categorizacion TO categorizacion2;
RENAME TABLE individualizacion2 TO categorizacion;
RENAME TABLE categorizacion2 TO individualizacion;

CREATE TABLE individualizacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50),
    codigo_general VARCHAR(20) NOT NULL,
    codigo_especifico INT NOT NULL
);

select * from inventario;

select * from  categorizacion;
-- asegurar que no se repitan codigo general + codigo especifico 
ALTER TABLE categorizacion 
ADD UNIQUE KEY unique_codigo (codigo_general, codigo_especifico);


select * from individualizacion;
select * from categorizacion;


select * from antecedente_escolar;

drop table  antecedente_escolar;

