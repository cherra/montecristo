<?php

/**
 * Controlador para productos
 *
 * @author cherra
 */
class Productos extends CI_Controller{
    
    private $folder = 'ventas/';
    private $clase = 'productos/';
    
    function __construct() {
        parent::__construct();
    }
    
    /*
     * Categorías de productos
     */
    public function categorias( $offset = 0 ){
        $this->load->model('categoria','a');
        $this->config->load("pagination");
    	
        $data['titulo'] = 'Categorías <small>Lista</small>';
    	$data['link_add'] = anchor($this->folder.$this->clase.'categorias_agregar','<i class="icon-plus icon-white"></i> Agregar', array('class' => 'btn btn-inverse'));
    	$data['action'] = $this->folder.$this->clase.'categorias';
        
        // Filtro de busqueda (se almacenan en la sesión a través de un hook)
        $filtro = $this->session->userdata('filtro');
        if($filtro)
            $data['filtro'] = $filtro;
        
        $page_limit = $this->config->item("per_page");
    	$datos = $this->a->get_paged_list($page_limit, $offset, $filtro)->result();
    	
        // generar paginacion
    	$this->load->library('pagination');
    	$config['base_url'] = site_url($this->folder.$this->clase.'categorias');
    	$config['total_rows'] = $this->a->count_all_filtro($filtro);
    	$config['per_page'] = $page_limit;
    	$config['uri_segment'] = 4;
    	$this->pagination->initialize($config);
    	$data['pagination'] = $this->pagination->create_links();
    	
    	// generar tabla
    	$this->load->library('table');
    	$this->table->set_empty('&nbsp;');
    	$tmpl = array ( 'table_open' => '<table class="' . $this->config->item('tabla_css') . '" >' );
    	$this->table->set_template($tmpl);
    	$this->table->set_heading('Nombre', 'Código', '');
    	foreach ($datos as $d) {
    		$this->table->add_row(
                    $d->nombre,
                    $d->codigo,
                    array('data' => anchor($this->folder.$this->clase.'categorias_editar/' . $d->id, '<i class="icon-edit"></i>', array('class' => 'btn btn-small')), 'style' => 'text-align: right;')
    		);
    	}
    	$data['table'] = $this->table->generate();
    	
    	$this->load->view('ventas/lista', $data);
    }
    
