<?php

/**
 * Controlador para productos
 *
 * @author cherra
 */
class Productos extends CI_Controller{
    
    private $folder = 'almacenes/';
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
                    $d->codigo
    		);
    	}
    	$data['table'] = $this->table->generate();
    	
    	$this->load->view('lista', $data);
    }
    
    /*
     * Productos
     */
    public function index( $offset = 0 ){
        $this->load->model('producto','a');
        $this->load->model('categoria','c');
        $this->load->model('stock','st');
        $this->config->load("pagination");
    	
        $data['titulo'] = 'Productos <small>Lista</small>';
    	//$data['link_add'] = anchor($this->folder.$this->clase.'productos_agregar','<i class="icon-plus icon-white"></i> Agregar', array('class' => 'btn btn-inverse'));
    	$data['action'] = $this->folder.$this->clase.'index';
        
        // Filtro de busqueda (se almacenan en la sesión a través de un hook)
        $filtro = $this->session->userdata('filtro');
        if($filtro)
            $data['filtro'] = $filtro;
        
        $page_limit = $this->config->item("per_page");
    	$datos = $this->a->get_paged_list_presentaciones($page_limit, $offset, $filtro)->result();
    	
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
    	$this->table->set_heading('Nombre', 'Presentación', 'SKU', 'Stock', 'Categoría');
    	foreach ($datos as $d) {
            $categoria = $this->c->get_by_id($d->id_categoria)->row();
            $stock = $this->st->get_real_by_producto($d->id)->row();
            $this->table->add_row(
                    $d->nombre,
                    $d->presentacion,
                    $d->sku,
                    $stock->stock,
                    $categoria->nombre
            );
            //echo $this->db->last_query();
            //die();
    	}
    	$data['table'] = $this->table->generate();
    	
    	$this->load->view('lista', $data);
    }
    
}

?>
