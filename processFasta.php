<?php
$time = microtime();
$time = explode(" ", $time);
$time = $time[1] + $time[0];
$start = $time;

$sequences = array();
$nl = "\n<br>";
$file = file_get_contents("./data/clustalw2.fasta");
if($file)
{
	//Make an array where each element is a separate species' protein sequence
	$proteins = preg_split("/\>/", $file);
	foreach($proteins as $protein)
	{
		$lines = preg_split("/\n/", $protein);
		$speciesTrig = false;
		$sequence = "";
		$nameSpace = 15;
		//Condence line breaks
		foreach($lines as $line)
		{
			//Omit any line that has no text on it
			if($line)
			{
				//The first line will always contain information about the sequence
				if(!$speciesTrig)
				{
					if(preg_match("/_/", $line))
					{
						$pos_species = preg_split("/_/", $line);
						$name = $pos_species[1];
					}
					else
					{
						$name = $line;
					}
					//Since spacing matters, make a var with the correct 
					//number of blank spaces to maintain sequence alignment				
					$nameLen = strlen($name);
					$opSpace = "";
					for($i = 0; $i < ($nameSpace - $nameLen); $i++) { $opSpace.=" "; }
					$sequence .= $name . $opSpace;
					$speciesTrig = true;
				}
				else { $sequence .= $line; }
			}
		}
		array_push($sequences, $sequence);
	}
}
echo "<textarea rows=\"17\" cols=\"135\" wrap=\"off\">" . implode("   \n", $sequences) . "</textarea>$nl";
$time = microtime();
$time = explode(" ", $time);
$time = $time[1] + $time[0];
$end = $time;
$totalTime = ($end - $start);
printf("Load time: %f seconds", $totalTime);
?>
