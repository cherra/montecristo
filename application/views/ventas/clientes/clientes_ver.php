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
    <div class="span2">
        <p><strong>Grupo:</strong></p>
    </div>
    <div class="span4">
        <p><?php echo $grupo->nombre; ?></p>
    </div>
    <div class="span2">
        <p><strong>Precio:</strong></p>
    </div>
    <div class="span4">
        <p>-</p>
    </div>
</div>
<div class="row-fluid">
    <div class="span2">
        <p><strong>Nombre:</strong></p>
    </div>
    <div class="span4">
        <p><?php echo $datos->nombre; ?></p>
    </div>
    <div class="span2">
        <p><strong>RFC:</strong></p>
    </div>
    <div class="span4">
        <p><?php echo $datos->rfc; ?></p>
    </div>
</div>
<div class="row-fluid">
    <div class="span2">
        <p><strong>Calle:</strong></p>
    </div>
    <div class="span4">
        <p><?php echo $datos->calle; ?></p>
    </div>
    <div class="span2">
        <p><strong>Número:</strong></p>
    </div>
    <div class="span4">
        <p><?php echo $datos->numero_exterior.' '.$datos->numero_interior; ?></p>
    </div>
</div>
<div class="row-fluid">
    <div class="span2">
        <p><strong>Colonia:</strong></p>
    </div>
    <div class="span4">
        <p><?php echo $datos->colonia; ?></p>
    </div>
    <div class="span2">
        <p><strong>Población:</strong></p>
    </div>
    <div class="span4">
        <p><?php echo $datos->poblacion; ?></p>
    </div>
</div>
<div class="row-fluid">
    <div class="span2">
        <p><strong>Municipio:</strong></p>
    </div>
    <div class="span4">
        <p><?php echo $datos->municipio; ?></p>
    </div>
    <div class="span2">
        <p><strong>Estado:</strong></p>
    </div>
    <div class="span4">
        <p><?php echo $datos->estado; ?></p>
    </div>
</div>
<div class="row-fluid">
    <div class="span2">
        <p><strong>C.P.:</strong></p>
    </div>
    <div class="span4">
        <p><?php echo $datos->cp; ?></p>
    </div>
    <div class="span2">
        <p><strong>Teléfono:</strong></p>
    </div>
    <div class="span4">
        <p><?php echo $datos->telefono; ?></p>
    </div>
</div>
<div class="row-fluid">
    <div class="span2">
        <p><strong>Teléfono 2:</strong></p>
    </div>
    <div class="span4">
        <p><?php echo $datos->telefono2; ?></p>
    </div>
    <div class="span2">
        <p><strong>e-mail:</strong></p>
    </div>
    <div class="span4">
        <p><?php echo $datos->email; ?></p>
    </div>
</div>