<?php

/**
 * Description of pedidos
 *
 * @author cherra
 */
class Pedidos extends CI_Controller {
    
    private $folder = 'ventas/';
    private $clase = 'pedidos/';
    private $iconos_estado_pedido = array(
        0 => 'icon-remove',
        1 => 'icon-inbox',
        2 => 'icon-gears',
        3 => 'icon-check',
        4 => 'icon-truck',
        5 => 'icon-flag-checkered',
        6 => 'icon-qrcode'
    );
    
    function __construct() {
        parent::__construct();
    }
    
    public function index( $offset = 0 ){
        $this->load->model('pedido','p');
        $this->load->model('cliente','c');
        $this->load->model('sucursal','s');
        $this->load->model('preferencias/usuario','u');
        
        $this->config->load("pagination");
    	
        $data['titulo'] = 'Pedidos <small>Lista</small>';
        $data['link_add'] = anchor($this->folder.$this->clase.'pedidos_agregar','<i class="icon-plus icon-white"></i> Nuevo', array('class' => 'btn btn-inverse'));
    	$data['action'] = $this->folder.$this->clase.'index';
        
        // Filtro de busqueda (se almacenan en la sesión a través de un hook)
        $filtro = $this->session->userdata('filtro');
        if($filtro)
            $data['filtro'] = $filtro;
        
        $page_limit = $this->config->item("per_page");
    	$datos = $this->p->get_paged_list($page_limit, $offset, $filtro)->result();
    	
        // generar paginacion
    	$this->load->library('pagination');
    	$config['base_url'] = site_url($this->folder.$this->clase.'index');
    	$config['total_rows'] = $this->p->count_all($filtro);
    	$config['per_page'] = $page_limit;
    	$config['uri_segment'] = 4;
    	$this->pagination->initialize($config);
    	$data['pagination'] = $this->pagination->create_links();
    	
    	// generar tabla
    	$this->load->library('table');
    	$this->table->set_empty('&nbsp;');
    	$tmpl = array ( 'table_open' => '<table class="' . $this->config->item('tabla_css') . '" >' );
    	$this->table->set_template($tmpl);
    	$this->table->set_heading('E','Número','Fecha','Cliente','Sucursal','Municipio','Estado','Vendedor', 'Total', '', '','','');
    	foreach ($datos as $d) {
            $sucursal = $this->s->get_by_id($d->id_cliente_sucursal)->row();
            $cliente = $this->c->get_by_id($sucursal->id_cliente)->row();
            $usuario = $this->u->get_by_id($d->id_usuario)->row();
            $importe = $this->p->get_importe($d->id);
    		$this->table->add_row(
                        '<i class="'.$this->iconos_estado_pedido[$d->estado].'"></i>',
                        $d->id,
                        $d->fecha,
                        $cliente->nombre,
                        $sucursal->numero.'.- '.$sucursal->nombre,
                        $sucursal->municipio,
                        $sucursal->estado,
                        $usuario->nombre,
                        array('data' => number_format($importe,2), 'style' => 'text-align: right;'),
                        array('data' => anchor_popup($this->folder.$this->clase.'pedidos_documento/' . $d->id, '<i class="icon-print"></i>', array('class' => 'btn btn-small', 'title' => 'Imprimir')), 'style' => 'text-align: right;'),
                        array('data' => anchor_popup($this->folder.$this->clase.'pedidos_excel/' . $d->id, '<i class="icon-list-alt"></i>', array('class' => 'btn btn-small', 'title' => 'Exportar a excel')), 'style' => 'text-align: right;'),
                        array('data' => ($d->estado > 0 && $d->estado < 5 ? anchor($this->folder.$this->clase.'pedidos_editar/' . $d->id, '<i class="icon-edit"></i>', array('class' => 'btn btn-small', 'title' => 'Editar')) :  '<a class="btn btn-small" disabled><i class="icon-edit"></i></a>'), 'style' => 'text-align: right;'),
                        array('data' => ($d->estado > 0 ? anchor($this->folder.$this->clase.'pedidos_duplicar/' . $d->id, '<i class="icon-copy"></i>', array('class' => 'btn btn-small', 'title' => 'Duplicar')) :  '<a class="btn btn-small" disabled><i class="icon-copy"></i></a>'), 'style' => 'text-align: right;'),
                        array('data' => ($d->estado > 0 && $d->estado < 5 ? anchor($this->folder.$this->clase.'pedidos_cancelar/' . $d->id, '<i class="icon-ban-circle"></i>', array('class' => 'btn btn-small cancelar', 'title' => 'Cancelar')) :  '<a class="btn btn-small" disabled><i class="icon-ban-circle"></i></a>'), 'style' => 'text-align: right;')
    		);
                if($d->estado == 0)
                    $this->table->add_row_class('muted');
                else
                    $this->table->add_row_class('');
    	}
    	$data['table'] = $this->table->generate();
    	
    	$this->load->view('ventas/lista', $data);
    }
    
    public function pedidos_agregar( $id_cliente = NULL, $id_sucursal = NULL, $id_contacto = NULL, $id_llamada = NULL ) {
        // Nuevo cliente
        if(!empty($id_cliente) && $id_cliente == 'nuevo'){
            redirect('ventas/clientes/clientes_agregar/pedido');
        }
        // Nueva sucursal
        if(!empty($id_sucursal) && $id_sucursal == 'nuevo'){
            redirect('ventas/clientes/sucursales_agregar/'.$id_cliente.'/pedido');
        }
        // Nuevo contacto
        if(!empty($id_contacto) && $id_contacto == 'nuevo'){
            redirect('ventas/clientes/contactos_agregar/'.$id_cliente.'/'.$id_sucursal.'/pedido');
        }
    	$this->load->model('pedido', 'p');
        $this->load->model('ruta','r');
        $this->load->model('cliente','c');
        $this->load->model('sucursal','s');
        $this->load->model('contacto','co');
        
    	$data['titulo'] = 'Pedido <small>Registro nuevo</small>';
    	$data['link_back'] = anchor($this->folder.$this->clase.'index','<i class="icon-arrow-left"></i> Regresar',array('class'=>'btn'));
    
    	$data['action'] = site_url($this->folder.$this->clase.'pedidos_agregar');
        if(!empty($id_cliente)){
            $data['cliente'] = $this->c->get_by_id($id_cliente)->row();
            if(!empty($id_sucursal)){
                $data['sucursal'] = $this->s->get_by_id($id_sucursal)->row();
                if(!empty($id_contacto)){
                    $data['contacto'] = $this->co->get_by_id($id_contacto)->row();
                    if(!empty($id_llamada)){
                        $data['id_llamada'] = $id_llamada;
                    }
                }
            }
        }
        
        $data['rutas'] = $this->r->get_all()->result();
        $this->load->view('ventas/pedidos/pedidos_formulario', $data);
    }
    
    // Método para guardar un pedido (Ajax)
    public function pedidos_guardar( $id = NULL ){
        if($this->input->is_ajax_request()){
            $this->load->model('pedido','p');
            $this->load->model('orden_salida','os');
            $this->load->model('producto','pr');
            if( ($datos = $this->input->post()) || ($datos = $this->input->get()) ){
                $respuesta = 'OK';
                $pedido = array(
                    'id_usuario' => $this->session->userdata('userid'),
                    'id_cliente_sucursal' => $datos['id_cliente_sucursal'],
                    'id_contacto' => $datos['id_contacto'],
                    'id_ruta' => $datos['id_ruta'],
                    'id_llamada' => $datos['id_llamada'],
                    'observaciones' => $datos['observaciones'],
                    'orden_compra' => $datos['orden_compra']
                );
                $this->db->trans_start();
                
                $edicion = FALSE;
                if(empty($id)){ // Si es un pedido nuevo
                    $id_pedido = $this->p->save($pedido);
                    $mensaje = 'Se generó el pedido no. '.$id_pedido;
                }else{  // Si se está editando el pedido
                    $edicion = TRUE;
                    $id_pedido = $id;
                    unset($pedido['id_usuario']); // Se quita el usuario para respetar al que registró el pedido originalmente.
                    $this->p->update($id, $pedido);
                    $this->p->delete_presentaciones($id);  // Se borran los productos
                    $mensaje = 'Se actualizó el pedido no. '.$id_pedido;
                }
                if( $id_pedido ){
                    $pedido = $this->p->get_by_id($id_pedido)->row();
                    $tasa_iva = $this->configuracion->get_valor('iva');
                    //$salida = $this->os->get_by_id($pedido->id_orden_salida)->row();
                    $this->os->delete_presentaciones($pedido->id_orden_salida);
                    foreach($datos['productos'] as $producto){
                        $data = $this->pr->get_by_id($producto[4])->row();
                        if($data->iva == 1)
                            $iva = $tasa_iva;
                        else
                            $iva = 0;
                        // Presentaciones del pedido
                        $presentacion = array(
                            'id_pedido' => $id_pedido,
                            'id_producto_presentacion' => $producto[0],
                            'cantidad' => $producto[1],
                            'precio' => $producto[2],
                            'observaciones' => $producto[3],
                            'iva' => $iva
                        );
                        if( !($this->p->save_presentacion($presentacion)) ){
                            $respuesta = 'Error';
                            $this->session->set_flashdata('mensaje',array('texto' => 'Error al guardar el pedido', 'tipo' => 'alert-error'));
                        }
                        if($edicion && ($pedido->estado > 1 && $pedido->estado < 4)){  // Si el estado del pedido es mayor a 1 quiere decir que es una edición de pedido
                            // Presentaciones de la orden de salida
                            $presentacion_os = array(
                                'id_orden_salida' => $pedido->id_orden_salida,
                                'id_producto_presentacion' => $producto[0],
                                'cantidad' => $producto[1],
                                'observaciones' => $producto[3]
                            );
                            if( !($this->os->save_presentacion($presentacion_os)) ){
                                $respuesta = 'Error';
                                $this->session->set_flashdata('mensaje',array('texto' => 'Error al guardar la orden de salida', 'tipo' => 'alert-error'));
                            }
                        }
                    }
                }else{
                    $respuesta = 'Error';
                    $this->session->set_flashdata('mensaje',array('texto' => 'Error al guardar el pedido', 'tipo' => 'alert-error'));
                }
                $this->db->trans_complete();
                $this->session->set_flashdata('mensaje', array('texto' => $mensaje, 'tipo' => 'alert-success'));
                echo $respuesta;
            }
        }
    }
    
