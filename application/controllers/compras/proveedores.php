<?php
/**
 * Description of proveedores
 *
 * @author cherra
 */
class Proveedores extends CI_Controller {
    
    private $folder = 'compras/';
    private $clase = 'proveedores/';
    
    function __construct() {
        parent::__construct();
    }
    
    /*
     * Listado de proveedores
     */
    public function index( $offset = 0 ){
        $this->load->model('proveedor','p');
        
        $this->config->load("pagination");
    	
        $data['titulo'] = 'Proveedores <small>Lista</small>';
    	$data['link_add'] = anchor($this->folder.$this->clase.'proveedores_agregar','<i class="icon-plus icon-white"></i> Agregar', array('class' => 'btn btn-inverse'));
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
    	$this->table->set_heading('Nombre', 'Población', 'Municipio', 'Estado', 'Teléfono', 'Contacto', '', '');
    	foreach ($datos as $d) {
            $this->table->add_row(
                    $d->nombre,
                    $d->poblacion,
                    $d->municipio,
                    $d->estado,
                    $d->telefono,
                    $d->contacto,
                    array('data' => anchor($this->folder.$this->clase.'proveedores_editar/' . $d->id, '<i class="icon-edit"></i>', array('class' => 'btn btn-small')), 'style' => 'text-align: right;')
            );
    	}
    	$data['table'] = $this->table->generate();
    	
    	$this->load->view('lista', $data);
    }
    
    /*
     * Agregar un proveedor
     */
    public function proveedores_agregar() {
        $this->load->model('proveedor','p');
        
    	$data['titulo'] = 'Proveedores <small>Registro nuevo</small>';
    	$data['link_back'] = anchor($this->folder.$this->clase.'index','<i class="icon-arrow-left"></i> Regresar',array('class'=>'btn'));
    
    	$data['action'] = $this->folder.$this->clase.'proveedores_agregar';
    	if ( ($datos = $this->input->post()) ) {
    		$this->p->save($datos);
    		$data['mensaje'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>¡Registro exitoso!</div>';
    	}
        $this->load->view('compras/proveedores/formulario', $data);
    }
    
    /*
     * Editar un proveedor
     */
    public function proveedores_editar( $id = NULL ) {
    	$this->load->model('proveedor', 'p');
        
        $proveedor = $this->p->get_by_id($id);
        if ( empty($id) OR $proveedor->num_rows() <= 0) {
    		redirect($this->folder.$this->clase.'index');
    	}
    	
    	$data['titulo'] = 'Proveedores <small>Editar registro</small>';
    	$data['link_back'] = anchor($this->folder.$this->clase.'index','<i class="icon-arrow-left"></i> Regresar',array('class'=>'btn'));
    	$data['mensaje'] = '';
    	$data['action'] = $this->folder.$this->clase.'proveedores_editar/' . $id;
    	 
    	if ( ($datos = $this->input->post()) ) {
    		$this->p->update($id, $datos);
    		$data['mensaje'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>¡Registro modificado!</div>';
    	}

    	$data['datos'] = $this->p->get_by_id($id)->row();
        
        $this->load->view('compras/proveedores/formulario', $data);
    }

    /*
     * Métodos Ajax
     */
    
    public function get_proveedores( $filtro = NULL ){
        // La petición debe venir por GET
        if($this->input->is_ajax_request()){
            if( ($filtro = $this->input->get('filtro')) ){
                $this->load->model('proveedor','p');
                $limit = ($this->input->get('limit') ? $this->input->get('limit') : NULL);
                $query = $this->p->get_paged_list($limit, 0, $filtro);
                
                if($query->num_rows() > 0){
                    $proveedores = $query->result();
                    echo json_encode($proveedores);
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
