<?php

class Pedido_reubicado extends CI_Model {
	
	private $tbl_reubicados = 'PedidosReubicados';
	private $tbl_productos_reubicados = 'PedidoReubicadoPresentacion';

	/**
	 * obtener pedido reubicado por id
	 * @param  int $id
	 * @return mixed
	 */
	function get_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get($this->tbl_reubicados)->row();
    }

	/**
     * guardar datos de pedido reubicado
     * @param  mixed $datos
     * @return int
     */
    function save_pedido_reubicado($datos) {
        $this->db->insert($this->tbl_reubicados, $datos);
        return $this->db->insert_id();   
    }

    /**
     * guardar datos de las presentaciones de productos
     * @param  mixed $datos
     * @return int
     */
    function save_presentacion ($datos) {
        $this->db->insert($this->tbl_productos_reubicados, $datos);
        return $this->db->insert_id();
    }

    /**
     * borrar el pedido reubicado
     * @param  int $id
     * @return mixed
     */
    function delete($id) {
        $pedido_reubicado = $this->get_by_id($id);
        $data = array(
            'id_pedido' => $pedido_reubicado->id_pedido,
            'id_ruta'   => $pedido_reubicado->id_ruta
        );
        // eliminar registro de PedidosReubicados
        $this->db->delete($this->tbl_reubicados, array('id' => $id));
        // eliminar registro(s) de PedidoReubicadoPresentacion
        $this->db->delete($this->tbl_productos_reubicados, array('id_pedido_reubicado' => $id));
        return (object) $data;
    }

    /**
     * validar si el pedido ha sido reubicado
     * @param  int $id_pedido
     * @return bool
     */
    function isPedidoReubicado($id_pedido) {
        $reubicado = false;
        $sql = "select id from PedidosReubicados where id_pedido = ? limit 1;";
        $query = $this->db->query($sql, array($id_pedido));
        if ($query->num_rows() > 0) 
            $reubicado = true;
        return $reubicado;
    }

    /**
     * obtener los pedidos reubicados
     * @param  int $id_pedido
     * @return mixed
     */
    function getReubicadosByPedido($id_pedido) {
        $sql = "select
                pr.id, pr.id_pedido, cl.nombre as cliente, cs.nombre as sucursal, cs.municipio, cs.estado, u.nombre as vendedor,
                sum(prp.cantidad) as piezas, sum(prp.cantidad * precio) as total
                from PedidosReubicados pr
                inner join ClienteSucursales cs on cs.id = pr.id_cliente_sucursal
                inner join Clientes cl on cl.id = cs.id_cliente
                inner join Usuarios u on u.id_usuario = pr.id_usuario
                inner join PedidoReubicadoPresentacion prp on prp.id_pedido_reubicado = pr.id
                where pr.id_pedido = ?
                group by pr.id
                order by pr.fecha;";
        $query = $this->db->query($sql, array($id_pedido));
        return $query->result();
    }

    /**
     * validar si aun existen remanente de productos con respecto
     * al pedido original
     * @param  int $id_pedido
     * @return bool
     */
    public function hasPedidoRemanentes($id_pedido) {
        $remanente = false;
        $sql = "select pr.id, pr.id_pedido, prp.id_producto_presentacion, prp.cantidad,
                ((select cantidad from PedidoPresentacion where id_pedido = pr.id_pedido and id_producto_presentacion = prp.id_producto_presentacion) - prp.cantidad) as remanente
                from PedidosReubicados pr
                inner join PedidoReubicadoPresentacion prp on prp.id_pedido_reubicado = pr.id
                where 
                pr.id_pedido = ?
                and ((select cantidad from PedidoPresentacion where id_pedido = pr.id_pedido and id_producto_presentacion = prp.id_producto_presentacion) - prp.cantidad) > 0
                limit 1;";
        $query = $this->db->query($sql, array($id_pedido));
        if ($query->num_rows() > 0) 
            $remanente = true;
        return $remanente;
    }

    /**
     * obtener los remanentes del pedido original
     * @param  int $id_pedido
     * @return mixed
     */
    public function getPedidoRemanentes($id_pedido)     {
        $sql = "SELECT 
                pp.id, pp.id_pedido_reubicado, pp.id_producto_presentacion, SUM(pp.cantidad) AS cantidad, 
                pp.precio, pp.iva, pp.observaciones, 
                IF( LENGTH( cp.producto ) > 0, cp.producto, pro.nombre ) AS producto, 
                IF( LENGTH( cp.codigo ) > 0, cp.codigo, 
                CONCAT( pro.codigo, ppr.codigo ) ) AS codigo,
                ((select cantidad from PedidoPresentacion where id_pedido = p.id_pedido and id_producto_presentacion = pp.id_producto_presentacion) - pp.cantidad) as remanente
                FROM (PedidosReubicados p) 
                JOIN PedidoReubicadoPresentacion pp ON p.id = pp.id_pedido_reubicado 
                JOIN ProductoPresentaciones ppr ON pp.id_producto_presentacion = ppr.id 
                JOIN Productos pro ON ppr.id_producto = pro.id 
                JOIN Presentaciones pre ON ppr.id_presentacion = pre.id 
                JOIN ClienteSucursales cs ON p.id_cliente_sucursal = cs.id 
                JOIN Clientes c ON cs.id_cliente = c.id 
                LEFT JOIN ClientePresentaciones cp ON ppr.id = cp.id_producto_presentacion AND c.id = cp.id_cliente 
                WHERE p.id_pedido = ? 
                and ((select cantidad from PedidoPresentacion where id_pedido = p.id_pedido and id_producto_presentacion = pp.id_producto_presentacion) - pp.cantidad) > 0
                GROUP BY pp.id 
                ORDER BY producto, pre.nombre;";
        $query = $this->db->query($sql, array($id_pedido));
        return $query->result();
    }

    /**
     * obtener las presentaciones de un pedido reubicado
     * @param  int $id
     * @return mixed
     */
    public function getPresentaciones($id) {
        $sql = "SELECT 
                pp.id, pp.id_pedido_reubicado, pp.id_producto_presentacion, SUM(pp.cantidad) AS cantidad, 
                pp.precio, pp.iva, pp.observaciones, 
                IF( LENGTH( cp.producto ) > 0, cp.producto, pro.nombre ) AS producto, 
                IF( LENGTH( cp.codigo ) > 0, cp.codigo, 
                CONCAT( pro.codigo, ppr.codigo ) ) AS codigo 
                FROM (PedidosReubicados p) 
                INNER JOIN PedidoReubicadoPresentacion pp ON p.id = pp.id_pedido_reubicado 
                INNER JOIN ProductoPresentaciones ppr ON pp.id_producto_presentacion = ppr.id 
                INNER JOIN Productos pro ON ppr.id_producto = pro.id 
                INNER JOIN Presentaciones pre ON ppr.id_presentacion = pre.id 
                INNER JOIN ClienteSucursales cs ON p.id_cliente_sucursal = cs.id 
                INNER JOIN Clientes c ON cs.id_cliente = c.id 
                LEFT JOIN ClientePresentaciones cp ON ppr.id = cp.id_producto_presentacion AND c.id = cp.id_cliente 
                WHERE p.id = ? 
                GROUP BY pp.id 
                ORDER BY producto, pre.nombre";
        $query = $this->db->query($sql, array($id));
        return $query->result();
    }
}

?>