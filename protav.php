<div id="loading">
	<p id="loadingTitle">Loading...</p>
	<img src="/inc/loader.gif" alt="Loading..." />
</div>
<div id="protav">
<?php
if($_POST)
{
	$incAnimals = preg_split("/,/", $_POST['incAnimals']);
}else{ 
	$incAnimals = array();
	//$incAnimals = array("human");
}
echo "

";
/*
   echo "

";
*/
$msg = "";
$longest_word = 0;


//Parse species name file and build a hash of arrays of the info
$common_name	= array();
$science_name	= array();
$name_length	= array();
//$protein_src	= array();
//$protein_len	= array();
//$protein_name	= array();

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
		$len = strlen($cols[3]." (".$cols[2].")  ");
		$name_length  = array_push_assoc($name_length, $cols[2], $len);
		if(count($incAnimals))
		{
			if($len > $longest_word)
			{
				foreach($incAnimals as $useAnimal) { if($useAnimal == strtolower($cols[2])) { $longest_word = $len; } }
			}
		}
		else { $longest_word = 47; }

		//$protein_src  = array_push_assoc($protein_src , $cols[1], $cols[4]);
		//$protein_len  = array_push_assoc($protein_len , $cols[1], $cols[5]);
		//$protein_name = array_push_assoc($protein_name, $cols[1], $cols[6]);
	}
}
unset($file);
unset($lines);


$sequences = array();
$names = array();
$animals_uniprot = array();
$animals_mod = array();
$input = "data/clustalw2.fasta";
//echo "<h3>Input: <a href=\"/inc/protein_alignment/$input\">/inc/protein_alignment/$input</a></h3>";
$file = file_get_contents($base . "/" . $input);
if($file)
{
	//Make an array where each element is a separate species' protein sequence
	$proteins = preg_split("/\>/", $file);
	foreach($proteins as $protein)
	{

			$lines = preg_split("/\n/", $protein);
			$speciesTrig = false;
			$sequence = "";
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
							$animals_uniprot= array_push_assoc($animals_uniprot, $common_name{$prot_id}, $prot_id);
							$tname = "<a href='http://www.uniprot.org/uniprot/$prot_id' target='_blank'>" . $science_name{$prot_id} . "</a> (" . $common_name{$prot_id} . ")";
							$name = $science_name{$prot_id} . " (" . $common_name{$prot_id} . ")";
						}
						else
						{
							$name = $science_name{$line} . " (" . $common_name{$line} . ")";
							$tname = "<a href='http://www.uniprot.org/uniparc/$line' target='_blank'>" . $science_name{$line} . "</a> (" . $common_name{$line} . ")";
						}
						//Since spacing matters, make a var with the correct 
						//number of blank spaces to maintain sequence alignment				
						$nameLen = strlen($name);
						$opSpace = "";
						for($i = 0; $i < ($longest_word - $nameLen); $i++) { $opSpace.="&nbsp;"; }
						if($tname) { $name = $tname; }
						array_push($names, $name.$opSpace);
						$speciesTrig = true;
					}
					else { $sequence .= $line; }
				}
			}
			if($sequence) { array_push($sequences, $sequence); } 
		//}
	}
}	

