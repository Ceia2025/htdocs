show databases;
create database saatdevtest2;
use saatdevtest2;

show tables;

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
`deleted_at` DATE NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `run` (`run`)
);



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