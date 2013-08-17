<?php
/**
 * Description of administracion
 *
 * @author cherra
 */
class Administracion extends CI_Controller {
    
    private $folder = 'administracion/';
    private $clase = 'administracion/';
    
    function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $this->load->view('vacio');
    }
}
?>
