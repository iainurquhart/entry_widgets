<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Entry Widgets Fieldtype for ExpressionEngine 2
 *
 * @package		ExpressionEngine
 * @subpackage	Module
 * @category	Plugin
 * @author    	Iain Urquhart <shout@iain.co.nz>
 * @author    	Phil Sturgeon - who wrote the original widgets module: https://github.com/philsturgeon/ee2-widgets
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
			<div class="widget-body">
			{widget_body}
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

		return $widget ? $this->EE->entry_widget->render($widget->slug, $widget->options) : '';
	}

	// ----------------------------------------------------------------

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

		$i = 1;
		foreach ($widgets as &$widget)
		{
			$widget->options = $this->EE->entry_widget->decode_options( $widget->options );
			$widget->widget_body = $this->EE->entry_widget->render( $widget->slug, $widget->options );
			$widget->widget_count = $i++;
			$widget->total_widget_count = count($widgets);
			$variables[]['widgets'][] = (array) $widget;
		}

		$return = $this->EE->TMPL->parse_variables(
			'{widgets}'.$wrapper_html.'{/widgets}',
			$variables 
		);

		return $this->_rendered_areas[$key] = $return;

	}

	public function prep_vars()
	{
		$prefix = $this->EE->TMPL->tagparams['var_prefix'];
		return str_replace($prefix, '', $this->EE->TMPL->tagdata);

	}

	public function extract_domain()
	{
		$url = $this->EE->TMPL->tagparams['url'];
		$domain =  parse_url($url, PHP_URL_HOST);
		return str_replace('www.', '', $domain);

	} 

	public function format_bytes() 
	{ 

		$bytes = $this->EE->TMPL->tagparams['bytes'];
		$precision = 1;
    	$base = log($bytes) / log(1024);
   		$suffixes = array('', 'KB', 'MB', 'GB', 'TB');   

    	return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];

	} 

	
}
/* End of file mod.entry_widgets.php */
/* Location: /system/expressionengine/third_party/entry_widgets/mod.entry_widgets.php */