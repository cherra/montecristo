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
        <!-- <p style="margin-bottom: 10px;"><button class="btn btn-warning" id="editar"><i class="icon-edit"></i> Editar</button></p> -->
        <?php } ?>
    </div>
    <div class="offset3 span2">
        <?php if(isset($pedido)){ ?>
        <!-- <p style="margin-bottom: 10px;"><button class="btn" id="duplicar"><i class="icon-copy"></i> Duplicar</button></p> -->
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
<!--            <button class="btn btn-small">Editar</button>-->
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
<!--            <button class="btn btn-small">Editar</button>-->
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
<!--            <button class="btn btn-small">Editar</button>-->
        </div>
    </form>
</div>
<div class="row-fluid">
    <div class="span12">
        <hr>
    </div>
</div>

<div class="row-fluid">
    <div class="span12" style="height: 220px; overflow-y: auto; overflow-x: hidden;" id="tabla_container">
        <table class="table table-condensed table-bordered table-hover table-striped" id="productos">
            <thead>
                <tr>
                    <th class="span1">Cant.</th>
                    <th class="span2">Código</th>
                    <th class="span3">Producto</th>
                    <th class="span2">Presentación</th>
                    <th class="span2">Precio anterior</th>
                    <th class="span2">Precio nuevo</th>
                    <th class="span2">Importe</th>
                </th>
            </thead>
            <tbody id="lineas">
                <?php
                if(isset($presentaciones)){
                    foreach($presentaciones as $p){
                        echo '<tr id_producto_presentacion="'.$p->id_producto_presentacion.'" cantidad="'.$p->cantidad.'" precio="'.$p->precio.'" precio_nuevo="0" codigo="'.$p->codigo.'" observaciones="'.$p->observaciones.'" producto="'.$p->producto.'" id_producto="'.$p->id_producto.'" title="Click= editar Doble click= borrar">'.
                             '<td style="text-align: right;">'.$p->cantidad.'</td><td>'.$p->codigo.'</td><td>'.$p->producto.'</td><td>'.$p->presentacion.'</td><td style="text-align: right;">'.$p->precio.'</td><td style="text-align: right;">0.00</td><td style="text-align: right;">'.number_format($p->cantidad*$p->precio,2).'</td></tr>';
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
            <label for="orden_compra">Orden de compra</label>
            <input type="text" id="orden_compra" name="orden_compra" placeholder="Orden de compra" class="input-block-level" value="<?php echo !empty($pedido->orden_compra) ? $pedido->orden_compra : ''; ?>">
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
        <button id="borrar" class="btn btn-danger"><i class="icon-remove"></i> Borrar pedido reubicado</button>
    </div>
    <div class="offset8 span2">
        <p class="text-right"><?php echo $link_imprimir; ?></p>
    </div>
</div>

<script type="text/javascript">
$(function () {
    calcula_totales();
});

function calcula_totales(){
    var total = new Number(0);
    var total_cantidad = new Number(0);

    $("#lineas tr").each(function(fila) {
        total_cantidad += Number($(this).attr('cantidad'));

        // sumar si hay precio nuevo
        if (Number($(this).attr('precio_nuevo')) > 0)
            total += Number($(this).attr('cantidad') * $(this).attr('precio_nuevo'));
        else
            total += Number($(this).attr('cantidad') * $(this).attr('precio'));
    });
    $('#total').html(Globalize.format(total,'n'));
    $('#total_cantidad').html(Globalize.format(total_cantidad,'n'));

    // Recorrer el scroll hasta el fondo
    $("#tabla_container").animate({scrollTop:$("#tabla_container")[0].scrollHeight}, 1000);
}
</script>