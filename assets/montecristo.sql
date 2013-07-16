-- MySQL dump 10.13  Distrib 5.5.31, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: montecristo
-- ------------------------------------------------------
-- Server version	5.5.31-0ubuntu0.13.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `montecristo`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `montecristo` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `montecristo`;

--
-- Table structure for table `Almacen`
--

DROP TABLE IF EXISTS `Almacen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Almacen` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(128) NOT NULL,
  `descripcion` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Almacen`
--

LOCK TABLES `Almacen` WRITE;
/*!40000 ALTER TABLE `Almacen` DISABLE KEYS */;
/*!40000 ALTER TABLE `Almacen` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Categorias`
--

DROP TABLE IF EXISTS `Categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Categorias` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(128) NOT NULL,
  `codigo` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Categorias`
--

LOCK TABLES `Categorias` WRITE;
/*!40000 ALTER TABLE `Categorias` DISABLE KEYS */;
INSERT INTO `Categorias` VALUES (1,'Especie 2',''),(2,'Insumos',''),(3,'Especie 1',''),(4,'Varios',''),(5,'Flor del desierto','');
/*!40000 ALTER TABLE `Categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ClientePresentaciones`
--

DROP TABLE IF EXISTS `ClientePresentaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ClientePresentaciones` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_cliente` int(11) NOT NULL,
  `id_producto_presentacion` int(11) NOT NULL,
  `producto` varchar(128) DEFAULT NULL,
  `codigo` varchar(32) DEFAULT NULL,
  `presentacion` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_cliente` (`id_cliente`,`id_producto_presentacion`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='Registro de "Alias" de productos por cliente';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ClientePresentaciones`
--

