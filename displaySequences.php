<link rel="stylesheet" type="text/css" href="/inc/protein_alignment/style.css"/>
<link rel="stylesheet" type="text/css" href="/inc/js/jquery.ui.css"/>
<script type="text/javascript" src="/inc/protein_alignment/display.js"></script>
<script type="text/javascript" src="/inc/js/jquery.js"></script>
<script type="text/javascript" src="/inc/js/jquery.ui.js"></script>
<?php
$time = microtime();
$time = explode(" ", $time);
$time = $time[1] + $time[0];
$start = $time;
$nl = "\n<br>";
$threeSpaces = "&nbsp;&nbsp;&nbsp;";


//Parse species name file and build a hash of arrays of the info
$common_name	= array();
$science_name	= array();
$protein_src	= array();
$protein_len	= array();
$protein_name	= array();

$base = "/var/www/html/chdi/inc/protein_alignment";
$file = file_get_contents($base."/data/Possible_HTT_Proteins.csv");
if($file)
{
	$lines = preg_split("/\n/", $file);
	foreach($lines as $line)
	{
		$cols = preg_split("/\|/", $line);
		$common_name  = array_push_assoc($common_name, $cols[1], $cols[2]);
		$science_name = array_push_assoc($science_name, $cols[1], $cols[3]);
		$protein_src  = array_push_assoc($protein_src , $cols[1], $cols[4]);
		$protein_len  = array_push_assoc($protein_len , $cols[1], $cols[5]);
		$protein_name = array_push_assoc($protein_name, $cols[1], $cols[6]);
	}
}
unset($file);

$sequences = array();
$names = array();
$input = "/data/clustalw2.fasta";
echo "<h3>Input: <a href=\"/inc/protein_alignment$input\">/inc/protein_alignment$input</a></h3>";
$file = file_get_contents($base . $input);
if($file)
{
	//Make an array where each element is a separate species' protein sequence
	$proteins = preg_split("/\>/", $file);
	foreach($proteins as $protein)
	{
		$lines = preg_split("/\n/", $protein);
		$speciesTrig = false;
		$sequence = "";
		$nameSpace = 47;
		//Condence line breaks
		foreach($lines as $line)
		{
			//Omit any line that has no text on it
			if($line)
			{
				//The first line will always contain information about the sequence
				if(!$speciesTrig)
				{
					if(preg_match("/\|/", $line))
					{
						$speciesArr	= preg_split("/\|/", $line);
						$origin		= $speciesArr[0];
						$prot_id	= $speciesArr[1];
						$pos_species	= preg_split("/_/", $speciesArr[2]);
						$speciesId	= $pos_species[0];
						$speciesName	= $pos_species[1];
						$name = $science_name{$prot_id} . " (" . $common_name{$prot_id} . ")";
					}
					else
					{
						$name = $science_name{$line} . " (" . $common_name{$line} . ")";
					}
					//Since spacing matters, make a var with the correct 
					//number of blank spaces to maintain sequence alignment				
					$nameLen = strlen($name);
					$opSpace = "";
					for($i = 0; $i < ($nameSpace - $nameLen); $i++) { $opSpace.="&nbsp;"; }
					array_push($names, $name.$opSpace);
					$speciesTrig = true;
				}
				else { $sequence .= $line; }
			}
		}
		if($sequence) { array_push($sequences, $sequence); } 
	}
}


if(count($names) == count($sequences))
{
	echo "
<div id=\"legend\">
	<div id=\"cell\">
		Position:<div id=\"position\"></div>
	</div>
	<div id=\"cell\">
		Amino Acid:<div id=\"amino-acid-name\"></div>
	</div>
	<div id=\"cell\">
		Species:<div id=\"speciesName\"></div>
	</div>
</div>
<div id=\"protavWrapper\">
	<ul id=\"animalSeq\">";
	for($i=0; $i<count($names); $i++)
	{
		$sp_arr = preg_split("/\(/", $names[$i]);
		$sci_name = trim($sp_arr[0]);
		$nor_name = preg_replace("/\)*\&nbsp\;*/", "", $sp_arr[1]);
		$tempSeq = str_split($sequences[$i]);

		echo "
		<li id=\"animal\">
			" . $names[$i] . "
			<ul id=\"seqList\" data-sci-name=\"$sci_name\" data-nor-name=\"$nor_name\">";
		for($l=0; $l<count($tempSeq); $l++)
		{
			$tLet = $tempSeq[$l];
			if($tLet == "-") { $tLet = "0"; }
			echo "<li id=\"seq_letter\" class=\"pos_" . ($l+1) . "\" data-position=\"" . ($l+1) . "\" data-amino-acid=\"$tLet\">" . $tempSeq[$l] . "</li>";
		}
		echo "
			</ul>
		</li>";
	}
	echo "
	</ul>
</div>";
}



$time = microtime();
$time = explode(" ", $time);
$time = $time[1] + $time[0];
$end = $time;
$totalTime = ($end - $start);
printf("Load time: %f seconds", $totalTime);



/* --------------- Functions --------------- */

?>
