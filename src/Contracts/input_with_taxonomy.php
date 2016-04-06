<?php

namespace MrKoopie\WP_ajaxfilter\Contracts;


interface input_with_taxonomy
{
    /**
     * Load the taxonomy data from WordPress
     *
     * @param $taxonomy_id The WordPress Taxonomy id
     */
    public function load_data_from_taxonomy($taxonomy_id);
}