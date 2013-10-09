<?php

/**
 * Description of sucursal
 *
 * @author cherra
 */
class Sucursal extends CI_Model {
    
    private $tbl = 'ClienteSucursales';
    
    /*
     * Cuenta todos los registros utilizando un filtro de busqueda
     */
    function count_all( $filtro = NULL, $id_cliente = NULL, $estado = NULL ) {
        if(!empty($filtro)){
            $filtro = explode(' ', $filtro);
            foreach($filtro as $f){
                $this->db->where("(
                        numero LIKE '%".$f."%' OR
                        nombre LIKE '%".$f."%' OR
                        poblacion LIKE '%".$f."%' OR
                        municipio LIKE '%".$f."%' OR
                        estado LIKE '%".$f."%'
                    )");
                //$this->db->like('nombre',$f);
            }
        }
        if(!empty($id_cliente)){
            $this->db->where('id_cliente', $id_cliente);
        }
        if(!empty($estado)){
            $this->db->like('estado', $estado);
        }
        $query = $this->db->get($this->tbl);
        return $query->num_rows();
    }
    
    /**
     *  Obtiene todos los registros de la tabla
     */
    function get_all() {
        $this->db->order_by('numero','asc');
        return $this->db->get($this->tbl);
    }
    
    /**
    * Cantidad de registros por pagina
    */
    function get_paged_list($limit = null, $offset = 0, $filtro = null, $id_cliente = null, $estado = null) {
        if(!empty($filtro)){
            $filtro = explode(' ', $filtro);
            foreach($filtro as $f){
                $this->db->where("(
                        numero LIKE '%".$f."%' OR
                        nombre LIKE '%".$f."%' OR
                        poblacion LIKE '%".$f."%' OR
                        municipio LIKE '%".$f."%' OR
                        estado LIKE '%".$f."%'
                    )");
                //$this->db->like('nombre',$f);
            }
        }
        if(!empty($id_cliente)){
            $this->db->where('id_cliente', $id_cliente);
        }
        if(!empty($estado)){
            $this->db->like('estado', $estado);
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
    
    function get_by_id_cliente($id){
        $this->db->where('id_cliente',$id);
        $this->db->order_by('nombre','asc');
        return $this->db->get($this->tbl);
    }
    
    function get_by_id_cliente_estado($id, $estado){
        $this->db->where('id_cliente', $id);
        $this->db->like('estado', $estado);
        $this->db->order_by('nombre','asc');
        return $this->db->get($this->tbl);
    }
    
    function get_estados_by_id_cliente($id){
        $this->db->select('estado');
        $this->db->where('id_cliente', $id);
        $this->db->group_by('estado');
        $this->db->order_by('estado');
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
