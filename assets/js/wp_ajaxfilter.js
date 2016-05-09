/**
 * Adds support for ajax filtering in WordPress
 * 
 * @author  	Daniel Koop <mail@danielkoop.me>
 * @copyright  	Daniel Koop 2016
 * @license  	https://opensource.org/licenses/MIT MIT
 * @version  	0.2
 */

jQuery(document).ready(function($) {
	console.log('Daniel Koop - wp_ajaxfilter.js is loaded.')

	// Find a form with the data-model data-MRK-ajax-filter
	$("form[data-MRK-ajax-filter]").change(function( event ) {
		// Store the data
		var filter_data = [];
		var html_block = "#" + $(this).attr('data-MRK-ajax-filter');
		var form_id = $(this).attr('data-MRK-ajax-filter');
		var form_action = $(this).attr('action');

		// Prevent that the browser processes the form
		event.preventDefault();

		// When found, find all checkboxes and text fields to submit
		$(this).find(':input').each(function(index, value){
			if($(this).attr('type') == 'checkbox' && $(this).is(':checked'))
			{
				// Store the input value
				if($(this).val() != '')
					filter_data.push( $(this).attr('name') + '=' + encodeURIComponent($(this).val()) );
			}

		});

		filter_data = encodeURIComponent(filter_data.join('&'));

		// Set the loading screen
		$( html_block ).html( mrka.loading );
		$( html_block ).addClass('mrk-loading');

		// Compile the required data
        var data = {
            'action': 'wpf_' + form_id, // With this the template knows it should send back an ajax response
            'mrka_id': form_id,
            'mrka_val' : filter_data
        };

        // Change the URL
        var current_url = '';
        current_url = current_url.replace(/(\/page\/([0-9]*)\/)/i,"\/");
        history.pushState(null, null, current_url + '?mrka_id=' + form_id + '&mrka_val=' + filter_data);

        // Make the Ajax call
        jQuery.get(form_action, data, function(response) {
        	//Show the results
            $( html_block ).html(response);
            $( html_block ).removeClass('mrk-loading');
        });
		
	}); 

});