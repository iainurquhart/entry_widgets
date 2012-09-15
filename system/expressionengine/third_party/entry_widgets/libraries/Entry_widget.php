<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Entry_widget
{
	private $_widget = NULL;
	private $_rendered_areas = array();
	private $_widget_locations = array();

	function __construct()
	{

		$this->EE =& get_instance();
		$this->EE->load->model('entry_widgets_m');

		// Map where all widgets are
		foreach ($this->EE->load->get_package_paths() as $path)
		{
			
			$widgets = glob($path.'entry_widget/*', GLOB_ONLYDIR);

			if ( ! is_array($widgets))
			{
				$widgets = array();
			}

			foreach ($widgets as $widget_path)
			{
				$slug = basename($widget_path);
				// Set this so we know where it is later
				$this->_widget_locations[$slug] = $widget_path . '/';
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
		foreach ($this->_widget_locations as $widget_path)
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
		$widget_path = $this->_widget_locations[$name];

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







	function render_backend($name, $saved_data = array(), $name_atribute)
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

			$options[$field_name] = set_value($field_name, @$saved_data[$field_name]);
		}

		// Check for default data if there is any
		$data = method_exists($this->_widget, 'form') ? call_user_func(array(&$this->_widget, 'form'), $options) : array();
		$data['field_name'] = $name_atribute;

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





	function edit_instance($instance_id, $entry_id, $title, $widget_area_id, $options = array())
	{
		$slug = $this->EE->entry_widgets_m->get_instance($instance_id)->slug;

		if ( $error = $this->validation_errors($slug, $options) )
		{
			return array('status' => 'error', 'error' => $error);
		}

		// The widget has to do some stuff before it saves
		$options = $this->EE->entry_widget->prepare_options($slug, $options);

		$this->EE->entry_widgets_m->update_instance(
			$instance_id, 
				array(
					'entry_id' => $entry_id,
					'title' => $title,
					'widget_area_id' => $widget_area_id,
					'options' => $this->_serialize_options($options)
				)
			);

		return array('status' => 'success');
	}





	function add_instance($entry_id, $title, $widget_id, $widget_area_id, $options = array())
	{
		$slug = $this->get_widget($widget_id)->slug;

		if ( $error = $this->validation_errors($slug, $options) )
		{
			return array('status' => 'error', 'error' => $error);
		}

		// The widget has to do some stuff before it saves
		$options = $this->prepare_options($slug, $options);

		$this->EE->entry_widgets_m->insert_instance(array(
			'entry_id' => $entry_id,
			'title' => $title,
			'widget_id' => $widget_id,
			'widget_area_id' => $widget_area_id,
			'options' => $this->_serialize_options($options)
		));

		return array('status' => 'success');
	}




	function validation_errors($name, $options)
	{
		$this->_widget || $this->_spawn_widget($name);

	    if (property_exists($this->_widget, 'fields'))
    	{
    		$_POST = $options;

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


    private function _serialize_options($options)
	{
		return serialize((array) $options);
	}

	public function _unserialize_options($options)
	{
		return (array) unserialize($options);
	}



	function list_area_instances($slug)
	{
		return $this->EE->entry_widgets_m->get_by_area($slug);
	}







}