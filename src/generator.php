<?php

namespace MrKoopie\WP_ajaxfilter;
use MrKoopie\WP_ajaxfilter\Exceptions\no_data_to_render_exception;

class generator extends configurator
{
	private $loaded_classes = false;

	/**
	 * WordPress does not have dependency injection.
	 */
	public function __construct($form_id, $WP_wrapper = null)
	{
		parent::__construct($form_id, $WP_wrapper);
		
		if(isset($_GET['MRK_af_'.$form_id]))
		{
			$this->it_can_load_input_data($_GET['MRK_af_'.$form_id]);
		}
	}

	/**
	 * Registers the filters and ajax listener.
	 */
	public function render()
	{
		if(count($this->mapped_fields) == 0)
			throw new no_data_to_render_exception();

		// Register the ajax actions.
		$this->WP_wrapper->add_action( 'wp_ajax_' . $this->config['form_id'] , [ $this, 'ajax' ] );
		$this->WP_wrapper->add_action( 'wp_ajax_nopriv_' . $this->config['form_id'] , [ $this, 'ajax' ] );

		// Enqueue the jQuery filter
		$this->WP_wrapper->add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	/** 
	 * Enque the jQuery file
	 */
	public function enqueue_scripts()
	{
		$this->WP_wrapper->wp_enqueue_script( 'script-name', get_template_directory_uri() . '/vendor/mrkoopie/wp_ajaxfilter/assets/wp_ajaxfilter.js', array('jquery'));
	}

	/** 
	 * Generate the form HTML code.
	 * 
	 * @return string The HTML code
	 * @todo  Still to do
	 */
	public function html()
	{
		$return = '<form method="get" action="' . $this->WP_wrapper->get_site_url() .'/wp-admin/admin-ajax.php">
		<input type="hidden" name="action" value="' . $this->config['form_id'] . '">';

		$this->load_field_classes();

		foreach($this->mapped_fields as $mapped_field)
		{
            // Load the taxonomy data when available.
			if(isset($mapped_field['taxonomy_id']))
                $mapped_field['class']->load_data_from_taxonomy($mapped_field['taxonomy_id']);

			$return .= $mapped_field['class']->generate_html();
		}

		$return .= '
		</form>';

		return $return;
	}

	/**
	 * Process the filters and load the ajax template
	 * @todo
	 */
	public function ajax()
	{
		
		$this->WP_wrapper->query_posts($args);
	}

	/** 
	 * Load the classes of all mapped fields
	 */
	private function load_field_classes()
	{
		// Check if the classes of the mapped_fields are already loaded
		if($this->loaded_classes == true)
			return true;

		foreach($this->mapped_fields as $key => $mapped_field)
		{
			$class_name = "MrKoopie\\WP_ajaxfilter\\Input\\" . $mapped_field['type'];

			// Store the class
			$this->mapped_fields [$key] ['class'] = new $class_name ($mapped_field['translation'], $mapped_field['field_name'], $this->WP_wrapper);

			// Load the input data
			if(isset($this->input_data[$mapped_field['field_name']]))
				$this->mapped_fields [$key] ['class']->set_input_data($this->input_data[$mapped_field['field_name']]);
		}
	}

	/**
	 * Register the query_action for pre_get_posts.
	 * @todo
	 */
	private function register_query_action()
	{
		
	}

	/**
	 * Register the ajax action.
	 * @todo
	 */
	private function register_ajax_action()
	{
		$this->WP_wrapper->add_action( 'wp_ajax_my_action', [ $this, 'ajax' ] );
		$this->WP_wrapper->add_action( 'wp_ajax_nopriv_my_action', [ $this, 'ajax' ] );
	}

	
}
