<?php echo $options['script'] ?>
<ul>
	<li class="even">
		<label>List Label</label>
		<?php echo form_input($field_name.'[label]', $options['label']); ?>
	</li>
</ul>
<table class="nolan_widget_table widget_nest_sort entry_widget_notop"  border="0" cellspacing="0" cellpadding="0">
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



<table style="margin-top: 10px;" class="mainTable entry_widget_notop" border="0" cellspacing="0" cellpadding="0" data-index="0"> 
	<tbody>
		<tr>
			<td><label>Layout Type</label>
				<?php echo form_dropdown($field_name.'[type]', $options['type_options'], $options['type']); ?>
			</td>
			<td><label>Bullet Type</label>
				<?php echo form_dropdown($field_name.'[bullet_type]', $options['bullet_type_options'], $options['bullet_type']); ?>
			</td>
			<td><label>Additional CSS Classes</label>
				<?php echo form_input($field_name.'[css_classes]', $options['css_classes']); ?>
			</td>
		</tr>
	</tbody>
</table>