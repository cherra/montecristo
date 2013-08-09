<?php

/**
 * Description of orden_salida
 *
 * @author cherra
 */
class Orden_salida extends CI_Model {
    
    private $tbl = 'OrdenSalida';
    private $tbl_presentacion = 'OrdenSalidaPresentacion';
    
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
    
    
    function count_grouped_by_ruta($filtro = NULL, $estado = array('1'), $id_ruta = NULL){
        $this->db->join('OrdenSalidaPresentacion osp','os.id = osp.id_orden_salida');
        $this->db->join('ProductoPresentaciones ppr','osp.id_producto_presentacion = ppr.id');
        $this->db->join('Rutas r','os.id_ruta = r.id');
        if(!empty($id_ruta))
            $this->db->where('os.id_ruta',$id_ruta);
        if(!empty($filtro)){
            $this->db->like('r.nombre',$filtro);
        }
        $estados = "(";
        $i = 0;
        foreach($estado as $e){
            if($i > 0)
                $estados .= ' OR ';
            $estados .= 'os.estado = '.$e;
            $i++;
        }
        $estados .= ")";
        $this->db->where($estados);
        $this->db->group_by('r.id');
        $query = $this->db->get($this->tbl.' os');
        return $query->num_rows();
    }
    
    function get_grouped_by_ruta($limit = NULL, $offset = 0, $filtro = NULL, $estado = array('1'), $id_ruta = NULL){
        $this->db->select('r.nombre AS ruta, r.id AS id_ruta, 
            COUNT(DISTINCT os.id) AS ordenes, 
            SUM(osp.cantidad * ppr.peso) AS peso, 
            SUM(osp.cantidad) AS piezas');
        $this->db->join('OrdenSalidaPresentacion osp','os.id = osp.id_orden_salida');
        $this->db->join('ProductoPresentaciones ppr','osp.id_producto_presentacion = ppr.id');
        $this->db->join('Rutas r','os.id_ruta = r.id');
        if(!empty($id_ruta))
            $this->db->where('os.id_ruta',$id_ruta);
        if(!empty($filtro)){
            $this->db->like('r.nombre',$filtro);
        }
        $estados = "(";
        $i = 0;
        foreach($estado as $e){
            if($i > 0)
                $estados .= ' OR ';
            $estados .= 'os.estado = '.$e;
            $i++;
        }
        $estados .= ")";
        $this->db->where($estados);
        $this->db->group_by('r.id');
        return $this->db->get($this->tbl.' os', $limit, $offset);
    }
    
    function count_by_ruta($id_ruta, $estado = array('1'), $filtro = NULL){
        $this->db->join('OrdenSalidaPresentacion osp','os.id = osp.id_orden_salida');
        $this->db->join('ProductoPresentaciones ppr','osp.id_producto_presentacion = ppr.id');
        $this->db->join('ClienteSucursales cs','os.id_cliente_sucursal = cs.id');
        $this->db->join('Clientes c','cs.id_cliente = c.id');
        $this->db->join('Usuarios u','os.id_usuario = u.id_usuario');
        $this->db->join('Rutas r','os.id_ruta = r.id');
        if(!empty($filtro)){
            $filtro = explode(' ', $filtro);
            foreach($filtro as $f){
                $this->db->where("(
                    os.id LIKE '%".$f."%' OR
                    c.nombre LIKE '%".$f."%' OR
                    cs.nombre LIKE '%".$f."%' OR
                    cs.numero LIKE '%".$f."%' OR
                    cs.municipio LIKE '%".$f."%' OR
                    cs.estado LIKE '%".$f."%' OR
                    u.nombre LIKE '%".$f."%' OR
                    os.fecha_programada LIKE '%".$f."%' OR
                    os.fecha_entrega LIKE '%".$f."%' OR
                    os.fecha LIKE '%".$f."%'
                    )"
                );
            }
        }
        $this->db->where('os.id_ruta',$id_ruta);
        $estados = "(";
        $i = 0;
        foreach($estado as $e){
            if($i > 0)
                $estados .= ' OR ';
            $estados .= 'os.estado = '.$e;
            $i++;
        }
        $estados .= ")";
        $this->db->where($estados);
        $this->db->group_by('os.id');
        $this->db->order_by('c.nombre, os.id','desc');
        $query = $this->db->get($this->tbl.' os');
        return $query->num_rows();
    }
    
