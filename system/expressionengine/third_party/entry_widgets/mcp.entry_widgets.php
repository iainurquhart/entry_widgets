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
		$this->EE->view->cp_page_title = lang('entry_widgets_module_name');
		$this->EE->load->library('entry_widget');
		$this->EE->load->library('table');
		$this->EE->cp->add_js_script(
			array(
				'plugin' => array('ee_url_title')
			)
		);
		$this->EE->cp->add_to_head('
			<script type="text/javascript" charset="utf-8">
			// <![CDATA[
				$(document).ready(function() {
					$("#area-label").bind("keyup keydown", function() {
						$(this).ee_url_title("#area-slug");
					});
				});
			// ]]>
			</script>
		');

		$data = array();

		$data['widget_areas'] 	= $this->EE->entry_widget->list_areas();
		$data['_form_base_url'] = $this->_form_base_url;
		$data['_base_url'] 		= $this->_base_url;

		if($_POST)
		{
			$w = new stdClass();
			$w->title = $this->EE->input->get_post('area_title');
			$w->slug = $this->EE->input->get_post('area_slug');
			$w->show_shortcode = $this->EE->input->get_post('show_shortcode');

			$w->id = $this->EE->entry_widget->add_area( $w );
			$this->EE->session->set_flashdata('message_success', lang('widget_area_created'));
			$this->EE->functions->redirect($this->_base_url);
		}

		return $this->EE->load->view('module/index', $data, TRUE);

	}

	/**
	 * Edit widget area
	 *
	 * @return 	void
	 */
	public function edit_area()
	{
		
		$id = $this->EE->input->get_post('area_id');
		if(!$id)
			show_error('Area you requested was not found');

		$this->EE->view->cp_page_title = lang('entry_widgets_module_name');
		$this->EE->load->library('entry_widget');
		$this->EE->load->library('table');

		$data = array();

		$data['area'] 	= $this->EE->entry_widget->get_area( $id );
		$data['_form_base_url'] = $this->_form_base_url;
		$data['_base_url'] 		= $this->_base_url;

		if($_POST)
		{
			$w = new stdClass();
			$w->id = $this->EE->input->get_post('area_id');
			$w->title = $this->EE->input->get_post('area_title');
			$w->slug = $this->EE->input->get_post('area_slug');
			$w->show_shortcode = $this->EE->input->get_post('show_shortcode');

			$this->EE->entry_widget->update_area( $w );
			$this->EE->session->set_flashdata('message_success', lang('widget_area_updated'));
			$this->EE->functions->redirect($this->_base_url);
		}

		return $this->EE->load->view('module/edit_area', $data, TRUE);

	}

	/**
	 * Edit widget area
	 *
	 * @return 	void
	 */
	public function delete_area()
	{
		
		$id = $this->EE->input->get_post('area_id');
		if(!$id)
			show_error('Area you requested was not found');

		$this->EE->db->delete('entry_widget_areas', array('id' => $id)); 
		$this->EE->db->delete('entry_widget_instances', array('widget_area_id' => $id)); 

		$this->EE->session->set_flashdata('message_success', lang('widget_area_deleted'));

		$this->EE->functions->redirect($this->_base_url);

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