<div class="row-fluid">
  <div class="page-header">
    <h2><?php echo $titulo; ?></h2>
      <?php echo $link_back; ?>
    </div>
</div>

<div class="row-fluid">
    <div class="span12">
        <table class="table table-condensed">
            <tr class="info">
                <td><strong>Categoría</strong></td>
                <td><strong>Poducto</strong></td>
                <td><strong>Código</strong></td>
            </tr>
            <?php if(!empty($producto)){ ?>
            <tr>
                <td><?php echo (!empty($categoria)) ? $categoria->nombre : ''; ?></td>
                <td><?php echo $producto->nombre; ?></td>
                <td><?php echo $producto->codigo; ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>
</div>

<?php echo form_open($action, array('class' => 'form-horizontal')); ?>
    <input type="hidden" id="id_producto" name="id_producto" value="<?php echo $producto->id; ?>"/>
    <div class="control-group">
        <label class="control-label" for="id_presentacion">Presentación</label>
        <div class="controls">
            <select id="id_presentacion" name="id_presentacion" class="required">
                <option value="">Selecciona una presentación...</option>
                <?php
                foreach($presentaciones as $p){ ?>
                    <option value="<?php echo $p->id; ?>" <?php if(!empty($presentacion)) echo ($presentacion->id == $p->id ? 'selected' : ''); ?>><?php echo $p->nombre; ?></option>
                <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="sku">SKU</label>
        <div class="controls">
            <input type="text" name="sku" id="sku" placeholder="SKU" <?php if(empty($presentacion)) echo "readonly"; ?>/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="peso">Peso</label>
        <div class="controls">
            <input type="text" name="peso" id="peso" placeholder="Peso" <?php if(empty($presentacion)) echo "readonly"; ?>/>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <button type="submit" class="btn btn-primary">Agregar</button>
        </div>
    </div>
<?php echo form_close(); ?>



<div class="row-fluid">
    <div class="span12">
        <div class="data"><?php echo $table; ?></div>
    </div>
</div>

<script type="text/javascript">
$(function () {
        
        var url = "<?php echo site_url('compras/productos/productos_presentaciones'); ?>";
        $('#id_presentacion').change(function(){
            //if($(this).val() > 0)
                $(location).attr('href',url+'/'+$('#id_producto').val()+'/'+$(this).val());
        });
        
        $('#codigo').focus();
});
</script>