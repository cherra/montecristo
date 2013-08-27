<?php

/**
 * Description of entradas
 *
 * @author cherra
 */
class Entradas extends CI_Controller {

    private $folder = 'almacenes/';
    private $clase = 'entradas/';
    private $iconos_estado = array(
        0 => 'icon-remove',
        1 => 'icon-time',
        2 => 'icon-download-alt'
    );

    function __construct() {
        parent::__construct();
    }

    // Listado de todas las ordenes de salida
    public function index($offset = 0) {
        $this->load->model('orden_entrada', 'oe');
        $this->load->model('proveedor', 'p');
        $this->load->model('almacen', 'a');
        $this->load->model('preferencias/usuario','u');

        $this->config->load("pagination");

        $data['titulo'] = 'Ordenes de entrada <small>Lista</small>';
        //$data['link_add'] = anchor($this->folder.$this->clase.'pedidos_agregar','<i class="icon-plus icon-white"></i> Nuevo', array('class' => 'btn btn-inverse'));
        $data['action'] = $this->folder . $this->clase . 'index';

        // Filtro de busqueda (se almacenan en la sesión a través de un hook)
        $filtro = $this->session->userdata('filtro');
        if ($filtro)
            $data['filtro'] = $filtro;

        $page_limit = $this->config->item("per_page");
        $datos = $this->oe->get_paged_list($page_limit, $offset, $filtro)->result();

        // generar paginacion
        $this->load->library('pagination');
        $config['base_url'] = site_url($this->folder . $this->clase . 'index');
        $config['total_rows'] = $this->oe->count_all($filtro);
        $config['per_page'] = $page_limit;
        $config['uri_segment'] = 4;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        // generar tabla
        $this->load->library('table');
        $this->table->set_empty('&nbsp;');
        $tmpl = array('table_open' => '<table class="' . $this->config->item('tabla_css') . '" >');
        $this->table->set_template($tmpl);
        $this->table->set_heading('E', 'Número', 'Fecha', 'Proveedor', 'Almacén', 'Fecha programada', 'Piezas', 'Origen', 'Usuario', '');
        foreach ($datos as $d) {
            $almacen = $this->a->get_by_id($d->id_almacen)->row();
            $proveedor = $this->p->get_by_id($d->id_proveedor)->row();
            //$ruta = $this->r->get_by_id($d->id_ruta)->row();
            $piezas = $this->oe->get_piezas($d->id);
            $usuario = $this->u->get_by_id($d->id_usuario)->row();
            $this->table->add_row(
                    '<i class="'.$this->iconos_estado[$d->estado].'"></i>', 
                    $d->id, 
                    $d->fecha, 
                    $proveedor->nombre,
                    !empty($almacen->nombre) ? $almacen->nombre : '', 
                    $d->fecha_programada, 
                    array('data' => number_format($piezas, 2), 'style' => 'text-align: right;'), 
                    $d->origen, 
                    $usuario->nombre,
                    array('data' => ($d->estado > 0 && $d->estado < 4 ? anchor($this->folder . $this->clase . 'ordenes_entrada_editar/' . $d->id, '<i class="icon-edit"></i>', array('class' => 'btn btn-small', 'title' => 'Editar')) : '<a class="btn btn-small" disabled><i class="icon-edit"></i></a>'), 'style' => 'text-align: right;')
            );
            if ($d->estado == 0)
                $this->table->add_row_class('muted');
            else
                $this->table->add_row_class('');
        }
        $data['table'] = $this->table->generate();

        $this->load->view('lista', $data);
    }
    
