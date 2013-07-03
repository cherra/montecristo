<?php

/**
 * Description of producto_presentacion
 *
 * @author cherra
 */
class Producto_presentacion extends CI_Model {
    
    private $tbl = 'ProductoPresentaciones';
    
    /*
     * Cuenta todos los registros de la tabla
     */
    function count_all( $filtro = NULL ) {
        if(!empty($filtro)){
            $filtro = explode(' ', $filtro);
            foreach($filtro as $f){
                $this->db->or_like('sku',$f);
            }
        }
        $query = $this->db->get($this->tbl);
        return $query->num_rows();
    }
    
    /**
     *  Obtiene todos los registros de la tabla
     */
    function get_all() {
        $this->db->order_by('sku','asc');
        return $this->db->get($this->tbl);
    }
    
    /**
    * Cantidad de registros por pagina
    */
    function get_paged_list($limit = null, $offset = 0, $filtro = null) {
        $this->db->select('pp.*');
        $this->db->join('Productos p','pp.id_producto = p.id');
        if(!empty($filtro)){
            $filtro = explode(' ', $filtro);
            foreach($filtro as $f){
                $this->db->or_like('pp.sku',$f);
                $this->db->or_like('p.nombre',$f);
            }
        }
        $this->db->order_by('p.nombre','asc');
        return $this->db->get($this->tbl.' pp', $limit, $offset);
    }
    
    /**
    * Obtener por id
    */
    function get_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get($this->tbl);
    }
    
    /*
     * Obtener las presentaciones asignadas a un producto
     */
    function get_by_producto( $id ){
        $this->db->select('pr.*, pp.peso, pp.sku');
        $this->db->join('Presentaciones pr', 'pp.id_presentacion = pr.id');
        $this->db->where('pp.id_producto', $id);
        $this->db->order_by('pr.nombre','asc');
        return $this->db->get($this->tbl.' pp');
    }
    
    /*
     * Obtener las presentaciones no asignadas a un producto
     */
    function get_no_asignadas_by_producto( $id ){
        $this->db->select('pr.*, pp.peso, pp.sku, p.id AS producto');
        $this->db->join($this->tbl.' pp','pr.id = pp.id_presentacion AND pp.id_producto = '.$id,'left');
        $this->db->join('Productos p','pp.id_producto = p.id','left');
        $this->db->having('producto IS NULL');
        $this->db->order_by('pr.nombre','asc');
        return $this->db->get('Presentaciones pr');
    }
    
    /*
     * Valida la disponibilidad de un SKU
     */
    function sku_disponible( $sku ){
        $this->db->where('sku', $sku);
        $query = $this->db->get($this->tbl);
        if($query->num_rows() > 0){
            return FALSE;
        }else{
            return TRUE;
        }
    }
    
    /**
    * Alta
    */
    function save( $datos ) {
        $this->db->insert($this->tbl, $datos);
        return $this->db->insert_id();
    }

    /**
    * Actualizar por id
    */
    function update($id, $datos) {
        $this->db->where('id', $id);
        $this->db->update($this->tbl, $datos);
    }

    /**
    * Eliminar por id
    */
    function delete( $id_producto, $id_presentacion ) {
        $this->db->where('id_producto', $id_producto);
        $this->db->where('id_presentacion', $id_presentacion);
        $this->db->delete($this->tbl);
    } 
}
?>
