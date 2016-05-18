<?php

namespace MrKoopie\WP_ajaxfilter\Input;

use MrKoopie\WP_ajaxfilter\stub;
use MrKoopie\WP_wrapper\WP_wrapper;

/**
 * Class Checkbox.
 */
class base
{
    protected $base;
    protected $WP_wrapper;
    protected $stub;
    protected $input_data;
    protected $label;
    protected $field_name;
    protected $taxonomy_id;
    // protected $filter_options;

    /**
     * Can be taxonomy or array.
     */
    protected $data_source;
    protected $filter_data;

    /**
     * @param $label The translation shown in the HTML Code
     * @param $tech_name The technical name of the field
     */
    public function __construct($label, $field_name, $WP_wrapper = null, $stub = null)
    {
        $this->label = $label;
        $this->field_name = $field_name;

        if ($WP_wrapper != null) {
            $this->WP_wrapper = $WP_wrapper;
        } else {
            $this->WP_wrapper = new WP_wrapper();
        }

        if ($stub != null) {
            $this->stub = $stub;
        } else {
            $this->stub = new stub();
        }
    }

    /**
     * Load the taxonomy data from WordPress.
     *
     * @param $taxonomy_id The $taxonomy_id of WordPress
     */
    public function load_data_from_taxonomy($taxonomy_id)
    {
        $this->taxonomy_id = $taxonomy_id;
        $this->data_source = 'taxonomy';

        $terms = $this->WP_wrapper->get_terms($taxonomy_id);
        foreach ($terms as $taxonomy) {
            $tmp_taxonomy['slug'] = $taxonomy->slug;
            $tmp_taxonomy['label'] = $taxonomy->name;
            $tmp_taxonomy['term_id'] = $taxonomy->term_id;

            $this->filter_data[] = $tmp_taxonomy;
            unset($tmp_taxonomy);
        }
    }

    /**
     * Load the array.
     *
     * @param array $array The $array in the format []['slug', 'label']
     */
    public function load_data_from_array($array)
    {
        $this->data_source = 'array';

        foreach ($array as $key => $data) {
            $tmp_data['slug'] = $data['slug'];
            $tmp_data['label'] = $data['label'];

            $this->filter_data[] = $tmp_data;
            unset($tmp_data);
        }
    }

    /**
     * Pass the input data to the input type.
     *
     * @param string $input_data The input data for this plugin
     */
    public function set_input_data($input_data)
    {
        $this->input_data = $input_data;
    }

    /**
     * Return the input data to the input type.
     *
     * @return string The input data for this plugin
     */
    public function get_input_data()
    {
        return $this->input_data;
    }

    /**
     * @return string the taxonomy id
     */
    public function get_taxonomy_id()
    {
        return $this->taxonomy_id;
    }

    /**
     * @return array The filter data.
     */
    public function get_filter_data()
    {
        return $this->filter_data;
    }
}
