<?php

/**
 * Description of plantillas
 *
 * @author cherra
 */
class Plantillas extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        $this->load->helper('file');
    }
    
    private function leer_plantilla($path, $file){
        if(!file_exists($path)){
                mkdir($path, 0777, true);
            }elseif(!is_writable($path)){
                chmod($path, 0777);
            }

            if(!file_exists($path.$file)){
                touch($path.$file);
            }elseif(!is_writable($path.$file)){
                chmod($path.$file, 0777);
            }
            return read_file($path.$file);
    }
    
    /************************
     * Templates para pedidos
     * 
     ************************/
    public function pedidos(){
        $data['titulo'] = 'Pedidos <small>Plantilla</small>';
        $data['action'] = site_url('preferencias/plantillas/pedidos');
        $data['mensaje'] = '';
        
        if (($path = $this->configuracion->get_valor('template_path')) && (($file = $this->configuracion->get_valor('template_pedidos')))){
            if( ($datos = $this->input->post()) ){
                if(strlen($datos['plantilla']) > 0){
                    write_file($path.$file, $datos['plantilla']);
                    $data['mensaje'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Registro exitoso</div>';
                }
            }
            $data['plantilla'] = $this->leer_plantilla($path,$file);
            
        }else{
            $data['mensaje'] = '<div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Aviso!</strong> No hay configuración para la plantilla del pedido.</div>';
        }
        $this->load->view('preferencias/plantillas', $data);
    }
    
    /************************
     * Templates para ordenes de compra
     * 
     ************************/
    public function ordenes_compra(){
        $data['titulo'] = 'Ordenes de compra <small>Plantilla</small>';
        $data['action'] = site_url('preferencias/plantillas/ordenes_compra');
        $data['mensaje'] = '';
        
        if (($path = $this->configuracion->get_valor('template_path')) && (($file = $this->configuracion->get_valor('template_ordenes_compra')))){
            if( ($datos = $this->input->post()) ){
                if(strlen($datos['plantilla']) > 0){
                    write_file($path.$file, $datos['plantilla']);
                    $data['mensaje'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Registro exitoso</div>';
                }
            }
            $data['plantilla'] = $this->leer_plantilla($path,$file);
            
        }else{
            $data['mensaje'] = '<div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Aviso!</strong> No hay configuración para la plantilla de ordenes de compra.</div>';
        }
        $this->load->view('preferencias/plantillas', $data);
    }
    
    /************************
     * Templates para concentrado de pedidos
     * 
     ************************/
    public function concentrado_pedidos_cliente(){
        $data['titulo'] = 'Concentrado de pedidos por cliente <small>Plantilla</small>';
        $data['action'] = site_url('preferencias/plantillas/concentrado_pedidos_cliente');
        $data['mensaje'] = '';
        
        if (($path = $this->configuracion->get_valor('template_path')) && (($file = $this->configuracion->get_valor('template_concentrado_pedidos_cliente')))){
            if( ($datos = $this->input->post()) ){
                if(strlen($datos['plantilla']) > 0){
                    write_file($path.$file, $datos['plantilla']);
                    $data['mensaje'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Registro exitoso</div>';
                }
            }
            $data['plantilla'] = $this->leer_plantilla($path,$file);
            
        }else{
            $data['mensaje'] = '<div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Aviso!</strong> No hay configuración para la plantilla de concentrado de pedidos.</div>';
        }
        $this->load->view('preferencias/plantillas', $data);
    }
    
    /************************
     * Templates para ordenes de compra
     * 
     ************************/
    public function buzon_fiscal(){
        $data['titulo'] = 'Remisión para buzón fiscal <small>Plantilla</small>';
        $data['action'] = site_url('preferencias/plantillas/buzon_fiscal');
        $data['mensaje'] = '';
        $data['formato'] = 'txt';
        
        if (($path = $this->configuracion->get_valor('template_path')) && (($file = $this->configuracion->get_valor('template_buzon_fiscal')))){
            if( ($datos = $this->input->post()) ){
                if(strlen($datos['plantilla']) > 0){
                    write_file($path.$file, $datos['plantilla']);
                    $data['mensaje'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Registro exitoso</div>';
                }
            }
            $data['plantilla'] = $this->leer_plantilla($path,$file);
            
        }else{
            $data['mensaje'] = '<div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Aviso!</strong> No hay configuración para la plantilla de buzón fiscal.</div>';
        }
        $this->load->view('preferencias/plantillas', $data);
    }
}

?>
