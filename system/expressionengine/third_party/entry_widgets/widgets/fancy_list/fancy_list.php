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
		),
		array(
			'field'   => 'type',
			'label'   => 'Type'
		),
		array(
			'field'   => 'bullet_type',
			'label'   => 'Bullet Type'
		),
		array(
			'field'   => 'css_classes',
			'label'   => 'Additional CSS classes'
		)
	);

	function __construct()
	{
		parent::__construct();
		$this->asset_path 	= defined('URL_THIRD_THEMES') ? URL_THIRD_THEMES . '/entry_widgets' : $this->EE->config->item('theme_folder_url') . '/third_party/entry_widgets';
		$this->save_string =& $this->EE->session->cache['entry_widgets']['widget_save_string'];
	}

	public function run($options)
	{
		$items = (isset($options['list_items'])) ? $options['list_items'] : array();
		$i=1;
		foreach($items as $key => $item)
		{
			$items[$key]['item_count'] = $i++;
			$items[$key]['total_items'] = count($items);
		}
		$data = array(
			'items' 		=> $items,
			'label' 		=> (isset($options['label'])) ? $options['label'] : '',
			'type' 			=> (isset($options['type'])) ? $options['type'] : 'floated',
			'bullet_type' 	=> (isset($options['bullet_type'])) ? $options['bullet_type'] : '',
			'css_classes' 	=> (isset($options['css_classes'])) ? $options['css_classes'] : ''
		);

		$data['has_items'] 	= (count($items)) ? TRUE : FALSE;

		return $data;

	}

	public function form($options)
	{

		$list_items = (isset($options['list_items'])) ? $options['list_items'] : array();
		$label = (isset($options['label'])) ? $options['label'] : '';
		$widget_id = (isset($options['widget_id'])) ? $options['widget_id'] : '';
		$type 	= (isset($options['type'])) ? $options['type'] : 'floated';
		$css_classes = (isset($options['css_classes'])) ? $options['css_classes'] : '';
		$bullet_type = (isset($options['bullet_type'])) ? $options['bullet_type'] : '';

		$options = array(
			'label' => $label,
			'widget_id' => $widget_id,
			'list_items' => $list_items,
			'nav' => $this->nolan_nav,
			'drag_handle' => $this->drag_handle,
			'script' => '<script src="'.$this->asset_path.'/js/jquery.roland.js"></script>',
			'type' => $type,
			'bullet_type' => $bullet_type,
			'css_classes' => $css_classes,
			'type_options' => array('floated' => 'Floated', 'block' => 'Block'),
			'bullet_type_options' => array('' => 'Default', 'ticks' => 'Ticks', 'crosses' => 'Crosses')
		);

		return array('options' => $options); 

	}

}
