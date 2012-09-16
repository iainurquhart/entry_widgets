<?php if (!defined('BASEPATH')) exit('No direct script access allowed.');

class MY_Form_validation extends CI_Form_validation
{
    

   /**
    * ----------------------------------------------------------
    * Set Value: Get the value from a form
    * Extends method to return posted data for fields with no rules
    * ----------------------------------------------------------
    * 
    * @param string $field
    * @param string $default
    * @return string
    */     
    
    function set_value($field = '', $default = '')
    {
        // no post?
        if (count($_POST) == 0){
            return $default;
        }
        
        // no rules for this field?
        if ( ! isset($this->_field_data[$field]))
        {
            $this->set_rules($field, '', '');
            
            // fieldname is an array
            if ($this->_field_data[$field]['is_array']){
                $keys = $this->_field_data[$field]['keys'];
                $value = $this->_traverse_array($_POST, $keys);
            }
            
            // fieldname is a key
            else{
                $value = isset($_POST[$field])? $_POST[$field] : FALSE;
            }
            
            // field was not in the post
            if($value === FALSE){
                return $default;
            }
            
            // add field value to postdata
            else{
                $this->_field_data[$field]['postdata'] = form_prep($value, $field);
            }
        }

        return parent::set_value($field, $default);
    }
    


   /**
    * ----------------------------------------------------------
    * Traverse Array: traverse array with given keys and return value or false if not set
    * ----------------------------------------------------------
    * 
    * @param array $array
    * @param array $keys
    * @return mixed
    */
    public function _traverse_array($array, $keys)
    {
        foreach($keys as $key)
        {    
            if( ! isset($array[$key]))
            {
                return FALSE;
            }
            
            $array = $array[$key];
        }
        
        return $array;
    }


}

/* End of file MY_Form_validation.php */ 