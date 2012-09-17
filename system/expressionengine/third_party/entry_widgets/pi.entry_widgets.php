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

	private $_rendered_areas = array();

	private $_default_wrapper = '
		<div class="widget {slug}">
			<h3>{instance_title}</h3>

			<div class="widget-body">
			{body}
			</div>
		</div>';
	
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

	public function render()
	{

		$this->EE->load->model('entry_widgets_m');
		$area = $this->EE->TMPL->fetch_param('area');
		$entry_id = $this->EE->TMPL->fetch_param('entry_id');
		$wrapper_html = $this->EE->TMPL->tagdata ? $this->EE->TMPL->tagdata : $this->_default_wrapper;

		$key = $area.'|'.$entry_id;

		if (isset($this->_rendered_areas[$key]))
		{
			return $this->_rendered_areas[$key];
		}

		$widgets = $this->EE->entry_widgets_m->get_by_area($area, $entry_id);

		if(!$widgets)
			return $this->EE->TMPL->no_results();

		// $output = '';
		$variables = array();

		foreach ($widgets as &$widget)
		{
			$widget->options = $this->EE->entry_widget->unserialize_options( $widget->options );
			$widget->options['title'] = $widget->instance_title;
			$widget->body = $this->EE->entry_widget->render( $widget->slug, $widget->options );
			// $output .= $this->EE->entry_widget->render($widget->slug, $widget->options);
			$variables[]['widgets'][] = (array) $widget;
		}

		$return = $this->EE->TMPL->parse_variables(
			'{widgets}'.$wrapper_html.'{/widgets}',
			$variables 
		);

		return $this->_rendered_areas[$key] = $return;

	}

	
}
/* End of file mod.entry_widgets.php */
/* Location: /system/expressionengine/third_party/entry_widgets/mod.entry_widgets.php */