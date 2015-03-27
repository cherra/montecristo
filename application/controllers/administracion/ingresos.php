<?php

/**
 * Description of ingresos
 *
 * @author cherra
 */
class Ingresos extends CI_Controller {
    
    private $folder = 'administracion/';
    private $clase = 'ingresos/';
    
    function __construct() {
        parent::__construct();
    }
    
    public function facturas( $offset = 0 ){
        $this->load->model('factura','f');
        $this->load->model('cliente','c');
        $this->load->model('pedido','p');
        $this->load->model('preferencias/usuario','u');
        
        $this->config->load("pagination");
    	
        $data['titulo'] = 'Facturas <small>Lista</small>';
        //$data['link_add'] = anchor($this->folder.$this->clase.'facturas_agregar','<i class="icon-plus icon-white"></i> Nuevo', array('class' => 'btn btn-inverse'));
    	$data['action'] = $this->folder.$this->clase.'facturas';
        
        // Filtro de busqueda (se almacenan en la sesión a través de un hook)
        $filtro = $this->session->userdata('filtro');
        if($filtro)
            $data['filtro'] = $filtro;
        
        $page_limit = $this->config->item("per_page");
    	$datos = $this->f->get_paged_list($page_limit, $offset, $filtro)->result();
    	
        // generar paginacion
    	$this->load->library('pagination');
    	$config['base_url'] = site_url($this->folder.$this->clase.'facturas');
    	$config['total_rows'] = $this->f->count_all($filtro);
    	$config['per_page'] = $page_limit;
    	$config['uri_segment'] = 4;
    	$this->pagination->initialize($config);
    	$data['pagination'] = $this->pagination->create_links();
    	
    	// generar tabla
    	$this->load->library('table');
    	$this->table->set_empty('&nbsp;');
    	$tmpl = array ( 'table_open' => '<table class="' . $this->config->item('tabla_css') . '" >' );
    	$this->table->set_template($tmpl);
    	$this->table->set_heading('Folio interno','Pedido', 'Fecha','Cliente','Piezas', 'Subtotal','IVA','Total', '', '','');
    	foreach ($datos as $d) {
            $cliente = $this->c->get_by_id($d->id_cliente)->row();
            $pedido = $this->p->get_by_factura($d->id)->row();
            if(!empty($pedido)){
                $piezas = $this->p->get_piezas($pedido->id);
            }
            $usuario = $this->u->get_by_id($d->id_usuario)->row();
            $importes = $this->f->get_importes($d->id);
    		$this->table->add_row(
                        $d->id,
                        !empty($pedido) ? $pedido->id : '',
                        $d->fecha,
                        $cliente->nombre,
                        $piezas,
                        array('data' => number_format($importes->subtotal,2), 'style' => 'text-align: right;'),
                        array('data' => number_format($importes->iva,2), 'style' => 'text-align: right;'),
                        array('data' => number_format($importes->total,2), 'style' => 'text-align: right;'),
                        array('data' => anchor_popup($this->folder.$this->clase.'facturas_documento/' . $d->id, '<i class="icon-print"></i>', array('class' => 'btn btn-small', 'title' => 'Imprimir')), 'style' => 'text-align: right;'),
                        array('data' => ($d->estado > 0 && $d->estado < 5 ? anchor($this->folder.$this->clase.'facturas_cancelar/' . $d->id, '<i class="icon-ban-circle"></i>', array('class' => 'btn btn-small cancelar', 'title' => 'Cancelar')) :  '<a class="btn btn-small" disabled><i class="icon-ban-circle"></i></a>'), 'style' => 'text-align: right;'),
                        array('data' => ($d->estado >= 1 ? anchor($this->folder.$this->clase.'facturas_exportar_buzon_fiscal/' . $d->id, '<i class="icon-share"></i>', array('class' => 'btn btn-small', 'title' => 'Exportar a buzón fiscal')) :  '<a class="btn btn-small" disabled><i class="icon-share"></i></a>'), 'style' => 'text-align: right;')
    		);
                if($d->estado == 0)
                    $this->table->add_row_class('muted');
                else
                    $this->table->add_row_class('');
    	}
    	$data['table'] = $this->table->generate();
    	
    	$this->load->view('lista', $data);
    }
    
    /*
     * 
     * Cancelar facturas
     */
    public function facturas_cancelar( $id = NULL ){
        if(empty($id)){
            redirect($this->folder.$this->clase.'facturas');
        }
        
        $this->load->model('factura','f');
        $this->load->model('pedido','p');
        $this->db->trans_start();
        $respuesta = $this->f->cancelar($id);
        if($respuesta > 0){
            $pedido = $this->p->get_by_factura($id)->row();
            $respuesta = $this->p->update($pedido->id, array('id_factura' => 0));
            if($respuesta > 0)
                $this->session->set_flashdata('mensaje',array('texto' => 'Factura cancelada', 'tipo' => ''));
            else
                $this->session->set_flashdata('mensaje',array('texto' => 'Ocurrió un error al cancelar la factura', 'tipo' => 'alert-error'));
        }else{
            $this->session->set_flashdata('mensaje',array('texto' => 'Ocurrió un error al cancelar la factura', 'tipo' => 'alert-error'));
        }
        $this->db->trans_complete();
        redirect($this->folder.$this->clase.'facturas');
    }
}
?>