    public function pedidos_editar( $id = NULL, $id_cliente = NULL, $id_sucursal = NULL, $id_contacto = NULL ){
        // Nueva sucursal
        if(!empty($id_sucursal) && $id_sucursal == 'nuevo'){
            redirect('ventas/clientes/sucursales_agregar/'.$id_cliente.'/pedido/'.$id);
        }
        // Nuevo contacto
        if(!empty($id_contacto) && $id_contacto == 'nuevo'){
            redirect('ventas/clientes/contactos_agregar/'.$id_cliente.'/'.$id_sucursal.'/pedido/'.$id);
        }
        
        $this->load->model('pedido','p');
        
        $datos = $this->p->get_by_id($id);
        if (empty($id) OR $datos->num_rows() <= 0) {
            redirect($this->folder.$this->clase.'index');
        }
        
        $this->load->model('cliente','c');
        $this->load->model('ruta','r');
        $this->load->model('sucursal','s');
        $this->load->model('contacto','co');
        $this->load->model('producto','pro');
        $this->load->model('cliente_presentacion', 'cp');
        
        $data['titulo'] = 'Pedido <small>Editar</small>';
    	$data['link_back'] = anchor($this->folder.$this->clase.'index','<i class="icon-arrow-left"></i> Regresar',array('class'=>'btn'));
        $data['action'] = site_url($this->folder.$this->clase.'pedidos_editar/'.$id);
        
        $pedido = $datos->row();
        $data['pedido'] = $pedido;
        
        $sucursal = !empty($id_sucursal) ? $id_sucursal : $pedido->id_cliente_sucursal;
        $contacto = !empty($id_contacto) ? $id_contacto : $pedido->id_contacto;

        $data['sucursal'] = $this->s->get_by_id($sucursal)->row();
        $data['cliente'] = $this->c->get_by_id($data['sucursal']->id_cliente)->row();
        if(!(!empty($id_sucursal) && empty($id_contacto)))
            $data['contacto'] = $this->co->get_by_id($contacto)->row();
        $data['ruta'] = $this->r->get_by_id($pedido->id_ruta)->row();
        
        $data['rutas'] = $this->r->get_all()->result();
        
        $presentaciones = $this->p->get_presentaciones($id)->result();
        foreach($presentaciones as $p){
            $ppc = $this->cp->get_presentacion($data['cliente']->id, $p->id_producto_presentacion)->row();
            if($ppc){
                $producto = $this->pro->get_by_id($ppc->id_producto)->row();
                $data['presentaciones'][] = (object)array(
                    'id_producto_presentacion' => $p->id_producto_presentacion,
                    'cantidad' => $p->cantidad,
                    'precio' => $p->precio,
                    'producto' => $ppc->producto,
                    'id_producto' => $producto->id,
                    'presentacion' => $ppc->presentacion,
                    'codigo' => $ppc->codigo,
                    'observaciones' => $p->observaciones);
            }
        }
        $data['icono_estado'] = '<i class="'.$this->iconos_estado_pedido[$pedido->estado].' icon-2x"></i>';
        /*if($data['pedido']->estado == '1')
            $data['action_estado'] = anchor($this->folder.$this->clase.'siguiente_estado/'.$id, 'Autorizar', array('class' => 'btn btn-success input-block-level', 'id' => 'autorizar'));
            */
        $data['link_imprimir'] = anchor_popup($this->folder.$this->clase.'pedidos_documento/' . $id, '<i class="icon-print"></i> Imprimir', array('class' => 'btn', 'title' => 'Imprimir'));
        $this->load->view('ventas/pedidos/pedidos_formulario', $data);
    }
    
    public function pedidos_cancelar( $id = NULL ){
        if(empty($id)){
            redirect('ventas/pedidos/index');
        }
        
        $this->load->model('pedido','p');
        $this->load->model('orden_salida','os');
        $this->db->trans_start();
        $pedido = $this->p->get_by_id($id)->row();
        $respuesta = $this->p->cancelar($id);
        $respuesta_os = TRUE;
        if($pedido->estado < 4)
            $respuesta_os = $this->os->cancelar($pedido->id_orden_salida);
        $this->db->trans_complete();
        if($respuesta && $respuesta_os){
            $this->session->set_flashdata('mensaje',array('texto' => 'Pedido cancelado', 'tipo' => ''));
        }else{
            $this->session->set_flashdata('mensaje',array('texto' => 'Ocurrió un error al cancelar el pedido', 'tipo' => 'alert-error'));
        }
        redirect('ventas/pedidos/index');
    }
    
    public function pedidos_duplicar( $id = NULL, $incluir_id = FALSE, $origen = 'index', $id_llamada = 0 ){
        if(!empty($id)){
            $this->load->model('pedido','p');
            $id_usuario = $this->session->userdata('userid');
            $respuesta = $this->p->duplicar( $id, $id_llamada, $id_usuario );   // Debe regresar el id del pedido nuevo
            if($respuesta){
                $this->session->set_flashdata('mensaje',array('texto' => 'Pedido duplicado', 'tipo' => ''));
            }else{
                $this->session->set_flashdata('mensaje',array('texto' => 'Ocurrió un error al duplicar el pedido', 'tipo' => 'alert-error'));
            }
        }
        if($incluir_id)
            redirect('ventas/pedidos/'.$origen.'/'.$respuesta);
        else
            redirect('ventas/pedidos/'.$origen);
    }
    
    
    public function siguiente_estado( $id = NULL ){
        if(!empty($id)){
            $this->load->model('pedido','p');
            $pedido = $this->p->get_by_id($id)->row();
            if($pedido){
                $this->p->update($id, array('estado' => $pedido->estado + 1));
            }
        }
        redirect($this->folder.$this->clase.'pedidos_editar/'.$id);
    }
    
    public function pedidos_consolidar( $offset = 0 ){
        $this->load->model('pedido','p');
        
        $this->config->load("pagination");
    	
        $data['titulo'] = 'Pedidos por consolidar <small>Agrupados por ruta</small>';
        //$data['link_add'] = anchor($this->folder.$this->clase.'pedidos_agregar','<i class="icon-plus icon-white"></i> Nuevo', array('class' => 'btn btn-inverse'));
    	$data['action'] = $this->folder.$this->clase.'pedidos_consolidar';
        
        // Filtro de busqueda (se almacenan en la sesión a través de un hook)
        $filtro = $this->session->userdata('filtro');
        if($filtro)
            $data['filtro'] = $filtro;
        
        $page_limit = $this->config->item("per_page");
    	$datos = $this->p->get_grouped_by_ruta($page_limit, $offset, $filtro, array('1'))->result();
    	
        // generar paginacion
    	$this->load->library('pagination');
    	$config['base_url'] = site_url($this->folder.$this->clase.'pedidos_consolidar');
    	$config['total_rows'] = $this->p->count_grouped_by_ruta($filtro, array('4'));
    	$config['per_page'] = $page_limit;
    	$config['uri_segment'] = 4;
    	$this->pagination->initialize($config);
    	$data['pagination'] = $this->pagination->create_links();
    	
    	// generar tabla
    	$this->load->library('table');
    	$this->table->set_empty('&nbsp;');
    	$tmpl = array ( 'table_open' => '<table class="' . $this->config->item('tabla_css') . '" >' );
    	$this->table->set_template($tmpl);
    	$this->table->set_heading('Ruta','Primer pedido','Último pedido','Pedidos','Peso','Piezas','Total', '');
    	foreach ($datos as $d) {
            $this->table->add_row(
                    $d->ruta,
                    $d->desde,
                    $d->hasta,
                    $d->pedidos,
                    array('data' => number_format($d->peso,2).'kg', 'style' => 'text-align: right;'),
                    array('data' => number_format($d->piezas,2), 'style' => 'text-align: right;'),
                    array('data' => number_format($d->total,2), 'style' => 'text-align: right;'),
                    array('data' => anchor($this->folder.$this->clase.'pedidos_consolidar_ruta/' . $d->id_ruta, '<i class="icon-list"></i>', array('class' => 'btn btn-small', 'title' => 'Pedidos')), 'style' => 'text-align: right;')
            );
    	}
    	$data['table'] = $this->table->generate();
    	
    	$this->load->view('ventas/lista', $data);
    }
    
