<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Global Fields for ExpressionEngine 2
 *
 * @package		ExpressionEngine
 * @subpackage	Modules
 * @category	Tab
 * @author    	Iain Urquhart <shout@iain.co.nz>
 * @copyright 	Copyright (c) 2012 Iain Urquhart
 * @license   	All Rights Reserved
*/
 
// ------------------------------------------------------------------------

/**
 * Global Fields Module Control Panel File
 */

include_once PATH_THIRD.'entry_widgets/libraries/Entry_widget'.EXT;

class Entry_widgets extends Entry_widget {}

class Entry_widgets_mcp {
	
	public $return_data;
	
	private $_base_url;
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->EE =& get_instance();
		
		$this->_base_url = BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=entry_widgets';
		
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
		return 'Nothing here yet';
	}


	function ajax_add_instance()
	{
		return $this->_add_instance(TRUE);
	}

	private function _add_instance($ajax = TRUE)
	{
		
		$widget_id 		= (int) $this->EE->input->get_post('widget_id');
		$widget_area_id = $this->EE->input->get_post('widget_area_id');
		$field_name 	= $this->EE->input->get_post('field_name');

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
			$field_name.'[0][options]['.$data['widget']['slug'].']'
		);

		// $data['view'] = $this->EE->load->view('field/test_view', $data, TRUE);
		$data['view'] = $this->EE->load->view('field/add_instance', $data, TRUE);

		$this->EE->output->send_ajax_response($data);
	}



	
}
/* End of file mcp.entry_widgets.php */
/* Location: /system/expressionengine/third_party/entry_widgets/mcp.entry_widgets.php */