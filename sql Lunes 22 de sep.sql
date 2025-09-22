show databases;

use saatdevtest2;

show tables;


SHOW CREATE TABLE alum_asistencia2;
SHOW CREATE TABLE matriculas2;
SHOW CREATE TABLE alum_emergencia2;
SHOW CREATE TABLE alum_familia2;
SHOW CREATE TABLE alum_notas2;
SHOW CREATE TABLE alumnos2;
SHOW CREATE TABLE anios2;
SHOW CREATE TABLE antecedentes_familiares;
SHOW CREATE TABLE asignaturas2;
SHOW CREATE TABLE cursos2;
SHOW CREATE TABLE curso_asignaturas2;
SHOW CREATE TABLE roles2;
SHOW CREATE TABLE usuarios2;

-- |||||||||||||||||||||||||||||||||||||||||
--
-- 		Alumno Asistencia
-- 
-- |||||||||||||||||||||||||||||||||||||||||
CREATE TABLE `alum_asistencia2` (
`id` int NOT NULL AUTO_INCREMENT,
`matricula_id` int NOT NULL,
`fecha` date NOT NULL,
`presente` tinyint(1) DEFAULT '1',
`observaciones` varchar(255) DEFAULT NULL,
PRIMARY KEY (`id`),
KEY `idx_asistencia_matricula` (`matricula_id`),
CONSTRAINT `alum_asistencia2_ibfk_1` FOREIGN KEY (`matricula_id`) REFERENCES `matriculas2` (`id`) ON DELETE CASCADE
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
 
ALTER TABLE `alum_emergencia2`
MODIFY COLUMN `relacion` 
ENUM('Madre','Padre','Tutor Legal','Representante','Apoderado','Hermana/Hermano') 
COLLATE utf8mb4_unicode_ci 
DEFAULT NULL;

select * from alum_emergencia2;

UPDATE alum_emergencia2
SET relacion = 'Hermana/Hermano'
WHERE relacion = 'Hermana';



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
-- 		Notas
-- 
-- |||||||||||||||||||||||||||||||||||||||||
CREATE TABLE `alumnos2` (
`id` int NOT NULL AUTO_INCREMENT,
`run` varchar(20) NOT NULL,
`codver` VARCHAR(1) NOT NULL,
`nombre` varchar(100) NOT NULL,
`apepat` varchar(100) NOT NULL,
`apemat` varchar(100) NOT NULL,
`fechanac` date NOT NULL,
`mayoredad` enum('No','Si') NULL,
`numerohijos` int DEFAULT NULL,
`telefono` varchar(12) DEFAULT NULL,
`celular` varchar(8) DEFAULT NULL,
`email` varchar(100) DEFAULT NULL,
`sexo` enum('F','M') NOT NULL,
`created_at` DATE NOT NULL DEFAULT (CURRENT_DATE),
`nacionalidades` varchar(12) DEFAULT NULL,
`region` varchar(12) DEFAULT NULL,
`ciudad` varchar(12) DEFAULT NULL,
`cod_etnia` enum('Ninguna',
				'Mapuche',
                'Aymara',
                'Rapa Nui',
                'Lickan Antai (Atacameños)',
                'Quechua',
                'Colla',
                'Diaguita',
                'Chango',
                'Kawésqar',
                'Yagán',
                'Selk nam') NOT NULL DEFAULT 'Ninguna',
`deleted_at` DATE DEFAULT (CURRENT_DATE),
PRIMARY KEY (`id`),
UNIQUE KEY `run` (`run`)
);

select * from alumnos2;

ALTER TABLE alumnos2 
MODIFY COLUMN created_at DATE NOT NULL DEFAULT (CURRENT_DATE);


ALTER TABLE alumnos2
ADD COLUMN deleted_at date NULL AFTER `cod_etnia`;

ALTER TABLE alumnos2
    MODIFY codver VARCHAR(1);


ALTER TABLE alumnos2 
MODIFY sexo ENUM('Femenino','Masculino','Prefiere no identificar','Otro') NULL;

ALTER TABLE `alumnos2`
MODIFY COLUMN `sexo` 
ENUM('F','M')
DEFAULT NULL;

UPDATE alumnos2 
SET sexo = NULL 
WHERE id = 4;
select * from alumnos2;