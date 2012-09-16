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
	 * constructor
	 * 
	 * @access	public
	 * @return	void
	 */
	public function __construct()
	{
		parent::EE_Fieldtype();

		$this->site_id 		= $this->EE->config->item('site_id');
		$this->asset_path 	= $this->EE->config->item('theme_folder_url').'third_party/entry_widgets/';
		$this->drag_handle  = '&nbsp;';
		$this->cache 	   =& $this->EE->session->cache['entry_widgets_data'];
	}
	
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
		$this->EE->cp->add_js_script('ui', 'sortable');

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

		$this->data->widgets = '';


		if($data) // if something on the publish page fails to validate, we've got access to our data
		{
			// print_r($data);
		}



		// are we editing an entry
		if ($this->EE->input->get_post('entry_id') != FALSE)
    	{

    		$entry_id = $this->EE->input->get_post('entry_id');

    		foreach($this->data->available_widget_areas as $key => &$area)
			{
					
				$area->widgets = $this->EE->entry_widget->list_area_instances($area->slug, $entry_id);
			}

			$i = 0;

			foreach($this->data->available_widget_areas as $widget_area)
			{
				foreach($widget_area->widgets as $widget)
				{
					$data = array();
					$data['widget'] 		= (array) $this->EE->entry_widget->get_widget($widget->widget_id);
					$data['widget_instance'] = (array) $this->EE->entry_widget->get_widget_instance($widget->instance_id);
					$data['widget_area'] 	= (array) $this->EE->entry_widget->get_area( $widget->widget_area_id );
					$data['field_name'] 	= $this->field_name;
					$data['row_count']		= $i++;

					$data['form'] = $this->EE->entry_widget->render_backend(
						$widget->slug, 
						isset($widget->options) ? $this->EE->entry_widget->unserialize_options($widget->options) : array(),
						$this->field_name.'['.$data['row_count'].'][options]['.$widget->slug.']'
					);

					$this->data->widgets[$widget_area->slug][] = $this->EE->load->view('field/add_instance', $data, TRUE);
					unset($data);
				}
			}
		}

		return $this->EE->load->view('field/index', $this->data, TRUE);
	}

	function post_save($data)
	{

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