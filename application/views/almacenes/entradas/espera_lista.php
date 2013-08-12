<div class="row-fluid">
    <div class="page-header">
        <h2><?php echo $titulo; ?></h2>
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
<?php echo form_open($action, array('id' => 'form_tabla', 'class' => 'form-horizontal')) ?>
<div class="row-fluid">
    <div class="span2">
        <input type="checkbox" id="marcar_todos"/> Marcar todos
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="data"><?php echo $table; ?></div>
    </div>
</div>
<div class="row-fluid">
    <div class="control-group">
        <label class="control-label" for="id_almacen">Entrada a: </label>
        <div class="controls">
            <select name="id_almacen" class="required">
                <option value="">Selecciona un almacén...</option>
                <?php foreach($almacenes AS $a){ ?>
                <option value="<?php echo $a->id; ?>"><?php echo $a->nombre; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="control-group">
        <label for="fecha_programada" class="control-label">Fecha de recepción: </label>
        <div class="controls">
            <input type="text" class="input-small fecha required" name="fecha_programada" value="<?php if(isset($fecha_programada)) echo $fecha_programada; ?>" />
        </div>
    </div>
    <div class="control-group">
        <label for="hora_programada" class="control-label">Hora: </label>
        <div class="controls">
            <input type="text" class="input-small hora required" name="hora_programada" value="<?php if(isset($hora_programada)) echo $hora_programada; ?>" />
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <button type="submit" class="btn btn-info"><i class="icon-download-alt"></i> Recibida(s)</button>
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
    
    // Validación del formulario
    $('#form_tabla').validate({
        rules:{
            "compras[]":{
                required: true
            }
        },
        messages: {
            "compras[]": "Es necesario seleccionar al menos una orden de entrada"
        },
        errorPlacement: function(error, element) {
            if (element.attr("name") == "entradas[]") {
              $('#error').html(error);
              //error.html("form");
            } else {
              error.insertAfter(element);
            }
        },
        highlight: function(element, errorClass) {
            $(element).fadeOut(function() {
              $(element).fadeIn();
            });
        },
        submitHandler: function(form){
            if(confirm("¿Se han recibido todos los productos?")){
                form.submit();
            }
        }
    });
    
    //var url = "<?php echo site_url(); ?>/almacenes/entradas/ordenes_entrada_espera";
        
    $('#marcar_todos').change(function(){
        if($(this).is(':checked')){
            $('table input[type="checkbox"]').prop('checked', true);
        }else{
            $('table input[type="checkbox"]').prop('checked', false);
        }
    });
});
</script>