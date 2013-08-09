<?php

/**
 * Description of salidas
 *
 * @author cherra
 */
class Salidas extends CI_Controller {

    private $folder = 'almacenes/';
    private $clase = 'salidas/';
    private $iconos_estado = array(
        0 => 'icon-remove',
        1 => 'icon-gears',
        2 => 'icon-adjust',
        3 => 'icon-check'
    );

    function __construct() {
        parent::__construct();
    }

    // Listado de todas las ordenes de salida
    public function index($offset = 0) {
        $this->load->model('orden_salida', 'os');
        $this->load->model('cliente', 'c');
        $this->load->model('sucursal', 's');
        $this->load->model('almacen', 'a');
        $this->load->model('ruta', 'r');

        $this->config->load("pagination");

        $data['titulo'] = 'Ordenes de salida <small>Lista</small>';
        //$data['link_add'] = anchor($this->folder.$this->clase.'pedidos_agregar','<i class="icon-plus icon-white"></i> Nuevo', array('class' => 'btn btn-inverse'));
        $data['action'] = $this->folder . $this->clase . 'ordenes_salida';

        // Filtro de busqueda (se almacenan en la sesión a través de un hook)
        $filtro = $this->session->userdata('filtro');
        if ($filtro)
            $data['filtro'] = $filtro;

        $page_limit = $this->config->item("per_page");
        $datos = $this->os->get_paged_list($page_limit, $offset, $filtro)->result();

        // generar paginacion
        $this->load->library('pagination');
        $config['base_url'] = site_url($this->folder . $this->clase . 'index');
        $config['total_rows'] = $this->os->count_all($filtro);
        $config['per_page'] = $page_limit;
        $config['uri_segment'] = 4;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        // generar tabla
        $this->load->library('table');
        $this->table->set_empty('&nbsp;');
        $tmpl = array('table_open' => '<table class="' . $this->config->item('tabla_css') . '" >');
        $this->table->set_template($tmpl);
        $this->table->set_heading('E', 'Número', 'Fecha', 'Cliente', 'Sucursal', 'Ubicación', 'Almacén', 'Fecha programada', 'Piezas', 'Origen', '');
        foreach ($datos as $d) {
            $almacen = $this->a->get_by_id($d->id_almacen)->row();
            $sucursal = $this->s->get_by_id($d->id_cliente_sucursal)->row();
            $cliente = $this->c->get_by_id($sucursal->id_cliente)->row();
            //$ruta = $this->r->get_by_id($d->id_ruta)->row();
            $piezas = $this->os->get_piezas($d->id);
            $this->table->add_row(
                    '<i class="'.$this->iconos_estado[$d->estado].'"></i>', 
                    $d->id, 
                    $d->fecha, 
                    $cliente->nombre, $sucursal->numero . ' ' . $sucursal->nombre, $sucursal->poblacion . ', ' . $sucursal->municipio, 
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

        $this->load->view('lista', $data);
    }

    public function ordenes_salida($offset = 0) {
        $this->load->model('orden_salida', 'os');
        $this->load->model('cliente', 'c');
        $this->load->model('sucursal', 's');
        $this->load->model('almacen', 'a');
        $this->load->model('ruta', 'r');

        $this->config->load("pagination");

        $data['titulo'] = 'Ordenes de salida en proceso <small>Lista</small>';
        //$data['link_add'] = anchor($this->folder.$this->clase.'pedidos_agregar','<i class="icon-plus icon-white"></i> Nuevo', array('class' => 'btn btn-inverse'));
        $data['action'] = $this->folder . $this->clase . 'ordenes_salida';

        // Filtro de busqueda (se almacenan en la sesión a través de un hook)
        $filtro = $this->session->userdata('filtro');
        if ($filtro)
            $data['filtro'] = $filtro;

        $page_limit = $this->config->item("per_page");
        $datos = $this->os->get_grouped_by_ruta($page_limit, $offset, $filtro, array('1'))->result();

        // generar paginacion
        $this->load->library('pagination');
        $config['base_url'] = site_url($this->folder . $this->clase . 'ordenes_salida');
        $config['total_rows'] = $this->os->count_grouped_by_ruta($filtro, array('1'));
        $config['per_page'] = $page_limit;
        $config['uri_segment'] = 4;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        // generar tabla
        $this->load->library('table');
        $this->table->set_empty('&nbsp;');
        $tmpl = array('table_open' => '<table class="' . $this->config->item('tabla_css') . '" >');
        $this->table->set_template($tmpl);
        $this->table->set_heading('Ruta', 'Ordenes', 'Piezas', 'Peso', '');
        foreach ($datos as $d) {
            $this->table->add_row(
                    $d->ruta, 
                    $d->ordenes, 
                    array('data' => number_format($d->piezas, 2), 'style' => 'text-align: right;'), 
                    array('data' => number_format($d->peso, 2), 'style' => 'text-align: right;'), 
                    array('data' => anchor($this->folder . $this->clase . 'ordenes_salida_ruta/' . $d->id_ruta, '<i class="icon-list"></i>', array('class' => 'btn btn-small', 'title' => 'Ordenes de salida')), 'style' => 'text-align: right;')
            );
        }
        $data['table'] = $this->table->generate();

        $this->load->view('lista', $data);
    }

    public function ordenes_salida_ruta($id_ruta = NULL, $offset = 0) {
        $this->load->model('orden_salida', 'os');
        $this->load->model('cliente', 'c');
        $this->load->model('sucursal', 's');
        $this->load->model('almacen', 'a');
        $this->load->model('ruta', 'r');
        $this->load->model('pedido', 'p');

        $this->config->load("pagination");

        $data['titulo'] = 'Ordenes de salida en proceso por ruta <small>Lista</small>';
        //$data['link_add'] = anchor($this->folder.$this->clase.'pedidos_agregar','<i class="icon-plus icon-white"></i> Nuevo', array('class' => 'btn btn-inverse'));
        $data['link_back'] = anchor($this->folder . $this->clase . 'ordenes_salida', '<i class="icon-arrow-left"></i> Regresar', array('class' => 'btn'));
        $data['action'] = $this->folder . $this->clase . 'ordenes_salida_ruta/' . $id_ruta;

        // Filtro de busqueda (se almacenan en la sesión a través de un hook)
        $filtro = $this->session->userdata('filtro');
        if ($filtro)
            $data['filtro'] = $filtro;

        $data['rutas'] = $this->r->get_all()->result();
        $data['ruta'] = $this->r->get_by_id($id_ruta)->row();

        $data['almacenes'] = $this->a->get_all()->result();

        // Se marca(n) como enviadas las ordenes de salida
        if ($this->input->post()) {
            $salidas = $this->input->post('salidas');
            $id_almacen = $this->input->post('id_almacen');
            foreach ($salidas as $s) {
                $this->os->update($s, array('estado' => '2', 'id_almacen' => $id_almacen));
                $this->p->update_by_orden_salida($s, array('estado' => '3'));
            }
        }

        $page_limit = $this->config->item("per_page");
        $datos = $this->os->get_by_ruta($id_ruta, array('1'), $page_limit, $offset, $filtro)->result();
        // generar paginacion
        $this->load->library('pagination');
        $config['base_url'] = site_url($this->folder . $this->clase . 'ordenes_salida_ruta');
        $config['total_rows'] = $this->os->count_by_ruta($id_ruta, array('1'), $filtro);
        $config['per_page'] = $page_limit;
        $config['uri_segment'] = 4;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        // generar tabla
        $this->load->library('table');
        $this->table->set_empty('&nbsp;');
        $tmpl = array('table_open' => '<table class="' . $this->config->item('tabla_css') . '" >');
        $this->table->set_template($tmpl);
        $this->table->set_heading('E', 'Número', 'Fecha', 'Cliente', 'Sucursal', 'Ubicación', 'Almacén', 'Fecha programada', 'Piezas', 'Origen', '');
        foreach ($datos as $d) {
            $almacen = $this->a->get_by_id($d->id_almacen)->row();
            $sucursal = $this->s->get_by_id($d->id_cliente_sucursal)->row();
            $cliente = $this->c->get_by_id($sucursal->id_cliente)->row();
            //$ruta = $this->r->get_by_id($d->id_ruta)->row();
            $piezas = $this->os->get_piezas($d->id);
            $this->table->add_row(
                    '<input type="checkbox" name="salidas[]" value="' . $d->id . '"/>', 
                    $d->id, 
                    $d->fecha, 
                    $cliente->nombre, 
                    $sucursal->numero . ' ' . $sucursal->nombre, $sucursal->poblacion . ', ' . $sucursal->municipio, 
                    !empty($almacen->nombre) ? $almacen->nombre : '', $d->fecha_programada,
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

        $this->load->view('almacenes/salidas/proceso_ruta_lista', $data);
    }

    // Ordenes de salida listas para envío
    public function ordenes_salida_procesadas($offset = 0) {
        $this->load->model('orden_salida', 'os');
        $this->load->model('cliente', 'c');
        $this->load->model('sucursal', 's');
        $this->load->model('almacen', 'a');
        $this->load->model('ruta', 'r');

        $this->config->load("pagination");

        $data['titulo'] = 'Ordenes de salida procesadas <small>Lista</small>';
        //$data['link_add'] = anchor($this->folder.$this->clase.'pedidos_agregar','<i class="icon-plus icon-white"></i> Nuevo', array('class' => 'btn btn-inverse'));
        $data['action'] = $this->folder . $this->clase . 'ordenes_salida_procesadas';

        // Filtro de busqueda (se almacenan en la sesión a través de un hook)
        $filtro = $this->session->userdata('filtro');
        if ($filtro)
            $data['filtro'] = $filtro;

        $page_limit = $this->config->item("per_page");
        $datos = $this->os->get_grouped_by_ruta($page_limit, $offset, $filtro, array('2'))->result(); // 2 = Lista para envío
        // generar paginacion
        $this->load->library('pagination');
        $config['base_url'] = site_url($this->folder . $this->clase . 'ordenes_salida_procesadas');
        $config['total_rows'] = $this->os->count_grouped_by_ruta($filtro, array('2')); // 2 = Lista para envío
        $config['per_page'] = $page_limit;
        $config['uri_segment'] = 4;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        // generar tabla
        $this->load->library('table');
        $this->table->set_empty('&nbsp;');
        $tmpl = array('table_open' => '<table class="' . $this->config->item('tabla_css') . '" >');
        $this->table->set_template($tmpl);
        $this->table->set_heading('Ruta', 'Ordenes', 'Piezas', 'Peso', '');
        foreach ($datos as $d) {
            $this->table->add_row(
                    $d->ruta, 
                    $d->ordenes, 
                    array('data' => number_format($d->piezas, 2), 'style' => 'text-align: right;'), 
                    array('data' => number_format($d->peso, 2), 'style' => 'text-align: right;'),
                    array('data' => anchor($this->folder . $this->clase . 'ordenes_salida_ruta_procesadas/' . $d->id_ruta, '<i class="icon-list"></i>', array('class' => 'btn btn-small', 'title' => 'Ordenes de salida')), 'style' => 'text-align: right;')
            );
        }
        $data['table'] = $this->table->generate();

        $this->load->view('lista', $data);
    }

    public function ordenes_salida_ruta_procesadas($id_ruta = NULL, $offset = 0) {
        $this->load->model('orden_salida', 'os');
        $this->load->model('cliente', 'c');
        $this->load->model('sucursal', 's');
        $this->load->model('almacen', 'a');
        $this->load->model('ruta', 'r');
        $this->load->model('pedido', 'p');

        $this->config->load("pagination");

        $data['titulo'] = 'Ordenes de salida procesadas por ruta <small>Lista</small>';
        //$data['link_add'] = anchor($this->folder.$this->clase.'pedidos_agregar','<i class="icon-plus icon-white"></i> Nuevo', array('class' => 'btn btn-inverse'));
        $data['link_back'] = anchor($this->folder . $this->clase . 'ordenes_salida', '<i class="icon-arrow-left"></i> Regresar', array('class' => 'btn'));
        $data['action'] = $this->folder . $this->clase . 'ordenes_salida_ruta_procesadas/' . $id_ruta;

        // Filtro de busqueda (se almacenan en la sesión a través de un hook)
        $filtro = $this->session->userdata('filtro');
        if ($filtro)
            $data['filtro'] = $filtro;

        $data['rutas'] = $this->r->get_all()->result();
        $data['ruta'] = $this->r->get_by_id($id_ruta)->row();

        $data['almacenes'] = $this->a->get_all()->result();

        // Se marca(n) como enviadas las ordenes de salida
        if ($this->input->post()) {
            $salidas = $this->input->post('salidas');
            $id_almacen = $this->input->post('id_almacen');
            foreach ($salidas as $s) {
                $this->os->update($s, array('estado' => '3', 'id_almacen' => $id_almacen)); // Estado 3 = Enviado
                $this->p->update_by_orden_salida($s, array('estado' => '4'));  // Estado 4 = Enviado
            }
        }

        $page_limit = $this->config->item("per_page");
        $datos = $this->os->get_by_ruta($id_ruta, array('2'), $page_limit, $offset, $filtro)->result();
        // generar paginacion
        $this->load->library('pagination');
        $config['base_url'] = site_url($this->folder . $this->clase . 'ordenes_salida_ruta_procesadas');
        $config['total_rows'] = $this->os->count_by_ruta($id_ruta, array('2'), $filtro);
        $config['per_page'] = $page_limit;
        $config['uri_segment'] = 4;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        // generar tabla
        $this->load->library('table');
        $this->table->set_empty('&nbsp;');
        $tmpl = array('table_open' => '<table class="' . $this->config->item('tabla_css') . '" >');
        $this->table->set_template($tmpl);
        $this->table->set_heading('E', 'Número', 'Fecha', 'Cliente', 'Sucursal', 'Ubicación', 'Almacén', 'Fecha programada', 'Piezas', 'Origen', '');
        foreach ($datos as $d) {
            $almacen = $this->a->get_by_id($d->id_almacen)->row();
            $sucursal = $this->s->get_by_id($d->id_cliente_sucursal)->row();
            $cliente = $this->c->get_by_id($sucursal->id_cliente)->row();
            //$ruta = $this->r->get_by_id($d->id_ruta)->row();
            $piezas = $this->os->get_piezas($d->id);
            $this->table->add_row(
                    '<input type="checkbox" name="salidas[]" value="' . $d->id . '"/>', 
                    $d->id, $d->fecha, 
                    $cliente->nombre, 
                    $sucursal->numero . ' ' . $sucursal->nombre, $sucursal->poblacion . ', ' . $sucursal->municipio, 
                    !empty($almacen->nombre) ? $almacen->nombre : '', $d->fecha_programada, 
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

        $this->load->view('almacenes/salidas/proceso_ruta_lista', $data);
    }
    
    public function ordenes_salida_enviadas($offset = 0) {
        $this->load->model('orden_salida', 'os');
        $this->load->model('cliente', 'c');
        $this->load->model('sucursal', 's');
        $this->load->model('almacen', 'a');
        $this->load->model('ruta', 'r');

        $this->config->load("pagination");

        $data['titulo'] = 'Ordenes de salida <small>Lista</small>';
        //$data['link_add'] = anchor($this->folder.$this->clase.'pedidos_agregar','<i class="icon-plus icon-white"></i> Nuevo', array('class' => 'btn btn-inverse'));
        $data['action'] = $this->folder . $this->clase . 'ordenes_salida_enviadas';

        // Filtro de busqueda (se almacenan en la sesión a través de un hook)
        $filtro = $this->session->userdata('filtro');
        if ($filtro)
            $data['filtro'] = $filtro;

        $page_limit = $this->config->item("per_page");
        $datos = $this->os->get_by_estado(array('4'), $page_limit, $offset, $filtro)->result();

        // generar paginacion
        $this->load->library('pagination');
        $config['base_url'] = site_url($this->folder . $this->clase . 'ordenes_salida_enviadas');
        $config['total_rows'] = $this->os->count_by_estado(array('4'), $filtro);
        $config['per_page'] = $page_limit;
        $config['uri_segment'] = 4;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        // generar tabla
        $this->load->library('table');
        $this->table->set_empty('&nbsp;');
        $tmpl = array('table_open' => '<table class="' . $this->config->item('tabla_css') . '" >');
        $this->table->set_template($tmpl);
        $this->table->set_heading('E', 'Número', 'Fecha', 'Cliente', 'Sucursal', 'Ubicación', 'Almacén', 'Fecha programada', 'Piezas', 'Origen', '');
        foreach ($datos as $d) {
            $almacen = $this->a->get_by_id($d->id_almacen)->row();
            $sucursal = $this->s->get_by_id($d->id_cliente_sucursal)->row();
            $cliente = $this->c->get_by_id($sucursal->id_cliente)->row();
            //$ruta = $this->r->get_by_id($d->id_ruta)->row();
            $piezas = $this->os->get_piezas($d->id);
            $this->table->add_row(
                    '<i class="'.$this->iconos_estado[$d->estado].'"></i>', 
                    $d->id, 
                    $d->fecha, 
                    $cliente->nombre, 
                    $sucursal->numero . ' ' . $sucursal->nombre, $sucursal->poblacion . ', ' . $sucursal->municipio, 
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

        $this->load->view('lista', $data);
    }

    public function ordenes_salida_enviadas_ruta($id_ruta = NULL, $offset = 0) {
        $this->load->model('orden_salida', 'os');
        $this->load->model('cliente', 'c');
        $this->load->model('sucursal', 's');
        $this->load->model('almacen', 'a');
        $this->load->model('ruta', 'r');
        $this->load->model('pedido', 'p');

        $this->config->load("pagination");

        $data['titulo'] = 'Ordenes de salida enviadas <small>Lista</small>';
        //$data['link_add'] = anchor($this->folder.$this->clase.'pedidos_agregar','<i class="icon-plus icon-white"></i> Nuevo', array('class' => 'btn btn-inverse'));
        $data['link_back'] = anchor($this->folder . $this->clase . 'ordenes_salida_enviadas', '<i class="icon-arrow-left"></i> Regresar', array('class' => 'btn'));
        $data['action'] = $this->folder . $this->clase . 'ordenes_salida_enviadas_ruta/' . $id_ruta;

        // Filtro de busqueda (se almacenan en la sesión a través de un hook)
        $filtro = $this->session->userdata('filtro');
        if ($filtro)
            $data['filtro'] = $filtro;

        $data['rutas'] = $this->r->get_all()->result();
        $data['ruta'] = $this->r->get_by_id($id_ruta)->row();

        $data['almacenes'] = $this->a->get_all()->result();

        // Se marca(n) como enviadas las ordenes de salida
        if ($this->input->post()) {
            $salidas = $this->input->post('salidas');
            $id_almacen = $this->input->post('id_almacen');
            foreach ($salidas as $s) {
                $this->os->update($s, array('estado' => '2', 'id_almacen' => $id_almacen));
                $this->p->update_by_orden_salida($s, array('estado' => '3'));
            }
        }

        $page_limit = $this->config->item("per_page");
        $datos = $this->os->get_by_ruta($id_ruta, array('1'), $page_limit, $offset, $filtro)->result();
        // generar paginacion
        $this->load->library('pagination');
        $config['base_url'] = site_url($this->folder . $this->clase . 'ordenes_salida_ruta');
        $config['total_rows'] = $this->os->count_by_ruta($id_ruta, array('1'), $filtro);
        $config['per_page'] = $page_limit;
        $config['uri_segment'] = 4;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        // generar tabla
        $this->load->library('table');
        $this->table->set_empty('&nbsp;');
        $tmpl = array('table_open' => '<table class="' . $this->config->item('tabla_css') . '" >');
        $this->table->set_template($tmpl);
        $this->table->set_heading('E', 'Número', 'Fecha', 'Cliente', 'Sucursal', 'Ubicación', 'Almacén', 'Fecha programada', 'Piezas', 'Origen', '');
        foreach ($datos as $d) {
            $almacen = $this->a->get_by_id($d->id_almacen)->row();
            $sucursal = $this->s->get_by_id($d->id_cliente_sucursal)->row();
            $cliente = $this->c->get_by_id($sucursal->id_cliente)->row();
            //$ruta = $this->r->get_by_id($d->id_ruta)->row();
            $piezas = $this->os->get_piezas($d->id);
            $this->table->add_row(
                    '<input type="checkbox" name="salidas[]" value="' . $d->id . '"/>', $d->id, $d->fecha, $cliente->nombre, $sucursal->numero . ' ' . $sucursal->nombre, $sucursal->poblacion . ', ' . $sucursal->municipio, !empty($almacen->nombre) ? $almacen->nombre : '', $d->fecha_programada, array('data' => number_format($piezas, 2), 'style' => 'text-align: right;'), $d->origen, array('data' => ($d->estado > 0 && $d->estado < 4 ? anchor($this->folder . $this->clase . 'ordenes_salida_editar/' . $d->id, '<i class="icon-edit"></i>', array('class' => 'btn btn-small', 'title' => 'Editar')) : '<a class="btn btn-small" disabled><i class="icon-edit"></i></a>'), 'style' => 'text-align: right;')
            );
            if ($d->estado == 0)
                $this->table->add_row_class('muted');
            else
                $this->table->add_row_class('');
        }
        $data['table'] = $this->table->generate();

        $this->load->view('almacenes/salidas/proceso_ruta_lista', $data);
    }

}

?>
