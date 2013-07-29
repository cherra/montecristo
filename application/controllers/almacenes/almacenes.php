<?php

/**
 * Description of almacen
 *
 * @author cherra
 */
class Almacenes extends CI_Controller {
    
    private $folder = 'almacen/';
    private $clase = 'almacen/';
    
    function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $this->load->view('vacio');
    }
}
?>
