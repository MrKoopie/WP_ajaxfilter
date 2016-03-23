<?php

namespace MrKoopie\WP_ajaxfilter;
use MrKoopie\WP_wrapper\WP_wrapper;
use Exceptions\No_such_action_method;

class generator
{
    protected $mapped_fields;
    protected $WP_wrapper;
    protected $config;

    /**
     * WordPress does not have dependency injection.
     */
    public function __construct($form_id, $WP_wrapper = null)
    {
        $this->config['form_id'] = $form_id;

        if($WP_wrapper != null)
            $this->WP_wrapper = $WP_wrapper;
        else
            $this->WP_wrapper = new WP_wrapper();
    }

    /******************************************************************
    *                                                                 *
    *                         SET INPUT TYPE                          *
    *                                                                 *
    ******************************************************************/

    /**
     * Sets the last field type to checkbox
     *
     * @param  string $translation The translation of the field.
     * @param  string $name The field name of the field.
     * @return  object Returns $this
     */
    public function add_checkbox($translation, $name = null)
    {
        $this->add_field($translation, $name);

        return $this->set_field_config('type', 'checkbox');
    }

    /**
     * Sets the last field type to radiobutton
     *
     * @param  string $translation The translation of the field.
     * @param  string $name The field name of the field.
     * @return  object Returns $this
     */
    public function add_radiobutton($translation, $name = null)
    {
        $this->add_field($translation, $name);

        return $this->set_field_config('type', 'radiobutton');
    }

    /**
     * Sets the last field type to dropdown
     *
     * @param  string $translation The translation of the field.
     * @param  string $name The field name of the field.
     * @return  object Returns $this
     */
    public function add_dropdown($translation, $name = null)
    {
        $this->add_field($translation, $name);

        return $this->set_field_config('type', 'dropdown');
    }

    /**
     * Sets the last field type to text
     *
     * @param  string $translation The translation of the field.
     * @param  string $name The field name of the field.
     * @return  object Returns $this
     */
    public function add_textfield($translation, $name = null)
    {
        $this->add_field($translation, $name);

        return $this->set_field_config('type', 'text');
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
        $terms = $this->WP_wrapper->get_terms($taxonomy);

        $data_array = [];

        foreach($terms as $term)
        {
            $data_array[$term->id] = $term->name;
        }
        
        return $this->set_field_config('data_source', 'taxonomy')
                    ->set_field_config('data_taxonomy_name', $taxonomy)
                    ->set_field_config('data_array', $data_array);
    }

    /**
     * Load data from an array.
     * Input style: ['unique_html_input_field_name' => 'value']
     * 
     * @param  array $array The data.
     * @return  object Returns $this
     */
    public function load_data_from_an_array($array)
    {
        return $this->set_field_config('data_source', 'array')
                    ->set_field_config('data_array', $array);
    }

    /******************************************************************
    *                                                                 *
    *                          CONFIG OPTIONS                         *
    *                                                                 *
    ******************************************************************/

    /**
     * Set the form method
     * 
     * @param string $method The method. Can be post or get.
     */
    public function set_method($method)
    {
        if($method == 'post')
            $this->config['method'] = 'post';

        elseif($method == 'get')
            $this->config['method'] = 'get';
        else
            throw new Exceptions\no_such_method_exists_exception('The method ' . $method . ' is not supported');

        return $this;
    }

    /**
     * Set the action
     * 
     * @param string $action The action (URL) where the data should be send to.
     */
    public function set_action($action)
    {
        $this->config['action'] = $action;

        return $this;
    }

    /******************************************************************
    *                                                                 *
    *                          THE GENERATOR                          *
    *                                                                 *
    ******************************************************************/

    public function generate_html()
    {
        
    }


    /******************************************************************
    *                                                                 *
    *                             VARIOUS                             *
    *                                                                 *
    ******************************************************************/

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
     * Get the config
     * 
     * @return  array The config settings.
     */
    public function get_config_settings()
    {
        return $this->config;
    }
 
    /**
     * Add the field
     * 
     * @param  string $translation The translation
     * @param  string $name The name of the field. This is optional, if no name is provided a name will be generated.
     */
    private function add_field($translation, $name = null)
    {
        $this->mapped_fields[]    = [
                                    'translation'    => $translation,
                                    'name'           => $name
                                ];

        return $this;
    }

    /**
     * Set the mapped field config
     * 
     * @return  object Returns $this
     */
    public function set_field_config($name, $value)
    {
        end($this->mapped_fields);
        $key = key($this->mapped_fields);

        $this->mapped_fields[ $key ][$name] = $value;

        return $this;
    }
}
