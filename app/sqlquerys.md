
show create table alum_asistencia2;
show create table alum_emergencia2;
show create table alum_familia2;
show create table alum_notas2;
show create table alumnos2;
show create table anios2;
show create table antecedente_escolar;
show create table antecedentes_familiares;
show create table asignaturas2;
show create table categorizacion;
show create table curso_asignaturas2;
show create table cursos2;
show create table estado_conservacion;
show create table horarios2;
show create table individualizacion;
show create table inventario;
show create table lugar_fisico;
show create table matriculas2;
show create table nivel_educativo;
show create table procedencia;
show create table profesor_curso_asignatura2;
show create table profesores2;
show create table roles2;
show create table suplencias2;
show create table usuarios2;
 



'alum_asistencia2', 'CREATE TABLE `alum_asistencia2` (\n  `id` int NOT NULL AUTO_INCREMENT,\n  `matricula_id` int NOT NULL,\n  `fecha` date NOT NULL,\n  `presente` tinyint(1) DEFAULT \'1\',\n  `observaciones` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,\n  PRIMARY KEY (`id`),\n  KEY `idx_asistencia_matricula` (`matricula_id`),\n  CONSTRAINT `alum_asistencia2_ibfk_1` FOREIGN KEY (`matricula_id`) REFERENCES `matriculas2` (`id`) ON DELETE CASCADE\n) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'


