<div class="row-fluid">
    <div class="page-header">
        <h2><?php echo $titulo; ?></h2> 
        <?php echo $link_back; ?>
    </div>
</div>
<div class="row-fluid">
  <div class="span12">
    <?php echo form_open($action, array('class' => 'form-horizontal', 'name' => 'form', 'id' => 'form')) ?>
        <div class="control-group">
            <label class="control-label hidden-phone" for="cliente">Cliente</label>
            <div class="controls">
              <input type="text" id="cliente" class="required" placeholder="Cliente" value="<?php echo (isset($cliente) ? $cliente->nombre : ''); ?>" readonly>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="cliente_sucursal">Sucursal</label>
            <div class="controls">
              <input type="text" id="cliente_sucursal" class="required" placeholder="Sucursal" value="<?php echo (isset($sucursal) ? $sucursal->nombre : ''); ?>" readonly>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="cliente_sucursal_contacto">Contacto</label>
            <div class="controls">
              <input type="text" id="cliente_sucursal_contacto" class="required" placeholder="Contacto" value="<?php echo (isset($contacto->nombre) ? $contacto->nombre : ''); ?>" readonly>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="puesto">Puesto</label>
            <div class="controls">
              <input type="text" placeholder="Puesto" value="<?php echo (isset($contacto->puesto) ? $contacto->puesto : ''); ?>" readonly>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="fecha">Fecha</label>
            <div class="controls">
              <input type="text" name="fecha" id="fecha" class="fecha" placeholder="Fecha" value="<?php echo (isset($datos->fecha) ? $datos->fecha : ''); ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="hora">Hora</label>
            <div class="controls">
              <input type="text" name="hora" id="hora" class="hora" placeholder="Hora" value="<?php echo (isset($datos->hora) ? $datos->hora : ''); ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="comentarios">Comentarios</label>
            <div class="controls">
                <textarea rows="3" name="comentarios" placeholder="Comentarios"><?php echo (isset($datos->comentarios) ? $datos->comentarios : ''); ?></textarea>
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <input type="hidden" name="pedido" id="pedido" value="0" />
              <button type="submit" class="btn btn-primary">Guardar</button>
              <button type="button" id="btn_pedido" class="btn btn-success">Pedido</button>
            </div>
        </div>
    <?php echo form_close(); ?>
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
$(document).ready(function(){
    $('#nombre').focus();
    
    $('#btn_pedido').click(function(){
        $('#pedido').val('1');
        $('form').submit();
    });
});
</script>
