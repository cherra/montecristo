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
            <select id="id_cliente">
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
        <label class="control-label hidden-phone">Estado</label>
        <div class="controls">
            <select id="estado" <?php if(empty($cliente)) echo "disabled"; ?>>
                <option value=" ">Selecciona un estado...</option>
                <?php
                foreach($estados as $e){ ?>
                    <option value="<?php echo urlencode(trim($e->estado)); ?>" <?php if(!empty($estado)) echo ($estado == trim($e->estado) ? 'selected' : ''); ?>><?php echo trim($e->estado); ?></option>
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

<div class="row-fluid">
    <div class="span10">
        <?php if(isset($pagination)){ ?>
        <div class="pagination"><?php echo $pagination; ?></div>
        <?php } ?>
    </div>
    <div class="span2">
        <?php if(isset($link_add)){ ?>
        <p class="text-right"><?php echo $link_add; ?></p>
        <?php } ?>
    </div>
</div>
<?php if(isset($pagination)){ ?>
<div class="row-fluid">
    <div class="span12">
        <div class="data"><?php echo $table; ?></div>
    </div>
</div>
<?php } ?>
<div id="info_llamada" class="modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Llamada</h3>
    </div>
    <div class="modal-body">
        <p>Fecha: <span id="fecha"></span></p>
        <p>Usuario: <span id="usuario"></span></p>
        <p>Sucursal: <span id="sucursal"></span></p>
        <p>Contacto: <span id="contacto"></span></p>
        <p>Observaciones: <span id="observaciones"></span></p>
        <p>Pedido: <span id="pedido"></span></p>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal">Cerrar</a>
<!--        <a href="#" class="btn btn-primary">Save changes</a>-->
    </div>
</div>
<script>
$(document).ready(function(){
    var url = "<?php echo site_url(); ?>/ventas/clientes/sucursales";
   
    $('#id_cliente').on('change',function(){
       if($(this).val() > 0)
           $(location).attr('href',url+'/'+$(this).val());
    });
    
    $('#estado').on('change',function(){
       //if($(this).val() > 0)
           $(location).attr('href',url+'/'+$('#id_cliente').val()+'/'+$(this).val());
    });
    
    $('a[href="#info_llamada"]').click(function(){
        $('#usuario').text($(this).attr('usuario'));
        $('#fecha').text($(this).attr('fecha'));
        $('#sucursal').text($(this).attr('sucursal'));
        $('#observaciones').text($(this).attr('observaciones'));
        $('#contacto').text($(this).attr('contacto'));
        if($(this).attr('id_pedido') !== '0'){
            $('#pedido').text($(this).attr('id_pedido'));
        }
    });
});
</script>
