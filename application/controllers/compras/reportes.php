<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of reportes
 *
 * @author cherra
 */
class Reportes extends CI_Controller {
    
    private $folder = 'ventas/';
    private $clase = 'reportes/';
    private $template = '';
    
    function __construct() {
        parent::__construct();
        
        $this->template = $this->load->file(APPPATH . 'views/templates/template_pdf.php', true);
        
        ini_set('memory_limit', '-1');
    }
    
    
    
    //Ventas por cliente
    public function ordenes_compra_proveedor(){
        $data['reporte'] = '';
        if( ($post = $this->input->post()) ){
            
            $data['desde'] = $post['desde'];
            $data['hasta'] = $post['hasta'];
            $data['filtro'] = $post['filtro'];
            
            $this->load->model('proveedor','p');
            $this->load->model('compra','c');
            $this->load->model('preferencias/usuario','u');
            
            $proveedores = $this->p->get_paged_list(NULL, 0, $post['filtro'])->result();
            
            // generar tabla
            $this->load->library('table');
            $this->table->set_empty('&nbsp;');
            
            $tmpl = array ( 'table_open' => '<table class="table table-condensed" >' );
            $this->table->set_template($tmpl);
            $this->table->set_heading('Folio', 'Fecha', 'Usuario', 'Piezas', 'Importe');
            
            $total_piezas = 0;
            $total_importe = 0;
            $clase = '';
            foreach ($proveedores as $p){
                $compras = $this->c->get_by_proveedor($p->id, $post['desde'], $post['hasta'].' 23:59:59')->result();
                
                //die($this->db->last_query());
                if(!empty($compras)){
                    $clase = '';
                    // Fila con el nombre del cliente
                    $this->table->add_row(
                        array('data' => $p->nombre, 'class' => $clase, 'colspan' => '6')
                    );
                    $this->table->add_row_class($clase);

                    $total_piezas_proveedor = 0;
                    $total_importe_proveedor = 0;
                    // Filas con los pedidos del cliente
                    foreach($compras as $c){
                        if($c->estado > 0){
                            $fecha = date_create($c->fecha_orden_compra);
                            $usuario = $this->u->get_by_id($c->id_usuario)->row();
                            $piezas = $this->c->get_piezas($p->id);
                            $importe = $this->c->get_importe($p->id);
                            $total_piezas_proveedor += $piezas;
                            $total_importe_proveedor += $importe;
                            $this->table->add_row(
                                array('data' => $c->id,'class' => $clase),
                                array('data' => date_format($fecha,'d/m/Y h:i'),'class' => $clase),
                                array('data' => $usuario->nombre,'class' => $clase),
                                array('data' => number_format($piezas,0),'class' => $clase, 'style' => 'text-align: right'),
                                array('data' => number_format($importe,2),'class' => $clase, 'style' => 'text-align: right')
                            );
                            $this->table->add_row_class($clase);
                        }
                    }
                    $clase = 'resaltado';
                    // Totales por cliente
                    $this->table->add_row(
                        array('data' => 'TOTAL','class' => $clase, 'colspan' => '3'),
                        array('data' => number_format($total_piezas_proveedor,0),'class' => $clase, 'style' => 'text-align: right'),
                        array('data' => number_format($total_importe_proveedor,2),'class' => $clase, 'style' => 'text-align: right')
                    );
                    $this->table->add_row_class($clase);
                    
                    $this->table->add_row(
                        array('data' => '','class' => $clase, 'colspan' => '6')
                    );
                    $this->table->add_row_class($clase);
            
                    $total_piezas += $total_piezas_proveedor;
                    $total_importe += $total_importe_proveedor;
                }
            }
            // Totales
            $this->table->add_row(
                array('data' => '','class' => $clase, 'colspan' => '5')
            );
            $this->table->add_row_class($clase);
            
            $this->table->add_row(
                array('data' => 'TOTAL','class' => $clase, 'colspan' => '3'),
                array('data' => number_format($total_piezas,0),'class' => $clase, 'style' => 'text-align: right'),
                array('data' => number_format($total_importe,2),'class' => $clase, 'style' => 'text-align: right')
            );
            $this->table->add_row_class($clase);
                        
            $tabla = $this->table->generate();
            
            //$tabla.= '</tbody></table>';
            $this->load->library('tbs');
            $this->load->library('pdf');
            
            // Se obtiene la plantilla (2° parametro se pone false para evitar que haga conversión de caractéres con htmlspecialchars() )
            $this->tbs->LoadTemplate($this->configuracion->get_valor('template_path').$this->configuracion->get_valor('template_reportes'), false);

            // Se sustituyen los campos en el template
            $this->tbs->VarRef['titulo'] = 'Compras por proveedor';
            date_default_timezone_set('America/Mexico_City'); // Zona horaria
            $this->tbs->VarRef['fecha'] = date('d/m/Y H:i:s');
            $desde = date_create($post['desde']);
            $hasta = date_create($post['hasta']);
            $this->tbs->VarRef['subtitulo'] = 'Del '.date_format($desde, 'd/m/Y').' al '.date_format($hasta, 'd/m/Y');
            $this->tbs->VarRef['contenido'] = $tabla;
            
            $this->tbs->Show(TBS_NOTHING);
            
            // Se regresa el render
            $output = $this->tbs->Source;
            
            $view = str_replace("{contenido_vista}", $output, $this->template);
//            
//            // PDF
            $this->pdf->pagenumSuffix = '/';
            $this->pdf->SetHeader('{PAGENO}{nbpg}');
            $pdf = $this->pdf->render($view);
//            $pdf = $view;
            
            $fp = fopen($this->configuracion->get_valor('asset_path').$this->configuracion->get_valor('tmp_path').'compras_proveedor.pdf','w');
            fwrite($fp, $pdf);
            fclose($fp);
            $data['reporte'] = 'compras_proveedor.pdf';
        }
        $data['titulo'] = 'Reporte <small>Compras por proveedor</small>';
        $this->load->view('reporte', $data);
    }
    
