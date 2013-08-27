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
            <label class="control-label hidden-phone" for="nombre">Nombre</label>
            <div class="controls">
              <input type="text" name="nombre" id="nombre" class="required" placeholder="Nombre" value="<?php echo (isset($datos->nombre) ? $datos->nombre : ''); ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="descripcion">Descripción</label>
            <div class="controls">
              <textarea name="descripcion" placeholder="Descripción"><?php echo (isset($datos->descripcion) ? $datos->descripcion : ''); ?></textarea>
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
    $('#nombre').focus();
});
</script>