    public function pedidos_consolidar_ruta( $id_ruta = NULL, $offset = 0 ){
        $this->load->model('pedido','p');
        $this->load->model('cliente','c');
        $this->load->model('sucursal','s');
        $this->load->model('preferencias/usuario','u');
        $this->load->model('ruta','r');
        $this->load->model('orden_salida','os');
        $this->load->model('almacen','a');
        
        $this->config->load("pagination");
    	
        $data['titulo'] = 'Consolidar pedidos para envío <small>Lista por ruta</small>';
        //$data['link_add'] = anchor($this->folder.$this->clase.'pedidos_agregar','<i class="icon-plus icon-white"></i> Nuevo', array('class' => 'btn btn-inverse'));
    	$data['action'] = $this->folder.$this->clase.'pedidos_consolidar_ruta/'.$id_ruta;
        $data['link_back'] = anchor($this->folder.$this->clase.'pedidos_consolidar/'.$offset,'<i class="icon-arrow-left"></i> Regresar',array('class'=>'btn'));
        $data['rutas'] = $this->r->get_all()->result();
        $data['link_concentrado'] = anchor_popup($this->folder.$this->clase.'concentrado_documento/'.$id_ruta, '<i class="icon-print"></i> Concentrado', array('class' => 'btn', 'title' => 'Concentrado'));
        
        if(!empty($id_ruta)){
            $data['almacenes'] = $this->a->get_all()->result();
            $data['ruta'] = $this->r->get_by_id($id_ruta)->row();
            
            // A la fecha programada se le agregan los días predefinidos en la configuración
            $fecha_programada = date_create(date('Y-m-d H:i:s'));
            date_add($fecha_programada, date_interval_create_from_date_string($this->configuracion->get_valor('salidas_dias').' days'));
            $data['fecha_programada'] = date_format($fecha_programada, 'Y-m-d');
            if( ($pedidos = $this->input->post('pedidos')) ){
                foreach($pedidos AS $id){
                    $pedido = $this->p->get_by_id($id)->row();
                    
                    // Se genera la orden de salida de almacén
                    $presentaciones = $this->p->get_presentaciones($id)->result();
                    
                    $salida = array(
                        'id_cliente_sucursal' => $pedido->id_cliente_sucursal,
                        'id_ruta' => $pedido->id_ruta,
                        'id_usuario' => $this->session->userdata('userid'),
                        'id_almacen' => $this->input->post('id_almacen'),
                        'fecha' => date('Y-m-d H:i:s'),
                        'origen' => $this->configuracion->get_valor('pedidos_prefijo').$id,
                        'fecha_programada' => $this->input->post('fecha_programada').' '.$this->input->post('hora_programada')
                    );
                    $this->db->trans_start();
                    $id_orden = $this->os->save($salida);
                    foreach($presentaciones AS $p){
                        $presentacion = array(
                            'id_orden_salida' => $id_orden,
                            'id_producto_presentacion' => $p->id_producto_presentacion,
                            'cantidad' => $p->cantidad
                        );
                        $this->os->save_presentacion($presentacion);
                    }
                    
                    $this->p->update($id, array('estado' => $pedido->estado + 1, 'id_orden_salida' => $id_orden));
                    $this->db->trans_complete();
                }
            }
            // Filtro de busqueda (se almacenan en la sesión a través de un hook)
            $filtro = $this->session->userdata('filtro');
            if($filtro)
                $data['filtro'] = $filtro;

            $page_limit = $this->config->item("per_page");
            // Pedidos en preorden y en proceso
            //$datos = (object)array_merge($this->p->get_by_ruta($id_ruta, '1', $page_limit, $offset, $filtro)->result(), $this->p->get_by_ruta($id_ruta, '2', $page_limit, $offset, $filtro)->result());
            // Pedidos en preorden
            $datos = $this->p->get_by_ruta($id_ruta, '1', $page_limit, $offset, $filtro)->result();
            
            // generar paginacion
            $this->load->library('pagination');
            $config['base_url'] = site_url($this->folder.$this->clase.'index');
            $config['total_rows'] = $this->p->count_by_ruta($id_ruta, '1',$filtro);
            //$config['total_rows'] += $this->p->count_by_ruta($id_ruta, '2',$filtro);
            $config['per_page'] = $page_limit;
            $config['uri_segment'] = 5;
            $this->pagination->initialize($config);
            $data['pagination'] = $this->pagination->create_links();

            // generar tabla
            $this->load->library('table');
            $this->table->set_empty('&nbsp;');
            $tmpl = array ( 'table_open' => '<table class="' . $this->config->item('tabla_css') . '" >' );
            $this->table->set_template($tmpl);
            $this->table->set_heading('','Núm.','Fecha','Cliente','Sucursal','Municipio','Estado','Vendedor', 'Peso','Piezas','Total', '', '');
            $total_peso = 0;
            $total_piezas = 0;
            $total_importe = 0;
            foreach ($datos as $d) {
                $sucursal = $this->s->get_by_id($d->id_cliente_sucursal)->row();
                $cliente = $this->c->get_by_id($sucursal->id_cliente)->row();
                $usuario = $this->u->get_by_id($d->id_usuario)->row();
                $importe = $this->p->get_importe($d->id);
                $this->table->add_row(
                        $d->estado == '1' ? '<input type="checkbox" name="pedidos[]" value="'.$d->id.'"/>' : '<i class="icon-gears"></i>',
                        $d->id,
                        $d->fecha,
                        $cliente->nombre,
                        $sucursal->numero.' | '.$sucursal->nombre,
                        $sucursal->municipio,
                        $sucursal->estado,
                        $usuario->nombre,
                        array('data' => number_format($d->peso,2).'kg', 'style' => 'text-align: right;'),
                        array('data' => number_format($d->piezas,2), 'style' => 'text-align: right;'),
                        array('data' => number_format($importe,2), 'style' => 'text-align: right;'),
                        array('data' => anchor_popup($this->folder.$this->clase.'pedidos_documento/' . $d->id, '<i class="icon-print"></i>', array('class' => 'btn btn-small', 'title' => 'Imprimir')), 'style' => 'text-align: right;'),
                        array('data' => anchor($this->folder.$this->clase.'pedidos_editar/' . $d->id, '<i class="icon-edit"></i>', array('class' => 'btn btn-small', 'title' => 'Editar')), 'style' => 'text-align: right;')
                );
                if($d->estado == 0)
                    $this->table->add_row_class('muted');
                else
                    $this->table->add_row_class('');
                $total_peso += $d->peso;
                $total_piezas += $d->piezas;
                $total_importe += $importe;
            }
            // Totales
            $this->table->add_row(
                    '', '', '', '', '', '', '', '',
                    array('data' => number_format($total_peso,2).'kg', 'style' => 'text-align: right;', 'class' => 'text-info'),
                    array('data' => number_format($total_piezas,2), 'style' => 'text-align: right;', 'class' => 'text-info'),
                    array('data' => number_format($total_importe,2), 'style' => 'text-align: right;', 'class' => 'text-info'),
                    ''
            );
            $data['table'] = $this->table->generate();
        }
    	
    	$this->load->view('ventas/pedidos/consolidar_ruta_lista', $data);
    }
    
    public function pedidos_proceso( $offset = 0 ){
        $this->load->model('pedido','p');
        
        $this->config->load("pagination");
    	
        $data['titulo'] = 'Pedidos en proceso <small>Lista por ruta</small>';
        //$data['link_add'] = anchor($this->folder.$this->clase.'pedidos_agregar','<i class="icon-plus icon-white"></i> Nuevo', array('class' => 'btn btn-inverse'));
    	$data['action'] = $this->folder.$this->clase.'proceso';
        //$data['link_back'] = anchor($this->folder.$this->clase.'consolidar/'.$offset,'<i class="icon-arrow-left"></i> Regresar',array('class'=>'btn'));
        
        // Filtro de busqueda (se almacenan en la sesión a través de un hook)
        $filtro = $this->session->userdata('filtro');
        if($filtro)
            $data['filtro'] = $filtro;
        
        $page_limit = $this->config->item("per_page");
    	$datos = $this->p->get_grouped_by_fecha_programada($page_limit, $offset, $filtro, array('2'))->result();
    	
        // generar paginacion
    	$this->load->library('pagination');
    	$config['base_url'] = site_url($this->folder.$this->clase.'consolidar');
    	$config['total_rows'] = $this->p->count_grouped_by_fecha_programada($filtro, array('2'));
    	$config['per_page'] = $page_limit;
    	$config['uri_segment'] = 4;
    	$this->pagination->initialize($config);
    	$data['pagination'] = $this->pagination->create_links();
    	
    	// generar tabla
    	$this->load->library('table');
    	$this->table->set_empty('&nbsp;');
    	$tmpl = array ( 'table_open' => '<table class="' . $this->config->item('tabla_css') . '" >' );
    	$this->table->set_template($tmpl);
    	$this->table->set_heading('Ruta','Fecha programada', 'Pedidos','Peso','Piezas','Total', '');
    	foreach ($datos as $d) {
            $this->table->add_row(
                    $d->ruta,
                    $d->fecha,
                    $d->pedidos,
                    array('data' => number_format($d->peso,2).'kg', 'style' => 'text-align: right;'),
                    array('data' => number_format($d->piezas,2), 'style' => 'text-align: right;'),
                    array('data' => number_format($d->total,2), 'style' => 'text-align: right;'),
                    array('data' => anchor($this->folder.$this->clase.'pedidos_proceso_ruta/' . $d->id_ruta.'/'.$d->fecha, '<i class="icon-list"></i>', array('class' => 'btn btn-small', 'title' => 'Pedidos')), 'style' => 'text-align: right;')
            );
    	}
    	$data['table'] = $this->table->generate();
    	
    	$this->load->view('ventas/lista', $data);
    }
    
    public function pedidos_proceso_ruta( $id_ruta = NULL, $fecha = NULL,  $offset = 0 ){
        $this->load->model('pedido','p');
        $this->load->model('cliente','c');
        $this->load->model('sucursal','s');
        $this->load->model('preferencias/usuario','u');
        $this->load->model('ruta','r');
        
        $this->config->load("pagination");
    	
        $data['titulo'] = 'Pedidos en proceso <small>Lista por ruta</small>';
        //$data['link_add'] = anchor($this->folder.$this->clase.'pedidos_agregar','<i class="icon-plus icon-white"></i> Nuevo', array('class' => 'btn btn-inverse'));
    	$data['action'] = $this->folder.$this->clase.'pedidos_proceso_ruta/'.$id_ruta;
        $data['link_back'] = '<a href="javascript:history.back(-1)" class="btn"><i class="icon-arrow-left"></i> Regresar</a>';
        $data['rutas'] = $this->r->get_all()->result();
        
        if(!empty($id_ruta)){
            $data['ruta'] = $this->r->get_by_id($id_ruta)->row();
            
            // Filtro de busqueda (se almacenan en la sesión a través de un hook)
            $filtro = $this->session->userdata('filtro');
            if($filtro)
                $data['filtro'] = $filtro;

            $page_limit = $this->config->item("per_page");
            $datos = $this->p->get_by_ruta_fecha_programada($id_ruta, $fecha, '2', $page_limit, $offset, $filtro)->result();

            //die($this->db->last_query());
            // generar paginacion
            $this->load->library('pagination');
            $config['base_url'] = site_url($this->folder.$this->clase.'index');
            $config['total_rows'] = $this->p->count_by_ruta_fecha_programada($id_ruta, $fecha, '2', $filtro);
            $config['per_page'] = $page_limit;
            $config['uri_segment'] = 5;
            $this->pagination->initialize($config);
            $data['pagination'] = $this->pagination->create_links();

            // generar tabla
            $this->load->library('table');
            $this->table->set_empty('&nbsp;');
            $tmpl = array ( 'table_open' => '<table class="' . $this->config->item('tabla_css') . '" >' );
            $this->table->set_template($tmpl);
            $this->table->set_heading('','Núm.','Fecha','Fecha programada','Cliente','Sucursal','Municipio','Estado','Vendedor', 'Peso','Piezas','Total', '');
            $total_peso = 0;
            $total_piezas = 0;
            $total_importe = 0;
            foreach ($datos as $d) {
                $sucursal = $this->s->get_by_id($d->id_cliente_sucursal)->row();
                $cliente = $this->c->get_by_id($sucursal->id_cliente)->row();
                $usuario = $this->u->get_by_id($d->id_usuario)->row();
                $importe = $this->p->get_importe($d->id);
                $this->table->add_row(
                        '<i class="'.$this->iconos_estado_pedido[$d->estado].'"></i>',
                        $d->id,
                        $d->fecha,
                        $d->fecha_programada,
                        $cliente->nombre,
                        $sucursal->numero.' | '.$sucursal->nombre,
                        $sucursal->municipio,
                        $sucursal->estado,
                        $usuario->nombre,
                        array('data' => number_format($d->peso,2).'kg', 'style' => 'text-align: right;'),
                        array('data' => number_format($d->piezas,2), 'style' => 'text-align: right;'),
                        array('data' => number_format($importe,2), 'style' => 'text-align: right;'),
                        array('data' => anchor_popup($this->folder.$this->clase.'pedidos_documento/' . $d->id, '<i class="icon-print"></i>', array('class' => 'btn btn-small', 'title' => 'Imprimir')), 'style' => 'text-align: right;'),
                        array('data' => anchor($this->folder.$this->clase.'pedidos_editar/' . $d->id, '<i class="icon-edit"></i>', array('class' => 'btn btn-small', 'title' => 'Editar')), 'style' => 'text-align: right;')
                );
                if($d->estado == 0)
                    $this->table->add_row_class('muted');
                else
                    $this->table->add_row_class('');
                $total_peso += $d->peso;
                $total_piezas += $d->piezas;
                $total_importe += $importe;
            }
            // Totales
            $this->table->add_row(
                    '', '', '', '', '', '', '', '',
                    array('data' => number_format($total_peso,2).'kg', 'style' => 'text-align: right;', 'class' => 'text-info'),
                    array('data' => number_format($total_piezas,2), 'style' => 'text-align: right;', 'class' => 'text-info'),
                    array('data' => number_format($total_importe,2), 'style' => 'text-align: right;', 'class' => 'text-info'),
                    ''
            );
            $data['table'] = $this->table->generate();
        }
    	
    	$this->load->view('ventas/pedidos/proceso_ruta_lista', $data);
    }
    
