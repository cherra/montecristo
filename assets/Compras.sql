CREATE TABLE `Compras` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_proveedor` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_orden_entrada` int(10) unsigned NOT NULL,
  `fecha_orden_compra` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha` date NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `origen` varchar(32) NOT NULL,
  `contacto` varchar(128) NOT NULL,
  `observaciones` varchar(128) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT '1',
  `tipo` enum('compra','gasto') NOT NULL,
  `numero` int(11) NOT NULL,
  `tipo_pago` varchar(45) NOT NULL,
  `fecha_factura` date NOT NULL,
  `foto_factura` longblob,
  PRIMARY KEY (`id`),
  KEY `id_proveedor` (`id_proveedor`,`id_pedido`,`id_usuario`),
  KEY `id_orden_entrada` (`id_orden_entrada`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
;