<?php
namespace MrKoopie\WP_ajaxfilter\Input;
use \MrKoopie\WP_ajaxfilter\Contracts\input;
use \MrKoopie\WP_ajaxfilter\Contracts\input_with_taxonomy;

/**
 * Class Checkbox
 * @package MrKoopie\WP_ajaxfilter\Input
 */
class checkbox extends Base implements input,input_with_taxonomy
{


    /** 
     * Generate the HTML code for this Input method.
     * @return String The HTML code.
     */
    public function generate_html()
    {
        $checkboxes = '';
        foreach($this->taxonomy_data as $term)
        {
            $parameters = [
                'field_name' => $this->field_name,
                'value' => $term->slug,
                'label' => $term->name
            ];

            $checkboxes .= $this->stub->parse_stub('checkbox-parameter', $parameters)."\n";
        }

        unset($parameters);
        $parameters = [
            'label'         => $this->label,
            'field_name'    => $this->field_name,
            'checkboxes'    => $checkboxes
        ];

        $return = $this->stub->parse_stub('checkbox', $parameters)."\n";

        return $return;
    }

    /**
     * Generate the filter
     * @param  object $WP_Query The WP_Query
     * @return object The $WP_Query.
     */
    public function filter($WP_Query)
    {

    }
}