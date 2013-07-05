<?php
/**
 * Description of rutas
 *
 * @author cherra
 */
class Rutas extends CI_Controller {
    
    private $folder = 'ventas/';
    private $clase = 'rutas/';
    
    function __construct() {
        parent::__construct();
    }
    
    /*
     * Listado de rutas
     */
    public function index( $offset = 0 ){
        $this->load->model('ruta','r');
        $this->config->load("pagination");
    	
        $data['titulo'] = 'Rutas <small>Lista</small>';
        $data['link_add'] = anchor($this->folder.$this->clase.'rutas_agregar','<i class="icon-plus icon-white"></i> Agregar', array('class' => 'btn btn-inverse'));
    	$data['action'] = $this->folder.$this->clase.'rutas';
        
        // Filtro de busqueda (se almacenan en la sesión a través de un hook)
        $filtro = $this->session->userdata('filtro');
        if($filtro)
            $data['filtro'] = $filtro;
        
        $page_limit = $this->config->item("per_page");
    	$datos = $this->r->get_paged_list($page_limit, $offset, $filtro)->result();
    	
        // generar paginacion
    	$this->load->library('pagination');
    	$config['base_url'] = site_url($this->folder.$this->clase.'categorias');
    	$config['total_rows'] = $this->r->count_all($filtro);
    	$config['per_page'] = $page_limit;
    	$config['uri_segment'] = 4;
    	$this->pagination->initialize($config);
    	$data['pagination'] = $this->pagination->create_links();
    	
    	// generar tabla
    	$this->load->library('table');
    	$this->table->set_empty('&nbsp;');
    	$tmpl = array ( 'table_open' => '<table class="' . $this->config->item('tabla_css') . '" >' );
    	$this->table->set_template($tmpl);
    	$this->table->set_heading('Número','Nombre','Descripción', '');
    	foreach ($datos as $d) {
    		$this->table->add_row(
                        $d->numero,
                        $d->nombre,
                        $d->descripcion,
                        array('data' => anchor($this->folder.$this->clase.'rutas_editar/' . $d->id, '<i class="icon-edit"></i>', array('class' => 'btn btn-small', 'title' => 'Editar')), 'style' => 'text-align: right;')
    		);
    	}
    	$data['table'] = $this->table->generate();
    	
    	$this->load->view('ventas/lista', $data);
    }
    
    public function rutas_agregar() {
    	$this->load->model('ruta', 'r');
        
    	$data['titulo'] = 'Rutas <small>Registro nuevo</small>';
    	$data['link_back'] = anchor($this->folder.$this->clase.'index','<i class="icon-arrow-left"></i> Regresar',array('class'=>'btn'));
    
    	$data['action'] = site_url($this->folder.$this->clase.'rutas_agregar');
    	if ( ($datos = $this->input->post()) ) {
            if($this->r->numero_disponible($datos['numero'])){
    		$this->r->save($datos);
                $data['mensaje'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>¡Registro exitoso!</div>';
            }else{
                $data['datos'] = (object)$datos;
                $data['mensaje'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>¡Error: el número de ruta ya existe!</div>';
            }
    	} 
        $this->load->view('ventas/rutas/rutas_formulario', $data);
    }
    
    public function rutas_editar($id = NULL) {
    	$this->load->model('ruta', 'r');
        $ruta = $this->r->get_by_id( $id );
        if ( empty($id) OR $ruta->num_rows() <= 0 ) {
    		redirect($this->folder.$this->clase.'index');
    	}
    	
    	$data['titulo'] = 'Rutas <small>Editar registro</small>';
    	$data['link_back'] = anchor($this->folder.$this->clase.'rutas','<i class="icon-arrow-left"></i> Regresar',array('class'=>'btn'));
    	$data['mensaje'] = '';
    	$data['action'] = site_url($this->folder.$this->clase.'rutas_editar') . '/' . $id;
    	 
    	if ( ($datos = $this->input->post()) ) {
            if($this->r->numero_disponible($datos['numero'])){
    		$this->r->update($id, $datos);
    		$data['mensaje'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>¡Registro modificado!</div>';
            }else{
                $data['datos'] = (object)$datos;
                $data['mensaje'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>¡Error: el número de ruta ya existe!</div>';
            }
    	}

    	$data['datos'] = $this->r->get_by_id($id)->row();
        
        $this->load->view('ventas/rutas/rutas_formulario', $data);
    }
}
?>
