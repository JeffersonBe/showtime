$(document).ready(function(){ 
	
	/* Associate form for handling */
	$('#reform_standard').reform();
	
	/* Apply uniform form styles */
	$("#reform_standard select, #reform_standard input, #reform_standard textarea").uniform();
	
	/* poshyTip tool-tips */
	$('.poshytip').poshytip({
	className: 'tip-skyblue',
	showOn: 'hover',
	alignTo: 'target',
	alignX: 'right',
	alignY: 'center',
	offsetX: 5});

});