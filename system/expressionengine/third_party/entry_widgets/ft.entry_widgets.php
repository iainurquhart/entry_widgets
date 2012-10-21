<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Entry Widgets Fieldtype for ExpressionEngine 2
 *
 * @package		ExpressionEngine
 * @subpackage	Modules
 * @category	Fieldtype
 * @author    	Iain Urquhart <shout@iain.co.nz>
 * @author    	Phil Sturgeon - who wrote the original widgets module: https://github.com/philsturgeon/ee2-widgets
 * @copyright 	Copyright (c) 2012 Iain Urquhart
 * @license   	All Rights Reserved
*/
 
// ------------------------------------------------------------------------

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
		
		$this->widget_cache =& $this->EE->session->cache['entry_widgets_data'];

		$this->site_id 		= $this->EE->config->item('site_id');
		$this->asset_path 	= $this->EE->config->item('theme_folder_url').'third_party/entry_widgets/';
		$this->drag_handle  = '&nbsp;';
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
		
		// BW is playing up, just force entry to exist before adding widgets.
		if($this->EE->input->get_post('entry_id') == '')
		{
			return "<p>Please save the entry before creating widgets</p>";
		}

		$this->EE->load->library('entry_widget');
		$this->EE->load->model('entry_widgets_m');
		$this->_add_widget_assets();
		$i = 0;

		$this->data->available_widgets = array();

		if($this->settings['widget_area_id'] == '')
		{
			return 'no area defined';
		}

		// Firstly, install any uninstalled widgets
		$uninstalled_widgets = $this->EE->entry_widget->list_uninstalled_widgets();
		foreach($uninstalled_widgets as $widget) // install them
		{
			$this->EE->entry_widget->add_widget( (array) $widget );
		}

		$this->data->available_widgets = $this->EE->entry_widget->list_available_widgets();
		$this->data->settings = (array) $this->EE->entry_widgets_m->get_area_by( 'id', $this->settings['widget_area_id'] );
		$this->data->field_name = $this->field_name;
		$this->data->widgets = '';

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
					$this->field_name.'['.$w_data['row_count'].'][options]',
					$w_data['widget_instance']
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
							   		$this->data->settings['slug'], 
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
					isset($widget->options) ? $this->EE->entry_widget->decode_options($widget->options) : array(),
					$this->field_name.'['.$data['row_count'].'][options]',
					$data['widget_instance']
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
		if (! isset($this->widget_cache['assets_added']) )
		{
			$this->widget_cache['assets_added'] = 1;

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

		$this->EE->load->library('entry_widget');
		$areas = $this->EE->entry_widget->list_areas();
		$options = array();

		if(!$areas)
		{
			$this->EE->table->add_row(
				lang('Add a widget area via the module interface first!'),
				''
			);
			return;
		}
		else
		{
			foreach($areas as $area)
			{
				$options[ $area->id ] = $area->title;
			}
		}

		$this->EE->table->add_row(
			lang('widget_area'),
			form_dropdown('widget_area_id', $options)
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
			'widget_area_id'	=> $this->EE->input->post('widget_area_id')
		);
	}

	
	// --------------------------------------------------------------------

	/**
	 * Save
	 *
	 * @access	public
	 * @return	field data
	 *
	 */
	function save($data)
	{
		$this->EE->load->library('entry_widget');

		$this->widget_cache->data = $data;
		$this->widget_cache->is_draft = 0;
		if (isset($this->EE->session->cache['ep_better_workflow']['is_draft']) && $this->EE->session->cache['ep_better_workflow']['is_draft']) 
		{
			$this->widget_cache->is_draft = 1;
		}

		if($data == '' && $this->EE->input->get_post('entry_id'))
		{
			$this->EE->db->where('widget_area_id', $this->settings['widget_area_id'] );
			$this->EE->db->where('entry_id', $this->EE->input->get_post('entry_id') );
			$this->EE->db->where('is_draft', $this->widget_cache->is_draft );
			$this->EE->db->delete('entry_widget_instances');
		}

		return '';
	}


	// --------------------------------------------------------------------

	/**
	 * post_save
	 *
	 * @access	public
	 * @return	field data
	 *
	 */
	function post_save($data)
	{
		$this->widget_cache->is_draft = 0;
		if (isset($this->EE->session->cache['ep_better_workflow']['is_draft']) && $this->EE->session->cache['ep_better_workflow']['is_draft']) 
		{
			$this->widget_cache->is_draft = 1;
		}
		else
		{
			$this->_save($data);
		}
		
	}

	// --------------------------------------------------------------------

	/**
	 * post_save
	 *
	 * @access	public
	 * @return	field data
	 *
	 */
	public function draft_save($data, $draft_action)
	{
		$this->widget_cache->is_draft = 1;
		$this->widget_cache->draft_action = $draft_action;

		// Some Vars
		$entry_id = $this->settings['entry_id'];
		$widget_area_id = $this->settings['widget_area_id'];

		$this->EE->db->where('widget_area_id', $this->settings['widget_area_id'] );
		$this->EE->db->where('entry_id', $this->settings['entry_id'] );
		$this->EE->db->where('is_draft', 1 );
		$this->EE->db->delete('entry_widget_instances');

		foreach ($this->widget_cache->data  as $key => $widget)
		{
		  	 $this->EE->entry_widget->add_instance( 
					$this->settings['entry_id'], 
					$widget['widget_id'],
					$widget['widget_area_id'], 
					$widget['options'],
					$key
				);
		}

		return 'data_updated';

	}

	public function draft_discard()
	{
		
		$this->EE->db->where('widget_area_id', $this->settings['widget_area_id'] );
		$this->EE->db->where('entry_id', $this->settings['entry_id'] );
		$this->EE->db->where('is_draft', 1 );
		$this->EE->db->delete('entry_widget_instances');
		
		return;
	}

	public function draft_publish()
	{

		// Delete all the current live content
		$this->EE->db->where('widget_area_id', $this->settings['widget_area_id'] );
		$this->EE->db->where('entry_id', $this->settings['entry_id'] );
		$this->EE->db->where('is_draft', 0 );
		$this->EE->db->delete('entry_widget_instances');

		// Update the current draft content to be live
		$this->EE->db->where('entry_id', $this->settings['entry_id']);
		$this->EE->db->where('widget_area_id', $this->settings['widget_area_id']);
		$this->EE->db->where('is_draft', 1);
		$this->EE->db->update('entry_widget_instances', array('is_draft' => 0));

		return;
	}




	private function _save($data, $draft_action = '')
	{

		$widget_data = $this->widget_cache->data;
		$instances = array();
		$this->widget_cache->is_draft = 0;



		// do we have widget data
		if(!is_array($widget_data))
		{
			$this->EE->db->where('widget_area_id', $this->settings['widget_area_id'] );
			$this->EE->db->where('entry_id', $this->settings['entry_id'] );
			$this->EE->db->delete('entry_widget_instances');
		}
		else
		{
			// gather instance ids
			foreach($widget_data as $key => $widget)
			{
				if(isset($widget['instance_id']))
				{
					$instances[] = $widget['instance_id'];
				}
			}

			// remove any instances not submitted
			if($instances)
			{	
				$this->EE->db->where_not_in('id', $instances);
				$this->EE->db->where('widget_area_id', $this->settings['widget_area_id'] );
				$this->EE->db->where('entry_id', $this->settings['entry_id'] );
				$this->EE->db->delete('entry_widget_instances');
			}

			// process new and update existing
			foreach($widget_data as $key => $widget)
			{

				$widget['options'] = (isset($widget['options'])) ? $widget['options'] : array();

				// edit an existing
				if( isset($widget['instance_id']) && $widget['instance_id'] != '')
				{
					$result = $this->EE->entry_widget->edit_instance(
						$widget['instance_id'], // this should be widget_instance_id
						$this->settings['entry_id'], 
						$widget['widget_id'],
						$widget['widget_area_id'], 
						$widget['options'],
						$key
					);
				}
				else // add new
				{

					$result = $this->EE->entry_widget->add_instance( 
						$this->settings['entry_id'], 
						$widget['widget_id'],
						$widget['widget_area_id'], 
						$widget['options'],
						$key
					);
				}
			}
		}
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