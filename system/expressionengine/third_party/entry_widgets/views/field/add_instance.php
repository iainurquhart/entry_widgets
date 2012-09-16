<?php
		$row_count = (isset($row_count)) ? $row_count : 0;
	?>


<div class="entry_widget" data-index="<?=$row_count?>">

	
	<?php


	if(isset($via_validation))
	{
		if(isset($widget['id']))
		{
			echo form_hidden($field_name.'['.$row_count .'][widget_id]', $widget['id']); 
		}
	}
	else
	{
		if(isset($widget_instance['id']))
		{
			echo form_hidden($field_name.'['.$row_count .'][instance_id]', $widget_instance['id']); 
		}

		if(isset($widget_instance['widget_id']))
		{
			echo form_hidden($field_name.'['.$row_count .'][widget_id]', $widget_instance['widget_id']); 
		}

		if(isset($widget['widget_id']))
		{
			echo form_hidden($field_name.'['.$row_count .'][widget_id]', $widget['widget_id']); 
		}
	}

	?>

	<?php echo form_hidden($field_name.'['.$row_count .'][widget_area_id]', $widget_area['id']); ?>
	
	<?php if(!empty($error)): ?>
		<?php echo $error; ?>
	<?php endif; ?>

	<table class="mainTable" border="0" cellspacing="0" cellpadding="0" data-index="0">

		<thead>
			<tr>
				<th colspan="2"><?php echo $widget['title']; ?> <a class="widget-delete">x</a></th>
			</tr>
		</thead>
		<tbody>
			<tr class="odd">
				<td>
					<label><?php echo lang('widgets_instance_title'); ?></label>
					<?php echo form_input($field_name.'['.$row_count .'][title]', (isset($widget_instance['title'])) ? $widget_instance['title'] : ''); ?>
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