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
    	$this->table->set_heading('E','Número','Fecha','Cliente','Sucursal','Municipio','Estado','Vendedor', 'Total', '');
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
                        array('data' => ($d->estado > 0 && $d->estado < 5 ? anchor($this->folder.$this->clase.'pedidos_editar/' . $d->id, '<i class="icon-edit"></i>', array('class' => 'btn btn-small', 'title' => 'Editar')) :  '<a class="btn btn-small" disabled><i class="icon-edit"></i></a>'), 'style' => 'text-align: right;'),
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
    
    public function pedidos_agregar() {
    	$this->load->model('pedido', 'p');
        $this->load->model('ruta','r');
        
    	$data['titulo'] = 'Pedido <small>Registro nuevo</small>';
    	$data['link_back'] = anchor($this->folder.$this->clase.'index','<i class="icon-arrow-left"></i> Regresar',array('class'=>'btn'));
    
    	//$data['action'] = site_url($this->folder.$this->clase.'pedidos_agregar');
        
        $data['rutas'] = $this->r->get_all()->result();
        $this->load->view('ventas/pedidos/pedidos_formulario', $data);
    }
    
    // Método para guardar un pedido (Ajax)
    public function pedidos_guardar( $id = NULL ){
        if($this->input->is_ajax_request()){
            $this->load->model('pedido','p');
            if( ($datos = $this->input->post()) ){
                $respuesta = 'OK';
                $pedido = array(
                    'id_usuario' => $this->session->userdata('userid'),
                    'id_cliente_sucursal' => $datos['id_cliente_sucursal'],
                    'id_contacto' => $datos['id_contacto'],
                    'id_ruta' => $datos['id_ruta'],
                    'observaciones' => $datos['observaciones']
                );
                $this->db->trans_start();
                
                if(empty($id)){ // Si es un pedido nuevo
                    $id_pedido = $this->p->save($pedido);
                    $mensaje = 'Se generó el pedido no. '.$id_pedido;
                }else{  // Si se está editando el pedido
                    $id_pedido = $id;
                    $this->p->update($id, $pedido);
                    $this->p->delete_presentaciones($id);  // Se borran los productos
                    $mensaje = 'Se actualizó el pedido no. '.$id_pedido;
                }
                if( $id_pedido ){
                    foreach($datos['productos'] as $producto){
                        $presentacion = array(
                            'id_pedido' => $id_pedido,
                            'id_producto_presentacion' => $producto[0],
                            'cantidad' => $producto[1],
                            'precio' => $producto[2],
                            'observaciones' => $producto[3]
                        );
                        if( !($this->p->save_presentacion($presentacion)) ){
                            $respuesto = 'Error';
                            $this->session->set_flashdata('mensaje',array('texto' => 'Error al guardar el pedido', 'tipo' => 'alert-error'));
                        }
                    }
                }else{
                    $respuesto = 'Error';
                    $this->session->set_flashdata('mensaje',array('texto' => 'Error al guardar el pedido', 'tipo' => 'alert-error'));
                }
                $this->db->trans_complete();
                $this->session->set_flashdata('mensaje', array('texto' => $mensaje, 'tipo' => 'alert-success'));
                echo $respuesta;
            }
        }
    }
    
    public function pedidos_editar( $id = NULL ){
        $this->load->model('pedido','p');
        
        $datos = $this->p->get_by_id($id);
        if (empty($id) OR $datos->num_rows() <= 0) {
            redirect($this->folder.$this->clase.'index');
        }
        
        $this->load->model('cliente','c');
        $this->load->model('ruta','r');
        $this->load->model('sucursal','s');
        $this->load->model('contacto','co');
        $this->load->model('producto_presentacion','pp');
        $this->load->model('producto','pro');
        $this->load->model('presentacion','pre');
        
        $data['titulo'] = 'Pedido <small>Editar</small>';
    	$data['link_back'] = anchor($this->folder.$this->clase.'index','<i class="icon-arrow-left"></i> Regresar',array('class'=>'btn'));
        
        $pedido = $datos->row();
        $data['pedido'] = $pedido;
        $data['sucursal'] = $this->s->get_by_id($pedido->id_cliente_sucursal)->row();
        $data['cliente'] = $this->c->get_by_id($data['sucursal']->id_cliente)->row();
        $data['contacto'] = $this->co->get_by_id($pedido->id_contacto)->row();
        $data['ruta'] = $this->r->get_by_id($pedido->id_ruta)->row();
        
        $data['rutas'] = $this->r->get_all()->result();
        
        $presentaciones = $this->p->get_presentaciones($id)->result();
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
        $data['icono_estado'] = '<i class="'.$this->iconos_estado_pedido[$pedido->estado].' icon-2x"></i>';
        /*if($data['pedido']->estado == '1')
            $data['action_estado'] = anchor($this->folder.$this->clase.'siguiente_estado/'.$id, 'Autorizar', array('class' => 'btn btn-success input-block-level', 'id' => 'autorizar'));
            */
        $this->load->view('ventas/pedidos/pedidos_formulario', $data);
    }
    
    public function pedidos_cancelar( $id = NULL ){
        if(empty($id)){
            redirect('ventas/pedidos/index');
        }
        
        $this->load->model('pedido','p');
        $respuesta = $this->p->cancelar($id);
        if($respuesta){
            $this->session->set_flashdata('mensaje',array('texto' => 'Pedido cancelado', 'tipo' => ''));
        }else{
            $this->session->set_flashdata('mensaje',array('texto' => 'Ocurrió un error al cancelar el pedido', 'tipo' => 'alert-error'));
        }
        redirect('ventas/pedidos/index');
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
    	$datos = $this->p->get_grouped_by_ruta($page_limit, $offset, $filtro)->result();
    	
        // generar paginacion
    	$this->load->library('pagination');
    	$config['base_url'] = site_url($this->folder.$this->clase.'consolidar');
    	$config['total_rows'] = $this->p->count_grouped_by_ruta($filtro);
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
        
        $this->config->load("pagination");
    	
        $data['titulo'] = 'Pedidos por consolidar <small>Lista por ruta</small>';
        //$data['link_add'] = anchor($this->folder.$this->clase.'pedidos_agregar','<i class="icon-plus icon-white"></i> Nuevo', array('class' => 'btn btn-inverse'));
    	$data['action'] = $this->folder.$this->clase.'pedidos_consolidar_ruta/'.$id_ruta;
        $data['link_back'] = anchor($this->folder.$this->clase.'pedidos_consolidar/'.$offset,'<i class="icon-arrow-left"></i> Regresar',array('class'=>'btn'));
        $data['rutas'] = $this->r->get_all()->result();
        
        if(!empty($id_ruta)){
            $data['ruta'] = $this->r->get_by_id($id_ruta)->row();
            
            if( ($pedidos = $this->input->post('pedidos')) ){
                foreach($pedidos AS $id){
                    $pedido = $this->p->get_by_id($id)->row();
                    $this->p->update($id, array('estado' => $pedido->estado + 1));
                }
            }
            // Filtro de busqueda (se almacenan en la sesión a través de un hook)
            $filtro = $this->session->userdata('filtro');
            if($filtro)
                $data['filtro'] = $filtro;

            $page_limit = $this->config->item("per_page");
            $datos = $this->p->get_by_ruta($id_ruta, '1', $page_limit, $offset, $filtro)->result();

            // generar paginacion
            $this->load->library('pagination');
            $config['base_url'] = site_url($this->folder.$this->clase.'index');
            $config['total_rows'] = $this->p->count_by_ruta($id_ruta, '1',$filtro);
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
            foreach ($datos as $d) {
                $sucursal = $this->s->get_by_id($d->id_cliente_sucursal)->row();
                $cliente = $this->c->get_by_id($sucursal->id_cliente)->row();
                $usuario = $this->u->get_by_id($d->id_usuario)->row();
                $importe = $this->p->get_importe($d->id);
                    $this->table->add_row(
                            '<input type="checkbox" name="pedidos[]" value="'.$d->id.'"/>',
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
                            array('data' => anchor($this->folder.$this->clase.'pedidos_editar/' . $d->id, '<i class="icon-edit"></i>', array('class' => 'btn btn-small', 'title' => 'Editar')), 'style' => 'text-align: right;')
                    );
                    if($d->estado == 0)
                        $this->table->add_row_class('muted');
                    else
                        $this->table->add_row_class('');
            }
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
    	$datos = $this->p->get_grouped_by_ruta($page_limit, $offset, $filtro, '2')->result();
    	
        // generar paginacion
    	$this->load->library('pagination');
    	$config['base_url'] = site_url($this->folder.$this->clase.'consolidar');
    	$config['total_rows'] = $this->p->count_grouped_by_ruta($filtro, '2');
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
                    array('data' => anchor($this->folder.$this->clase.'pedidos_proceso_ruta/' . $d->id_ruta, '<i class="icon-list"></i>', array('class' => 'btn btn-small', 'title' => 'Pedidos')), 'style' => 'text-align: right;')
            );
    	}
    	$data['table'] = $this->table->generate();
    	
    	$this->load->view('ventas/lista', $data);
    }
    
    public function pedidos_proceso_ruta( $id_ruta = NULL, $offset = 0 ){
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
            $datos = $this->p->get_by_ruta($id_ruta, '2', $page_limit, $offset, $filtro)->result();

            // generar paginacion
            $this->load->library('pagination');
            $config['base_url'] = site_url($this->folder.$this->clase.'index');
            $config['total_rows'] = $this->p->count_by_ruta($id_ruta, '2', $filtro);
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
                            array('data' => anchor($this->folder.$this->clase.'pedidos_editar/' . $d->id, '<i class="icon-edit"></i>', array('class' => 'btn btn-small', 'title' => 'Editar')), 'style' => 'text-align: right;')
                    );
                    if($d->estado == 0)
                        $this->table->add_row_class('muted');
                    else
                        $this->table->add_row_class('');
            }
            $data['table'] = $this->table->generate();
        }
    	
    	$this->load->view('ventas/pedidos/proceso_ruta_lista', $data);
    }
}
?>
