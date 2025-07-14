-- MySQL dump 10.13  Distrib 8.0.41, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: nexura-bd
-- ------------------------------------------------------
-- Server version	8.0.41-google

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `nexura-bd`
--

-- CREATE DATABASE /*!32312 IF NOT EXISTS*/ `nexura-bd` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci*/ /*!80016 DEFAULT ENCRYPTION='N' */;

-- USE `nexura-bd`;

--
-- Table structure for table `areas`
--

DROP TABLE IF EXISTS `areas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `areas` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'Identificador del área',
  `nombre` varchar(255) NOT NULL COMMENT 'Nombre del área de la empresa',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `areas`
--

LOCK TABLES `areas` WRITE;
/*!40000 ALTER TABLE `areas` DISABLE KEYS */;
INSERT INTO `areas` VALUES (1,'Administración'),(2,'Ventas'),(3,'Calidad'),(4,'Producción'),(5,'Finanzas');
/*!40000 ALTER TABLE `areas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empleado_rol`
--

DROP TABLE IF EXISTS `empleado_rol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empleado_rol` (
  `empleado_id` int NOT NULL COMMENT 'Identificador del empleado',
  `rol_id` int NOT NULL COMMENT 'Identificador del rol',
  PRIMARY KEY (`empleado_id`,`rol_id`),
  KEY `rol_id` (`rol_id`),
  CONSTRAINT `empleado_rol_ibfk_1` FOREIGN KEY (`empleado_id`) REFERENCES `empleados` (`id`) ON DELETE CASCADE,
  CONSTRAINT `empleado_rol_ibfk_2` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empleado_rol`
--

LOCK TABLES `empleado_rol` WRITE;
/*!40000 ALTER TABLE `empleado_rol` DISABLE KEYS */;
/*!40000 ALTER TABLE `empleado_rol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empleados`
--

DROP TABLE IF EXISTS `empleados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empleados` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'Identificador del empleado',
  `nombre` varchar(255) NOT NULL COMMENT 'Nombre completo del empleado. Campo tipo Text. Solo debe permitir letras con tilde y espacios. No se admiten caracteres especiales ni números',
  `email` varchar(255) NOT NULL COMMENT 'Correo electrónico del empleado. Campo de tipo Text|email. Solo permite una estructura de correo. Obligatorio',
  `sexo` char(1) NOT NULL COMMENT 'Campo de tipo Radio Button. M para masculino. F para Femenino. Obligatorio',
  `area_id` int NOT NULL COMMENT 'Area de la empresa a la que pertence el empleado. Campo tipo Select. Obligatorio',
  `boletin` int NOT NULL DEFAULT '0' COMMENT '1 para Recibir boletin. 0 para No recibir boletin. Campo de tipo Checkbox. Opcional',
  `descripcion` text NOT NULL COMMENT 'Se describe la experiencia del empleado. Campo de tipo textarea. Obligatorio',
  PRIMARY KEY (`id`),
  KEY `area_id` (`area_id`),
  CONSTRAINT `empleados_ibfk_1` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empleados`
--

LOCK TABLES `empleados` WRITE;
/*!40000 ALTER TABLE `empleados` DISABLE KEYS */;
/*!40000 ALTER TABLE `empleados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'Identificador del rol',
  `nombre` varchar(255) NOT NULL COMMENT 'Nombre del rol',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Profesional de proyectos'),(2,'Desarrollador'),(3,'Gerente estratégico'),(4,'Auxiliar administrativo');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-07-11 21:44:28
