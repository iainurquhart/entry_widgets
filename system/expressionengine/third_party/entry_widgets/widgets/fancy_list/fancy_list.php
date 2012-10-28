<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @package 		ExpressionEngine 2: Widgets
 * @subpackage 		Pie Chart Widget
 * @author			Iain Urquhart
 * 
 * Show Pie Charts in your site
 */

class Widget_Fancy_list extends Entry_widget
{
	public $title = 'Fancy List';
	public $description = 'Create a fancy list';
	public $author = '';
	public $website = '';
	public $version = '1.0';
	public $drag_handle  = '&nbsp;';
	public $nolan_nav 	= '<a class="remove_row" href="#">-</a> <a class="add_row" href="#">+</a>';
	
	public $fields = array(
		array(
			'field'   => 'list_items',
			'label'   => 'List Items'
		),
		array(
			'field'   => 'label',
			'label'   => 'Label'
		)
	);

	function __construct()
	{
		parent::__construct();
		$this->asset_path = $this->EE->config->item('theme_folder_url').'third_party/entry_widgets';
		$this->cache =& $this->EE->session->cache['widget_fancy_list'];
	}

	public function run($options)
	{

		// print_r($options);
		
		$items  = (isset($options['list_items'])) ? $options['list_items'] : array();
		$label = (isset($options['label'])) ? $options['label'] : '';

		return array(
			'items' => $items,
			'label' => $label
		);

	}

	public function form($options)
	{

		$list_items = (isset($options['list_items'])) ? $options['list_items'] : array();
		$label = (isset($options['label'])) ? $options['label'] : '';
		$widget_id = (isset($options['widget_id'])) ? $options['widget_id'] : '';

		$options = array(
			'label' => $label,
			'widget_id' => $widget_id,
			'list_items' => $list_items,
			'nav' => $this->nolan_nav,
			'drag_handle' => $this->drag_handle,
			'script' => '<script src="'.$this->asset_path.'/js/jquery.roland.js"></script>'
		);

		return array('options' => $options); 
	}

	public function save($options)
	{	
		return $options;
	}

}
