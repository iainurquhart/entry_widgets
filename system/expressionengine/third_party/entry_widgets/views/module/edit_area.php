<?php

	echo form_open($_form_base_url.AMP.'method=edit_area'.AMP.time());
	$this->table->set_template($cp_table_template);
	echo form_hidden('area_id', $area->id);
	$this->table->set_heading(
		array('data' => lang('edit_widget_area'), 'colspan' => '2')
	);

	$this->table->add_row(
			array('data' => lang('area_title'), 'style' => 'width: 30%'),
			form_input('area_title', $area->title, 'id="area-label"')
		);
	$this->table->add_row(
			lang('area_slug'),
			form_input('area_slug', $area->slug, 'id="area-slug"')
		);
	$this->table->add_row(
			lang('show_shortcode'),
			form_checkbox('show_shortcode', 1, $area->show_shortcode)
		);

	echo $this->table->generate();
	echo '<input type="submit" name="update" class="submit" value="'.lang('submit').'" />';
	echo form_close();
?>