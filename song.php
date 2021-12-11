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
 <link rel="stylesheet" type="text/css" href="css/style.css" />
</head>
<body>
<div id="header"><img src="images/header.gif" />
<div id="menu"><a href="about.php">About</a> <a href="settings.php">Settings</a> <a href="index.php">Song Index</a> </div>
</div>
<!--<div id="autoscr"><a href="#" onclick="modScroll(250);">Start</a></div>-->
<div id="setlist"></div>
<?php

if (isset($_GET['s']))
{
	if (file_exists('xml/' . $_GET['s']))
	{
		$xml = simplexml_load_file('xml/' . $_GET['s']);
	}
	else if (file_exists('xml/' . $_GET['s']) . '.txt')
	{
		$xml = simplexml_load_file('xml/' . $_GET['s'] . '.txt');
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
echo "<p><strong>" . $xml->title . "</strong><br />";
echo "<em>" . $xml->author . "</em></p>";
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
<div id="songnav"></div>
<div id="innersongnav">Presentation Order<br /><? 
$pres = explode(" ", $xml->presentation);
foreach ($pres as $part)
{
	echo "<a href='#" . $part . "'>" . $part . "</a> &nbsp;&nbsp;";
}
?></div>
<?
}
else
{
	?>
    <a href="index.php">Choose a song to view</a>
    <?
}
?>
</body>
</html>