$animals_withMod = array();
$modifications = array();
foreach($animals_uniprot as $animal_name=>$uniprot_id)
{
	//echo $animal_name . "[" . $uniprot_id . "]<br>\n";
	if(file_exists($base."/data/ptms/$uniprot_id.txt"))
	{
		$file = file_get_contents($base."/data/ptms/$uniprot_id.txt");
		if(preg_match("/PTMs/", $file))
		{
			//echo $uniprot_id . " has no PTMs...<br>\n";
			$animals_withMod = array_push_assoc($animals_withMod, $animal_name, 0);
		}
		else
		{
			$animals_withMod = array_push_assoc($animals_withMod, $animal_name, 1);
			$temp_mod = array();
			$lines = preg_split("/\n/", $file);
			foreach($lines as $line)
			{
				$cols = preg_split("/\t/", $line);
				$tPos = $cols[0];
				$tLen = $cols[1];
				$tTyp = $cols[2];
				$temp_mod = array_push_assoc($temp_mod, $tPos, $tTyp);
			}
			$modifications = array_push_assoc($modifications, $animal_name, $temp_mod);
		}
	}
	else
	{
		$temp_mod = array();
		$tmp_mod = "";
		$uniprot_page = file_get_contents("http://www.uniprot.org/uniprot/$uniprot_id");
		if(preg_match("/Modified residue/", $uniprot_page))
		{
			$animals_withMod = array_push_assoc($animals_withMod, $animal_name, 1);
			$rows = preg_split("/\<tr/", $uniprot_page);
			foreach($rows as $row)
			{
				if(preg_match("/Modified residue/", $row))
				{
					$cols = preg_split("/\<\/td/", $row);
					$temp_pos = 0;
					$temp_type = "";
					for($i=0; $i<count($cols); $i++)
					{
						if($i > 1)
						{
							switch($i)
							{
								case 2:
									$temp_pos = preg_replace("/ Ref.*/", "", preg_replace("/\>/", "", strip_tags($cols[$i])));
									break;
								case 3:
									$temp_len = preg_replace("/\>/", "", strip_tags($cols[$i]));
									break;
								case 4:
									$temp_type = ucfirst(strtolower(preg_replace("/ Ref.*/", "", preg_replace("/\>/", "", strip_tags($cols[$i])))));
									//$temp_type = preg_replace("/\ by similarity/i", "", $t_type);
									break;
							}
						}
					}
					$temp_mod = array_push_assoc($temp_mod, $temp_pos, $temp_type);
					$tmp_mod .= $temp_pos . "\t" . $temp_len . "\t" . $temp_type . "\n";
				}
			}
			$modifications = array_push_assoc($modifications, $animal_name, $temp_mod);
			if($handle = fopen($base."/data/ptms/$uniprot_id.txt", 'w'))
			{
				if(fwrite($handle, $tmp_mod) == false)
				{
					//echo "cannot write to file: $uniprot_id.txt<br>\n";
					echo alert('There was a problem updating a uniprot file...'); 
					exit;
				}
			}
		}
		else
		{
			$animals_withMod = array_push_assoc($animals_withMod, $animal_name, 0);
			if($handle = fopen($base."/data/ptms/$uniprot_id.txt", 'w'))
			{
				if(fwrite($handle, "No PTMs for this uniprot id...") == false)
				{
					//echo "cannot write to file: $uniprot_id.txt<br>\n";
					echo alert('There was a problem updating a uniprot file...'); 
					exit;
				}
			}
		}
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
		PTM:<div id=\"ptm\"></div>
	</div>
	<div id=\"cell\">
		Amino Acid:<div id=\"amino-acid-name\"></div>
	</div>
	<div id=\"last-cell\">
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
		$track_pos = 0;

		if(count($incAnimals))
		{
			$useTrig = false;
			foreach($incAnimals as $useAnimal)
			{
				if(strtolower($nor_name) == $useAnimal) { $useTrig = true; }
			}
			if(!$useTrig) { continue 1; }
		}
		echo "
		<li id=\"animal\">
			" . $names[$i] . "
			<ul class=\"seqList-normal\" data-sci-name=\"$sci_name\" data-nor-name=\"$nor_name\">";
		for($l=0; $l<count($tempSeq); $l++)
		{
			if($animals_withMod{$nor_name})
			{
				if($modifications{$nor_name}{($track_pos+1)})
				{
					$mod = "cell-" . strtolower($modifications{$nor_name}{($track_pos+1)});
					$ptm = " data-ptm=\"" . $modifications{$nor_name}{($track_pos+1)} . "\"";
				}
				else 
				{ $mod = "cell-normal"; $ptm = "data-ptm=\"None\""; }
			}
			else 
			{ $mod = "cell-normal"; $ptm = "data-ptm=\"None\""; }

			$tLet = $tempSeq[$l];
			if($tLet == "-") 
			{
			echo "<li 
				class=\"seq_letter $mod ind_" . ($l+1) . "\" 
				id=\"ind_" . ($l+1) . "\" $ptm 
				data-position=\"Gap\" 
				data-amino-acid=\"0\">" . $tempSeq[$l] . "</li>";
			}
			else 
			{
				$track_pos++;
			echo "<li 
				class=\"seq_letter $mod ind_" . ($l+1) . "\" 
				id=\"ind_" . ($l+1) . "\" $ptm 
				data-position=\"$track_pos\" 
				data-amino-acid=\"$tLet\">" . $tempSeq[$l] . "</li>";
			}

		}
		echo "
			</ul>
		</li>";
	}
	echo "
	</ul>
</div>";
}

/* --------------- Functions --------------- */
function array_push_assoc($array, $key, $value){
	$array[$key] = $value;
	return $array;
}
function alert($msg){
	return "<script>alert('$msg');</script>";
}
?>
</div>
