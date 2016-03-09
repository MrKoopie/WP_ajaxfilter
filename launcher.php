<?php
/**
 * Supports launching the WP_ajaxfilter classes.
 * 
 * @author  	Daniel Koop <mail@danielkoop.me>
 * @copyright  	Daniel Koop 2016
 * @license  	https://opensource.org/licenses/MIT MIT
 * @version  	0.1
 */

// Define the include path
if(is_child_theme())
{
	define('MRK_include_path', get_stylesheet_directory());
	define('MRK_include_uri', get_stylesheet_directory_uri());
}
else
{
	define('MRK_include_path', get_template_directory());
	define('MRK_include_uri', get_template_directory_uri());
}

/** Enqueue script */
wp_enqueue_script('MRK-ajax_filter', MRK_include_uri . '/MrKoopie/WP_ajaxfilter/js/ajax_filter.js', array( 'jquery' ));

/**
 * Only register the autoloader if it does not exist yet
 */
if(!function_exists('MrKoopie_autoloader'))
{
	/** Class autoloader for MrKoopie namespace */
	function MrKoopie_autoloader($class) {

		// This autoloader only works for MrKoopie
		if(substr($class, 0, 8) == 'MrKoopie')
		{

			$exploded_class_path = explode('\\', $class);

			foreach($exploded_class_path as $path)
			{
				if(!isset($class_path))
					$class_path = $path;
				else
					$class_path = $class_path . '/' . $path;
			}

			// Load the class
			require_once( MRK_include_path . '/' . $class_path . '.php' );
		}
	}

	spl_autoload_register('MrKoopie_autoloader');
}

/**
 * Load the language files
 */

add_action('after_setup_theme', 'MRK_WP_ajaxfilter_init');
function MRK_WP_ajaxfilter_init()
{
    load_theme_textdomain('WP_ajaxfilter', MRK_include_path . '/MrKoopie/WP_ajaxfilter/languages');
}
