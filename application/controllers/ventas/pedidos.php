<?php

/**
 * Description of pedidos
 *
 * @author cherra
 */
class Pedidos extends CI_Controller {
    
    private $folder = 'ventas/';
    private $clase = 'pedidos/';
    
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
    	$this->table->set_heading('Número','Fecha','Cliente','Sucursal','Vendedor', '');
    	foreach ($datos as $d) {
            $sucursal = $this->s->get_by_id($d->id_cliente_sucursal)->row();
            $cliente = $this->c->get_by_id($sucursal->id_cliente)->row();
            $usuario = $this->u->get_by_id($d->id_usuario)->row();
    		$this->table->add_row(
                        $d->id,
                        $d->fecha,
                        $cliente->nombre,
                        $sucursal->nombre,
                        $usuario->nombre,
                        array('data' => ($d->estado == 1 ? anchor($this->folder.$this->clase.'pedidos_editar/' . $d->id, '<i class="icon-edit"></i>', array('class' => 'btn btn-small', 'title' => 'Editar')) :  '<a class="btn btn-small" disabled><i class="icon-edit"></i></a>'), 'style' => 'text-align: right;'),
                        array('data' => ($d->estado == 1 ? anchor($this->folder.$this->clase.'pedidos_cancelar/' . $d->id, '<i class="icon-ban-circle"></i>', array('class' => 'btn btn-small cancelar', 'title' => 'Cancelar')) :  '<a class="btn btn-small" disabled><i class="icon-ban-circle"></i></a>'), 'style' => 'text-align: right;')
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
        
        $pedido = $this->p->get_by_id($id);
        if (empty($id) OR $pedido->num_rows() <= 0) {
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
        
        $data['pedido'] = $pedido->row();
        $data['sucursal'] = $this->s->get_by_id($pedido->row()->id_cliente_sucursal)->row();
        $data['cliente'] = $this->c->get_by_id($data['sucursal']->id_cliente)->row();
        $data['contacto'] = $this->co->get_by_id($pedido->row()->id_contacto)->row();
        $data['ruta'] = $this->r->get_by_id($pedido->row()->id_ruta)->row();
        
        $data['rutas'] = $this->r->get_all()->result();
        
        $presentaciones = $this->p->get_presentaciones($id)->result();
        foreach($presentaciones as $p){
            $pp = $this->pp->get_by_id($p->id_producto_presentacion)->row();
            $producto = $this->pro->get_by_id($pp->id_producto)->row();
            $presentacion = $this->pre->get_by_id($pp->id_presentacion)->row();
            $data['presentaciones'][] = (object)array(
                'id_producto_presentacion' => $p->id_producto_presentacion,
                'cantidad' => $p->cantidad,
                'precio' => $p->precio,
                'producto' => $producto->nombre,
                'id_producto' => $producto->id,
                'presentacion' => $presentacion->nombre,
                'observaciones' => $p->observaciones);
        }

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
}
?>
