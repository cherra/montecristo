<?php

/**
 * Description of producto
 *
 * @author cherra
 */
class Producto extends CI_Model {
    
    private $tbl = 'Productos';
    private $tbl_pp = 'ProductoPresentaciones';
    
    /*
     * Cuenta todos los registros de la tabla
     */
    function count_all() {
        return $this->db->count_all($this->tbl);
    }
    
    /*
     * Cuenta todos los registros utilizando un filtro de busqueda
     */
    function count_all_filtro( $filtro = NULL ) {
        if(!empty($filtro)){
            $filtro = explode(' ', $filtro);
            foreach($filtro as $f){
                $this->db->or_like('nombre',$f);
            }
        }
        $query = $this->db->get($this->tbl);
        return $query->num_rows();
    }
    
    /**
     *  Obtiene todos los registros de la tabla
     */
    function get_all() {
        return $this->db->get($this->tbl);
    }
    
    /**
    * Cantidad de registros por pagina
    */
    function get_paged_list($limit = NULL, $offset = 0, $filtro = NULL) {
        if(!empty($filtro)){
            $filtro = explode(' ', $filtro);
            foreach($filtro as $f){
                $this->db->or_like('nombre',$f);
            }
        }
        $this->db->order_by('nombre','asc');
        return $this->db->get($this->tbl, $limit, $offset);
    }
    
    function get_paged_list_presentaciones($limit = NULL, $offset = 0, $filtro = NULL) {
        $this->db->select('p.*, GROUP_CONCAT(DISTINCT pr.nombre ORDER BY pr.nombre ASC SEPARATOR ",") AS presentacion, pp.sku, pp.peso', FALSE);
        $this->db->join('ProductoPresentaciones pp','p.id = pp.id_producto','left');
        $this->db->join('Presentaciones pr','pp.id_presentacion = pr.id','left');
        if(!empty($filtro)){
            $filtro = explode(' ', $filtro);
            foreach($filtro as $f){
                $this->db->or_like('p.nombre',$f);
                $this->db->or_like('pr.nombre',$f);
            }
        }
        $this->db->group_by('p.id');
        $this->db->order_by('nombre','asc');
        return $this->db->get($this->tbl.' p', $limit, $offset);
    }
    
    /**
    * Obtener por id
    */
    function get_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get($this->tbl);
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
    function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->tbl);
        return $this->db->affected_rows();
    } 
}
?>
