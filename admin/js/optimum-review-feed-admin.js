(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	var OptimumAdmin = {
		// fields
		gatherLocationListButton : Object,
		locationList : Object,

		/**
		 * handle click on Gather Location List button
		 *
		 * @class      LocationListOnChange (name)
		 */
		LocationListOnChange : function(){
			$( this.gatherLocationListButton ).on( 'click', function( event ) {
				OptimumAdmin.getLocations();
			});
		},

		getLocations : function(){
			var apiElement = document.getElementsByName( 'opf_settings[opf_api_key]' )[0];

			$.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url:  opf_data.ajax_url,
                    data: { api_key: apiElement.value , action: 'get_location_list' },
                    beforeSend : function () {
                        // empty options
                    },
                    success: function( items ){
                        if ( items.status == 'ok' ){
	                        //var items = $.parseJSON( items );
	                        for ( var i = 0 ; i < items.data.length; i++ ) {
	                            OptimumAdmin.locationList.append('<option value="'+items.data[i].value+'">'+items.data[i].text+'</option>');
	                        }

	                        OptimumAdmin.locationList.show();
	                        OptimumAdmin.gatherLocationListButton.hide();
	                    
	                    } else if ( items.status == 'error' ){
	                    	alert( items.msg );
	                    }
                    }
                });
		},

		/**
         * Initialization event
         */
        init : function(){
        	this.gatherLocationListButton = $('#get_location_list');
			this.locationList = $('#location_list');

        	this.LocationListOnChange();
        }
	};

	$(function() {
		// run all handler for admin
		OptimumAdmin.init();

		if ( typeof opf_data !== 'undefined' ){
			if ( opf_data.reviews && $("#viewer").length ){
				renderjson.set_show_to_level( 1 );
				document.getElementById("viewer").appendChild( renderjson( opf_data.reviews ) );
			}
		}

	});
})( jQuery );