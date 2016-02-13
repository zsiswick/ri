<div class="row">
  <div class="large-12 columns text-center"><h1>Welcome to Ruby Invoice</h1><h4>( simple invoicing for designers )</h4></div>
</div>
<div class="row">
	<div class="large-12 columns text-right">
		<a href="<?php echo base_url(); ?>index.php/invoices/create">Create New Invoice</a>
	</div>
</div>
<div class="row">
	<div class="large-12 columns">
		<h5><?php echo $title ?></h5>
		<table class="invoice-list">
		<thead>
			<tr>
				<th width="200"></th>
				<th>Date</th>
				<th width="150">Client</th>
				<th width="150">Amount</th>
				<th>Status</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($invoices as $invoice_item): ?>
				<tr>
					<td><a href="<?php echo base_url(); ?>index.php/invoices/edit/<?php echo $invoice_item['id'] ?>">Edit</a></td>
					<td><?php echo $invoice_item['date'] ?></td>
					<td><?php echo $invoice_item['client'] ?></td>
					<td><?php echo $invoice_item['amount'] ?></td>
					<td><?php echo $invoice_item['status'] ?></td>
				</tr>
			<?php endforeach ?>
		</tbody>
		</table>
	</div>
</div>