$(document).ready(function() {
	$(".seq_letter").live("mouseover mouseout click", function(event) {
		var crnt_position	= $(this).attr('data-position');
		var normal_name		= $(this).parent().attr("data-nor-name");
		var science_name	= $(this).attr("data-position");
		var amino_acid		= lookup($(this).text());
		var protein_mod		= $(this).attr("data-ptm").replace("cell-","");

		if(event.type == "mouseover")
		{
			//Row Hover
			$(this).parent().removeClass("seqList-normal");
			$(this).parent().addClass("seqList-hover");
			
			//Column Hover
			//$(".pos_"+crnt_position).css("background-color", "#ddd");
			$(".pos_"+crnt_position).removeClass("cell-normal");
			$(".pos_"+crnt_position).addClass("cell-hover");


			//Legend text
			$('#speciesName').text(normal_name);
			$('#position').text(science_name);
			$('#amino-acid-name').text(amino_acid);
			$('#ptm').text(protein_mod); 
		}
		if(event.type == "mouseout")
		{
			//Row De-Hover
			$(this).parent().removeClass("seqList-hover");
			$(this).parent().addClass("seqList-normal");

			//Column De-Hover
			$(".pos_"+crnt_position).removeClass("cell-hover");
			if(!$(".pos_"+crnt_position).attr("data-ptm")) { $(".pos_"+crnt_position).addClass("cell-normal"); }
		}
	});
});

String.prototype.capitalize = function() {
	return this.charAt(0).toUpperCase() + this.slice(1);
}
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
