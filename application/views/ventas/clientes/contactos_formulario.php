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
            <label class="control-label hidden-phone" for="nombre">Nombre</label>
            <div class="controls">
              <input type="text" name="nombre" id="nombre" class="required" placeholder="Nombre" value="<?php echo (isset($datos->nombre) ? $datos->nombre : ''); ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="puesto">Puesto</label>
            <div class="controls">
              <input type="text" name="puesto" placeholder="Puesto" value="<?php echo (isset($datos->puesto) ? $datos->puesto : ''); ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="telefono">Teléfono</label>
            <div class="controls">
              <input type="text" name="telefono" placeholder="Teléfono" value="<?php echo (isset($datos->telefono) ? $datos->telefono : ''); ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="celular">Celular</label>
            <div class="controls">
              <input type="text" name="celular" placeholder="Celular" value="<?php echo (isset($datos->celular) ? $datos->celular : ''); ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="email">E-mail</label>
            <div class="controls">
              <input type="text" name="email" placeholder="E-mail" value="<?php echo (isset($datos->email) ? $datos->email : ''); ?>">
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
