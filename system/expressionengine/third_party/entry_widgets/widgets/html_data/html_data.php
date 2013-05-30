<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @package 		ExpressionEngine 2: Widgets
 * @subpackage 		RSS Feed Widget
 * @author			Phil Sturgeon - PyroCMS Development Team
 * 
 * Show RSS feeds in your site
 */

class Widget_Html_data extends Entry_widget
{
	public $title = 'HTML';
	public $description = 'Create blocks of custom HTML.';
	public $author = 'Phil Sturgeon';
	public $website = 'http://philsturgeon.co.uk/';
	public $version = '1.0';
	
	public $fields = array(
		array(
			'field'   => 'html_data',
			'label'   => 'HTML'
		)
	);

	public function run($data)
	{
		return array(
			'html_data' => (!empty($data['html_data'])) ? $data['html_data'] : '' 
		);

	}
	
}
