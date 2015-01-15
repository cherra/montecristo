<?php

/**
 *
 * @author cherra
 */
class Ventas extends CI_Controller{
    
    public function index(){
        
        $this->load->model('llamada', 'll');
        $this->load->model('preferencias/usuario', 'u');
        $this->load->model('cliente', 'c');
        $this->load->model('sucursal', 's');
        $this->load->model('contacto', 'co');
        
        $llamadas = $this->ll->get_paged_list(20)->result();
        
        // generar tabla de llamadas
    	$this->load->library('table');
    	$this->table->set_empty('&nbsp;');
    	$tmpl = array ( 'table_open' => '<table class="' . $this->config->item('tabla_css') . '" >' );
    	$this->table->set_template($tmpl);
    	$this->table->set_heading('Fecha', 'Hora', 'Usuario', 'Cliente', 'Tienda', 'Contacto', 'Comentarios', '', '');
    	foreach ($llamadas as $d) {
            $fecha = new DateTime($d->fecha);
            $usuario = $this->u->get_by_id($d->id_usuario)->row();
            $contacto = $this->co->get_by_id($d->id_cliente_sucursal_contacto)->row();
            $sucursal = $this->s->get_by_id($contacto->id_cliente_sucursal)->row();
            $cliente = $this->c->get_by_id($sucursal->id_cliente)->row();
            $this->table->add_row(
                    date_format($fecha,'d/m/Y'),
                    date_format($fecha,'H:i'),
                    $usuario->nombre,
                    $cliente->nombre,
                    $sucursal->nombre,
                    $contacto->nombre,
                    $d->comentarios
            );
    	}
        $data['columna2_title'] = "Todas las llamadas";
    	$data['columna2_data'] = $this->table->generate();
        
        $misllamadas = $this->ll->get_by_id_usuario($this->session->userdata('userid'), 20)->result();
        
        // generar tabla de llamadas
    	$this->load->library('table');
    	$this->table->set_empty('&nbsp;');
    	$tmpl = array ( 'table_open' => '<table class="' . $this->config->item('tabla_css') . '" >' );
    	$this->table->set_template($tmpl);
    	$this->table->set_heading('Fecha', 'Hora',  'Cliente', 'Tienda', 'Contacto', 'Comentarios');
    	foreach ($llamadas as $d) {
            $fecha = new DateTime($d->fecha);
            $usuario = $this->u->get_by_id($d->id_usuario)->row();
            $contacto = $this->co->get_by_id($d->id_cliente_sucursal_contacto)->row();
            $sucursal = $this->s->get_by_id($contacto->id_cliente_sucursal)->row();
            $cliente = $this->c->get_by_id($sucursal->id_cliente)->row();
            $this->table->add_row(
                    date_format($fecha,'d/m/Y'),
                    date_format($fecha,'H:i'),
                    $cliente->nombre,
                    $sucursal->nombre,
                    $contacto->nombre,
                    $d->comentarios,
                    array('data' => anchor('ventas/clientes/llamadas_agregar/' . $cliente->id . '/'. $sucursal->id . '/'. $contacto->id, '<i class="icon-phone"></i>', array('class' => 'btn btn-small', 'title' => 'Llamada')), 'style' => 'text-align: right;'),
                    $d->marca == '1' ? array('data' => anchor('ventas/clientes/llamadas_desmarcar/' . $d->id, '<i class="icon-check"></i>', array('class' => 'btn btn-small', 'title' => 'Desmarcar')), 'style' => 'text-align: right;') : ''
            );
            if($d->marca == '1')
                $this->table->add_row_class ('info');
            else
                $this->table->add_row_class ('');
    	}
        $data['columna1_title'] = "Mis llamadas";
    	$data['columna1_data'] = $this->table->generate();
        
        $this->load->view('landpage', $data);
    }
}

?>