    function get_by_ruta( $id_ruta, $estado = array('1'), $limit = NULL, $offset = 0, $filtro = NULL){
        $this->db->select('os.*, SUM(osp.cantidad * ppr.peso) AS peso, SUM(osp.cantidad) AS piezas');
        $this->db->join('OrdenSalidaPresentacion osp','os.id = osp.id_orden_salida');
        $this->db->join('ProductoPresentaciones ppr','osp.id_producto_presentacion = ppr.id');
        $this->db->join('ClienteSucursales cs','os.id_cliente_sucursal = cs.id');
        $this->db->join('Clientes c','cs.id_cliente = c.id');
        $this->db->join('Usuarios u','os.id_usuario = u.id_usuario');
        $this->db->join('Rutas r','os.id_ruta = r.id');
        if(!empty($filtro)){
            $filtro = explode(' ', $filtro);
            foreach($filtro as $f){
                $this->db->where("(
                    os.id LIKE '%".$f."%' OR
                    c.nombre LIKE '%".$f."%' OR
                    cs.nombre LIKE '%".$f."%' OR
                    cs.numero LIKE '%".$f."%' OR
                    cs.municipio LIKE '%".$f."%' OR
                    cs.estado LIKE '%".$f."%' OR
                    u.nombre LIKE '%".$f."%' OR
                    os.fecha_programada LIKE '%".$f."%' OR
                    os.fecha_entrega LIKE '%".$f."%' OR
                    os.fecha LIKE '%".$f."%'
                    )"
                );
            }
        }
        $this->db->where('os.id_ruta',$id_ruta);
        $estados = "(";
        $i = 0;
        foreach($estado as $e){
            if($i > 0)
                $estados .= ' OR ';
            $estados .= 'os.estado = '.$e;
            $i++;
        }
        $estados .= ")";
        $this->db->where($estados);
        $this->db->group_by('os.id');
        $this->db->order_by('c.nombre, os.id','desc');
        return $this->db->get($this->tbl.' os', $limit, $offset);
    }
    
    function count_by_estado( $estado = array('1'), $filtro = NULL){
        $this->db->join('OrdenSalidaPresentacion osp','os.id = osp.id_orden_salida');
        $this->db->join('ProductoPresentaciones ppr','osp.id_producto_presentacion = ppr.id');
        $this->db->join('ClienteSucursales cs','os.id_cliente_sucursal = cs.id');
        $this->db->join('Clientes c','cs.id_cliente = c.id');
        $this->db->join('Usuarios u','os.id_usuario = u.id_usuario');
        $this->db->join('Rutas r','os.id_ruta = r.id');
        if(!empty($filtro)){
            $filtro = explode(' ', $filtro);
            foreach($filtro as $f){
                $this->db->where("(
                    os.id LIKE '%".$f."%' OR
                    c.nombre LIKE '%".$f."%' OR
                    cs.nombre LIKE '%".$f."%' OR
                    cs.numero LIKE '%".$f."%' OR
                    cs.municipio LIKE '%".$f."%' OR
                    cs.estado LIKE '%".$f."%' OR
                    u.nombre LIKE '%".$f."%' OR
                    os.fecha_programada LIKE '%".$f."%' OR
                    os.fecha_entrega LIKE '%".$f."%' OR
                    os.fecha LIKE '%".$f."%'
                    )"
                );
            }
        }
        $estados = "(";
        $i = 0;
        foreach($estado as $e){
            if($i > 0)
                $estados .= ' OR ';
            $estados .= 'os.estado = '.$e;
            $i++;
        }
        $estados .= ")";
        $this->db->where($estados);
        $this->db->group_by('os.id');
        $this->db->order_by('c.nombre, os.id','desc');
        $query = $this->db->get($this->tbl.' os');
        return $query->num_rows();
    }
    
    function get_by_estado( $estado = array('1'), $limit = NULL, $offset = 0, $filtro = NULL){
        $this->db->select('os.*, SUM(osp.cantidad * ppr.peso) AS peso, SUM(osp.cantidad) AS piezas');
        $this->db->join('OrdenSalidaPresentacion osp','os.id = osp.id_orden_salida');
        $this->db->join('ProductoPresentaciones ppr','osp.id_producto_presentacion = ppr.id');
        $this->db->join('ClienteSucursales cs','os.id_cliente_sucursal = cs.id');
        $this->db->join('Clientes c','cs.id_cliente = c.id');
        $this->db->join('Usuarios u','os.id_usuario = u.id_usuario');
        $this->db->join('Rutas r','os.id_ruta = r.id');
        if(!empty($filtro)){
            $filtro = explode(' ', $filtro);
            foreach($filtro as $f){
                $this->db->where("(
                    os.id LIKE '%".$f."%' OR
                    c.nombre LIKE '%".$f."%' OR
                    cs.nombre LIKE '%".$f."%' OR
                    cs.numero LIKE '%".$f."%' OR
                    cs.municipio LIKE '%".$f."%' OR
                    cs.estado LIKE '%".$f."%' OR
                    u.nombre LIKE '%".$f."%' OR
                    os.fecha_programada LIKE '%".$f."%' OR
                    os.fecha_entrega LIKE '%".$f."%' OR
                    os.fecha LIKE '%".$f."%'
                    )"
                );
            }
        }
        $estados = "(";
        $i = 0;
        foreach($estado as $e){
            if($i > 0)
                $estados .= ' OR ';
            $estados .= 'os.estado = '.$e;
            $i++;
        }
        $estados .= ")";
        $this->db->where($estados);
        $this->db->group_by('os.id');
        $this->db->order_by('c.nombre, os.id','desc');
        return $this->db->get($this->tbl.' os', $limit, $offset);
    }
    
    function get_piezas($id){
        $this->db->select('SUM(osp.cantidad) AS piezas', FALSE);
        $this->db->join($this->tbl_presentacion.' osp','os.id = osp.id_orden_salida');
        $this->db->where('os.id',$id);
        $this->db->group_by('os.id');
        $query = $this->db->get($this->tbl.' os')->row();
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
        $this->db->where('id_orden_salida',$id);
        $this->db->delete($this->tbl_presentacion);
    }
}
?>