    public function categorias_agregar() {
    	$this->load->model('categoria', 'a');
        
    	$data['titulo'] = 'Categorías <small>Registro nuevo</small>';
    	$data['link_back'] = anchor($this->folder.$this->clase.'categorias','<i class="icon-arrow-left"></i> Regresar',array('class'=>'btn'));
    
    	$data['action'] = site_url($this->folder.$this->clase.'categorias_agregar');
    	if ( ($datos = $this->input->post()) ) {
    		$this->a->save($datos);
    		$data['mensaje'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>¡Registro exitoso!</div>';
    	} 
        $this->load->view('ventas/productos/categorias_formulario', $data);
    }
    
    public function categorias_editar($id = NULL) {
    	$this->load->model('categoria', 'a');
        $categoria = $this->a->get_by_id( $id );
        if ( empty($id) OR $categoria->num_rows() <= 0 ) {
    		redirect(site_url($this->folder.$this->clase.'categorias'));
    	}
    	
    	$data['titulo'] = 'Categorías <small>Editar registro</small>';
    	$data['link_back'] = anchor($this->folder.$this->clase.'categorias','<i class="icon-arrow-left"></i> Regresar',array('class'=>'btn'));
    	$data['mensaje'] = '';
    	$data['action'] = site_url($this->folder.$this->clase.'categorias_editar') . '/' . $id;
    	 
    	if ( ($datos = $this->input->post()) ) {
    		$this->a->update($id, $datos);
    		$data['mensaje'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>¡Registro modificado!</div>';
    	}

    	$data['datos'] = $this->a->get_by_id($id)->row();
        
        $this->load->view('ventas/productos/categorias_formulario', $data);
    }
    
    /*
     * Productos
     */
    public function index( $offset = 0 ){
        $this->load->model('producto','a');
        $this->load->model('categoria','c');
        $this->config->load("pagination");
    	
        $data['titulo'] = 'Productos <small>Lista</small>';
    	$data['link_add'] = anchor($this->folder.$this->clase.'productos_agregar','<i class="icon-plus icon-white"></i> Agregar', array('class' => 'btn btn-inverse'));
    	$data['action'] = $this->folder.$this->clase.'index';
        
        // Filtro de busqueda (se almacenan en la sesión a través de un hook)
        $filtro = $this->session->userdata('filtro');
        if($filtro)
            $data['filtro'] = $filtro;
        
        $page_limit = $this->config->item("per_page");
    	$datos = $this->a->get_paged_list($page_limit, $offset, $filtro)->result();
    	
        // generar paginacion
    	$this->load->library('pagination');
    	$config['base_url'] = site_url($this->folder.$this->clase.'index');
    	$config['total_rows'] = $this->a->count_all_filtro($filtro);
    	$config['per_page'] = $page_limit;
    	$config['uri_segment'] = 4;
    	$this->pagination->initialize($config);
    	$data['pagination'] = $this->pagination->create_links();
    	
    	// generar tabla
    	$this->load->library('table');
    	$this->table->set_empty('&nbsp;');
    	$tmpl = array ( 'table_open' => '<table class="' . $this->config->item('tabla_css') . '" >' );
    	$this->table->set_template($tmpl);
    	$this->table->set_heading('Nombre', 'Código', 'Categoría');
    	foreach ($datos as $d) {
            $categoria = $this->c->get_by_id($d->id_categoria)->row();
            $this->table->add_row(
                    $d->nombre,
                    $d->codigo,
                    $categoria->nombre,
                    array('data' => anchor($this->folder.$this->clase.'productos_editar/' . $d->id, '<i class="icon-edit"></i>', array('class' => 'btn btn-small')), 'style' => 'text-align: right;')
            );
    	}
    	$data['table'] = $this->table->generate();
    	
    	$this->load->view('ventas/lista', $data);
    }
    
    public function productos_agregar() {
    	$this->load->model('producto', 'a');
        $this->load->model('categoria','c');
        
    	$data['titulo'] = 'Productos <small>Registro nuevo</small>';
    	$data['link_back'] = anchor($this->folder.$this->clase.'index','<i class="icon-arrow-left"></i> Regresar',array('class'=>'btn'));
    
    	$data['action'] = site_url($this->folder.$this->clase.'productos_agregar');
    	if ( ($datos = $this->input->post()) ) {
    		$this->a->save($datos);
    		$data['mensaje'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>¡Registro exitoso!</div>';
    	}
        $data['categorias'] = $this->c->get_all()->result();
        $this->load->view('ventas/productos/productos_formulario', $data);
    }
    
    public function productos_editar( $id = NULL ) {
    	$this->load->model('producto', 'a');
        $producto = $this->a->get_by_id($id);
        if ( empty($id) OR $producto->num_rows() <= 0) {
    		redirect(site_url($this->folder.$this->clase.'index'));
    	}
    	
        $this->load->model('categoria','c');
        
    	$data['titulo'] = 'Productos <small>Editar registro</small>';
    	$data['link_back'] = anchor($this->folder.$this->clase.'index','<i class="icon-arrow-left"></i> Regresar',array('class'=>'btn'));
    	$data['mensaje'] = '';
    	$data['action'] = site_url($this->folder.$this->clase.'productos_editar') . '/' . $id;
    	 
    	if ( ($datos = $this->input->post()) ) {
    		$this->a->update($id, $datos);
    		$data['mensaje'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>¡Registro modificado!</div>';
    	}

    	$data['datos'] = $this->a->get_by_id($id)->row();
        $data['categorias'] = $this->c->get_all()->result();
        
        $this->load->view('ventas/productos/productos_formulario', $data);
    }
    
}

?>
