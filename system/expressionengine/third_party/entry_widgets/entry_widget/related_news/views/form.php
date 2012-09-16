<ol>
	<li class="even">
		<label>Number of items</label>
		<?php 
			$select_options = array(3=>3,4=>4,5=>5,10=>10);
			echo form_dropdown($field_name.'[number]', $select_options, $options['number']); 
		?>
	</li>
	<li class="even">
		<label>Category</label>
		<?php 
			$select_options = array(100=>'Media Releases', 200=>'Something Else');
			echo form_dropdown($field_name.'[category]', $select_options, $options['category']); ?>
	</li>
</ol>