<?php

/**
 * Controlador para productos
 *
 * @author cherra
 */
class Productos extends CI_Controller{
    
    private $folder = 'compras/';
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
    	
    	$this->load->view('compras/lista', $data);
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
        $this->load->view('compras/productos/categorias_formulario', $data);
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
        
        $this->load->view('compras/productos/categorias_formulario', $data);
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
    	$this->table->set_heading('Nombre', 'Presentación', 'SKU', 'Categoría');
    	foreach ($datos as $d) {
            $categoria = $this->c->get_by_id($d->id_categoria)->row();
            $this->table->add_row(
                    $d->nombre,
                    $d->presentacion,
                    $d->sku,
                    $categoria->nombre,
                    array('data' => anchor($this->folder.$this->clase.'productos_presentaciones/' . $d->id, '<i class="icon-bitbucket"></i>', array('class' => 'btn btn-small')), 'style' => 'text-align: right;'),
                    array('data' => anchor($this->folder.$this->clase.'productos_editar/' . $d->id, '<i class="icon-edit"></i>', array('class' => 'btn btn-small')), 'style' => 'text-align: right;')
            );
    	}
    	$data['table'] = $this->table->generate();
    	
    	$this->load->view('compras/lista', $data);
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
        $this->load->view('compras/productos/productos_formulario', $data);
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
        
