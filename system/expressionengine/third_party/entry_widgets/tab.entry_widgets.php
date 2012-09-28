<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Entry Widgets Fieldtype for ExpressionEngine 2
 *
 * @package		ExpressionEngine
 * @subpackage	Module
 * @category	Tab
 * @author    	Iain Urquhart <shout@iain.co.nz>
 * @author    	Phil Sturgeon - who wrote the original widgets module: https://github.com/philsturgeon/ee2-widgets
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
		$this->cache 	   =& $this->EE->session->cache['entry_widgets_data'];

	}

	/**
	 * Add our tab and fields
	 */
	function publish_tabs($channel_id, $entry_id = '')
	{

		$widget_areas = $this->EE->entry_widget->list_areas();

		$settings = array();

		foreach ($widget_areas as $area) 
		{
			$settings[] = array(
				'field_id' => 		'widget_'.$area->slug,
				'field_label'       => $area->title,
				'field_required'    => 'n',
				'field_data'        => '',
				'field_list_items'  => '',
				'field_fmt'     => '',
				'field_instructions'    => '',
				'field_show_fmt'    => 'n',
				'field_pre_populate'    => 'n',
				'field_text_direction'  => 'ltr',
				'field_type'        => 'entry_widgets',
				'field_maxl'		=> '22',
				'field_settings'	=> array(
					'area_id' 	=> $area->id,
					'area_slug' => $area->slug
				)
			);
        }

        return $settings;
	}



	function publish_data_db($params)
	{

		$entry_id = $params['entry_id'];
		$widget_areas 	= $this->EE->entry_widget->list_areas();

		foreach ($widget_areas as $area) 
		{

			$widget_data = $params['mod_data']['widget_'.$area->slug];
			
			$instances = array();

			// do we have widget data
			if(!is_array($widget_data))
			{
				$this->EE->db->where('entry_id', $entry_id );
				$this->EE->db->where('widget_area_id', $area->id );
				$this->EE->db->delete('entry_widget_instances');
				continue;
			}

			// gather instance ids
			foreach($widget_data as $key => $widget)
			{
				if(isset($widget['instance_id']))
				{
					$instances[] = $widget['instance_id'];
				}
			}
			
			// remove any instances not submitted
			if($instances)
			{
				$this->EE->db->where_not_in('id', $instances);
				$this->EE->db->where('widget_area_id', $area->id );
				$this->EE->db->delete('entry_widget_instances');
			}

			// process new and update existing
			foreach($widget_data as $key => $widget)
			{
				$widget['options'] = (isset($widget['options'])) ? $widget['options'] : array();
				// edit an existing
				if( isset($widget['instance_id']) && $widget['instance_id'] != '')
				{
					$result = $this->EE->entry_widget->edit_instance(
						$widget['instance_id'], // this should be widget_instance_id
						$entry_id, 
						$widget['widget_id'],
						$widget['widget_area_id'], 
						$widget['options'],
						$key
					);
				}
				else // add new
				{
					$result = $this->EE->entry_widget->add_instance( 
						$entry_id, 
						$widget['widget_id'],
						$widget['widget_area_id'], 
						$widget['options'],
						$key
					);
				}
			}

		}

	}


	function validate_publish($params)
	{
		// @todo
	}


}
/* END Class */

/* End of file tab.entry_widgets.php */
/* Location: ./system/expressionengine/third_party/modules/entry_widgets/tab.entry_widgets.php */