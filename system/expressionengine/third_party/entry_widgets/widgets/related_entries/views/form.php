<table>
	<tbody>
	<tr>
		<td>
		<label>Number of Entries</label>
		<?php 
			$select_options = array(
				1 => 1,
				3 => 3,
				5 => 5,
				10 => 10
			);
			echo form_dropdown($field_name.'[limit]', $select_options, $options['limit']); 
		?>
	</td>
	<td>
		<label>Channel</label>
		<?php 
			echo form_dropdown($field_name.'[channel_name]', $options['channel_select'], $options['channel_name']); ?>
	</td>
	<td>
		<label>Show Future Entries</label>
		<?php 
			echo form_dropdown($field_name.'[show_future_entries]', $options['show_future_entries_select'], $options['show_future_entries']); ?>
	</td>
	<td>
		<label>Categories</label>
		<?php 
			echo form_input($field_name.'[categories]', $options['categories']); ?>
	</td>
</tr>
</tbody>
</table>