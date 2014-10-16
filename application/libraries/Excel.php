<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of excel
 *
 * @author cherra
 */

require_once 'excel/PHPExcel.php';
//require_once 'excel/PHPExcel/IOFactory.php';

class Excel extends PHPExcel {
    
    public function __construct() {
        parent::__construct();
    }
}

?>
