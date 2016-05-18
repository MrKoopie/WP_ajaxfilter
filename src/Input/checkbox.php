<?php

namespace MrKoopie\WP_ajaxfilter\Input;

use MrKoopie\WP_ajaxfilter\Contracts\input;
use MrKoopie\WP_ajaxfilter\Contracts\input_with_taxonomy;

/**
 * Class Checkbox.
 */
class checkbox extends base implements input,input_with_taxonomy
{
    /**
     * Generate the HTML code for this Input method.
     *
     * @return string The HTML code.
     */
    public function generate_html()
    {
        // Prepare the input data
        $input_data = $this->prepare_input_data();

        $checkboxes = '';
        foreach ($this->filter_data as $taxonomy) {
            unset($parameters);
            $parameters = [
                'field_name' => $this->field_name,
                'value'      => $taxonomy['slug'],
                'label'      => $taxonomy['label'],
            ];

            if (isset($input_data [$taxonomy['slug']])) {
                $parameters['checked'] = ' checked';
            } else {
                $parameters['checked'] = '';
            }

            $checkboxes .= $this->stub->parse_stub('input-checkbox-parameter', $parameters)."\n";
        }

        unset($parameters);
        $parameters = [
            'label'         => $this->label,
            'field_name'    => $this->field_name,
            'checkboxes'    => $checkboxes,
        ];

        $return = $this->stub->parse_stub('input-checkbox', $parameters)."\n";

        return $return;
    }

    /**
     * Generate the filter.
     *
     * @param object $query The query
     *
     * @return object The $query.
     */
    public function filter($query)
    {
        // We do not do anything when the data source is an array.
        if ($this->data_source == 'array') {
            return $query;
        }

        // Prepare the input data
        $input_data = $this->prepare_input_data();

        // Foreach all the taxonomy data
        foreach ($this->filter_data as $taxonomy) {
            // Check if we have some input for $taxonomy
            if (isset($input_data [$taxonomy['slug']])) {
                $tax_query_id[] = $taxonomy['term_id'];
            }
        }

        // Check if we have some filter data to configure in the query
        if (isset($tax_query_id)) {
            // We do! We need to add the filter as an array in an array.
            $tax_query[] = [
                            'taxonomy' => $this->taxonomy_id,
                            'field'    => 'id',
                            'terms'    => $tax_query_id,
                        ];

            // Set the filter.
            $query->set('tax_query', $tax_query);
        }

        return $query;
    }

    /******************************************************************
    *                                                                 *
    *                             HELPERS                             *
    *                                                                 *
    ******************************************************************/

    /**
     * Flip the input data.
     *
     * @return array The original value as name
     */
    private function prepare_input_data()
    {
        if (!empty($this->input_data)) {
            return array_flip($this->input_data);
        } else {
            return [];
        }
    }
}
