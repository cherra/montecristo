<?php

/**
 * Description of pedido
 *
 * @author cherra
 */
class Pedido extends CI_Model {
    
    private $tbl = 'Pedidos';
    private $tbl_productos = 'PedidoPresentacion';
    
    /*
     * Cuenta todos los registros utilizando un filtro de busqueda
     */
    function count_all( $filtro = NULL ) {
        $this->db->select('p.*');
        $this->db->join('ClienteSucursales cs','p.id_cliente_sucursal = cs.id');
        $this->db->join('Clientes c','cs.id_cliente = c.id');
        $this->db->join('Usuarios u','p.id_usuario = u.id_usuario');
        if(!empty($filtro)){
            $filtro = explode(' ', $filtro);
            foreach($filtro as $f){
                $this->db->where("(
                    p.id LIKE '%".$f."%' OR
                    c.nombre LIKE '%".$f."%' OR
                    cs.nombre LIKE '%".$f."%' OR
                    cs.numero LIKE '%".$f."%' OR
                    cs.municipio LIKE '%".$f."%' OR
                    cs.estado LIKE '%".$f."%' OR
                    u.nombre LIKE '%".$f."%' OR
                    p.fecha LIKE '%".$f."%'
                    )"
                );
            }
        }
        $query = $this->db->get($this->tbl.' p');
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
        $this->db->select('p.*');
        $this->db->join('ClienteSucursales cs','p.id_cliente_sucursal = cs.id');
        $this->db->join('Clientes c','cs.id_cliente = c.id');
        $this->db->join('Usuarios u','p.id_usuario = u.id_usuario');
        if(!empty($filtro)){
            $filtro = explode(' ', $filtro);
            foreach($filtro as $f){
                $this->db->where("(
                    p.id LIKE '%".$f."%' OR
                    c.nombre LIKE '%".$f."%' OR
                    cs.nombre LIKE '%".$f."%' OR
                    cs.numero LIKE '%".$f."%' OR
                    cs.municipio LIKE '%".$f."%' OR
                    cs.estado LIKE '%".$f."%' OR
                    u.nombre LIKE '%".$f."%' OR
                    p.fecha LIKE '%".$f."%'
                    )"
                );
            }
        }
        $this->db->order_by('p.id','desc');
        return $this->db->get($this->tbl.' p', $limit, $offset);
    }
    
    /**
    * Obtener por id
    */
    function get_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get($this->tbl);
    }
    
    function get_presentaciones( $id ){
        $this->db->select('pp.*');
        $this->db->join('PedidoPresentacion pp','p.id = pp.id_pedido');
        $this->db->where('p.id', $id);
        $this->db->order_by('pp.id');
        return $this->db->get($this->tbl.' p');
    }
    
    function get_importe($id){
        $this->db->select('SUM(pp.cantidad * pp.precio) AS total', FALSE);
        $this->db->join('PedidoPresentacion pp','p.id = pp.id_pedido');
        $this->db->where('p.id',$id);
        $this->db->group_by('p.id');
        $query = $this->db->get($this->tbl.' p');
        if($query->num_rows() > 0){
            $result = $query->row();
            return $result->total;
        }else{
            return 0;
        }
    }
    
    function count_grouped_by_ruta($filtro = NULL, $estado = array('1'), $id_ruta = NULL){
        //$this->db->select('r.nombre AS ruta, r.id AS id_ruta, COUNT(DISTINCT p.id) AS pedidos, MIN(p.fecha) AS desde, MAX(p.fecha) AS hasta, SUM(pp.cantidad * pp.precio) AS total');
        $this->db->join('PedidoPresentacion pp','p.id = pp.id_pedido');
        $this->db->join('ProductoPresentaciones ppr','pp.id_producto_presentacion = ppr.id');
        $this->db->join('Rutas r','p.id_ruta = r.id');
        $estados = "(";
        $i = 0;
        foreach($estado as $e){
            if($i > 0)
                $estados .= ' OR ';
            $estados .= 'p.estado = '.$e;
            $i++;
        }
        $estados .= ")";
        $this->db->where($estados);
        if(!empty($id_ruta))
            $this->db->where('p.id_ruta',$id_ruta);
        if(!empty($filtro)){
            $this->db->like('r.nombre',$filtro);
        }
        $this->db->group_by('r.id');
        $query = $this->db->get($this->tbl.' p');
        return $query->num_rows();
    }
    
    function get_grouped_by_ruta( $limit = NULL, $offset = 0, $filtro = NULL, $estado = array('1'), $id_ruta = NULL ){
        $this->db->select('r.nombre AS ruta, r.id AS id_ruta, 
            COUNT(DISTINCT p.id) AS pedidos, 
            MIN(p.fecha) AS desde, MAX(p.fecha) AS hasta, 
            SUM(pp.cantidad * ppr.peso) AS peso, 
            SUM(pp.cantidad) AS piezas, 
            SUM(pp.cantidad * pp.precio) AS total');
        $this->db->join('PedidoPresentacion pp','p.id = pp.id_pedido');
        $this->db->join('ProductoPresentaciones ppr','pp.id_producto_presentacion = ppr.id');
        $this->db->join('Rutas r','p.id_ruta = r.id');
        $estados = "(";
        $i = 0;
        foreach($estado as $e){
            if($i > 0)
                $estados .= ' OR ';
            $estados .= 'p.estado = '.$e;
            $i++;
        }
        $estados .= ")";
        $this->db->where($estados);
        if(!empty($id_ruta))
            $this->db->where('p.id_ruta',$id_ruta);
        if(!empty($filtro)){
            $this->db->like('r.nombre',$filtro);
        }
        $this->db->group_by('r.id');
        return $this->db->get($this->tbl.' p', $limit, $offset);
    }
    
    function count_grouped_by_fecha_programada($filtro = NULL, $estado = array('1'), $id_ruta = NULL){
        //$this->db->select('r.nombre AS ruta, r.id AS id_ruta, COUNT(DISTINCT p.id) AS pedidos, MIN(p.fecha) AS desde, MAX(p.fecha) AS hasta, SUM(pp.cantidad * pp.precio) AS total');
        $this->db->join('PedidoPresentacion pp','p.id = pp.id_pedido');
        $this->db->join('ProductoPresentaciones ppr','pp.id_producto_presentacion = ppr.id');
        $this->db->join('Rutas r','p.id_ruta = r.id');
        $this->db->join('OrdenSalida os','p.id_orden_salida = os.id');
        $estados = "(";
        $i = 0;
        foreach($estado as $e){
            if($i > 0)
                $estados .= ' OR ';
            $estados .= 'p.estado = '.$e;
            $i++;
        }
        $estados .= ")";
        $this->db->where($estados);
        if(!empty($id_ruta))
            $this->db->where('p.id_ruta',$id_ruta);
        if(!empty($filtro)){
            $this->db->like('r.nombre',$filtro);
        }
        $this->db->group_by('os.fecha_programada, r.id');
        $query = $this->db->get($this->tbl.' p');
        return $query->num_rows();
    }
    
    function get_grouped_by_fecha_programada( $limit = NULL, $offset = 0, $filtro = NULL, $estado = array('1'), $id_ruta = NULL ){
        $this->db->select('r.nombre AS ruta, r.id AS id_ruta, 
            COUNT(DISTINCT p.id) AS pedidos, 
            MIN(p.fecha) AS desde, MAX(p.fecha) AS hasta, 
            SUM(pp.cantidad * ppr.peso) AS peso, 
            SUM(pp.cantidad) AS piezas, 
            SUM(pp.cantidad * pp.precio) AS total,
            DATE_FORMAT(os.fecha_programada,"%Y-%m-%d") AS fecha', FALSE);
        $this->db->join('PedidoPresentacion pp','p.id = pp.id_pedido');
        $this->db->join('ProductoPresentaciones ppr','pp.id_producto_presentacion = ppr.id');
        $this->db->join('Rutas r','p.id_ruta = r.id');
        $this->db->join('OrdenSalida os','p.id_orden_salida = os.id');
        $estados = "(";
        $i = 0;
        foreach($estado as $e){
            if($i > 0)
                $estados .= ' OR ';
            $estados .= 'p.estado = '.$e;
            $i++;
        }
        $estados .= ")";
        $this->db->where($estados);
        if(!empty($id_ruta))
            $this->db->where('p.id_ruta',$id_ruta);
        if(!empty($filtro)){
            $this->db->like('r.nombre',$filtro);
        }
        $this->db->group_by('fecha, r.id');
        return $this->db->get($this->tbl.' p', $limit, $offset);
    }
    
    function count_by_ruta( $id_ruta, $estado = '1', $filtro = NULL){
        $this->db->join('PedidoPresentacion pp','p.id = pp.id_pedido');
        $this->db->join('ProductoPresentaciones ppr','pp.id_producto_presentacion = ppr.id');
        $this->db->join('ClienteSucursales cs','p.id_cliente_sucursal = cs.id');
        $this->db->join('Clientes c','cs.id_cliente = c.id');
        $this->db->join('Usuarios u','p.id_usuario = u.id_usuario');
        $this->db->join('Rutas r','p.id_ruta = r.id');
        if(!empty($filtro)){
            $filtro = explode(' ', $filtro);
            foreach($filtro as $f){
                $this->db->where("(
                    p.id LIKE '%".$f."%' OR
                    c.nombre LIKE '%".$f."%' OR
                    cs.nombre LIKE '%".$f."%' OR
                    cs.numero LIKE '%".$f."%' OR
                    cs.municipio LIKE '%".$f."%' OR
                    cs.estado LIKE '%".$f."%' OR
                    u.nombre LIKE '%".$f."%' OR
                    p.fecha LIKE '%".$f."%'
                    )"
                );
            }
        }
        $this->db->where('p.id_ruta',$id_ruta);
        $this->db->where('p.estado',$estado);
        $this->db->group_by('p.id');
        $this->db->order_by('p.id','desc');
        $query = $this->db->get($this->tbl.' p');
        return $query->num_rows();
    }
    
    function get_by_ruta( $id_ruta, $estado = '1', $limit = NULL, $offset = 0, $filtro = NULL){
        $this->db->select('p.*, SUM(pp.cantidad * ppr.peso) AS peso, SUM(pp.cantidad) AS piezas');
        $this->db->join('PedidoPresentacion pp','p.id = pp.id_pedido');
        $this->db->join('ProductoPresentaciones ppr','pp.id_producto_presentacion = ppr.id');
        $this->db->join('ClienteSucursales cs','p.id_cliente_sucursal = cs.id');
        $this->db->join('Clientes c','cs.id_cliente = c.id');
        $this->db->join('Usuarios u','p.id_usuario = u.id_usuario');
        $this->db->join('Rutas r','p.id_ruta = r.id');
        if(!empty($filtro)){
            $filtro = explode(' ', $filtro);
            foreach($filtro as $f){
                $this->db->where("(
                    p.id LIKE '%".$f."%' OR
                    c.nombre LIKE '%".$f."%' OR
                    cs.nombre LIKE '%".$f."%' OR
                    cs.numero LIKE '%".$f."%' OR
                    cs.municipio LIKE '%".$f."%' OR
                    cs.estado LIKE '%".$f."%' OR
                    u.nombre LIKE '%".$f."%' OR
                    p.fecha LIKE '%".$f."%'
                    )"
                );
            }
        }
        $this->db->where('p.id_ruta',$id_ruta);
        $this->db->where('p.estado',$estado);
        $this->db->group_by('p.id');
        $this->db->order_by('c.nombre, p.id','desc');
        return $this->db->get($this->tbl.' p', $limit, $offset);
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
        $this->db->where('id_pedido',$id);
        $this->db->delete($this->tbl_productos);
    }
}
?>
