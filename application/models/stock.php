<?php

/**
 * Description of stock
 *
 * @author cherra
 */
class Stock extends CI_Model {
    
    //private $tbl = '';
    
    function get_real_by_producto( $id ){
        $this->db->select('IFNULL((SELECT SUM(oep.cantidad) 
            FROM OrdenEntrada oe
            JOIN OrdenEntradaPresentacion oep ON oe.id = oep.id_orden_entrada
            JOIN ProductoPresentaciones pp ON oep.id_producto_presentacion = pp.id 
            JOIN Productos p ON pp.id_producto = p.id 
            WHERE oe.estado > 1 AND p.id = '.$id.'),0)
            - 
            IFNULL((SELECT SUM(osp.cantidad) 
            FROM OrdenSalida os
            JOIN OrdenSalidaPresentacion osp ON os.id = osp.id_orden_salida
            JOIN ProductoPresentaciones pp ON osp.id_producto_presentacion = pp.id 
            JOIN Productos p ON pp.id_producto = p.id 
            WHERE os.estado > 2 AND p.id = '.$id.'),0) 
            AS stock', FALSE);
        return $this->db->get();
    }
    
    function get_real_by_presentacion( $id ){
        $this->db->select('(SELECT SUM(oep.cantidad) 
            FROM OrdenEntrada oe
            JOIN OrdenEntradaPresentacion oep ON oe.id = oep.id_orden_entrada
            JOIN ProductoPresentaciones pp ON oep.id_producto_presentacion = pp.id 
            WHERE oe.estado > 1 AND pp.id = '.$id.')
            - 
            (SELECT SUM(osp.cantidad) 
            FROM OrdenSalida os
            JOIN OrdenSalidaPresentacion osp ON os.id = osp.id_orden_salida
            JOIN ProductoPresentaciones pp ON osp.id_producto_presentacion = pp.id 
            WHERE os.estado > 3 AND pp.id = '.$id.') AS stock');
        return $this->db->get();
    }
    
}
?>
