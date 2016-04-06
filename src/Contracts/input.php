<?php
namespace MrKoopie\WP_ajaxfilter\Contracts;

interface input
{
    /**
     * @param $label The translation shown in the HTML Code
     * @param $tech_name The technical name of the field
     */
    public function __construct($label, $tech_name);

	/** 
	 * Generate the HTML code for this Input method.
	 * @return String The HTML code.
	 */
	public function generate_html();

	/**
	 * Generate the filter
	 * @param  object $WP_Query The WP_Query
	 * @return object The $WP_Query.
	 */
	public function filter($WP_Query);

    /**
     * Pass the input data to the input type.
     *
     * @param string $input_data The input data for this plugin
     */
    public function set_input_data($input_data);

    /**
     * Return the input data to the input type.
     *
     * @return string The input data for this plugin
     */
    public function get_input_data();
}