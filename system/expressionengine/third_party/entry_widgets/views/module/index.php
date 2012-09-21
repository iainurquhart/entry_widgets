<?php


	echo form_open($_form_base_url.AMP.time());
	$this->table->set_template($cp_table_template);
	$this->table->set_heading(
		array('data' => lang('add_widget_area'), 'colspan' => '2')
	);
	$this->table->add_row(
			array('data' => lang('add_widget_area_instructions'), 'colspan' => '2')
		);

	$this->table->add_row(
			lang('area_title'),
			form_input('area_title')
		);
	$this->table->add_row(
			lang('area_slug'),
			form_input('area_slug')
		);
	$this->table->add_row(
			lang('show_shortcode'),
			form_checkbox('show_shortcode', 1)
		);
	$this->table->add_row(
			'',
			form_submit('mysubmit', 'Add Widget Area')
		);

	echo $this->table->generate();

	echo form_close();



	if($widget_areas)
	{
		$this->table->set_template($cp_table_template);
		$this->table->set_heading(
			array('data' => lang('id'), 'style' => 'width: 25px !important; text-align: center;'),
			lang('widget_areas'),
			lang('delete')
		);
		foreach($widget_areas as $area)
		{
			$this->table->add_row(
					array('data' => $area->id, 'style' => 'width: 25px; text-align: center; text-shadow: 0 1px 0 #fff;  font-weight: bold;font-size: 14px;'),
					array('data' => '<a href="'.$area->title.'">'.$area->title.'</a>', 'style' => 'font-size: 14px; font-weight: bold;  text-shadow: 0 1px 0 #fff;'),
					array('data' => '<a href="'.$area->id.'" class="delete_tree_confirm">
					<img src="'.$this->cp->cp_theme_url.'images/icon-delete.png" /></a>', 'style' => 'width: 20px; text-align: center;')
					
				);
		}
		echo $this->table->generate();
	}
	else
	{
		echo lang('no_areas_defined');
	}




?>