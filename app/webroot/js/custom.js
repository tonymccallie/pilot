$(document).ready(function() {
	$('.dropdown-toggle').dropdown();
	$('.datepicker').pickadate({
		format: 'mm/dd/yyyy'
	});
	$('.timepicker').pickatime({
		format: 'hh:i A'
	});
});