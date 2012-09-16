<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


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
		$this->_add_widget_assets();
		$i = 0;

		$this->data->available_widgets = array();

		// Firstly, install any uninstalled widgets
		$uninstalled_widgets = $this->EE->entry_widget->list_uninstalled_widgets();
		foreach($uninstalled_widgets as $widget) // install them
		{
			$this->EE->entry_widget->add_widget( (array) $widget );
		}

		$this->data->available_widgets = $this->EE->entry_widget->list_available_widgets();
		$this->data->settings = $this->settings['field_settings'];
		$this->data->field_name = $this->field_name;
		$this->data->widgets = '';


		// @todo - work on displaying widgets if something else on the publish page
		// fails to validate.
		// subsequent failures cause errors atm.
		if(is_array($data)) // if something on the publish page fails to validate, we've got access to our data
		{

			foreach($data as $widget)
			{
				
				$w_data['widget'] = (array) $this->EE->entry_widget->get_widget($widget['widget_id']);
				$w_data['widget_instance'] = $widget;
				$w_data['widget_area'] 	= (array) $this->EE->entry_widget->get_area( $widget['widget_area_id'] );
				$w_data['row_count']		= $i++;
				$w_data['via_validation'] = TRUE;
				$w_data['field_name']	= $this->field_name;

				$w_data['form'] = $this->EE->entry_widget->render_backend(
					$w_data['widget']['slug'], 
					isset($w_data['widget_instance']['options']) ? $w_data['widget_instance']['options'] : array(),
					$this->field_name.'['.$w_data['row_count'].'][options]['.$w_data['widget']['slug'].']'
				);


				array_walk_recursive($w_data, array($this, '_filter'));


				$this->data->widgets[] = $this->EE->load->view('field/add_instance', $w_data, TRUE);

			}

			return $this->EE->load->view('field/index', $this->data, TRUE);

		}

		// are we editing an entry
		if ($this->EE->input->get_post('entry_id') != FALSE)
    	{

    		$entry_id = $this->EE->input->get_post('entry_id');



    		$this->data->widget_instances = $this->EE->entry_widget->list_area_instances(
							   		$this->data->settings['area_slug'], 
									$entry_id
								);


    		

			foreach($this->data->widget_instances as $widget)
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

				$this->data->widgets[] = $this->EE->load->view('field/add_instance', $data, TRUE);

			}

		
		}

		return $this->EE->load->view('field/index', $this->data, TRUE);
	}

	public static function _filter(&$value) {
  		$value = htmlspecialchars_decode($value);
	}


	private function _add_widget_assets()
	{
		if (! isset($this->cache['assets_added']) )
		{
			$this->cache['assets_added'] = 1;

			$this->EE->cp->add_to_head('
				<script src="'.$this->asset_path.'/js/entry_widgets.js"></script>
			');

			$this->EE->cp->add_js_script('ui', 'sortable');
		}
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