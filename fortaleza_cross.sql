-- MySQL dump 10.13  Distrib 8.0.43, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: fortaleza_cross
-- ------------------------------------------------------
-- Server version	8.0.43

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `bloque_entrenamiento`
--

DROP TABLE IF EXISTS `bloque_entrenamiento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bloque_entrenamiento` (
  `id_bloque_entrenamiento` int NOT NULL AUTO_INCREMENT,
  `id_entrenamiento_fk` int DEFAULT NULL,
  `nombre` text,
  `orden` int DEFAULT NULL,
  `descripcion` text,
  PRIMARY KEY (`id_bloque_entrenamiento`),
  KEY `id_entrenamiento_fk_idx` (`id_entrenamiento_fk`),
  CONSTRAINT `id_entrenamiento_fk` FOREIGN KEY (`id_entrenamiento_fk`) REFERENCES `entrenamientos` (`id_entrenamiento`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bloque_entrenamiento`
--

LOCK TABLES `bloque_entrenamiento` WRITE;
/*!40000 ALTER TABLE `bloque_entrenamiento` DISABLE KEYS */;
INSERT INTO `bloque_entrenamiento` VALUES (1,10,'Deadlifts',1,'Ejercicio 1:15 Deadlifts (con ketbell)'),(2,10,'push press por brazo',2,'Ejercicio 2:10 push press por brazo(con ketbell)'),(3,10,'sit up',3,'Ejercicio 3:15 sit up(con disco)'),(4,11,'hollow rock',1,'Ejercicio 1:20 hollow rock (con disco)'),(5,11,'V-ups',2,'Ejercicio 20 :V-ups'),(6,11,'mountan climbers',3,'Ejercicio 3:4 mountan climbers'),(7,12,'saltos simples',1,'Ejercicio 1: 50 saltos simples (soga)'),(8,12,'hang squat clean',2,'Ejercicio 2: 15 hang squat clean (H: 45kg / M: 35kg)'),(9,12,'push press',3,'Ejercicio 3: 10 push press (H: 45kg / M: 35kg)'),(10,13,'burpes',1,'Ejercicio 1:10 burpes'),(11,13,'god morning',2,'Ejercicio 3:15 god morning'),(12,14,'sentadilla con la barra atras',1,'Ejercicio 1:5x5 con el 50% del rm'),(13,14,'sentadilla con la barra atras',2,'Ejercicio 2:3x5 con el 60% del rm'),(14,15,'front squat',1,'10 front squat'),(15,15,'push jerks',2,'10 push jerks'),(16,15,'hang power clean',3,'10 hang power clean'),(17,15,'burpe bar',4,'7 burpe bar(saltando la barra de costado)'),(18,16,'squat jump',1,'Ejercicio 1:15 squat jump'),(19,16,'walk out ',2,'Ejercicio 2:walk out 7'),(20,17,'hollow rock',1,'Ejercicio 1:hollow rock'),(21,17,'superman rock',2,'Ejercicio 2:superman rock'),(22,18,'5squat',1,'Ejercicio 1: 5squat(peso corporal)'),(23,18,'sit ups',2,'Ejercicio 2:10 sit ups'),(24,18,'yoga push ups',3,'Ejercicio 3:10 yoga push ups'),(25,19,'squat clean',1,'Ejercicio 1: 7 hang squat clean'),(26,19,'front sqaut',2,'Ejercicio 2:7 front sqaut'),(27,19,'push jerks',3,'Ejercicio 3:7 push jerks'),(28,20,'escalera desendente del 10 al 1',1,'Entrada en calor : escalera desendente del 10 al 1'),(29,20,'suicidios',2,'Ejercicio 1: suicidios'),(30,20,'burpes',3,'Ejercicio 2: burpes'),(31,20,'step box',4,'Ejercicio 3: step box'),(32,21,'mts run',1,'Ejercicio 1:2000 mts run'),(33,21,'burpe box jump over',2,'Ejercicio 2: 30 burpe box jump over'),(34,21,'wall ball',3,'Ejercicio 3: 30 wall ball'),(35,21,'power clean',4,'Ejercicio 4:30 power clean'),(36,21,'burpe box jump over',5,'Ejercicio 5: 30 burpe box jump over'),(37,22,'Fuerza ',1,'(Ejercicios principales de pecho y hombro)\n\nPress de banca con barra: 4 series de 6-8 repeticiones. Es un ejercicio fundamental para la fuerza del pectoral.\n\nPress militar con mancuernas: 4 series de 8-10 repeticiones. Ideal para el desarrollo de los hombros.\n\nPress inclinado con mancuernas: 3 series de 10-12 repeticiones. Para enfocarte en la parte superior del pecho.'),(38,22,'Hipertrofia',2,'(Ejercicios para aislamiento y bombeo)\n\nAperturas con mancuernas en banco plano: 3 series de 12-15 repeticiones. Un ejercicio de aislamiento para el pecho.\n\nElevaciones laterales con mancuernas: 3 series de 12-15 repeticiones. Para dar amplitud a los hombros.\n\nPress francés o extensiones de tríceps en polea: 3 series de 10-12 repeticiones. Para trabajar el tríceps de forma aislada.'),(39,22,'Finalización',3,'(Ejercicios de resistencia y definición)\n\nFondos en paralelas (si puedes) o flexiones: 3 series hasta el fallo muscular.\n\nElevaciones frontales con mancuernas: 3 series de 15 repeticiones.\n\nFondos en banco para tríceps: 3 series hasta el fallo.'),(40,23,'Fuerza',1,'(Ejercicios de espalda de gran impacto)\n\nDominadas o jalón al pecho en máquina: 4 series de 6-8 repeticiones (o hasta el fallo si haces dominadas).\n\nRemo con barra (remo Pendlay): 4 series de 8-10 repeticiones. Un ejercicio clave para la fuerza y el grosor de la espalda.\n\nRemo con mancuerna a una mano: 3 series de 10-12 repeticiones por cada brazo.'),(41,23,'Hipertrofia',2,'(Ejercicios para el ancho de la espalda y bíceps)\n\nJalón al cuello o jalón con agarre neutro: 3 series de 12-15 repeticiones.\n\nRemo en polea baja con agarre cerrado: 3 series de 12-15 repeticiones.\n\nCurl de bíceps con barra EZ: 3 series de 10-12 repeticiones.'),(42,23,'Finalización',3,'(Ejercicios para detalles y agarre)\n\nFace pulls: 3 series de 15-20 repeticiones. Para la parte posterior del hombro y el manguito rotador.\n\nCurl martillo con mancuernas: 3 series de 12-15 repeticiones.\n\nEncogimiento de hombros con mancuernas: 3 series de 15-20 repeticiones. Para los trapecios.'),(43,24,'Fuerza',1,'(Ejercicios compuestos para el tren inferior)\n\nSentadilla con barra: 4 series de 6-8 repeticiones. El rey de los ejercicios de pierna.\n\nPeso muerto rumano con barra: 4 series de 8-10 repeticiones. Para el desarrollo de isquiotibiales y glúteos.\n\nPrensa de piernas (Leg Press): 3 series de 10-12 repeticiones.'),(44,24,'Hipertrofia',2,'(Ejercicios para aislar los grupos musculares)\n\nZancadas con mancuernas: 3 series de 10-12 repeticiones por cada pierna.\n\nHip Thrust con barra: 3 series de 10-12 repeticiones. Uno de los mejores para los glúteos.\n\nCurl femoral tumbado o sentado: 3 series de 12-15 repeticiones. Para los isquiotibiales.'),(45,24,'Finalización',3,'(Ejercicios para detalles y bombeo)\n\nExtensiones de cuádriceps: 3 series de 15-20 repeticiones. Para aislar el cuádriceps.\n\nSentadilla búlgara: 3 series de 10 repeticiones por cada pierna.\n\nElevación de talones en máquina o de pie: 4 series de 15-20 repeticiones. Para las pantorrillas.');
/*!40000 ALTER TABLE `bloque_entrenamiento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clases`
--

DROP TABLE IF EXISTS `clases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clases` (
  `id_clase` int NOT NULL AUTO_INCREMENT,
  `id_entrenamiento` int DEFAULT NULL,
  `dia_semana` varchar(50) DEFAULT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_fin` time DEFAULT NULL,
  `id_entrenador` int DEFAULT NULL,
  PRIMARY KEY (`id_clase`),
  KEY `id_entrenamiento` (`id_entrenamiento`),
  KEY `id_entrenador` (`id_entrenador`),
  CONSTRAINT `clases_ibfk_1` FOREIGN KEY (`id_entrenamiento`) REFERENCES `entrenamientos` (`id_entrenamiento`),
  CONSTRAINT `clases_ibfk_2` FOREIGN KEY (`id_entrenador`) REFERENCES `entrenadores` (`id_entrenador`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clases`
--

LOCK TABLES `clases` WRITE;
/*!40000 ALTER TABLE `clases` DISABLE KEYS */;
INSERT INTO `clases` VALUES (1,10,'Lunes','14:00:00','22:00:00',2),(2,11,'Lunes','14:00:00','22:00:00',2),(3,12,'Lunes','14:00:00','22:00:00',2),(4,13,'Martes','14:00:00','22:00:00',2),(5,14,'Martes','14:00:00','22:00:00',2),(6,15,'Martes','14:00:00','22:00:00',2),(7,16,'Miercoles','14:00:00','22:00:00',2),(8,17,'Miercoles','14:00:00','22:00:00',2),(12,18,'Jueves','14:00:00','00:00:22',2),(13,19,'Jueves','14:00:00','22:00:00',2),(14,20,'Viernes','14:00:00','22:00:00',2),(15,21,'Viernes','14:00:00','22:00:00',2),(16,22,'Lunes-Martes-Jueves','14:00:00','22:00:00',2),(17,23,'Miercoles-Viernes','18:00:00','22:00:00',2),(18,24,'Lunes-Viernes','20:00:00','22:00:00',2);
/*!40000 ALTER TABLE `clases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes` (
  `id_cliente` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `dni` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `fecha_alta` date DEFAULT NULL,
  `genero` enum('Masculino','Femenino','Otro') DEFAULT NULL,
  `estado` enum('Activo','Inactivo') DEFAULT NULL,
  `id_usuario_fk` int DEFAULT NULL,
  PRIMARY KEY (`id_cliente`),
  UNIQUE KEY `dni_UNIQUE` (`dni`),
  KEY `id_usuario_fk_idx` (`id_usuario_fk`),
  CONSTRAINT `id_usuario_fk` FOREIGN KEY (`id_usuario_fk`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` VALUES (30,'Elias','Ramirez','42746919','eliasrami1111@gmail.com','3644222296','2000-07-25','','2025-09-05','Otro','Activo',33),(100,'Fernando','Torres','12345678','miusiccristin@gmail.com','3644332211','1992-01-01',NULL,'2025-01-01','Masculino','Activo',100),(102,'Josué','Ramírez','40607763','jr1703825@gmail.com','3644609703','1997-08-29','','2025-09-25','Otro','Inactivo',102),(107,'Karen','Beliz','42922741','lizabeliz878@gmail.com','3644340211','2000-08-15','','2025-09-26','Otro','Activo',107),(108,'Ariel','Paszco','26606550','marketingdigital.gap@gmail.com','3644558877','1986-09-26','','2025-09-26','Otro','Activo',108),(113,'Editado','Ultimo','12345212','borrar@gmail.com','1232342343','2000-10-20',NULL,'2025-09-25','Otro','Inactivo',1004),(117,'gabriel','elizondo','41255028','gabrielelizondo241998@gmail.com','3633667788','1998-05-24','','2025-11-28','Otro','Activo',1008),(118,'Matias','Sotelo','42746914','gamepelyelias@gmail.com','3644786655','2010-06-11','','2025-12-17','Otro','Activo',1009),(119,'Maria ','Morreira','23564578','maria@gmail.com','3644578976','2002-07-26',NULL,'2025-12-17','Masculino','Activo',1010);
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `entrenadores`
--

DROP TABLE IF EXISTS `entrenadores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `entrenadores` (
  `id_entrenador` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `apellido` varchar(50) DEFAULT NULL,
  `especialidad` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `id_usuario_fk` int DEFAULT NULL,
  PRIMARY KEY (`id_entrenador`),
  KEY `id_usuario_fk_idx` (`id_usuario_fk`),
  CONSTRAINT `id_usuario_fkk` FOREIGN KEY (`id_usuario_fk`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entrenadores`
--

LOCK TABLES `entrenadores` WRITE;
/*!40000 ALTER TABLE `entrenadores` DISABLE KEYS */;
INSERT INTO `entrenadores` VALUES (2,'Fernandez','Torres','Profesor Educacion FIsica','3644111111','miusiccristin@gmail.com',100);
/*!40000 ALTER TABLE `entrenadores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `entrenamientos`
--

DROP TABLE IF EXISTS `entrenamientos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `entrenamientos` (
  `id_entrenamiento` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` text,
  `duracion_minutos` int DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `id_entrenador` int DEFAULT NULL,
  `video` text,
  PRIMARY KEY (`id_entrenamiento`),
  KEY `id_entrenador` (`id_entrenador`),
  CONSTRAINT `entrenamientos_ibfk_1` FOREIGN KEY (`id_entrenador`) REFERENCES `entrenadores` (`id_entrenador`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entrenamientos`
--

LOCK TABLES `entrenamientos` WRITE;
/*!40000 ALTER TABLE `entrenamientos` DISABLE KEYS */;
INSERT INTO `entrenamientos` VALUES (10,'Entrada en Calor Lunes','Entrada en calor: 3 rondas',20,10000.00,2,'https://youtu.be/iqBkUGKd4Eo'),(11,'Correr/trotar','CORE(zona media) : 3 rondas',20,10000.00,2,'https://youtu.be/T3eVbej-qes'),(12,'Pesa Liviana','WOD (Trabajo del día) For time: 5 rondas',20,10000.00,2,'https://youtu.be/HfymNGgl4Is'),(13,'Entrada en calor Marte','Tipo: Entrada en calor: AMRAP(10 minutos)',20,10000.00,2,'https://youtu.be/AlHVk1uCIQU'),(14,'Bloque de Fuerza','Bloque de Fuerza :back squat(sentadilla con la barra atras)',20,10000.00,2,'https://youtu.be/3z8EjqNsuog'),(15,'EMON 25 minutos','WOD EMON 25 minutos:(trabajo por tiempo de 1 minuto )',25,10000.00,2,'https://youtu.be/mp0AtsXvJ4c'),(16,'Entrada en Calor Miercoles','Entrada en calor:3 rondas',20,10000.00,2,'https://youtu.be/AlHVk1uCIQU'),(17,'Tableta 8 ronda','core :tabata 8 rondas(20 segundos de trabajo y 10 segundos de descanso)',20,10000.00,2,'https://www.youtube.com/shorts/P0Q9POIXOOI?feature=share'),(18,'Entrada en Calor Jueves','Entrada en calor : 5 rondas',20,10000.00,2,'https://youtu.be/AlHVk1uCIQU'),(19,'Complex 3','WOD 1 :Complex 3 rondas (barra con peso: mujeres:40 kg,hombres 60kg)',20,10000.00,2,'https://www.youtube.com/shorts/hQAHARKqsMQ?feature=share'),(20,'Entrada en Calor Viernes','Entrada en calor : escalera desendente del 10 al 1',20,10000.00,2,'https://youtu.be/AlHVk1uCIQU'),(21,'CHIPPERS 35 minutos','WOD CHIPPERS 35 minutos(a completar )',20,10000.00,2,'https://youtu.be/U5lV7oPW3CA'),(22,'Empuje','Entrenamiento 1: Empuje (Pecho, Hombros y Tríceps)',20,15000.00,2,'https://youtu.be/O-gb9wWLoL8'),(23,'Tracción ','Entrenamiento 2: Tracción (Espalda y Bíceps)',20,15000.00,2,'https://youtu.be/wIppFAhl6SE'),(24,'Pierna ','Entrenamiento 3: Pierna (Cuádriceps, Isquiotibiales y Glúteos)',20,15000.00,2,'https://youtu.be/FRIYkXmaMkQ');
/*!40000 ALTER TABLE `entrenamientos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `equipos`
--

DROP TABLE IF EXISTS `equipos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `equipos` (
  `id_equipo` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` text,
  `estado` enum('activo','en reparación') DEFAULT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `sector` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_equipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipos`
--

LOCK TABLES `equipos` WRITE;
/*!40000 ALTER TABLE `equipos` DISABLE KEYS */;
/*!40000 ALTER TABLE `equipos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `horarios`
--

DROP TABLE IF EXISTS `horarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `horarios` (
  `id_horario` int NOT NULL AUTO_INCREMENT,
  `id_entrenamiento` int DEFAULT NULL,
  `dia_semana` enum('Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo') DEFAULT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_fin` time DEFAULT NULL,
  `lugar` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_horario`),
  KEY `id_entrenamiento` (`id_entrenamiento`),
  CONSTRAINT `horarios_ibfk_1` FOREIGN KEY (`id_entrenamiento`) REFERENCES `entrenamientos` (`id_entrenamiento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `horarios`
--

LOCK TABLES `horarios` WRITE;
/*!40000 ALTER TABLE `horarios` DISABLE KEYS */;
/*!40000 ALTER TABLE `horarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inscripciones_clases`
--

DROP TABLE IF EXISTS `inscripciones_clases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inscripciones_clases` (
  `id_inscripcion` int NOT NULL AUTO_INCREMENT,
  `id_cliente` int DEFAULT NULL,
  `id_clase` int DEFAULT NULL,
  `fecha_inscripcion` date DEFAULT NULL,
  PRIMARY KEY (`id_inscripcion`),
  KEY `id_cliente` (`id_cliente`),
  KEY `id_clase` (`id_clase`),
  CONSTRAINT `inscripciones_clases_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  CONSTRAINT `inscripciones_clases_ibfk_2` FOREIGN KEY (`id_clase`) REFERENCES `clases` (`id_clase`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inscripciones_clases`
--

LOCK TABLES `inscripciones_clases` WRITE;
/*!40000 ALTER TABLE `inscripciones_clases` DISABLE KEYS */;
/*!40000 ALTER TABLE `inscripciones_clases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `membresia_entrenamiento`
--

DROP TABLE IF EXISTS `membresia_entrenamiento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `membresia_entrenamiento` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_membresia_fk` int NOT NULL,
  `id_entrenamiento_fk` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idMembresia_idx` (`id_membresia_fk`),
  KEY `idEntrenamiento_idx` (`id_entrenamiento_fk`),
  CONSTRAINT `idEntrenamiento` FOREIGN KEY (`id_entrenamiento_fk`) REFERENCES `entrenamientos` (`id_entrenamiento`),
  CONSTRAINT `idMembresia` FOREIGN KEY (`id_membresia_fk`) REFERENCES `membresias` (`id_membresia`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `membresia_entrenamiento`
--

LOCK TABLES `membresia_entrenamiento` WRITE;
/*!40000 ALTER TABLE `membresia_entrenamiento` DISABLE KEYS */;
INSERT INTO `membresia_entrenamiento` VALUES (8,3,10),(9,3,11),(10,3,12),(11,3,13),(12,3,14),(13,3,15),(14,3,16),(15,3,17),(16,3,18),(17,3,19),(18,3,20),(19,3,21),(20,5,22),(21,6,23),(22,7,24),(23,8,10),(24,8,11),(25,8,12),(26,8,13),(27,8,14),(28,8,15),(29,8,16),(30,8,17),(31,8,18),(32,8,19),(33,8,20),(34,8,21),(35,8,22),(36,8,23),(37,8,24);
/*!40000 ALTER TABLE `membresia_entrenamiento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `membresias`
--

DROP TABLE IF EXISTS `membresias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `membresias` (
  `id_membresia` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` text,
  `duracion_dias` int DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `activa` tinyint(1) DEFAULT NULL,
  `caracteristicas` text,
  `descripcion_larga` text,
  `badge` varchar(50) DEFAULT NULL,
  `especial` tinyint DEFAULT '0',
  PRIMARY KEY (`id_membresia`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `membresias`
--

LOCK TABLES `membresias` WRITE;
/*!40000 ALTER TABLE `membresias` DISABLE KEYS */;
INSERT INTO `membresias` VALUES (3,'Crosfit - Basico','Pago Mensual Incluye:',29,35.00,1,'Sin costo de lanzamiento;Sin límites de socios;Profesores capacitados;Soporte directo por WhatsApp','Plan mensual (Se realiza un entrenamiento semanal). Incluye entrenamientos semanales estructurado según WOD (Entrada en calor, Fuerza, WOD principal). Ideal para quienes recién comienzan. mostro en el WOD)','Basico',0),(4,'Entrenamiento Personalizado','Pago Mensual Incluye:',30,25.00,1,'Acceso ilimitado a clases de cardio;Sesiones guiadas con entrenadores;Sin costo de inscripción;Soporte directo por WhatsApp','Sesiones adaptadas a los objetivos del cliente (bajar de peso, ganar masa muscular, rehabilitación). Rutina diseñada por el entrenador y seguimiento individual.','Personalizado',0),(5,'Cardio / Empuje','Pago Mensual Incluye:',30,10.00,1,'Rutinas especializadas para bíceps;Acceso a área de pesas;Soporte por WhatsApp</li>','Enfocado en grupos musculares de pecho, hombros y tríceps. Ideal para mejorar resistencia cardiovascular y fuerza en la parte superior del cuerpo.','Basico',0),(6,'Fuerza / Tracción','Pago Mensual Incluye:',30,10.00,1,'Rutinas especializadas para bíceps;Acceso a área de pesas;Soporte por WhatsApp','Rutinas enfocadas en espalda y bíceps. Mejora la tracción y la postura, ideal para quienes buscan fuerza en la parte superior.','Basico',0),(7,'Piernas','Pago Mensual Incluye:',30,10.00,1,'Entrenamiento especializado para piernas;Incluye circuitos de fuerza;Sin costo de inscripción;Soporte directo por WhatsApp','Entrenamiento de piernas completo: cuádriceps, isquiotibiales y glúteos. Mejora fuerza, potencia y estabilidad.','Basico',0),(8,'Plan Completo','Pago Mensual Incluye:',30,40.00,1,'Clases de cardio, fuerza y más;Acceso a todos los equipos;Entrenadores personalizados;Soporte directo 24/7','Acceso ilimitado a todos los entrenamientos del gimnasio (Crossfit, Cardio, Fuerza, Piernas, Personalizado). Ideal para quienes buscan un plan integral.','recomendado',1),(32,'borrar','12',12,12.00,0,'12','12','e',0),(33,'Biceps','El Pago Mensual Incluye:',30,18.00,1,'Atención Personalizada en la institución;\nContacto las 24hs del dia;\nAcceso a los equipos necesarios;','Este plan fortalece los músculos y permite trabajar de una manera mas eficiente.','Basico',0);
/*!40000 ALTER TABLE `membresias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `membresias_clientes`
--

DROP TABLE IF EXISTS `membresias_clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `membresias_clientes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_cliente` int DEFAULT NULL,
  `id_membresia` int DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_cancelacion` date DEFAULT NULL,
  `estado` enum('activa','vencida','cancelada') DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_cliente` (`id_cliente`),
  KEY `id_membresia` (`id_membresia`),
  CONSTRAINT `membresias_clientes_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  CONSTRAINT `membresias_clientes_ibfk_2` FOREIGN KEY (`id_membresia`) REFERENCES `membresias` (`id_membresia`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `membresias_clientes`
--

LOCK TABLES `membresias_clientes` WRITE;
/*!40000 ALTER TABLE `membresias_clientes` DISABLE KEYS */;
INSERT INTO `membresias_clientes` VALUES (43,102,3,'2025-10-02',NULL,'activa'),(45,107,6,'2025-10-02',NULL,'activa'),(46,108,5,'2025-10-02',NULL,'activa'),(80,30,3,'2025-10-20',NULL,'activa'),(82,102,6,'2025-10-26',NULL,'activa'),(87,108,7,'2025-11-22',NULL,'activa'),(88,30,7,'2025-11-22',NULL,'activa'),(91,107,3,'2025-11-28',NULL,'activa'),(92,117,3,'2025-11-28',NULL,'activa'),(93,30,5,'2025-12-17',NULL,'activa'),(94,30,8,'2025-12-17',NULL,'activa'),(95,118,3,'2025-12-17',NULL,'activa'),(96,118,7,'2025-12-17',NULL,'activa'),(97,118,4,'2025-12-17',NULL,'activa'),(98,118,8,'2025-12-17',NULL,'activa'),(99,117,7,'2025-12-18',NULL,'activa'),(100,117,4,'2025-12-18',NULL,'activa'),(101,108,3,'2025-12-18',NULL,'activa');
/*!40000 ALTER TABLE `membresias_clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pagos`
--

DROP TABLE IF EXISTS `pagos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pagos` (
  `id_pago` int NOT NULL AUTO_INCREMENT,
  `id_membresia` int DEFAULT NULL,
  `id_MemCliente` int NOT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `fecha_pago` date DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `metodo_pago` enum('efectivo','tarjeta','transferencia') DEFAULT NULL,
  `estado` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_pago`),
  KEY `id_membresia` (`id_membresia`),
  KEY `id_memCliente_fk_idx` (`id_MemCliente`),
  CONSTRAINT `id_memCliente_fk` FOREIGN KEY (`id_MemCliente`) REFERENCES `membresias_clientes` (`id`),
  CONSTRAINT `pagos_ibfk_2` FOREIGN KEY (`id_membresia`) REFERENCES `membresias` (`id_membresia`)
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pagos`
--

LOCK TABLES `pagos` WRITE;
/*!40000 ALTER TABLE `pagos` DISABLE KEYS */;
INSERT INTO `pagos` VALUES (35,3,43,30.00,'2025-10-03','2025-10-10','transferencia',1),(37,6,45,10.00,'2025-11-22','2026-01-10','tarjeta',1),(38,5,46,10.00,'2025-11-22','2026-01-10','tarjeta',1),(72,3,80,35.00,'2025-12-17','2026-02-10','efectivo',1),(74,6,82,NULL,NULL,'2025-11-10',NULL,0),(79,7,87,NULL,NULL,'2025-12-10',NULL,0),(80,7,88,10.00,'2025-12-17','2026-02-10','efectivo',1),(83,3,91,NULL,NULL,'2025-12-10',NULL,0),(84,3,92,NULL,NULL,'2025-12-10',NULL,0),(85,5,93,NULL,NULL,'2026-01-10',NULL,0),(86,8,94,NULL,NULL,'2026-01-10',NULL,0),(87,3,95,NULL,NULL,'2026-01-10',NULL,0),(88,7,96,NULL,NULL,'2026-01-10',NULL,0),(89,4,97,25.00,'2025-12-17','2026-01-10','efectivo',1),(90,8,98,NULL,NULL,'2026-01-10',NULL,0),(91,7,99,NULL,NULL,'2026-01-10',NULL,0),(92,4,100,NULL,NULL,'2026-01-10',NULL,0),(93,3,101,35.00,'2025-12-18','2026-01-10','efectivo',1);
/*!40000 ALTER TABLE `pagos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `producto`
--

DROP TABLE IF EXISTS `producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `producto` (
  `id_producto` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` text,
  `precio` decimal(10,2) DEFAULT NULL,
  `stock` int DEFAULT NULL,
  `categoria` enum('suplemento','comida','ropa') DEFAULT NULL,
  PRIMARY KEY (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `producto`
--

LOCK TABLES `producto` WRITE;
/*!40000 ALTER TABLE `producto` DISABLE KEYS */;
/*!40000 ALTER TABLE `producto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `registro_pendiente`
--

DROP TABLE IF EXISTS `registro_pendiente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `registro_pendiente` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `dni` varchar(20) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `usuario` varchar(100) DEFAULT NULL,
  `clave` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(64) DEFAULT NULL,
  `creado_en` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `registro_pendiente`
--

LOCK TABLES `registro_pendiente` WRITE;
/*!40000 ALTER TABLE `registro_pendiente` DISABLE KEYS */;
INSERT INTO `registro_pendiente` VALUES (112,'Dylan','Medina','50295201','3644521463','2010-03-26','Dylan','Leonel2025','dyleome@gmail.com','9da84438942c67dd15c74ceb5f58babbb620cea58553cd952693c4d8dc0c8c34','2025-09-25 02:28:20'),(122,'Gustavo Ariel','Paszco','42746919','3644123123','2000-03-06','gustavopaszco','$2y$10$7uADoSYAfzSaZcYlccUEH..wJQepkKnbAGxpNcW0OXYTq75mYmJge','gustavopaszco@gmail.com','3007d2886804743418c5d7b2cfb26a878320c4a0d4fbe04fc755a9de24f1fe8d','2025-09-26 18:41:47'),(126,'Elias','Ramirez','42746919','3644340211','2014-02-14','admin','$2y$10$m0I7egGijByd3ehLMdrWEOUBFzxVP/UEbIQqruhwtc8tl9ewUtfTK','gamepelyelias2@gmail.com','260d798e37f4306034d02f51522947654fdfab1e0cf20d8acc449f37de0c8cc2','2025-11-22 22:42:06');
/*!40000 ALTER TABLE `registro_pendiente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `usuario` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `rol` enum('admin','entrenador','cliente') DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `token_recuperacion` varchar(255) DEFAULT NULL,
  `token_expiracion` datetime DEFAULT NULL,
  `foto` varchar(255) DEFAULT '/Fortaleza_Cross/img/ImgUser/user_default.png',
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=1011 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (33,'admin','$2y$10$ITc2xjdGgZ9dZQxKwpRo9.WfWt7U2O3.0IaSLirQRBRI9kyW6AaoG','cliente','eliasrami1111@gmail.com','f842281320e150e3a355ddd83de59796ce48cd9752d465c9f649e4e19aef027e','2025-11-28 17:04:32','/Fortaleza_Cross/img/ImgUser/33_1764272291.jpg'),(100,'admin','$2y$10$Rj8zVVO685kptaWzrdDl8.nYSkE5reBjq5wTZiypWr/Z6kTYnsNem','admin','miusiccristin@gmail.com','5cb0c81d6ad82124c1dfc62ab0b4a516ae9e830a099997c4e1c484e86a542af9','2025-09-20 20:18:45','/Fortaleza_Cross/img/ImgUser/user_default.png'),(102,'Josue','josue','cliente','jr1703825@gmail.com',NULL,NULL,'/Fortaleza_Cross/img/ImgUser/user_default.png'),(107,'elizabeth','$2y$10$85fdHUjY15I9uy1nUU/X8eIsEpTnBmtzIrHr16wMX8.F/HO7aWJP2','cliente','lizabeliz878@gmail.com','36fa1c7dbfd330a12eb4ddcf66c145fe00a31ad9c1f187980404988d8123f011','2025-11-28 17:04:11','/Fortaleza_Cross/img/ImgUser/user_default.png'),(108,'marketingdigital','$2y$10$.ZKDioL8auyiIYrTG3wfm.piMgpCSpINuoQaqLdPgBNW/l64lTF3O','cliente','marketingdigital.gap@gmail.com','c47b9c1d9d870bbeb4c51764545a9175d8c156ca2bd132dad35c2e56638c8e54','2025-12-18 21:04:23','/Fortaleza_Cross/img/ImgUser/user_default.png'),(1004,'borrar','$borrar','cliente','borrar@gmail.com',NULL,NULL,'/Fortaleza_Cross/img/ImgUser/user_default.png'),(1008,'alexis','$2y$10$sszVDsXJ2dmQvasVwZAlV.j6BkoHz.vmsaaimFcn6WCY3ZVwzj5W6','cliente','gabrielelizondo241998@gmail.com',NULL,NULL,'/Fortaleza_Cross/img/ImgUser/user_default.png'),(1009,'mati','$2y$10$2cOOg2zS.ULGDdtVi7BNA.g3VzEDK2Q.zl1nLh4nNTa8ITb7DecY6','cliente','gamepelyelias@gmail.com','d65a1cc75b738697c513c02c7b332d4ede5bb972986eb1d49b1122f30bb3a63c','2025-12-17 15:47:49','/Fortaleza_Cross/img/ImgUser/user_default.png'),(1010,'maria','$2y$10$ZlX8zZ2WZXG816XBG8AanegbXoZCy77NW5svYaOkOEdj6Vn8jBwtS','cliente','maria@gmail.com',NULL,NULL,'/Fortaleza_Cross/img/ImgUser/user_default.png');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ventas`
--

DROP TABLE IF EXISTS `ventas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ventas` (
  `id_venta` int NOT NULL AUTO_INCREMENT,
  `id_producto` int DEFAULT NULL,
  `id_cliente` int DEFAULT NULL,
  `cantidad` int DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  PRIMARY KEY (`id_venta`),
  KEY `id_producto` (`id_producto`),
  KEY `id_cliente` (`id_cliente`),
  CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`),
  CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ventas`
--

LOCK TABLES `ventas` WRITE;
/*!40000 ALTER TABLE `ventas` DISABLE KEYS */;
/*!40000 ALTER TABLE `ventas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `vistaallplanes`
--

DROP TABLE IF EXISTS `vistaallplanes`;
/*!50001 DROP VIEW IF EXISTS `vistaallplanes`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vistaallplanes` AS SELECT 
 1 AS `id_membresia`,
 1 AS `nombre_plan`,
 1 AS `descripcion`,
 1 AS `duracion_dias`,
 1 AS `precio`,
 1 AS `activa`,
 1 AS `id_cliente`,
 1 AS `nombre_cliente`,
 1 AS `apellido_cliente`,
 1 AS `estado_inscripcion`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vistaclasespormembresia`
--

DROP TABLE IF EXISTS `vistaclasespormembresia`;
/*!50001 DROP VIEW IF EXISTS `vistaclasespormembresia`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vistaclasespormembresia` AS SELECT 
 1 AS `id_membresia`,
 1 AS `nombre_membresia`,
 1 AS `descripcion_membresia`,
 1 AS `duracion_dias`,
 1 AS `precio_membresia`,
 1 AS `id_entrenamiento`,
 1 AS `nombre_entrenamiento`,
 1 AS `descripcion_entrenamiento`,
 1 AS `duracion_minutos`,
 1 AS `precio_entrenamiento`,
 1 AS `id_clase`,
 1 AS `dia_semana`,
 1 AS `hora_inicio`,
 1 AS `hora_fin`,
 1 AS `nombre_entrenador`,
 1 AS `Apellido_Entrenador`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vistadatospagocliente`
--

DROP TABLE IF EXISTS `vistadatospagocliente`;
/*!50001 DROP VIEW IF EXISTS `vistadatospagocliente`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vistadatospagocliente` AS SELECT 
 1 AS `id_cliente`,
 1 AS `Nombre_Cliente`,
 1 AS `apellido`,
 1 AS `dni`,
 1 AS `email`,
 1 AS `id_MemCliente`,
 1 AS `id_membresia`,
 1 AS `Nombre_Membresia`,
 1 AS `precio`,
 1 AS `id_pago`,
 1 AS `fecha_vencimiento`,
 1 AS `fecha_pago`,
 1 AS `estado`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vistaestadocliente`
--

DROP TABLE IF EXISTS `vistaestadocliente`;
/*!50001 DROP VIEW IF EXISTS `vistaestadocliente`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vistaestadocliente` AS SELECT 
 1 AS `id_cliente`,
 1 AS `Nombre_Membresia`,
 1 AS `Duracion_Membresia`,
 1 AS `nombre`,
 1 AS `apellido`,
 1 AS `ultimo_pago`,
 1 AS `vence_hasta`,
 1 AS `estado_plan`,
 1 AS `dias_restantes`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vistainfoclases`
--

DROP TABLE IF EXISTS `vistainfoclases`;
/*!50001 DROP VIEW IF EXISTS `vistainfoclases`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vistainfoclases` AS SELECT 
 1 AS `id_clase`,
 1 AS `id_entrenamiento`,
 1 AS `Nombre_Clase`,
 1 AS `dia_semana`,
 1 AS `hora_inicio`,
 1 AS `hora_fin`,
 1 AS `Nombre_Entrenador`,
 1 AS `Apellido`,
 1 AS `Detalle`,
 1 AS `Precio_Entrenamiento`,
 1 AS `video`*/;
SET character_set_client = @saved_cs_client;

--
-- Dumping events for database 'fortaleza_cross'
--

--
-- Dumping routines for database 'fortaleza_cross'
--

--
-- Final view structure for view `vistaallplanes`
--

/*!50001 DROP VIEW IF EXISTS `vistaallplanes`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vistaallplanes` AS select `m`.`id_membresia` AS `id_membresia`,`m`.`nombre` AS `nombre_plan`,`m`.`descripcion` AS `descripcion`,`m`.`duracion_dias` AS `duracion_dias`,`m`.`precio` AS `precio`,`m`.`activa` AS `activa`,`c`.`id_cliente` AS `id_cliente`,`c`.`nombre` AS `nombre_cliente`,`c`.`apellido` AS `apellido_cliente`,(case when (`mc`.`id` is not null) then 'Inscripto' else 'No inscripto' end) AS `estado_inscripcion` from ((`membresias` `m` join `clientes` `c`) left join `membresias_clientes` `mc` on(((`mc`.`id_membresia` = `m`.`id_membresia`) and (`mc`.`id_cliente` = `c`.`id_cliente`)))) where (`m`.`activa` = 1) order by `c`.`id_cliente`,`m`.`id_membresia` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vistaclasespormembresia`
--

/*!50001 DROP VIEW IF EXISTS `vistaclasespormembresia`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vistaclasespormembresia` AS select `m`.`id_membresia` AS `id_membresia`,`m`.`nombre` AS `nombre_membresia`,`m`.`descripcion` AS `descripcion_membresia`,`m`.`duracion_dias` AS `duracion_dias`,`m`.`precio` AS `precio_membresia`,`e`.`id_entrenamiento` AS `id_entrenamiento`,`e`.`nombre` AS `nombre_entrenamiento`,`e`.`descripcion` AS `descripcion_entrenamiento`,`e`.`duracion_minutos` AS `duracion_minutos`,`e`.`precio` AS `precio_entrenamiento`,`c`.`id_clase` AS `id_clase`,`c`.`dia_semana` AS `dia_semana`,`c`.`hora_inicio` AS `hora_inicio`,`c`.`hora_fin` AS `hora_fin`,`en`.`nombre` AS `nombre_entrenador`,`en`.`apellido` AS `Apellido_Entrenador` from ((((`membresias` `m` join `membresia_entrenamiento` `me` on((`m`.`id_membresia` = `me`.`id_membresia_fk`))) join `entrenamientos` `e` on((`me`.`id_entrenamiento_fk` = `e`.`id_entrenamiento`))) join `clases` `c` on((`e`.`id_entrenamiento` = `c`.`id_entrenamiento`))) join `entrenadores` `en` on((`c`.`id_entrenador` = `en`.`id_entrenador`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vistadatospagocliente`
--

/*!50001 DROP VIEW IF EXISTS `vistadatospagocliente`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vistadatospagocliente` AS select `c`.`id_cliente` AS `id_cliente`,`c`.`nombre` AS `Nombre_Cliente`,`c`.`apellido` AS `apellido`,`c`.`dni` AS `dni`,`c`.`email` AS `email`,`mc`.`id` AS `id_MemCliente`,`m`.`id_membresia` AS `id_membresia`,`m`.`nombre` AS `Nombre_Membresia`,`m`.`precio` AS `precio`,`p`.`id_pago` AS `id_pago`,`p`.`fecha_vencimiento` AS `fecha_vencimiento`,`p`.`fecha_pago` AS `fecha_pago`,`p`.`estado` AS `estado` from (((`clientes` `c` join `membresias_clientes` `mc` on((`mc`.`id_cliente` = `c`.`id_cliente`))) join `pagos` `p` on((`p`.`id_MemCliente` = `mc`.`id`))) join `membresias` `m` on((`m`.`id_membresia` = `p`.`id_membresia`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vistaestadocliente`
--

/*!50001 DROP VIEW IF EXISTS `vistaestadocliente`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vistaestadocliente` AS select `c`.`id_cliente` AS `id_cliente`,`m`.`nombre` AS `Nombre_Membresia`,`m`.`duracion_dias` AS `Duracion_Membresia`,`c`.`nombre` AS `nombre`,`c`.`apellido` AS `apellido`,max(`p`.`fecha_pago`) AS `ultimo_pago`,(last_day(max(`p`.`fecha_pago`)) + interval 10 day) AS `vence_hasta`,(case when (max(`p`.`fecha_pago`) is null) then 'Sin pago' when ((last_day(max(`p`.`fecha_pago`)) + interval 10 day) >= curdate()) then 'Al dia' else 'Vencido' end) AS `estado_plan`,greatest((to_days((last_day(max(`p`.`fecha_pago`)) + interval 10 day)) - to_days(curdate())),0) AS `dias_restantes` from (((`clientes` `c` join `membresias_clientes` `mc` on((`c`.`id_cliente` = `mc`.`id_cliente`))) join `membresias` `m` on((`m`.`id_membresia` = `mc`.`id_membresia`))) left join `pagos` `p` on((`mc`.`id` = `p`.`id_MemCliente`))) group by `c`.`id_cliente`,`c`.`nombre`,`m`.`nombre`,`m`.`duracion_dias` order by `c`.`id_cliente` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vistainfoclases`
--

/*!50001 DROP VIEW IF EXISTS `vistainfoclases`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vistainfoclases` AS select `c`.`id_clase` AS `id_clase`,`e`.`id_entrenamiento` AS `id_entrenamiento`,`e`.`nombre` AS `Nombre_Clase`,`c`.`dia_semana` AS `dia_semana`,`c`.`hora_inicio` AS `hora_inicio`,`c`.`hora_fin` AS `hora_fin`,`en`.`nombre` AS `Nombre_Entrenador`,`en`.`apellido` AS `Apellido`,`e`.`descripcion` AS `Detalle`,`e`.`precio` AS `Precio_Entrenamiento`,`e`.`video` AS `video` from ((`clases` `c` join `entrenamientos` `e` on((`e`.`id_entrenamiento` = `c`.`id_entrenamiento`))) join `entrenadores` `en` on((`e`.`id_entrenador` = `en`.`id_entrenador`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-21 22:53:22
