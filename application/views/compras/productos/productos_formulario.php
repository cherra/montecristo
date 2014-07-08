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
            <label class="control-label hidden-phone" for="id_categoria">Categoría</label>
            <div class="controls">
                <select id="categoria" name="id_categoria">
                    <?php foreach ($categorias as $categoria){?>
                        <option value="<?php echo $categoria->id; ?>" <?php echo (isset($datos->id_categoria) && $datos->id_categoria == $categoria->id ? 'selected' : ''); ?>><?php echo $categoria->nombre; ?></option>
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
            <label class="control-label hidden-phone" for="codigo">Código</label>
            <div class="controls">
              <input type="text" name="codigo" placeholder="Código" value="<?php echo (isset($datos->codigo) ? $datos->codigo : ''); ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="comprable">Comprable?</label>
            <div class="controls">
                <input type="checkbox" name="comprable" value="1" <?php if(isset($datos->comprable) && $datos->comprable == 1) echo 'checked'; ?>>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="vendible">Vendible?</label>
            <div class="controls">
                <input type="checkbox" name="vendible" value="1" <?php if(isset($datos->vendible) && $datos->vendible == 1) echo 'checked'; ?>>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="control_stock">Control de existencias?</label>
            <div class="controls">
                <input type="checkbox" name="control_stock" value="1" <?php if(isset($datos->control_stock) && $datos->control_stock == 1) echo 'checked'; ?>>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label hidden-phone" for="iva">Causa IVA?</label>
            <div class="controls">
                <input type="checkbox" name="iva" value="1" <?php if(isset($datos->iva) && $datos->iva == 1) echo 'checked'; ?>>
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
