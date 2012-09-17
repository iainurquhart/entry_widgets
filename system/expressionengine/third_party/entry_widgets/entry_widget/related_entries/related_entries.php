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
			'field'   => 'channel_id',
			'label'   => 'Channel',
			'rules'   => 'trim'
		),
		array(
			'field'   => 'limit',
			'label'   => 'Number of entries',
			'rules'   => 'numeric'
		)
	);

	public function run($options)
	{
		$limit 		= (isset($options['limit'])) ? $options['limit'] : 0;
		$channel_id = (isset($options['channel_id'])) ? $options['channel_id'] : 0;

		return array(
			'limit' 	 => $limit,
			'channel_id' => $channel_id,
		);
	}
	
	public function form($options)
	{
		
		$this->EE->load->model('channel_model');
		$channels = $this->EE->channel_model->get_channels()->result_array();

		foreach($channels as $channel)
		{
			$options['channel_select'][$channel['channel_id']] = $channel['channel_title'];
		}

		return array('options' => $options); 
	}

	public function save($options)
	{		
		return $options;
	}
	
}
