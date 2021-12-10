<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" manifest="os.manifest" >
<head>
	<title>OpenSong Viewer</title>
  	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	 <script type="text/javascript" src="js/jquery.js"></script>
  	<script type="text/javascript" src="js/jquery.transposer.js"></script>
  	<script type="text/javascript" src="js/jquery-ui-1.8.2.custom.min.js"></script>
  	<script type="text/javascript" src="js/functions.js"></script>
 	<link rel="stylesheet" type="text/css" href="css/jquery.transposer.css" />
 	<link type="text/css" href="css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />	
 	<link rel="stylesheet" type="text/css" href="/home/gccpraise/public_html/os-viewer/css/setstyle.css" />
 	<style type="text/css" media="print">
		body {
  		width: 100%;
  		column-width: 50%;
  		column-gap: 2em;   /* shown in yellow */            
  		column-rule: none;
  		padding: 5px;      /* shown in blue */
  		columns: 2 auto;
		}
	print { margin: 0; padding: 0 }
	img { display: none }

	 .NonPrintable
    	{
      	display: none;
    	}
	</style>
</head>
<body>
<div id="header"><img src="images/gcclogo.gif">

<?php
$dir = "/home/gccpraise/public_html/opensongv2/xml/";
$song=$_GET['s'];
$menu='<div id="menu"><a href="http://gccpraise.com/praise-team/">Praise Team Home</a> <a href="http://gccpraise.com/os-viewer/preview_index.php">Song Index</a> <a href="https://docs.google.com/spreadsheets/d/1_FAwmbf0nH7_qV2MJVWeMRbm--1I_AdLFlaFOXtl8U0/edit#gid=0" TARGET="_blank">Worship Schedule</a> <a href="https://songselect.ccli.com/" TARGET="_blank">CCLI Song Select</a></div>';

?>

<!--</div>-->
<!--<div id="autoscr"><a href="#" onclick="modScroll(250);">Start</a></div>-->
<!--<div id="setlist" class="NonPrintable"></div>-->
<?php

if (isset($_GET['s']))
{
	
	if (file_exists($dir . $_GET['s']))
	{
		$xml = simplexml_load_file($dir . $_GET['s']);
	}
//print_r($xml);
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

echo  $xml->title . " </h1>";
//echo "<h3>" . $xml->author . "</h3>";
echo "</div>";
echo "(Presentation Order: " .$xml->presentation .")<br>";

echo $menu;


if (!empty($xml->user1))
{
	echo "<p class='NonPrintable'><strong><a href='" . $xml->user1 .  "' TARGET='_blank'>Click to display sample video</a></strong>";
	if (!empty($xml->user2))
	{
	echo "<em> (" . $xml->user2 .  ")</em>";
	
	}
	echo "<br />";
}

echo "<pre data-key=\"$key\">";
foreach ($temp as $line)
{
	$line = str_replace("â€™", "'", $line);
	echo ltrim(str_replace("\t", '   ', $line), '.') . "\n";
}
echo "</pre>";
echo "<p><em>" . $xml->copyright . "</em><br />";
echo "<em>" . $xml->ccli . "</em></p>";
?>
<div id="songnav" class="NonPrintable"></div>
<div id="innersongnav" class="NonPrintable">Presentation Order<br />
<?php 
$pres = explode(" ", $xml->presentation);
foreach ($pres as $part)
{
	echo "<a href='#" . $part . "'>" . $part . "</a> &nbsp;&nbsp;";
}
?>
</div>
<?php
}
else
{
?>
    <a href="preview_index.php">Choose a song to view</a>
<?php
}
?>
</body>
</html>