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

}

?>