    public function ordenes_entrada_espera($offset = 0) {
        $this->load->model('orden_entrada', 'oe');
        $this->load->model('proveedor', 'p');
        $this->load->model('almacen', 'a');

        $this->config->load("pagination");

        $data['titulo'] = 'Ordenes de entrada en espera <small>Lista</small>';
        //$data['link_add'] = anchor($this->folder.$this->clase.'pedidos_agregar','<i class="icon-plus icon-white"></i> Nuevo', array('class' => 'btn btn-inverse'));
        $data['action'] = $this->folder . $this->clase . 'ordenes_entrada_espera';

        // Filtro de busqueda (se almacenan en la sesión a través de un hook)
        $filtro = $this->session->userdata('filtro');
        if ($filtro)
            $data['filtro'] = $filtro;

        // Se marca(n) como recibidas las entradas
        if ($this->input->post()) {
            $entradas = $this->input->post('entradas');
            //$fecha = $this->input->post('fecha_entrega').' '.$this->input->post('hora_entrega');
            foreach ($entradas as $id) {
                $this->oe->update($id, array('estado' => '2', 'fecha_entrega' => $this->input->post('fecha_entrega').' '.$this->input->post('hora_entrega'))); // Estado 2 = Recibida
                //$this->p->update_by_orden_salida($s, array('estado' => '4'));  // Estado 4 = Enviado
            }
        }
        
        $page_limit = $this->config->item("per_page");
        $datos = $this->oe->get_by_estado(array('1'), $page_limit, $offset, $filtro)->result();  // Estado 3 = Enviadas

        // generar paginacion
        $this->load->library('pagination');
        $config['base_url'] = site_url($this->folder . $this->clase . 'ordenes_entrada_espera');
        $config['total_rows'] = $this->oe->count_by_estado(array('1'), $filtro);
        $config['per_page'] = $page_limit;
        $config['uri_segment'] = 4;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        // generar tabla
        $this->load->library('table');
        $this->table->set_empty('&nbsp;');
        $tmpl = array('table_open' => '<table class="' . $this->config->item('tabla_css') . '" >');
        $this->table->set_template($tmpl);
        $this->table->set_heading('E', 'Número', 'Fecha', 'Proveedor', 'Almacén', 'Fecha programada', 'Piezas', 'Origen', '');
        foreach ($datos as $d) {
            $almacen = $this->a->get_by_id($d->id_almacen)->row();
            $proveedor = $this->p->get_by_id($d->id_proveedor)->row();
            //$ruta = $this->r->get_by_id($d->id_ruta)->row();
            $piezas = $this->oe->get_piezas($d->id);
            $this->table->add_row(
                    $d->estado == '1' ? '<input type="checkbox" name="entradas[]" value="'.$d->id.'"/>' : '<i class="'.$this->iconos_estado[$d->estado].'"></i>',
                    $d->id, 
                    $d->fecha, 
                    $proveedor->nombre, 
                    !empty($almacen->nombre) ? $almacen->nombre : '', 
                    $d->fecha_programada,
                    array('data' => number_format($piezas, 2), 'style' => 'text-align: right;'), 
                    $d->origen, 
                    array('data' => ($d->estado > 0 && $d->estado < 4 ? anchor($this->folder . $this->clase . 'ordenes_salida_editar/' . $d->id, '<i class="icon-edit"></i>', array('class' => 'btn btn-small', 'title' => 'Editar')) : '<a class="btn btn-small" disabled><i class="icon-edit"></i></a>'), 'style' => 'text-align: right;')
            );
            if ($d->estado == 0)
                $this->table->add_row_class('muted');
            else
                $this->table->add_row_class('');
        }
        $data['table'] = $this->table->generate();

        $data['almacenes'] = $this->a->get_all()->result();
        $this->load->view('almacenes/entradas/espera_lista', $data);
    }
    
    public function ordenes_entrada_recibidas($offset = 0) {
        $this->load->model('orden_entrada', 'oe');
        $this->load->model('proveedor', 'p');
        $this->load->model('almacen', 'a');

        $this->config->load("pagination");

        $data['titulo'] = 'Ordenes de entrada recibidas <small>Lista</small>';
        //$data['link_add'] = anchor($this->folder.$this->clase.'pedidos_agregar','<i class="icon-plus icon-white"></i> Nuevo', array('class' => 'btn btn-inverse'));
        $data['action'] = $this->folder . $this->clase . 'ordenes_entrada_recibidas';

        // Filtro de busqueda (se almacenan en la sesión a través de un hook)
        $filtro = $this->session->userdata('filtro');
        if ($filtro)
            $data['filtro'] = $filtro;

        $page_limit = $this->config->item("per_page");
        $datos = $this->oe->get_by_estado(array('2'), $page_limit, $offset, $filtro)->result();  // Estado 2 = Recibidas

        // generar paginacion
        $this->load->library('pagination');
        $config['base_url'] = site_url($this->folder . $this->clase . 'ordenes_entrada_recibidas');
        $config['total_rows'] = $this->oe->count_by_estado(array('2'), $filtro);
        $config['per_page'] = $page_limit;
        $config['uri_segment'] = 4;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        // generar tabla
        $this->load->library('table');
        $this->table->set_empty('&nbsp;');
        $tmpl = array('table_open' => '<table class="' . $this->config->item('tabla_css') . '" >');
        $this->table->set_template($tmpl);
        $this->table->set_heading('E', 'Número', 'Fecha', 'Proveedor', 'Almacén', 'Fecha programada', 'Fecha de recepción', 'Piezas', 'Origen', '');
        foreach ($datos as $d) {
            $almacen = $this->a->get_by_id($d->id_almacen)->row();
            $proveedor = $this->p->get_by_id($d->id_proveedor)->row();
            //$ruta = $this->r->get_by_id($d->id_ruta)->row();
            $piezas = $this->oe->get_piezas($d->id);
            $this->table->add_row(
                    $d->estado == '1' ? '<input type="checkbox" name="entradas[]" value="'.$d->id.'"/>' : '<i class="'.$this->iconos_estado[$d->estado].'"></i>',
                    $d->id, 
                    $d->fecha, 
                    $proveedor->nombre, 
                    !empty($almacen->nombre) ? $almacen->nombre : '', 
                    $d->fecha_programada,
                    $d->fecha_entrega, 
                    array('data' => number_format($piezas, 2), 'style' => 'text-align: right;'), 
                    $d->origen, 
                    array('data' => ($d->estado > 0 && $d->estado < 4 ? anchor($this->folder . $this->clase . 'ordenes_salida_editar/' . $d->id, '<i class="icon-edit"></i>', array('class' => 'btn btn-small', 'title' => 'Editar')) : '<a class="btn btn-small" disabled><i class="icon-edit"></i></a>'), 'style' => 'text-align: right;')
            );
            if ($d->estado == 0)
                $this->table->add_row_class('muted');
            else
                $this->table->add_row_class('');
        }
        $data['table'] = $this->table->generate();

        $data['almacenes'] = $this->a->get_all()->result();
        $this->load->view('almacenes/entradas/espera_lista', $data);
    }
}

?>
