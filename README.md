# WP_ajaxfilter

This package is developed to be a part of a WordPress theme or plugin. By installing this code you can add an advanced filter without writing every piece of HTML, PHP, javascript and CSS code.


### Why are you using Mockery for mocking?
The [developers](https://github.com/phpspec/prophecy/issues/44) of Prophecy did not include support for magic functions like __call(). Although they do have a point, in this case we need to use __call in order to mock WordPress functions without predefining every possible WordPress function. 

