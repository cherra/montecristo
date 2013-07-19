<?php

/**
 * Description of cliente_presentacion
 *
 * @author cherra
 */
class Cliente_presentacion extends CI_Model {
    
    private $tbl = 'ClientePresentaciones';
    
    /*
     * Cuenta todos los registros utilizando un filtro de busqueda
     */
    function count_all( $filtro = null, $id = NULL ) {
        //$this->db->select('IFNULL(cp.producto, p.nombre) AS producto, IFNULL(cp.presentacion, pr.nombre) AS presentacion, IFNULL(cp.codigo, pr.sku) AS sku');
        $this->db->join('ProductoPresentaciones pp','cp.id_producto_presentacion = pp.id','left');
        $this->db->join('Presentaciones pr', 'pp.id_presentacion = pr.id AND cp.id_cliente = '.$id,'left');
        $this->db->join('Productos p', 'pp.id_producto = p.id','left');
        if(!empty($filtro)){
            $filtro = explode(' ', $filtro);
            foreach($filtro as $f){
                $this->db->or_like('cp.producto',$f);
                $this->db->or_like('p.nombre',$f);
            }
        }
        $this->db->where('cp.id_cliente', $id);
        $query = $this->db->get($this->tbl.' cp');
        return $query->num_rows();
    }
    
    /**
     *  Obtiene todos los registros de la tabla
     */
    function get_all() {
        $this->db->order_by('producto, presentacion','asc');
        return $this->db->get($this->tbl);
    }
    
    /**
    * Cantidad de registros por pagina
    */
    function get_paged_list($limit = null, $offset = 0, $filtro = null, $id = NULL) {
        $this->db->select('IF(LENGTH(cp.producto) > 0, cp.producto, p.nombre) AS producto, 
            IF(LENGTH(cp.presentacion) > 0,cp.presentacion, pr.nombre) AS presentacion, 
            IF(LENGTH(cp.codigo) > 0, cp.codigo, pp.codigo) AS codigo, 
            IF(LENGTH(cp.sku) > 0, cp.sku, pp.sku) AS sku, 
            pp.id AS id_producto_presentacion, 
            cp.id,
            pre.precio', FALSE);
        $this->db->join('ProductoPresentaciones pp','p.id = pp.id_producto','left');
        $this->db->join('Presentaciones pr', 'pp.id_presentacion = pr.id','left');
        $this->db->join('ClientePresentaciones cp', 'cp.id_producto_presentacion = pp.id AND cp.id_cliente = '.$id,'left');
        $this->db->join('Clientes c','cp.id_cliente = c.id  OR c.id = '.$id,'left');
        $this->db->join('Listas l','c.id_lista = l.id','left');
        $this->db->join('Precios pre','l.id = pre.id_lista AND pp.id = pre.id_producto_presentacion','left');
        if(!empty($filtro)){
            $filtro = explode(' ', $filtro);
            foreach($filtro as $f){
                $this->db->or_like('cp.producto',$f);
                $this->db->or_like('p.nombre',$f);
            }
        }
        //$this->db->where('cp.id_cliente', $id);
        $this->db->having('precio > 0');
        $this->db->order_by('presentacion, producto','asc');
        return $this->db->get('Productos p', $limit, $offset);
    }
    
    /**
    * Obtener por id
    */
    function get_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get($this->tbl);
    }
    
    function get_by_producto_cliente($id_producto_presentacion, $id_cliente){
        $this->db->where('id_producto_presentacion',$id_producto_presentacion);
        $this->db->where('id_cliente',$id_cliente);
        return $this->db->get($this->tbl);
    }
    
