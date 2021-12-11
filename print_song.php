<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>OpenSong Viewer</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <script type="text/javascript" src="js/jquery.js"></script>
  <script type="text/javascript" src="js/jquery.transposer.js"></script>
  <script type="text/javascript" src="js/jquery-ui-1.8.2.custom.min.js"></script>
  <script type="text/javascript" src="js/functions.js"></script>
  <link type="text/css" media="screen, print" rel="stylesheet" href="css/navstyle.css" />
 <link rel="stylesheet" type="text/css" href="css/jquery.transposer.css" />
 <link type="text/css" href="css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />	
 <link rel="stylesheet" type="text/css" href="css/style.css" />
</head>
<body>

<?php
$columnbreak = "---"; 
$multicolumn = "no";
$column2 = "";
$column1 = "";

if (isset($_GET['s']))
{
	
	if (file_exists('xml/' . $_GET['s']))
	{
		$xml = simplexml_load_file('xml/' . $_GET['s']);
	 
		$presentation_order = $xml->presentation;
		$l = $xml->lyrics;
		$temp = explode("\n", $l);

		if (!empty($xml->key))
		{
			$key = $xml->key;
		}
		else
		{
			$keystr = substr($l, strpos($l, '.'), 50);
			$keystr = preg_replace("/\s/", "", $keystr);
			$key = substr($keystr, 1,1);
			$sharp_flat = substr($keystr,2,1);
			if ($sharp_flat == "#" || $sharp_flat == "b")
			{
				$key .= $sharp_flat;
			}
		}
		echo "<h1 style='font-size:150%'>";
		if (!empty($xml->hymn_number))
		{
			echo "Hymn #" . $xml->hymn_number .  " - ";		
		}

 		echo  $xml->title . "  (Presentation Order: " .$presentation_order .")</h1>";

		echo "<em>" . $xml->author . "</em></p>";
//		echo "<h1 style='font-size:150%'>";
//		echo "<pre data-key=\"$key\">";
		foreach ($temp as $line)
		{
			$line = str_replace("â€™", "'", $line);
//  			echo ltrim(str_replace("\t", '   ', $line), '.') . "\n";
			$newline = ltrim(str_replace("\t", '   ', $line), '.') . "\n";
						
			if(strstr($newline,$columnbreak)) 
			{
//				echo "Column break found";
				$multicolumn = "yes";
//				$column1 .= $newline;
			}
			else
			{
				if ($multicolumn == "yes")
				{
					$column1 .= $newline;			
				}
				else
				{
					$column2 .= $newline;
				}
			}
		}
// 		echo "</pre>";
		
?>
<div style="float:left;width:50%;"> 
<?
	echo "<pre>"; 
	echo "<h1 style='font-size:150%'>";
	echo $column2;
	echo "</pre>";
?>
</div>
	<div style="float:left;width:50%;"> 
<?
	echo "<pre>";
	echo "<h1 style='font-size:150%'>";
	echo $column1;
	echo "</pre>";
?>
</div>
</div>
<?		
		echo "<p><em>" . $xml->copyright . "</em><br />";
		echo "<em>" . $xml->ccli . "</em></p>";
	}
}
else
{
	
    echo "<a href='preview_index.php'>Choose a song from the list</a></h1>";
}
?>
</div> 
</body>
</html>