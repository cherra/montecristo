<div class="row-fluid">
    <div class="page-header">
        <h2><?php echo $titulo; ?></h2> 
        <a href="javascript:history.back(-1)" class="btn"><i class="icon-arrow-left"></i> Regresar</a>
    </div>
</div>
<?php
if(isset($pedido)){
?>
<div class="row-fluid">
    <div class="span1">
        <p class="lead text-right" style="margin-bottom: 10px;"><strong>Pedido:</strong></p>
    </div>
    <div class="span2">
        <p class="lead text-info text-right" style="margin-bottom: 10px;"><strong><?php echo $pedido->id; ?></strong></p>
    </div>
    <div class="span3">
        <?php if(isset($pedido)){ ?>
        <p style="margin-bottom: 10px;"><button class="btn btn-warning" id="editar"><i class="icon-edit"></i> Editar</button></p>
        <?php } ?>
    </div>
    <div class="offset3 span2">
        <?php if(isset($pedido)){ ?>
        <p style="margin-bottom: 10px;"><button class="btn" id="duplicar"><i class="icon-copy"></i> Duplicar</button></p>
        <?php } ?>
    </div>
    <div class="span1">
        <?php if(isset($icono_estado)){ ?>
        <p class="text-right text-info" style="margin-bottom: 10px;"><?php echo $icono_estado; ?></p>
        <?php } ?>
    </div>
</div>
<?php
}
?>
<div class="row-fluid">
    <form id="todos_datos_cliente">
        <div class="span3">
            <!-- Cuando se edita el pedido se le asigna el id al input id_pedido -->
            <input type="hidden" id="id_pedido" value="<?php if(isset($pedido)) echo $pedido->id; ?>" />
            <input type="hidden" id="id_cliente" value="<?php if(isset($cliente)) echo $cliente->id; ?>" />
            <input type="hidden" id="id_sucursal" value="<?php if(isset($sucursal)) echo $sucursal->id; ?>" />
            <input type="hidden" id="id_contacto" value="<?php if(isset($contacto)) echo $contacto->id; ?>" />
            
            <label><strong>Buscar cliente</strong></label>
            <input type="text" id="cliente" class="input-block-level" placeholder="Cliente" value="<?php echo (isset($cliente) ? $cliente->nombre : ''); ?>">
            <input type="text" id="sucursal" class="input-block-level" placeholder="Sucursal" value="<?php echo (isset($sucursal) ? $sucursal->nombre : ''); ?>" <?php if(!isset($cliente)) echo "disabled"; ?>>
            <input type="text" id="contacto" class="input-block-level" placeholder="Contacto" value="<?php echo (isset($contacto) ? $contacto->nombre : ''); ?>" disabled>
        </div>
        <div class="span3">
            <address id="datos_cliente">
                <?php
                if(isset($cliente)){
                    echo '<strong>'.$cliente->nombre.'</strong><br>'.
                            $cliente->rfc.'<br>'.
                            $cliente->calle.' '.$cliente->numero_exterior.' '.$cliente->numero_interior.'<br>'.
                            $cliente->colonia.'<br>'.
                            $cliente->poblacion.', '.$cliente->municipio.', '.$cliente->estado.'<br>';
                }else{
                ?>
                <br><br><br><br><br>
                <?php
                }
                ?>
            </address>
            <button class="btn btn-small">Editar</button>
        </div>
        <div class="span3">
            <address id="datos_sucursal">
                <?php
                if(isset($sucursal)){
                    echo '<strong>'.$sucursal->numero.' '.$sucursal->nombre.'</strong><br>'.
                            $sucursal->calle.' '.$sucursal->numero_exterior.' '.$sucursal->numero_interior.'<br>'.
                            $sucursal->colonia.'<br>'.
                            $sucursal->poblacion.', '.$sucursal->municipio.', '.$sucursal->estado.'<br>'.
                            $sucursal->telefono.'<br>';
                }else{
                ?>
                <br><br><br><br><br>
                <?php
                }
                ?>
            </address>
            <button class="btn btn-small">Editar</button>
        </div>
        <div class="span3">
            <address id="datos_contacto">
                <?php
                if(isset($contacto)){
                    echo '<strong>'.$contacto->nombre.'</strong><br>'.
                            $contacto->puesto.'<br>'.
                            $contacto->telefono.'<br>'.
                            $contacto->celular.'<br>'.
                            $contacto->email.'<br>';
                }else{
                ?>
                <br><br><br><br><br>
                <?php
                }
                ?>
            </address>
            <button class="btn btn-small">Editar</button>
        </div>
    </form>
