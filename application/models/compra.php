<?php
/**
 * Description of compra
 *
 * @author cherra
 */
class Compra extends CI_Model {
    
    private $tbl = 'Compras';
    private $tbl_productos = 'CompraPresentacion'; 
    
    /*
     * Cuenta todos los registros utilizando un filtro de busqueda
     */
    function count_all( $filtro = NULL, $estado = array('0','1','2','3','4','5') ) {
        if(!empty($filtro)){
            $filtro = explode(' ', $filtro);
            foreach($filtro as $f){
                $this->db->or_like('id',$f);
            }
        }
        $estados = "(";
        $i = 0;
        foreach($estado as $e){
            if($i > 0)
                $estados .= ' OR ';
            $estados .= 'estado = '.$e;
            $i++;
        }
        $estados .= ")";
        $this->db->where($estados);
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
    function get_paged_list($limit = NULL, $offset = 0, $filtro = NULL, $estado = array('0','1','2','3','4','5')) {
        if(!empty($filtro)){
            $filtro = explode(' ', $filtro);
            foreach($filtro as $f){
                $this->db->or_like('id',$f);
            }
        }
        $estados = "(";
        $i = 0;
        foreach($estado as $e){
            if($i > 0)
                $estados .= ' OR ';
            $estados .= 'estado = '.$e;
            $i++;
        }
        $estados .= ")";
        $this->db->where($estados);
        $this->db->order_by('id','asc');
        return $this->db->get($this->tbl, $limit, $offset);
    }
    
    /**
    * Obtener por id
    */
    function get_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get($this->tbl);
    }
    
    function get_by_proveedor($id, $desde = NULL, $hasta = NULL){
        $this->db->select('c.*');
        $this->db->join('Proveedores p','c.id_proveedor = p.id');
        $this->db->join('Usuarios u','c.id_usuario = u.id_usuario');
        $this->db->where('p.id', $id);
        $this->db->where('c.fecha_orden_compra BETWEEN "'.$desde.'" AND "'.$hasta.'"');
        $this->db->order_by('c.id','desc');
        return $this->db->get($this->tbl.' c');
    }
    
    function get_presentaciones( $id ){
        $this->db->select('cp.*');
        $this->db->join('CompraPresentacion cp','c.id = cp.id_compra');
        $this->db->where('c.id', $id);
        $this->db->order_by('cp.id');
        return $this->db->get($this->tbl.' c');
    }
    
    function get_iva($id){
        $this->db->select('SUM(cp.cantidad * cp.precio * cp.iva) AS iva', FALSE);
        $this->db->join('CompraPresentacion cp','c.id = cp.id_compra');
        $this->db->where('c.id',$id);
        $this->db->group_by('c.id');
        $query = $this->db->get($this->tbl.' c');
        if($query->num_rows() > 0){
            $result = $query->row();
            return $result->iva;
        }else{
            return 0;
        }
    }
    
    function get_subtotal($id){
        $this->db->select('SUM(cp.cantidad * cp.precio) AS total', FALSE);
        $this->db->join('CompraPresentacion cp','c.id = cp.id_compra');
        $this->db->where('c.id',$id);
        $this->db->group_by('c.id');
        $query = $this->db->get($this->tbl.' c');
        if($query->num_rows() > 0){
            $result = $query->row();
            return $result->total;
        }else{
            return 0;
        }
    }
    
    function get_importe($id){
        $this->db->select('SUM(cp.cantidad * cp.precio) + SUM(cp.cantidad * cp.precio * cp.iva) AS total', FALSE);
        $this->db->join('CompraPresentacion cp','c.id = cp.id_compra');
        $this->db->where('c.id',$id);
        $this->db->group_by('c.id');
        $query = $this->db->get($this->tbl.' c');
        if($query->num_rows() > 0){
            $result = $query->row();
            return $result->total;
        }else{
            return 0;
        }
    }
    
    function get_peso($id){
        $this->db->select('SUM(cp.cantidad * ppr.peso) AS peso', FALSE);
        $this->db->join('CompraPresentacion cp','c.id = cp.id_compra');
        $this->db->join('ProductoPresentaciones ppr','cp.id_producto_presentacion = ppr.id');
        $this->db->where('c.id',$id);
        $this->db->group_by('c.id');
        $query = $this->db->get($this->tbl.' c');
        if($query->num_rows() > 0){
            $result = $query->row();
            return $result->peso;
        }else{
            return 0;
        }
    }
    
    function get_piezas($id){
        $this->db->select('SUM(cp.cantidad) AS piezas', FALSE);
        $this->db->join('CompraPresentacion cp','p.id = cp.id_compra');
        $this->db->join('ProductoPresentaciones ppr','cp.id_producto_presentacion = ppr.id');
        $this->db->where('p.id',$id);
        $this->db->group_by('p.id');
        $query = $this->db->get($this->tbl.' p');
        if($query->num_rows() > 0){
            $result = $query->row();
            return $result->piezas;
        }else{
            return 0;
        }
    }
    
    /**
    * Alta
    */
    function save( $datos ) {
        $this->db->insert($this->tbl, $datos);
        return $this->db->insert_id();
    }
    
    function save_presentacion ( $datos ){
        $this->db->insert($this->tbl_productos, $datos);
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
        $this->db->where('id_compra',$id);
        $this->db->delete($this->tbl_productos);
    }
}
?>
