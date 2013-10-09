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
            <!--<input type="hidden" id="id_cliente" value="<?php echo $cliente->id; ?>" />
            <input type="text" readonly value="<?php echo $cliente->nombre; ?>" />-->
            <select id="id_cliente">
                <option value="">Selecciona un cliente...</option>
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
        <label class="control-label hidden-phone">Estado</label>
        <div class="controls">
            <select id="estado">
                <option value="">Selecciona un estado...</option>
                <?php
                foreach($estados as $e){ ?>
                    <option value="<?php echo trim($e->estado); ?>" <?php if(!empty($estado)) echo ($estado == trim($e->estado) ? 'selected' : ''); ?>><?php echo trim($e->estado); ?></option>
                <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label hidden-phone">Sucursal</label>
        <div class="controls">
            <select id="id_sucursal" <?php if(empty($cliente)) echo "disabled"; ?>>
                <option value="">Selecciona una sucursal...</option>
                <?php
                foreach($sucursales as $s){ ?>
                    <option value="<?php echo $s->id; ?>" <?php if(!empty($sucursal->id)) echo ($sucursal->id == $s->id ? 'selected' : ''); ?>><?php echo $s->nombre; ?></option>
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
    var url = "<?php echo site_url(); ?>/ventas/clientes/contactos";
    
    $('#id_cliente').on('change',function(){
       if($(this).val() > 0)
           $(location).attr('href',url+'/'+$(this).val());
    });
    
    $('#estado').on('change',function(){
       if($(this).length > 0)
           $(location).attr('href',url+'/'+$('#id_cliente').val()+'/'+$(this).val());
    });
   
    $('#id_sucursal').on('change',function(){
       if($(this).val() > 0)
           $(location).attr('href',url+'/'+$('#id_cliente').val()+'/'+$('#estado').val()+'/'+$(this).val());
    });
});
</script>
