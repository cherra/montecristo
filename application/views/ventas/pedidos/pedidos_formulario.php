<div class="row-fluid">
    <div class="page-header">
        <h2><?php echo $titulo; ?></h2> 
        <?php echo $link_back; ?>
    </div>
</div>
<div class="row-fluid">
    <form class="no-submit">
        <div class="span3">
            <label><strong>Buscar cliente</strong></label>
            <input type="text" id="cliente" class="required input-block-level" placeholder="Cliente" value="<?php echo (isset($datos->cliente) ? $datos->cliente : ''); ?>">
            <input type="text" id="sucursal" class="required input-block-level" placeholder="Sucursal" value="<?php echo (isset($datos->sucursal) ? $datos->sucursal : ''); ?>" disabled>
            <input type="text" id="contacto" class="required input-block-level" placeholder="Contacto" value="<?php echo (isset($datos->contacto) ? $datos->contacto : ''); ?>" disabled>
        </div>
        <div class="span3">
            <address id="datos_cliente"><br><br><br><br><br></address>
            <button class="btn btn-small">Editar</button>
        </div>
        <div class="span3">
            <address id="datos_sucursal"><br><br><br><br><br></address>
            <button class="btn btn-small">Editar</button>
        </div>
        <div class="span3">
            <address id="datos_contacto"><br><br><br><br><br></address>
            <button class="btn btn-small">Editar</button>
        </div>
    </form>
</div>
<div class="row-fluid">
    <div class="span12">
        <hr>
    </div>
</div>
<form id="llenado" class="no-submit">
<div class="row-fluid">
    
        <div class="span1">
                <label for="cantidad">Cantidad</label>
                <input type="text" id="cantidad" placeholder="Cantidad" class="input-block-level" disabled>
        </div>
        <div class="span4">
                <label for="producto">Producto</label>
                <input type="text" id="producto" placeholder="Producto" class="input-block-level" disabled>
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
                <textarea id="comentarios" placeholder="Comentarios" rows="2" class="input-block-level" disabled></textarea>
        </div>
</div>
<div class="row-fluid">
    <div class="span12">
       
            <button class="btn btn-inverse" id="agregar_producto"><i class="icon-plus"></i> Agregar</button>
       
    </div>
</div>
</form>
<div class="row-fluid">
    <div class="span12" style="height: 220px; overflow-y: auto; overflow-x: hidden;">
        <table class="table table-condensed table-bordered table-hover table-striped">
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
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="6">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="6">&nbsp;</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <table class="table table-condensed">
            <thead>
                <tr>
                    <th class="span1" style="text-align: right;">0.00</th>
                    <th class="span2">&nbsp;</th>
                    <th class="span3">&nbsp;</th>
                    <th class="span2">&nbsp;</th>
                    <th class="span2">&nbsp;</th>
                    <th class="span2" style="text-align: right;">0.00</th>
                </th>
            </thead>
        </table>
    </div>
</div>
<form class="form-inline">
    <input type="hidden" id="id_cliente" />
    <input type="hidden" id="id_sucursal" />
    <input type="hidden" id="id_contacto" />
    <div class="row-fluid">
                <div class="span4">
                    <label for="ruta">Ruta</label>
                    <input type="text" id="ruta" placeholder="Ruta" class="input-block-level" disabled>
                </div>
                <div class="span8">
                    <label for="observaciones">Observaciones</label>
                    <textarea id="observaciones" placeholder="Observaciones" class="input-block-level" rows="3" disabled></textarea>
                </div>
    </div>
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
                <p class="text-right"><button id="borrar" class="btn btn-danger"><i class="icon-trash"></i> Borrar</button></p>
            </div>
    </div>
</form>
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
$(document).ready(function(){
    $('#cliente').focus();
    
    // Se anulan los submit de formularios con clase .no-submit
    $('form.no-submit').submit(function(event){
        event.preventDefault();
    });
    
    
    /*$( "#cliente" ).autocomplete({
      source: "<?php echo site_url('ventas/clientes/get_clientes'); ?>"
    });*/
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
        var datos;
        var id_cliente = $('#id_cliente').val();
        var id_producto = ui.item.id;
        var select = '<option value="">Selecciona...</option>';
        $.get('<?php echo site_url('ventas/clientes/get_presentaciones'); ?>', { id_cliente: id_cliente, limit: 10, id_producto: id_producto }, function(data) {
            datos = JSON.parse(data);
            // Se almacena el resultado en un array para devolverlo al "autocomplete"
            if (datos !== false) {
                $.each(datos, function(i, object) {
                    select += '<option value="'+object.id_producto_presentacion+'">'+object.presentacion+'</option>';
                });
                $('#presentacion').html(select);
            }
        });
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
    
    $('#agregar_producto').click(function(){
        var producto = $('#producto').val();
        //var producto = $('#nombre').val();
        //var codigo = $('#codigo').val();
        var codigo = '';
        var presentacion = $('#presentacion option:selected').text();
        var cantidad = Number($('#cantidad').val());
        var id_producto_presentacion = $('#id_producto_presentacion').val();
        var precio = Number($('#precio').val());
        var observaciones = $('#comentarios').val();
        var importe = Number(cantidad * precio);
        var fila = "";
        
        if($.isNumeric(cantidad) && id_producto_presentacion.length > 0){
            fila = '<tr id_producto_presentacion="'+id_producto_presentacion+'" cantidad="'+cantidad+'" precio="'+precio+'" observaciones="'+observaciones+'" producto="'+producto+'">'+
            '<td style="text-align: center;">'+
            Globalize.format(cantidad,'n')+'</td><td>'+
            codigo+'</td><td>'+
            producto+'</td><td>'+
            presentacion+'</td><td style="text-align: right;">'+
            Globalize.format(precio,'n')+'</td><td style="text-align: right;">'+
            Globalize.format(importe,'n')+'</td></tr>';
            $('#lineas').append(fila);
            
            $('#producto').val('');
            $('#presentacion').html('').attr('disabled',true);
            $('#precio').val('');
            $('#comentarios').val('');
            $('#cantidad').val('');
        }
        $('#cantidad').focus();
    });
});
</script>
