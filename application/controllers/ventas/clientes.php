<?php

/**
 * Description of clientes
 *
 * @author cherra
 */
class Clientes extends CI_Controller{
    
    private $folder = 'ventas/';
    private $clase = 'clientes/';
    
    /*
     * Grupos de clientes
     */
    public function grupos( $offset = 0 ){
        $this->load->model('grupo','g');
        
        $this->config->load("pagination");
    	
        $data['titulo'] = 'Grupos <small>Lista</small>';
    	$data['link_add'] = anchor($this->folder.$this->clase.'grupos_agregar','<i class="icon-plus icon-white"></i> Agregar', array('class' => 'btn btn-inverse'));
    	$data['action'] = $this->folder.$this->clase.'grupos';
        
        // Filtro de busqueda (se almacenan en la sesión a través de un hook)
        $filtro = $this->session->userdata('filtro');
        if($filtro)
            $data['filtro'] = $filtro;
        
        $page_limit = $this->config->item("per_page");
    	$datos = $this->g->get_paged_list($page_limit, $offset, $filtro)->result();
    	
        // generar paginacion
    	$this->load->library('pagination');
    	$config['base_url'] = site_url($this->folder.$this->clase.'index');
    	$config['total_rows'] = $this->g->count_all_filtro($filtro);
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
                    array('data' => anchor($this->folder.$this->clase.'grupos_editar/' . $d->id, '<i class="icon-edit"></i>', array('class' => 'btn btn-small')), 'style' => 'text-align: right;')
            );
    	}
    	$data['table'] = $this->table->generate();
    	
    	$this->load->view('ventas/lista', $data);
    }
    
    /*
     * Agregar un grupo
     */
    public function grupos_agregar() {
    	$this->load->model('grupo', 'g');
        
    	$data['titulo'] = 'Grupos <small>Registro nuevo</small>';
    	$data['link_back'] = anchor($this->folder.$this->clase.'grupos','<i class="icon-arrow-left"></i> Regresar',array('class'=>'btn'));
    
    	$data['action'] = $this->folder.$this->clase.'grupos_agregar';
    	if ( ($datos = $this->input->post()) ) {
    		$this->g->save($datos);
    		$data['mensaje'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>¡Registro exitoso!</div>';
    	}
        $this->load->view('ventas/clientes/grupos_formulario', $data);
    }
    
    /*
     * Editar grupo
     */
    public function grupos_editar( $id = NULL ) {
    	$this->load->model('grupo', 'g');
        $grupo = $this->g->get_by_id($id);
        if ( empty($id) OR $grupo->num_rows() <= 0) {
    		redirect($this->folder.$this->clase.'grupos');
    	}
    	
    	$data['titulo'] = 'Grupos <small>Editar registro</small>';
    	$data['link_back'] = anchor($this->folder.$this->clase.'grupos','<i class="icon-arrow-left"></i> Regresar',array('class'=>'btn'));
    	$data['mensaje'] = '';
    	$data['action'] = $this->folder.$this->clase.'grupos_editar/' . $id;
    	 
    	if ( ($datos = $this->input->post()) ) {
    		$this->g->update($id, $datos);
    		$data['mensaje'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>¡Registro modificado!</div>';
    	}

    	$data['datos'] = $this->g->get_by_id($id)->row();
        
        $this->load->view('ventas/clientes/grupos_formulario', $data);
    }
    
    /*
     * Listado de clientes
     */
    
    public function index( $offset = 0 ){
        $this->load->model('cliente','c');
        $this->load->model('lista','l');
        
        $this->config->load("pagination");
    	
        $data['titulo'] = 'Clientes <small>Lista</small>';
    	$data['link_add'] = anchor($this->folder.$this->clase.'clientes_agregar','<i class="icon-plus icon-white"></i> Agregar', array('class' => 'btn btn-inverse'));
    	$data['action'] = $this->folder.$this->clase.'index';
        
        // Filtro de busqueda (se almacenan en la sesión a través de un hook)
        $filtro = $this->session->userdata('filtro');
        if($filtro)
            $data['filtro'] = $filtro;
        
        $page_limit = $this->config->item("per_page");
    	$datos = $this->c->get_paged_list($page_limit, $offset, $filtro)->result();
    	
        // generar paginacion
    	$this->load->library('pagination');
    	$config['base_url'] = site_url($this->folder.$this->clase.'index');
    	$config['total_rows'] = $this->c->count_all_filtro($filtro);
    	$config['per_page'] = $page_limit;
    	$config['uri_segment'] = 4;
    	$this->pagination->initialize($config);
    	$data['pagination'] = $this->pagination->create_links();
    	
    	// generar tabla
    	$this->load->library('table');
    	$this->table->set_empty('&nbsp;');
    	$tmpl = array ( 'table_open' => '<table class="' . $this->config->item('tabla_css') . '" >' );
    	$this->table->set_template($tmpl);
    	$this->table->set_heading('Nombre', 'Lista', 'Municipio', 'Estado', 'Teléfono', '', '');
    	foreach ($datos as $d) {
            $lista = $this->l->get_by_id($d->id_lista)->row();
            $this->table->add_row(
                    $d->nombre,
                    (!empty($lista->nombre) ? $lista->nombre : ''),
                    $d->municipio,
                    $d->estado,
                    $d->telefono,
                    array('data' => anchor('ventas/pedidos/pedidos_agregar/' . $d->id, '<i class="icon-shopping-cart"></i>', array('class' => 'btn btn-small', 'title' => 'Pedido')), 'style' => 'text-align: right;'),
                    array('data' => anchor($this->folder.$this->clase.'sucursales/' . $d->id, '<i class="icon-building"></i>', array('class' => 'btn btn-small')), 'style' => 'text-align: right;'),
                    array('data' => anchor($this->folder.$this->clase.'clientes_editar/' . $d->id, '<i class="icon-edit"></i>', array('class' => 'btn btn-small')), 'style' => 'text-align: right;')
            );
    	}
    	$data['table'] = $this->table->generate();
    	
    	$this->load->view('ventas/lista', $data);
    }
    
    /*
     * Agregar un cliente
     */
    public function clientes_agregar() {
    	$this->load->model('grupo', 'g');
        $this->load->model('cliente','c');
        $this->load->model('lista','l');
        
    	$data['titulo'] = 'Clientes <small>Registro nuevo</small>';
    	$data['link_back'] = anchor($this->folder.$this->clase.'index','<i class="icon-arrow-left"></i> Regresar',array('class'=>'btn'));
    
    	$data['action'] = $this->folder.$this->clase.'clientes_agregar';
    	if ( ($datos = $this->input->post()) ) {
    		$this->c->save($datos);
    		$data['mensaje'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>¡Registro exitoso!</div>';
    	}
        $data['grupos'] = $this->g->get_all()->result();
        $data['listas'] = $this->l->get_all()->result();
        $this->load->view('ventas/clientes/clientes_formulario', $data);
    }
    
    /*
     * Editar un cliente
     */
    public function clientes_editar( $id = NULL ) {
    	$this->load->model('cliente', 'c');
        $this->load->model('grupo', 'g');
        $this->load->model('lista','l');
        
        $cliente = $this->c->get_by_id($id);
        if ( empty($id) OR $cliente->num_rows() <= 0) {
    		redirect($this->folder.$this->clase.'index');
    	}
    	
    	$data['titulo'] = 'Clientes <small>Editar registro</small>';
    	$data['link_back'] = anchor($this->folder.$this->clase.'index','<i class="icon-arrow-left"></i> Regresar',array('class'=>'btn'));
    	$data['mensaje'] = '';
    	$data['action'] = $this->folder.$this->clase.'clientes_editar/' . $id;
    	 
    	if ( ($datos = $this->input->post()) ) {
    		$this->c->update($id, $datos);
    		$data['mensaje'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>¡Registro modificado!</div>';
    	}

        $data['grupos'] = $this->g->get_all()->result();
        $data['listas'] = $this->l->get_all()->result();
    	$data['datos'] = $this->c->get_by_id($id)->row();
        
        $this->load->view('ventas/clientes/clientes_formulario', $data);
    }
    
    /*
     * Sucursales por cliente
     */
    public function sucursales($id = NULL, $offset = 0) {
        $this->load->model('cliente', 'c');
        $this->load->model('sucursal', 's');
        $data['clientes'] = $this->c->get_all()->result();
        
        $data['titulo'] = 'Sucursales <small>Listado</small>';
        $data['link_back'] = anchor($this->folder.$this->clase.'index','<i class="icon-arrow-left"></i> Clientes',array('class'=>'btn'));
        $data['action'] = $this->folder.$this->clase.'sucursales/'.$id;
        
        // Filtro de busqueda (se almacenan en la sesión a través de un hook)
        $filtro = $this->session->userdata('filtro');
        if($filtro)
            $data['filtro'] = $filtro;
        
        if (!empty($id)) {
            $data['cliente'] = $this->c->get_by_id($id)->row();
            
            // obtener datos
            $this->config->load("pagination");
            //$this->load->model('fraccionamientos/manzana', 'm');
            $page_limit = $this->config->item("per_page");
            $sucursales = $this->s->get_paged_list($page_limit, $offset, $filtro, $id)->result();

            // generar paginacion
            $this->load->library('pagination');
            $config['base_url'] = site_url($this->folder.$this->clase.'sucursales/' . $id);
            $config['total_rows'] = $this->s->count_all($filtro, $id);
            $config['uri_segment'] = 5;
            $this->pagination->initialize($config);
            $data['pagination'] = $this->pagination->create_links();

            // generar tabla
            $this->load->library('table');
            $this->table->set_empty('&nbsp;');
            $tmpl = array ( 'table_open' => '<table class="' . $this->config->item('tabla_css') . '">' );
            $this->table->set_template($tmpl);
            $this->table->set_heading('Núm', 'Nombre', 'Población', 'Municipio', 'Estado', 'Teléfono', array('data' => 'E-mail', 'class' => 'hidden-phone'), '','');
            foreach ($sucursales as $sucursal) {
                $this->table->add_row(
                        $sucursal->numero, 
                        $sucursal->nombre,
                        $sucursal->poblacion,
                        $sucursal->municipio,
                        $sucursal->estado,
                        $sucursal->telefono,
                        array('data' => $sucursal->email, 'class' => 'hidden-phone'),
                        array('data' => anchor('ventas/pedidos/pedidos_agregar/' . $id . '/'. $sucursal->id, '<i class="icon-shopping-cart"></i>', array('class' => 'btn btn-small', 'title' => 'Pedido')), 'style' => 'text-align: right;'),
                        array('data' => anchor($this->folder.$this->clase.'contactos/' . $id.'/'.$sucursal->id, '<i class="icon-phone"></i>', array('class' => 'btn btn-small', 'title' => 'Contactos')), 'style' => 'text-align: right;'),
                        array('data' => anchor($this->folder.$this->clase.'sucursales_editar/' . $sucursal->id, '<i class="icon-edit"></i>', array('class' => 'btn btn-small', 'title' => 'Editar')), 'style' => 'text-align: right;')
                );
            }

            $data['table'] = $this->table->generate();
            $data['link_add'] = anchor($this->folder.$this->clase.'sucursales_agregar/' . $id,'<i class="icon-plus icon-white"></i> Agregar', array('class' => 'btn btn-inverse'));
        }
        
        $this->load->view('ventas/clientes/sucursales_lista', $data);
    }
    
    /*
     * Agregar una sucursal
     */
    public function sucursales_agregar( $id = NULL ) {
        if (empty($id)) {
            redirect($this->folder.$this->clase.'sucursales');
        }

        $this->load->model('cliente', 'c');
        $this->load->model('sucursal', 's');
        $data['cliente'] = $this->c->get_by_id($id)->row();
        $data['titulo'] = 'Sucursales <small>Registro nuevo</small>';
        $data['link_back'] = anchor($this->folder.$this->clase.'sucursales/' . $id,'<i class="icon-arrow-left"></i> Regresar',array('class'=>'btn'));
        $data['mensaje'] = '';
        $data['action'] = $this->folder.$this->clase.'sucursales_agregar/' . $id;
	
        if ( ( $sucursal = $this->input->post() ) ) {
            $sucursal['id_cliente'] = $id;
            if( $this->s->save($sucursal) > 0 )
                $data['mensaje'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Registro exitoso</div>';
            else
                $data['mensaje'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrió un error!</div>';
        }
        $this->load->view('ventas/clientes/sucursales_formulario', $data);
    }
    
    /*
     * Editar datos de sucursal
     */
    public function sucursales_editar( $id = NULL ) {
    	$this->load->model('cliente', 'c');
        $this->load->model('sucursal', 's');

        $sucursal = $this->s->get_by_id($id);
        if ( empty($id) OR $sucursal->num_rows() <= 0) {
    		redirect($this->folder.$this->clase.'sucursales');
    	}
    	
    	$data['titulo'] = 'Sucursales <small>Editar registro</small>';
    	$data['link_back'] = anchor($this->folder.$this->clase.'sucursales/'.$id,'<i class="icon-arrow-left"></i> Regresar',array('class'=>'btn'));
    	$data['mensaje'] = '';
    	$data['action'] = $this->folder.$this->clase.'sucursales_editar/' . $id;
    	 
    	if ( ($datos = $this->input->post()) ) {
    		$this->s->update($id, $datos);
    		$data['mensaje'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>¡Registro modificado!</div>';
    	}

        //$data['grupos'] = $this->g->get_all()->result();
    	$data['datos'] = $this->s->get_by_id($id)->row();
        $data['cliente'] = $this->c->get_by_id($sucursal->row()->id_cliente)->row();
        
        $this->load->view('ventas/clientes/sucursales_formulario', $data);
    }
    
    /*
     * Contactos por sucursal
     */
    public function contactos( $id_cliente = NULL, $estado = NULL, $id_sucursal = NULL, $offset = 0) {
       
        $this->load->model('cliente', 'cl');
        $this->load->model('sucursal', 's');
        $this->load->model('contacto','c');
        
        $data['titulo'] = 'Contactos <small>Listado</small>';
        $data['link_back'] = anchor($this->folder.$this->clase.'sucursales/'.$id_cliente,'<i class="icon-arrow-left"></i> Sucursales',array('class'=>'btn'));
        $data['action'] = $this->folder.$this->clase.'contactos/'.$id_sucursal;
        
        // Filtro de busqueda (se almacenan en la sesión a través de un hook)
        $filtro = $this->session->userdata('filtro');
        if($filtro)
            $data['filtro'] = $filtro;
        
        $data['clientes'] = $this->cl->get_all()->result();
        if(!empty($id_cliente)){
            $data['estados'] = $this->s->get_estados_by_id_cliente($id_cliente)->result();
            if(!empty($estado)){
                $data['sucursales'] = $this->s->get_by_id_cliente_estado($id_cliente, urldecode($estado))->result();
                $data['estado'] = trim(urldecode($estado));
            }else{
                $data['sucursales'] = $this->s->get_by_id_cliente($id_cliente)->result();
            }
            $data['cliente'] = $this->cl->get_by_id($id_cliente)->row();
            
            if(!empty($id_sucursal)){
                $data['sucursal'] = $this->s->get_by_id($id_sucursal)->row();

                // obtener datos
                $this->config->load("pagination");
                //$this->load->model('fraccionamientos/manzana', 'm');
                $page_limit = $this->config->item("per_page");
                $contactos = $this->c->get_paged_list($page_limit, $offset, $filtro, $id_sucursal)->result();

                // generar paginacion
                $this->load->library('pagination');
                $config['base_url'] = site_url($this->folder.$this->clase.'sucursales/' . $id_sucursal);
                $config['total_rows'] = $this->c->count_all($filtro, $id_sucursal);
                $config['uri_segment'] = 7;
                $this->pagination->initialize($config);
                $data['pagination'] = $this->pagination->create_links();

                // generar tabla
                $this->load->library('table');
                $this->table->set_empty('&nbsp;');
                $tmpl = array ( 'table_open' => '<table class="' . $this->config->item('tabla_css') . '">' );
                $this->table->set_template($tmpl);
                $this->table->set_heading('Nombre', 'Puesto', 'Teléfono', 'Celular', 'E-mail', '','');
                foreach ($contactos as $contacto) {
                    $this->table->add_row(
                            $contacto->nombre,
                            $contacto->puesto,
                            $contacto->telefono,
                            $contacto->celular,
                            $contacto->email,
                            array('data' => anchor('ventas/pedidos/pedidos_agregar/' . $id_cliente . '/'. $id_sucursal . '/'. $contacto->id, '<i class="icon-shopping-cart"></i>', array('class' => 'btn btn-small', 'title' => 'Pedido')), 'style' => 'text-align: right;'),
                            array('data' => anchor('ventas/clientes/contactos_editar/' . $contacto->id, '<i class="icon-edit"></i>', array('class' => 'btn btn-small', 'title' => 'Editar')), 'style' => 'text-align: right;')
                    );
                }

                $data['table'] = $this->table->generate();
            }
        }
        $data['link_add'] = anchor('ventas/clientes/contactos_agregar/' . $id_cliente.'/'. $id_sucursal,'<i class="icon-plus icon-white"></i> Agregar', array('class' => 'btn btn-inverse'));
        
        $this->load->view('ventas/clientes/contactos_lista', $data);
    }
    
    public function contactos_agregar( $id_cliente = NULL, $id_sucursal = NULL ) {
        if (empty($id_cliente) OR empty($id_sucursal)) {
            redirect($this->folder.$this->clase.'contactos');
        }

        $this->load->model('cliente', 'c');
        $this->load->model('sucursal', 's');
        $this->load->model('contacto','co');
        $data['cliente'] = $this->c->get_by_id($id_cliente)->row();
        $data['sucursal'] = $this->s->get_by_id($id_sucursal)->row();
        
        $data['titulo'] = 'Contactos <small>Registro nuevo</small>';
        $data['link_back'] = anchor($this->folder.$this->clase.'contactos/' . $id_cliente . '/'. $id_sucursal,'<i class="icon-arrow-left"></i> Regresar',array('class'=>'btn'));
        $data['mensaje'] = '';
        $data['action'] = 'ventas/clientes/contactos_agregar/' . $id_cliente . '/' . $id_sucursal;
	
        if ( ( $contacto = $this->input->post() ) ) {
            $contacto['id_cliente_sucursal'] = $id_sucursal;
            if( $this->co->save($contacto) > 0 )
                $data['mensaje'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Registro exitoso</div>';
            else
                $data['mensaje'] = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>Ocurrió un error!</div>';
        }
        $this->load->view('ventas/clientes/contactos_formulario', $data);
    }
    
    public function contactos_editar( $id = NULL ) {
        $this->load->model('contacto','co');
        
        $contacto = $this->co->get_by_id($id);
        if (empty($id) OR $contacto->num_rows() <= 0) {
            redirect($this->folder.$this->clase.'contactos');
        }
        
    	$this->load->model('cliente', 'c');
        $this->load->model('sucursal', 's');
        $data['sucursal'] = $this->s->get_by_id($contacto->row()->id_cliente_sucursal)->row();
        $data['cliente'] = $this->c->get_by_id($data['sucursal']->id_cliente)->row();
        
    	$data['titulo'] = 'Sucursales <small>Editar registro</small>';
    	$data['link_back'] = anchor($this->folder.$this->clase.'contactos/'.$data['cliente']->id.'/'.$data['sucursal']->id,'<i class="icon-arrow-left"></i> Regresar',array('class'=>'btn'));
    	$data['mensaje'] = '';
    	$data['action'] = $this->folder.$this->clase.'contactos_editar/' . $id;
    	 
    	if ( ($datos = $this->input->post()) ) {
    		$this->co->update($id, $datos);
    		$data['mensaje'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>¡Registro modificado!</div>';
    	}

    	$data['datos'] = $this->co->get_by_id($id)->row();        
        $this->load->view('ventas/clientes/contactos_formulario', $data);
    }
    
    
    public function productos( $id = NULL, $offset = 0 ){
        $this->load->model('cliente', 'c');
        $this->load->model('cliente_presentacion', 'cp');
        
        $data['clientes'] = $this->c->get_all()->result();
        
        $data['titulo'] = 'Productos por cliente <small>Listado</small>';
        $data['link_back'] = anchor($this->folder.$this->clase.'index','<i class="icon-arrow-left"></i> Clientes',array('class'=>'btn'));
        $data['action'] = $this->folder.$this->clase.'productos/'.$id;
        
        // Filtro de busqueda (se almacenan en la sesión a través de un hook)
        $filtro = $this->session->userdata('filtro');
        if($filtro)
            $data['filtro'] = $filtro;
        
        if (!empty($id)) {
            $data['cliente'] = $this->c->get_by_id($id)->row();
            
            // obtener datos
            $this->config->load("pagination");
            //$this->load->model('fraccionamientos/manzana', 'm');
            $page_limit = $this->config->item("per_page");
            $productos = $this->cp->get_paged_list($page_limit, $offset, $filtro, $id)->result();
            
            // generar paginacion
            $this->load->library('pagination');
            $config['base_url'] = site_url($this->folder.$this->clase.'sucursales/' . $id);
            $config['total_rows'] = $this->cp->count_all($filtro, $id);
            $config['uri_segment'] = 5;
            $this->pagination->initialize($config);
            $data['pagination'] = $this->pagination->create_links();

            // generar tabla
            $this->load->library('table');
            $this->table->set_empty('&nbsp;');
            $tmpl = array ( 'table_open' => '<table class="' . $this->config->item('tabla_css') . '">' );
            $this->table->set_template($tmpl);
            $this->table->set_heading('Código', 'SKU', 'Producto', 'Presentación', 'Precio', '');
            foreach ($productos as $p) {
                    $this->table->add_row(
                            $p->codigo, 
                            $p->sku, 
                            $p->producto,
                            $p->presentacion,
                            $p->precio,
                            array('data' => anchor($this->folder.$this->clase.'productos_editar/' . $p->id_producto_presentacion.'/'.$id, '<i class="icon-edit"></i>', array('class' => 'btn btn-small', 'title' => 'Editar')), 'style' => 'text-align: right;')
                    );
            }

            $data['table'] = $this->table->generate();
            //$data['link_add'] = anchor($this->folder.$this->clase.'sucursales_agregar/' . $id,'<i class="icon-plus icon-white"></i> Agregar', array('class' => 'btn btn-inverse'));
        }
        
        $this->load->view('ventas/clientes/productos_lista', $data);
    }
    
    /*
     * Editar Alias de los productos por cliente
     */
    public function productos_editar( $id = NULL, $id_cliente = NULL ) {
        if(empty($id) OR empty($id_cliente)){
            redirect($this->folder.$this->clase.'productos');
        }
    	$this->load->model('cliente', 'c');
        $this->load->model('cliente_presentacion', 'cp');
        //$this->load->model('precio','p');
        $this->load->model('producto_presentacion','pp');
        $this->load->model('producto','p');
        $this->load->model('presentacion','pr');

    	$data['titulo'] = 'Productos por cliente <small>Editar registro</small>';
    	$data['link_back'] = anchor($this->folder.$this->clase.'productos/'.$id_cliente,'<i class="icon-arrow-left"></i> Regresar',array('class'=>'btn'));
    	$data['mensaje'] = '';
    	$data['action'] = $this->folder.$this->clase.'productos_editar/' . $id.'/'.$id_cliente;
    	 
    	if ( ($datos = $this->input->post()) ) {
            $alias = $this->cp->get_by_producto_cliente($id, $id_cliente);
            if($alias->num_rows() > 0)
    		$this->cp->update($alias->row()->id, $datos);
            else{
                $datos['id_producto_presentacion'] = $id;
                $datos['id_cliente'] = $id_cliente;
                $this->cp->save($datos);
            }
            $data['mensaje'] = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>¡Registro modificado!</div>';
    	}

        $producto_presentacion = $this->pp->get_by_id($id)->row();
        $data['producto_presentacion'] = $producto_presentacion;
        $data['producto'] = $this->p->get_by_id($producto_presentacion->id_producto)->row();
        $data['presentacion'] = $this->pr->get_by_id($producto_presentacion->id_presentacion)->row();
    	$data['datos'] = $this->cp->get_by_producto_cliente($id, $id_cliente)->row();
        $data['cliente'] = $this->c->get_by_id($id_cliente)->row();
        
        $this->load->view('ventas/clientes/productos_formulario', $data);
    }
    
    
    /****************************
     * Métodos Ajax
     */
    
    public function get_clientes( $filtro = NULL ){
        // La petición debe venir por GET
        if($this->input->is_ajax_request()){
            if( ($filtro = $this->input->get('filtro')) ){
                $this->load->model('cliente','c');
                $limit = ($this->input->get('limit') ? $this->input->get('limit') : NULL);
                $query = $this->c->get_paged_list($limit, 0, $filtro);
                
                if($query->num_rows() > 0){
                    $clientes = $query->result();
                    echo json_encode($clientes);
                }else{
                    echo json_encode(FALSE);
                }
            }else{
                echo json_encode(FALSE);
            }
        }
    }
    
    public function get_sucursales( $filtro = NULL ){
        // La petición debe venir por GET
        if($this->input->is_ajax_request()){
            if( ($id_cliente = $this->input->get('id_cliente')) ){
                $this->load->model('sucursal','s');
                $limit = ($this->input->get('limit') ? $this->input->get('limit') : NULL);
                $filtro = ($this->input->get('filtro') ? $this->input->get('filtro') : NULL);
                if($id_cliente){
                    $query = $this->s->get_paged_list($limit, 0, $filtro, $id_cliente);

                    if($query->num_rows() > 0){
                        $sucursales = $query->result();
                        echo json_encode($sucursales);
                    }else{
                        echo json_encode(FALSE);
                    }
                }else{
                    echo json_encode(FALSE);
                }
            }else{
                echo json_encode(FALSE);
            }
        }
    }
    
    public function get_contactos( $filtro = NULL ){
        // La petición debe venir por GET
        if($this->input->is_ajax_request()){
            if( ($id_sucursal = $this->input->get('id_sucursal')) ){
                $this->load->model('contacto','c');
                $limit = ($this->input->get('limit') ? $this->input->get('limit') : NULL);
                $filtro = ($this->input->get('filtro') ? $this->input->get('filtro') : NULL);
                if($id_sucursal){
                    $query = $this->c->get_paged_list($limit, 0, $filtro, $id_sucursal);

                    if($query->num_rows() > 0){
                        $contactos = $query->result();
                        echo json_encode($contactos);
                    }else{
                        echo json_encode(FALSE);
                    }
                }else{
                    echo json_encode(FALSE);
                }
            }else{
                echo json_encode(FALSE);
            }
        }
    }
    
    public function get_productos(){
        // La petición debe venir por GET
        if($this->input->is_ajax_request()){
            if( ($id_cliente = $this->input->get('id_cliente')) ){
                $this->load->model('cliente_presentacion', 'cp');
                
                $limit = ($this->input->get('limit') ? $this->input->get('limit') : NULL);
                $filtro = ($this->input->get('filtro') ? $this->input->get('filtro') : NULL);
                if($id_cliente){
                    $query = $this->cp->get_productos($limit, 0, $filtro, $id_cliente);
                    
                    if($query->num_rows() > 0){
                        $productos = $query->result();
                        echo json_encode($productos);
                    }else{
                        echo json_encode(FALSE);
                    }
                }else{
                    echo json_encode(FALSE);
                }
            }else{
                echo json_encode(FALSE);
            }
        }
    }
    
    public function get_presentaciones(){
        // La petición debe venir por GET
        if($this->input->is_ajax_request()){
            if( ($id_cliente = $this->input->get('id_cliente')) && ($id_producto = $this->input->get('id_producto')) ){
                $this->load->model('cliente_presentacion', 'cp');
                
                $limit = ($this->input->get('limit') ? $this->input->get('limit') : NULL);
                $filtro = ($this->input->get('filtro') ? $this->input->get('filtro') : NULL);
                if($id_cliente && $id_producto){
                    $query = $this->cp->get_presentaciones($limit, 0, $filtro, $id_cliente, $id_producto);
                    //echo $this->db->last_query();
                    //die();
                    if($query->num_rows() > 0){
                        $presentaciones = $query->result();
                        echo json_encode($presentaciones);
                    }else{
                        echo json_encode(FALSE);
                    }
                }else{
                    echo json_encode(FALSE);
                }
            }else{
                echo json_encode(FALSE);
            }
        }
    }
    
    public function get_precio_presentacion(){
        // La petición debe venir por GET
        if($this->input->is_ajax_request()){
            if( ($id_cliente = $this->input->get('id_cliente')) && ($id_producto_presentacion = $this->input->get('id_producto_presentacion')) ){
                $this->load->model('cliente', 'c');
                $this->load->model('cliente_presentacion', 'cp');
                $this->load->model('precio','p');
                
                if($id_cliente && $id_producto_presentacion){
                    $cliente = $this->c->get_by_id($id_cliente)->row();
                    $query = $this->p->get_by_lista_producto_presentacion($cliente->id_lista, $id_producto_presentacion);

                    if($query->num_rows() > 0){
                        $precio = $query->row();
                        /*$alias = $this->cp->get_presentacion( $id_cliente, $id_producto_presentacion)->row();
                        
                        $precio['codigo'] = $alias->codigo;
                        $precio['nombre'] = $alias->presentacion;*/
                        echo json_encode($precio);
                    }else{
                        echo json_encode(FALSE);
                    }
                }else{
                    echo json_encode(FALSE);
                }
            }else{
                echo json_encode(FALSE);
            }
        }
    }
}

?>
