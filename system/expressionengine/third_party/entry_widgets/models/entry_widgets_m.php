<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Entry_widgets_m
{

	protected $site_id;
	
	public function __construct()
	{
		$this->EE =& get_instance();
		$this->site_id = $this->EE->config->item('site_id');
		$this->widget_cache =& $this->EE->session->cache['entry_widgets_data'];
	}

	function get_widgets()
	{
		return $this->EE->db
			->get('entry_widgets')
			->result();
	}

	public function insert_widget($input)
	{
		$this->EE->db->insert('entry_widgets', array(
			'title' 		=> $input['title'],
			'slug' 			=> $input['slug'],
			'description' 	=> $input['description'],
			'author' 		=> $input['author'],
			'website' 		=> $input['website'],
			'version' 		=> $input['version']
		));

		return $this->EE->db->insert_id();
	}

	public function get_widget_instance_by($field, $id)
	{
		return $this->EE->db
			->where($field, $id)
			->get('entry_widget_instances')
			->row();
	}

	public function get_widget_by($field, $id)
	{

		return $this->EE->db
			->where($field, $id)
			->get('entry_widgets')
			->row();
	}

	public function get_area_by($field, $id)
	{
		return $this->EE->db
			->where('site_id', $this->site_id)
			->where($field, $id)
			->get('entry_widget_areas')
			->row();
	}

	public function get_areas()
	{
		return $this->EE->db
			->where('site_id', $this->site_id)
			->get('entry_widget_areas')
			->result();
	}

	public function insert_instance($input)
	{

		$this->EE->load->helper('date');
		
		$last_widget = $this->EE->db->select('`order`')
			->order_by('`order`', 'desc')
			->limit(1)
			->get_where('entry_widget_instances', array('widget_area_id' => $input['widget_area_id']))
			->row();
			
		$order = isset($last_widget->order) ? $last_widget->order + 1 : 1;
		
		$this->EE->db->insert('entry_widget_instances', array(
			'entry_id' 	 => $input['entry_id'],
			'widget_id'  => $input['widget_id'],
			'widget_area_id' => $input['widget_area_id'],
			'options' 	 => $input['options'],
			'`order`' 	 => $input['order'],
			'created_on' => now(),
			'updated_on' => now(),
			'site_id' 	 => $this->site_id,
			'is_draft' => (isset($this->widget_cache['is_draft']) && $this->widget_cache['is_draft'] == 1) ? 1 : 0
		));



		return $this->EE->db->insert_id();
	}


	public function update_instance($instance_id, $input, $key)
	{
		$this->EE->db->where('id', $instance_id);
		$this->EE->db->where('site_id', $this->site_id);
		
		return $this->EE->db->update(
			'entry_widget_instances', 
			array(
				'widget_area_id' => $input['widget_area_id'],
				'options' => $input['options'],
				'order' => $key,
				'is_draft' => (isset($this->widget_cache['is_draft']) && $this->widget_cache['is_draft'] == 1) ? 1 : 0
			)
		);
	}


	function get_by_area($slug, $entry_id = '')
	{
		$this->EE->db->select('wi.id, w.slug, wi.id as instance_id, wi.widget_id, wi.widget_area_id, wa.slug as widget_area_slug, wi.options')
			->from('entry_widget_areas wa')
			->join('entry_widget_instances wi', 'wa.id = wi.widget_area_id')
			->join('entry_widgets w', 'wi.widget_id = w.id')
			->where('wa.slug', $slug)
			->where('wi.site_id', $this->site_id);

		if (isset($this->EE->session->cache['ep_better_workflow']['is_draft']) 
			&& $this->EE->session->cache['ep_better_workflow']['is_draft'])
		{
			$this->EE->db->where('wi.is_draft', 1);
		}
		else
		{
			$this->EE->db->where('wi.is_draft', 0);
		}
		

		if($entry_id)
		{
			$this->EE->db->where('wi.entry_id', $entry_id);
		}

		$this->EE->db->order_by('wi.order');

		return $this->EE->db->get()->result();
	}




	public function insert_area($input)
	{
		$this->EE->db->insert('entry_widget_areas', array(
			'title' => $input['title'],
			'slug' 	=> $input['slug'],
			'show_shortcode' 	=> $input['show_shortcode'],
			'site_id' => $this->site_id
		));

		return $this->EE->db->insert_id();
	}



	function get_instance($id)
	{
		$this->EE->db->select('w.id, w.slug, wi.id as instance_id, wi.widget_area_id, wa.slug as widget_area_slug, wi.options')
			->from('entry_widget_areas wa')
			->join('entry_widget_instances wi', 'wa.id = wi.widget_area_id')
			->join('entry_widgets w', 'wi.widget_id = w.id')
			->where('wi.site_id', $this->site_id)
			->where('wi.id', $id);

		return $this->EE->db->get()->row();
	}

}