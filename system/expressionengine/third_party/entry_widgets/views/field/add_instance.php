<div class="entry_widget" data-index="0">

	<?php if(isset($widget['instance_id'])): ?>
		<?php echo form_hidden($field_name.'[0][instance_id]', $widget['instance_id']); ?>
	<?php endif; ?>

	<?php echo form_hidden($field_name.'[0][widget_id]', $widget['id']); ?>
	<?php echo form_hidden($field_name.'[0][widget_area_id]', $widget_area['id']); ?>
	
	<?php if(!empty($error)): ?>
		<?php echo $error; ?>
	<?php endif; ?>

	<table class="mainTable" border="0" cellspacing="0" cellpadding="0" data-index="0">

		<thead>
			<tr>
				<th colspan="2"><?php echo $widget['title']; ?></th>
			</tr>
		</thead>
		<tbody>
			<tr class="odd">
				<td>
					<label><?php echo lang('widgets_instance_title'); ?></label>
					<?php echo form_input($field_name.'[0][title]', set_value('title', isset($widget['instance_title']) ? $widget['instance_title'] : '')); ?>
				</td>
			</tr>

			<?php if($form): ?>
			<tr class="odd widget-options">
				<td colspan="2"><?php echo $form; ?></td>
			</tr>
			<?php endif; ?>
			
		</tbody>
	</table>

</div>