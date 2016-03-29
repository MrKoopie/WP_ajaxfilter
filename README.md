# WP_ajaxfilter

This package is developed to be a part of a WordPress theme or plugin. By installing this code you can add an advanced filter without writing every piece of HTML, PHP, javascript and CSS code.

## How to install
Run the following command:
```php
composer require mrkoopie/wp_ajaxfilter
```

Add to your functions.php the following code:
```php
require_once('vendor/autoload.php');
```

## How to use the code
Start the HTML generator using the following code:
```php
$g = new MrKoopie/WP_ajaxfilter/generator();
```

Use the following code to add a checkbox:
```php
$g->add_checkbox( 'label name' ) // The label name is shown in the HTML interface and can be a translated string.
  ->load_data_from_taxonomy( 'taxonomy_name' ) // You can load data from a taxonomy or array
  ->comparison(); // The filtered object should match all selected filters. 
  @todo should I use comparison?
```

### load_data_from_
#### array

#### taxonomy

## match_all() and match_any()
With $g->match_all() the object must match all the set filters for the speci


# FAQ

### Why is this not a plugin?
A plugin would require a GUI in the back-end, which is not provided by this package. This package is designed to be used within a theme or a plugin, to make the life of a developer easier.

### Why are you using Mockery for mocking?
The [developers](https://github.com/phpspec/prophecy/issues/44) of Prophecy did not include support for magic functions like __call(). Although they do have a point, in this case we need to use __call in order to mock WordPress functions without predefining every possible WordPress function. 