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

    public function set_as_checkbox()
    {
        end($this->mapped_fields);
        $key = key($this->mapped_fields);

        $this->mapped_fields[ $key ]['type'] = 'checkbox';

        return $this;
    }
}
