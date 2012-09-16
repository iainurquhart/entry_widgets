<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @package 		ExpressionEngine 2: Widgets
 * @subpackage 		RSS Feed Widget
 * @author			Phil Sturgeon - PyroCMS Development Team
 * 
 * Show RSS feeds in your site
 */

class Widget_Related_news extends Entry_widget
{
	public $title = 'Related News';
	public $description = 'Display related news items.';
	public $author = 'Iain Urquhart';
	public $website = 'http://iain.co.nz';
	public $version = '1.0';
	
	public $fields = array(
		array(
			'field'   => 'category',
			'label'   => 'Category',
			'rules'   => 'trim'
		),
		array(
			'field'   => 'number',
			'label'   => 'Number of items',
			'rules'   => 'numeric'
		)
	);

	public function run($options)
	{

		// Store the feed items
		return array(
			'output' => $options
		);
	}
	
	public function form($options)
	{
		empty($options['number']) AND $options['number'] = 5;
		empty($options['date_format']) AND $options['date_format'] = 'd-m-Y h:m';

		return array('options' => $options); // $test = thing in form.php
	}
	
	public function save($options)
	{
		
		return $options;
	}
}
