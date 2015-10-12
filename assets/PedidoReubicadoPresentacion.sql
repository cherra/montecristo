CREATE TABLE `PedidoReubicadoPresentacion` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_pedido_reubicado` int(11) NOT NULL,
  `id_producto_presentacion` int(11) NOT NULL,
  `cantidad` decimal(8,2) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `precio_nuevo` decimal(10,2) DEFAULT NULL,
  `iva` decimal(4,3) NOT NULL,
  `observaciones` varchar(256) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_pedido_reubicado` (`id_pedido_reubicado`,`id_producto_presentacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;