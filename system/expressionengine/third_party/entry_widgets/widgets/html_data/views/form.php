<ul>
	<li class="even">
		<label>HTML</label>
		<?php 
			echo form_textarea(array(
				'name'  => $field_name.'[html_data]',
				'value' => $options['html_data'])
			); 
		?>
	</li>
</ul>