    public function pedidos_procesados( $offset = 0 ){
        $this->load->model('pedido','p');
        
        $this->config->load("pagination");
    	
        $data['titulo'] = 'Pedidos listos para envío <small>Lista por ruta</small>';
        //$data['link_add'] = anchor($this->folder.$this->clase.'pedidos_agregar','<i class="icon-plus icon-white"></i> Nuevo', array('class' => 'btn btn-inverse'));
    	$data['action'] = $this->folder.$this->clase.'proceso';
        //$data['link_back'] = anchor($this->folder.$this->clase.'consolidar/'.$offset,'<i class="icon-arrow-left"></i> Regresar',array('class'=>'btn'));
        
        // Filtro de busqueda (se almacenan en la sesión a través de un hook)
        $filtro = $this->session->userdata('filtro');
        if($filtro)
            $data['filtro'] = $filtro;
        
        $page_limit = $this->config->item("per_page");
    	$datos = $this->p->get_grouped_by_fecha_programada($page_limit, $offset, $filtro, array('3'))->result();
    	
        // generar paginacion
    	$this->load->library('pagination');
    	$config['base_url'] = site_url($this->folder.$this->clase.'consolidar');
    	$config['total_rows'] = $this->p->count_grouped_by_fecha_programada($filtro, array('3'));
    	$config['per_page'] = $page_limit;
    	$config['uri_segment'] = 4;
    	$this->pagination->initialize($config);
    	$data['pagination'] = $this->pagination->create_links();
    	
    	// generar tabla
    	$this->load->library('table');
    	$this->table->set_empty('&nbsp;');
    	$tmpl = array ( 'table_open' => '<table class="' . $this->config->item('tabla_css') . '" >' );
    	$this->table->set_template($tmpl);
    	$this->table->set_heading('Ruta','Fecha programada', 'Pedidos','Peso','Piezas','Total', '');
    	foreach ($datos as $d) {
            $this->table->add_row(
                    $d->ruta,
                    $d->fecha,
                    $d->pedidos,
                    array('data' => number_format($d->peso,2).'kg', 'style' => 'text-align: right;'),
                    array('data' => number_format($d->piezas,2), 'style' => 'text-align: right;'),
                    array('data' => number_format($d->total,2), 'style' => 'text-align: right;'),
                    array('data' => anchor($this->folder.$this->clase.'pedidos_procesados_ruta/' . $d->id_ruta, '<i class="icon-list"></i>', array('class' => 'btn btn-small', 'title' => 'Pedidos')), 'style' => 'text-align: right;')
            );
    	}
    	$data['table'] = $this->table->generate();
    	
    	$this->load->view('ventas/lista', $data);
    }
    
    public function pedidos_procesados_ruta( $id_ruta = NULL, $offset = 0 ){
        $this->load->model('pedido','p');
        $this->load->model('cliente','c');
        $this->load->model('sucursal','s');
        $this->load->model('preferencias/usuario','u');
        $this->load->model('ruta','r');
        
        $this->config->load("pagination");
    	
        $data['titulo'] = 'Pedidos listos para envío <small>Lista por ruta</small>';
        //$data['link_add'] = anchor($this->folder.$this->clase.'pedidos_agregar','<i class="icon-plus icon-white"></i> Nuevo', array('class' => 'btn btn-inverse'));
    	$data['action'] = $this->folder.$this->clase.'pedidos_proceso_ruta/'.$id_ruta;
        $data['link_back'] = '<a href="javascript:history.back(-1)" class="btn"><i class="icon-arrow-left"></i> Regresar</a>';
        $data['rutas'] = $this->r->get_all()->result();
        
        if(!empty($id_ruta)){
            $data['ruta'] = $this->r->get_by_id($id_ruta)->row();
            
            // Filtro de busqueda (se almacenan en la sesión a través de un hook)
            $filtro = $this->session->userdata('filtro');
            if($filtro)
                $data['filtro'] = $filtro;

            $page_limit = $this->config->item("per_page");
            $datos = $this->p->get_by_ruta($id_ruta, '3', $page_limit, $offset, $filtro)->result();

            // generar paginacion
            $this->load->library('pagination');
            $config['base_url'] = site_url($this->folder.$this->clase.'index');
            $config['total_rows'] = $this->p->count_by_ruta($id_ruta, '3', $filtro);
            $config['per_page'] = $page_limit;
            $config['uri_segment'] = 5;
            $this->pagination->initialize($config);
            $data['pagination'] = $this->pagination->create_links();

            // generar tabla
            $this->load->library('table');
            $this->table->set_empty('&nbsp;');
            $tmpl = array ( 'table_open' => '<table class="' . $this->config->item('tabla_css') . '" >' );
            $this->table->set_template($tmpl);
            $this->table->set_heading('','Núm.','Fecha','Cliente','Sucursal','Municipio','Estado','Vendedor', 'Peso','Piezas','Total', '');
            $total_peso = 0;
            $total_piezas = 0;
            $total_importe = 0;
            foreach ($datos as $d) {
                $sucursal = $this->s->get_by_id($d->id_cliente_sucursal)->row();
                $cliente = $this->c->get_by_id($sucursal->id_cliente)->row();
                $usuario = $this->u->get_by_id($d->id_usuario)->row();
                $importe = $this->p->get_importe($d->id);
                $this->table->add_row(
                        '<i class="'.$this->iconos_estado_pedido[$d->estado].'"></i>',
                        $d->id,
                        $d->fecha,
                        $cliente->nombre,
                        $sucursal->numero.' | '.$sucursal->nombre,
                        $sucursal->municipio,
                        $sucursal->estado,
                        $usuario->nombre,
                        array('data' => number_format($d->peso,2).'kg', 'style' => 'text-align: right;'),
                        array('data' => number_format($d->piezas,2), 'style' => 'text-align: right;'),
                        array('data' => number_format($importe,2), 'style' => 'text-align: right;'),
                        array('data' => anchor_popup($this->folder.$this->clase.'pedidos_documento/' . $d->id, '<i class="icon-print"></i>', array('class' => 'btn btn-small', 'title' => 'Imprimir')), 'style' => 'text-align: right;'),
                        array('data' => anchor($this->folder.$this->clase.'pedidos_editar/' . $d->id, '<i class="icon-edit"></i>', array('class' => 'btn btn-small', 'title' => 'Editar')), 'style' => 'text-align: right;')
                );
                if($d->estado == 0)
                    $this->table->add_row_class('muted');
                else
                    $this->table->add_row_class('');
                $total_peso += $d->peso;
                $total_piezas += $d->piezas;
                $total_importe += $importe;
            }
            // Totales
            $this->table->add_row(
                    '', '', '', '', '', '', '', '',
                    array('data' => number_format($total_peso,2).'kg', 'style' => 'text-align: right;', 'class' => 'text-info'),
                    array('data' => number_format($total_piezas,2), 'style' => 'text-align: right;', 'class' => 'text-info'),
                    array('data' => number_format($total_importe,2), 'style' => 'text-align: right;', 'class' => 'text-info'),
                    ''
            );
            $data['table'] = $this->table->generate();
        }
    	
    	$this->load->view('ventas/pedidos/procesados_ruta_lista', $data);
    }
    
    public function pedidos_enviados( $offset = 0 ){
        $this->load->model('pedido','p');
        
        $this->config->load("pagination");
    	
        $data['titulo'] = 'Pedidos enviados <small>Lista por ruta</small>';
        //$data['link_add'] = anchor($this->folder.$this->clase.'pedidos_agregar','<i class="icon-plus icon-white"></i> Nuevo', array('class' => 'btn btn-inverse'));
    	$data['action'] = $this->folder.$this->clase.'proceso';
        //$data['link_back'] = anchor($this->folder.$this->clase.'consolidar/'.$offset,'<i class="icon-arrow-left"></i> Regresar',array('class'=>'btn'));
        
        // Filtro de busqueda (se almacenan en la sesión a través de un hook)
        $filtro = $this->session->userdata('filtro');
        if($filtro)
            $data['filtro'] = $filtro;
        
        $page_limit = $this->config->item("per_page");
    	$datos = $this->p->get_grouped_by_fecha_programada($page_limit, $offset, $filtro, array('4'))->result();
    	
        // generar paginacion
    	$this->load->library('pagination');
    	$config['base_url'] = site_url($this->folder.$this->clase.'consolidar');
    	$config['total_rows'] = $this->p->count_grouped_by_fecha_programada($filtro, array('4'));
    	$config['per_page'] = $page_limit;
    	$config['uri_segment'] = 4;
    	$this->pagination->initialize($config);
    	$data['pagination'] = $this->pagination->create_links();
    	
    	// generar tabla
    	$this->load->library('table');
    	$this->table->set_empty('&nbsp;');
    	$tmpl = array ( 'table_open' => '<table class="' . $this->config->item('tabla_css') . '" >' );
    	$this->table->set_template($tmpl);
    	$this->table->set_heading('Ruta','Fecha programada', 'Pedidos','Peso','Piezas','Total', '');
    	foreach ($datos as $d) {
            $this->table->add_row(
                    $d->ruta,
                    $d->fecha,
                    $d->pedidos,
                    array('data' => number_format($d->peso,2).'kg', 'style' => 'text-align: right;'),
                    array('data' => number_format($d->piezas,2), 'style' => 'text-align: right;'),
                    array('data' => number_format($d->total,2), 'style' => 'text-align: right;'),
                    array('data' => anchor($this->folder.$this->clase.'pedidos_enviados_ruta/' . $d->id_ruta, '<i class="icon-list"></i>', array('class' => 'btn btn-small', 'title' => 'Pedidos')), 'style' => 'text-align: right;')
            );
    	}
    	$data['table'] = $this->table->generate();
    	
    	$this->load->view('ventas/lista', $data);
    }
    
