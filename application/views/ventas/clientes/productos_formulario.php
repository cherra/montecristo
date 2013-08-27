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
            <label class="control-label hidden-phone" for="producto">Alias nombre</label>
            <div class="controls">
              <input type="text" name="producto" id="producto" placeholder="Alias nombre" value="<?php echo (isset($datos->producto) ? $datos->producto : ''); ?>">
              <span class="help-inline"><?php echo (isset($producto) ? $producto->nombre : ''); ?></span>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="presentacion">Alias presentación</label>
            <div class="controls">
              <input type="text" name="presentacion" id="presentacion" placeholder="Alias presentación" value="<?php echo (isset($datos->presentacion) ? $datos->presentacion : ''); ?>">
              <span class="help-inline"><?php echo (isset($presentacion) ? $presentacion->nombre : ''); ?></span>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="codigo">Alias código</label>
            <div class="controls">
              <input type="text" name="codigo" placeholder="Alias código" value="<?php echo (isset($datos->codigo) ? $datos->codigo : ''); ?>">
              <span class="help-inline"><?php echo (isset($producto_presentacion) ? $producto_presentacion->codigo : ''); ?></span>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="sku">Alias SKU</label>
            <div class="controls">
              <input type="text" name="sku" placeholder="Alias SKU" value="<?php echo (isset($datos->sku) ? $datos->sku : ''); ?>">
              <span class="help-inline"><?php echo (isset($producto_presentacion) ? $producto_presentacion->sku : ''); ?></span>
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
              <button type="submit" class="btn btn-primary">Guardar</button>
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
    $('#producto').focus();
});
</script>
