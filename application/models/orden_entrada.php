<?php

/*
 * @author cherra
 */
class Orden_entrada extends CI_Model {
    
    private $tbl = 'OrdenEntrada';
    private $tbl_presentacion = 'OrdenEntradaPresentacion';
    
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
        $this->db->order_by('id','asc');
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
        $this->db->order_by('fecha_programada','desc');
        $this->db->order_by('id','desc');
        return $this->db->get($this->tbl, $limit, $offset);
    }
    
    /**
    * Obtener por id
    */
    function get_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get($this->tbl);
    }
    
    
    function count_by_estado( $estado = array('1'), $filtro = NULL){
        $this->db->join($this->tbl_presentacion.' oep','oe.id = oep.id_orden_entrada');
        $this->db->join('ProductoPresentaciones ppr','oep.id_producto_presentacion = ppr.id');
        $this->db->join('Proveedores p','oe.id_proveedor = p.id');
        $this->db->join('Usuarios u','oe.id_usuario = u.id_usuario');
        if(!empty($filtro)){
            $filtro = explode(' ', $filtro);
            foreach($filtro as $f){
                $this->db->where("(
                    oe.id LIKE '%".$f."%' OR
                    p.nombre LIKE '%".$f."%' OR
                    u.nombre LIKE '%".$f."%' OR
                    oe.fecha_programada LIKE '%".$f."%' OR
                    oe.fecha_entrega LIKE '%".$f."%' OR
                    oe.fecha LIKE '%".$f."%'
                    )"
                );
            }
        }
        $estados = "(";
        $i = 0;
        foreach($estado as $e){
            if($i > 0)
                $estados .= ' OR ';
            $estados .= 'oe.estado = '.$e;
            $i++;
        }
        $estados .= ")";
        $this->db->where($estados);
        $this->db->group_by('oe.id');
        $query = $this->db->get($this->tbl.' oe');
        return $query->num_rows();
    }
    
    function get_by_estado( $estado = array('1'), $limit = NULL, $offset = 0, $filtro = NULL){
        $this->db->select('oe.*, SUM(oep.cantidad * ppr.peso) AS peso, SUM(oep.cantidad) AS piezas');
        $this->db->join('OrdenEntradaPresentacion oep','oe.id = oep.id_orden_entrada');
        $this->db->join('ProductoPresentaciones ppr','oep.id_producto_presentacion = ppr.id');
        $this->db->join('Proveedores p','oe.id_proveedor = p.id');
        $this->db->join('Usuarios u','oe.id_usuario = u.id_usuario');
        if(!empty($filtro)){
            $filtro = explode(' ', $filtro);
            foreach($filtro as $f){
                $this->db->where("(
                    oe.id LIKE '%".$f."%' OR
                    p.nombre LIKE '%".$f."%' OR
                    u.nombre LIKE '%".$f."%' OR
                    oe.fecha_programada LIKE '%".$f."%' OR
                    oe.fecha_entrega LIKE '%".$f."%' OR
                    oe.fecha LIKE '%".$f."%'
                    )"
                );
            }
        }
        $estados = "(";
        $i = 0;
        foreach($estado as $e){
            if($i > 0)
                $estados .= ' OR ';
            $estados .= 'oe.estado = '.$e;
            $i++;
        }
        $estados .= ")";
        $this->db->where($estados);
        $this->db->group_by('oe.id');
        $this->db->order_by('oe.fecha_entrega','desc');
        $this->db->order_by('oe.id','desc');
        return $this->db->get($this->tbl.' oe', $limit, $offset);
    }
    
    function get_piezas($id){
        $this->db->select('SUM(oep.cantidad) AS piezas', FALSE);
        $this->db->join($this->tbl_presentacion.' oep','oe.id = oep.id_orden_entrada');
        $this->db->where('oe.id',$id);
        $this->db->group_by('oe.id');
        $query = $this->db->get($this->tbl.' oe')->row();
        if($query)
            return $query->piezas;
        else
            return 0;
    }
    
    /**
    * Alta
    */
    function save( $datos ) {
        $this->db->insert($this->tbl, $datos);
        return $this->db->insert_id();
    }
    
    function save_presentacion ( $datos ){
        $this->db->insert($this->tbl_presentacion, $datos);
        return $this->db->insert_id();
    }

    /**
    * Actualizar por id
    */
    function update($id, $datos) {
        $this->db->where('id', $id);
        $this->db->update($this->tbl, $datos);
    }
    
    function cancelar( $id ){
        $this->db->where('id', $id);
        $this->db->update($this->tbl, array('estado' => 0));
        return $this->db->affected_rows();
    }

    /**
    * Eliminar por id
    */
    function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->tbl);
    }
    
    function delete_presentaciones( $id ){
        $this->db->where('id_orden_entrada',$id);
        $this->db->delete($this->tbl_presentacion);
    }
}
?>