</div>
<div class="row-fluid">
    <div class="span12">
        <hr>
    </div>
</div>
<form id="llenado">
<div class="row-fluid">
    
        <div class="span1">
                <label for="cantidad">Cantidad</label>
                <input type="text" id="cantidad" placeholder="Cantidad" class="input-block-level" <?php if(empty($pedido)) echo "disabled"; ?>>
        </div>
        <div class="span4">
                <label for="producto">Producto</label>
                <input type="text" id="producto" placeholder="Producto" class="input-block-level" <?php if(empty($pedido)) echo "disabled"; ?>>
                <input type="hidden" id="id_producto"/>
        </div>
        <div class="span2">
                <label for="presentacion">Presentación</label>
                <select id="presentacion" class="input-block-level" disabled>
                    
                </select>
                <!-- <input type="text" id="presentacion" placeholder="Presentación" class="input-block-level" disabled> -->
                <input type="hidden" id="id_producto_presentacion"/>
                <input type="hidden" id="codigo" />
                <input type="hidden" id="nombre" />
        </div>
        <div class="span2">
                <label for="precio">Precio</label>
                <input type="text" id="precio" placeholder="Precio" class="input-block-level" readonly>
        </div>
        <div class="span3">
                <label for="comentarios">Comentarios</label>
                <textarea id="comentarios" placeholder="Comentarios" rows="2" class="input-block-level" <?php if(empty($pedido)) echo "disabled"; ?>></textarea>
        </div>
</div>
<div class="row-fluid">
    <div class="span12">
       
            <button class="btn btn-inverse" id="agregar_producto"><i class="icon-plus"></i> Agregar</button>
       
    </div>
