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
    
    public function llamadas(){
        $data['reporte'] = '';
        if( ($post = $this->input->post()) ){
            
            $data['desde'] = $post['desde'];
            $data['hasta'] = $post['hasta'];
            $data['filtro'] = $post['filtro'];
            
            $this->load->model('llamada','ll');
            $this->load->model('cliente','c');
            $this->load->model('sucursal','s');
            $this->load->model('contacto','co');
            $this->load->model('pedido','p');
            $this->load->model('preferencias/usuario','u');
            $llamadas = $this->ll->get_by_fecha( $post['desde'], $post['hasta'], $post['filtro'] )->result();
            
            // generar tabla
            $this->load->library('table');
            $this->table->set_empty('&nbsp;');
            
            $tmpl = array ( 'table_open' => '<table class="table table-condensed" >' );
            $this->table->set_template($tmpl);
            $this->table->set_heading('Folio', 'Fecha', 'Cliente', 'Sucursal', 'Vendedor');
            $clase = '';
            foreach ($llamadas as $f){
                $fecha = date_create($f->fecha);
                $contacto = $this->co->get_by_id($f->id_cliente_sucursal_contacto)->row();
                $sucursal = $this->s->get_by_id($contacto->id_cliente_sucursal)->row();
                $cliente = $this->c->get_by_id($sucursal->id_cliente)->row();
                $usuario = $this->u->get_by_id($f->id_usuario)->row();
                $this->table->add_row(
                    array('data' => $f->id,'class' => $clase),
                    array('data' => date_format($fecha,'d/m/Y h:i'),'class' => $clase),
                    array('data' => $cliente->nombre,'class' => $clase),
                    array('data' => $sucursal->numero.' '.$sucursal->nombre,'class' => $clase),
                    array('data' => $usuario->nombre,'class' => $clase)
    		);
                
                $this->table->add_row_class($clase);
            }
                        
            $tabla = $this->table->generate();
            
            $vendedores = $this->u->get_paged_list()->result();
            $this->table->set_empty('&nbsp;');
            $this->table->set_heading('Vendedor', 'Total llamadas', 'Total pedidos', 'Total importe');
            $clase = '';
            $total_llamadas = 0;
            $total_pedidos = 0;
            $total_importes = 0;
            foreach ($vendedores as $v){
                $llamadas = $this->ll->get_by_vendedor($v->id_usuario, $post['desde'], $post['hasta'], $post['filtro'])->result();
                $total_llamada = 0;
                $total_pedido = 0;
                $total_importe = 0;
                if(!empty($llamadas)){
                    foreach($llamadas as $ll){
                        $total_llamada++;
                        $total_llamadas++;
                        $pedido = $this->p->get_by_llamada($ll->id)->row();
                        if(!empty($pedido)){
                            $total_pedido++;
                            $total_pedidos++;
                            $total_importe += $this->p->get_importe($pedido->id);
                            $total_importes += $this->p->get_importe($pedido->id);
                        }
                    }
                    $this->table->add_row(
                        array('data' => $v->nombre,'class' => $clase),
                        array('data' => $total_llamada,'class' => $clase),
                        array('data' => $total_pedido,'class' => $clase),
                        array('data' => number_format($total_importe,2),'class' => $clase, 'style' => 'text-align:right')
                    );

                    $this->table->add_row_class($clase);
                }
            }
            $this->table->add_row(
                array('data' => 'TOTAL','class' => $clase),
                array('data' => $total_llamadas,'class' => $clase),
                array('data' => $total_pedidos,'class' => $clase),
                array('data' => number_format($total_importes,2),'class' => $clase, 'style' => 'text-align:right')
            );
            $this->table->add_row_class('text-info');
                        
            $tabla2 = $this->table->generate();
            
            //$tabla.= '</tbody></table>';
            $this->load->library('tbs');
            $this->load->library('pdf');
            
            // Se obtiene la plantilla (2° parametro se pone false para evitar que haga conversión de caractéres con htmlspecialchars() )
            $this->tbs->LoadTemplate($this->configuracion->get_valor('template_path').$this->configuracion->get_valor('template_reportes'), false);

            // Se sustituyen los campos en el template
            $this->tbs->VarRef['titulo'] = 'Llamadas';
            date_default_timezone_set('America/Mexico_City'); // Zona horaria
            $this->tbs->VarRef['fecha'] = date('d/m/Y H:i:s');
            $desde = date_create($post['desde']);
            $hasta = date_create($post['hasta']);
            $this->tbs->VarRef['subtitulo'] = 'Del '.date_format($desde, 'd/m/Y').' al '.date_format($hasta, 'd/m/Y');
            $this->tbs->VarRef['contenido'] = $tabla . '<br>'. $tabla2;
            
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
            
            $fp = fopen($this->configuracion->get_valor('asset_path').$this->configuracion->get_valor('tmp_path').'llamadas.pdf','w');
            fwrite($fp, $pdf);
            fclose($fp);
            $data['reporte'] = 'llamadas.pdf';
        }
        $data['titulo'] = 'Reporte <small>Llamadas</small>';
        $this->load->view('reporte', $data);
    }
    
    //Ventas por cliente
    public function pedidos_cliente(){
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
            
            $clientes = $this->c->get_paged_list(NULL, 0, $post['filtro'])->result();
            
            // generar tabla
            $this->load->library('table');
            $this->table->set_empty('&nbsp;');
            
            $tmpl = array ( 'table_open' => '<table class="table table-condensed" >' );
            $this->table->set_template($tmpl);
            $this->table->set_heading('Folio', 'Fecha', 'Sucursal', 'Vendedor', 'Piezas', 'Importe');
            
            $total_piezas = 0;
            $total_importe = 0;
            foreach ($clientes as $c){
                $pedidos = $this->p->get_by_cliente($c->id, $post['desde'], $post['hasta'])->result();
                
                //die($this->db->last_query());
                if(!empty($pedidos)){
                    $clase = '';
                    // Fila con el nombre del cliente
                    $this->table->add_row(
                        array('data' => $c->nombre, 'class' => $clase, 'colspan' => '6')
                    );
                    $this->table->add_row_class($clase);

                    $total_piezas_cliente = 0;
                    $total_importe_cliente = 0;
                    // Filas con los pedidos del cliente
                    foreach($pedidos as $p){
                        $fecha = date_create($p->fecha);
                        $usuario = $this->u->get_by_id($p->id_usuario)->row();
                        $sucursal = $this->s->get_by_id($p->id_cliente_sucursal)->row();
                        $contacto = $this->co->get_by_id($p->id_contacto)->row();
                        $piezas = $this->p->get_piezas($p->id);
                        $importe = $this->p->get_importe($p->id);
                        $total_piezas_cliente += $piezas;
                        $total_importe_cliente += $importe;
                        $this->table->add_row(
                            array('data' => $p->id,'class' => $clase),
                            array('data' => date_format($fecha,'d/m/Y h:i'),'class' => $clase),
                            array('data' => $sucursal->numero.' '.$sucursal->nombre,'class' => $clase),
                            array('data' => $usuario->nombre,'class' => $clase),
                            array('data' => number_format($piezas,0),'class' => $clase, 'style' => 'text-align: right'),
                            array('data' => number_format($importe,2),'class' => $clase, 'style' => 'text-align: right')
                        );
                        $this->table->add_row_class($clase);
                    }
                    $clase = 'resaltado';
                    // Totales por cliente
                    $this->table->add_row(
                        array('data' => 'TOTAL','class' => $clase, 'colspan' => '4'),
                        array('data' => number_format($total_piezas_cliente,0),'class' => $clase, 'style' => 'text-align: right'),
                        array('data' => number_format($total_importe_cliente,2),'class' => $clase, 'style' => 'text-align: right')
                    );
                    $this->table->add_row_class($clase);
                    
                    $this->table->add_row(
                        array('data' => '','class' => $clase, 'colspan' => '6')
                    );
                    $this->table->add_row_class($clase);
            
                    $total_piezas += $total_piezas_cliente;
                    $total_importe += $total_importe_cliente;
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
            $this->tbs->VarRef['titulo'] = 'Pedidos por cliente';
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
            
            $fp = fopen($this->configuracion->get_valor('asset_path').$this->configuracion->get_valor('tmp_path').'pedidos_cliente.pdf','w');
            fwrite($fp, $pdf);
            fclose($fp);
            $data['reporte'] = 'pedidos_cliente.pdf';
        }
        $data['titulo'] = 'Reporte <small>Pedidos por cliente</small>';
        $this->load->view('reporte', $data);
    }
}
?>
