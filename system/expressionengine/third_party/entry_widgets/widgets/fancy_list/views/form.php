<?php echo $options['script'] ?>
<ul>
	<li class="even">
		<label>List Label</label>
		<?php echo form_input($field_name.'[label]', $options['label']); ?>
	</li>
</ul>
<table class="nolan_widget_table widget_nest_sort" style="width: 80%;" border="0" cellspacing="0" cellpadding="0">
	<thead>
		<tr>
			<th style='width: 10px;'></th>
			<th>Items</th>
			<th></th>
		</tr>
	</thead>
	<tbody>

		<?php if(!$options['list_items']) : ?>
		<tr class="row">
			<td class='nolan_drag_handle'><?php echo $options['drag_handle']; ?></td>
			<td class="nolan_content_col"><?php echo form_input($field_name.'[list_items][0][item]', '') ?></td>
			<td class='nolan_nav'><?php echo $options['nav']; ?></td>
		</tr>
		<?php endif ?>

		<?php foreach ($options['list_items'] as $key => $data): ?>
		<tr class="row">
			<td class='nolan_drag_handle'><?php echo $options['drag_handle']; ?></td>
			<td class="nolan_content_col"><?php echo form_input($field_name.'[list_items]['.$key.'][item]', $data['item']) ?></td>
			<td class='nolan_nav'><?php echo $options['nav']; ?></td>
		</tr>
		<?php endforeach ?>

	</tbody>
</table>