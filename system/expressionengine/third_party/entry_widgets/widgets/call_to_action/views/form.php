<table class="mainTable entry_widget_notop" border="0" cellspacing="0" cellpadding="0" data-index="0"> 
	<tbody>
		<tr>
			<td colspan="2"><label>Title</label>
				<?php echo form_input($field_name.'[cta_title]', $options['cta_title']); ?>
			</td>
		</tr>
		<tr>
			<td colspan="2"><label>Call to action text</label>
				<?php 
					$data = array(
						'name'	=> $field_name.'[cta_text]',
						'value'	=> $options['cta_text'],
						'cols'	=> '5',
						'style'	=> 'height:100px;'
					);
					echo form_textarea($data); 

				?>
			</td>
		</tr>
		<tr>
			<td><label>Link Text</label>
				<?php echo form_input($field_name.'[cta_link_text]', $options['cta_link_text']); ?>
			</td>
			<td><label>Link URL</label>
				<?php echo form_input($field_name.'[cta_url]', $options['cta_url']); ?>
			</td>
		</tr>
	</tbody>
</table>
