-- phpMyAdmin SQL Dump
-- version 3.5.8.1deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 22-05-2013 a las 22:35:06
-- Versión del servidor: 5.5.31-0ubuntu0.13.04.1
-- Versión de PHP: 5.4.9-4ubuntu2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `montecristo`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Configuracion`
--

DROP TABLE IF EXISTS `Configuracion`;
CREATE TABLE IF NOT EXISTS `Configuracion` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(128) NOT NULL,
  `valor` varchar(128) NOT NULL,
  `descripcion` varchar(128) NOT NULL,
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Volcado de datos para la tabla `Configuracion`
--

INSERT INTO `Configuracion` (`id`, `key`, `valor`, `descripcion`, `data`) VALUES
(7, 'asset_path', 'assets/', 'Carpeta de Assets', ''),
(8, 'template_orden_compra', 'text/html', 'Plantilla para las ordenes de compra.', 0x3c68333e3c7374726f6e673e5072756562613c2f7374726f6e673e3c2f68333e),
(12, 'iva', '0.16', 'Impuesto al Valor Agregado', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Permisos`
--

DROP TABLE IF EXISTS `Permisos`;
CREATE TABLE IF NOT EXISTS `Permisos` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Volcado de datos para la tabla `Permisos`
--

INSERT INTO `Permisos` (`id_permiso`, `permKey`, `nombre`, `folder`, `submenu`, `class`, `method`, `menu`, `icon`) VALUES
(2, 'preferencias', 'preferencias', 'preferencias', '', 'preferencias', 'index', 0, ''),
(3, 'seguridad/permisos_lista', 'Permisos', 'preferencias', '', 'seguridad', 'permisos_lista', 1, 'icon-list'),
(4, 'seguridad/permiso_delete', 'seguridad/permiso_delete', 'preferencias', '', 'seguridad', 'permiso_delete', 0, ''),
(5, 'seguridad/permiso_update', 'seguridad/permiso_update', 'preferencias', '', 'seguridad', 'permiso_update', 0, ''),
(6, 'seguridad/roles_lista', 'Roles', 'preferencias', '', 'seguridad', 'roles_lista', 1, 'icon-list'),
(7, 'seguridad/rol_update', 'seguridad/rol_update', 'preferencias', '', 'seguridad', 'rol_update', 0, ''),
(8, 'seguridad/rol_delete', 'seguridad/rol_delete', 'preferencias', '', 'seguridad', 'rol_delete', 0, ''),
(9, 'seguridad/usuarios_lista', 'Usuarios', 'preferencias', '', 'seguridad', 'usuarios_lista', 1, 'icon-list'),
(10, 'seguridad/usuario_update', 'seguridad/usuario_update', 'preferencias', '', 'seguridad', 'usuario_update', 0, ''),
(11, 'seguridad/usuario_permisos', 'seguridad/usuario_permisos', 'preferencias', '', 'seguridad', 'usuario_permisos', 0, ''),
(12, 'seguridad/usuario_delete', 'seguridad/usuario_delete', 'preferencias', '', 'seguridad', 'usuario_delete', 0, ''),
(13, 'seguridad/rol_permisos', 'seguridad/rol_permisos', 'preferencias', '', 'seguridad', 'rol_permisos', 0, ''),
(14, 'preferencias/configuracion_lista', 'Parámetros de configuración', 'preferencias', '', 'preferencias', 'configuracion_lista', 1, 'icon-cog'),
(15, 'preferencias/configuracion_add', 'preferencias/configuracion_add', 'preferencias', '', 'preferencias', 'configuracion_add', 0, ''),
(16, 'preferencias/configuracion_delete', 'preferencias/configuracion_delete', 'preferencias', '', 'preferencias', 'configuracion_delete', 0, ''),
(17, 'preferencias/configuracion_update', 'preferencias/configuracion_update', 'preferencias', '', 'preferencias', 'configuracion_update', 0, ''),
(18, 'plantillas/ordenes_compra', 'Orden de compra', 'preferencias', '', 'plantillas', 'ordenes_compra', 1, 'icon-edit');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `PermisosRol`
--

DROP TABLE IF EXISTS `PermisosRol`;
CREATE TABLE IF NOT EXISTS `PermisosRol` (
  `id_permisorol` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_rol` bigint(20) NOT NULL,
  `id_permiso` bigint(20) NOT NULL,
  `valor` tinyint(1) NOT NULL DEFAULT '0',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_permisorol`),
  UNIQUE KEY `roleID_2` (`id_rol`,`id_permiso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `PermisosUsuario`
--

DROP TABLE IF EXISTS `PermisosUsuario`;
CREATE TABLE IF NOT EXISTS `PermisosUsuario` (
  `id_permisousuario` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_usuario` bigint(20) NOT NULL,
  `id_permiso` bigint(20) NOT NULL,
  `valor` tinyint(1) NOT NULL DEFAULT '0',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_permisousuario`),
  UNIQUE KEY `userID` (`id_usuario`,`id_permiso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Roles`
--

DROP TABLE IF EXISTS `Roles`;
CREATE TABLE IF NOT EXISTS `Roles` (
  `id_rol` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(20) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  PRIMARY KEY (`id_rol`),
  UNIQUE KEY `roleName` (`nombre`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `Roles`
--

INSERT INTO `Roles` (`id_rol`, `nombre`, `descripcion`) VALUES
(1, 'Superusuario', ''),
(3, 'Administrador', 'Personal administrativo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `RolesUsuario`
--

DROP TABLE IF EXISTS `RolesUsuario`;
CREATE TABLE IF NOT EXISTS `RolesUsuario` (
  `id_rolusuario` int(11) NOT NULL,
  `id_usuario` bigint(20) NOT NULL,
  `id_rol` bigint(20) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `userID` (`id_usuario`,`id_rol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `RolesUsuario`
--

INSERT INTO `RolesUsuario` (`id_rolusuario`, `id_usuario`, `id_rol`, `fecha`) VALUES
(1, 1, 1, '2013-03-21 02:23:11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Usuarios`
--

DROP TABLE IF EXISTS `Usuarios`;
CREATE TABLE IF NOT EXISTS `Usuarios` (
  `id_usuario` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(128) NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(64) NOT NULL,
  `activo` enum('s','n') NOT NULL DEFAULT 's',
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Volcado de datos para la tabla `Usuarios`
--

INSERT INTO `Usuarios` (`id_usuario`, `nombre`, `username`, `password`, `activo`) VALUES
(1, 'Jorge', 'jorge', '33f927344e079e00d3fa45d8833b04e735223eec', 's');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
