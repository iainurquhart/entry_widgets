<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Entry_widget
{
	private $_widget = NULL;
	private $_rendered_areas = array();
	private $_widgets = array();

	function __construct()
	{

		$this->EE =& get_instance();
		$this->EE->load->model('entry_widgets_m');

		// allow config override of where widgets are located.
		if($this->EE->config->item('entry_widget_path'))
		{	
			$paths = array(
				$this->EE->config->item('entry_widget_path')
			);
			$widget_path = '';
		}
		else // default to our /entry_widgets/widgets folder
		{
			$paths = $this->EE->load->get_package_paths();
			$widget_path = 'widgets';
		}


		// Map where all widgets are
		foreach ($paths as $path)
		{
			
			$widgets = glob($path.$widget_path.'/*', GLOB_ONLYDIR);

			if ( ! is_array($widgets))
			{
				$widgets = array();
			}

			foreach ($widgets as $widget_path)
			{
				$slug = basename($widget_path);
				// Set this so we know where it is later
				$this->_widgets[$slug] = $widget_path . '/';
			}

		}
	}

	// --------------------------------------------------------------------
		
	/**
	 * List Uninstalled Widgets
	 *
	 * @access	public
	 * @return	array
	 *
	 */

	function list_uninstalled_widgets()
	{
		$available = $this->list_available_widgets();
		$available_slugs = array();

		foreach ($available as $widget)
		{
			$available_slugs[] = $widget->slug;
		}

		$uninstalled = array();
		foreach ($this->_widgets as $widget_path)
		{
			$slug = basename($widget_path);

			if ( ! in_array($slug, $available_slugs) && $widget = $this->read_widget($slug))
			{
				$uninstalled[] = $widget;
			}
		}

		return $uninstalled;
	}

	// --------------------------------------------------------------------
		
	/**
	 * List Uninstalled Widgets
	 *
	 * @access	public
	 * @return	array
	 *
	 */

	function list_available_widgets()
	{
		return $this->EE->entry_widgets_m->get_widgets();
	}

	// --------------------------------------------------------------------
		
	/**
	 * Read Wudget
	 *
	 * @access	public
	 * @return	array
	 *
	 */
	function read_widget($slug)
	{
    	$this->_spawn_widget($slug);

		if ($this->_widget === FALSE)
		{
			return FALSE;
		}

    	$widget = (object) get_object_vars($this->_widget);
    	$widget->slug = $slug;
   
       	return $widget;
	}


	// --------------------------------------------------------------------
		
	/**
	 * Spawn Widget
	 *
	 * @access	private
	 * @return	array
	 *
	 */

	private function _spawn_widget($name)
    {

		$widget_path = $this->_widgets[$name];

		if (file_exists($widget_path . $name . EXT))
		{
			require_once $widget_path . $name . EXT;
			$class_name = 'Widget_'.ucfirst($name);

			$this->_widget = new $class_name;

			$this->_widget->path = $widget_path;

			return;
		}

		$this->_widget = NULL;
    }


	// --------------------------------------------------------------------
		
	/**
	 * Add Widget
	 *
	 * @access	private
	 * @return	array
	 *
	 */

    function add_widget($input)
	{
		return $this->EE->entry_widgets_m->insert_widget($input);
	}


	// --------------------------------------------------------------------
		
	/**
	 * Get Widget
	 *
	 * @access	private
	 * @return	array
	 *
	 */


	function get_widget($id)
	{
		return is_numeric($id)
			? $this->EE->entry_widgets_m->get_widget_by('id', $id)
			: $this->EE->entry_widgets_m->get_widget_by('slug', $id);
	}

	function get_widget_instance($id)
	{
		return $this->EE->entry_widgets_m->get_widget_instance_by('id', $id);
	}

	// --------------------------------------------------------------------
		
	/**
	 * Get Area
	 *
	 * @access	private
	 * @return	array
	 *
	 */

	function get_area($id)
	{
		return is_numeric($id)
			? $this->EE->entry_widgets_m->get_area_by('id', $id)
			: $this->EE->entry_widgets_m->get_area_by('slug', $id);
	}

	// --------------------------------------------------------------------
		
	/**
	 * Get Area
	 *
	 * @access	private
	 * @return	array
	 *
	 */

	function list_areas()
	{
		return $this->EE->entry_widgets_m->get_areas();
	}







	function render_backend($name, $saved_data = array(), $name_atribute, $instance = '')
	{

		$this->_spawn_widget($name);

		// No fields, no backend, no rendering
		if (empty($this->_widget->fields))
		{
			return '';
		}

		$options = array();

		foreach ($this->_widget->fields as $field)
		{
			$field_name = &$field['field'];

			// not sure why this converts to entities, need to review
			// @iain @todo
			//$options[$field_name] = set_value($field_name, @$saved_data[$field_name]);
			$options[$field_name] = @$saved_data[$field_name];


		}

		$options['widget_id'] = (isset($instance['id'])) ? $instance['id'] : '';

		// Check for default data if there is any
		$data = method_exists($this->_widget, 'form') ? call_user_func(array(&$this->_widget, 'form'), $options) : array();
		$data['field_name'] = $name_atribute;
		$data['slug'] = $name;

		// Options we'rent changed, lets use the defaults
		isset($data['options']) OR $data['options'] = $options;

		return $this->load_view('form', $data);
	}





	protected function load_view($view, $data = array())
	{
		$path = isset($this->_widget->path) ? $this->_widget->path : $this->path;

		$this->EE->load->vars($data);

		return $this->EE->load->file($path.'views/'.$view.'.php', TRUE);
	}

/*

$widget['instance_id'], // this should be widget_instance_id
					$entry_id, 
					$widget['widget_id']
					$widget['title'], 
					$widget['widget_area_id'], 
					$widget['options']
*/
	function edit_instance($instance_id, $entry_id, $widget_id, $widget_area_id, $options = array(), $key)
	{

		
		$slug = $this->EE->entry_widgets_m->get_widget_by('id', $widget_id)->slug;

		// The widget has to do some stuff before it saves
		$options = $this->EE->entry_widget->prepare_options($slug, $options);

		$this->EE->entry_widgets_m->update_instance(
			$instance_id, 
				array(
					'entry_id' => $entry_id,
					'widget_area_id' => $widget_area_id,
					'options' => $this->encode_options($options)
				),
			$key
		);

		return array('status' => 'success');
	}





	function add_instance($entry_id, $widget_id, $widget_area_id, $options = array(), $key)
	{



		$slug = $this->get_widget($widget_id)->slug;



		/*
		if ( $error = $this->validation_errors($slug, $options, $key) )
		{

			return array('status' => 'error', 'error' => $error);
		}*/
		

		// The widget has to do some stuff before it saves
		$options = $this->prepare_options($slug, $options);

		

		$this->EE->entry_widgets_m->insert_instance(array(
			'entry_id' => $entry_id,
			'widget_id' => $widget_id,
			'widget_area_id' => $widget_area_id,
			'options' => $this->encode_options($options),
			'order'	=> $key
		));

		return array('status' => 'success');
	}




	function validation_errors($name, $options, $key)
	{
		$this->_spawn_widget($name);

	    if (property_exists($this->_widget, 'fields'))
    	{
    		
    		$_POST = $options;

    		// print_r($this->_widget->fields); exit('here');

    		foreach($this->_widget->fields as &$field)
    		{
    			// $field['field'] = 'widget_data['.$key.'][options]['.$field['field'].']';
    		}
    		
    		// print_r($this->_widget->fields); 
    		// entry_widgets__widget_data[1][html]
    		// entry_widgets__widget_data[1][options][html]
    		
    		$this->EE->load->library('form_validation');
    		$this->EE->form_validation->set_rules($this->_widget->fields);



    		if (!$this->EE->form_validation->run('', FALSE))
    		{
    			return validation_errors();
    		}
    		
    	}
	}



	function prepare_options($name, $options = array())
    {
    	$this->_widget OR $this->_spawn_widget($name);

    	if (method_exists($this->_widget, 'save'))
	    {
			return (array) call_user_func(array(&$this->_widget, 'save'), $options);
	    }

	    return $options;
    }


    public function encode_options($options)
	{
		return json_encode((array) $options);
	}

	public function decode_options($options)
	{
		return (array) json_decode($options, TRUE);
	}



	function list_area_instances($slug, $entry_id = '')
	{
		return $this->EE->entry_widgets_m->get_by_area($slug, $entry_id);
	}


	function add_area($input)
	{
		if($input->title == '' || $input->slug == '')
			show_error('You must add a title and a slug.');

		$this->EE->load->helper('url');

		$input->slug = url_title($input->slug, '_', TRUE);

		return $this->EE->entry_widgets_m->insert_area((array)$input);
	}

	function edit_area($input)
	{
		if($input->title == '' || $input->slug == '')
			show_error('You must add a title and a slug.');

		$this->EE->load->helper('url');

		$input->slug = url_title($input->slug, '_', TRUE);

		return $this->EE->entry_widgets_m->insert_area((array)$input);
	}

	function update_area($input)
	{
		if($input->title == '' || $input->slug == '')
			show_error('You must add a title and a slug.');

		$this->EE->load->helper('url');

		$input->slug = url_title($input->slug, '_', TRUE);

		return $this->EE->entry_widgets_m->update_area((array)$input);
	}



	function get_instance($instance_id)
	{
		$widget = $this->EE->entry_widgets_m->get_instance($instance_id);

		if ($widget)
		{
			$widget->options = $this->decode_options($widget->options);

			return $widget;
		}

		return FALSE;
	}



	function render($name, $options = array())
    {
    	$this->_spawn_widget($name);

        $data = method_exists($this->_widget, 'run')
			? call_user_func(array($this->_widget, 'run'), $options)
			: array();

		// Don't run this widget
		if ($data === FALSE)
		{
			return FALSE;
		}

		// If we have TRUE, just make an empty array
		$data !== TRUE OR $data = array();

		// convert to array
		is_array($data) OR $data = (array) $data;

		$widget_template = $this->load_view('display', '', TRUE);
		$vars = array($data);

		$widget_template = ee()->functions->prep_conditionals($widget_template, $vars);

		return $this->EE->TMPL->parse_variables($widget_template, $vars);

    }







}