CREATE TABLE `alum_emergencia2` (
   `id` int NOT NULL AUTO_INCREMENT,
   `alumno_id` int DEFAULT NULL,
   `nombre_contacto` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   `telefono` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   `direccion` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   `relacion` enum('Madre','Padre','Tutor Legal','Representante','Apoderado','Hermana/Hermano') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   PRIMARY KEY (`id`),
   KEY `fk_emergencia_alumno` (`alumno_id`),
   CONSTRAINT `fk_emergencia_alumno` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos2` (`id`)
 ) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci


 CREATE TABLE `alum_familia2` (
   `id` int NOT NULL AUTO_INCREMENT,
   `alumno_id` int DEFAULT NULL,
   `nombre` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   `relacion` enum('Madre','Padre','Tutor Legal','Representante','Apoderado') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   `telefono` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   PRIMARY KEY (`id`),
   KEY `fk_familia_alumno` (`alumno_id`),
   CONSTRAINT `fk_familia_alumno` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos2` (`id`)
 ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci


 CREATE TABLE `alum_notas2` (
   `id` int NOT NULL AUTO_INCREMENT,
   `matricula_id` int NOT NULL,
   `asignatura_id` int NOT NULL,
   `semestre` tinyint(1) NOT NULL DEFAULT '1',
   `nota` decimal(4,2) DEFAULT NULL,
   `fecha` date DEFAULT NULL,
   PRIMARY KEY (`id`),
   KEY `asignatura_id` (`asignatura_id`),
   KEY `idx_notas_matricula` (`matricula_id`),
   CONSTRAINT `alum_notas2_ibfk_1` FOREIGN KEY (`matricula_id`) REFERENCES `matriculas2` (`id`) ON DELETE CASCADE,
   CONSTRAINT `alum_notas2_ibfk_2` FOREIGN KEY (`asignatura_id`) REFERENCES `asignaturas2` (`id`) ON DELETE CASCADE
 ) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci


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
   `cod_etnia` enum('No pertenece a ningún Pueblo Originario','Aymara','Likanantai( Atacameño )','Colla','Diaguita','Quechua','Rapa Nui','Mapuche','Kawésqar','Yagán','Otro','No Registra') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'No Registra',
   `deleted_at` datetime DEFAULT NULL,
   PRIMARY KEY (`id`),
   UNIQUE KEY `run` (`run`)
 ) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci


 CREATE TABLE `anios2` (
   `id` int NOT NULL AUTO_INCREMENT,
   `anio` year NOT NULL,
   `descripcion` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   PRIMARY KEY (`id`),
   UNIQUE KEY `anio` (`anio`)
 ) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci


 CREATE TABLE `antecedente_escolar` (
   `id` int NOT NULL AUTO_INCREMENT,
   `alumno_id` int DEFAULT NULL,
   `procedencia_colegio` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   `comuna` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   `ultimo_curso` enum('1ro basico','2do basico','3ro basico','4to basico','5to basico','6to basico','7mo basico','8vo basico','1ro medio','2do medio','3ro Medio','4to Medio') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   `ultimo_anio_cursado` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   `cursos_repetidos` int DEFAULT (0),
   `pertenece_20` tinyint(1) DEFAULT NULL,
   `informe_20` tinyint(1) DEFAULT NULL,
   `embarazo` tinyint(1) DEFAULT NULL,
   `semanas` int DEFAULT NULL,
   `info_salud` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   `eva_psico` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   `prob_apren` enum('Sin','Con','Desconocido') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   `pie` enum('Si','No','No se sabe') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   `chile_solidario` tinyint(1) DEFAULT NULL,
   `chile_solidario_cual` enum('Prioritario','Preferente','Incremento','Pro-Retención') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   `fonasa` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   `grupo_fonasa` enum('Ninguno','A','B','C','D') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   `isapre` enum('Ninguno','BANCA MEDICA','CRUZ BLANCA','COLMENA','MAS VIDA','CON SALUD','VIDA TRES','DIPRECA') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   `seguro_salud` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   PRIMARY KEY (`id`),
   KEY `fk_antecedente_alumno` (`alumno_id`),
   CONSTRAINT `fk_antecedente_alumno` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos2` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
 ) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci


 CREATE TABLE `antecedentes_familiares` (
   `id` int NOT NULL AUTO_INCREMENT,
   `alumno_id` int NOT NULL,
   `padre` enum('Basica Incompleta','Basica Completa','Media Incompleta','Media Completa','Técnico Incompleta','Técnico Completa','Superior Incompleta','Superior Completa','Desconocido') COLLATE utf8mb4_unicode_ci DEFAULT 'Desconocido',
   `nivel_ciclo_p` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   `madre` enum('Basica Incompleta','Basica Completa','Media Incompleta','Media Completa','Técnico Incompleta','Técnico Completa','Superior Incompleta','Superior Completa','Desconocido') COLLATE utf8mb4_unicode_ci DEFAULT 'Desconocido',
   `nivel_ciclo_m` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   PRIMARY KEY (`id`),
   KEY `fk_antecedentes_alumno` (`alumno_id`),
   CONSTRAINT `fk_antecedentes_alumno` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos2` (`id`)
 ) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci


 CREATE TABLE `asignaturas2` (
   `id` int NOT NULL AUTO_INCREMENT,
   `abreviatura` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
   `descp` varchar(800) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   PRIMARY KEY (`id`),
   UNIQUE KEY `nombre` (`nombre`)
 ) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci


 CREATE TABLE `categorizacion` (
   `id` int NOT NULL AUTO_INCREMENT,
   `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
   PRIMARY KEY (`id`)
 ) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci


 CREATE TABLE `curso_asignaturas2` (
   `id` int NOT NULL AUTO_INCREMENT,
   `curso_id` int NOT NULL,
   `asignatura_id` int NOT NULL,
   PRIMARY KEY (`id`),
   KEY `curso_id` (`curso_id`),
   KEY `asignatura_id` (`asignatura_id`),
   CONSTRAINT `curso_asignaturas2_ibfk_1` FOREIGN KEY (`curso_id`) REFERENCES `cursos2` (`id`),
   CONSTRAINT `curso_asignaturas2_ibfk_2` FOREIGN KEY (`asignatura_id`) REFERENCES `asignaturas2` (`id`)
 ) ENGINE=InnoDB AUTO_INCREMENT=109 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci


 CREATE TABLE `cursos2` (
   `id` int NOT NULL AUTO_INCREMENT,
   `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
   PRIMARY KEY (`id`)
 ) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci



 CREATE TABLE `estado_conservacion` (
   `id` int NOT NULL AUTO_INCREMENT,
   `nombre` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   PRIMARY KEY (`id`),
   UNIQUE KEY `nombre` (`nombre`)
 ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci


 CREATE TABLE `horarios2` (
   `id` int NOT NULL AUTO_INCREMENT,
   `pca_id` int NOT NULL,
   `dia_semana` enum('Lunes','Martes','Miércoles','Jueves','Viernes','Sábado') COLLATE utf8mb4_unicode_ci NOT NULL,
   `hora_inicio` time NOT NULL,
   `hora_fin` time NOT NULL,
   `sala` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   PRIMARY KEY (`id`),
   KEY `pca_id` (`pca_id`),
   CONSTRAINT `fk_horario_pca` FOREIGN KEY (`pca_id`) REFERENCES `profesor_curso_asignatura2` (`id`) ON DELETE CASCADE
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci


 CREATE TABLE `individualizacion` (
   `id` int NOT NULL AUTO_INCREMENT,
   `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
   `codigo_general` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
   `codigo_especifico` int NOT NULL,
   PRIMARY KEY (`id`)
 ) ENGINE=InnoDB AUTO_INCREMENT=389 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci


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
 ) ENGINE=InnoDB AUTO_INCREMENT=320 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci


 CREATE TABLE `lugar_fisico` (
   `id` int NOT NULL AUTO_INCREMENT,
   `nombre` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   PRIMARY KEY (`id`),
   UNIQUE KEY `nombre` (`nombre`)
 ) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci


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
 ) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci


 CREATE TABLE `nivel_educativo` (
   `id` int NOT NULL AUTO_INCREMENT,
   `nombre` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   PRIMARY KEY (`id`),
   UNIQUE KEY `nombre` (`nombre`)
 ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci


 CREATE TABLE `procedencia` (
   `id` int NOT NULL AUTO_INCREMENT,
   `tipo` enum('Inversión','Donación','Sin información') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   `donador_fondo` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   `fecha_adquisicion` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   PRIMARY KEY (`id`)
 ) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci


 CREATE TABLE `profesor_curso_asignatura2` (
   `id` int NOT NULL AUTO_INCREMENT,
   `profesor_id` int NOT NULL,
   `curso_id` int NOT NULL,
   `asignatura_id` int NOT NULL,
   `anio_id` int NOT NULL,
   `fecha_inicio` date DEFAULT NULL,
   `fecha_fin` date DEFAULT NULL,
   `horas_semanales` tinyint DEFAULT NULL,
   `es_jefe_curso` tinyint(1) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`),
   KEY `profesor_id` (`profesor_id`),
   KEY `curso_id` (`curso_id`),
   KEY `asignatura_id` (`asignatura_id`),
   KEY `anio_id` (`anio_id`),
   CONSTRAINT `fk_pca_anio` FOREIGN KEY (`anio_id`) REFERENCES `anios2` (`id`) ON DELETE CASCADE,
   CONSTRAINT `fk_pca_asignatura` FOREIGN KEY (`asignatura_id`) REFERENCES `asignaturas2` (`id`) ON DELETE CASCADE,
   CONSTRAINT `fk_pca_curso` FOREIGN KEY (`curso_id`) REFERENCES `cursos2` (`id`) ON DELETE CASCADE,
   CONSTRAINT `fk_pca_profesor` FOREIGN KEY (`profesor_id`) REFERENCES `profesores2` (`id`) ON DELETE CASCADE
 ) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci


 CREATE TABLE `profesores2` (
   `id` int NOT NULL AUTO_INCREMENT,
   `run` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
   `codver` varchar(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   `nombres` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
   `apepat` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
   `apemat` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   `email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   `telefono` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   `fecha_ingreso` date DEFAULT NULL,
   `fecha_salida` date DEFAULT NULL,
   `tipo` enum('titular','suplente') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'titular',
   `estado` enum('activo','inactivo') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'activo',
   `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
   `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
   PRIMARY KEY (`id`),
   UNIQUE KEY `run_unique` (`run`)
 ) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci


 CREATE TABLE `roles2` (
   `id` int NOT NULL AUTO_INCREMENT,
   `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
   PRIMARY KEY (`id`),
   UNIQUE KEY `nombre` (`nombre`)
 ) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci

CREATE TABLE `suplencias2` (
   `id` int NOT NULL AUTO_INCREMENT,
   `horario_id` int NOT NULL,
   `profesor_titular_id` int NOT NULL,
   `profesor_suplente_id` int NOT NULL,
   `fecha_desde` date NOT NULL,
   `fecha_hasta` date NOT NULL,
   `motivo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   PRIMARY KEY (`id`),
   KEY `horario_id` (`horario_id`),
   KEY `profesor_titular_id` (`profesor_titular_id`),
   KEY `profesor_suplente_id` (`profesor_suplente_id`),
   CONSTRAINT `fk_suplencia_horario` FOREIGN KEY (`horario_id`) REFERENCES `horarios2` (`id`) ON DELETE CASCADE,
   CONSTRAINT `fk_suplencia_prof_suplente` FOREIGN KEY (`profesor_suplente_id`) REFERENCES `profesores2` (`id`) ON DELETE CASCADE,
   CONSTRAINT `fk_suplencia_prof_titular` FOREIGN KEY (`profesor_titular_id`) REFERENCES `profesores2` (`id`) ON DELETE CASCADE
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci


 CREATE TABLE `usuarios2` (
   `id` int NOT NULL AUTO_INCREMENT,
   `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
   `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
   `rol_id` int NOT NULL,
   `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
   `ape_paterno` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   `ape_materno` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   `run` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
   `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
   `numero_telefonico` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
   `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
   PRIMARY KEY (`id`),
   UNIQUE KEY `username` (`username`),
   UNIQUE KEY `run` (`run`),
   UNIQUE KEY `numero_telefonico` (`numero_telefonico`),
   UNIQUE KEY `email` (`email`),
   KEY `rol_id` (`rol_id`),
   CONSTRAINT `usuarios2_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles2` (`id`)
 ) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci