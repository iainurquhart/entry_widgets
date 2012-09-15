<ol>
	<li class="even">
		<label>Feed URL</label>
		<?php echo form_input($field_name.'[feed_url]', $options['feed_url']); ?>
	</li>
	<li class="even">
		<label>Number of items</label>
		<?php echo form_input($field_name.'[number]', $options['number']); ?>
	</li>
	<li class="even">
		<label>Date format</label>
		<?php echo form_input($field_name.'[date_format]', $options['date_format']); ?>
	</li>
</ol>