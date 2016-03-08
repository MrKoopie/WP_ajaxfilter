# WP_ajaxfilter
This code snippet allows you to create an ajax filter. NOTE: This is still in development and may change drastically until a final release is done.

# Requirements
- You must have at least PHP version 5.3 to support namespaces.
- Depends on jQuery.
- Your own CSS to properly integrate the forms into your website.

# How to install
1. Clone the files to wp-content/theme/yourthemename/MrKoopie/WP_ajaxfilter
2. Add in functions.php the following code:
```php
/** Enable filtering on WP posts */
include( get_template_directory() . '/MrKoopie/WP_ajaxfilter/launcher.php'); // Comment for child themes.
// include( get_stylesheet_directory() . '/MrKoopie/WP_ajaxfilter/launcher.php'); // Uncomment this for child themes.
```

# How to use
This code is not a plug-and-play piece of code, but allows developers to create in an easier way filters for WordPress post's. 

1. Create a filter and include this in your functions.php.
```php
add_action( 'pre_get_posts', 'MRK_ajax_filter' ); 

function MRK_ajax_filter($query)
{
	// Enable this filter for the front-page pm;u
	if(is_admin())
		return $query;

	// Do we have a filter?
	if(isset($_GET['MRK_ajax_filter']))
	{
		if($query->query['post_type'] == 'company')
		{
			$filter = new MrKoopie\WordPress\ajax_filter();

			/** 
			 * Customize the following to configure the filter properly.
			 * This has to be done manually, as we do not want to depend
			 * on the columns provided by the user's input.
			 */
			// Map the columns
			$filter->add_column('company_name', 'search')
    			   ->add_column('product_categories', 'serialized')
    			   ->add_column('province')
    			   ->add_column('country');
			
			// Configure the raw data
			$filter->filter_raw_data($_GET['MRK_ajax_filter']);

			// Make the filter query
			$filter->configure_query($query);

		}
	}

	return $query;
}
```
2. Go to your template file and add the following code where the form should be listed:
```php
<?php
$filter_generator = new MrKoopie\WP_ajaxfilter\ajax_filter_generator();

/**
 * add_column( 'label value', 'customfield_term')
*/
$filter_generator->add_column(__('Company name', 'yourtheme'), 'company_name')
				 ->set_text()
				 ->add_column(__('Country', 'suribedrijven'), 'country')
				 ->set_checkbox('company_country');

/**
 * generate_form('the_html_identifier')
 */
$filter_generator->generate_form('company_index');
?>
```
3. The ajax script requests the current URL with the filters in the GET method. Check with the following
code if the request is an ajax request. If so, only send the part where the posts are shown.
```php
if(isset($_GET['action']) && $_GET['action'] == 'ajax')
{
	// Load the correct template file.
	get_template_part();
	return true;
}
```

# Available filter forms
After defining the column, you need to set the column type. Currently the following types are supported:
```php
$filter_generator = new MrKoopie\WP_ajaxfilter\ajax_filter_generator();
$filter_generator->set_text(); // Shows a textarea
$filter_generator->set_checkbox('taxonomy_id'); // Shows all values in taxonomy_id as an checkbox.
$filter_generator->set_radio_button('taxonomy_id'); // Shows all values in taxonomy_id as an checkbox.
```

# FAQ
1. Do you provide CSS Markup?
The HTML forms will not have any CSS markup. The idea is that you create this by yourself in your own scss files (or css).

2. How are the classes loaded?
Instead of bothering you to include the correct files every time, the code in launcher.php autoloads the required classes.

3. Will your code mess with other plugins?
I can not provide any guarantee, however the code is designed to be compatible with any other code. On the PHP side, it 
uses the MrKoopie\WP_ajaxfilter namespace to make sure that it does not overwrite other plugins. 


# TODO
[] Make the Taxonomy operator flexible.
[] Make the data source for checkboxes flexible.
[] Add


# Credits
Jasper Kums, Eenvoud Media B.V. for helping with debugging.