    //Ventas por usuario
    public function ordenes_compra_producto(){
        $data['reporte'] = '';
        if( ($post = $this->input->post()) ){
            
            $data['desde'] = $post['desde'];
            $data['hasta'] = $post['hasta'];
            $data['filtro'] = $post['filtro'];
            
            $this->load->model('compra','c');
            $this->load->model('preferencias/usuario','u');
            $this->load->model('producto','pr');
            $this->load->model('producto_presentacion','pp');
            
            $productos = $this->pr->get_paged_list(NULL, 0, $post['filtro'])->result();
            
            // generar tabla
            $this->load->library('table');
            $this->table->set_empty('&nbsp;');
            
            $tmpl = array ( 'table_open' => '<table class="table table-condensed" >' );
            $this->table->set_template($tmpl);
            $this->table->set_heading('Código', 'Producto', 'Piezas', 'Importe');
            
            $total_piezas = 0;
            $total_importe = 0;
            foreach ($productos as $p){
                $totales = $this->c->get_total_by_producto($p->id, $post['desde'], $post['hasta'].' 23:59:59')->row();
                
                //die($this->db->last_query());
                if(!empty($totales)){
                    $clase = '';
                    
                    $piezas = $totales->cantidad;
                    $importe = $totales->importe;
                    $total_piezas += $piezas;
                    $total_importe += $importe;
                    $this->table->add_row(
                        array('data' => $totales->codigo,'class' => $clase),
                        array('data' => $totales->nombre,'class' => $clase),
                        array('data' => number_format($totales->cantidad,0),'class' => $clase, 'style' => 'text-align: right'),
                        array('data' => number_format($totales->importe,2),'class' => $clase, 'style' => 'text-align: right')
                    );
                    $this->table->add_row_class($clase);
                }
            }
            
            $clase = 'resaltado';
            $this->table->add_row(
                array('data' => 'TOTAL','class' => $clase, 'colspan' => '2'),
                array('data' => number_format($total_piezas,0),'class' => $clase, 'style' => 'text-align: right'),
                array('data' => number_format($total_importe,2),'class' => $clase, 'style' => 'text-align: right')
            );
            $this->table->add_row_class($clase);
                        
            $tabla = $this->table->generate();
            
            //$tabla.= '</tbody></table>';
            $this->load->library('tbs');
            $this->load->library('pdf');
            
            // Se obtiene la plantilla (2° parametro se pone false para evitar que haga conversión de caractéres con htmlspecialchars() )
            $this->tbs->LoadTemplate($this->configuracion->get_valor('template_path').$this->configuracion->get_valor('template_reportes'), false);

            // Se sustituyen los campos en el template
            $this->tbs->VarRef['titulo'] = 'Productos comprados';
            date_default_timezone_set('America/Mexico_City'); // Zona horaria
            $this->tbs->VarRef['fecha'] = date('d/m/Y H:i:s');
            $desde = date_create($post['desde']);
            $hasta = date_create($post['hasta']);
            $this->tbs->VarRef['subtitulo'] = 'Del '.date_format($desde, 'd/m/Y').' al '.date_format($hasta, 'd/m/Y');
            $this->tbs->VarRef['contenido'] = $tabla;
            
            $this->tbs->Show(TBS_NOTHING);
            
            // Se regresa el render
            $output = $this->tbs->Source;
            
            $view = str_replace("{contenido_vista}", $output, $this->template);
//            
//            // PDF
            $this->pdf->pagenumSuffix = '/';
            $this->pdf->SetHeader('{PAGENO}{nbpg}');
            $pdf = $this->pdf->render($view);
//            $pdf = $view;
            
            $fp = fopen($this->configuracion->get_valor('asset_path').$this->configuracion->get_valor('tmp_path').'productos_comprados.pdf','w');
            fwrite($fp, $pdf);
            fclose($fp);
            $data['reporte'] = 'productos_comprados.pdf';
        }
        $data['titulo'] = 'Reporte <small>Total por producto comprado</small>';
        $this->load->view('reporte', $data);
    }
    
