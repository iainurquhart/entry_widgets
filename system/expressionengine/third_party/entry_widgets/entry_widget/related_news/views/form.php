<ol>
	<li class="even">
		<label>Number of items</label>
		<?php 
			$select_options = array(3,4,5,10);
			echo form_dropdown($field_name.'[number]', $select_options, $options['number']); 
		?>
	</li>
	<li class="even">
		<label>Category</label>
		<?php 
			$select_options = array('Media Releases', 'Something Else');
			echo form_dropdown($field_name.'[category]', $select_options, $options['category']); ?>
	</li>
</ol>