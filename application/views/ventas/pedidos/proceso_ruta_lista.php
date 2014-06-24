<div class="row-fluid">
    <div class="page-header">
        <h2><?php echo $titulo; ?></h2>
        <a href="javascript:history.back(-1)" class="btn"><i class="icon-arrow-left"></i> Regresar</a>
    </div>
</div>
<div class="row-fluid">
    <?php echo form_open($action, array('class' => 'form-horizontal', 'name' => 'form', 'id' => 'form')) ?>
    <div class="control-group">
        <label class="control-label hidden-phone">Ruta</label>
        <div class="controls">
            <select id="id_ruta" class="span4">
            <option value="0">Selecciona una ruta...</option>
            <?php
            foreach($rutas as $r){ ?>
                <option value="<?php echo $r->id; ?>" <?php if(!empty($ruta->id)) echo ($ruta->id == $r->id ? 'selected' : ''); ?>><?php echo $r->nombre; ?></option>
            <?php
            }
            ?>
        </select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label hidden-phone" for="fecha">Fecha programada</label>
        <div class="controls">
          <input type="text" name="fecha" id="fecha" placeholder="Fecha programada" value="<?php if(isset($fecha)) echo $fecha; ?>" class="fecha" >
        </div>
    </div>
    <div class="control-group">
        <label class="control-label hidden-phone" for="filtro">Filtros</label>
        <div class="controls">
          <input type="text" name="filtro" id="filtro" placeholder="Filtros de busqueda" value="<?php if(isset($filtro)) echo $filtro; ?>" >
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
<div class="row-fluid">
    <div class="span12">
        <div class="data"><?php echo $table; ?></div>
    </div>
</div>
<?php } ?>
<script>
$(document).ready(function(){
    var url = "<?php echo site_url(); ?>/ventas/pedidos/pedidos_proceso_ruta";
    
    $('#id_ruta').on('change',function(){
       if($(this).val() > 0)
           $(location).attr('href',url+'/'+$(this).val());
    });
});
</script>