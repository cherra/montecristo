<?php

/**
 * Description of grupo
 *
 * @author cherra
 */
class Grupo extends CI_Model {
    
    private $tbl = 'Grupos';
    
    /*
     * Cuenta todos los registros de la tabla
     */
    function count_all() {
        return $this->db->count_all($this->tbl);
    }
    
    /*
     * Cuenta todos los registros utilizando un filtro de busqueda
     */
    function count_all_filtro( $filtro = null ) {
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
        $this->db->order_by('nombre','asc');
        return $this->db->get($this->tbl);
    }
    
    /**
    * Cantidad de registros por pagina
    */
    function get_paged_list($limit = null, $offset = 0, $filtro = null) {
        if(!empty($filtro)){
            $filtro = explode(' ', $filtro);
            foreach($filtro as $f){
                $this->db->or_like('nombre',$f);
            }
        }
        $this->db->order_by('nombre','asc');
        return $this->db->get($this->tbl, $limit, $offset);
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
    } 
}
?>
