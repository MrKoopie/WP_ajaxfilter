<?php

namespace MrKoopie\WP_ajaxfilter;
use MrKoopie\WP_wrapper\WP_wrapper;
use MrKoopie\WP_ajaxfilter\Exceptions\no_such_method_exists_exception;

class configurator
{
    protected $mapped_fields;
    protected $input_data;
    protected $WP_wrapper;
    protected $config;
    protected $post_type = 'post';

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
     * @param  string $label The translation of the field.
     * @param  string $tech_name The technical field name of the field.
     * @return  object Returns $this
     */
    public function add_checkbox($label, $tech_name = null)
    {
        $this->add_field($label, $tech_name);

        return $this->set_field_config('type', 'checkbox');
    }

    /**
     * Sets the last field type to radiobutton
     *
     * @param  string $label The translation of the field.
     * @param  string $tech_name The technical field name of the field.
     * @return  object Returns $this
     */
    public function add_radiobutton($label, $tech_name = null)
    {
        $this->add_field($label, $tech_name);

        return $this->set_field_config('type', 'radiobutton');
    }

    /**
     * Sets the last field type to dropdown
     *
     * @param  string $label The translation of the field.
     * @param  string $tech_name The technical field name of the field.
     * @return  object Returns $this
     */
    public function add_dropdown($label, $tech_name = null)
    {
        $this->add_field($label, $tech_name);

        return $this->set_field_config('type', 'dropdown');
    }

    /**
     * Sets the last field type to text
     *
     * @param  string $label The translation of the field.
     * @param  string $tech_name The technical field name of the field.
     * @return  object Returns $this
     */
    public function add_textfield($label, $tech_name = null)
    {
        $this->add_field($label, $tech_name);

        return $this->set_field_config('type', 'text');
    }

    /******************************************************************
    *                                                                 *
    *                           DATA LOADERS                          *
    *                                                                 *
    ******************************************************************/

    /**
     * Load the data from a taxonomy
     * 
     * @param string $taxonomy_id The $taxonomy_id
     * @return  object $this
     */
    public function load_data_from_a_taxonomy($taxonomy_id)
    {
        $this->set_field_config('data_source', 'taxonomy');
        $this->set_field_config('taxonomy_id', $taxonomy_id);

        return $this;
    }

    /**
     * Load the data from an array
     * 
     * @param string $array The $array in the format []['slug', 'label']
     * @return  object $this
     */
    public function load_data_from_an_array($array)
    {
        $this->set_field_config('data_source', 'array');
        $this->set_field_config('filter_data', $array);

        return $this;
    }

    /******************************************************************
    *                                                                 *
    *                          CONFIG OPTIONS                         *
    *                                                                 *
    ******************************************************************/

    /**
     * Set the custom post type
     * 
     * @param  string $post_type The custom post type
     */
    public function set_post_type($post_type)
    {
        $this->post_type = $post_type;

        return $this;
    }

    /**
     * Get the set post type
     */
    public function get_post_type()
    {
        return $this->post_type;
    }

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
            throw new no_such_method_exists_exception('The method ' . $method . ' is not supported');

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
     * Set the ajax template.
     * 
     * @param  string $template The template file location (will be used via get_template_part($template) )
     */
    public function set_ajax_template($template)
    {
        $this->config['template'] = $template;

        return $this;
    }

    /******************************************************************
    *                                                                 *
    *                             HELPERS                             *
    *                                                                 *
    ******************************************************************/
    
    /**
     * Add the field
     * 
     * @param  string $label The translation
     * @param  string $tech_name The name of the field. This is optional, if no name is provided a name will be generated.
     */
    private function add_field($label, $tech_name = null)
    {
        $this->mapped_fields[]    = [
                                    'translation'    => $label,
                                    'field_name'           => $tech_name
                                ];

        return $this;
    }

    /**
     * Set the mapped field config
     * 
     * @param  string $tech_name The technical name of the field.
     * @param  string $value The value of the field.
     * @return  object Returns $this
     */
    private function set_field_config($tech_name, $value)
    {
        end($this->mapped_fields);
        $key = key($this->mapped_fields);

        $this->mapped_fields[ $key ][$tech_name] = $value;

        return $this;
    }
}
