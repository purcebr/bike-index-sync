(function ( $ ) {
	"use strict";

	$(function () {
		// Place your public-facing JavaScript here
    	$('.bikeindex-list').dataTable({ "order": [[ 0, "desc" ]] } );
	});

}(jQuery));