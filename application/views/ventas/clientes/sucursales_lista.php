<div class="row-fluid">
    <div class="page-header">
        <h2><?php echo $titulo; ?></h2>
        <?php echo $link_back; ?>
    </div>
</div>
<div class="row-fluid">
    <?php echo form_open($action, array('class' => 'form-horizontal', 'name' => 'form', 'id' => 'form')) ?>
    <div class="control-group">
        <label class="control-label hidden-phone">Cliente</label>
        <div class="controls">
            <select id="id_cliente" class="span4">
            <option value="0">Selecciona un cliente...</option>
            <?php
            foreach($clientes as $c){ ?>
                <option value="<?php echo $c->id; ?>" <?php if(!empty($cliente->id)) echo ($cliente->id == $c->id ? 'selected' : ''); ?>><?php echo $c->nombre; ?></option>
            <?php
            }
            ?>
        </select>
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
<?php if(isset($pagination)){ ?>
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
<div class="row-fluid">
    <div class="span12">
        <div class="data"><?php echo $table; ?></div>
    </div>
</div>
<?php } ?>

<script>
$(document).ready(function(){
    var url = "<?php echo site_url(); ?>/ventas/clientes/sucursales";
   
    $('#id_cliente').on('change',function(){
       if($(this).val() > 0)
           $(location).attr('href',url+'/'+$(this).val());
    });
});
</script>