    public function pedidos_enviados_ruta( $id_ruta = NULL, $offset = 0 ){
        $this->load->model('pedido','p');
        $this->load->model('cliente','c');
        $this->load->model('sucursal','s');
        $this->load->model('preferencias/usuario','u');
        $this->load->model('ruta','r');
        
        $this->config->load("pagination");
    	
        $data['titulo'] = 'Pedidos en proceso <small>Lista por ruta</small>';
        //$data['link_add'] = anchor($this->folder.$this->clase.'pedidos_agregar','<i class="icon-plus icon-white"></i> Nuevo', array('class' => 'btn btn-inverse'));
    	$data['action'] = $this->folder.$this->clase.'pedidos_enviados_ruta/'.$id_ruta;
        $data['link_back'] = '<a href="javascript:history.back(-1)" class="btn"><i class="icon-arrow-left"></i> Regresar</a>';
        $data['rutas'] = $this->r->get_all()->result();
        
        if(!empty($id_ruta)){
            $data['ruta'] = $this->r->get_by_id($id_ruta)->row();
            
            if( ($pedidos = $this->input->post('pedidos')) ){
                foreach($pedidos AS $id){
                    $this->p->update($id, array('estado' => '5'));
                }
            }
            
            // Filtro de busqueda (se almacenan en la sesión a través de un hook)
            $filtro = $this->session->userdata('filtro');
            if($filtro)
                $data['filtro'] = $filtro;

            $page_limit = $this->config->item("per_page");
            $datos = $this->p->get_by_ruta($id_ruta, '4', $page_limit, $offset, $filtro)->result();

            // generar paginacion
            $this->load->library('pagination');
            $config['base_url'] = site_url($this->folder.$this->clase.'index');
            $config['total_rows'] = $this->p->count_by_ruta($id_ruta, '4', $filtro);
            $config['per_page'] = $page_limit;
            $config['uri_segment'] = 5;
            $this->pagination->initialize($config);
            $data['pagination'] = $this->pagination->create_links();

            // generar tabla
            $this->load->library('table');
            $this->table->set_empty('&nbsp;');
            $tmpl = array ( 'table_open' => '<table class="' . $this->config->item('tabla_css') . '" >' );
            $this->table->set_template($tmpl);
            $this->table->set_heading('','Núm.','Fecha','Cliente','Sucursal','Municipio','Estado','Vendedor', 'Peso','Piezas','Total', '');
            $total_peso = 0;
            $total_piezas = 0;
            $total_importe = 0;
            foreach ($datos as $d) {
                $sucursal = $this->s->get_by_id($d->id_cliente_sucursal)->row();
                $cliente = $this->c->get_by_id($sucursal->id_cliente)->row();
                $usuario = $this->u->get_by_id($d->id_usuario)->row();
                $importe = $this->p->get_importe($d->id);

                // si se encuentra un pedido ya reubicado mostrar link diferente
                $link_reubicar = array('data' => anchor($this->folder.$this->clase.'pedidos_reubicar/' . $d->id, '<i class="icon-random"></i>', array('class' => 'btn btn-small', 'title' => 'Reubicar')), 'style' => 'text-align: right;');
                if ($d->reubicado)
                    $link_reubicar = array('data' => anchor($this->folder.$this->clase.'pedidos_reubicados/' . $id_ruta . '/' . $d->id, '<i class="icon-random"></i>', array('class' => 'btn btn-small btn-warning', 'title' => 'Reubicado')), 'style' => 'text-align: right;');

                $this->table->add_row(
                        $d->estado == '4' ? '<input type="checkbox" name="pedidos[]" value="'.$d->id.'"/>' : '<i class="'.$this->iconos_estado_pedido[$d->estado].'"></i>',
                        $d->id,
                        $d->fecha,
                        $cliente->nombre,
                        $sucursal->numero.' | '.$sucursal->nombre,
                        $sucursal->municipio,
                        $sucursal->estado,
                        $usuario->nombre,
                        array('data' => number_format($d->peso,2).'kg', 'style' => 'text-align: right;'),
                        array('data' => number_format($d->piezas,2), 'style' => 'text-align: right;'),
                        array('data' => number_format($importe,2), 'style' => 'text-align: right;'),
                        array('data' => anchor_popup($this->folder.$this->clase.'pedidos_documento/' . $d->id, '<i class="icon-print"></i>', array('class' => 'btn btn-small', 'title' => 'Imprimir')), 'style' => 'text-align: right;'),
                        array('data' => anchor($this->folder.$this->clase.'pedidos_editar/' . $d->id, '<i class="icon-edit"></i>', array('class' => 'btn btn-small', 'title' => 'Editar')), 'style' => 'text-align: right;'),
                        $link_reubicar
                );
                if($d->estado == 0)
                    $this->table->add_row_class('muted');
                else
                    $this->table->add_row_class('');
                $total_peso += $d->peso;
                $total_piezas += $d->piezas;
                $total_importe += $importe;
            }
            // Totales
            $this->table->add_row(
                    '', '', '', '', '', '', '', '',
                    array('data' => number_format($total_peso,2).'kg', 'style' => 'text-align: right;', 'class' => 'text-info'),
                    array('data' => number_format($total_piezas,2), 'style' => 'text-align: right;', 'class' => 'text-info'),
                    array('data' => number_format($total_importe,2), 'style' => 'text-align: right;', 'class' => 'text-info'),
                    ''
            );
            $data['table'] = $this->table->generate();
        }
    	
    	$this->load->view('ventas/pedidos/enviados_ruta_lista', $data);
    }
    
    /**
     * VIEW
     * mostrar lista de pedidos ya reubicados
     * @param int $id_ruta
     * @param int $id_pedido
     * @return view
     */
    public function pedidos_reubicados($id_ruta, $id_pedido) {
        if (empty($id_pedido)) redirect('ventas/pedidos/pedidos_enviados_ruta/' . $id_ruta);

        $this->load->model('pedido_reubicado', 'pr');
        
        // obtener los pedidos reubicados
        $pedidos_reubicados = $this->pr->getReubicadosByPedido($id_pedido);
        if (sizeof($pedidos_reubicados) === 0) redirect('ventas/pedidos/pedidos_enviados_ruta/' . $id_ruta);

        // si el pedido aun tiene remanentes de producto mostrar enlace
        // para agregar pedido reubicado
        $remanente = $this->pr->hasPedidoRemanentes($id_pedido);
        $link_add = '';
        if ($remanente)
            $link_add = anchor($this->folder.$this->clase.'pedidos_reubicar/' . $id_pedido,'<i class="icon-plus icon-white"></i> Nuevo', array('class' => 'btn btn-inverse'));

        $data['link_add'] = $link_add;
        $data['pedidos_reubicados'] = $pedidos_reubicados;

        $this->load->view('ventas/pedidos/pedidos_reubicados', $data);
    }

    /**
     * VIEW
     * mostrar formulario para reubicar un pedido
     * @param  int $id
     * @param  int $id_cliente
     * @param  int $id_sucursal
     * @param  int $id_contacto
     * @return view
     */
    public function pedidos_reubicar($id = NULL, $id_cliente = NULL, $id_sucursal = NULL, $id_contacto = NULL ) {
        // Nueva sucursal
        if(!empty($id_sucursal) && $id_sucursal == 'nuevo'){
            redirect('ventas/clientes/sucursales_agregar/'.$id_cliente.'/pedido/'.$id);
        }
        // Nuevo contacto
        if(!empty($id_contacto) && $id_contacto == 'nuevo'){
            redirect('ventas/clientes/contactos_agregar/'.$id_cliente.'/'.$id_sucursal.'/pedido/'.$id);
        }

        $this->load->model('pedido','p');
        $this->load->model('pedido_reubicado', 'pr');
        $this->load->model('cliente','c');
        $this->load->model('ruta','r');
        $this->load->model('sucursal','s');
        $this->load->model('contacto','co');
        $this->load->model('producto','pro');
        $this->load->model('cliente_presentacion', 'cp');

        $data['titulo'] = 'Pedido <small>Reubicar</small>';
        $data['link_back'] = anchor($this->folder.$this->clase.'index','<i class="icon-arrow-left"></i> Regresar',array('class'=>'btn'));
        $data['action'] = site_url($this->folder.$this->clase.'pedidos_reubicar/'.$id);

        // obtener pedido
        $datos = $this->p->get_by_id($id);

        // si no se encuentra redireccionar a pedidos enviados
        if (empty($id) OR $datos->num_rows() <= 0) {
            redirect('ventas/pedidos/pedidos_enviados');
        }
        
        $pedido = $datos->row();
        $data['pedido'] = $pedido;
        
        $sucursal = !empty($id_sucursal) ? $id_sucursal : $pedido->id_cliente_sucursal;
        $contacto = !empty($id_contacto) ? $id_contacto : $pedido->id_contacto;

        $data['sucursal'] = $this->s->get_by_id($sucursal)->row();
        $data['cliente'] = $this->c->get_by_id($data['sucursal']->id_cliente)->row();
        if(!(!empty($id_sucursal) && empty($id_contacto)))
            $data['contacto'] = $this->co->get_by_id($contacto)->row();
        $data['ruta'] = $this->r->get_by_id($pedido->id_ruta)->row();
        $data['rutas'] = $this->r->get_all()->result();
        
        $isPedidoReubicado = $this->pr->isPedidoReubicado($id);
        if (!$isPedidoReubicado):
            // si el pedido no tiene pedidos reubicados
            // mostrar presentaciones del pedido original
            $presentaciones = $this->p->get_presentaciones($id)->result();
            foreach($presentaciones as $p){
                $ppc = $this->cp->get_presentacion($data['cliente']->id, $p->id_producto_presentacion)->row();
                if($ppc){
                    $producto = $this->pro->get_by_id($ppc->id_producto)->row();
                    $data['presentaciones'][] = (object)array(
                        'id_producto_presentacion' => $p->id_producto_presentacion,
                        'cantidad' => $p->cantidad,
                        'precio' => $p->precio,
                        'producto' => $ppc->producto,
                        'id_producto' => $producto->id,
                        'presentacion' => $ppc->presentacion,
                        'codigo' => $ppc->codigo,
                        'observaciones' => $p->observaciones);
                }
            }
        else:
            // si el pedido tiene pedidos reubicados
            // mostrar remanentes entre el pedido original y los pedidos reubicados
            $presentaciones = $this->pr->getPedidoRemanentes($id);
            foreach($presentaciones as $p){
                $ppc = $this->cp->get_presentacion($data['cliente']->id, $p->id_producto_presentacion)->row();
                if($ppc){
                    $producto = $this->pro->get_by_id($ppc->id_producto)->row();
                    $data['presentaciones'][] = (object)array(
                        'id_producto_presentacion' => $p->id_producto_presentacion,
                        'cantidad' => $p->cantidad,
                        'precio' => $p->precio,
                        'producto' => $ppc->producto,
                        'id_producto' => $producto->id,
                        'presentacion' => $ppc->presentacion,
                        'codigo' => $ppc->codigo,
                        'observaciones' => $p->observaciones,
                        'remanente' => $p->remanente);
                }
            }
        endif;

        $data['icono_estado'] = '<i class="'.$this->iconos_estado_pedido[$pedido->estado].' icon-2x"></i>';
        $data['link_imprimir'] = anchor_popup($this->folder.$this->clase.'pedidos_documento/' . $id, '<i class="icon-print"></i> Imprimir', array('class' => 'btn', 'title' => 'Imprimir'));
        $this->load->view('ventas/pedidos/pedidos_reubicados_formulario', $data);
    }

