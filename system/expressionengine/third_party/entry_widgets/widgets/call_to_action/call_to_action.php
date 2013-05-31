<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @package 		ExpressionEngine 2: Widgets
 * @subpackage 		Quotes
 * @author			Iain Urquhart
 * 
 * Show a quote/testimonial
 */

class Widget_Call_to_action extends Entry_widget
{
	public $title = 'Call to action';
	public $description = 'Display a find out more call to action.';
	public $author = 'Iain Urquhart';
	public $website = 'http://iain.co.nz';
	public $version = '1.0';
	
	public $fields = array(
		array(
			'field'   => 'cta_title',
			'label'   => 'Call to action title'
		),
		array(
			'field'   => 'cta_text',
			'label'   => 'Description / text'
		),
		array(
			'field'   => 'cta_link_text',
			'label'   => 'Link text'
		),
		array(
			'field'   => 'cta_url',
			'label'   => 'URL'
		)
	);

	public function run($options)
	{

		$cta_text = (isset($options['cta_text'])) ? $options['cta_text'] : '';

		if($cta_text) // parse via typography lib
		{
			$prefs = array(
				'text_format'   => 'xhtml',
				'html_format'   => 'all',
				'auto_links'    => 'y',
				'allow_img_url' => 'y'
			);
			$this->EE->load->library('typography');
			$this->EE->typography->initialize();
			$cta_text = $this->EE->typography->parse_type($cta_text, $prefs);
		}

		return array(
			'cta_title' => (isset($options['cta_title'])) ? $options['cta_title'] : '',
			'cta_text' => $cta_text,
			'cta_link_text' => (isset($options['cta_link_text'])) ? $options['cta_link_text'] : 'Find out more',
			'cta_url' => (isset($options['cta_url'])) ? $options['cta_url'] : ''
		);

	}
	
	public function form($options)
	{	
		$options['cta_link_text'] = (isset($options['cta_link_text'])) ? $options['cta_link_text'] : 'Find out more';
		return array('options' => $options); 
	}
	
}
