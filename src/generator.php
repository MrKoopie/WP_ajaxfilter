<?php

namespace MrKoopie\WP_ajaxfilter;

class generator
{
	protected $mapped_fields;

    public function add_field($translation, $name)
    {
    	$this->mapped_fields[] 	= [
    	    						'name'			=> $name,
    	    						'translation' 	=> $translation
    							];

        return $this;
    }


    /**
     * Get the mapped_fields
     */
    public function get_mapped_fields()
    {
        return $this->mapped_fields;
    }

    /**
     * Set the field type to $type
     * 
     * @param  string $type The type (like checkbox, dropdown, etc)
     * @return  object Returns $this
     */
    public function set_as($type)
    {
        end($this->mapped_fields);
        $key = key($this->mapped_fields);

        $this->mapped_fields[ $key ]['type'] = $type;

        return $this;
    }

    /**
     * Sets the last field type to checkbox
     *
     * @return  object Returns $this
     */
    public function set_as_checkbox()
    {
        return $this->set_as('checkbox');
    }

    /**
     * Sets the last field type to radiobutton
     *
     * @return  object Returns $this
     */
    public function set_as_radiobutton()
    {
        return $this->set_as('radiobutton');
    }

    /**
     * Sets the last field type to dropdown
     *
     * @return  object Returns $this
     */
    public function set_as_dropdown()
    {
        return $this->set_as('dropdown');
    }

    /**
     * Sets the last field type to text
     *
     * @return  object Returns $this
     */
    public function set_as_text()
    {
        return $this->set_as('text');
    }

    
}
