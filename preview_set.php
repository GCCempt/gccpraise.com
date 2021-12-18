<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" manifest="os.manifest" lang="en">
<head>
    <title>OpenSong Viewer</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link type="text/css" rel="stylesheet" href="css/navstyle.css"/>
    <link type="text/css" href="css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="css/jquery.transposer.css"/>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/jquery.transposer.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.8.2.custom.min.js"></script>
    <script type="text/javascript" src="js/functions.js"></script>
    <link type="text/css" rel="stylesheet" href="css/style.css" />

</head>
<body>
<?php
$listing = "";
$alpha = "";
$preview = "All In All";
$s = false;
$searchresults = "";
$dir = "sets/";
$files = array();
$dir = "sets/";
$thedir = opendir($dir);
while (false !== ($file = readdir($thedir))) {
    if ($file != "." && $file != "..") {
        $files[] = $file;
    }
}
sort($files);
if (isset($_POST['search'])) {
    $s = true;
    $pattern = '/' . $_POST['search'] . '/i';
}
$last = "AA";
foreach ($files as $f) {
    $letter = substr($f, 0, 1);
    if ($letter != $last) {
        if ($last != "AA") {
            $listing .= "</ul>";

        }
//		$listing .=  "<a name='" . $letter . "' class='noborder'></a><p><strong>" . $letter . "</strong></p><ul>";
//		$alpha .= "<li><a href='#" . $letter . "' >" . $letter . "</a></li><br>";
    }
    $listing .= '<li><a href="preview_set.php?s=' . $f . '">' . $f . '</a><br>';
    $last = $letter;
}
?>
<?php include "nav_menu.html" ?>
<div id="container">

    <h1 style='font-size:150%'>Set Listing</h1>

    <div class="colmask leftmenu">
        <div class="colleft">
            <div class="col1">
                <?php

                if (isset($_GET['s'])) {
//	if (file_exists('sets/' . $_GET['s']))
                    if (file_exists($dir . $_GET['s'])) {
                        $xml = simplexml_load_file($dir . $_GET['s']);
                        $preview = $_GET['s'];


                        $xml = simplexml_load_file($dir . $preview);
                        //print_r($xml);
                        $set_name = $xml['name'];
                        //$temp = explode("\n", $l);

                        echo "<h1 style='font-size:150%'>Set Name: <a href='publish_set.php?s=" . $set_name . "'target='_blank'> " . $set_name . "</a> </h1><p></p> \n";
                        echo "<h1 style='font-size:120%'> (Click Set Name above to expand all set contents or click individual songs below)</h1><p></p> \n";

                        foreach ($xml->slide_groups->slide_group as $item) {
                            $slide = $item['name'];
                            $type = $item['type'];
                            $presentation = $item['presentation'];

                            if ($type == "song") {
                                if (!empty($presentation)) {
                                    $song = '<li><a href="preview_index.php?s=' . $slide . '">' . $slide . "    ( " . $presentation . ")" . '</a> </li>';
                                } else {
                                    $song = '<li><a href="preview_index.php?s=' . $slide . '">' . $slide . '</a></li>';
                                }
                                echo $song;
                            } else {
//			echo "<h1><p>$slide\n </p></h1> \n";
                                echo "<p>$slide\n </p> \n";
                            }
                        }
                    } else {
                        echo "<b>Choose a set to preview</b>";
                    }

                } else {
                    ?>
                    <b>Choose a set to preview</b>
                    <?php
                }
                ?>
            </div>
            <div class="col2">
                <?php
                echo $listing;
                ?>
            </div>
        </div>
    </div>
</body>
</html>