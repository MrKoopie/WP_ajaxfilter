<?php

namespace MrKoopie\WP_ajaxfilter;
use MrKoopie\WP_wrapper\WP_wrapper;

class generator
{
    protected $mapped_fields;
    protected $WP_wrapper;

    /**
     * WordPress does not have dependency injection.
     */
    public function __construct($WP_wrapper = null)
    {
        if($WP_wrapper != null)
            $this->WP_wrapper = $WP_wrapper;
        else
            $this->WP_wrapper = new WP_wrapper();
    }

    public function add_field($translation, $name)
    {
        $this->mapped_fields[]    = [
                                    'name'            => $name,
                                    'translation'    => $translation
                                ];

        return $this;
    }


    /**
     * Get the mapped_fields
     * 
     * @return  array The mapped fields.
     */
    public function get_mapped_fields()
    {
        return $this->mapped_fields;
    }

    /**
     * Set the mapped field config
     * 
     * @return  object Returns $this
     */
    public function set_mapped_field_config($name, $value)
    {
        end($this->mapped_fields);
        $key = key($this->mapped_fields);

        $this->mapped_fields[ $key ][$name] = $value;

        return $this;
    }

    /******************************************************************
    *                                                                 *
    *                         SET INPUT TYPE                          *
    *                                                                 *
    ******************************************************************/

    /**
     * Sets the last field type to checkbox
     *
     * @return  object Returns $this
     */
    public function set_as_checkbox()
    {
        return $this->set_mapped_field_config('type', 'checkbox');
    }

    /**
     * Sets the last field type to radiobutton
     *
     * @return  object Returns $this
     */
    public function set_as_radiobutton()
    {
        return $this->set_mapped_field_config('type', 'radiobutton');
    }

    /**
     * Sets the last field type to dropdown
     *
     * @return  object Returns $this
     */
    public function set_as_dropdown()
    {
        return $this->set_mapped_field_config('type', 'dropdown');
    }

    /**
     * Sets the last field type to text
     *
     * @return  object Returns $this
     */
    public function set_as_text()
    {
        return $this->set_mapped_field_config('type', 'text');
    }

    /******************************************************************
    *                                                                 *
    *                        DATA LOAD METHODS                        *
    *                                                                 *
    ******************************************************************/

    /**
     * Load data from a taxonomy
     * 
     * @param  string $taxonomy The technical name of a taxonomy
     * @return  object Returns $this
     */
    public function load_data_from_a_taxonomy($taxonomy)
    {
        return $this->set_mapped_field_config('data_source', 'taxonomy')
                    ->set_mapped_field_config('data_taxonomy_name', $taxonomy);
    }

    /**
     * Load data from a taxonomy
     * 
     * @param  array $array The data.
     * @return  object Returns $this
     */
    public function load_data_from_an_array($array)
    {
        return $this->set_mapped_field_config('data_source', 'array')
                    ->set_mapped_field_config('data_array', $array);
    }
}
