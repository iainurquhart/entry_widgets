<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Entry Widgets for ExpressionEngine 2
 *
 * @package		ExpressionEngine
 * @subpackage	Modules
 * @category	Tab
 * @author    	Iain Urquhart <shout@iain.co.nz>
 * @author    	Phil Sturgeon - who wrote the original widgets module: https://github.com/philsturgeon/ee2-widgets
 * @copyright 	Copyright (c) 2012 Iain Urquhart
 * @license   	All Rights Reserved
*/
 
// ------------------------------------------------------------------------

/**
 * Global Fields Module Control Panel File
 */

class Entry_widgets_mcp {
	
	public $return_data;
	
	private $_base_url;

	var $module_name = "entry_widgets";
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->EE =& get_instance();

		// define some vars
		$this->_form_base_url 	= 'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module='.$this->module_name;
		$this->_base_url		= BASE.AMP.$this->_form_base_url;
		$this->_theme_base_url 	= $this->EE->config->item('theme_folder_url').'third_party/'.$this->module_name.'/';
		$this->site_id	 		= $this->EE->config->item('site_id');
		
		
		$this->EE->cp->set_right_nav(array(
			'module_home'	=> $this->_base_url,
			// Add more right nav items here.
		));

		$this->EE->load->library('entry_widget');
	}
	
	// ----------------------------------------------------------------

	/**
	 * Index Function
	 *
	 * @return 	void
	 */
	public function index()
	{
		$this->EE->cp->set_variable('cp_page_title', lang('entry_widgets_module_name'));
		$this->EE->load->library('entry_widget');
		$this->EE->load->library('table');

		$data = array();

		$data['widget_areas'] = $this->EE->entry_widget->list_areas();
		$data['_form_base_url'] = $this->_form_base_url;

		if($_POST)
		{
			$w_data->widget_area->title = $this->EE->input->get_post('area_title');
			$w_data->widget_area->slug = $this->EE->input->get_post('area_slug');
			$w_data->widget_area->show_shortcode = $this->EE->input->get_post('show_shortcode');

			$w_data->widget_area->id = $this->EE->entry_widget->add_area( $w_data->widget_area );
			
			$this->EE->functions->redirect($this->_base_url);
		}

		return $this->EE->load->view('module/index', $data, TRUE);

	}

	function ajax_add_instance()
	{
		return $this->_add_instance(TRUE);
	}

	function add_instance()
	{
		return $this->_add_instance(FALSE);
	}

	private function _add_instance($ajax = TRUE)
	{
		
		$widget_id 		= (int) $this->EE->input->get_post('widget_id');
		$widget_area_id = $this->EE->input->get_post('widget_area_id');
		$field_name 	= $this->EE->input->get_post('field_name');

		if(!$widget_id)
			return;

		$data = array();

		$data['widget'] 		= (array) $this->EE->entry_widget->get_widget($widget_id);

		// getting a widget as opposed to a widget instance.
		$data['widget']['widget_id'] = $data['widget']['id'];
		unset($data['widget']['id']);

		$data['widget_area'] 	= (array) $this->EE->entry_widget->get_area( $widget_area_id );
		$data['widget_areas'] 	= array();
		$data['field_name'] 	= $field_name;
		
		$widget_areas 	= (array) $this->EE->entry_widget->list_areas();

		foreach($widget_areas as $area)
		{
			$data['widget_areas'][$area->id] = $area->title;
		}

		$data['form'] = $this->EE->entry_widget->render_backend(
			$data['widget']['slug'], 
			isset($data['widget']['options']) ? $data['widget']['options'] : array(),
			$field_name.'[0][options]'
		);

		$data['view'] = $this->EE->load->view('field/add_instance', $data, TRUE);

		if($ajax)
			$this->EE->output->send_ajax_response($data);
		else
			return $data['view'];

	}



	
}
/* End of file mcp.entry_widgets.php */
/* Location: /system/expressionengine/third_party/entry_widgets/mcp.entry_widgets.php */