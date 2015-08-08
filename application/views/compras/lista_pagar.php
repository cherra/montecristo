<style type="text/css">
    .fila_compra {
        cursor: pointer;
    }

    .fila_seleccionada {
        background-color: #f00;
    }
</style>
<div class="row-fluid">
    <div class="page-header">
        <h2><?php echo $titulo; ?></h2>
        <a href="javascript:history.back(-1)" class="btn"><i class="icon-arrow-left"></i> Regresar</a>
    </div>
</div>
<div class="row-fluid">
    <?php echo form_open($action, array('class' => 'form-horizontal', 'name' => 'form', 'id' => 'form')) ?>
    <div class="control-group">
        <label class="control-label hidden-phone" for="filtro">Filtros</label>
        <div class="controls">
          <input type="text" name="filtro" id="filtro" placeholder="Filtros de busqueda" value="<?php if(isset($filtro)) echo $filtro; ?>"/>
          <button type="submit" class="btn btn-primary">Buscar</button>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?php if(isset($tabs)){  ?>
<ul class="nav nav-tabs">
  <?php foreach($tabs as $tab){ ?>
  <li class="<?php if(strpos($tab, current_url())) echo 'active'; ?>"><?php echo $tab; ?></li>
  <?php } ?>
</ul>
<?php } ?>
<div class="row-fluid">
    <div class="span10">
        <div class="pagination"><?php echo $pagination; ?></div>
    </div>
    <div class="span2">
        <?php if(isset($link_add)){ ?>
        <p class="text-right"><?php echo $link_add; ?></p>
        <?php } ?>
    </div>
</div>
<?php if(isset($table)){ ?>
<?php echo form_open($action, array('id' => 'form_tabla', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data')) ?>
<div class="row-fluid">
    <div class="span12">
        <div class="data"><?php echo $table; ?></div>
    </div>
</div>
<div class="row-fluid">
    <div class="control-group">
        <label for="referencia" class="control-label">Referencia: </label>
        <div class="controls">
            <input type="text" class="input-small required" name="referencia" id="referencia" value="" />
            <input type="text" class="input-small" name="id_compra" id="id_compra" value="0" readonly="readonly" />
        </div>
    </div>
    <div class="control-group">
        <label for="tipo_pago" class="control-label">Tipo de pago: </label>
        <div class="controls">
            <select id="tipo_pago" name="tipo_pago" class="input">
                <option value="EFECTIVO">EFECTIVO</option>
                <option value="CRÉDITO">CŔÉDITO</option>
            </select>
        </div>
    </div>
    <div class="control-group">
        <label for="fecha" class="control-label">Fecha: </label>
        <div class="controls">
            <input type="text" class="input-small fecha required" name="fecha" id="fecha" value="<?php echo date('Y-m-d'); ?>" />
        </div>
    </div>
    <div class="control-group">
        <a href="#" target="_blank" style="display: none;" id="archivo">Ver Imagen</a>
    </div>
    <div class="control-group">
        <label for="foto_factura" class="control-label">Foto factura: </label>
        <div class="controls">
            <input type="file" id="foto_factura" name="foto_factura" class="input" accept="image/x-png, image/gif, image/jpeg" />
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <button type="button" id="guardar" class="btn btn-info"><i class="icon-check"></i> Guardar</button>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <div id="error"></div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>
<?php } ?>
<script>
$(document).ready(function(){
    $('#tabla_compras > tbody > tr').addClass('fila_compra');

    $('#tabla_compras > tbody > tr').click(function(e) {
        var id_compra = $(this).find('td:nth-child(2)').html();
        $('#id_compra').val(id_compra);
        $('#archivo').hide();
        a('<?php echo site_url("compras/compras/get_factura") ?>', {id: id_compra})
        .done(function (d) {
            $('#referencia').val(d.referencia);
            $('#tipo_pago').val(d.tipo_pago);
            $('#fecha').val(d.fecha);
            if (d.foto_factura == true) {
                $('#archivo').attr('href', '<?php echo site_url("compras/compras/ver_foto/"); ?>/' + id_compra);
                $('#archivo').show();
            }
        })
        .error(function() {
            alert('Ocurrió un error, intentalo de nuevo.');
            return false;
        });
    });
    
    $('#guardar').click(function(e) {
        var r;
        var msj = '';
        e.preventDefault();
        if ($('#id_compra').val() == 0) {
            alert('Selecciona una compra.');
            return false;
        }
        if ($('#referencia').val() == '') msj += '- Proporciona la referencia.';
        if (msj != '') {
            alert(msj);
            $('#referencia').focus();
            return false;
        }
        r = confirm('¿Los datos proporcionados son correctos?');
        if (!r) return false;
        $('#form_tabla').submit();
    });
});

function a(url_, params) {
    return $.ajax({
        type: "post",
        url: url_,
        dataType: 'json',
        data: params
    });
}
</script>