LOCK TABLES `ClientePresentaciones` WRITE;
/*!40000 ALTER TABLE `ClientePresentaciones` DISABLE KEYS */;
INSERT INTO `ClientePresentaciones` VALUES (1,1,13,'Telefono dorado','7254841235','M-6');
/*!40000 ALTER TABLE `ClientePresentaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ClienteSucursalContactos`
--

DROP TABLE IF EXISTS `ClienteSucursalContactos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ClienteSucursalContactos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_cliente_sucursal` int(11) NOT NULL,
  `nombre` varchar(128) NOT NULL,
  `puesto` varchar(128) NOT NULL,
  `telefono` varchar(10) NOT NULL,
  `celular` varchar(10) NOT NULL,
  `email` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_cliente_sucursal` (`id_cliente_sucursal`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ClienteSucursalContactos`
--

LOCK TABLES `ClienteSucursalContactos` WRITE;
/*!40000 ALTER TABLE `ClienteSucursalContactos` DISABLE KEYS */;
INSERT INTO `ClienteSucursalContactos` VALUES (1,1,'Jorge Isaías González Becerra','Gerente','3121361900','3123013565','jorge@matica.mx'),(2,1,'Ana Sofía González','Gte. Marketing','3121361900','3123013565','cherra@mac.com'),(3,3,'Ana Cristina','Gerente','3121361900','','cherra@mac.com'),(4,5,'Jorge','Gerente','','','');
/*!40000 ALTER TABLE `ClienteSucursalContactos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ClienteSucursales`
--

DROP TABLE IF EXISTS `ClienteSucursales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ClienteSucursales` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_cliente` int(11) NOT NULL,
  `numero` varchar(32) NOT NULL,
  `nombre` varchar(128) NOT NULL,
  `calle` varchar(128) NOT NULL,
  `numero_exterior` varchar(8) NOT NULL,
  `numero_interior` varchar(4) NOT NULL,
  `colonia` varchar(128) NOT NULL,
  `poblacion` varchar(128) NOT NULL,
  `municipio` varchar(128) NOT NULL,
  `estado` varchar(128) NOT NULL,
  `cp` varchar(5) NOT NULL,
  `telefono` varchar(10) NOT NULL,
  `telefono2` varchar(10) NOT NULL,
  `email` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_cliente` (`id_cliente`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ClienteSucursales`
--

LOCK TABLES `ClienteSucursales` WRITE;
/*!40000 ALTER TABLE `ClienteSucursales` DISABLE KEYS */;
INSERT INTO `ClienteSucursales` VALUES (1,1,'1','Sucursal 1 de Soriana','','','','','Colima','','','','','',''),(2,1,'2','Otra sucursal','','','','','','','','','','',''),(3,2,'1','Walmart Tecnológico','Av. Tecnológico','100','','','Colima','Colima','Colima','','','',''),(4,1,'3','Sucursal 3','','','','','','','','','','',''),(5,2,'2','Walmart Calzada','Calzada Galván','200','','','','','','','','','');
/*!40000 ALTER TABLE `ClienteSucursales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Clientes`
--

DROP TABLE IF EXISTS `Clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Clientes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_grupo` int(11) NOT NULL,
  `id_lista` int(11) NOT NULL,
  `nombre` varchar(128) NOT NULL,
  `rfc` varchar(13) NOT NULL,
  `calle` varchar(128) NOT NULL,
  `numero_exterior` varchar(8) NOT NULL,
  `numero_interior` varchar(4) NOT NULL,
  `colonia` varchar(128) NOT NULL,
  `poblacion` varchar(128) NOT NULL,
  `municipio` varchar(128) NOT NULL,
  `estado` varchar(128) NOT NULL,
  `cp` varchar(5) NOT NULL,
  `telefono` varchar(10) NOT NULL,
  `telefono2` varchar(10) NOT NULL,
  `email` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_grupo` (`id_grupo`,`id_lista`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Clientes`
--

LOCK TABLES `Clientes` WRITE;
/*!40000 ALTER TABLE `Clientes` DISABLE KEYS */;
INSERT INTO `Clientes` VALUES (1,1,1,'Soriana S.A. de C.V.','GOBJ7903253XA','Leon Felipe','37','','Lomas Verdes','Colima','Colima','Colima','28017','3121361900','3123013565','cherra@mac.com'),(2,2,2,'Walmart de México','','','','','','Distrito Federal','Distrito Federal','Distrito Federal','','','',''),(3,2,2,'Jorge','','','','','','','','','','','','');
/*!40000 ALTER TABLE `Clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `CompraPresentacion`
--

DROP TABLE IF EXISTS `CompraPresentacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CompraPresentacion` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_compra` int(11) NOT NULL,
  `id_producto_presentacion` int(11) NOT NULL,
  `cantidad` decimal(8,2) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `observaciones` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_compra` (`id_compra`,`id_producto_presentacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CompraPresentacion`
--

LOCK TABLES `CompraPresentacion` WRITE;
/*!40000 ALTER TABLE `CompraPresentacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `CompraPresentacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Compras`
--

DROP TABLE IF EXISTS `Compras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Compras` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_proveedor` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `origen` varchar(32) NOT NULL,
  `numero` int(11) NOT NULL,
  `contacto` varchar(128) NOT NULL,
  `observaciones` varchar(128) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `id_proveedor` (`id_proveedor`,`id_pedido`,`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Compras`
--

LOCK TABLES `Compras` WRITE;
/*!40000 ALTER TABLE `Compras` DISABLE KEYS */;
/*!40000 ALTER TABLE `Compras` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Configuracion`
--

DROP TABLE IF EXISTS `Configuracion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Configuracion` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(128) NOT NULL,
  `valor` varchar(128) NOT NULL,
  `descripcion` varchar(128) NOT NULL,
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Configuracion`
--

LOCK TABLES `Configuracion` WRITE;
/*!40000 ALTER TABLE `Configuracion` DISABLE KEYS */;
INSERT INTO `Configuracion` VALUES (7,'asset_path','assets/','Carpeta de Assets',''),(8,'template_orden_compra','text/html','Plantilla para las ordenes de compra.','<h3><strong>Prueba</strong></h3>'),(12,'iva','0.16','Impuesto al Valor Agregado','');
/*!40000 ALTER TABLE `Configuracion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `FacturaLinea`
--

DROP TABLE IF EXISTS `FacturaLinea`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `FacturaLinea` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_factura` int(11) NOT NULL,
  `id_producto_presentacion` int(11) NOT NULL,
  `concepto` varchar(128) NOT NULL,
  `cantidad` decimal(8,2) NOT NULL,
  `unidad` varchar(16) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_factura` (`id_factura`,`id_producto_presentacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `FacturaLinea`
--

LOCK TABLES `FacturaLinea` WRITE;
/*!40000 ALTER TABLE `FacturaLinea` DISABLE KEYS */;
/*!40000 ALTER TABLE `FacturaLinea` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Facturas`
--

DROP TABLE IF EXISTS `Facturas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Facturas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_cliente` int(10) unsigned NOT NULL,
  `id_usuario` int(10) unsigned NOT NULL,
  `fecha` datetime NOT NULL,
  `folio` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `iva` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `id_cliente` (`id_cliente`,`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Facturas`
--

LOCK TABLES `Facturas` WRITE;
/*!40000 ALTER TABLE `Facturas` DISABLE KEYS */;
/*!40000 ALTER TABLE `Facturas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Grupos`
--

DROP TABLE IF EXISTS `Grupos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Grupos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(128) NOT NULL,
  `descripcion` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Grupos`
--

LOCK TABLES `Grupos` WRITE;
/*!40000 ALTER TABLE `Grupos` DISABLE KEYS */;
INSERT INTO `Grupos` VALUES (1,'Soriana','Cadena de tiendas Soriana'),(2,'Walmart','Cadena de tiendas Walmart');
/*!40000 ALTER TABLE `Grupos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Listas`
--

DROP TABLE IF EXISTS `Listas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Listas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(128) NOT NULL,
  `descripcion` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Listas`
--

LOCK TABLES `Listas` WRITE;
/*!40000 ALTER TABLE `Listas` DISABLE KEYS */;
INSERT INTO `Listas` VALUES (1,'Soriana','Lista de precios para toda la cadena Soriana'),(2,'Walmart',''),(3,'Varios',''),(4,'City Club','');
/*!40000 ALTER TABLE `Listas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `NotaCreditoLinea`
--

DROP TABLE IF EXISTS `NotaCreditoLinea`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `NotaCreditoLinea` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_nota_credito` int(11) NOT NULL,
  `id_producto_presentacion` int(11) NOT NULL,
  `concepto` varchar(128) NOT NULL,
  `cantidad` decimal(8,2) NOT NULL,
  `unidad` varchar(16) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_factura` (`id_nota_credito`,`id_producto_presentacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `NotaCreditoLinea`
--

LOCK TABLES `NotaCreditoLinea` WRITE;
/*!40000 ALTER TABLE `NotaCreditoLinea` DISABLE KEYS */;
/*!40000 ALTER TABLE `NotaCreditoLinea` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `NotasCredito`
--

DROP TABLE IF EXISTS `NotasCredito`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `NotasCredito` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_cliente` int(10) unsigned NOT NULL,
  `id_usuario` int(10) unsigned NOT NULL,
  `fecha` datetime NOT NULL,
  `folio` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `iva` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `id_cliente` (`id_cliente`,`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `NotasCredito`
--

LOCK TABLES `NotasCredito` WRITE;
/*!40000 ALTER TABLE `NotasCredito` DISABLE KEYS */;
/*!40000 ALTER TABLE `NotasCredito` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `OrdenEntrada`
--

DROP TABLE IF EXISTS `OrdenEntrada`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `OrdenEntrada` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_almacen` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `fecha_entrega` date NOT NULL,
  `origen` varchar(32) NOT NULL,
  `numero` int(11) NOT NULL,
  `observaciones` varchar(128) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `id_almacen` (`id_almacen`,`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `OrdenEntrada`
--

LOCK TABLES `OrdenEntrada` WRITE;
/*!40000 ALTER TABLE `OrdenEntrada` DISABLE KEYS */;
/*!40000 ALTER TABLE `OrdenEntrada` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `OrdenEntradaPresentacion`
--

DROP TABLE IF EXISTS `OrdenEntradaPresentacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `OrdenEntradaPresentacion` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_orden_entrada` int(11) NOT NULL,
  `id_producto_presentacion` int(11) NOT NULL,
  `cantidad` decimal(8,2) NOT NULL,
  `observaciones` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_orden_entrada` (`id_orden_entrada`,`id_producto_presentacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `OrdenEntradaPresentacion`
--

LOCK TABLES `OrdenEntradaPresentacion` WRITE;
/*!40000 ALTER TABLE `OrdenEntradaPresentacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `OrdenEntradaPresentacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `OrdenProduccion`
--

DROP TABLE IF EXISTS `OrdenProduccion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `OrdenProduccion` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `fecha_programada` date NOT NULL,
  `numero` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `OrdenProduccion`
--

LOCK TABLES `OrdenProduccion` WRITE;
/*!40000 ALTER TABLE `OrdenProduccion` DISABLE KEYS */;
/*!40000 ALTER TABLE `OrdenProduccion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `OrdenProduccionPresentacion`
--

DROP TABLE IF EXISTS `OrdenProduccionPresentacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `OrdenProduccionPresentacion` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_orden_produccion` int(11) NOT NULL,
  `id_producto_presentacion` int(11) NOT NULL,
  `cantidad` decimal(8,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_orden_produccion` (`id_orden_produccion`,`id_producto_presentacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `OrdenProduccionPresentacion`
--

LOCK TABLES `OrdenProduccionPresentacion` WRITE;
/*!40000 ALTER TABLE `OrdenProduccionPresentacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `OrdenProduccionPresentacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `OrdenSalida`
--

DROP TABLE IF EXISTS `OrdenSalida`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `OrdenSalida` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_almacen` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `fecha_entrega` date NOT NULL,
  `origen` varchar(32) NOT NULL,
  `numero` int(11) NOT NULL,
  `observaciones` varchar(128) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `id_almacen` (`id_almacen`,`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `OrdenSalida`
--

LOCK TABLES `OrdenSalida` WRITE;
/*!40000 ALTER TABLE `OrdenSalida` DISABLE KEYS */;
/*!40000 ALTER TABLE `OrdenSalida` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `OrdenSalidaPresentacion`
--

DROP TABLE IF EXISTS `OrdenSalidaPresentacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `OrdenSalidaPresentacion` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_orden_salida` int(11) NOT NULL,
  `id_producto_presentacion` int(11) NOT NULL,
  `cantidad` decimal(8,2) NOT NULL,
  `observaciones` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_orden_salida` (`id_orden_salida`,`id_producto_presentacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `OrdenSalidaPresentacion`
--

LOCK TABLES `OrdenSalidaPresentacion` WRITE;
/*!40000 ALTER TABLE `OrdenSalidaPresentacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `OrdenSalidaPresentacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PedidoPresentacion`
--

DROP TABLE IF EXISTS `PedidoPresentacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PedidoPresentacion` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_pedido` int(11) NOT NULL,
  `id_producto_presentacion` int(11) NOT NULL,
  `cantidad` decimal(8,2) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `observaciones` varchar(256) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_pedido` (`id_pedido`,`id_producto_presentacion`)
) ENGINE=InnoDB AUTO_INCREMENT=122 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PedidoPresentacion`
--

LOCK TABLES `PedidoPresentacion` WRITE;
/*!40000 ALTER TABLE `PedidoPresentacion` DISABLE KEYS */;
INSERT INTO `PedidoPresentacion` VALUES (82,3,17,5.00,31.35,'prueba'),(83,3,22,10.00,31.35,'prueba croto'),(84,3,21,45.00,31.35,''),(85,3,19,8.00,31.35,''),(86,3,13,25.00,57.00,''),(87,3,23,42.00,37.05,''),(88,3,21,27.00,31.35,''),(89,3,20,20.00,42.18,''),(107,5,14,55.00,285.00,''),(108,5,16,40.00,55.00,''),(109,6,16,5.00,55.00,''),(110,6,14,10.00,285.00,''),(117,7,17,20.00,210.00,'Comentarios'),(118,7,18,40.00,60.00,''),(119,7,13,20.00,142.00,''),(120,7,14,10.00,285.00,''),(121,7,16,5.00,55.00,'');
/*!40000 ALTER TABLE `PedidoPresentacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Pedidos`
--

DROP TABLE IF EXISTS `Pedidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Pedidos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_usuario_autoriza` int(11) NOT NULL,
  `id_cliente_sucursal` int(11) NOT NULL,
  `id_ruta` int(11) NOT NULL,
  `id_factura` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_vencimiento` date NOT NULL,
  `numero` int(11) NOT NULL,
  `id_contacto` int(11) NOT NULL,
  `observaciones` varchar(256) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`,`id_usuario_autoriza`,`id_cliente_sucursal`,`id_ruta`,`id_factura`,`id_contacto`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Pedidos`
--

LOCK TABLES `Pedidos` WRITE;
/*!40000 ALTER TABLE `Pedidos` DISABLE KEYS */;
INSERT INTO `Pedidos` VALUES (1,1,0,1,0,0,'2013-07-08 19:37:52','0000-00-00',0,2,'',1),(2,1,0,1,2,0,'2013-07-08 19:39:19','0000-00-00',0,1,'Prueba de comentarios',0),(3,1,0,3,1,0,'2013-07-08 20:22:21','0000-00-00',0,3,'Prueba obs',1),(5,1,0,1,2,0,'2013-07-08 20:32:19','0000-00-00',0,2,'Pedido modificado',1),(6,1,0,1,1,0,'2013-07-09 01:51:48','0000-00-00',0,2,'',1),(7,1,0,1,1,0,'2013-07-09 19:02:39','0000-00-00',0,1,'',1);
/*!40000 ALTER TABLE `Pedidos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Permisos`
--

DROP TABLE IF EXISTS `Permisos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Permisos` (
  `id_permiso` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `permKey` varchar(50) NOT NULL,
  `nombre` varchar(64) NOT NULL,
  `folder` varchar(100) NOT NULL,
  `submenu` varchar(100) NOT NULL,
  `class` varchar(100) NOT NULL,
  `method` varchar(100) NOT NULL,
  `menu` tinyint(1) NOT NULL DEFAULT '1',
  `icon` varchar(32) NOT NULL,
  PRIMARY KEY (`id_permiso`),
  UNIQUE KEY `permKey` (`permKey`)
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Permisos`
--

LOCK TABLES `Permisos` WRITE;
/*!40000 ALTER TABLE `Permisos` DISABLE KEYS */;
INSERT INTO `Permisos` VALUES (2,'preferencias','preferencias','preferencias','','preferencias','index',0,''),(14,'preferencias/configuracion_lista','Parámetros de configuración','preferencias','','preferencias','configuracion_lista',1,'icon-cog'),(15,'preferencias/configuracion_add','preferencias/configuracion_add','preferencias','','preferencias','configuracion_add',0,''),(16,'preferencias/configuracion_delete','preferencias/configuracion_delete','preferencias','','preferencias','configuracion_delete',0,''),(17,'preferencias/configuracion_update','preferencias/configuracion_update','preferencias','','preferencias','configuracion_update',0,''),(21,'ventas','Ventas','ventas','','ventas','index',0,''),(35,'compras/productos','Productos','compras','','productos','index',1,'icon-archive'),(36,'ventas/productos/categorias','Categorías','ventas','','productos','categorias',1,'icon-sitemap'),(37,'ventas/productos','Productos','ventas','','productos','index',1,'icon-archive'),(41,'preferencias/seguridad/permisos_lista','Permisos','preferencias','','seguridad','permisos_lista',1,'icon-list'),(42,'preferencias/seguridad/permiso_delete','preferencias/seguridad/permiso_delete','preferencias','','seguridad','permiso_delete',0,''),(43,'preferencias/seguridad/permiso_update','preferencias/seguridad/permiso_update','preferencias','','seguridad','permiso_update',0,''),(44,'preferencias/seguridad/roles_lista','Roles','preferencias','','seguridad','roles_lista',1,'icon-list'),(45,'preferencias/seguridad/rol_permisos','preferencias/seguridad/rol_permisos','preferencias','','seguridad','rol_permisos',0,''),(46,'preferencias/seguridad/rol_update','preferencias/seguridad/rol_update','preferencias','','seguridad','rol_update',0,''),(47,'preferencias/seguridad/rol_add','preferencias/seguridad/rol_add','preferencias','','seguridad','rol_add',0,''),(48,'preferencias/seguridad/rol_delete','preferencias/seguridad/rol_delete','preferencias','','seguridad','rol_delete',0,''),(49,'preferencias/seguridad/usuarios_lista','Usuarios','preferencias','','seguridad','usuarios_lista',1,'icon-list'),(50,'preferencias/seguridad/usuario_add','preferencias/seguridad/usuario_add','preferencias','','seguridad','usuario_add',0,''),(51,'preferencias/seguridad/usuario_permisos','preferencias/seguridad/usuario_permisos','preferencias','','seguridad','usuario_permisos',0,''),(52,'preferencias/seguridad/usuario_roles','preferencias/seguridad/usuario_roles','preferencias','','seguridad','usuario_roles',0,''),(53,'preferencias/seguridad/usuario_update','preferencias/seguridad/usuario_update','preferencias','','seguridad','usuario_update',0,''),(54,'preferencias/seguridad/usuario_delete','preferencias/seguridad/usuario_delete','preferencias','','seguridad','usuario_delete',0,''),(55,'compras','compras','compras','','compras','index',0,''),(56,'compras/productos/productos_agregar','compras/productos/productos_agregar','compras','','productos','productos_agregar',0,''),(57,'compras/productos/productos_editar','compras/productos/productos_editar','compras','','productos','productos_editar',0,''),(58,'compras/productos/categorias','Categorías','compras','','productos','categorias',1,'icon-sitemap'),(59,'compras/productos/categorias_agregar','compras/productos/categorias_agregar','compras','','productos','categorias_agregar',0,''),(60,'compras/productos/categorias_editar','compras/productos/categorias_editar','compras','','productos','categorias_editar',0,''),(61,'compras/productos/presentaciones','Presentaciones','compras','','productos','presentaciones',1,'icon-bitbucket'),(62,'compras/productos/presentaciones_editar','compras/productos/presentaciones_editar','compras','','productos','presentaciones_editar',0,''),(63,'compras/productos/presentaciones_agregar','compras/productos/presentaciones_agregar','compras','','productos','presentaciones_agregar',0,''),(64,'compras/productos/productos_presentaciones','compras/productos/productos_presentaciones','compras','','productos','productos_presentaciones',0,''),(65,'compras/productos/productos_delete_presentacion','compras/productos/productos_delete_presentacion','compras','','productos','productos_delete_presentacion',0,''),(66,'compras/productos/productos_add_presentacion','compras/productos/productos_add_presentacion','compras','','productos','productos_add_presentacion',0,''),(67,'ventas/clientes','Listado','ventas','','clientes','index',1,'icon-user'),(68,'ventas/clientes/clientes_agregar','ventas/clientes/clientes_agregar','ventas','','clientes','clientes_agregar',0,''),(69,'ventas/clientes/grupos','Grupos','ventas','','clientes','grupos',1,'icon-group'),(70,'ventas/clientes/grupos_agregar','ventas/clientes/grupos_agregar','ventas','','clientes','grupos_agregar',0,''),(71,'ventas/clientes/grupos_editar','ventas/clientes/grupos_editar','ventas','','clientes','grupos_editar',0,''),(72,'ventas/clientes/clientes_editar','ventas/clientes/clientes_editar','ventas','','clientes','clientes_editar',0,''),(75,'ventas/clientes/sucursales','Sucursales','ventas','','clientes','sucursales',1,'icon-building'),(76,'ventas/clientes/sucursales_editar','ventas/clientes/sucursales_editar','ventas','','clientes','sucursales_editar',0,''),(77,'ventas/clientes/sucursales_agregar','ventas/clientes/sucursales_agregar','ventas','','clientes','sucursales_agregar',0,''),(78,'ventas/clientes/clientes_ver','ventas/clientes/clientes_ver','ventas','','clientes','clientes_ver',0,''),(81,'ventas/clientes/contactos','Contactos','ventas','','clientes','contactos',1,'icon-phone'),(82,'ventas/clientes/contactos_agregar','ventas/clientes/contactos_agregar','ventas','','clientes','contactos_agregar',0,''),(83,'ventas/clientes/contactos_editar','ventas/clientes/contactos_editar','ventas','','clientes','contactos_editar',0,''),(84,'ventas/precios/listas','Listas','ventas','','precios','listas',1,'icon-list'),(85,'ventas/precios/listas_agregar','ventas/precios/listas_agregar','ventas','','precios','listas_agregar',0,''),(86,'ventas/precios/listas_editar','ventas/precios/listas_editar','ventas','','precios','listas_editar',0,''),(87,'ventas/precios','Precios','ventas','','precios','index',1,'icon-usd'),(88,'ventas/clientes/productos','Productos','ventas','','clientes','productos',1,'icon-archive'),(89,'ventas/clientes/productos_editar','ventas/clientes/productos_editar','ventas','','clientes','productos_editar',0,''),(90,'ventas/rutas','Rutas','ventas','','rutas','index',1,'icon-truck'),(91,'ventas/rutas/rutas_agregar','ventas/rutas/rutas_agregar','ventas','','rutas','rutas_agregar',0,''),(92,'ventas/rutas/rutas_editar','ventas/rutas/rutas_editar','ventas','','rutas','rutas_editar',0,''),(93,'ventas/pedidos','Pedidos','ventas','','pedidos','index',1,'icon-shopping-cart'),(94,'ventas/pedidos/pedidos_agregar','ventas/pedidos/pedidos_agregar','ventas','','pedidos','pedidos_agregar',0,''),(95,'ventas/pedidos/pedidos_editar','ventas/pedidos/pedidos_editar','ventas','','pedidos','pedidos_editar',0,''),(96,'ventas/pedidos/pedidos_cancelar','ventas/pedidos/pedidos_cancelar','ventas','','pedidos','pedidos_cancelar',0,'');
/*!40000 ALTER TABLE `Permisos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PermisosRol`
--

DROP TABLE IF EXISTS `PermisosRol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PermisosRol` (
  `id_permisorol` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_rol` bigint(20) NOT NULL,
  `id_permiso` bigint(20) NOT NULL,
  `valor` tinyint(1) NOT NULL DEFAULT '0',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_permisorol`),
  UNIQUE KEY `roleID_2` (`id_rol`,`id_permiso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PermisosRol`
--

LOCK TABLES `PermisosRol` WRITE;
/*!40000 ALTER TABLE `PermisosRol` DISABLE KEYS */;
/*!40000 ALTER TABLE `PermisosRol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PermisosUsuario`
--

DROP TABLE IF EXISTS `PermisosUsuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PermisosUsuario` (
  `id_permisousuario` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_usuario` bigint(20) NOT NULL,
  `id_permiso` bigint(20) NOT NULL,
  `valor` tinyint(1) NOT NULL DEFAULT '0',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_permisousuario`),
  UNIQUE KEY `userID` (`id_usuario`,`id_permiso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PermisosUsuario`
--

LOCK TABLES `PermisosUsuario` WRITE;
/*!40000 ALTER TABLE `PermisosUsuario` DISABLE KEYS */;
/*!40000 ALTER TABLE `PermisosUsuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Precios`
--

DROP TABLE IF EXISTS `Precios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Precios` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_lista` int(11) NOT NULL,
  `id_producto_presentacion` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_lista` (`id_lista`,`id_producto_presentacion`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Precios`
--

LOCK TABLES `Precios` WRITE;
/*!40000 ALTER TABLE `Precios` DISABLE KEYS */;
INSERT INTO `Precios` VALUES (1,1,16,55.00),(2,1,17,210.00),(4,1,18,60.00),(5,1,14,285.00),(6,1,13,142.00),(7,2,17,31.35),(9,2,22,31.35),(10,2,23,37.05),(11,2,21,31.35),(12,2,19,31.35),(13,2,20,42.18),(14,2,13,57.00),(15,4,22,35.00),(16,4,23,40.00);
/*!40000 ALTER TABLE `Precios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Presentaciones`
--

DROP TABLE IF EXISTS `Presentaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Presentaciones` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Presentaciones`
--

LOCK TABLES `Presentaciones` WRITE;
/*!40000 ALTER TABLE `Presentaciones` DISABLE KEYS */;
INSERT INTO `Presentaciones` VALUES (1,'M-3'),(2,'M-4'),(3,'M-6'),(4,'Colgante');
/*!40000 ALTER TABLE `Presentaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ProductoPresentaciones`
--

DROP TABLE IF EXISTS `ProductoPresentaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ProductoPresentaciones` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) NOT NULL,
  `id_presentacion` int(11) NOT NULL,
  `sku` varchar(32) NOT NULL,
  `pmc` varchar(32) NOT NULL,
  `peso` decimal(6,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_producto` (`id_producto`,`id_presentacion`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ProductoPresentaciones`
--

LOCK TABLES `ProductoPresentaciones` WRITE;
/*!40000 ALTER TABLE `ProductoPresentaciones` DISABLE KEYS */;
INSERT INTO `ProductoPresentaciones` VALUES (13,2,4,'234','',1.00),(14,2,3,'456','',3.00),(16,1,1,'123','',1.00),(17,1,2,'124','',2.00),(18,1,3,'345','',3.00),(19,5,2,'548','',1.00),(20,5,3,'547','',1.50),(21,4,2,'587','',1.00),(22,3,2,'879','',1.00),(23,3,3,'698','',1.50),(24,6,4,'457','',2.00),(25,6,1,'354','',0.80),(26,7,4,'11111','',1.00);
/*!40000 ALTER TABLE `ProductoPresentaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Productos`
--

DROP TABLE IF EXISTS `Productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Productos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(128) NOT NULL,
  `codigo` varchar(32) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `comprable` tinyint(4) NOT NULL,
  `vendible` tinyint(4) NOT NULL,
  `control_stock` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_categoria` (`id_categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Productos`
--

LOCK TABLES `Productos` WRITE;
/*!40000 ALTER TABLE `Productos` DISABLE KEYS */;
INSERT INTO `Productos` VALUES (1,'Helecho Boston','100',3,1,1,1),(2,'Teléfono dorado','101',1,1,1,1),(3,'Croto Petra','321',3,1,1,1),(4,'Cuna de Moises','461',3,1,1,1),(5,'Ficus Raqueta','143',3,1,1,1),(6,'Helecho Canguro','198',3,1,1,1),(7,'Hemerocallis','123456',1,1,1,1);
/*!40000 ALTER TABLE `Productos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ProveedorPresentaciones`
--

DROP TABLE IF EXISTS `ProveedorPresentaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ProveedorPresentaciones` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_proveedor` int(11) NOT NULL,
  `id_producto_presentacion` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_proveedor` (`id_proveedor`,`id_producto_presentacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ProveedorPresentaciones`
--

LOCK TABLES `ProveedorPresentaciones` WRITE;
/*!40000 ALTER TABLE `ProveedorPresentaciones` DISABLE KEYS */;
/*!40000 ALTER TABLE `ProveedorPresentaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Proveedores`
--

DROP TABLE IF EXISTS `Proveedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Proveedores` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(128) NOT NULL,
  `rfc` varchar(13) NOT NULL,
  `calle` varchar(128) NOT NULL,
  `numero_exterior` varchar(8) NOT NULL,
  `numero_interior` varchar(4) NOT NULL,
  `colonia` varchar(128) NOT NULL,
  `poblacion` varchar(128) NOT NULL,
  `municipio` varchar(128) NOT NULL,
  `estado` varchar(128) NOT NULL,
  `telefono` varchar(10) NOT NULL,
  `telefono2` varchar(10) NOT NULL,
  `email` varchar(128) NOT NULL,
  `contacto` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Proveedores`
--

LOCK TABLES `Proveedores` WRITE;
/*!40000 ALTER TABLE `Proveedores` DISABLE KEYS */;
/*!40000 ALTER TABLE `Proveedores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Roles`
--

DROP TABLE IF EXISTS `Roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Roles` (
  `id_rol` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(20) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  PRIMARY KEY (`id_rol`),
  UNIQUE KEY `roleName` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Roles`
--

LOCK TABLES `Roles` WRITE;
/*!40000 ALTER TABLE `Roles` DISABLE KEYS */;
INSERT INTO `Roles` VALUES (1,'Superusuario',''),(3,'Administrador','Personal administrativo');
/*!40000 ALTER TABLE `Roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `RolesUsuario`
--

DROP TABLE IF EXISTS `RolesUsuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `RolesUsuario` (
  `id_rolusuario` int(11) NOT NULL,
  `id_usuario` bigint(20) NOT NULL,
  `id_rol` bigint(20) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `userID` (`id_usuario`,`id_rol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `RolesUsuario`
--

LOCK TABLES `RolesUsuario` WRITE;
/*!40000 ALTER TABLE `RolesUsuario` DISABLE KEYS */;
INSERT INTO `RolesUsuario` VALUES (1,1,1,'2013-03-21 02:23:11');
/*!40000 ALTER TABLE `RolesUsuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Rutas`
--

DROP TABLE IF EXISTS `Rutas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Rutas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `numero` varchar(32) NOT NULL,
  `nombre` varchar(128) NOT NULL,
  `descripcion` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Rutas`
--

LOCK TABLES `Rutas` WRITE;
/*!40000 ALTER TABLE `Rutas` DISABLE KEYS */;
INSERT INTO `Rutas` VALUES (1,'1','Ruta Pacífico',''),(2,'2','Ruta Norte',''),(3,'4','Prueba',''),(4,'3','Prueba','');
/*!40000 ALTER TABLE `Rutas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Traspaso`
--

DROP TABLE IF EXISTS `Traspaso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Traspaso` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_almacen_salida` int(11) NOT NULL,
  `id_almacen_entrada` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `numero` int(11) NOT NULL,
  `observaciones` varchar(128) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `id_almacen_salida` (`id_almacen_salida`,`id_almacen_entrada`,`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Traspaso`
--

LOCK TABLES `Traspaso` WRITE;
/*!40000 ALTER TABLE `Traspaso` DISABLE KEYS */;
/*!40000 ALTER TABLE `Traspaso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `TraspasoPresentacion`
--

DROP TABLE IF EXISTS `TraspasoPresentacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TraspasoPresentacion` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_traspaso` int(11) NOT NULL,
  `id_producto_presentacion` int(11) NOT NULL,
  `cantidad` decimal(8,2) NOT NULL,
  `observaciones` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_traspaso` (`id_traspaso`,`id_producto_presentacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TraspasoPresentacion`
--

LOCK TABLES `TraspasoPresentacion` WRITE;
/*!40000 ALTER TABLE `TraspasoPresentacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `TraspasoPresentacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Usuarios`
--

DROP TABLE IF EXISTS `Usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Usuarios` (
  `id_usuario` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(128) NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(64) NOT NULL,
  `activo` enum('s','n') NOT NULL DEFAULT 's',
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Usuarios`
--

LOCK TABLES `Usuarios` WRITE;
/*!40000 ALTER TABLE `Usuarios` DISABLE KEYS */;
INSERT INTO `Usuarios` VALUES (1,'Jorge','jorge','33f927344e079e00d3fa45d8833b04e735223eec','s');
/*!40000 ALTER TABLE `Usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-07-16  9:40:17
