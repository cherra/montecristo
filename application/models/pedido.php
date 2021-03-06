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
        $this->db->select('p.*, (select id_ruta from PedidosReubicados where id_pedido = p.id limit 1) as ruta_reubicado, (select id_pedido from PedidosReubicados where id_pedido = p.id limit 1) AS reubicado');
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
    
    /*
     * Pedidos por rango de fecha y cliente
     */
    function get_by_cliente($id, $desde = NULL, $hasta = NULL){
        $this->db->select('p.*');
        $this->db->join('ClienteSucursales cs','p.id_cliente_sucursal = cs.id');
        $this->db->join('Clientes c','cs.id_cliente = c.id');
        $this->db->join('Usuarios u','p.id_usuario = u.id_usuario');
        $this->db->where('c.id', $id);
        $this->db->where('p.fecha BETWEEN "'.$desde.'" AND "'.$hasta.'"');
        $this->db->order_by('p.id','desc');
        return $this->db->get($this->tbl.' p');
    }
    
    function get_by_usuario($id, $desde = NULL, $hasta = NULL){
        $this->db->select('p.*');
        $this->db->join('Usuarios u','p.id_usuario = u.id_usuario');
        $this->db->where('u.id_usuario', $id);
        $this->db->where('p.fecha BETWEEN "'.$desde.'" AND "'.$hasta.'"');
        $this->db->order_by('p.id','desc');
        return $this->db->get($this->tbl.' p');
    }
    
    /**
    * Pedidos sin factura
    */
    
    function count_sin_factura($filtro = NULL, $estado = 5) {
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
        $this->db->where('p.id_factura = 0');
        $this->db->where('p.estado', $estado);
        $query = $this->db->get($this->tbl.' p');
        return $query->num_rows();
    }
    
    function get_sin_factura($limit = NULL, $offset = 0, $filtro = NULL, $estado = 5) {
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
        $this->db->where('p.id_factura = 0');
        $this->db->where('p.estado', $estado);
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
    
    function get_by_factura($id) {
        $this->db->where('id_factura', $id);
        return $this->db->get($this->tbl);
    }
    
    function get_by_llamada($id) {
        $this->db->where('id_llamada', $id);
        return $this->db->get($this->tbl);
    }
    
    function get_total_by_producto($id, $desde = NULL, $hasta = NULL) {
        $this->db->select('pr.nombre, pr.codigo');
        $this->db->select('SUM(pep.cantidad) AS cantidad');
        $this->db->select('SUM(pep.cantidad * precio) AS importe');
        $this->db->join('PedidoPresentacion pep', 'p.id = pep.id_pedido');
        $this->db->join('ProductoPresentaciones pp', 'pep.id_producto_presentacion = pp.id');
        $this->db->join('Productos pr', 'pp.id_producto = pr.id');
        $this->db->where('p.estado > 0');
        $this->db->where('pr.id', $id);
        $this->db->where('p.fecha BETWEEN "'.$desde.'" AND "'.$hasta.'"');
        $this->db->group_by('pr.id');
        return $this->db->get($this->tbl.' p');
    }
    
    function get_paged_list_by_sucursal($id, $limit = NULL, $offset = 0) {
        $this->db->select('p.*');
        $this->db->join('ClienteSucursales cs','p.id_cliente_sucursal = cs.id');
        $this->db->join('Clientes c','cs.id_cliente = c.id');
        $this->db->join('Usuarios u','p.id_usuario = u.id_usuario');
        $this->db->where('p.id_cliente_sucursal', $id);
        $this->db->order_by('p.id','desc');
        return $this->db->get($this->tbl.' p', $limit, $offset);
    }
    
    function get_presentaciones( $id, $agrupar_codigo = FALSE ){
        $this->db->select('pp.id, pp.id_pedido, pp.id_producto_presentacion, SUM(pp.cantidad) AS cantidad, pp.precio, pp.iva, pp.observaciones, 
            IF( LENGTH( cp.producto ) >0, cp.producto, pro.nombre ) AS producto,
            IF( LENGTH( cp.codigo ) >0, cp.codigo, CONCAT( pro.codigo, ppr.codigo ) ) AS codigo');
        $this->db->join('PedidoPresentacion pp','p.id = pp.id_pedido');
        $this->db->join('ProductoPresentaciones ppr', 'pp.id_producto_presentacion = ppr.id');
        $this->db->join('Productos pro', 'ppr.id_producto = pro.id');
        $this->db->join('Presentaciones pre', 'ppr.id_presentacion = pre.id');
        $this->db->join('ClienteSucursales cs', 'p.id_cliente_sucursal = cs.id');
        $this->db->join('Clientes c', 'cs.id_cliente = c.id');
        $this->db->join('ClientePresentaciones cp', 'ppr.id = cp.id_producto_presentacion AND c.id = cp.id_cliente', 'left');
        $this->db->where('p.id', $id);
        if($agrupar_codigo)
            $this->db->group_by('codigo');
        else
            $this->db->group_by('pp.id');
        $this->db->order_by('producto, pre.nombre');
        $presentaciones = $this->db->get($this->tbl.' p');
        return $presentaciones;
    }
    
    function get_presentaciones_by_cliente( $id_cliente, $id_ruta, $estado = array('1'), $limit = NULL, $offset = 0){
        $this->db->select('SUM(pp.cantidad) as cantidad, pp.precio, pp.id_producto_presentacion, IF( LENGTH( cp.producto ) >0, cp.producto, pro.nombre ) AS producto', FALSE);
        $this->db->join('PedidoPresentacion pp','p.id = pp.id_pedido');
        $this->db->join('ProductoPresentaciones ppr', 'pp.id_producto_presentacion = ppr.id');
        $this->db->join('Productos pro', 'ppr.id_producto = pro.id');
        $this->db->join('Presentaciones pre', 'ppr.id_presentacion = pre.id');
        $this->db->join('ClienteSucursales cs', 'p.id_cliente_sucursal = cs.id');
        $this->db->join('Clientes c', 'cs.id_cliente = c.id');
        $this->db->join('ClientePresentaciones cp', 'ppr.id = cp.id_producto_presentacion AND c.id = cp.id_cliente', 'left');
        $this->db->where('c.id', $id_cliente);
        $this->db->where('p.id_ruta', $id_ruta);
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
        $this->db->group_by('pre.id, pro.id');
        $this->db->order_by('producto, pre.nombre');
        return $this->db->get($this->tbl.' p', $limit, $offset);
    }
    
    function get_importe($id){
        //$this->db->select('SUM(pp.cantidad * pp.precio) + SUM(pp.cantidad * pp.precio * pp.iva) AS total', FALSE);
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
    
    function get_iva($id){
        $this->db->select('SUM(pp.cantidad * pp.precio) - SUM(pp.cantidad * pp.precio / (1 + pp.iva) ) AS iva', FALSE);
        $this->db->join('PedidoPresentacion pp','p.id = pp.id_pedido');
        $this->db->where('p.id',$id);
        $this->db->group_by('p.id');
        $query = $this->db->get($this->tbl.' p');
        if($query->num_rows() > 0){
            $result = $query->row();
            return $result->iva;
        }else{
            return 0;
        }
    }
    
    function get_subtotal($id){
        $this->db->select('SUM(pp.cantidad * pp.precio / (1 + pp.iva) ) AS total', FALSE);
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
    
    function get_peso($id){
        $this->db->select('SUM(pp.cantidad * ppr.peso) AS peso', FALSE);
        $this->db->join('PedidoPresentacion pp','p.id = pp.id_pedido');
        $this->db->join('ProductoPresentaciones ppr','pp.id_producto_presentacion = ppr.id');
        $this->db->where('p.id',$id);
        $this->db->group_by('p.id');
        $query = $this->db->get($this->tbl.' p');
        if($query->num_rows() > 0){
            $result = $query->row();
            return $result->peso;
        }else{
            return 0;
        }
    }
    
    function get_piezas($id){
        $this->db->select('SUM(pp.cantidad) AS piezas', FALSE);
        $this->db->join('PedidoPresentacion pp','p.id = pp.id_pedido');
        $this->db->join('ProductoPresentaciones ppr','pp.id_producto_presentacion = ppr.id');
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
        $this->db->select('p.*, SUM(pp.cantidad * ppr.peso) AS peso, SUM(pp.cantidad) AS piezas, (select id from PedidosReubicados where id_pedido = p.id limit 1) AS reubicado');
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
        return $this->db->get($this->tbl.' p', $limit, $offset);
    }
    
    function count_by_ruta_fecha_programada( $id_ruta, $fecha, $estado = '1', $filtro = NULL){
        $this->db->join('PedidoPresentacion pp','p.id = pp.id_pedido');
        $this->db->join('OrdenSalida os', 'p.id_orden_salida = os.id');
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
        $this->db->where('DATE(os.fecha_programada) = "'.$fecha.'"');
        $this->db->where('p.estado',$estado);
        $this->db->group_by('p.id');
        $this->db->order_by('p.id','desc');
        $query = $this->db->get($this->tbl.' p');
        return $query->num_rows();
    }
    
    function get_by_ruta_fecha_programada( $id_ruta, $fecha,  $estado = '1', $limit = NULL, $offset = 0, $filtro = NULL){
        $this->db->select('p.*, SUM(pp.cantidad * ppr.peso) AS peso, SUM(pp.cantidad) AS piezas, DATE(os.fecha_programada) AS fecha_programada');
        $this->db->join('PedidoPresentacion pp','p.id = pp.id_pedido');
        $this->db->join('OrdenSalida os', 'p.id_orden_salida = os.id');
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
        $this->db->where('DATE(os.fecha_programada) = "'.$fecha.'"');
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
        return $this->db->affected_rows();
    }
    
    function update_by_orden_salida($id, $datos) {
        $this->db->where('id_orden_salida', $id);
        $this->db->update($this->tbl, $datos);
    }
    
    function cancelar( $id ){
        $this->db->where('id', $id);
        $this->db->update($this->tbl, array('estado' => 0));
        return $this->db->affected_rows();
    }
    
    function duplicar( $id, $id_llamada = 0, $id_usuario = NULL ){
        $this->db->where('id', $id);
        $query = $this->db->get($this->tbl);  // Se obtiene el pedido
        $this->db->where('id_pedido', $id);
        $query_presentaciones = $this->db->get($this->tbl_productos);  // Se obtienen los productos
        
        if($query->num_rows() > 0 && $query_presentaciones->num_rows() > 0){
            $pedido = $query->row_array();
            $presentaciones = $query_presentaciones->result_array();
            unset($pedido['id']);  // Quitamos el ID para que se genere uno nuevo
            unset($pedido['fecha']);  // Quitamos la fecha para poner la fecha actual
            unset($pedido['id_factura']);  // Quitamos la factura
            $pedido['estado'] = '1';
            $pedido['id_llamada'] = $id_llamada;
            if(!empty($id_usuario)){
                $pedido['id_usuario'] = $id_usuario;
            }
            $this->db->trans_start();
            $this->db->insert($this->tbl, $pedido);
            $id_pedido = $this->db->insert_id();
            foreach($presentaciones AS $key => $val){  // Quitamos el ID para que se genere uno nuevo
                unset($presentaciones[$key]['id']);
                $presentaciones[$key]['id_pedido'] = $id_pedido;
            }
            $this->db->insert_batch($this->tbl_productos, $presentaciones);
            $this->db->trans_complete();
            return $id_pedido;
        }else{
            return FALSE;
        }
        
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
