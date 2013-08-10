<?php

/**
 *
 * @author cherra
 */
class Ordenes_compra extends CI_Controller{
    
    private $folder = 'compras/';
    private $clase = 'ordenes_compra/';
    private $iconos_estado = array(
        0 => 'icon-remove',
        1 => 'icon-time',
        2 => 'icon-thumbs-up',
        3 => 'icon-share',
        4 => 'icon-check',
        5 => 'icon-qrcode'
    );
    
    public function index( $offset = 0 ){
        $this->load->model('compra','c');
        $this->load->model('proveedor','p');
        $this->load->model('preferencias/usuario','u');
        
        $this->config->load("pagination");
    	
        $data['titulo'] = 'Ordenes de compra <small>Lista</small>';
        $data['link_add'] = anchor($this->folder.$this->clase.'ordenes_compra_agregar','<i class="icon-plus icon-white"></i> Nueva', array('class' => 'btn btn-inverse'));
    	$data['action'] = $this->folder.$this->clase.'ordenes_compra';
        
        // Filtro de busqueda (se almacenan en la sesión a través de un hook)
        $filtro = $this->session->userdata('filtro');
        if($filtro)
            $data['filtro'] = $filtro;
        
        $page_limit = $this->config->item("per_page");
    	$datos = $this->c->get_paged_list($page_limit, $offset, $filtro)->result();
    	
        // generar paginacion
    	$this->load->library('pagination');
    	$config['base_url'] = site_url($this->folder.$this->clase.'index');
    	$config['total_rows'] = $this->c->count_all($filtro);
    	$config['per_page'] = $page_limit;
    	$config['uri_segment'] = 4;
    	$this->pagination->initialize($config);
    	$data['pagination'] = $this->pagination->create_links();
    	
    	// generar tabla
    	$this->load->library('table');
    	$this->table->set_empty('&nbsp;');
    	$tmpl = array ( 'table_open' => '<table class="' . $this->config->item('tabla_css') . '" >' );
    	$this->table->set_template($tmpl);
    	$this->table->set_heading('E','Número','Fecha','Proveedor','Municipio','Estado','Teléfono','Contacto','Usuario','Total', '', '');
    	foreach ($datos as $d) {
            $proveedor = $this->p->get_by_id($d->id_proveedor)->row();
            $usuario = $this->u->get_by_id($d->id_usuario)->row();
            $importe = $this->c->get_importe($d->id);
    		$this->table->add_row(
                        '<i class="'.$this->iconos_estado[$d->estado].'"></i>',
                        $d->id,
                        $d->fecha_orden_compra,
                        $proveedor->nombre,
                        $proveedor->municipio,
                        $proveedor->estado,
                        $proveedor->telefono,
                        $proveedor->contacto,
                        $usuario->nombre,
                        array('data' => number_format($importe,2), 'style' => 'text-align: right;'),
                        array('data' => ($d->estado > 0 && $d->estado < 5 ? anchor($this->folder.$this->clase.'ordenes_compra_editar/' . $d->id, '<i class="icon-edit"></i>', array('class' => 'btn btn-small', 'title' => 'Editar')) :  '<a class="btn btn-small" disabled><i class="icon-edit"></i></a>'), 'style' => 'text-align: right;'),
                        array('data' => ($d->estado > 0 && $d->estado < 5 ? anchor($this->folder.$this->clase.'ordenes_compra_cancelar/' . $d->id, '<i class="icon-ban-circle"></i>', array('class' => 'btn btn-small cancelar', 'title' => 'Cancelar')) :  '<a class="btn btn-small" disabled><i class="icon-ban-circle"></i></a>'), 'style' => 'text-align: right;')
    		);
                if($d->estado == 0)
                    $this->table->add_row_class('muted');
                else
                    $this->table->add_row_class('');
    	}
    	$data['table'] = $this->table->generate();
    	
    	$this->load->view('lista', $data);
    }
    
    public function ordenes_compra_agregar() {
    	$this->load->model('compra', 'c');
        
    	$data['titulo'] = 'Orden de compra <small>Registro nuevo</small>';
    	$data['link_back'] = anchor($this->folder.$this->clase.'ordenes_compra','<i class="icon-arrow-left"></i> Regresar',array('class'=>'btn'));
    
        $this->load->view('compras/ordenes_compra/formulario', $data);
    }
    