    function get_productos( $limit = NULL, $offset = 0, $filtro = NULL, $id = NULL ){
        $this->db->select('IF(LENGTH(cp.producto) > 0, cp.producto, p.nombre) AS producto, 
            p.id,
            MAX(pre.id) AS precio', FALSE);
        $this->db->join('ProductoPresentaciones pp','p.id = pp.id_producto','left');
        $this->db->join('Presentaciones pr', 'pp.id_presentacion = pr.id','left');
        $this->db->join('ClientePresentaciones cp', 'cp.id_producto_presentacion = pp.id AND cp.id_cliente = '.$id,'left');
        $this->db->join('Clientes c','cp.id_cliente = c.id  OR c.id = '.$id,'left');
        $this->db->join('Listas l','c.id_lista = l.id','left');
        $this->db->join('Precios pre','l.id = pre.id_lista AND pp.id = pre.id_producto_presentacion','left');
        if(!empty($filtro)){
            $filtro = explode(' ', $filtro);
            foreach($filtro as $f){
                $this->db->or_like('cp.producto',$f);
                $this->db->or_like('p.nombre',$f);
            }
        }
        $this->db->group_by('p.id');
        $this->db->having('precio IS NOT NULL');
        //$this->db->where('cp.id_cliente', $id);
        $this->db->order_by('producto','asc');
        return $this->db->get('Productos p', $limit, $offset);
    }
    
    function get_presentaciones( $limit = NULL, $offset = 0, $filtro = NULL, $id_cliente = NULL, $id_producto = NULL ){
        $this->db->select('IF(LENGTH(cp.presentacion) > 0, cp.presentacion, pr.nombre) AS presentacion, 
            cp.id,
            pp.id AS id_producto_presentacion,
            MAX(pre.precio) AS precio,
            IF(LENGTH(cp.codigo) > 0, cp.codigo, pp.codigo) AS codigo,
            IF(LENGTH(cp.sku) > 0, cp.sku, pp.sku) AS sku', FALSE);
        $this->db->join('ProductoPresentaciones pp','p.id = pp.id_producto','left');
        $this->db->join('Presentaciones pr', 'pp.id_presentacion = pr.id','left');
        $this->db->join('ClientePresentaciones cp', 'cp.id_producto_presentacion = pp.id AND cp.id_cliente = '.$id_cliente,'left');
        $this->db->join('Clientes c','cp.id_cliente = c.id  OR c.id = '.$id_cliente,'left');
        $this->db->join('Listas l','c.id_lista = l.id','left');
        $this->db->join('Precios pre','l.id = pre.id_lista AND pp.id = pre.id_producto_presentacion','left');
        if(!empty($filtro)){
            $filtro = explode(' ', $filtro);
            foreach($filtro as $f){
                $this->db->or_like('cp.presentacion',$f);
                $this->db->or_like('pr.nombre',$f);
            }
        }
        $this->db->where('p.id', $id_producto);
        $this->db->group_by('pr.id');
        $this->db->having('precio IS NOT NULL');
        $this->db->order_by('presentacion','asc');
        return $this->db->get('Productos p', $limit, $offset);
    }
    
    function get_presentacion( $id_cliente = NULL, $id_producto_presentacion = NULL ){
        $this->db->select('IF(LENGTH(cp.presentacion) > 0, cp.presentacion, pr.nombre) AS presentacion, 
            cp.id,
            pp.id AS id_producto_presentacion,
            pre.precio', FALSE);
        $this->db->join('ProductoPresentaciones pp','p.id = pp.id_producto','left');
        $this->db->join('Presentaciones pr', 'pp.id_presentacion = pr.id','left');
        $this->db->join('ClientePresentaciones cp', 'cp.id_producto_presentacion = pp.id AND cp.id_cliente = '.$id_cliente,'left');
        $this->db->join('Clientes c','cp.id_cliente = c.id  OR c.id = '.$id_cliente,'left');
        $this->db->join('Listas l','c.id_lista = l.id','left');
        $this->db->join('Precios pre','l.id = pre.id_lista AND pp.id = pre.id_producto_presentacion','left');
        if(!empty($filtro)){
            $filtro = explode(' ', $filtro);
            foreach($filtro as $f){
                $this->db->or_like('cp.presentacion',$f);
                $this->db->or_like('pr.nombre',$f);
            }
        }
        $this->db->where('pp.id', $id_producto_presentacion);
        //$this->db->group_by('pr.id');
        $this->db->having('precio IS NOT NULL');
        //$this->db->order_by('presentacion','asc');
        return $this->db->get('Productos p', $limit, $offset);
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