    /**
     * AJAX
     * guardar datos del pedido reubicado
     * @param  int $id
     * @return string
     */
    public function pedidos_reubicar_guardar() {
        if($this->input->is_ajax_request()){
            
            $this->load->model('pedido_reubicado','pre');
            $this->load->model('orden_salida','os');
            $this->load->model('producto','pr');

            if( ($datos = $this->input->post()) || ($datos = $this->input->get()) ){
                $respuesta = 'OK';
                $pedido_reubicado = array(
                    'id_pedido' => $datos['id_pedido'],
                    'id_usuario' => $this->session->userdata('userid'),
                    'id_cliente_sucursal' => $datos['id_cliente_sucursal'],
                    'id_contacto' => $datos['id_contacto'],
                    'id_ruta' => $datos['id_ruta'],
                    'id_llamada' => $datos['id_llamada'],
                    'observaciones' => $datos['observaciones'],
                    'orden_compra' => $datos['orden_compra']
                );
                
                $this->db->trans_start();

                // save pedido reubicado
                $id_pedido_reubicado = $this->pre->save_pedido_reubicado($pedido_reubicado);
                $mensaje = 'Se generó el pedido reubicado no. '.$id_pedido_reubicado;

                if ($id_pedido_reubicado) {
                    $pedido_reubicado = $this->pre->get_by_id($id_pedido_reubicado);
                    $tasa_iva = $this->configuracion->get_valor('iva');
                    //$salida = $this->os->get_by_id($pedido->id_orden_salida)->row();
                    
                    /**
                     * DUDA *****
                     */
                    // $this->os->delete_presentaciones($pedido_reubicado->id_orden_salida);
                    
                    foreach($datos['productos'] as $producto){
                        $data = $this->pr->get_by_id($producto[4])->row();
                        if($data->iva == 1)
                            $iva = $tasa_iva;
                        else
                            $iva = 0;
                        // Presentaciones del pedido
                        $presentacion = array(
                            'id_pedido_reubicado' => $id_pedido_reubicado,
                            'id_producto_presentacion' => $producto[0],
                            'cantidad' => $producto[1],
                            'precio' => $producto[2],
                            'precio_nuevo' => $producto[5],
                            'observaciones' => $producto[3],
                            'iva' => $iva
                        );
                        if( !($this->pre->save_presentacion($presentacion)) ){
                            $respuesta = 'Error';
                            $this->session->set_flashdata('mensaje',array('texto' => 'Error al guardar el pedido reubicado', 'tipo' => 'alert-error'));
                        }

                        /**
                         * DUDA *****
                         */
                        /*
                        if($pedido_reubicado->estado > 1 && $pedido_reubicado->estado < 4){  // Si el estado del pedido es mayor a 1 quiere decir que es una edición de pedido
                            // Presentaciones de la orden de salida
                            $presentacion_os = array(
                                'id_orden_salida' => $pedido_reubicado->id_orden_salida,
                                'id_producto_presentacion' => $producto[0],
                                'cantidad' => $producto[1],
                                'observaciones' => $producto[3]
                            );
                            if( !($this->os->save_presentacion($presentacion_os)) ){
                                $respuesta = 'Error';
                                $this->session->set_flashdata('mensaje',array('texto' => 'Error al guardar la orden de salida', 'tipo' => 'alert-error'));
                            }
                        }
                        */
                       
                        $id_ruta = $pedido_reubicado->id_ruta;
                    }
                }else{
                    $respuesta = 'Error';
                    $this->session->set_flashdata('mensaje',array('texto' => 'Error al guardar el pedido reubicado', 'tipo' => 'alert-error'));
                }
                $this->db->trans_complete();
                $this->session->set_flashdata('mensaje', array('texto' => $mensaje, 'tipo' => 'alert-success'));
                
                $response = array(
                    'respuesta' => $respuesta,
                    'id_ruta' => $id_ruta
                );
                echo json_encode($response);
            }
        }
    }

    /**
     * VIEW
     * mostrar en pantalla el pedido reubicado
     * @param  int $id
     * @return view
     */
    public function pedido_reubicado($id) {
        $this->load->model('pedido','p');
        $this->load->model('pedido_reubicado', 'pr');
        $this->load->model('cliente','c');
        $this->load->model('ruta','r');
        $this->load->model('sucursal','s');
        $this->load->model('contacto','co');
        $this->load->model('producto','pro');
        $this->load->model('cliente_presentacion', 'cp');

        $data['titulo'] = 'Pedido <small>Reubicado</small>';
        $data['link_back'] = anchor('javascript:history.go(-1);','<i class="icon-arrow-left"></i> Regresar',array('class'=>'btn'));
        $data['link_imprimir'] = anchor_popup($this->folder.$this->clase.'pedidos_documento/' . $id, '<i class="icon-print"></i> Imprimir', array('class' => 'btn', 'title' => 'Imprimir'));
        $data['action'] = site_url($this->folder.$this->clase.'pedidos_reubicar/'.$id);

        // obtener pedido reubicado
        $pedido = $this->pr->get_by_id($id);

        // si no se encuentra redireccionar a pedidos enviados
        if (empty($id) OR empty($pedido)) {
            redirect('ventas/pedidos/pedidos_enviados');
        }
        
        $data['pedido'] = $pedido;
        $sucursal = !empty($id_sucursal) ? $id_sucursal : $pedido->id_cliente_sucursal;
        $contacto = !empty($id_contacto) ? $id_contacto : $pedido->id_contacto;
        $data['sucursal'] = $this->s->get_by_id($sucursal)->row();
        $data['cliente'] = $this->c->get_by_id($data['sucursal']->id_cliente)->row();
        if(!(!empty($id_sucursal) && empty($id_contacto)))
            $data['contacto'] = $this->co->get_by_id($contacto)->row();
        $data['ruta'] = $this->r->get_by_id($pedido->id_ruta)->row();
        $data['rutas'] = $this->r->get_all()->result();
        
        $presentaciones = $this->pr->getPresentaciones($id);
        foreach($presentaciones as $p){
            $ppc = $this->cp->get_presentacion($data['cliente']->id, $p->id_producto_presentacion)->row();
            if($ppc){
                $producto = $this->pro->get_by_id($ppc->id_producto)->row();
                $data['presentaciones'][] = (object)array(
                    'id_producto_presentacion' => $p->id_producto_presentacion,
                    'cantidad' => $p->cantidad,
                    'precio' => $p->precio,
                    'producto' => $ppc->producto,
                    'id_producto' => $producto->id,
                    'presentacion' => $ppc->presentacion,
                    'codigo' => $ppc->codigo,
                    'observaciones' => $p->observaciones);
            }
        }
        $this->load->view('ventas/pedidos/pedido_reubicado', $data);
    }