    // Método para guardar una orden de compra (Ajax)
    public function ordenes_compra_guardar( $id = NULL ){
        if($this->input->is_ajax_request()){
            $this->load->model('compra','c');
            //$this->load->model('orden_entrada','oe');
            if( ($datos = $this->input->post()) ){
                $respuesta = 'OK';
                $compra = array(
                    'id_usuario' => $this->session->userdata('userid'),
                    'id_proveedor' => $datos['id_proveedor'],
                    'observaciones' => $datos['observaciones']
                );
                $this->db->trans_start();
                
                $edicion = FALSE;
                if(empty($id)){ // Si es una orden de compra nueva
                    $id_compra = $this->c->save($compra);
                    $mensaje = 'Se generó la orden de compra no. '.$id_compra;
                }else{  // Si se está editando
                    $edicion = TRUE;
                    $id_compra = $id;
                    $this->c->update($id, $compra);
                    $this->c->delete_presentaciones($id);  // Se borran los productos
                    $mensaje = 'Se actualizó la orden de compra no. '.$id_compra;
                }
                if( $id_compra ){
                    $compra = $this->c->get_by_id($id_compra)->row();
                    //$salida = $this->os->get_by_id($pedido->id_orden_salida)->row();
                    //$this->os->delete_presentaciones($pedido->id_orden_salida);
                    foreach($datos['productos'] as $producto){
                        // Presentaciones
                        $presentacion = array(
                            'id_compra' => $id_compra,
                            'id_producto_presentacion' => $producto[0],
                            'cantidad' => $producto[1],
                            'precio' => $producto[2],
                            'observaciones' => $producto[3]
                        );
                        if( !($this->c->save_presentacion($presentacion)) ){
                            $respuesta = 'Error';
                            $this->session->set_flashdata('mensaje',array('texto' => 'Error al guardar la orden de compra', 'tipo' => 'alert-error'));
                        }
                        /*if($edicion && ($compra->estado > 1 && $compra->estado < 4)){  // Si el estado del pedido es mayor a 1 quiere decir que es una edición de pedido
                            // Presentaciones de la orden de entrada
                            $presentacion_oe = array(
                                'id_orden_entrada' => $compra->id_orden_entrada,
                                'id_producto_presentacion' => $producto[0],
                                'cantidad' => $producto[1],
                                'observaciones' => $producto[3]
                            );
                            if( !($this->oe->save_presentacion($presentacion_oe)) ){
                                $respuesta = 'Error';
                                $this->session->set_flashdata('mensaje',array('texto' => 'Error al guardar la orden de entrada', 'tipo' => 'alert-error'));
                            }
                        }*/
                    }
                }else{
                    $respuesta = 'Error';
                    $this->session->set_flashdata('mensaje',array('texto' => 'Error al guardar la orden de compra', 'tipo' => 'alert-error'));
                }
                $this->db->trans_complete();
                $this->session->set_flashdata('mensaje', array('texto' => $mensaje, 'tipo' => 'alert-success'));
                echo $respuesta;
            }
        }
    }
    
    public function ordenes_compra_editar( $id = NULL ){
        $this->load->model('compra','c');
        
        $datos = $this->c->get_by_id($id);
        if (empty($id) OR $datos->num_rows() <= 0) {
            redirect($this->folder.$this->clase.'index');
        }
        
        $this->load->model('proveedor','p');
        $this->load->model('producto_presentacion','pp');
        $this->load->model('producto','pro');
        $this->load->model('presentacion','pre');
        
        $data['titulo'] = 'Orden de compra <small>Editar</small>';
    	$data['link_back'] = anchor($this->folder.$this->clase.'index','<i class="icon-arrow-left"></i> Regresar',array('class'=>'btn'));
        
        $compra = $datos->row();
        $data['compra'] = $compra;
        $data['proveedor'] = $this->p->get_by_id($compra->id_proveedor)->row();
        
        $presentaciones = $this->c->get_presentaciones($id)->result();
        foreach($presentaciones as $p){
            $pp = $this->pp->get_by_id($p->id_producto_presentacion)->row();
            if($pp){
                $producto = $this->pro->get_by_id($pp->id_producto)->row();
                $presentacion = $this->pre->get_by_id($pp->id_presentacion)->row();
                $data['presentaciones'][] = (object)array(
                    'id_producto_presentacion' => $p->id_producto_presentacion,
                    'cantidad' => $p->cantidad,
                    'precio' => $p->precio,
                    'producto' => $producto->nombre,
                    'id_producto' => $producto->id,
                    'presentacion' => $presentacion->nombre,
                    'codigo' => $pp->codigo,
                    'observaciones' => $p->observaciones);
            }
        }
        $data['icono_estado'] = '<i class="'.$this->iconos_estado[$compra->estado].' icon-2x"></i>';
        /*if($data['pedido']->estado == '1')
            $data['action_estado'] = anchor($this->folder.$this->clase.'siguiente_estado/'.$id, 'Autorizar', array('class' => 'btn btn-success input-block-level', 'id' => 'autorizar'));
            */
        $this->load->view('compras/ordenes_compra/formulario', $data);
    }
    
    public function ordenes_compra_cancelar( $id = NULL ){
        if(empty($id)){
            redirect('compras/ordenes_compra/index');
        }
        
        $this->load->model('compra','c');
        //$this->load->model('orden_entrada','oe');
        $this->db->trans_start();
        $compra = $this->c->get_by_id($id)->row();
        $respuesta = $this->c->cancelar($id);
        $respuesta_oe = TRUE;
        /*if($compra->estado < 4)
            $respuesta_oe = $this->oe->cancelar($compra->id_orden_entrada);*/
        $this->db->trans_complete();
        if($respuesta && $respuesta_oe){
            $this->session->set_flashdata('mensaje',array('texto' => 'Orden de compra cancelada', 'tipo' => ''));
        }else{
            $this->session->set_flashdata('mensaje',array('texto' => 'Ocurrió un error al cancelar la orden de compra', 'tipo' => 'alert-error'));
        }
        redirect('compras/ordenes_compra/index');
    }
}

?>
