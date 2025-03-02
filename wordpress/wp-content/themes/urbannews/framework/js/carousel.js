jQuery(function($){
$(document).ready(function(){


	// CAROUSELS
	$('#sections-photo').carousel({
		itemsPerPage: 2,
		itemsPerTransition: 2,
	easing: 'linear',
	noOfRows: 2
});

// CAROUSELS
$('#sections-video').carousel({
	itemsPerPage: 4,
	itemsPerTransition: 2,
	easing: 'linear',
	noOfRows: 2
});



}); // END doc ready
}); // END function