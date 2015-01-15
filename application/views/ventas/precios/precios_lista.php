<div class="row-fluid">
    <div class="page-header">
        <h2><?php echo $titulo; ?></h2>
        <?php echo $link_back; ?>
    </div>
</div>
<div class="row-fluid">
    <?php echo form_open($action, array('class' => 'form-horizontal', 'name' => 'form', 'id' => 'form')) ?>
    <div class="control-group">
        <label class="control-label hidden-phone">Lista</label>
        <div class="controls">
            <select id="id_lista" class="span4">
                <option value="0">Selecciona una lista...</option>
                <?php
                foreach($listas as $l){ ?>
                    <option value="<?php echo $l->id; ?>" <?php if(!empty($lista->id)) echo ($lista->id == $l->id ? 'selected' : ''); ?>><?php echo $l->nombre; ?></option>
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
<?php if(isset($tabs)){  ?>
<ul class="nav nav-tabs">
  <?php foreach($tabs as $tab){ ?>
  <li class="<?php if(strpos($tab, current_url())) echo 'active'; ?>"><?php echo $tab; ?></li>
  <?php } ?>
</ul>
<?php } ?>
<?php if(isset($pagination)){ ?>
<div class="row-fluid">
    <div class="span8">
        <div class="pagination"><?php echo $pagination; ?></div>
    </div>
    <div class="span2">
        <?php if(isset($link_add)){ ?>
        <p class="text-right"><?php echo $link_add; ?></p>
        <?php } ?>
    </div>
    <div class="span2">
        <?php if(isset($link_exportar)){ ?>
        <p class="text-right"><?php echo $link_exportar; ?></p>
        <?php } ?>
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <?php echo form_open($action, array('class' => 'form-horizontal', 'name' => 'form', 'id' => 'form')) ?>
        <div class="data"><?php echo $table; ?></div>
        <div class="offset8 span4"><button type="submit" class="btn btn-info">Guardar</button> </div>
        <?php echo form_close(); ?>
    </div>
</div>
<?php } ?>
<script>
$(document).ready(function(){
    var url = "<?php echo site_url(); ?>/ventas/precios/index";
   
    $('#id_lista').on('change',function(){
       if($(this).val() > 0)
           $(location).attr('href',url+'/'+$(this).val());
    });
    
    $('tbody').on('click','button',function(){
        var id = $(this).attr('id_producto_presentacion');
        var input = $('#'+id);
        if(input.attr('disabled') || input.attr('readonly')){
            $(this).removeClass('btn-info').addClass('btn-inverse');
            input.removeAttr('disabled');
            input.removeAttr('readonly');
            input.focus();
        }else{
            input.attr('readonly',true);
            $(this).removeClass('btn-inverse').addClass('btn-info');
        }
    });
});
</script>