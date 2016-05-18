<?php

namespace MrKoopie\WP_ajaxfilter;

use MrKoopie\WP_ajaxfilter\Exceptions\no_data_to_render_exception;

class generator extends configurator
{
    protected $loaded_classes = false;
    protected $stub;
    protected $filter_data;

    /**
     * WordPress does not have dependency injection.
     */
    public function __construct($form_id, $WP_wrapper = null, $stub = null)
    {
        parent::__construct($form_id, $WP_wrapper);

        if (isset($_GET['MRK_af_'.$form_id])) {
            $this->it_can_load_input_data($_GET['MRK_af_'.$form_id]);
        }

        if ($stub != null) {
            $this->stub = $stub;
        } else {
            $this->stub = new stub();
        }
    }

    /**
     * Registers the filters and ajax listener.
     *
     * @return object $this
     */
    public function render()
    {
        if (count($this->mapped_fields) == 0) {
            throw new no_data_to_render_exception();
        }

        // Register the ajax actions.
        $this->WP_wrapper->add_action('wp_ajax_wpf_'.$this->config['form_id'], [$this, 'ajax']);
        $this->WP_wrapper->add_action('wp_ajax_nopriv_wpf_'.$this->config['form_id'], [$this, 'ajax']);

        // Enqueue the jQuery filter
        $this->WP_wrapper->add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);

        // Register the WP_query filter
        $this->WP_wrapper->add_action('pre_get_posts', [$this, 'filter_query']);

        return $this;
    }

    /**
     * Enqueue the jQuery file.
     */
    public function enqueue_scripts()
    {
        $this->WP_wrapper->wp_register_script('MRK-ajax-filter', $this->WP_wrapper->get_stylesheet_directory_uri().'/vendor/mrkoopie/wp_ajaxfilter/assets/js/wp_ajaxfilter.js', ['jquery']);

        // Localize the script with new data
        $translation_array = [
            'loading' => $this->WP_wrapper->esc_html__('Loading...', 'mrka'),
        ];
        $this->WP_wrapper->wp_localize_script('MRK-ajax-filter', 'mrka', $translation_array);

        $this->WP_wrapper->wp_enqueue_script('MRK-ajax-filter');
    }

    /**
     * Process the filters and load the ajax template.
     *
     * @todo Make a test
     */
    public function ajax()
    {
        $args['post_type'] = $this->post_type;

        $this->WP_wrapper->query_posts($args);

        $this->WP_wrapper->get_template_part($this->config['template']);

        $this->WP_wrapper->exit();
    }

    /**
     * Add a query filter to the main WordPress query.
     * Using this trick, we can enable the filter at once for both the ajax requests
     * and the regular requests.
     *
     * @param object $query WP_Query object
     *
     * @return object (un)modified WP_Query object
     */
    public function filter_query($query)
    {
        // We only need to execute this code when we have found the key that belongs to this form.
        if (isset($_GET['mrka_id']) && $_GET['mrka_id'] == $this->config['form_id'] && $query->get('post_type') == $this->post_type) {
            $this->load_field_classes();

            if (isset($_GET['mrka_val'])) {
                $this->filter_raw_input_data($_GET['mrka_val']);
            }

            foreach ($this->mapped_fields as $mapped_field) {
                // Load the taxonomy data when available.
                if (isset($mapped_field['taxonomy_id'])) {
                    $mapped_field['class']->load_data_from_taxonomy($mapped_field['taxonomy_id']);
                }

                // Load data from an array
                   if (isset($mapped_field['filter_data'])) {
                       $mapped_field['class']->load_data_from_array($mapped_field['filter_data']);
                   }

                if (isset($this->input_data[$mapped_field['field_name']])) {
                    $mapped_field['class']->set_input_data($this->input_data[$mapped_field['field_name']]);
                }

                $query = $mapped_field['class']->filter($query);
            }
        }

        return $query;
    }

    /**
     * Generate the form HTML code.
     *
     * @return string The HTML code
     */
    public function html()
    {
        $this->load_field_classes();

        if (isset($_GET['mrka_val'])) {
            $this->filter_raw_input_data($_GET['mrka_val']);
        }

        $fields = '';

        foreach ($this->mapped_fields as $mapped_field) {
            // Load the taxonomy data when available.
            if (isset($mapped_field['taxonomy_id'])) {
                $mapped_field['class']->load_data_from_taxonomy($mapped_field['taxonomy_id']);
            }

            // Load data from an array
               if (isset($mapped_field['filter_data'])) {
                   $mapped_field['class']->load_data_from_array($mapped_field['filter_data']);
               }

            if (isset($this->input_data[$mapped_field['field_name']])) {
                $mapped_field['class']->set_input_data($this->input_data[$mapped_field['field_name']]);
            }

            $fields .= $mapped_field['class']->generate_html();
        }

        $parameters = [
                    'form_id'  => $this->config['form_id'],
                    'site_url' => $this->WP_wrapper->get_site_url().'/wp-admin/admin-ajax.php',
                    'fields'   => $fields,
                    ];

        return $this->stub->parse_stub('form', $parameters);
    }

    /******************************************************************
    *                                                                 *
    *                             HELPERS                             *
    *                                                                 *
    ******************************************************************/

    /**
     * Load the classes of all mapped fields.
     */
    private function load_field_classes()
    {
        // Check if the classes of the mapped_fields are already loaded
        if ($this->loaded_classes == true) {
            return true;
        }

        foreach ($this->mapped_fields as $key => $mapped_field) {
            $class_name = 'MrKoopie\\WP_ajaxfilter\\Input\\'.$mapped_field['type'];

            // Store the class
            $this->mapped_fields [$key] ['class'] = new $class_name ($mapped_field['translation'], $mapped_field['field_name'], $this->WP_wrapper, $this->stub);

            // Load the input data
            if (isset($this->input_data[$mapped_field['field_name']])) {
                $this->mapped_fields [$key] ['class']->set_input_data($this->input_data[$mapped_field['field_name']]);
            }
        }
    }

    /**
     * Convert the raw filter data into an array.
     *
     * @param string $raw_filter_data The raw input data.
     */
    private function filter_raw_input_data($raw_filter_data)
    {
        if ($raw_filter_data == '') {
            return $this;
        }

        $raw_filter_data = urldecode($raw_filter_data);
        $split_raw_filter_data = explode('&', $raw_filter_data);

        if (count($split_raw_filter_data) != 0) {
            foreach ($split_raw_filter_data as $raw_filter_data) {
                $split_raw_filter_data = explode('=', $raw_filter_data);

                $this->input_data[$split_raw_filter_data[0]][] = $split_raw_filter_data[1];
            }
        }

        return $this;
    }
}
