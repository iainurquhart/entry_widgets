<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Entry Widgets Fieldtype for ExpressionEngine 2
 *
 * @package		ExpressionEngine
 * @subpackage	Modules
 * @category	Updater/Installer
 * @author    	Iain Urquhart <shout@iain.co.nz>
 * @author    	Phil Sturgeon - who wrote the original widgets module: https://github.com/philsturgeon/ee2-widgets
 * @copyright 	Copyright (c) 2012 Iain Urquhart
 * @license   	All Rights Reserved
*/
 
 
// ------------------------------------------------------------------------

/**
 * Entry widgets Module Install/Update File
 */

class Entry_widgets_upd {
	
	public $version = '1.0.1';
	
	private $EE;
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->EE =& get_instance();
	}

	function tabs()
	{			
		$tabs['entry_widgets'] = array(
			'entry_widgets' => array(
				'visible' => 'true',
				'collapse' => 'false',
				'htmlbuttons' => 'false',
				'width' => '100%'
			)
        );

    	return $tabs;
	}
	
	// ----------------------------------------------------------------
	
	/**
	 * Installation Method
	 *
	 * @return 	boolean 	TRUE
	 */
	public function install()
	{
		$mod_data = array(
			'module_name'			=> 'entry_widgets',
			'module_version'		=> $this->version,
			'has_cp_backend'		=> "y",
			'has_publish_fields'	=> 'y'
		);
		
		$this->EE->db->insert('modules', $mod_data);
		
		$this->EE->load->library('layout');
		$this->EE->layout->add_layout_tabs($this->tabs());

		$this->EE->db->query('CREATE TABLE '.$this->EE->db->dbprefix('entry_widget_areas').' (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `slug` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `title` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `site_id` int(11) NOT NULL DEFAULT 0,
		  PRIMARY KEY (`id`),
		  UNIQUE KEY `unique_slug` (`slug`)
		)');

		$this->EE->db->query('CREATE TABLE '.$this->EE->db->dbprefix('entry_widget_instances').' (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `entry_id` int(11) DEFAULT NULL,
		  `title` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
		  `widget_id` int(11) DEFAULT NULL,
		  `widget_area_id` int(11) DEFAULT NULL,
		  `options` text COLLATE utf8_unicode_ci NOT NULL,
		  `order` int(10) NOT NULL DEFAULT 0,
		  `created_on` int(11) NOT NULL DEFAULT 0,
		  `updated_on` int(11) NOT NULL DEFAULT 0,
		  `site_id` int(11) NOT NULL DEFAULT 0,
		  PRIMARY KEY (`id`)
		)');

		$this->EE->db->query('CREATE TABLE '.$this->EE->db->dbprefix('entry_widgets').' (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT "",
		  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT "",
		  `description` text COLLATE utf8_unicode_ci NOT NULL,
		  `author` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT "",
		  `website` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT "",
		  `version` int(3) NOT NULL DEFAULT 0,
		  `site_id` int(11) NOT NULL DEFAULT 0,
		  PRIMARY KEY (`id`)
		)');
		
		return TRUE;
	}

	// ----------------------------------------------------------------
	
	/**
	 * Uninstall
	 *
	 * @return 	boolean 	TRUE
	 */	
	public function uninstall()
	{
		$mod_id = $this->EE->db->select('module_id')
								->get_where('modules', array(
									'module_name'	=> 'entry_widgets'
								))->row('module_id');
		
		$this->EE->db->where('module_id', $mod_id)
					 ->delete('module_member_groups');
		
		$this->EE->db->where('module_name', 'entry_widgets')
					 ->delete('modules');
		
		$this->EE->load->library('layout');
		$this->EE->layout->delete_layout_tabs($this->tabs(), 'entry_widgets');

		$this->EE->load->dbforge();
		$this->EE->dbforge->drop_table('entry_widget_areas');
		$this->EE->dbforge->drop_table('entry_widget_instances');
		$this->EE->dbforge->drop_table('entry_widgets');
		
		return TRUE;
	}
	
	// ----------------------------------------------------------------
	
	/**
	 * Module Updater
	 *
	 * @return 	boolean 	TRUE
	 */	
	public function update($current = '')
	{
		// If you have updates, drop 'em in here.
		return TRUE;
	}
	
}
/* End of file upd.entry_widgets.php */
/* Location: /system/expressionengine/third_party/entry_widgets/upd.entry_widgets.php */