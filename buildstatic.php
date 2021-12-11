<?
$dir = "xml";
	$files = array();
	$thedir = opendir($dir);
	while (false !== ($file = readdir($thedir)))
	{
		if ($file != "." && $file != "..")
		{
				array_push($files, $file);
				$output='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>OpenSong Viewer</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <script type="text/javascript" src="../js/jquery.js"></script>
  <script type="text/javascript" src="../js/jquery.transposer.js"></script>
  <script type="text/javascript" src="../js/jquery-ui-1.8.2.custom.min.js"></script>
  <script type="text/javascript" src="../js/offlinefunctions.js"></script>
 <link rel="stylesheet" type="text/css" href="../css/jquery.transposer.css" />
 <link type="text/css" href="../css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />	
 <link rel="stylesheet" type="text/css" href="../css/style.css" />
</head>
<body>
<div id="header"><img src="../images/header.gif" />
<div id="menu"><a href="static.html">Song Index</a> (Offline Mode)</div>
</div>
<!--<div id="autoscr"><a href="#" onclick="modScroll(250);">Start</a></div>-->
<div id="setlist"></div>';
				$xml = simplexml_load_file('xml/' . $file);
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
				$output .= "<p><strong>" . $xml->title . "</strong><br />";
				$output .= "<em>" . $xml->author . "</em></p>";
				$output .= "<pre data-key=\"$key\">";
				foreach ($temp as $line)
				{
					if (strpos($line, ';') === 0)
					{
						continue;
					}

					if (strpos($line, '.') === 0)
					{
						$output .= ltrim($line, '.') . "\n";
					}			 
					else
					{
						$output .= $line . "\n";
					}
				}
				$output .= "</pre>";
				$output .= "<p><em>" . $xml->copyright . "</em><br />";
				$output .= "<em>" . $xml->ccli . "</em></p><div id='songnav'></div>";
				$output .= "</body></html>";
				$fp=fopen('songs/' . $file . '.html','w+');
				fputs($fp,$output);
				fclose($fp);
				echo $file . " created<br />";
		}
	}
	
	$last = "AA";
	$static =  '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>OpenSong Viewer</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link type="text/css" rel="stylesheet" href="../css/style.css" />
    <link type="text/css" href="../css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />	
    <script type="text/javascript" src="../js/jquery.js"></script>
	<script type="text/javascript" src="../js/jquery.transposer.js"></script>
    <script type="text/javascript" src="../js/jquery-ui-1.8.2.custom.min.js"></script>
    <script type="text/javascript" src="../js/offlinefunctions.js"></script>
</head>
<body>';
$manifest .= "CACHE MANIFEST
#Updated: " . date('F j, Y, g:i a') .
"
js/jquery.transposer.js
js/jquery.js
js/functions.js
js/jquery-ui-1.8.2.custom.min.js
js/offlinefunctions.js
css/jquery.transposer.css
css/ui-lightness/jquery-ui-1.8.2.custom.css
css/style.css
images/header.gif
images/headerbg.gif
css/ui-lightness/images/ui-bg_highlight-soft_100_eeeeee_1x100.png
css/ui-lightness/images/ui-icons_222222_256x240.png
css/ui-lightness/images/ui-bg_gloss-wave_35_f6a828_500x100.png
css/ui-lightness/images/ui-icons_ffffff_256x240.png
css/ui-lightness/images/ui-bg_glass_100_f6f6f6_1x400.png
songs/static.html
offline.html
";
$listing = "";
$alpha = "";
sort($files);
	foreach ($files as $f)
	{
		$letter = substr($f, 0, 1);
		if ($letter!=$last)
		{
			if ($last != "AA")
			{
				$listing .= "</ul>";
				
			}
			$listing .=  "<a name='" . $letter . "' class='noborder'></a><p><strong>" . $letter . "</strong></p><ul>";
			$alpha .= "<li><a href='#" . $letter . "' >" . $letter . "</a></li>";
		}
		$listing .=  '<li><a href="' . $f . '.html">' . $f .
		'</a> | <a href="javascript:void(0)" onclick="addToSetlist(\'' . str_replace(" ", "_", $f) . '\')">Add</a> </li>';
		$last = $letter;
		$manifest .= "songs/" . str_replace(" ", "%20", $f) . ".html\n";
	}
	$static .= '<div id="header"><img src="../images/header.gif" />
<div id="menu">(Offline Mode)</div>
</div>
<div id="container">
<div id="alphalist">
<hr />
<strong>Song Index</strong>
<ul>' . $alpha . '</ul></div>
<hr />
<div id="songlist">' .
$listing .
'</div>
<div id="setlist"></div>
</div>
</body>
</html>';
	$fp=fopen('songs/static.html','w+');
	fputs($fp,$static);
	fclose($fp);
	echo "index created<br />";
	$manifest .= "\n\nNETWORK:\nindex.php\n\nFALLBACK:\n/ offline.html";
	$fp=fopen('os.manifest','w+');
	fputs($fp,$manifest);
	fclose($fp);
	echo "manifest created";
?>



