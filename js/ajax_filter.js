/**
 * Adds support for ajax filtering in WordPress
 * 
 * @author  	Daniel Koop <mail@danielkoop.me>
 * @copyright  	Daniel Koop 2016
 * @license  	https://opensource.org/licenses/MIT MIT
 * @version  	0.1
 */

jQuery(document).ready(function($) {
	console.log('Daniel Koop - ajax_filter.js is loaded.')

	// Find a form with the data-model data-MRK-ajax-filter
	$("form[data-MRK-ajax-filter]").submit(function( event ) {
		// Store the data
		var filter_data = [];
		var html_block = "#" + $(this).attr('data-MRK-ajax-filter');

		// Prevent that the browser processes the form
		event.preventDefault();

		// When found, find all checkboxes and text fields to submit
		$(this).find(':input').each(function(index, value){
			if(
					// Support for a text input
					($(this).attr('type') == 'text')

					// Support for a checkbox
					|| ($(this).attr('type') == 'checkbox' && $(this).is(':checked'))

					// Support for a radio button
					|| ($(this).attr('type') == 'radio' && $(this).is(':checked'))
					)
			{
				// Store the input value
				if($(this).val() != '')
					filter_data.push( $(this).attr('name') + '=' + encodeURIComponent($(this).val()) );
			}

		});

		filter_data = encodeURIComponent(filter_data.join('&'));

		// Set the loading screen
		$( html_block ).html( "Laden..." );

		// Compile the required data
        var data = {
            'action': 'ajax', // With this the template knows it should send back an ajax response
            'filter': filter_data
        };

        // Change the URL
        history.pushState(null, null, '?MRK_ajax_filter=' + filter_data);

        // Make the Ajax call
        jQuery.get(window.location.href, data, function(response) {
        	//Show the results
            $( html_block ).html(response);
        });
		
	}); 

	$("a[data-MRK-deselect-target]").on('click', function(){
		var name = "input[name='" + $(this).attr('data-MRK-deselect-target') + "']";

		console.log('click detected')
		console.log( name );
		$(name).each(function(){
			console.log($(this));
			$(this).prop('checked', false);

		});
	})

});