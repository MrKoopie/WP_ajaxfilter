<?php

namespace MrKoopie\WP_ajaxfilter\Input;

use MrKoopie\WP_ajaxfilter\Contracts\input;
use MrKoopie\WP_ajaxfilter\Contracts\input_with_taxonomy;

/**
 * Class Checkbox.
 */
class text extends base implements input
{
    /**
     * Generate the HTML code for this Input method.
     *
     * @return string The HTML code.
     */
    public function generate_html()
    {
        // Prepare the input data
        $input_data = $this->input_data[0];
        
        $parameters = [
            'label'         => $this->label,
            'field_name'    => $this->field_name,
            'value'         => $input_data
        ];

        $return = $this->stub->parse_stub('input-text', $parameters)."\n";

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
        $input_data = $this->input_data[0];

        // do nothing when we do not have any input data.
        if($input_data == '')
            return $query;

        if($this->field_name == 's')
        {
            $query->set('s', $input_data);

            return $query;
        }
        
        $meta_query = $query->meta_query;

        $meta_query [] = [
                        'key' => $this->field_name,
                        'value' => '%'. $input_data .'%',
                        'compare' => 'LIKE',
                        ];

        $query->set('meta_query', $meta_query);
        
        return $query;
    }

    /******************************************************************
    *                                                                 *
    *                             HELPERS                             *
    *                                                                 *
    ******************************************************************/
}
