<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once PATH_THIRD.'entry_widgets/libraries/Entry_widget'.EXT;

class Entry_widgets extends Entry_widget {}


class Entry_widgets_ft extends EE_Fieldtype {
	
	var $info = array(
		'name'		=> 'Entry_widgets',
		'version'	=> '1.0'
	);
	
	// --------------------------------------------------------------------
	
	/**
	 * Display Field on Publish
	 *
	 * @access	public
	 * @param	existing data
	 * @return	field html
	 *
	 */
	function display_field($data)
	{

		$this->EE->load->library('entry_widget');

		$this->data->available_widgets = array();

		// Firstly, install any uninstalled widgets
		$uninstalled_widgets = $this->EE->entry_widget->list_uninstalled_widgets();
		foreach($uninstalled_widgets as $widget) // install them
		{
			$this->EE->entry_widget->add_widget( (array) $widget );
		}

		$this->data->available_widgets = $this->EE->entry_widget->list_available_widgets();
		$this->data->available_widget_areas 	= (array) $this->EE->entry_widget->list_areas();

		$this->data->field_name = $this->field_name;


		if($data) // if something on the publish page fails to validate, we've got access to our data
		{
			// print_r($data);
		}

		// are we editing an entry
		if ($this->EE->input->get_post('entry_id') != FALSE)
    	{
    		foreach($this->data->available_widget_areas as &$area)
			{
				$area->widgets = $this->EE->entry_widget->list_area_instances($area->slug);


				foreach($area->widgets as $widget)
				{

					$data = array();

					$data['widget'] 		= (array) $this->EE->entry_widget->get_widget($widget->id);
					$data['widget_area'] 	= (array) $this->EE->entry_widget->get_area( $widget->widget_area_id );
					$data['field_name'] 	= $this->field_name;

					$data['form'] = $this->EE->entry_widget->render_backend(
						$widget->widget_area_slug, 
						isset($widget->options) ? $widget->options : array(),
						$this->field_name.'[0][options]['.$widget->widget_area_slug.']'
					);

					// $data['view'] = $this->EE->load->view('field/test_view', $data, TRUE);
					$data['view'] = $this->EE->load->view('field/add_instance', $data, TRUE);
					
					print_r($this->data->available_widget_areas);
				}
			}
		}




		

		return $this->EE->load->view('field/index', $this->data, TRUE);
	}
	
	// --------------------------------------------------------------------
		
	/**
	 * Replace tag
	 *
	 * @access	public
	 * @param	field contents
	 * @return	replacement text
	 *
	 */
	function replace_tag($data, $params = array(), $tagdata = FALSE)
	{
		return 'hello';
	}

	function validate($data)
	{	
		// print_r($data);
			// exit('here');
		// print_r($data);
	}
	

	// --------------------------------------------------------------------
	
	/**
	 * Display Settings Screen
	 *
	 * @access	public
	 * @return	default global settings
	 *
	 */
	function display_settings($data)
	{

		$this->EE->table->add_row(
			lang('latitude', 'latitude'),
			form_input('latitude', '')
		);
	}
	
	// --------------------------------------------------------------------

	/**
	 * Save Settings
	 *
	 * @access	public
	 * @return	field settings
	 *
	 */
	function save_settings($data)
	{
		return array(
			'latitude'	=> $this->EE->input->post('latitude')
		);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Install Fieldtype
	 *
	 * @access	public
	 * @return	default global settings
	 *
	 */
	function install()
	{
		return array();
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Control Panel Javascript
	 *
	 * @access	public
	 * @return	void
	 *
	 */
	function _cp_js()
	{

		// $this->EE->cp->add_to_head('<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>');
		// $this->EE->cp->load_package_js('cp');
	}
}

/* End of file ft.google_maps.php */
/* Location: ./system/expressionengine/third_party/google_maps/ft.google_maps.php */