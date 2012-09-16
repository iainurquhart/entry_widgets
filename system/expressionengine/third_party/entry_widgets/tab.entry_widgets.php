<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Global Fields for ExpressionEngine 2
 *
 * @package		ExpressionEngine
 * @subpackage	Modules
 * @category	Tab
 * @author    	Iain Urquhart <shout@iain.co.nz>
 * @copyright 	Copyright (c) 2012 Iain Urquhart
 * @license   	All Rights Reserved
*/

// ------------------------------------------------------------------------

/**
 * Tab Builder
 */

class Entry_widgets_tab {
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->EE =& get_instance();

		$this->EE->lang->loadfile('entry_widgets');
		if( $this->EE->config->item('entry_widgets_tab_name') ) 
		{
			$this->EE->lang->language['entry_widgets'] = $this->EE->config->item('entry_widgets_tab_name');
		}

		$this->EE->load->library('entry_widget');

	}

	/**
	 * Add our tab and fields
	 */
	function publish_tabs($channel_id, $entry_id = '')
	{

		$settings[] = array(
            'field_id' => 		'widget_data',
            'field_label'       => 'Page Widgets',
            'field_required'    => 'n',
            'field_data'        => $entry_id,
            'field_list_items'  => '',
            'field_fmt'     => '',
            'field_instructions'    => '',
            'field_show_fmt'    => 'n',
            'field_pre_populate'    => 'n',
            'field_text_direction'  => 'ltr',
            'field_type'        => 'entry_widgets',
            'field_maxl'		=> '22'
        );

        return $settings;
	}

	function publish_data_db($params)
	{


		$entry_id = $params['entry_id'];
		$widget_data = $params['mod_data']['widget_data'];
		$instances = array();

		// do we have widget data
		if(!is_array($widget_data))
		{
			$this->EE->db->where('entry_id', $entry_id );
			$this->EE->db->delete('entry_widget_instances');
			return;
		}

		// prune any instances not received
		foreach($widget_data as $key => $widget)
		{
			// build our list of previous instance ids
			if(isset($widget['instance_id']))
			{
				$instances[] = $widget['instance_id'];
			}
		}
			
		if($instances)
		{
			$this->EE->db->where_not_in('id', $instances);
			$this->EE->db->delete('entry_widget_instances');
		}

		// process new and update existing
		foreach($widget_data as $key => $widget)
		{

			if( isset($widget['instance_id']) && $widget['instance_id'] != '') // editing an instance
			{
				
				$result = $this->EE->entry_widget->edit_instance(
					$widget['instance_id'], // this should be widget_instance_id
					$entry_id, 
					$widget['widget_id'],
					$widget['title'], 
					$widget['widget_area_id'], 
					$widget['options'],
					$key
				);

			}
			else
			{

				$result = $this->EE->entry_widget->add_instance( 
					$entry_id, 
					$widget['title'], 
					$widget['widget_id'],
					$widget['widget_area_id'], 
					$widget['options'],
					$key
				);

			}
			
			
		}
	}

	/**
	 * Merge our tab data into the main channel data array
	 * So EE just takes it as if it was in the post data
	 */
	function validate_publish($params)
	{

	    

	}


}
/* END Class */

/* End of file tab.entry_widgets.php */
/* Location: ./system/expressionengine/third_party/modules/entry_widgets/tab.entry_widgets.php */