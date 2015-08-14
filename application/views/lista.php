<div class="row-fluid">
    <div class="page-header">
        <h2><?php echo $titulo; ?></h2>
    </div>
</div>
<div class="row-fluid">
    <?php echo form_open($action, array('class' => 'form-horizontal', 'name' => 'form', 'id' => 'form')) ?>
        <div class="control-group">
            <label class="control-label hidden-phone" for="filtro">Filtros</label>
            <div class="controls">
              <input type="text" name="filtro" id="filtro" placeholder="Filtros de busqueda" value="<?php if(isset($filtro)) echo $filtro; ?>" >
              <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </div>
    <?php echo form_close(); ?>
</div>
<?php if(isset($tabs)){  ?>
<ul class="nav nav-tabs">
  <?php foreach($tabs as $tab){ ?>
  <li class="<?php if(strpos($tab, current_url())) echo 'active'; ?>"><?php echo $tab; ?></li>
  <?php } ?>
</ul>
<?php } ?>
<div class="row-fluid">
    <div class="span10">
        <div class="pagination"><?php echo $pagination; ?></div>
    </div>
    <div class="span2">
        <?php if(isset($link_add)){ ?>
        <p class="text-right"><?php echo $link_add; ?></p>
        <?php } ?>
    </div>
</div>
<?php if(isset($table)){ ?>
<div class="row-fluid">
    <div class="span12">
        <div class="data"><?php echo $table; ?></div>
    </div>
</div>
<?php } ?>

<?php echo form_open(site_url('administracion/facturas/upload'), array('id' => 'frmFacturasUpload', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data')) ?>
<div id="modal" class="modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Guardar archivo <span id="lblArchivo"></span></h3>
    </div>
    <div class="modal-body">
        <div class="row-fluid">
            <div class="control-group">
                <label for="archivo" class="control-label">Archivo: </label>
                <div class="controls">
                    <input type="file" id="archivo" name="archivo" class="input" accept="" />
                    <input type="hidden" id="id_factura" name="id_factura" value="0" />
                    <input type="hidden" id="archivo_tipo" name="archivo_tipo" value="" />
                    <input type="hidden" id="current_url" name="current_url" value="<?php echo current_url(); ?>" />
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <button type="button" id="guardar" class="btn btn-info"><i class="icon-check"></i> Guardar</button>
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <div id="error"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>

<script>
$(document).ready(function(){
    $('a.cancelar').click(function(event){
        var confirmar = confirm("Deseas cancelar el registro?");
        if(!confirmar){
            event.preventDefault();
        }
    });
});
</script>

<?php
// si es el listado de facturas
if (strpos(base_url(uri_string()),'administracion/facturas/index') !== false) {
?>
<script type="text/javascript">
$(function () {
    $('#guardar').click(function(e) {
        e.preventDefault();
        doUpload();
    });
});

function upload(id, tipo) {
    $('#id_factura').val(id);
    if (tipo == 'pdf') {
        $('#archivo').attr('accept', 'application/pdf');
        $('#lblArchivo').html('PDF');
        $('#archivo_tipo').val('pdf');
    }
    else {
        $('#archivo').attr('accept', 'text/xml');
        $('#lblArchivo').html('XML');
        $('#archivo_tipo').val('xml');
    }
    $('#modal').modal('show');
}

function doUpload() {
    var r;
    r = confirm('¿Archivo correcto?');
    if (!r) return false;
    $('#frmFacturasUpload').submit();
}
</script>
<?php
}
?>