<table class="mainTable entry_widget_notop" border="0" cellspacing="0" cellpadding="0" data-index="0"> 
	<tbody>
		<tr>
			<td colspan="2"><label>What was said</label>
				<?php 
					$data = array(
						'name'	=> $field_name.'[quote_text]',
						'value'	=> $options['quote_text'],
						'cols'	=> '5',
						'style'	=> 'height:100px;'
					);
					echo form_textarea($data); 

				?>
			</td>
		</tr>
		<tr>
			<td colspan="2"><label>Who said it</label>
				<?php echo form_input($field_name.'[who_said]', $options['who_said']); ?>
			</td>
		</tr>
		<tr>
			<td><label>Position</label>
				<?php echo form_input($field_name.'[position]', $options['position']); ?>
			</td>
			<td><label>Company</label>
				<?php echo form_input($field_name.'[company]', $options['company']); ?>
			</td>
		</tr>
		<tr>
			<td colspan="2"><label>URL</label>
				<?php echo form_input($field_name.'[url]', $options['url']); ?>
			</td>
		</tr>
	</tbody>
</table>
