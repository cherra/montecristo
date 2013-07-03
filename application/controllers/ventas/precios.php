<?php

/**
 * Description of precios
 *
 * @author cherra
 */
class Precios extends CI_Controller{
    
    private $folder = 'ventas/';
    private $clase = 'precios/';
    
    /*
     * Listas de precios
     */
    public function listas( $offset = 0 ){
        $this->load->model('lista','l');
        
        $this->config->load("pagination");
    	
        $data['titulo'] = 'Listas de precios <small>Listado</small>';
    	$data['link_add'] = anchor($this->folder.$this->clase.'listas_agregar','<i class="icon-plus icon-white"></i> Agregar', array('class' => 'btn btn-inverse'));
    	$data['action'] = $this->folder.$this->clase.'listas';
        
        // Filtro de busqueda (se almacenan en la sesión a través de un hook)
        $filtro = $this->session->userdata('filtro');
        if($filtro)
            $data['filtro'] = $filtro;
        
        $page_limit = $this->config->item("per_page");
    	$datos = $this->l->get_paged_list($page_limit, $offset, $filtro)->result();
    	
        // generar paginacion
    	$this->load->library('pagination');
    	$config['base_url'] = site_url($this->folder.$this->clase.'listas');
    	$config['total_rows'] = $this->l->count_all($filtro);
    	$config['per_page'] = $page_limit;
    	$config['uri_segment'] = 4;
    	$this->pagination->initialize($config);
    	$data['pagination'] = $this->pagination->create_links();
    	
    	// generar tabla
    	$this->load->library('table');
    	$this->table->set_empty('&nbsp;');
    	$tmpl = array ( 'table_open' => '<table class="' . $this->config->item('tabla_css') . '" >' );
    	$this->table->set_template($tmpl);
    	$this->table->set_heading('Nombre', 'Descripción', '');
    	foreach ($datos as $d) {
            $this->table->add_row(
                    $d->nombre,
                    $d->descripcion,
                    array('data' => anchor($this->folder.$this->clase.'index/' . $d->id, '<i class="icon-usd"></i>', array('class' => 'btn btn-small')), 'style' => 'text-align: right;'),
                    array('data' => anchor($this->folder.$this->clase.'listas_editar/' . $d->id, '<i class="icon-edit"></i>', array('class' => 'btn btn-small')), 'style' => 'text-align: right;')
            );
    	}
    	$data['table'] = $this->table->generate();
    	
    	$this->load->view('ventas/lista', $data);
    }
    
    public function listas_agregar() {
    	$this->load->model('lista', 'l');
        
    	$data['titulo'] = 'Listas de precios <small>Registro nuevo</small>';
    	$data['link_back'] = anchor($this->folder.$this->clase.'listas','<i class="icon-arrow-left"></i> Regresar',array('class'=>'btn'));
    
    	$data['action'] = $this->folder.$this->clase.'listas_agregar';
    	if ( ($datos = $this->input->post()) ) {
    		$this->l->save($datos);
    		$data['mensaje'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>¡Registro exitoso!</div>';
    	}
        $this->load->view($this->folder.$this->clase.'listas_formulario', $data);
    }
    
    public function listas_editar( $id = NULL ) {
    	$this->load->model('lista', 'l');
        $lista = $this->l->get_by_id($id);
        if ( empty($id) OR $lista->num_rows() <= 0) {
    		redirect($this->folder.$this->clase.'listas');
    	}
    	
    	$data['titulo'] = 'Listas de precios <small>Editar registro</small>';
    	$data['link_back'] = anchor($this->folder.$this->clase.'listas','<i class="icon-arrow-left"></i> Regresar',array('class'=>'btn'));
    	$data['mensaje'] = '';
    	$data['action'] = $this->folder.$this->clase.'listas_editar/' . $id;
    	 
    	if ( ($datos = $this->input->post()) ) {
    		$this->l->update($id, $datos);
    		$data['mensaje'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>¡Registro modificado!</div>';
    	}

    	$data['datos'] = $this->l->get_by_id($id)->row();
        
        $this->load->view($this->folder.$this->clase.'listas_formulario', $data);
    }
    
    /*
     * Precios
     */
    public function index( $id = NULL, $offset = 0 ) {
        $this->load->model('lista', 'l');
        $this->load->model('precio', 'p');
        $this->load->model('producto_presentacion', 'pp');
        $this->load->model('producto','pro');
        $this->load->model('presentacion','pre');
        
        $data['listas'] = $this->l->get_all()->result();
        
        $data['titulo'] = 'Precios <small>Listado</small>';
        $data['link_back'] = anchor($this->folder.$this->clase.'listas','<i class="icon-arrow-left"></i> Listas',array('class'=>'btn'));
        $data['action'] = $this->folder.$this->clase.'index/'.$id;
        
        // Filtro de busqueda (se almacenan en la sesión a través de un hook)
        $filtro = $this->session->userdata('filtro');
        if($filtro)
            $data['filtro'] = $filtro;
        
        if (!empty($id)) {
            $data['lista'] = $this->l->get_by_id($id)->row();
            
            if( ($datos = $this->input->post()) ){
                foreach($datos as $id_producto_presentacion => $precio){
                    if(is_numeric($id_producto_presentacion)){
                        $this->p->save($id, $id_producto_presentacion, $precio);
                    }
                }
            }
            
            // obtener datos
            $this->config->load("pagination");
            $page_limit = $this->config->item("per_page");
            $presentaciones = $this->pp->get_paged_list($page_limit, $offset, $filtro)->result();

            // generar paginacion
            $this->load->library('pagination');
            $config['base_url'] = site_url($this->folder.$this->clase.'precios/' . $id);
            $config['total_rows'] = $this->pp->count_all();
            $config['uri_segment'] = 5;
            $this->pagination->initialize($config);
            $data['pagination'] = $this->pagination->create_links();

            // generar tabla
            $this->load->library('table');
            $this->table->set_empty('&nbsp;');
            $tmpl = array ( 'table_open' => '<table class="' . $this->config->item('tabla_css') . '">' );
            $this->table->set_template($tmpl);
            $this->table->set_heading('SKU', 'Producto', 'Presentacion', 'Precio');
            foreach ($presentaciones as $p) {
                $producto = $this->pro->get_by_id($p->id_producto)->row();
                $presentacion = $this->pre->get_by_id($p->id_presentacion)->row();
                $precio = $this->p->get_by_lista_producto_presentacion($id, $p->id)->row();
                $this->table->add_row(
                        $p->sku,
                        $producto->nombre,
                        $presentacion->nombre,
                        '<div class="input-append" style="margin-bottom: 0;">
                            <input type="text" name="'.$p->id.'" id="'.$p->id.'" class="input-mini" disabled value="'. number_format((empty($precio->precio) ? 0 : $precio->precio),2) .'" />
                            <button class="btn" type="button" id_producto_presentacion="'.$p->id.'"><i class="icon-edit"></i></button>
                         </div>'
                        //array('data' => anchor($this->folder.$this->clase.'precios_editar/' . $p->id, '<i class="icon-edit"></i>', array('class' => 'btn btn-small', 'title' => 'Editar')), 'style' => 'text-align: right;')
                );
            }

            $data['table'] = $this->table->generate();
            //$data['link_add'] = anchor($this->folder.$this->clase.'pre_agregar/' . $id,'<i class="icon-plus icon-white"></i> Agregar', array('class' => 'btn btn-inverse'));
        }
        
        $this->load->view($this->folder.$this->clase.'precios_lista', $data);
    }
}

?>
