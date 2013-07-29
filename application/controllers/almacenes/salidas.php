<?php

/**
 * Description of salidas
 *
 * @author cherra
 */
class Salidas extends CI_Controller {
    
    private $folder = 'almacen/';
    private $clase = 'salidas/';
    private $iconos_estado = array(
        0 => 'icon-remove',
        1 => 'icon-check-empty',
        2 => 'icon-gears',
        3 => 'icon-adjust',
        4 => 'icon-check'
    );
    
    function __construct() {
        parent::__construct();
    }
    
    public function ordenes_salida( $offset = 0){
        $this->load->model('orden_salida','os');
        $this->load->model('cliente','c');
        $this->load->model('sucursal','s');
        $this->load->model('almacen','a');
        $this->load->model('ruta','r');
        
        $this->config->load("pagination");
    	
        $data['titulo'] = 'Ordenes de salida <small>Lista</small>';
        //$data['link_add'] = anchor($this->folder.$this->clase.'pedidos_agregar','<i class="icon-plus icon-white"></i> Nuevo', array('class' => 'btn btn-inverse'));
    	$data['action'] = $this->folder.$this->clase.'ordenes_salida';
        
        // Filtro de busqueda (se almacenan en la sesión a través de un hook)
        $filtro = $this->session->userdata('filtro');
        if($filtro)
            $data['filtro'] = $filtro;
        
        $page_limit = $this->config->item("per_page");
    	$datos = $this->os->get_paged_list($page_limit, $offset, $filtro)->result();
    	
        // generar paginacion
    	$this->load->library('pagination');
    	$config['base_url'] = site_url($this->folder.$this->clase.'index');
    	$config['total_rows'] = $this->os->count_all($filtro);
    	$config['per_page'] = $page_limit;
    	$config['uri_segment'] = 4;
    	$this->pagination->initialize($config);
    	$data['pagination'] = $this->pagination->create_links();
    	
    	// generar tabla
    	$this->load->library('table');
    	$this->table->set_empty('&nbsp;');
    	$tmpl = array ( 'table_open' => '<table class="' . $this->config->item('tabla_css') . '" >' );
    	$this->table->set_template($tmpl);
    	$this->table->set_heading('E','Número','Fecha','Cliente','Almacén','Ruta','Fecha programada', 'Piezas', 'Origen', '');
    	foreach ($datos as $d) {
            $almacen = $this->a->get_by_id($d->id_almacen)->row();
            $sucursal = $this->s->get_by_id($d->id_cliente_sucursal)->row();
            $cliente = $this->c->get_by_id($sucursal->id_cliente)->row();
            $ruta = $this->r->get_by_id($d->id_ruta)->row();
            $piezas = $this->os->get_piezas($d->id);
    		$this->table->add_row(
                        '<i class="'.$this->iconos_estado[$d->estado].'"></i>',
                        $d->id,
                        $d->fecha,
                        $cliente->nombre,
                        !empty($almacen->nombre) ? $almacen->nombre : '',
                        $ruta->nombre,
                        $d->fecha_programada,
                        array('data' => number_format($piezas,2), 'style' => 'text-align: right;'),
                        $d->origen,
                        array('data' => ($d->estado > 0 && $d->estado < 4 ? anchor($this->folder.$this->clase.'ordenes_salida_editar/' . $d->id, '<i class="icon-edit"></i>', array('class' => 'btn btn-small', 'title' => 'Editar')) :  '<a class="btn btn-small" disabled><i class="icon-edit"></i></a>'), 'style' => 'text-align: right;')
    		);
                if($d->estado == 0)
                    $this->table->add_row_class('muted');
                else
                    $this->table->add_row_class('');
    	}
    	$data['table'] = $this->table->generate();
    	
    	$this->load->view('lista', $data);
    }
}
?>
