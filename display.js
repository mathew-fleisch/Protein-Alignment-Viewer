$(document).ready(function() {
	/*
	$('#protavWrapper').live("mouseover mouseout", function(event) {
		if(event.type == "mouseover")
		{
			$(this).css("cursor", "none");
		}
		if(event.type == "mouseout")
		{
			$(this).css("cursor", "pointer");
		}
	});
	*/
	$('#seq_letter').live("mouseover mouseout click", function(event) {
		var tempPos = $(this).attr('data-position');
		if(event.type == "mouseover")
		{
			$(this).css("background-color", "#555");
			$(this).css("color", "#000");
			$(this).parent().css("background-color", "#ddd");
			$('#speciesName').text($(this).parent().attr("data-nor-name"));
			$('#position').text($(this).attr("data-position"));
			$(".pos_"+tempPos).css("background-color", "#ddd");
		}
		if(event.type == "mouseout")
		{
			$(this).css("background-color", "transparent");
			$(this).css("color", "#666");
			$(this).parent().css("background-color", "transparent");
			$(".pos_"+tempPos).css("background-color", "transparent");
		}

	});
});
