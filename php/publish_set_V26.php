<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>OpenSong Viewer</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <script type="text/javascript" src="js/jquery.js"></script>
  <script type="text/javascript" src="js/jquery.transposer.js"></script>
  <script type="text/javascript" src="js/jquery-ui-1.8.2.custom.min.js"></script>
  <script type="text/javascript" src="js/functions.js"></script>
 <link rel="stylesheet" type="text/css" href="css/jquery.transposer.css" />
 <link type="text/css" href="css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />	
 <link rel="stylesheet" type="text/css" href="css/setstyle.css" />
 <meta name="viewport" content="width=device-width, initial-scale=1.0" /> 


</head>
<body>
<div id="header"><img src="images/gcclogo.gif"></div>

<?php

$menu='<pre><div id="newmenu"><a href="preview_index.php">Song Index</a>    <a href="https://docs.google.com/spreadsheets/d/1_FAwmbf0nH7_qV2MJVWeMRbm--1I_AdLFlaFOXtl8U0/edit#gid=0" TARGET="_blank">Worship Schedule</a>    <a href="preview_set.php" TARGET="_blank">Sets</a></div></pre>';
echo $menu;
$dir1 = "/home/gccpraise/public_html/opensongv2/sets/";  //sets directory
$dir2 = "/home/gccpraise/public_html/opensongv2/xml/";   //songs (xml) directory 
$setdir = opendir($dir1);
$songdir = opendir($dir2);
$songindex = array();

// Function to build lyrics display based on presentation order 
function search($presentation, $array, $lyrics) 
{ 
	echo "<pre> Search Function - Input Presentation Order: "; print_r($presentation); echo "</pre>";    //print presentation order array
	echo "<pre> Search Function - Input Song Sections: "; print_r($array); echo "</pre>";    //print song section array
	echo "<pre> Search Function - Input Lyrics Sections: "; print_r($lyrics); echo "</pre>";    //print song lyrics array
	
	$presentationarraysize = sizeof($presentation); 
	echo "<pre> Function Presentation Array Size: " . $presentationarraysize . "</pre> \n";    //print song section array

	if(empty($presentation))
//	if($presentationarraysize = 1)  // check for empty presentation order
	{
		$i=0;
		foreach ($array as $p)
		{
			$presentation[$i] = $p; //if no presentation order is set, use the default song sequence
			$i++;
		}
//		echo "<pre> Created Default Presentation Order: "; print_r($presentation); echo "</pre>";    //print presentation order array

	}

	$lyricsarraysize = sizeof($lyrics); 

	foreach ($presentation as $section)
	{
//		$key=array_search ($section , $array);
		if (in_array($section, $array))
		{
			$key=array_search ($section , $array);
			echo "<pre> Found song section: " . $section . " index is: " . $key . " </pre> \n"; 
				
//			echo ltrim(str_replace("\t", '   ', $lyrics[$key]), '.'); //print lines of song
			$line = "<pre style='font-size:100%'>  "  . str_replace("’", "'", $lyrics[$key]) . "</pre> \n";
//			echo ltrim(str_replace("\t", '   ', $lyrics[$key]), '.'); //print lines of song
			echo ltrim(str_replace("\t", '   ', $line), '.'); //print the song section header
		
//			foreach ($lyrics as $element) // loop through the lyrics
			for ($x = $key+1; $x <= $lyricsarraysize; $x++)
			{
			
//				echo "<pre> Print song section - index = " . $x . " </pre> \n";
//				$line = "<pre style='font-size:100%'> index=" . $x . " " . str_replace("’", "'", $lyrics[$x]) . "</pre> \n";
				$line = "<pre style='font-size:100%'>" . str_replace("’", "'", $lyrics[$x]) . "</pre> \n";
//				echo ltrim(str_replace("\t", '   ', $line), '.'); //print the remaining lines of the current section
			
//				if (strpos($lyrics[$x+1], '[') ==true) // look for next song sections 
				if (strpos($line, '[') ==true) // look for next song sections 
				{
					echo "<pre> End song section - index = " . $x . " </pre> \n";

					break;
				}
				else
				{
					echo ltrim(str_replace("\t", '   ', $line), '.'); //print the remaining lines of the current section
							
				}
			}
		}
		else
		{
			$notfound = "<pre> Not Found song section: " . $section . " </pre> \n"; 
//			echo $notfound
		}
	}
//			break;
}

//	echo "<pre> Search Result end: " . $key . "</pre> \n"; 
	
//	} 
// End of search function

