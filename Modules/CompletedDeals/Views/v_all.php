<div>
	<table>
		<thead>
			<tr>
				<th>Client Id</th>
				<th>Client Name</th>
				<th>Summ of closed leads</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($leadsData as $data): ?>
				<tr>
					<td><?=$data['id'] ?></td>
					<td><?=$data['name'] ?></td>
					<td><?=$data['sum']?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
		<tfoot>
			<tr class="total-row">
					<td colspan="2">Total summ:</td>
					<td><?=$total_sum ?></td>
			</tr>
		</tfoot>
	</table>
</div>