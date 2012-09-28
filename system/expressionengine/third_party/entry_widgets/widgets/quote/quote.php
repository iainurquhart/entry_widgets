<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @package 		ExpressionEngine 2: Widgets
 * @subpackage 		Quotes
 * @author			Iain Urquhart
 * 
 * Show a quote/testimonial
 */

class Widget_Quote extends Entry_widget
{
	public $title = 'Quote';
	public $description = 'Display related entries.';
	public $author = 'Iain Urquhart';
	public $website = 'http://iain.co.nz';
	public $version = '1.0';
	
	public $fields = array(
		array(
			'field'   => 'quote_text',
			'label'   => 'Quote Text'
		),
		array(
			'field'   => 'who_said',
			'label'   => 'Who said it'
		),
		array(
			'field'   => 'position',
			'label'   => 'Position'
		),
		array(
			'field'   => 'company',
			'label'   => 'Company'
		),
		array(
			'field'   => 'url',
			'label'   => 'URL',
			'rules'   => 'url'
		)
	);

	public function run($options)
	{

		$quote_text = (isset($options['quote_text'])) ? $options['quote_text'] : '';

		if($quote_text) // parse via typography lib
		{
			$prefs = array(
				'text_format'   => 'xhtml',
				'html_format'   => 'all',
				'auto_links'    => 'y',
				'allow_img_url' => 'y'
			);
			$this->EE->load->library('typography');
			$this->EE->typography->initialize();
			$quote_text = $this->EE->typography->parse_type($quote_text, $prefs);
		}

		return array(
			'quote_text' => $quote_text,
			'who_said' => (isset($options['who_said'])) ? $options['who_said'] : '',
			'position' => (isset($options['position'])) ? $options['position'] : '',
			'company' => (isset($options['company'])) ? $options['company'] : '',
			'url' => (isset($options['url'])) ? $options['url'] : '',
		);

	}
	
	public function form($options)
	{
		return array('options' => $options); 
	}

	public function save($options)
	{		
		return $options;
	}
	
}
