<div class="row-fluid">
  <h3><?php echo $titulo; ?></h3>
  <?php echo anchor('configuracion/seguridad/usuario_add','<li class="icon-plus"></li> Agregar', array('class' => 'btn')); ?>
  <br />
  <div class="span12">
    <div class="pagination"><?php echo $pagination; ?></div>
    <div class="data"><?php echo $table; ?></div>
  </div>
</div>