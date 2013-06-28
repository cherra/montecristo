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
        <label class="control-label hidden-phone" for="nombre">Nombre</label>
        <div class="controls">
          <input type="text" name="nombre" id="nombre" class="required" placeholder="Nombre" value="<?php echo (isset($datos->nombre) ? $datos->nombre : ''); ?>">
        </div>
      </div>
      <div class="control-group">
        <label class="control-label hidden-phone" for="codigo">Código</label>
        <div class="controls">
          <input type="text" name="codigo" placeholder="Código" value="<?php echo (isset($datos->codigo) ? $datos->codigo : ''); ?>">
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
    $('#nombre').focus();
});
</script>