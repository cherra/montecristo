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
                        array('data' => anchor($this->folder.$this->clase.'pedidos_editar/' . $d->id, '<i class="icon-edit"></i>', array('class' => 'btn btn-small', 'title' => 'Editar')), 'style' => 'text-align: right;')
    		);
    	}
    	$data['table'] = $this->table->generate();
    	
    	$this->load->view('ventas/lista', $data);
    }
    
    public function pedidos_agregar() {
    	$this->load->model('pedido', 'p');
        
    	$data['titulo'] = 'Pedido <small>Registro nuevo</small>';
    	$data['link_back'] = anchor($this->folder.$this->clase.'index','<i class="icon-arrow-left"></i> Regresar',array('class'=>'btn'));
    
    	$data['action'] = site_url($this->folder.$this->clase.'pedidos_agregar');
    	if ( ($datos = $this->input->post()) ) {
    		$this->p->save($datos);
                $data['mensaje'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>¡Registro exitoso!</div>';
    	} 
        $this->load->view('ventas/pedidos/pedidos_formulario', $data);
    }
}
?>
