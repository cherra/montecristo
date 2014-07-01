<div class="row-fluid">
    <div class="page-header">
        <h2><?php echo $titulo; ?></h2>
        <?php echo $link_back; ?>
    </div>
</div>
<?php if(isset($tabs)){  ?>
<div class="row-fluid">
<ul class="nav nav-tabs">
  <?php foreach($tabs as $tab){ ?>
  <li class="<?php if(strpos($tab, current_url())) echo 'active'; ?>"><?php echo $tab; ?></li>
  <?php } ?>
</ul>
</div>
<?php } ?>
<div class="row-fluid">
  <div class="span12">
    <?php echo form_open($action, array('class' => 'form-horizontal', 'name' => 'form', 'id' => 'form')) ?>
        <div class="control-group">
            <label class="control-label hidden-phone" for="id_grupo">Grupo</label>
            <div class="controls">
                <select id="grupo" name="id_grupo" class="required">
                    <option value="">Selecciona un grupo...</option>
                    <?php foreach ($grupos as $grupo){?>
                        <option value="<?php echo $grupo->id; ?>" <?php echo (isset($datos->id_grupo) && $datos->id_grupo == $grupo->id ? 'selected' : ''); ?>><?php echo $grupo->nombre; ?></option>
                    <?php }?>
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="id_lista">Lista de precios</label>
            <div class="controls">
                <select id="grupo" name="id_lista" class="required">
                    <option value="">Selecciona una lista...</option>
                    <?php foreach ($listas as $lista){?>
                        <option value="<?php echo $lista->id; ?>" <?php echo (isset($datos->id_lista) && $datos->id_lista == $lista->id ? 'selected' : ''); ?>><?php echo $lista->nombre; ?></option>
                    <?php }?>
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="nombre">Nombre</label>
            <div class="controls">
              <input type="text" name="nombre" id="nombre" class="required" placeholder="Nombre" value="<?php echo (isset($datos->nombre) ? $datos->nombre : ''); ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="rfc">RFC</label>
            <div class="controls">
              <input type="text" name="rfc" placeholder="RFC" value="<?php echo (isset($datos->rfc) ? $datos->rfc : ''); ?>">
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
              <input type="text" name="municipio" placeholder="Municipio" value="<?php echo (isset($datos->municipio) ? $datos->municipio : ''); ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="estado">Estado</label>
            <div class="controls">
              <input type="text" name="estado" placeholder="Estado" value="<?php echo (isset($datos->estado) ? $datos->estado : ''); ?>">
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
            <label class="control-label hidden-phone" for="email">E-mail</label>
            <div class="controls">
              <input type="text" name="email" placeholder="E-mail" value="<?php echo (isset($datos->email) ? $datos->email : ''); ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="precio_incremento">Incremento de precio</label>
            <div class="controls">
              <input type="text" name="precio_incremento" placeholder="% de incremento en el precio" value="<?php echo (isset($datos->precio_incremento) ? $datos->precio_incremento : ''); ?>">
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
