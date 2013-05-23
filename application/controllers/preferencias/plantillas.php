<?php

/**
 * Description of plantillas
 *
 * @author cherra
 */
class Plantillas extends CI_Controller{
    
    function __construct() {
        parent::__construct();
    }
    
    
    /************************
     * Templates para ordenes de compra
     * 
     ************************/
    public function ordenes_compra(){
        $data['titulo'] = 'Ordenes de compra <small>Plantilla</small>';
        $data['action'] = site_url('preferencias/plantillas/ordenes_compra');
        $data['mensaje'] = '';
        
        if ($this->configuracion->get_valor('template_orden_compra')){
        
            /*if(!file_exists($path)){
                mkdir($path, 0777, true);
            }elseif(!is_writable($path)){
                chmod($path, 0777);
            }

            if(!file_exists($path.$file)){
                touch($path.$file);
            }elseif(!is_writable($path.$file)){
                chmod($path.$file, 0777);
            }*/

            //$this->load->helper('file');
            if( ($datos = $this->input->post()) ){
                if(strlen($datos['plantilla']) > 0){
                    $this->configuracion->update_by_key('template_orden_compra', array('data' => $datos['plantilla']));
                    //write_file($path.$file, $datos['plantilla']);
                    $data['mensaje'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Registro exitoso</div>';
                }
            }
            //$data['plantilla'] = read_file($path.$file);
            $data['plantilla'] = $this->configuracion->get_data('template_orden_compra');
            
        }else{
            $data['mensaje'] = '<div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Aviso!</strong> No hay configuración para esta plantilla (template_orden_compra).</div>';
        }
        $this->load->view('preferencias/plantillas', $data);
    }
}

?>