</div>
</form>
<div class="row-fluid">
    <div class="span12" style="height: 220px; overflow-y: auto; overflow-x: hidden;" id="tabla_container">
        <table class="table table-condensed table-bordered table-hover table-striped" id="productos">
            <thead>
                <tr>
                    <th class="span1">Cant.</th>
                    <th class="span2">Código</th>
                    <th class="span3">Producto</th>
                    <th class="span2">Presentación</th>
                    <th class="span2">Precio</th>
                    <th class="span2">Importe</th>
                </th>
            </thead>
            <tbody id="lineas">
                <?php
                if(isset($presentaciones)){
                    foreach($presentaciones as $p){
                        echo '<tr id_producto_presentacion="'.$p->id_producto_presentacion.'" cantidad="'.$p->cantidad.'" precio="'.$p->precio.'" codigo="'.$p->codigo.'" observaciones="'.$p->observaciones.'" producto="'.$p->producto.'" id_producto="'.$p->id_producto.'" title="Click= editar
Doble click= borrar">'.
                                '<td style="text-align: right;">'.$p->cantidad.'</td><td>'.$p->codigo.'</td><td>'.$p->producto.'</td><td>'.$p->presentacion.'</td><td style="text-align: right;">'.$p->precio.'</td><td style="text-align: right;">'.number_format($p->cantidad*$p->precio,2).'</td></tr>';
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <table class="table table-condensed">
            <thead>
                <tr>
                    <th class="span1" style="text-align: right;"><div id="total_cantidad">0.00</div></th>
                    <th class="span2">&nbsp;</th>
                    <th class="span3">&nbsp;</th>
                    <th class="span2">&nbsp;</th>
                    <th class="span2">&nbsp;</th>
                    <th class="span2" style="text-align: right;"><div id="total">0.00</div></th>
                </th>
            </thead>
        </table>
    </div>
</div>
<form class="form-inline" id="final">
    <div class="row-fluid">
        <div class="span3">
            <label for="id_ruta">Ruta</label>
            <select name="id_ruta" id="id_ruta" class="input-block-level required" <?php if(empty($rutas)) echo "disabled"; ?>>
                <option value="">Selecciona una ruta...</option>
                <?php
                    if(!empty($rutas)){
                        foreach($rutas as $r){ ?>
                            <option value="<?php echo $r->id; ?>" <?php if(!empty($ruta) && $ruta->id == $r->id) echo "selected"; ?>><?php echo $r->nombre; ?></option>
                    <?php    
                        }
                    }
                    ?>
            </select>
        </div>
        <div class="span9">
            <label for="observaciones">Observaciones</label>
            <textarea id="observaciones" placeholder="Observaciones" class="input-block-level" rows="3" <?php if(empty($pedido)) echo "disabled"; ?>><?php if(isset($pedido)) echo $pedido->observaciones; ?></textarea>
        </div>
    </div>
</form>
    <div class="row-fluid">
        <div class="span12">
            <hr>
        </div>
    </div>
    <div class="row-fluid">
            <div class="span2">
                <button id="guardar" class="btn btn-primary"><i class="icon-save"></i> Guardar</button>
            </div>
            <div class="offset8 span2">
                <?php
                if(empty($pedido)){
                ?>
                <p class="text-right"><button id="borrar" class="btn btn-danger"><i class="icon-eraser"></i> Limpiar</button></p>
                <?php
                }else{
                ?>
                <p class="text-right"><?php echo $link_imprimir; ?></p>
                <?php
                }
                ?>
            </div>
    </div>
<?php
if(!empty($mensaje)){
?>
<div class="row-fluid">
  <div class="span6 offset3">
    <?php echo $mensaje ?>
  </div>
</div>
<?php
}
?>

<script>
    
function calcula_totales(){
    var total = new Number(0);
    var total_cantidad = new Number(0);

    $("#lineas tr").each(function(fila){
        total += Number($(this).attr('cantidad') * $(this).attr('precio'));
        total_cantidad += Number($(this).attr('cantidad'));
    });
    $('#total').html(Globalize.format(total,'n'));
    $('#total_cantidad').html(Globalize.format(total_cantidad,'n'));

    $("#tabla_container").animate({scrollTop:$("#tabla_container")[0].scrollHeight}, 1000);  // Recorrer el scroll hasta el fondo

    <?php if(!isset($presentaciones)){ ?>
    localStorage.setItem("ventas/pedidos/nuevo",$('#lineas').html());  // Cuando se va a registrar un nuevo pedido
    <?php }else{ ?>
    localStorage.setItem("ventas/pedidos/edicion",$('#lineas').html());  // Edición de un pedido existente
    <?php } ?>
}

function get_presentaciones( id_producto, id_producto_presentacion ){
    var datos;
    var id_cliente = $('#id_cliente').val();
    var id_producto = id_producto;
    var select = '<option value="">Selecciona...</option>';
    // Método Ajax para obtener presentaciones de un producto y por cliente
    $.get('<?php echo site_url('ventas/clientes/get_presentaciones'); ?>', { id_cliente: id_cliente, limit: 10, id_producto: id_producto }, function(data) {
        datos = JSON.parse(data);
        // Se almacena el resultado en un array para devolverlo al "autocomplete"
        if (datos !== false) {
            $.each(datos, function(i, object) {
                if(id_producto_presentacion == object.id_producto_presentacion)
                    select += '<option value="'+object.id_producto_presentacion+'" codigo="'+object.codigo+'" selected>'+object.presentacion+'</option>';
                else
                    select += '<option value="'+object.id_producto_presentacion+'" codigo="'+object.codigo+'">'+object.presentacion+'</option>';
            });
            $('#presentacion').html(select);
        }
    });
}
    
$(document).ready(function(){

    var edicion = true;
    <?php if(isset($pedido)){ ?>
            $('input, textarea, select, button[id!="editar"][id!="duplicar"]').attr('disabled',true);
            edicion = false;
    <?php }?>
        
    $('#editar').click(function(){
        $('input[id!="cliente"], textarea, select, button').removeAttr('disabled');
        $('#autorizar').hide();
        edicion = true;
        $('#cantidad').focus();
    });
    
    $('#duplicar').click(function(){
        window.location = "<?php echo site_url('ventas/pedidos/pedidos_duplicar/'.$pedido->id.'/1/pedidos_editar'); ?>";
    });

    $('#cliente').focus();
    
    // Se anulan los submit de formularios con clase .no-submit
    $('form.no-submit').submit(function(event){
        event.preventDefault();
    });
    
    $(window).bind('beforeunload', function(event){
        localStorage.removeItem("ventas/pedidos/edicion");
    });
    
    <?php if(!isset($presentaciones)){ ?>
        if(localStorage.getItem("ventas/pedidos/nuevo"))
            $('#lineas').html(localStorage.getItem("ventas/pedidos/nuevo"));
    <?php }else{ ?>
        if(localStorage.getItem("ventas/pedidos/edicion"))
            $('#lineas').html(localStorage.getItem("ventas/pedidos/edicion"));
    <?php } ?>
        
    calcula_totales();
    
    var arreglo = new Array();
    $( "#cliente" ).autocomplete({
      source: function(request, response){
            arreglo = [];
            var datos;
            $.get('<?php echo site_url('ventas/clientes/get_clientes'); ?>', { filtro: request.term, limit: 10 }, function(data) {
                datos = JSON.parse(data);
                // Se almacena el resultado en un array para devolverlo al "autocomplete"
                if (datos !== false) {
                    $.each(datos, function(i, object) {
                        object.label = object.nombre;
                        object.value = object.id;
                        arreglo.push(object);
                    });
                }
                // Se devuelve el array
                response(arreglo);
            });
      },
      select: function(event, ui){ // Cuando se selecciona un item
        event.preventDefault();  // Cancelamos el evento default
        $(this).val(ui.item.label);  // Sustituimos el valor del input con el nombre
        $('#id_cliente').val(ui.item.id);  // Se guarda el id del cliente en el input #id_cliente
        // Se muestran los datos del cliente
        $('#datos_cliente').html('<strong>'+ui.item.nombre+'</strong><br>'+
                ui.item.rfc+'<br>'+
                ui.item.calle+' '+ui.item.numero_exterior+' '+ui.item.numero_interior+'<br>'+
                ui.item.colonia+'<br>'+
                ui.item.poblacion+', '+ui.item.municipio+', '+ui.item.estado+'<br>');
        $('#sucursal').removeAttr('disabled').focus().val(''); // Se habilita el input de sucursal y se le da el focus
        $('#contacto').attr('disabled',true).val(''); // Se confirma el estatus 'disabled' del input para contacto
        // Se borran los datos de sucursal y contacto
        $('#datos_sucursal').html('<br><br><br><br><br>');  
        $('#datos_contacto').html('<br><br><br><br><br>');
        $('#llenado input, #llenado textarea').attr('disabled', true);  // Se deshabilitan los input para llenar el pedido (porque al cambiar el cliente puede cambiar el precio).
        $('#lineas').html(''); // Se vacía la tabla del pedido
      }
    });
    
    $( "#sucursal" ).autocomplete({
      source: function(request, response){
            arreglo = [];
            var datos;
            var id_cliente = $('#id_cliente').val();
            $.get('<?php echo site_url('ventas/clientes/get_sucursales'); ?>', { filtro: request.term, id_cliente: id_cliente, limit: 10 }, function(data) {
                datos = JSON.parse(data);
                // Se almacena el resultado en un array para devolverlo al "autocomplete"
                if (datos !== false) {
                    $.each(datos, function(i, object) {
                        object.label = object.nombre;
                        object.value = object.id;
                        arreglo.push(object);
                    });
                }
                // Se devuelve el array
                response(arreglo);
            });
      },
      select: function(event, ui){ // Cuando se selecciona un item
        event.preventDefault();  // Cancelamos el evento default
        $(this).val(ui.item.label);  // Sustituimos el valor del input con el nombre
        $('#id_sucursal').val(ui.item.id);  // Se guarda el id de la sucursal en el input #id_sucursal
        // Se muestran los datos de la sucursal
        $('#datos_sucursal').html('<strong>'+ui.item.numero+' '+ui.item.nombre+'</strong><br>'+
                ui.item.calle+' '+ui.item.numero_exterior+' '+ui.item.numero_interior+'<br>'+
                ui.item.colonia+'<br>'+
                ui.item.poblacion+', '+ui.item.municipio+', '+ui.item.estado+'<br>'+
                ui.item.telefono+'<br>');
        $('#contacto').removeAttr('disabled').focus().val('');  // Se habilita el input para contacto
        $('#datos_contacto').html('<br><br><br><br><br>');  // Se borran los datos de contacto
      }
    });
    
    $( "#contacto" ).autocomplete({
      source: function(request, response){
            arreglo = [];
            var datos;
            var id_sucursal = $('#id_sucursal').val();
            $.get('<?php echo site_url('ventas/clientes/get_contactos'); ?>', { filtro: request.term, id_sucursal: id_sucursal, limit: 10 }, function(data) {
                datos = JSON.parse(data);
                // Se almacena el resultado en un array para devolverlo al "autocomplete"
                if (datos !== false) {
                    $.each(datos, function(i, object) {
                        object.label = object.nombre;
                        object.value = object.id;
                        arreglo.push(object);
                    });
                }
                // Se devuelve el array
                response(arreglo);
            });
      },
      select: function(event, ui){ // Cuando se selecciona un item
        event.preventDefault();  // Cancelamos el evento default
        $(this).val(ui.item.label);  // Sustituimos el valor del input con el nombre
        $('#id_contacto').val(ui.item.id);
        $('#datos_contacto').html('<strong>'+ui.item.nombre+'</strong><br>'+
                ui.item.puesto+'<br>'+
                ui.item.telefono+'<br>'+
                ui.item.celular+'<br>'+
                ui.item.email+'<br>');
        $('#llenado input, #llenado textarea').removeAttr('disabled');
        $('#final select, #final textarea').removeAttr('disabled');
        $('#cantidad').focus();
      }
    });
    
    $( "#producto" ).autocomplete({
      source: function(request, response){
            arreglo = [];
            var datos;
            var id_cliente = $('#id_cliente').val();
            $.get('<?php echo site_url('ventas/clientes/get_productos'); ?>', { filtro: request.term, id_cliente: id_cliente, limit: 10 }, function(data) {
                datos = JSON.parse(data);
                // Se almacena el resultado en un array para devolverlo al "autocomplete"
                if (datos !== false) {
                    $.each(datos, function(i, object) {
                        object.label = object.producto;
                        object.value = object.id;
                        arreglo.push(object);
                    });
                }
                // Se devuelve el array
                response(arreglo);
            });
      },
      select: function(event, ui){ // Cuando se selecciona un item
        event.preventDefault();  // Cancelamos el evento default
        $(this).val(ui.item.label);  // Sustituimos el valor del input con el nombre
        $('#id_producto').val(ui.item.id);
        
        // Se obtienen las presentaciones para ese producto
        get_presentaciones(ui.item.id, null);
        
        $('#presentacion').removeAttr('disabled').focus();
      }
    });
    
    $('#presentacion').change(function(){
        var id_cliente = $('#id_cliente').val();
        $.get('<?php echo site_url('ventas/clientes/get_precio_presentacion'); ?>', { id_cliente: id_cliente, id_producto_presentacion: $(this).val() }, function(data) {
            datos = JSON.parse(data);
            // Se almacena el resultado en un array para devolverlo al "autocomplete"
            if (datos !== false) {
                $('#precio').val(datos.precio);  // Se llena el campo precio
                $('#id_producto_presentacion').val(datos.id_producto_presentacion);  // Se guarda el id de la presentación
                /*$('#codigo').val(datos.codigo);
                $('#nombre').val(datos.nombre);*/
                $('#comentarios').focus();
            }
        });
    });
    
    $('#agregar_producto').click(function(event){
        event.preventDefault();
    
        var producto = $('#producto').val();
        //var producto = $('#nombre').val();
        //var codigo = $('#codigo').val();
        var codigo = $('#presentacion option:selected').attr('codigo');
        var presentacion = $('#presentacion option:selected').text();
        var cantidad = Number($('#cantidad').val()).toFixed(2);
        var id_producto = $('#id_producto').val();
        var id_producto_presentacion = $('#id_producto_presentacion').val();
        var precio = Number($('#precio').val()).toFixed(2);
        var observaciones = $('#comentarios').val();
        var importe = Number(cantidad * precio);
        var fila = "";
        var seleccionada = false;
        
        if($.isNumeric(cantidad) && id_producto_presentacion.length > 0){
            fila = '<tr id_producto_presentacion="'+id_producto_presentacion+'" cantidad="'+cantidad+'" precio="'+precio+'" codigo="'+codigo+'" observaciones="'+observaciones+'" producto="'+producto+'" id_producto="'+id_producto+'" title="Click= editar\nDoble click= borrar">'+
            '<td style="text-align: right;">'+
            Globalize.format(cantidad,'n')+'</td><td>'+
            codigo+'</td><td>'+
            producto+'</td><td>'+
            presentacion+'</td><td style="text-align: right;">'+
            Globalize.format(precio,'n')+'</td><td style="text-align: right;">'+
            Globalize.format(importe,'n')+'</td>'+
            '</tr>';
            
            // Si hay alguna fila seleccionada se sustituye a fila
            $("#lineas tr").each(function(fila){
                if($(this).hasClass('info'))
                    seleccionada = true;
            });
            if(seleccionada){
                $('tr[class="info"]').replaceWith(fila);
            }else
                $('#lineas').append(fila);
            
            $('#producto').val('');
            $('#presentacion').html('').attr('disabled',true);
            $('#precio').val('');
            $('#comentarios').val('');
            $('#cantidad').val('');
            $('#id_producto_presentacion').val('');
            
            $(this).removeClass('btn-info').addClass('btn-inverse').html('<i class="icon-plus"></i> Agregar');
            
            calcula_totales();
        }
        $('#cantidad').focus();
    });
    
    $('#lineas').on('click','tr',function(){
        if(edicion){
            if($(this).hasClass('info')){
                $(this).removeClass('info');
                $('#cantidad').val('');
                $('#producto').val('');
                $('#precio').val('');
                $('#presentacion').html('').attr('disabled',true);
                $('#comentarios').val('');
                $('#agregar_producto').removeClass('btn-info').addClass('btn-inverse').html('<i class="icon-plus"></i> Agregar');
            }else{
                $("#lineas tr").each(function(fila){
                    $(this).removeClass('info');
                });

                $(this).addClass('info');

                $('#cantidad').val($(this).attr('cantidad'));
                $('#producto').val($(this).attr('producto'));
                $('#id_producto_presentacion').val($(this).attr('id_producto_presentacion'));

                $('#agregar_producto').removeClass('btn-inverse').addClass('btn-info').html('<i class="icon-refresh"></i> Cambiar');

                get_presentaciones($(this).attr('id_producto'), $(this).attr('id_producto_presentacion'));

                $('#presentacion').removeAttr('disabled');
                $('#presentacion').val($(this).attr('id_producto_presentacion'));
                $('#precio').val($(this).attr('precio'));
                $('#comentarios').val($(this).attr('observaciones'));
            }
            $("#cantidad").focus();
        }
    });
    
    $('#lineas').on('dblclick','tr',function(){
        if(edicion){
            if(confirm("¿Borrar linea?")){
                $(this).remove();
                calcula_totales();
            
            }
        }
    });
    
    $('#borrar').click(function(event){
        event.preventDefault();
        var confirmar = confirm("¿Deseas borrar los datos del pedido?");
        if(confirmar){
            $('#id_cliente').val('');
            $('#id_sucursal').val('');
            $('#id_contacto').val('');
            $('#cliente').val('');
            $('#sucursal').val('').attr('disabled',true);
            $('#contacto').val('').attr('disabled',true);
            $('#observaciones').val('').attr('disabled',true);
            $('#id_ruta').val('').attr('disabled',true);
            
            $('#producto').val('').attr('disabled',true);
            $('#presentacion').html('').attr('disabled',true);
            $('#precio').val('');
            $('#comentarios').val('').attr('disabled',true);
            $('#cantidad').val('').attr('disabled',true);
            $('#id_producto_presentacion').val('');
            
            $('#lineas').html('');
            $('#cliente').focus();
            
            calcula_totales();
        }
    });
    
    // Guardar el pedido
    $('#guardar').click(function(event){
        event.preventDefault();
        
        var productos = [];
        var i = new Number(0);

        $("#lineas tr").each(function(){
            if($(this).attr("id_producto_presentacion")){
                productos[i] = new Array(4);
                productos[i][0] = $(this).attr("id_producto_presentacion");
                productos[i][1] = $(this).attr("cantidad");
                productos[i][2] = $(this).attr("precio");
                productos[i][3] = $(this).attr("observaciones");
                i++;
            }
        });
        
        // Validaciones
        var id_cliente = $('#id_cliente').val();
        var id_sucursal = $('#id_sucursal').val();
        var id_contacto = $('#id_contacto').val();
        var id_ruta = $('#id_ruta').val();
        
        if(id_cliente.length > 0 && id_sucursal.length > 0 && id_contacto.length > 0 && id_ruta.length > 0 && productos.length > 0){
        
            var r = confirm('Deseas guardar el pedido?');
            if(r == true){
                
                // Si se está editando el pedido se le agrega el id al url para que el controlador haga update y no insert
                $.ajax({
                    url: "<?php echo (isset($pedido) ? site_url('ventas/pedidos/pedidos_guardar/'.$pedido->id) : site_url('ventas/pedidos/pedidos_guardar')); ?>",
                    type: 'post',
                    data: {id_cliente_sucursal: $('#id_sucursal').val(), id_contacto: $('#id_contacto').val(), id_ruta: $('#id_ruta').val(), 
                            observaciones: $('#observaciones').val(),
                            productos: productos},
                    dataType: 'text'
                }).done(function(respuesta){
                    if(respuesta == 'OK'){
                        localStorage.removeItem("ventas/pedidos/nuevo");
                        //localStorage.removeItem("compras/facturas/registro/productos");
                        window.location = "<?php echo site_url('ventas/pedidos/index'); ?>";
                    }
                }).fail(function(){
                        alert("Error al intentar guardar el pedido");
                });
            }
        }else{
            alert("Datos incompletos");
        }
    });
});
</script>
