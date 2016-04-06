# WP_ajaxfilter

This package is developed to be a part of a WordPress theme or plugin. By installing this code you can add an advanced filter without writing every piece of HTML, PHP, javascript and CSS code.

## How to install
Run the following command:
```php
composer require mrkoopie/wp_ajaxfilter
```

Add to your functions.php the following code:
```php
function ajaxfilter($filter_id)
{
    if(!isset($GLOBALS['WP_ajaxfilter'][$filter_id]))
        $GLOBALS['WP_ajaxfilter'][$filter_id] = new MrKoopie/WP_ajaxfilter/generator($filter_id);
    
    return $GLOBALS['WP_ajaxfilter'][$filter_id];
}

require_once('vendor/autoload.php');
```

## How to use the code in a theme
The code is designed to have a controller inside your functions.php (or any file that is included in your functions.php) and a simple call in a template file.

```php
# functions.php
/**
 * Here we configure the ajaxfilter with the form id your_filter_id.
 * This id should be unique and is used in the template to output
 * the filter and is used as a selector in jQuery.
 */
ajax_filter('your_filter_id')
    // Configure the input fields
    ->add_text(__('Company'), 's', 'optional_tech_name_s')
    ->add_checkbox(__('Province'), 'taxonomy_province', 'optional_tech_name_province')
    ->add_dropdown(__('Category'), 'taxonomy_category', 'optional_tech_name_category')
    ->add_radiobuttons(__('Support'), 'taxonomy_support', 'optional_tech_name_support')

    // The jQuery script will make an ajax call, set the
    // template filter here. The filter will use
    // get_template_part('your_ajax_template'), so you
    // can use the same input method.
    ->set_ajax_template('your_ajax_template')

    // By running render, we activate the query filter 
    // and create the ajax listener.
    ->render();
    
# template.php
/**
 * This command outputs the HTML form. Keep in mind that you have to put
 * a <div id="your_filter_id"></div> somewere!
 */
ajax_filter('your_filter_id')->html();
```

###### add_text($label, $field = 's', $tech_name )
$label is shown in the <label> tag.
With $field you define the field where the filter applies to. Set this to s (default) to use the default WordPress search fields.

###### add_checkbox($label, $taxonomy_id, $tech_name )
$label is shown in the <label> tag.
$taxonomy_id is the id of the taxonomy where the data is loaded of.
$tech_name This name is used for the technical field name (which is shown in every GET request).

###### add_dropdown($label, $taxonomy_id, $tech_name )
$label is shown in the <label> tag.
$taxonomy_id is the id of the taxonomy where the data is loaded of.
$tech_name This name is used for the technical field name (which is shown in every GET request).

###### add_radiobuttons($label, $taxonomy_id, $tech_name )
$label is shown in the <label> tag.
$taxonomy_id is the id of the taxonomy where the data is loaded of.
$tech_name This name is used for the technical field name (which is shown in every GET request).

##### set_ajax_template($template_name)
$template_name is the name of your template inside your theme. This template is used to show the results of the filter.

##### render()
With this function the query filter is set and the ajax call is registered.

# Todo
Add support for:
- Comparison methods


# FAQ

### Why is this not a plugin?
A plugin would require a GUI in the back-end, which is not provided by this package. This package is designed to be used within a theme or a plugin, to make the life of a developer easier.

### Why are you using Mockery for mocking?
The [developers](https://github.com/phpspec/prophecy/issues/44) of Prophecy did not include support for magic functions like __call(). Although they do have a point, in this case we need to use __call in order to mock WordPress functions without predefining every possible WordPress function.

# Credits
Jasper Kums, Eenvoud Media B.V. - For providing feedback.