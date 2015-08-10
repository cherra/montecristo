<?php
/**
 * Description of compras
 *
 * @author cherra
 */
class Compras extends CI_Controller {
    
    private $folder = 'compras/';
    private $clase = 'compras/';
    private $clase_orden_compras = 'ordenes_compra/';
    private $iconos_estado = array(
        0 => 'icon-remove',
        1 => 'icon-time',
        2 => 'icon-thumbs-up',
        3 => 'icon-share',
        4 => 'icon-check',
        5 => 'icon-shopping-cart',
        6 => 'icon-shopping-cart'
    );
    
    
    function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $this->load->view('vacio');
    }

    /**
     * VIEW
     * mostrar lista de compras confirmadas (estado == 4)
     * @param  integer $offset
     * @return view
     */
    public function confirmadas($offset = 0) {
    	$this->load->model('compra', 'c');
        $this->load->model('proveedor', 'p');
        $this->load->model('preferencias/usuario','u');

        $this->config->load("pagination");

        $data['titulo'] = 'Compras <small>confirmadas</small>';
        //$data['link_add'] = anchor($this->folder.$this->clase_orden_compras.'pedidos_agregar','<i class="icon-plus icon-white"></i> Nuevo', array('class' => 'btn btn-inverse'));
        $data['link_back'] = anchor('javascript:history.back(-1);', '<i class="icon-arrow-left"></i> Regresar', array('class' => 'btn'));
        $data['action'] = $this->folder . $this->clase . 'confirmadas';

        // Filtro de busqueda (se almacenan en la sesión a través de un hook)
        $filtro = $this->session->userdata('filtro');
        if ($filtro)
            $data['filtro'] = $filtro;

        // Se marca(n) como autorizadas las ordenes de compra
        if ($this->input->post()) {
            $compras = $this->input->post('compras');
            $fecha = $this->input->post('fecha');
            $fecha_vencimiento = $this->input->post('fecha_vencimiento');
            foreach ($compras as $c) {
                $this->c->update($c, array('estado' => '5', 'fecha' => $fecha, 'fecha_vencimiento' => $fecha_vencimiento));
            }
        }

        $page_limit = $this->config->item("per_page");
        $datos = $this->c->get_paged_list($page_limit, $offset, $filtro, array('4'))->result();
        // generar paginacion
        $this->load->library('pagination');
        $config['base_url'] = site_url($this->folder . $this->clase_orden_compras . 'ordenes_compra_autorizar');
        $config['total_rows'] = $this->c->count_all($filtro, array('1'));
        $config['per_page'] = $page_limit;
        $config['uri_segment'] = 4;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        // generar tabla
    	$this->load->library('table');
    	$this->table->set_empty('&nbsp;');
    	$tmpl = array ( 'table_open' => '<table class="' . $this->config->item('tabla_css') . '" >' );
    	$this->table->set_template($tmpl);
    	$this->table->set_heading('E','Número','Fecha','Proveedor','Municipio','Estado','Teléfono','Contacto','Usuario','Total', '', '');
    	foreach ($datos as $d) {
            $proveedor = $this->p->get_by_id($d->id_proveedor)->row();
            $usuario = $this->u->get_by_id($d->id_usuario)->row();
            $importe = $this->c->get_importe($d->id);
    		$this->table->add_row(
                        $d->estado == '4' ? '<input type="checkbox" name="compras[]" value="'.$d->id.'"/>' : '<i class="'.$this->iconos_estado[$d->estado].'"></i>',
                        $d->id,
                        $d->fecha_orden_compra,
                        $proveedor->nombre,
                        $proveedor->municipio,
                        $proveedor->estado,
                        $proveedor->telefono,
                        $proveedor->contacto,
                        $usuario->nombre,
                        array('data' => number_format($importe,2), 'style' => 'text-align: right;'),
                        array('data' => anchor_popup($this->folder.$this->clase_orden_compras.'ordenes_compra_documento/' . $d->id, '<i class="icon-print"></i>', array('class' => 'btn btn-small', 'title' => 'Imprimir')), 'style' => 'text-align: right;'),
                        array('data' => ($d->estado > 0 && $d->estado < 5 ? anchor($this->folder.$this->clase_orden_compras.'ordenes_compra_editar/' . $d->id, '<i class="icon-edit"></i>', array('class' => 'btn btn-small', 'title' => 'Editar')) :  '<a class="btn btn-small" disabled><i class="icon-edit"></i></a>'), 'style' => 'text-align: right;'),
                        array('data' => ($d->estado > 0 && $d->estado < 5 ? anchor($this->folder.$this->clase_orden_compras.'ordenes_compra_cancelar/' . $d->id, '<i class="icon-ban-circle"></i>', array('class' => 'btn btn-small cancelar', 'title' => 'Cancelar')) :  '<a class="btn btn-small" disabled><i class="icon-ban-circle"></i></a>'), 'style' => 'text-align: right;')
    		);
                if($d->estado == 0)
                    $this->table->add_row_class('muted');
                else
                    $this->table->add_row_class('');
    	}
    	$data['table'] = $this->table->generate();
    	$this->load->view('compras/lista_aceptar', $data);
    }

    /**
     * VIEW
     * mostrar lista de compras por pagar (estado == 5)
     * @param  integer $offset
     * @return view
     */
    public function por_pagar($offset = 0) {
        $this->load->model('compra', 'c');
        $this->load->model('proveedor', 'p');
        $this->load->model('preferencias/usuario','u');

        $this->config->load("pagination");

        $data['titulo'] = 'Compras <small>confirmadas</small>';
        //$data['link_add'] = anchor($this->folder.$this->clase_orden_compras.'pedidos_agregar','<i class="icon-plus icon-white"></i> Nuevo', array('class' => 'btn btn-inverse'));
        $data['link_back'] = anchor('javascript:history.back(-1);', '<i class="icon-arrow-left"></i> Regresar', array('class' => 'btn'));
        $data['action'] = $this->folder . $this->clase . 'guardar_factura';

        // Filtro de busqueda (se almacenan en la sesión a través de un hook)
        $filtro = $this->session->userdata('filtro');
        if ($filtro)
            $data['filtro'] = $filtro;

        $page_limit = $this->config->item("per_page");
        $datos = $this->c->get_paged_list($page_limit, $offset, $filtro, array('5'))->result();
        // generar paginacion
        $this->load->library('pagination');
        $config['base_url'] = site_url($this->folder . $this->clase_orden_compras . 'ordenes_compra_autorizar');
        $config['total_rows'] = $this->c->count_all($filtro, array('1'));
        $config['per_page'] = $page_limit;
        $config['uri_segment'] = 4;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        // generar tabla
        $this->load->library('table');
        $this->table->set_empty('&nbsp;');
        $tmpl = array ( 'table_open' => '<table id="tabla_compras" class="' . $this->config->item('tabla_css') . '" >' );
        $this->table->set_template($tmpl);
        $this->table->set_heading('E','Número','Fecha','Proveedor','Municipio','Estado','Teléfono','Contacto','Usuario','Total', '', '');
        foreach ($datos as $d) {
            $proveedor = $this->p->get_by_id($d->id_proveedor)->row();
            $usuario = $this->u->get_by_id($d->id_usuario)->row();
            $importe = $this->c->get_importe($d->id);
            $this->table->add_row(
                '<i class="'.$this->iconos_estado[$d->estado].'"></i>',
                $d->id,
                $d->fecha_orden_compra,
                $proveedor->nombre,
                $proveedor->municipio,
                $proveedor->estado,
                $proveedor->telefono,
                $proveedor->contacto,
                $usuario->nombre,
                array('data' => number_format($importe,2), 'style' => 'text-align: right;'),
                array('data' => anchor_popup($this->folder.$this->clase_orden_compras.'ordenes_compra_documento/' . $d->id, '<i class="icon-print"></i>', array('class' => 'btn btn-small', 'title' => 'Imprimir')), 'style' => 'text-align: right;'),
                '<a href="#modal" class="btn btn-small" data-toggle="modal" title="Guardar referencia"><i class="icon-edit"></i></a>'
            );
            if($d->estado == 0)
                $this->table->add_row_class('muted');
            else
                $this->table->add_row_class('');
        }
        $data['table'] = $this->table->generate();
        $this->load->view('compras/lista_pagar', $data);
    }

    /**
     * VIEW
     * mostrar lista de compras pagadas (estado == 6)
     * @param  integer $offset
     * @return view
     */
    public function pagadas($offset = 0) {
        $this->load->model('compra', 'c');
        $this->load->model('proveedor', 'p');
        $this->load->model('preferencias/usuario','u');

        $this->config->load("pagination");

        $data['titulo'] = 'Compras <small>pagadas</small>';
        //$data['link_add'] = anchor($this->folder.$this->clase_orden_compras.'pedidos_agregar','<i class="icon-plus icon-white"></i> Nuevo', array('class' => 'btn btn-inverse'));
        $data['link_back'] = anchor('javascript:history.back(-1);', '<i class="icon-arrow-left"></i> Regresar', array('class' => 'btn'));
        $data['action'] = $this->folder . $this->clase . 'guardar_factura';

        // Filtro de busqueda (se almacenan en la sesión a través de un hook)
        $filtro = $this->session->userdata('filtro');
        if ($filtro)
            $data['filtro'] = $filtro;

        $page_limit = $this->config->item("per_page");
        $datos = $this->c->get_paged_list($page_limit, $offset, $filtro, array('6'))->result();

        // generar paginacion
        $this->load->library('pagination');
        $config['base_url'] = site_url($this->folder . $this->clase_orden_compras . 'ordenes_compra_autorizar');
        $config['total_rows'] = $this->c->count_all($filtro, array('1'));
        $config['per_page'] = $page_limit;
        $config['uri_segment'] = 4;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        // generar tabla
        $this->load->library('table');
        $this->table->set_empty('&nbsp;');
        $tmpl = array ( 'table_open' => '<table id="tabla_compras" class="' . $this->config->item('tabla_css') . '" >' );
        $this->table->set_template($tmpl);
        $this->table->set_heading('E','Número','Fecha','Proveedor','Teléfono','Usuario','Total', 'No. referencia', 'Pago', 'Fecha Factura', '', '');
        foreach ($datos as $d) {
            $proveedor = $this->p->get_by_id($d->id_proveedor)->row();
            $usuario = $this->u->get_by_id($d->id_usuario)->row();
            $importe = $this->c->get_importe($d->id);
            
            // si existe foto_factura mostrar el boton
            $link_file = '';
            if ($d->foto_factura != null) 
                $link_file = array('data' => anchor_popup($this->folder.$this->clase.'ver_foto/'.$d->id, '<i class="icon-file"></i>', array('class' => 'btn btn-small', 'title' => 'Ver factura')), 'style' => 'text-align: right;');
            
            $this->table->add_row(
                '<i class="'.$this->iconos_estado[$d->estado].'"></i>',
                $d->id,
                explode(" ", $d->fecha_orden_compra)[0],
                $proveedor->nombre,
                $proveedor->telefono,
                $usuario->nombre,
                array('data' => number_format($importe,2), 'style' => 'text-align: right;'),
                $d->numero,
                $d->tipo_pago,
                $d->fecha_factura,
                array('data' => anchor_popup($this->folder.$this->clase_orden_compras.'ordenes_compra_documento/' . $d->id, '<i class="icon-print"></i>', array('class' => 'btn btn-small', 'title' => 'Imprimir')), 'style' => 'text-align: right;'),
                $link_file
            );
            
            if($d->estado == 0)
                $this->table->add_row_class('muted');
            else
                $this->table->add_row_class('');
        }
        $data['table'] = $this->table->generate();
        $this->load->view('compras/lista_pagar', $data);
    }

    public function guardar_factura() {
        $id_compra = $this->input->post('id_compra');
        $referencia = $this->input->post('referencia');
        $tipo_pago = $this->input->post('tipo_pago');
        $fecha = $this->input->post('fecha');
        $foto_factura = null;
        if ($_FILES['foto_factura']['size'] > 0) {
           $foto_factura = file_get_contents($_FILES['foto_factura']['tmp_name']);
        }
        $data = array(
            'estado' => 6,
            'numero' => $referencia,
            'tipo_pago' => $tipo_pago,
            'fecha_factura' => $fecha,
            'foto_factura' => $foto_factura
        );
        if ($foto_factura == null) {
            unset($data['foto_factura']);
        }
        $this->load->model('compra', 'c');
        $this->c->update($id_compra, $data);
        redirect(site_url('compras/compras/por_pagar'));
    }

    public function get_factura() {
        $this->load->model('compra', 'c');
        $id = $this->input->post('id');
        $compra = $this->c->get_by_id($id)->row();
        $foto = false;
        if ($compra->foto_factura != null) $foto = true;
        $data = array(
            'referencia' => $compra->numero,
            'tipo_pago' => $compra->tipo_pago,
            'fecha' => $compra->fecha_factura,
            'foto_factura' => $foto
        );
        echo json_encode($data);
    }

    public function ver_foto($id) {
        $this->load->model('compra', 'c');
        $compra = $this->c->get_by_id($id)->row();
        $foto = false;
        if ($compra->foto_factura != null) $foto = true;
        if ($foto) {
            echo '<img style="width: 100%; height: 100%;" src="data:image/jpeg;base64,'.base64_encode($compra->foto_factura).'"/>';
            die();
        }
    }
}
?>
