<div class="row-fluid">
    <div class="page-header">
        <h2><?php echo $titulo; ?></h2> 
        <a href="javascript:history.back(-1)" class="btn"><i class="icon-arrow-left"></i> Regresar</a>
    </div>
</div>
<?php
if(isset($compra)){
?>
<div class="row-fluid">
    <div class="span2">
        <p class="lead" style="margin-bottom: 10px;"><strong>Orden de compra:</strong></p>
    </div>
    <div class="span1">
        <p class="lead text-info text-right" style="margin-bottom: 10px;"><strong><?php echo $compra->id; ?></strong></p>
    </div>
    <div class="span3">
        <p style="margin-bottom: 10px;"><button class="btn btn-warning" id="editar"><i class="icon-edit"></i> Editar</button></p>
    </div>
    <div class="offset5 span1">
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
            <!-- Cuando se edita la orden de compra se le asigna el id al input id_orden_compra -->
            <input type="hidden" id="id_orden_compra" value="<?php if(isset($compra)) echo $compra->id; ?>" />
            <input type="hidden" id="id_proveedor" value="<?php if(isset($proveedor)) echo $proveedor->id; ?>" />
            
            <label><strong>Buscar proveedor</strong></label>
            <input type="text" id="proveedor" class="input-block-level" placeholder="Proveedor" value="<?php echo (isset($proveedor) ? $proveedor->nombre : ''); ?>">
        </div>
        <div class="span3">
            <address id="datos_proveedor">
                <?php
                if(isset($proveedor)){
                    echo '<strong>'.$proveedor->nombre.'</strong><br>'.
                            $proveedor->rfc.'<br>'.
                            $proveedor->calle.' '.$proveedor->numero_exterior.' '.$proveedor->numero_interior.'<br>'.
                            $proveedor->colonia.'<br>'.
                            $proveedor->poblacion.', '.$proveedor->municipio.', '.$proveedor->estado.'<br>';
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
                <input type="text" id="cantidad" placeholder="Cantidad" class="input-block-level" <?php if(empty($compra)) echo "disabled"; ?>>
        </div>
        <div class="span4">
                <label for="producto">Producto</label>
                <input type="text" id="producto" placeholder="Producto" class="input-block-level" <?php if(empty($compra)) echo "disabled"; ?>>
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
                <input type="text" id="precio" placeholder="Precio" class="input-block-level" <?php if(empty($compra)) echo "disabled"; ?>>
        </div>
        <div class="span3">
                <label for="comentarios">Comentarios</label>
                <textarea id="comentarios" placeholder="Comentarios" rows="2" class="input-block-level" <?php if(empty($compra)) echo "disabled"; ?>></textarea>
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
        <div class="offset3 span9">
            <label for="observaciones">Observaciones</label>
            <textarea id="observaciones" placeholder="Observaciones" class="input-block-level" rows="3" <?php if(empty($compra)) echo "disabled"; ?>><?php if(isset($compra)) echo $compra->observaciones; ?></textarea>
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
                if(empty($compra)){
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
    localStorage.setItem("compras/ordenes_compra/nuevo",$('#lineas').html());  // Cuando se va a registrar una nueva orden de compra
    <?php }else{ ?>
    localStorage.setItem("compras/ordenes_compra/edicion",$('#lineas').html());  // Edición de una orden de compra existente
    <?php } ?>
}

function get_presentaciones( id_producto, id_producto_presentacion ){
    var datos;
    var id_producto = id_producto;
    var select = '<option value="">Selecciona...</option>';
    // Método Ajax para obtener presentaciones de un producto y por cliente
    $.get('<?php echo site_url('compras/productos/get_presentaciones'); ?>', { limit: 10, id_producto: id_producto }, function(data) {
        datos = JSON.parse(data);
        // Se almacena el resultado en un array para devolverlo al "autocomplete"
        if (datos !== false) {
            $.each(datos, function(i, object) {
                if(id_producto_presentacion == object.id_producto_presentacion)
                    select += '<option value="'+object.id_producto_presentacion+'" codigo="'+object.codigo+'" selected>'+object.nombre+'</option>';
                else
                    select += '<option value="'+object.id_producto_presentacion+'" codigo="'+object.codigo+'">'+object.nombre+'</option>';
            });
            $('#presentacion').html(select);
        }
    });
}
    
$(document).ready(function(){

    var edicion = true;
    <?php if(isset($compra)){ ?>
            $('input, textarea, select, button[id!="editar"]').attr('disabled',true);
            edicion = false;
    <?php }?>
        
    $('#editar').click(function(){
        $('input[id!="proveedor"], textarea, select, button').removeAttr('disabled');
        $('#autorizar').hide();
        edicion = true;
        $('#cantidad').focus();
    });
    
    $('#proveedor').focus();
    
    // Se anulan los submit de formularios con clase .no-submit
    $('form.no-submit').submit(function(event){
        event.preventDefault();
    });
    
    $(window).bind('beforeunload', function(event){
        localStorage.removeItem("compras/ordenes_compra/edicion");
    });
    
    <?php if(!isset($presentaciones)){ ?>
        if(localStorage.getItem("compras/ordenes_compra/nuevo"))
            $('#lineas').html(localStorage.getItem("compras/ordenes_compra/nuevo"));
    <?php }else{ ?>
        if(localStorage.getItem("compras/ordenes_compra/edicion"))
            $('#lineas').html(localStorage.getItem("compras/ordenes_compra/edicion"));
    <?php } ?>
        
    calcula_totales();
    
    var arreglo = new Array();
    $( "#proveedor" ).autocomplete({
      source: function(request, response){
            arreglo = [];
            var datos;
            $.get('<?php echo site_url('compras/proveedores/get_proveedores'); ?>', { filtro: request.term, limit: 10 }, function(data) {
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
        $('#id_proveedor').val(ui.item.id);  // Se guarda el id del proveedor en el input #id_proveedor
        // Se muestran los datos del proveedor
        $('#datos_proveedor').html('<strong>'+ui.item.nombre+'</strong><br>'+
                ui.item.rfc+'<br>'+
                ui.item.calle+' '+ui.item.numero_exterior+' '+ui.item.numero_interior+'<br>'+
                ui.item.colonia+'<br>'+
                ui.item.poblacion+', '+ui.item.municipio+', '+ui.item.estado+'<br>');
        $('#llenado input, #llenado textarea').removeAttr('disabled');
        $('#final select, #final textarea').removeAttr('disabled');
        $('#cantidad').focus();
      }
    });
    
    $( "#producto" ).autocomplete({
      source: function(request, response){
            arreglo = [];
            var datos;
            $.get('<?php echo site_url('compras/productos/get_productos'); ?>', { filtro: request.term, limit: 10 }, function(data) {
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
        $('#id_producto').val(ui.item.id);
        
        // Se obtienen las presentaciones para ese producto
        get_presentaciones(ui.item.id, null);
        
        $('#presentacion').removeAttr('disabled').focus();
      }
    });
    
    $('#presentacion').change(function(){
        /*$.get('<?php echo site_url('compras/productos/get_precio_presentacion'); ?>', { id_cliente: id_cliente, id_producto_presentacion: $(this).val() }, function(data) {
            datos = JSON.parse(data);
            // Se almacena el resultado en un array para devolverlo al "autocomplete"
            if (datos !== false) {
                $('#precio').val(datos.precio);  // Se llena el campo precio
                $('#id_producto_presentacion').val(datos.id_producto_presentacion);  // Se guarda el id de la presentación
               $('#comentarios').focus();
            }
        });*/
        $('#id_producto_presentacion').val($(this).val());  // Se guarda el id de la presentación
        $('#precio').focus();
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
        var confirmar = confirm("¿Deseas borrar los datos de la orden de compra?");
        if(confirmar){
            $('#id_proveedor').val('');
            $('#proveedor').val('');
            $('#observaciones').val('').attr('disabled',true);
            
            $('#producto').val('').attr('disabled',true);
            $('#presentacion').html('').attr('disabled',true);
            $('#precio').val('');
            $('#comentarios').val('').attr('disabled',true);
            $('#cantidad').val('').attr('disabled',true);
            $('#id_producto_presentacion').val('');
            
            $('#lineas').html('');
            $('#proveedor').focus();
            
            calcula_totales();
        }
    });
    
    // Guardar la orden de compra
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
        var id_proveedor = $('#id_proveedor').val();
        
        if(id_proveedor.length > 0 && productos.length > 0){
        
            var r = confirm('Deseas guardar la orden de compra?');
            if(r === true){
                
                // Si se está editando la orden de compra se le agrega el id al url para que el controlador haga update y no insert
                $.ajax({
                    url: "<?php echo (isset($compra) ? site_url('compras/ordenes_compra/ordenes_compra_guardar/'.$compra->id) : site_url('compras/ordenes_compra/ordenes_compra_guardar')); ?>",
                    type: 'post',
                    data: { id_proveedor: $('#id_proveedor').val(), 
                            observaciones: $('#observaciones').val(),
                            productos: productos},
                    dataType: 'text'
                }).done(function(respuesta){
                    if(respuesta == 'OK'){
                        localStorage.removeItem("compras/ordenes_compra/nuevo");
                        //localStorage.removeItem("compras/facturas/registro/productos");
                        window.location = "<?php echo site_url('compras/ordenes_compra/index'); ?>";
                    }
                }).fail(function(){
                        alert("Error al intentar guardar la orden de compra");
                });
            }
        }else{
            alert("Datos incompletos");
        }
    });
});
</script>