    //Ventas por usuario
    public function pedidos_usuario(){
        $data['reporte'] = '';
        if( ($post = $this->input->post()) ){
            
            $data['desde'] = $post['desde'];
            $data['hasta'] = $post['hasta'];
            $data['filtro'] = $post['filtro'];
            
            $this->load->model('cliente','c');
            $this->load->model('sucursal','s');
            $this->load->model('contacto','co');
            $this->load->model('pedido','p');
            $this->load->model('preferencias/usuario','u');
            
            $usuarios = $this->u->get_paged_list(NULL, 0, $post['filtro'])->result();
            
            // generar tabla
            $this->load->library('table');
            $this->table->set_empty('&nbsp;');
            
            $tmpl = array ( 'table_open' => '<table class="table table-condensed" >' );
            $this->table->set_template($tmpl);
            $this->table->set_heading('Folio', 'Fecha', 'Cliente', 'Sucursal', 'Piezas', 'Importe');
            
            $total_piezas = 0;
            $total_importe = 0;
            foreach ($usuarios as $u){
                $pedidos = $this->p->get_by_usuario($u->id_usuario, $post['desde'], $post['hasta'].' 23:59:59')->result();
                
                //die($this->db->last_query());
                if(!empty($pedidos)){
                    $clase = '';
                    // Fila con el nombre del usuario
                    $this->table->add_row(
                        array('data' => $u->nombre, 'class' => $clase, 'colspan' => '6')
                    );
                    $this->table->add_row_class($clase);

                    $total_piezas_usuario = 0;
                    $total_importe_usuario = 0;
                    // Filas con los pedidos del usuario
                    foreach($pedidos as $p){
                        $fecha = date_create($p->fecha);
                        $sucursal = $this->s->get_by_id($p->id_cliente_sucursal)->row();
                        $contacto = $this->co->get_by_id($p->id_contacto)->row();
                        $cliente = $this->c->get_by_id($sucursal->id_cliente)->row();
                        $piezas = $this->p->get_piezas($p->id);
                        $importe = $this->p->get_importe($p->id);
                        $total_piezas_usuario += $piezas;
                        $total_importe_usuario += $importe;
                        $this->table->add_row(
                            array('data' => $p->id,'class' => $clase),
                            array('data' => date_format($fecha,'d/m/Y h:i'),'class' => $clase),
                            array('data' => $cliente->nombre,'class' => $clase),
                            array('data' => $sucursal->numero.' '.$sucursal->nombre,'class' => $clase),
                            array('data' => number_format($piezas,0),'class' => $clase, 'style' => 'text-align: right'),
                            array('data' => number_format($importe,2),'class' => $clase, 'style' => 'text-align: right')
                        );
                        $this->table->add_row_class($clase);
                    }
                    $clase = 'resaltado';
                    // Totales por cliente
                    $this->table->add_row(
                        array('data' => 'TOTAL','class' => $clase, 'colspan' => '4'),
                        array('data' => number_format($total_piezas_usuario,0),'class' => $clase, 'style' => 'text-align: right'),
                        array('data' => number_format($total_importe_usuario,2),'class' => $clase, 'style' => 'text-align: right')
                    );
                    $this->table->add_row_class($clase);
                    
                    $this->table->add_row(
                        array('data' => '','class' => $clase, 'colspan' => '6')
                    );
                    $this->table->add_row_class($clase);
            
                    $total_piezas += $total_piezas_usuario;
                    $total_importe += $total_importe_usuario;
                }
            }
            // Totales
            $this->table->add_row(
                array('data' => '','class' => $clase, 'colspan' => '6')
            );
            $this->table->add_row_class($clase);
            
            $this->table->add_row(
                array('data' => 'TOTAL','class' => $clase, 'colspan' => '4'),
                array('data' => number_format($total_piezas,0),'class' => $clase, 'style' => 'text-align: right'),
                array('data' => number_format($total_importe,2),'class' => $clase, 'style' => 'text-align: right')
            );
            $this->table->add_row_class($clase);
                        
            $tabla = $this->table->generate();
            
            //$tabla.= '</tbody></table>';
            $this->load->library('tbs');
            $this->load->library('pdf');
            
            // Se obtiene la plantilla (2° parametro se pone false para evitar que haga conversión de caractéres con htmlspecialchars() )
            $this->tbs->LoadTemplate($this->configuracion->get_valor('template_path').$this->configuracion->get_valor('template_reportes'), false);

            // Se sustituyen los campos en el template
            $this->tbs->VarRef['titulo'] = 'Pedidos por usuario';
            date_default_timezone_set('America/Mexico_City'); // Zona horaria
            $this->tbs->VarRef['fecha'] = date('d/m/Y H:i:s');
            $desde = date_create($post['desde']);
            $hasta = date_create($post['hasta']);
            $this->tbs->VarRef['subtitulo'] = 'Del '.date_format($desde, 'd/m/Y').' al '.date_format($hasta, 'd/m/Y');
            $this->tbs->VarRef['contenido'] = $tabla;
            
            $this->tbs->Show(TBS_NOTHING);
            
            // Se regresa el render
            $output = $this->tbs->Source;
            
            $view = str_replace("{contenido_vista}", $output, $this->template);
//            
//            // PDF
            $this->pdf->pagenumSuffix = '/';
            $this->pdf->SetHeader('{PAGENO}{nbpg}');
            $pdf = $this->pdf->render($view);
//            $pdf = $view;
            
            $fp = fopen($this->configuracion->get_valor('asset_path').$this->configuracion->get_valor('tmp_path').'pedidos_usuario.pdf','w');
            fwrite($fp, $pdf);
            fclose($fp);
            $data['reporte'] = 'pedidos_usuario.pdf';
        }
        $data['titulo'] = 'Reporte <small>Pedidos por usuario</small>';
        $this->load->view('reporte', $data);
    }
    
}
?>