        $this->load->view('compras/productos/productos_formulario', $data);
    }
    
    public function productos_presentaciones( $id = NULL, $id_presentacion = NULL ){
        if(empty($id)){
            redirect('compras/productos/productos');
        }
        
        $this->load->model('producto','p');
        $this->load->model('categoria','c');
        $this->load->model('presentacion','pr');
        $this->load->model('producto_presentacion', 'pp');
        
        $data['titulo'] = 'Prdoductos <small>Presentaciones</small>';
        $data['link_back'] = anchor('compras/productos/index','<i class="icon-arrow-left"></i> Regresar',array('class'=>'btn'));
        $data['mensaje'] = $this->session->flashdata('mensaje');
        $data['action'] = site_url('compras/productos/productos_add_presentacion');
        
        $data['producto'] = $this->p->get_by_id($id)->row();
        $data['categoria'] = $this->c->get_by_id($data['producto']->id_categoria)->row();
        $data['presentaciones'] = $this->pp->get_no_asignadas_by_producto($id)->result();
        
        $presentaciones = $this->pp->get_by_producto($id)->result();
        // generar tabla
        $this->load->library('table');
        $this->table->set_empty('-');
        $tmpl = array ('table_open'  => '<table class="' . $this->config->item('tabla_css') . '" >' );
        $this->table->set_template($tmpl);
        $this->table->set_heading('Presentacion', 'Código', 'SKU', 'Peso', '');
        
        foreach ($presentaciones as $p) {
            $this->table->add_row(
                $p->nombre,
                $p->codigo,
                $p->sku,
                $p->peso,
                array('data' => anchor('compras/productos/productos_delete_presentacion/' . $id.'/'.$p->id, '<i class="icon-remove"></i>', array('class' => 'btn btn-small', 'title' => 'Quitar')), 'class' => 'hidden-phone')
            );
        }
        
        if(!empty($id_presentacion)){
            $data['presentacion'] = $this->pr->get_by_id($id_presentacion)->row();
        }
        $data['table'] = $this->table->generate();

        $this->load->view('compras/productos/productos_presentaciones', $data);
    }
    
    public function productos_add_presentacion(){
        $this->load->model('producto_presentacion', 'pp');
        if( ($datos = $this->input->post()) ){
            $disponible = $this->pp->sku_disponible($datos['sku']);
            if($disponible){
                $resultado = $this->pp->save($datos);
                if($resultado > 0){
                    $this->session->set_flashdata('mensaje', array('tipo' => 'alert-success', 'texto' => 'Presentación agregada correctamente'));
                }else{
                    $this->session->set_flashdata('mensaje', array('tipo' => 'alert-error', 'texto' => 'Error al agregar la presentación'));
                }
            }else{
                $this->session->set_flashdata('mensaje', array('tipo' => 'alert-error', 'texto' => 'Error al agregar la presentación: SKU duplicado'));
            }
            redirect('compras/productos/productos_presentaciones/'.$datos['id_producto']);
        }else{
            redirect('compras/productos/productos_presentaciones');
        }
    }
    
    /*
     * Para desasignar una presentación de un producto
     */
    public function productos_delete_presentacion( $id_producto = NULL, $id_presentacion = NULL ){
        if(empty($id_presentacion) OR empty($id_producto)){
            redirect('compras/productos/productos_presentaciones');
        }
        $this->load->model('producto_presentacion','pp');
        $this->pp->delete($id_producto, $id_presentacion);
        redirect('compras/productos/productos_presentaciones/'.$id_producto);
    }
    
    /*
     * Categorías de productos
     */
    public function presentaciones( $offset = 0 ){
        $this->load->model('presentacion','a');
        $this->config->load("pagination");
    	
        $data['titulo'] = 'Presentaciones <small>Lista</small>';
    	$data['link_add'] = anchor($this->folder.$this->clase.'presentaciones_agregar','<i class="icon-plus icon-white"></i> Agregar', array('class' => 'btn btn-inverse'));
    	$data['action'] = $this->folder.$this->clase.'presentaciones';
        
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
    	$this->table->set_heading('Nombre', '');
    	foreach ($datos as $d) {
    		$this->table->add_row(
                    $d->nombre,
                    array('data' => anchor($this->folder.$this->clase.'presentaciones_editar/' . $d->id, '<i class="icon-edit"></i>', array('class' => 'btn btn-small')), 'style' => 'text-align: right;')
    		);
    	}
    	$data['table'] = $this->table->generate();
    	
    	$this->load->view('compras/lista', $data);
    }
    
    public function presentaciones_agregar() {
    	$this->load->model('presentacion', 'a');
        
    	$data['titulo'] = 'Presentaciones <small>Registro nuevo</small>';
    	$data['link_back'] = anchor($this->folder.$this->clase.'presentaciones','<i class="icon-arrow-left"></i> Regresar',array('class'=>'btn'));
    
    	$data['action'] = site_url($this->folder.$this->clase.'presentaciones_agregar');
    	if ( ($datos = $this->input->post()) ) {
    		$this->a->save($datos);
    		$data['mensaje'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>¡Registro exitoso!</div>';
    	} 
        $this->load->view('compras/productos/presentaciones_formulario', $data);
    }
    
    public function presentaciones_editar($id = NULL) {
    	$this->load->model('presentacion', 'a');
        $categoria = $this->a->get_by_id( $id );
        if ( empty($id) OR $categoria->num_rows() <= 0 ) {
    		redirect(site_url($this->folder.$this->clase.'presentaciones'));
    	}
    	
    	$data['titulo'] = 'Presentaciones <small>Editar registro</small>';
    	$data['link_back'] = anchor($this->folder.$this->clase.'presentaciones','<i class="icon-arrow-left"></i> Regresar',array('class'=>'btn'));
    	$data['mensaje'] = '';
    	$data['action'] = site_url($this->folder.$this->clase.'presentaciones_editar') . '/' . $id;
    	 
    	if ( ($datos = $this->input->post()) ) {
    		$this->a->update($id, $datos);
    		$data['mensaje'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>¡Registro modificado!</div>';
    	}

    	$data['datos'] = $this->a->get_by_id($id)->row();
        
        $this->load->view('compras/productos/presentaciones_formulario', $data);
    }
    
    /*
     * Métodos con Ajax
     */
    
    public function get_productos(){
        // La petición debe venir por GET
        if($this->input->is_ajax_request()){
            $this->load->model('producto','p');
            $limit = ($this->input->get('limit') ? $this->input->get('limit') : NULL);
            $filtro = ($this->input->get('filtro') ? $this->input->get('filtro') : NULL);
            
            $query = $this->p->get_paged_list($limit, 0, $filtro);

            if($query->num_rows() > 0){
                $productos = $query->result();
                echo json_encode($productos);
            }else{
                echo json_encode(FALSE);
            }
        }
    }
    
    public function get_presentaciones(){
        // La petición debe venir por GET
        if($this->input->is_ajax_request()){
            if( ($id_producto = $this->input->get('id_producto')) ){
                $this->load->model('producto_presentacion', 'p');
                
                //$limit = ($this->input->get('limit') ? $this->input->get('limit') : NULL);
                //$filtro = ($this->input->get('filtro') ? $this->input->get('filtro') : NULL);
                if($id_producto){
                    $query = $this->p->get_by_producto($id_producto);
                    if($query->num_rows() > 0){
                        $presentaciones = $query->result();
                        echo json_encode($presentaciones);
                    }else{
                        echo json_encode(FALSE);
                    }
                }else{
                    echo json_encode(FALSE);
                }
            }else{
                echo json_encode(FALSE);
            }
        }
    }
}

?>