    // Genera el formato de pedido para impresión
    private function pedidos_render_template($id){
        $this->load->model('pedido', 'p');
        $this->load->model('cliente','c');
        $this->load->model('sucursal','s');
        $this->load->model('contacto','co');
        $this->load->model('cliente_presentacion','cp');
        $this->load->model('producto_presentacion','pp');
        $this->load->model('preferencias/usuario','u');
            
        $pedido = $this->p->get_by_id($id)->row();
        $sucursal = $this->s->get_by_id($pedido->id_cliente_sucursal)->row();
        $cliente = $this->c->get_by_id($sucursal->id_cliente)->row();
        $contacto = $this->co->get_by_id($pedido->id_contacto)->row();
        $usuario = $this->u->get_by_id($pedido->id_usuario)->row();
        
        $this->load->library('tbs');
        $this->load->library('numero_letras');

        // Nombres de meses en español (config/sitio.php)
        $meses = $this->config->item('meses');

        // Se carga el template predefinido para los recibos (tabla Configuracion)
        $this->tbs->LoadTemplate($this->configuracion->get_valor('template_path').$this->configuracion->get_valor('template_pedidos'));

        //Logotipo
        $logo = $this->configuracion->get_valor('img_path').$this->configuracion->get_valor('logotipo');
        $this->tbs->VarRef['logo'] = base_url($logo);
        
        // Se sustituyen los campos en el template
        $this->tbs->VarRef['numero'] = $pedido->id;
        $fecha = date_create($pedido->fecha);
        $fecha_impresion = date_create();
        $this->tbs->VarRef['fecha'] = date_format($fecha,'d/m/Y');
        $this->tbs->VarRef['fecha_impresion'] = date_format($fecha_impresion,'d/m/Y');
        $this->tbs->VarRef['observaciones'] = $pedido->observaciones;
        /*$this->tbs->VarRef['dia'] = date_format($fecha,'d');
        $this->tbs->VarRef['mes'] = $meses[date_format($fecha,'n')-1];
        $this->tbs->VarRef['ano'] = date_format($fecha,'Y');
         */
        $this->tbs->VarRef['usuario'] = $usuario->nombre;
        
        $this->tbs->VarRef['cliente'] = $cliente->nombre;
        $this->tbs->VarRef['rfc'] = $cliente->rfc;
        $this->tbs->VarRef['domicilio'] = $cliente->calle.' '.$cliente->numero_exterior.' '.$cliente->numero_interior;
        $this->tbs->VarRef['colonia'] = $cliente->colonia;
        $this->tbs->VarRef['poblacion'] = $cliente->poblacion;
        $this->tbs->VarRef['municipio'] = $cliente->municipio;
        $this->tbs->VarRef['estado'] = $cliente->estado;
        $this->tbs->VarRef['cp'] = $cliente->cp;
        $this->tbs->VarRef['sucursal'] = $sucursal->numero.' '.$sucursal->nombre;
        $this->tbs->VarRef['domicilio_sucursal'] = $sucursal->calle.' '.$sucursal->numero_exterior.' '.$sucursal->numero_interior;
        $this->tbs->VarRef['colonia_sucursal'] = $sucursal->colonia;
        $this->tbs->VarRef['poblacion_sucursal'] = $sucursal->poblacion;
        $this->tbs->VarRef['municipio_sucursal'] = $sucursal->municipio;
        $this->tbs->VarRef['estado_sucursal'] = $sucursal->estado;
        $this->tbs->VarRef['cp_sucursal'] = $sucursal->cp;
        $this->tbs->VarRef['contacto'] = $contacto->nombre . ' ('. $contacto->puesto . ')';
        
        $this->tbs->VarRef['num_proveedor'] = $cliente->num_proveedor;
        $this->tbs->VarRef['orden_compra'] = $pedido->orden_compra;

        $presentaciones = $this->p->get_presentaciones($pedido->id)->result_array();
        foreach($presentaciones as $key => $value){
            $presentacion_cliente = $this->cp->get_presentacion($cliente->id, $presentaciones[$key]['id_producto_presentacion'])->row();
            $presentacion = $this->pp->get_by_id($presentaciones[$key]['id_producto_presentacion'])->row();
            $presentaciones[$key]['importe'] = number_format($presentaciones[$key]['cantidad'] * $presentaciones[$key]['precio'],2,'.',',');
            $presentaciones[$key]['cantidad'] = number_format($presentaciones[$key]['cantidad'],2,'.',',');
            $presentaciones[$key]['precio'] = number_format($presentaciones[$key]['precio'],2,'.',',');
            $presentaciones[$key]['codigo'] = $presentacion_cliente->codigo ? $presentacion_cliente->codigo : $presentacion->codigo;
            $presentaciones[$key]['sku'] = $presentacion_cliente->sku ? $presentacion_cliente->sku : $presentacion->sku;
            $presentaciones[$key]['nombre'] = $presentacion_cliente->producto;
            $presentaciones[$key]['presentacion'] = $presentacion_cliente->presentacion;
        }
        $this->tbs->MergeBlock('presentaciones', $presentaciones);
        
        $this->tbs->VarRef['subtotal'] = number_format($this->p->get_subtotal($pedido->id),2,'.',',');
        $this->tbs->VarRef['iva'] = number_format($this->p->get_iva($pedido->id),2,'.',',');
        $total = $this->p->get_importe($pedido->id);
        $this->tbs->VarRef['total'] = number_format($total,2,'.',',');
        $this->tbs->VarRef['cantidad_letra'] = $this->numero_letras->convertir($total);
        $this->tbs->VarRef['peso'] = number_format($this->p->get_peso($pedido->id),2,'.',',').'kg';
        $this->tbs->VarRef['piezas'] = number_format($this->p->get_piezas($pedido->id),2,'.',',');
        // Render sin desplegar en navegador
        $this->tbs->Show(TBS_NOTHING);
        // Se regresa el render
        return $this->tbs->Source;
    }
    
    // Impresión de pedidos
    public function pedidos_documento( $id = null ){
        if(!empty($id)){
            $this->layout = "template_pdf";
            $this->load->model('pedido', 'p');
            $pedido = $this->p->get_by_id($id)->row();
            if( $this->session->flashdata('pdf') ){
            //if(true){
                if($pedido){
                    $data['contenido'] = $this->pedidos_render_template($id);                    
                    $this->load->view('documento', $data);
                }else{
                    redirect($this->folder.$this->clase.'pedidos_proceso_ruta');
                }
            }else{
                $this->session->set_flashdata('pdf', true);
                if($pedido){
                    if($pedido->estado == '0'){  // Se agrega una marca de agua al PDF
                        $this->session->set_flashdata('watermark', 'Cancelado');
                    }
                }
                redirect($this->folder.$this->clase.'pedidos_documento/'.$id); // Se recarga el método para imprimirlo como PDF
            }
        }else{
            redirect($this->folder.$this->clase.'pedidos_proceso_ruta');
        }
    }
    
    public function pedidos_excel( $id ){
        
        $this->load->model('pedido', 'p');
        $this->load->model('cliente','c');
        $this->load->model('sucursal','s');
        $this->load->model('contacto','co');
        $this->load->model('cliente_presentacion','cp');
        $this->load->model('producto_presentacion','pp');
        $this->load->model('preferencias/usuario','u');
            
        $pedido = $this->p->get_by_id($id)->row();
        $sucursal = $this->s->get_by_id($pedido->id_cliente_sucursal)->row();
        $cliente = $this->c->get_by_id($sucursal->id_cliente)->row();
        $contacto = $this->co->get_by_id($pedido->id_contacto)->row();
        $usuario = $this->u->get_by_id($pedido->id_usuario)->row();
        
        $this->load->library('excel');
        
        $this->excel->getDefaultStyle()->getFont()->setName('Arial');
        $this->excel->getDefaultStyle()->getFont()->setSize(10);

        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('PEDIDO');
        
        $fila = 1;
        //Logotipo
        $logo = $this->configuracion->get_valor('img_path').$this->configuracion->get_valor('logotipo');
        
//        $objDrawing = new PHPExcel_Worksheet_Drawing();
//        $objDrawing->setName('Logo');
//        $objDrawing->setDescription('Logo');
//        $objDrawing->setPath(base_url($logo));
//        $objDrawing->setHeight(36);
//        $objDrawing->setCoordinates('B15');
//        $objDrawing->setOffsetX(110);
//        $objDrawing->setRotation(25);
//        $objDrawing->getShadow()->setVisible(true);
//        $objDrawing->getShadow()->setDirection(45);
//
//        $objDrawing->setWorksheet($this->excel->getActiveSheet());
//        
//        
        $this->excel->getActiveSheet()->setCellValue('A'.$fila, 'PEDIDO');
        $fila++;
        
        $this->excel->getActiveSheet()->setCellValue('A'.$fila, 'Cliente');
        $this->excel->getActiveSheet()->mergeCells('B'.$fila.':D'.$fila);
        $this->excel->getActiveSheet()->setCellValue('B'.$fila, $cliente->nombre);
        $this->excel->getActiveSheet()->setCellValue('E'.$fila, 'Núm:');
        $this->excel->getActiveSheet()->mergeCells('F'.$fila.':G'.$fila);
        $this->excel->getActiveSheet()->setCellValue('F'.$fila, $pedido->id);
        $fila++;
        
        $this->excel->getActiveSheet()->setCellValue('A'.$fila, 'RFC');
        $this->excel->getActiveSheet()->mergeCells('B'.$fila.':D'.$fila);
        $this->excel->getActiveSheet()->setCellValue('B'.$fila, $cliente->rfc);
        $fecha = date_create($pedido->fecha);
        $this->excel->getActiveSheet()->setCellValue('E'.$fila, 'Fecha:');
        $this->excel->getActiveSheet()->mergeCells('F'.$fila.':G'.$fila);
        $this->excel->getActiveSheet()->setCellValue('F'.$fila, date_format($fecha,'d/m/Y'));
        $fila++;
        
        $this->excel->getActiveSheet()->setCellValue('A'.$fila, 'Domicilio');
        $this->excel->getActiveSheet()->mergeCells('B'.$fila.':D'.$fila);
        $this->excel->getActiveSheet()->setCellValue('B'.$fila, $cliente->calle.' '.$cliente->numero_exterior.' '.$cliente->numero_interior);
        $this->excel->getActiveSheet()->setCellValue('E'.$fila, 'Promotor:');
        $this->excel->getActiveSheet()->mergeCells('F'.$fila.':G'.$fila);
        $this->excel->getActiveSheet()->setCellValue('F'.$fila, $usuario->nombre);
        $fila++;
        $this->excel->getActiveSheet()->mergeCells('B'.$fila.':D'.$fila);
        $this->excel->getActiveSheet()->setCellValue('B'.$fila, $cliente->colonia);
        $this->excel->getActiveSheet()->setCellValue('E'.$fila, 'No. proveedor:');
        $this->excel->getActiveSheet()->mergeCells('F'.$fila.':G'.$fila);
        $this->excel->getActiveSheet()->setCellValue('F'.$fila, $cliente->num_proveedor);
        $fila++;
        $this->excel->getActiveSheet()->mergeCells('B'.$fila.':D'.$fila);
        $this->excel->getActiveSheet()->setCellValue('B'.$fila, $cliente->poblacion.', '.$cliente->municipio.', '.$cliente->estado.', '.$cliente->cp);
        $this->excel->getActiveSheet()->setCellValue('E'.$fila, 'Orden compra:');
        $this->excel->getActiveSheet()->mergeCells('F'.$fila.':G'.$fila);
        $this->excel->getActiveSheet()->setCellValue('F'.$fila, $pedido->orden_compra);
        $fila++;
        
        $this->excel->getActiveSheet()->setCellValue('A'.$fila, 'Tienda');
        $this->excel->getActiveSheet()->mergeCells('B'.$fila.':D'.$fila);
        $this->excel->getActiveSheet()->setCellValue('B'.$fila, $sucursal->numero.' '.$sucursal->nombre);
        $fila++;
        $this->excel->getActiveSheet()->mergeCells('B'.$fila.':D'.$fila);
        $this->excel->getActiveSheet()->setCellValue('B'.$fila, $sucursal->calle.' '.$sucursal->numero_exterior.' '.$sucursal->numero_interior.' '.$sucursal->colonia);
        $fila++;
        $this->excel->getActiveSheet()->mergeCells('B'.$fila.':D'.$fila);
        $this->excel->getActiveSheet()->setCellValue('B'.$fila, $sucursal->poblacion.', '.$sucursal->municipio.', '.$sucursal->estado.', '.$sucursal->cp);
        $fila++;
        
        $this->excel->getActiveSheet()->setCellValue('A'.$fila, 'Contacto');
        $this->excel->getActiveSheet()->mergeCells('B'.$fila.':D'.$fila);
        $this->excel->getActiveSheet()->setCellValue('B'.$fila, $contacto->nombre);
        $fila+=2;
        
        $this->excel->getActiveSheet()->setCellValue('A'.$fila, 'Codigo');
        $this->excel->getActiveSheet()->setCellValue('B'.$fila, 'SKU');
        $this->excel->getActiveSheet()->setCellValue('C'.$fila, 'Cantidad');
        $this->excel->getActiveSheet()->setCellValue('D'.$fila, 'Descripción');
        $this->excel->getActiveSheet()->setCellValue('E'.$fila, 'Presentación');
        $this->excel->getActiveSheet()->setCellValue('F'.$fila, 'Precio');
        $this->excel->getActiveSheet()->setCellValue('G'.$fila, 'Importe');

        $fila++;
        $presentaciones = $this->p->get_presentaciones($pedido->id)->result_array();
        foreach($presentaciones as $key => $value){
            $presentacion_cliente = $this->cp->get_presentacion($cliente->id, $presentaciones[$key]['id_producto_presentacion'])->row();
            $presentacion = $this->pp->get_by_id($presentaciones[$key]['id_producto_presentacion'])->row();
            $presentaciones[$key]['importe'] = number_format($presentaciones[$key]['cantidad'] * $presentaciones[$key]['precio'],2,'.','');
            $presentaciones[$key]['cantidad'] = number_format($presentaciones[$key]['cantidad'],2,'.','');
            $presentaciones[$key]['precio'] = number_format($presentaciones[$key]['precio'],2,'.','');
            $presentaciones[$key]['codigo'] = $presentacion_cliente->codigo ? $presentacion_cliente->codigo : $presentacion->codigo;
            $presentaciones[$key]['sku'] = $presentacion_cliente->sku ? $presentacion_cliente->sku : $presentacion->sku;
            $presentaciones[$key]['nombre'] = $presentacion_cliente->producto;
            $presentaciones[$key]['presentacion'] = $presentacion_cliente->presentacion;
            
            $this->excel->getActiveSheet()->setCellValue('A'.$fila, $presentaciones[$key]['codigo']);
            $this->excel->getActiveSheet()->setCellValue('B'.$fila, $presentaciones[$key]['sku']);
            $this->excel->getActiveSheet()->setCellValue('C'.$fila, $presentaciones[$key]['cantidad']);
            $this->excel->getActiveSheet()->setCellValue('D'.$fila, $presentaciones[$key]['nombre']);
            $this->excel->getActiveSheet()->setCellValue('E'.$fila, $presentaciones[$key]['presentacion']);
            $this->excel->getActiveSheet()->setCellValue('F'.$fila, $presentaciones[$key]['precio']);
            $this->excel->getActiveSheet()->setCellValue('G'.$fila, $presentaciones[$key]['importe']);

            $this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
            $this->excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);

            $fila++;
        }
        
