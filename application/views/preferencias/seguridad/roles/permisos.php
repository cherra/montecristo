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
        <label class="control-label">Nombre</label>
        <div class="controls">
          <label class="control-label"><?php echo $rol->nombre; ?></label>
        </div>
      </div>
      <div class="control-group">
          <label class="control-label">Marcar todos</label>
          <div class="control-label">
              <input type="checkbox" id="marcar_todos"/>
          </div>
      </div>
      <div class="control-group">
          <?php echo $table; ?>
      </div>
      <div class="control-group">
        <div class="controls">
          <button type="submit" id="guardar" class="btn btn-primary">Guardar</button>
        </div>
      </div>
    </form>
  </div>
</div>
<div class="row-fluid">
  <div class="span6 offset3">
    <?php echo $mensaje ?>
  </div>
</div>
<script>
    $(document).ready(function(){
        $('#marcar_todos').change(function(){
            if($(this).is(':checked')){
                $('table input[type="checkbox"]').attr('checked','checked');
            }
        });
    });
</script>
