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
			//$(this).css("background-color", "#555");
			$(this).css("color", "#000");
			$(this).parent().css("background-color", "#ddd");
			$('#speciesName').text($(this).parent().attr("data-nor-name"));
			$('#position').text($(this).attr("data-position"));
			$('.pos_'+tempPos).css("background-color", "#ddd");
			$('#amino-acid-name').text(lookup($(this).text()));
		}
		if(event.type == "mouseout")
		{
			//$(this).css("background-color", "transparent");
			$(this).css("color", "#666");
			$(this).parent().css("background-color", "transparent");
			$(".pos_"+tempPos).css("background-color", "transparent");
		}

	});
});


function lookup(letter)
{
	switch(letter)
	{
		case "-":
			return "None";
			break;
		//Normal Amino Acids
		case "A":
			return "Alanine";
			break;
		case "R":
			return "Arginine";
			break;
		case "N":
			return "Asparagine";
			break;
		case "D":
			return "Aspartic Acid";
			break;
		case "C":
			return "Cysteine";
			break;
		case "E":
			return "Glutamic Acid";
			break;
		case "Q":
			return "Glutamine";
			break;
		case "G":
			return "Glycine";
			break;
		case "H":
			return "Gistidine";
			break;
		case "I":
			return "Isoleucine";
			break;
		case "L":
			return "Leucine";
			break;
		case "K":
			return "Lysine";
			break;
		case "M":
			return "Methionine";
			break;
		case "F":
			return "Phenylalanine";
			break;
		case "P":
			return "Proline";
			break;
		case "S":
			return "Serine";
			break;
		case "T":
			return "Threonine";
			break;
		case "W":
			return "Tryptophan";
			break;
		case "Y":
			return "Tyrosine";
			break;
		case "V":
			return "Valine";
			break;

		//extra Amino Acids:
		case "U":
			return "Selenocysteine";
			break;
		case "O":
			return "Pyrrolysine";
			break;

		//Placeholders:
		case "B":
			return "Asparagine or Aspartic Acid";
			break;
		case "Z":
			return "Glutamine or glutamic Acid";
			break;
		case "J":
			return "Leucine or Isoleucine";
			break;
		case "X":
			return "Unspecified or unknown Amino Acid";
			break;
	}
}
