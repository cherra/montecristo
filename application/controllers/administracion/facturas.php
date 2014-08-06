<?php
/**
 * Description of facturas
 *
 * @author cherra
 */
class Facturas extends CI_Controller {
    
    private $folder = 'administracion/';
    private $clase = 'facturas/';
    
    function __construct() {
        parent::__construct();
    }
    
    public function index( $offset = 0 ){
        $this->load->model('factura','f');
        $this->load->model('cliente','c');
        $this->load->model('preferencias/usuario','u');
        
        $this->config->load("pagination");
    	
        $data['titulo'] = 'Facturas <small>Lista</small>';
        $data['link_add'] = anchor($this->folder.$this->clase.'facturas_agregar','<i class="icon-plus icon-white"></i> Nuevo', array('class' => 'btn btn-inverse'));
    	$data['action'] = $this->folder.$this->clase.'index';
        
        // Filtro de busqueda (se almacenan en la sesión a través de un hook)
        $filtro = $this->session->userdata('filtro');
        if($filtro)
            $data['filtro'] = $filtro;
        
        $page_limit = $this->config->item("per_page");
    	$datos = $this->f->get_paged_list($page_limit, $offset, $filtro)->result();
    	
        // generar paginacion
    	$this->load->library('pagination');
    	$config['base_url'] = site_url($this->folder.$this->clase.'index');
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
    	$this->table->set_heading('Folio','Fecha','Cliente','Subtotal','IVA','Total', '', '','');
    	foreach ($datos as $d) {
            $cliente = $this->c->get_by_id($d->id_cliente)->row();
            $usuario = $this->u->get_by_id($d->id_usuario)->row();
            $importes = $this->f->get_importes($d->id);
    		$this->table->add_row(
                        $d->folio,
                        $d->fecha,
                        $cliente->nombre,
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
            redirect($this->folder.$this->clase.'index');
        }
        
        $this->load->model('factura','f');
        $respuesta = $this->f->cancelar($id);
        if($respuesta > 0){
            $this->session->set_flashdata('mensaje',array('texto' => 'Factura cancelado', 'tipo' => ''));
        }else{
            $this->session->set_flashdata('mensaje',array('texto' => 'Ocurrió un error al cancelar la factura', 'tipo' => 'alert-error'));
        }
        redirect($this->folder.$this->clase.'index');
    }
    
    // Genera el formato de factura para impresión
    private function facturas_render_template($id){
        $this->load->model('factura','f');
        $this->load->model('pedido', 'p');
        $this->load->model('cliente','c');
        $this->load->model('sucursal','s');
        $this->load->model('preferencias/usuario','u');
            
        $factura = $this->f->get_by_id($id)->row();
        $pedido = $this->p->get_by_factura($id)->row();
        $sucursal = $this->s->get_by_id($pedido->id_cliente_sucursal)->row();
        $cliente = $this->c->get_by_id($factura->id_cliente)->row();
        $usuario = $this->u->get_by_id($factura->id_usuario)->row();
        
        $this->load->library('tbs');
        $this->load->library('numero_letras');

        // Nombres de meses en español (config/sitio.php)
        $meses = $this->config->item('meses');

        // Se carga el template predefinido para las facturas (tabla Configuracion)
        $this->tbs->LoadTemplate($this->configuracion->get_valor('template_path').$this->configuracion->get_valor('template_facturas'));

        //Logotipo
        $logo = $this->configuracion->get_valor('img_path').$this->configuracion->get_valor('logotipo');
        $this->tbs->VarRef['logo'] = base_url($logo);
        
        // Se sustituyen los campos en el template
        $this->tbs->VarRef['folio'] = $factura->folio;
        $fecha = date_create($factura->fecha);
        $this->tbs->VarRef['fecha'] = date_format($fecha,'d/m/Y');
        /*$this->tbs->VarRef['dia'] = date_format($fecha,'d');
        $this->tbs->VarRef['mes'] = $meses[date_format($fecha,'n')-1];
        $this->tbs->VarRef['ano'] = date_format($fecha,'Y');
         */
        $this->tbs->VarRef['usuario'] = $usuario->nombre;
        
        $this->tbs->VarRef['cliente'] = $cliente->nombre;
        $this->tbs->VarRef['rfc'] = $cliente->rfc;
        $this->tbs->VarRef['domicilio'] = $cliente->calle.' '.$cliente->numero_exterior.' '.$cliente->numero_interior;
        $this->tbs->VarRef['colonia'] = $cliente->colonia;
        $this->tbs->VarRef['poblacion'] = $cliente->poblacion;
        $this->tbs->VarRef['municipio'] = $cliente->municipio;
        $this->tbs->VarRef['estado'] = $cliente->estado;
        $this->tbs->VarRef['cp'] = $cliente->cp;
        $this->tbs->VarRef['sucursal'] = $sucursal->numero.' '.$sucursal->nombre;
//        $this->tbs->VarRef['domicilio_sucursal'] = $sucursal->calle.' '.$sucursal->numero_exterior.' '.$sucursal->numero_interior;
//        $this->tbs->VarRef['colonia_sucursal'] = $sucursal->colonia;
//        $this->tbs->VarRef['poblacion_sucursal'] = $sucursal->poblacion;
//        $this->tbs->VarRef['municipio_sucursal'] = $sucursal->municipio;
//        $this->tbs->VarRef['estado_sucursal'] = $sucursal->estado;
//        $this->tbs->VarRef['cp_sucursal'] = $sucursal->cp;
        $this->tbs->VarRef['pedido'] = $pedido->id;
        
        $this->tbs->VarRef['num_proveedor'] = $cliente->num_proveedor;

        $conceptos = $this->f->get_conceptos($id)->result_array();
        foreach($conceptos as $key => $value){
//            $presentacion_cliente = $this->cp->get_presentacion($cliente->id, $presentaciones[$key]['id_producto_presentacion'])->row();
//            $presentacion = $this->pp->get_by_id($presentaciones[$key]['id_producto_presentacion'])->row();
            
            $conceptos[$key]['cantidad'] = number_format($conceptos[$key]['cantidad'],2,'.',',');
            $conceptos[$key]['precio'] = number_format($conceptos[$key]['precio'],2,'.',',');
            //$presentaciones[$key]['codigo'] = $presentacion_cliente->codigo ? $presentacion_cliente->codigo : $presentacion->codigo;
            $conceptos[$key]['importe'] = number_format($conceptos[$key]['cantidad'] * $conceptos[$key]['precio'],2,'.',',');
            //$presentaciones[$key]['nombre'] = $presentacion_cliente->producto;
            //$presentaciones[$key]['concepto'] = $conceptos[$key]['concepto'];
        }
        $this->tbs->MergeBlock('conceptos', $conceptos);
        $importes = $this->f->get_importes($id);
        $this->tbs->VarRef['subtotal'] = number_format($importes->subtotal,2,'.',',');
        $this->tbs->VarRef['iva'] = number_format($importes->iva,2,'.',',');
        //$total = $this->p->get_importe($pedido->id);
        $this->tbs->VarRef['total'] = number_format($importes->total,2,'.',',');
        $this->tbs->VarRef['cantidad_letra'] = $this->numero_letras->convertir($importes->total);
        //$this->tbs->VarRef['peso'] = number_format($this->p->get_peso($pedido->id),2,'.',',').'kg';
        //$this->tbs->VarRef['piezas'] = number_format($this->p->get_piezas($pedido->id),2,'.',',');
        // Render sin desplegar en navegador
        $this->tbs->Show(TBS_NOTHING);
        // Se regresa el render
        return $this->tbs->Source;
    }
    
    // Impresión de facturas
    public function facturas_documento( $id = null ){
        if(!empty($id)){
            $this->layout = "template_pdf";
            $this->load->model('pedido', 'p');
            $pedido = $this->p->get_by_id($id)->row();
            if( $this->session->flashdata('pdf') ){
            //if(true){
                if($pedido){
                    $data['contenido'] = $this->facturas_render_template($id);                    
                    $this->load->view('documento', $data);
                }else{
                    redirect($this->folder.$this->clase.'index');
                }
            }else{
                $this->session->set_flashdata('pdf', true);
                if($pedido){
                    if($pedido->estado == '0'){  // Se agrega una marca de agua al PDF
                        $this->session->set_flashdata('watermark', 'Cancelado');
                    }
                }
                redirect($this->folder.$this->clase.'facturas_documento/'.$id); // Se recarga el método para imprimirlo como PDF
            }
        }else{
            redirect($this->folder.$this->clase.'index');
        }
    }
    
}
?>
