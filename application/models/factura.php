<?php
/**
 * Description of factura
 *
 * @author cherra
 */
class Factura extends CI_Model {
    
    private $tbl = 'Facturas';
    private $tbl_conceptos = 'FacturaLinea';
    
    /*
     * Cuenta todos los registros utilizando un filtro de busqueda
     */
    function count_all( $filtro = NULL ) {
        $this->db->select('f.*');
        $this->db->join('Clientes c','f.id_cliente = c.id');
        if(!empty($filtro)){
            $filtro = explode(' ', $filtro);
            foreach($filtro as $f){
                $this->db->where("(
                    f.folio LIKE '%".$f."%' OR
                    c.nombre LIKE '%".$f."%' OR
                    c.rfc LIKE '%".$f."%' OR
                    f.fecha LIKE '%".$f."%'
                    )"
                );
            }
        }
        $query = $this->db->get($this->tbl.' f');
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
        $this->db->select('f.*');
        $this->db->join('Clientes c','f.id_cliente = c.id');
        if(!empty($filtro)){
            $filtro = explode(' ', $filtro);
            foreach($filtro as $f){
                $this->db->where("(
                    f.id LIKE '%".$f."%' OR
                    f.folio LIKE '%".$f."%' OR
                    c.nombre LIKE '%".$f."%' OR
                    c.rfc LIKE '%".$f."%' OR
                    f.fecha LIKE '%".$f."%'
                    )"
                );
            }
        }
        $this->db->order_by('id','desc');
        return $this->db->get($this->tbl.' f', $limit, $offset);
    }
    
    /**
    * Obtener por id
    */
    function get_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get($this->tbl);
    }
    
    function get_importes($id){
        //$this->db->select('SUM(pp.cantidad * pp.precio) + SUM(pp.cantidad * pp.precio * pp.iva) AS total', FALSE);
        $this->db->select('SUM(fl.cantidad * fl.precio) AS subtotal,
            SUM((fl.cantidad * fl.precio) * (1 + fl.tasa_iva)) - SUM(fl.cantidad * fl.precio) AS iva,
            SUM((fl.cantidad * fl.precio) * (1 + fl.tasa_iva)) AS total', FALSE);
        $this->db->join('FacturaLinea fl','f.id = fl.id_factura');
        $this->db->where('f.id',$id);
        $this->db->group_by('f.id');
        $query = $this->db->get($this->tbl.' f');
        if($query->num_rows() > 0){
            $result = $query->row();
            return $result;
        }else{
            return 0;
        }
    }
    
    function get_conceptos($id){
        $this->db->select('fl.*');
        $this->db->join('FacturaLinea fl', 'fl.id_factura = f.id');
        $this->db->where('f.id', $id);
        return $this->db->get($this->tbl.' f');
    }
        
    /**
    * Alta
    */
    function save( $datos ) {
        $this->db->insert($this->tbl, $datos);
        return $this->db->insert_id();
    }
    
    function save_concepto( $datos ){
        $this->db->insert($this->tbl_conceptos, $datos);
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
}
?>
