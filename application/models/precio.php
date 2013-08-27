<?php
/**
 * Description of precio
 *
 * @author cherra
 */
class Precio extends CI_Model {
    
    private $tbl = 'Precios';
    
    /*
     * Cuenta todos los registros utilizando un filtro de busqueda
     */
    function count_all( $filtro = NULL, $id_lista = NULL ) {
        if(!empty($filtro)){
            $filtro = explode(' ', $filtro);
            foreach($filtro as $f){
                $this->db->or_like('nombre',$f);
            }
        }
        if(!empty($id_lista)){
            $this->db->where('id_lista', $id_lista);
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
    function get_paged_list($limit = NULL, $offset = 0, $filtro = NULL, $id_lista = NULL ) {
        if(!empty($filtro)){
            $filtro = explode(' ', $filtro);
            foreach($filtro as $f){
                $this->db->or_like('nombre',$f);
            }
        }
        if(!empty($id_lista)){
            $this->db->where('id_lista', $id_lista);
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
    
    function get_by_lista_producto_presentacion($id_lista, $id_producto_presentacion){
        $this->db->where('id_lista', $id_lista);
        $this->db->where('id_producto_presentacion', $id_producto_presentacion);
        return $this->db->get($this->tbl);
    }
    
    /**
    * Alta
    */
    function save( $id_lista, $id_producto_presentacion, $precio ) {
        $this->db->trans_start();
        $this->db->where('id_lista', $id_lista);
        $this->db->where('id_producto_presentacion', $id_producto_presentacion);
        $registro = $this->db->get($this->tbl);
        if($precio > 0){
            if($registro->num_rows() > 0){
                $this->db->where('id_lista', $id_lista);
                $this->db->where('id_producto_presentacion', $id_producto_presentacion);
                $this->db->update($this->tbl, array('id_lista' => $id_lista, 'id_producto_presentacion' => $id_producto_presentacion, 'precio' => $precio));
            }else{
                $this->db->insert($this->tbl, array('id_lista' => $id_lista, 'id_producto_presentacion' => $id_producto_presentacion, 'precio' => $precio));
            }
        }else{
            $this->db->where('id_lista', $id_lista);
            $this->db->where('id_producto_presentacion', $id_producto_presentacion);
            $this->db->delete($this->tbl);
        }
        $this->db->trans_complete();
        return $this->db->affected_rows();
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
