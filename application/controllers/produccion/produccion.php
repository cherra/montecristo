<?php

/**
 * Description of produccion
 *
 * @author cherra
 */
class Produccion extends CI_Controller {
    
    private $folder = 'produccion/';
    private $clase = 'produccion/';
    
    function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $this->load->view('vacio');
    }
}
?>
