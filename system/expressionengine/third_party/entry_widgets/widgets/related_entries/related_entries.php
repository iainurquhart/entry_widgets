<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @package 		ExpressionEngine 2: Widgets
 * @subpackage 		Related Entries
 * @author			Iain Urquhart
 * 
 * Show related entries
 */

class Widget_Related_entries extends Entry_widget
{
	public $title = 'Related Entries';
	public $description = 'Display related entries.';
	public $author = 'Iain Urquhart';
	public $website = 'http://iain.co.nz';
	public $version = '1.0';
	
	public $fields = array(
		array(
			'field'   => 'channel_name',
			'label'   => 'Channel',
			'rules'   => 'trim'
		),
		array(
			'field'   => 'limit',
			'label'   => 'Number of entries',
			'rules'   => 'numeric'
		),
		array(
			'field'   => 'show_future_entries',
			'label'   => 'Show Future Entries',
			'rules'   => 'trim'
		),
		array(
			'field'   => 'categories',
			'label'   => 'Categories',
			'rules'   => 'trim'
		)
	);

	public function run($options)
	{
		$limit 				 = (isset($options['limit'])) ? $options['limit'] : 0;
		$channel_name 		 = (isset($options['channel_name'])) ? $options['channel_name'] : 0;
		$show_future_entries = (isset($options['show_future_entries'])) ? $options['show_future_entries'] : 'no';
		$categories 		 = (isset($options['categories'])) ? $options['categories'] : '';

		return array(
			'limit' 	 => $limit,
			'channel_name' => $channel_name,
			'show_future_entries' => $show_future_entries,
			'categories' => $categories
		);
	}
	
	public function form($options)
	{
		
		$this->EE->load->model('channel_model');
		$channels = $this->EE->channel_model->get_channels()->result_array();

		foreach($channels as $channel)
		{
			$options['channel_select'][$channel['channel_name']] = $channel['channel_title'];
		}

		$options['show_future_entries_select'] = array(
			'no' => 'No',
			'yes' => 'Yes'
		);

		return array('options' => $options); 
	}

	public function save($options)
	{		
		return $options;
	}
	
}