if (isset($_GET['s']))
{
	if (file_exists($dir1 . $_GET['s']))
	{
		$xml = simplexml_load_file($dir1 . $_GET['s']);   //load the xml document
		$preview = $_GET['s'];   //get the input parameter - i.e. set name if any

	
//$xml = simplexml_load_file('sets/' . $preview);
//print_r($xml);
$set_name = $xml['name'];
//$temp = explode("\n", $l);

//echo "<h1 style='font-size:150%'>Set Name: " . $set_name .  "</h1></p><p> \n "; //print set_name
//echo "<h1 style='font-size:10vw'>Set Name: " . $set_name .  "</h1> \n "; //print set_name
echo "<h2>Set Name: " . $set_name .  "</h2> \n"; //print set_name
foreach ($xml->slide_groups->slide_group as $item)  //loop through all the slide_groups in the presentation
{
	$slide = $item['name'];
	$type = $item['type'];
	$title = $item->title;
	$subtitle = $item->subtitle;
	$body = $item->body;
	echo "<h3>" .$slide . "</h3> \n "; //print "slide_group" from OpenSong Presentation
 
	if (!empty($item->subtitle))
	{
		echo "<h1 style='font-size:100%'><pre>    " .$subtitle . "</pre></h1> \n "; //print "slide_group" subtitle
	}

	if ($type == "song")
	{
		$songindex = null;
		if (file_exists($dir2 . $slide))
		{
			$xml = simplexml_load_file($dir2 . $slide);  //load the song file into an array
			$l = $xml->lyrics;   //load the lyrics into the $1 variable
			
			if (!empty($xml->hymn_number))
			{
				$hymn = $xml->hymn_number;
				echo "<h1 style='font-size:120%'><pre>   Hymn# " .$hymn. "-" . $slide . "</pre></h1> \n "; 	
			}
			else
			{
				echo "<h1 style='font-size:120%'><pre>   " .$slide . "</pre></h1> \n "; 
			}

			if (!empty($item['presentation'])) // check if there is a custom presentation order
			{
 			echo "<pre><b>  Custom Presentation Order:" .$item['presentation'] ."</b></pre> \n ";
				$porder=explode(" ", $item['presentation']);
//				echo "<pre> Custom Presentation Order: "; print_r($porder); echo "</pre>";    //print custom presentation order array
			}
			else
			{
			if (!empty($xml->presentation)) // check if the original presentation field is set
			{
				echo "<pre><b>  Original Presentation Order:" .$xml->presentation ."</b></pre> \n ";
				$porder=explode(" ", $xml->presentation);
				echo "<pre> Original Presentation Order: "; print_r($porder); echo "</pre>";    //print presentation order array

			}
			else
			{
				unset($porder);
			}

			}
 
			$temp = explode("\n", $l);   //break the <lyrics> into the $temp array based on the new-line character
			foreach ($temp as $key=>$line)  //read each element of the $temp array  into the $line varaible
			{
  				$line1 = $line;
				$line = "<pre style='font-size:100%'>  "  . str_replace("’", "'", $line) . "</pre> \n";

			if (strpos($line, '[') ==true) // look for song sections
			{
				$line1= str_replace("[", '', $line1); //remove left brace from section tag
				$line1= str_replace("]", '', $line1); //remove right brace from section tag
				$songindex[$key] = $line1;   // save the index of the song section into the songindex array
			}

//			echo ltrim(str_replace("\t", '   ', $line), '.'); //print lines of song
				
			}
		}
//		echo "<pre> Presentation Order: "; print_r($porder); echo "</pre>";    //print presentation order array
//		echo "<pre> Song Index: "; print_r($songindex); echo "</pre>";    //print song tag index array
	
//		$res = search('V3', $songindex);  //call the search function to look up the song section
//		echo "<pre> Search Result returned: " . $res . "</pre> \n"; 
		


// Print presentation order for song
//	echo "<pre> Song Lyrics: "; print_r($temp); echo "</pre>";    //print song lyrics array
//	$arraysize = sizeof($temp); 
//	echo "<pre> Size of song index array: " . $arraysize . "</pre> \n"; 
//	foreach ($porder as $section)
//	{
//		$res = search($section, $songindex, $temp);  //call the search function to look up the song section in the song index array
		search($porder, $songindex, $temp);  //call the search function to look up the song section in the song index array
		
//		echo "<pre> Returned from Search </pre> \n"; 
/*		
		for($i=$res; $i < $arraysize; $i++) 
		{
//			echo $i;
			$temp[i] = "<pre style='font-size:100%'>  "  . str_replace("’", "'", $temp[i]) . "</pre> \n";
			echo ltrim(str_replace("\t", '   ', $temp[$i]), '.'); //print lines of song
//			$i++;    //increment iterator
			if (strpos($temp[$i], '[') ==true) // look for next song sections
			{
				break;
			}
		}	
*/		
//	}
	


	}
	if(isset($item->slides))  // check if a child node exists
	{
 		foreach ($item->slides->slide as $scripture)
 		{
//			if (isset($scripture))
			if (!empty($scripture))
			{
				echo "<pre>" . "   " .$scripture->body . "</pre> \n";
			}
 		}
	}
}
}
}
else
{
	?>
    <b>Choose a set to preview</b>
    <?
}
?>
</body>
</html>