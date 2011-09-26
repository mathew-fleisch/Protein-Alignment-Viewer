$(document).ready(function() {
	$(".seq_letter").live("mouseover mouseout click", function(event) {
		var crnt_position	= $(this).attr('id').replace("ind_","");
		var normal_name		= $(this).parent().attr("data-nor-name");
		var science_name	= $(this).attr("data-position");
		var amino_acid		= lookup($(this).text());
		var protein_mod		= $(this).attr("data-ptm").replace("cell-","");

		if(event.type == "mouseover")
		{
			//Row Hover
			if($("#hl_row").attr('checked'))
			{
				$(this).parent().removeClass("seqList-normal");
				$(this).parent().addClass("seqList-hover");
			}

			//Column Hover
			if($("#hl_col").attr('checked'))
			{
				$(".ind_"+crnt_position).removeClass("cell-normal");
				$(".ind_"+crnt_position).addClass("cell-hover");
			}


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
			$(".ind_"+crnt_position).removeClass("cell-hover");
			if(!$(".ind_"+crnt_position).attr("data-ptm")) { $(".ind_"+crnt_position).addClass("cell-normal"); }
		}
	});
	$('#activate_protav_ajax').click(function(){
		var search_str = "";
		var possible_animals_long = new Array(
			"chimpanzee", 
			"florida lancelet", 
			"fruit fly", 
			"giant panda", 
			"horse", 
			"human", 
			"japanese pufferfish", 
			"mouse", 
			"pig", 
			"rat", 
			"sheep", 
			"sumatran orangutan", 
			"white-tufted-ear marmoset");
		$(".animalBoxes").each(function(index) {
			if(this.checked)
			{
				search_str += possible_animals_long[index]+",";
			}
		});
		protav(search_str.substring(0,search_str.length-1));
	});
	$('#check_all').click(function(){
		var chkd = true;
		$(".animalBoxes").each(function() { 
			if(this.checked == false)
			{
				chkd = false;
			}
		});
		$(".animalBoxes").each(function() {
			if(chkd)
			{
				this.checked = false;
			}
			else
			{
				this.checked = true;
			}
		});
	});
	$('#protavWrapper').css("height", ($("#optionsWrapper").height()-29)+"px");
});


/* ----------------------- FUNCTIONS -----------------------*/
String.prototype.capitalize = function() {
	return this.charAt(0).toUpperCase() + this.slice(1);
}


//Function:	protav()
function protav(useTheseAnimals)
{
	var animals = useTheseAnimals.split(",");
	//var count = (animals.length*25)+2;
	var count = $("#optionsWrapper").height()-29;

	$("#protav").hide();
	$("#loading").show();
	$.ajax({
		type: "POST",
		url: "/inc/protein_alignment/protav.php",
		data: "incAnimals="+useTheseAnimals,
		dataType: "html",
		success: function(html) {
			$('#protav').html(html);
			$('#protavWrapper').css("height", count+"px");
			var legendTop = (count+165);
			$("#legendHolder").css("margin-top", legendTop);
			$("#loading").hide();
			$("#protav").show();
		}
	});
	return false;
}
//End Function: protav()
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
