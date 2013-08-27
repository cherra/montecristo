<div class="row-fluid">
    <div class="page-header">
        <h2><?php echo $titulo; ?></h2> 
        <?php echo $link_back; ?>
    </div>
</div>
<div class="row-fluid">
  <div class="span12">
    <form name="form" id="form" action="<?php echo $action; ?>" class="form-horizontal" method="post">
      <div class="control-group">
        <label class="control-label hidden-phone" for="numero">Número</label>
        <div class="controls">
          <input type="text" name="numero" id="numero" class="required number" placeholder="Número" value="<?php echo (isset($datos->numero) ? $datos->numero : ''); ?>">
        </div>
      </div>
      <div class="control-group">
        <label class="control-label hidden-phone" for="nombre">Nombre</label>
        <div class="controls">
          <input type="text" name="nombre" id="nombre" class="required" placeholder="Nombre" value="<?php echo (isset($datos->nombre) ? $datos->nombre : ''); ?>">
        </div>
      </div>
      <div class="control-group">
        <label class="control-label hidden-phone" for="descripcion">Descripción</label>
        <div class="controls">
          <input type="text" name="descripcion" placeholder="Descripción" value="<?php echo (isset($datos->descripcion) ? $datos->descripcion : ''); ?>">
        </div>
      </div>
      <div class="control-group">
        <div class="controls">
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </div>
    </form>
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
    $('#numero').focus();
});
</script>
