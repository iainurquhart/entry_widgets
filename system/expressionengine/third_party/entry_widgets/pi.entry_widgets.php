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



include_once PATH_THIRD.'entry_widgets/libraries/Entry_widget'.EXT;

class Entry_widgets extends Entry_widget {
	
	public $return_data;
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		$this->EE->load->library('entry_widget');
	}
	
	// ----------------------------------------------------------------

	public function instance()
	{
		$id = $this->EE->TMPL->fetch_param('id');
		
		$widget = $this->EE->entry_widget->get_instance($id, TRUE);

		$widget->options['title'] = $widget->instance_title;

		return $widget ? $this->EE->entry_widget->render($widget->slug, $widget->options) : '';
	}

	
}
/* End of file mod.entry_widgets.php */
/* Location: /system/expressionengine/third_party/entry_widgets/mod.entry_widgets.php */