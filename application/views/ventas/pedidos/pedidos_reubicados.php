<div class="row-fluid">
    <div class="page-header">
        <h2>Pedidos <small>reubicados</small></h2> 
        <a href="javascript:history.back(-1)" class="btn"><i class="icon-arrow-left"></i> Regresar</a>
    </div>
</div>

<div class="row-fluid">
	<div class="span10">&nbsp;</div>
	<div class="span2 text-right">
		<?php echo $link_add; ?>
	</div>
</div>

<div class="row-fluid">
	<div class="span12">
		<table class="<?php echo $this->config->item('tabla_css'); ?>">
			<thead>
				<tr>
					<th>Núm.</th>
					<th>Pedido</th>
					<th>Cliente</th>
					<th>Sucursal</th>
					<th>Municipio</th>
					<th>Estado</th>
					<th>Vendedor</th>
					<th>Piezas</th>
					<th>Total</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($pedidos_reubicados as $p): ?>
				<tr>
					<td><?php echo $p->id; ?></td>
					<td><?php echo $p->id_pedido; ?></td>
					<td><?php echo $p->cliente; ?></td>
					<td><?php echo $p->sucursal; ?></td>
					<td><?php echo $p->municipio; ?></td>
					<td><?php echo $p->estado; ?></td>
					<td><?php echo $p->vendedor; ?></td>
					<td><?php echo number_format($p->piezas, 2); ?></td>
					<td><?php echo number_format($p->total, 2); ?></td>
					<td>
						<a href="<?php echo site_url('ventas/pedidos/pedido_reubicado/' . $p->id); ?>" class="btn btn-small" title="Ver Pedido Reubicado">
							<i class="icon-eye-open"></i>
						</a>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>