        $this->excel->getActiveSheet()->setCellValue('F'.$fila, 'Subtotal');
        $this->excel->getActiveSheet()->setCellValue('G'.$fila, $this->p->get_subtotal($pedido->id));
        $fila++;
        $this->excel->getActiveSheet()->setCellValue('F'.$fila, 'IVA');
        $this->excel->getActiveSheet()->setCellValue('G'.$fila, $this->p->get_iva($pedido->id));
        $fila++;
        $this->excel->getActiveSheet()->setCellValue('F'.$fila, 'Total');
        $this->excel->getActiveSheet()->setCellValue('G'.$fila, $this->p->get_importe($pedido->id));
        $fila+=2;
        
        $this->excel->getActiveSheet()->setCellValue('A'.$fila, 'Piezas:');
        $this->excel->getActiveSheet()->setCellValue('B'.$fila, $this->p->get_piezas($pedido->id));
        $fila++;
        
        $this->excel->getActiveSheet()->setCellValue('A'.$fila, 'Peso:');
        $this->excel->getActiveSheet()->setCellValue('B'.$fila, $this->p->get_peso($pedido->id));
        $fila++;
        
        $this->excel->getActiveSheet()->setCellValue('A'.$fila, 'Observaciones:');
        $this->excel->getActiveSheet()->mergeCells('B'.$fila.':G'.$fila);
        $this->excel->getActiveSheet()->setCellValue('G'.$fila, $pedido->observaciones);
        $fila++;
        
        //set cell A1 content with some text
//                $this->excel->getActiveSheet()->setCellValue('A1', 'This is just some text value');
//                //change the font size
//                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
//                //make the font become bold
//                $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
//                //merge cell A1 until D1
//                $this->excel->getActiveSheet()->mergeCells('A1:D1');
//                //set aligment to center for that merged cell (A1 to D1)
//                $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $filename='pedido_'.$pedido->id.'.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }
    
    // Genera el formato de pedido para impresión
    private function concentrado_render_template( $id_ruta ){
        $this->load->model('pedido', 'p');
        $this->load->model('cliente','c');
        $this->load->model('sucursal','s');
        $this->load->model('contacto','co');
        $this->load->model('ruta','r');
        $this->load->model('cliente_presentacion','cp');
        $this->load->model('producto_presentacion','pp');
        $this->load->model('preferencias/usuario','u');
        
        $this->load->library('tbs');
        $this->load->library('numero_letras');

        // Nombres de meses en español (config/sitio.php)
        $meses = $this->config->item('meses');

        // Se carga el template predefinido para los recibos (tabla Configuracion)
        $this->tbs->LoadTemplate($this->configuracion->get_valor('template_path').$this->configuracion->get_valor('template_concentrado_pedidos_cliente'));

        //$fecha = date('Y/m/d');
        // Se sustituyen los campos en el template
        $this->tbs->VarRef['dia'] = date('d');
        $this->tbs->VarRef['mes'] = $meses[date('n')-1];
        $this->tbs->VarRef['ano'] = date('Y');
        
        $ruta = $this->r->get_by_id($id_ruta)->row();
        $this->tbs->VarRef['ruta'] = $ruta->nombre;

        $i = $total_cantidad = $total = $total_cantidad_cliente = $total_cliente = 0;
        
        $clientes = $this->c->get_all()->result_array();
        foreach($clientes as $key => $value){
            $resultado = $this->p->get_presentaciones_by_cliente($clientes[$key]['id'], $id_ruta, array('1'));
            if($resultado->num_rows() > 0){
                $presentaciones = $resultado->result_array();
                $listado[$i] = array('nombre' => $clientes[$key]['nombre'], 'total_cantidad' => 0, 'total' => 0);
                foreach($presentaciones as $key2 => $value){
                    $presentacion_cliente = $this->cp->get_presentacion($clientes[$key]['id'], $presentaciones[$key2]['id_producto_presentacion'])->row();
                    $presentacion = $this->pp->get_by_id($presentaciones[$key2]['id_producto_presentacion'])->row();
                    $listado[$i]['presentaciones'][] = array(
                        'codigo' => $presentacion_cliente->codigo ? $presentacion_cliente->codigo : $presentacion->codigo,
                        'nombre' => $presentacion_cliente->producto,
                        'cantidad' => number_format($presentaciones[$key2]['cantidad'],2,'.',','),
                        'presentacion' => $presentacion_cliente->presentacion,
                        'precio' => number_format($presentaciones[$key2]['precio'],2,'.',','),
                        'importe' => number_format($presentaciones[$key2]['cantidad'] * $presentaciones[$key2]['precio'],2,'.',',')
                        );
                    $total_cantidad_cliente += $presentaciones[$key2]['cantidad'];
                    $total_cliente += $presentaciones[$key2]['cantidad'] * $presentaciones[$key2]['precio'];
                }
                $listado[$i]['total_cantidad_cliente'] = number_format($total_cantidad_cliente, 2, '.', ',');
                $listado[$i]['total_cliente'] = number_format($total_cliente, 2, '.', ',');
                $i++;
                $total += $total_cliente;
                $total_cantidad += $total_cantidad_cliente;
                $total_cantidad_cliente = $total_cliente = 0;
            }
        }
        $this->tbs->VarRef['total'] = number_format($total,2);
        $this->tbs->VarRef['total_cantidad'] = number_format($total_cantidad,2);
//        var_dump($listado);
//        die();
        $this->tbs->MergeBlock('clientes', $listado);
        
        
//        $this->tbs->VarRef['subtotal'] = number_format($this->p->get_subtotal($pedido->id),2,'.',',');
//        $this->tbs->VarRef['iva'] = number_format($this->p->get_iva($pedido->id),2,'.',',');
//        $total = $this->p->get_importe($pedido->id);
//        $this->tbs->VarRef['total'] = number_format($total,2,'.',',');
//        $this->tbs->VarRef['cantidad_letra'] = $this->numero_letras->convertir($total);
//        $this->tbs->VarRef['peso'] = number_format($this->p->get_peso($pedido->id),2,'.',',').'kg';
//        $this->tbs->VarRef['piezas'] = number_format($this->p->get_piezas($pedido->id),2,'.',',');
        // Render sin desplegar en navegador
        $this->tbs->Show(TBS_NOTHING);
        // Se regresa el render
        return $this->tbs->Source;
    }
    
    // Impresión de concentrado de productos por ruta
    public function concentrado_documento( $id_ruta ){
        $this->layout = "template_pdf";
        if( $this->session->flashdata('pdf') ){
            $data['contenido'] = $this->concentrado_render_template( $id_ruta );                    
            $this->load->view('documento', $data);
        }else{
            $this->session->set_flashdata('pdf', true);
            redirect($this->folder.$this->clase.'concentrado_documento/'.$id_ruta); // Se recarga el método para imprimirlo como PDF
        }
    }
}
?>
