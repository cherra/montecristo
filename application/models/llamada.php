<?php
/**
 * Description of llamada
 *
 * @author cherra
 */
class Llamada extends CI_Model {
    
    private $tbl = 'Llamadas';
    
    /*
     * Cuenta todos los registros utilizando un filtro de busqueda
     */
    function count_all( $filtro = NULL ) {
        if(!empty($filtro)){
            $filtro = explode(' ', $filtro);
            foreach($filtro as $f){
                $this->db->or_like('id',$f);
            }
        }
        $query = $this->db->get($this->tbl);
        return $query->num_rows();
    }
    
    /**
     *  Obtiene todos los registros de la tabla
     */
    function get_all() {
        $this->db->order_by('id','desc');
        return $this->db->get($this->tbl);
    }
    
    /**
    * Cantidad de registros por pagina
    */
    function get_paged_list($limit = NULL, $offset = 0, $filtro = NULL) {
        if(!empty($filtro)){
            $filtro = explode(' ', $filtro);
            foreach($filtro as $f){
                $this->db->or_like('id',$f);
            }
        }
        $this->db->order_by('fecha','desc');
        return $this->db->get($this->tbl, $limit, $offset);
    }
    
    function get_by_fecha($desde, $hasta, $filtro = NULL){
        $this->db->where('fecha BETWEEN "'.$desde.'" AND "'.$hasta.'"');
        if(!empty($filtro)){
            $filtro = explode(' ', $filtro);
            foreach($filtro as $f){
                $this->db->or_like('id',$f);
            }
        }
        $this->db->order_by('fecha','desc');
        return $this->db->get($this->tbl);
    }
    
    function get_by_vendedor($id_vendedor, $desde, $hasta, $filtro = NULL){
        $this->db->where('id_usuario', $id_vendedor);
        $this->db->where('fecha BETWEEN "'.$desde.'" AND "'.$hasta.'"');
        if(!empty($filtro)){
            $filtro = explode(' ', $filtro);
            foreach($filtro as $f){
                $this->db->or_like('id',$f);
            }
        }
        $this->db->order_by('fecha','desc');
        return $this->db->get($this->tbl);
    }
    
    /**
    * Obtener por id
    */
    function get_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get($this->tbl);
    }
    
    function get_by_id_usuario($id, $limit = NULL, $offset = 0){
        $this->db->where('id_usuario', $id);
        $this->db->order_by('marca','desc');
        $this->db->order_by('fecha','desc');
        return $this->db->get($this->tbl, $limit, $offset);
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
    } 
}
?>
