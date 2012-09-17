<ol>

	<li class="even">
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
	</li>
	<li class="even">
		<label>Channel</label>
		<?php 
			echo form_dropdown($field_name.'[channel_id]', $options['channel_select'], $options['channel_id']); ?>
	</li>
</ol>