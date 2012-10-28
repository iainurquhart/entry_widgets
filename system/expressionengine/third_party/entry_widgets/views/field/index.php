
<ul class="entry_widget_areas">

	<li data-area-id="<?= $settings['id'] ?>" class="widget-area-wrapper">

		<div class="entry_widget_data" id="widget-area-<?= $settings['id'] ?>">

			<?php 
				if($widgets)
				{
					foreach( $widgets as $widget)
					{
						echo $widget;
					}
				}
			?>
			
		</div>

		<select class="add-widget-select">
			<?php foreach($available_widgets as $widget):?>
				<option data-field-name="<?=$field_name?>" data-widget-id="<?= $widget->id ?>" data-area-id="<?= $settings['id'] ?>"><?= $widget->title ?></option>
		<?php endforeach ?>
		</select>

		<a class="add-widget-button submit" href="#">Add</a>
	</li>

</ul>