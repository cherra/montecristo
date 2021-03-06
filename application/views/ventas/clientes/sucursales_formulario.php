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
              <input type="text" id="cliente" class="required" placeholder="Cliente" value="<?php echo (isset($cliente) ? $cliente->nombre : ''); ?>" disabled>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="numero">Número</label>
            <div class="controls">
              <input type="text" name="numero" id="numero" class="required" placeholder="Número" value="<?php echo (isset($datos->numero) ? $datos->numero : ''); ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="nombre">Nombre</label>
            <div class="controls">
              <input type="text" name="nombre" id="nombre" class="required" placeholder="Nombre" value="<?php echo (isset($datos->nombre) ? $datos->nombre : ''); ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="calle">Calle</label>
            <div class="controls">
              <input type="text" name="calle" placeholder="Calle" value="<?php echo (isset($datos->calle) ? $datos->calle : ''); ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="numero_exterior">Núm. Exterior</label>
            <div class="controls">
              <input type="text" name="numero_exterior" placeholder="Núm. Exterior" value="<?php echo (isset($datos->numero_exterior) ? $datos->numero_exterior : ''); ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="numero_interior">Núm. Interior</label>
            <div class="controls">
              <input type="text" name="numero_interior" placeholder="Núm. Interior" value="<?php echo (isset($datos->numero_interior) ? $datos->numero_interior : ''); ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="colonia">Colonia</label>
            <div class="controls">
              <input type="text" name="colonia" placeholder="Colonia" value="<?php echo (isset($datos->colonia) ? $datos->colonia : ''); ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="poblacion">Población</label>
            <div class="controls">
              <input type="text" name="poblacion" placeholder="Población" value="<?php echo (isset($datos->poblacion) ? $datos->poblacion : ''); ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="municipio">Municipio</label>
            <div class="controls">
              <input type="text" name="municipio" placeholder="Municipio" value="<?php echo (isset($datos->municipio) ? $datos->municipio : ''); ?>" class="required">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="estado">Estado</label>
            <div class="controls">
              <input type="text" name="estado" placeholder="Estado" value="<?php echo (isset($datos->estado) ? $datos->estado : ''); ?>" class="required">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="cp">C.P.</label>
            <div class="controls">
              <input type="text" name="cp" placeholder="C.P." value="<?php echo (isset($datos->cp) ? $datos->cp : ''); ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="telefono">Teléfono</label>
            <div class="controls">
              <input type="text" name="telefono" placeholder="Teléfono" value="<?php echo (isset($datos->telefono) ? $datos->telefono : ''); ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="telefono2">Teléfono 2</label>
            <div class="controls">
              <input type="text" name="telefono2" placeholder="Teléfono 2" value="<?php echo (isset($datos->telefono2) ? $datos->telefono2 : ''); ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="telefono3">Teléfono 3</label>
            <div class="controls">
              <input type="text" name="telefono3" placeholder="Teléfono 3" value="<?php echo (isset($datos->telefono3) ? $datos->telefono3 : ''); ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="telefono4">Teléfono 4</label>
            <div class="controls">
              <input type="text" name="telefono4" placeholder="Teléfono 4" value="<?php echo (isset($datos->telefono4) ? $datos->telefono4 : ''); ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="telefono5">Teléfono 5</label>
            <div class="controls">
              <input type="text" name="telefono5" placeholder="Teléfono 5" value="<?php echo (isset($datos->telefono5) ? $datos->telefono5 : ''); ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="telefono6">Teléfono 6</label>
            <div class="controls">
              <input type="text" name="telefono6" placeholder="Teléfono 6" value="<?php echo (isset($datos->telefono6) ? $datos->telefono6 : ''); ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="telefono7">Teléfono 7</label>
            <div class="controls">
              <input type="text" name="telefono7" placeholder="Teléfono 7" value="<?php echo (isset($datos->telefono7) ? $datos->telefono7 : ''); ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="telefono8">Teléfono 8</label>
            <div class="controls">
              <input type="text" name="telefono8" placeholder="Teléfono 8" value="<?php echo (isset($datos->telefono8) ? $datos->telefono8 : ''); ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="telefono9">Teléfono 9</label>
            <div class="controls">
              <input type="text" name="telefono9" placeholder="Teléfono 9" value="<?php echo (isset($datos->telefono9) ? $datos->telefono9 : ''); ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="email">E-mail</label>
            <div class="controls">
              <input type="text" name="email" placeholder="E-mail" value="<?php echo (isset($datos->email) ? $datos->email : ''); ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="tipo">Tipo</label>
            <div class="controls">
              <input type="text" name="tipo" placeholder="Tipo" value="<?php echo (isset($datos->tipo) ? $datos->tipo : ''); ?>">
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
    $('#numero').focus();
});
</script>
