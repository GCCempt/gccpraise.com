<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" manifest="os.manifest" lang="en">
<head>
    <title>OpenSong Viewer</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link type="text/css" rel="stylesheet" href="css/style.css"/>
    <link type="text/css" rel="stylesheet" href="css/navstyle.css"/>
    <link type="text/css" href="css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="css/jquery.transposer.css"/>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/jquery.transposer.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.8.2.custom.min.js"></script>
    <script type="text/javascript" src="js/functions.js"></script>
</head>
<body>
<?php
$song = "";
$song2 = "";
$videolink = "<iframe width=\"420\" height=\"315\" src=\"//www.youtube.com/embed/user1?rel=0\"  allowfullscreen></iframe>";
$videolink2 = "<iframe width=\"420\" height=\"315\" src=\"//www.youtube.com/embed/user2?rel=0\"  allowfullscreen></iframe>";
$listing = "";
$alpha = "";
$preview = "All In All";
$s = false;
$searchresults = "";
$dir = "../www/xml/";
$files = array();
$dir1 = $dir;
$thedir = opendir($dir1);
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
    if ($s) {
        if (preg_match($pattern, $f)) {
            $searchresults .= '<li><a href="preview_index.php?s=' . $f . '">' . $f . '</a> <a href="javascript:void(0)" onclick="addToSetlist(\'' . str_replace(" ", "_", $f) . '\')"></a> </li>';
        }
    }
    $letter = substr($f, 0, 1);
    if ($letter != $last) {
        if ($last != "AA") {
            $listing .= "</ul>";

        }
        $listing .= "<a class='noborder'></a><p><strong>" . $letter . "</strong></p><ul>";
        $alpha .= "<li><a href='#" . $letter . "' >" . $letter . "</a></li>";
    }
    $listing .= '<li><a href="preview_index.php?s=' . $f . '">' . $f . '</a> <a href="javascript:void(0)" onclick="addToSetlist(\'' . str_replace(" ", "_", $f) . '\')"></a> </li>';
    $last = $letter;
}
?>
<div id="header"><img src="images/gcclogo.gif" alt="gcc logo"/>
    <div id="menu"><a
                href="https://docs.google.com/spreadsheets/d/1_FAwmbf0nH7_qV2MJVWeMRbm--1I_AdLFlaFOXtl8U0/edit#gid=0"
                TARGET="_blank">Worship Schedule</a> <a href="preview_set.php"
                                                        TARGET="_blank">Worship Sets</a></div>
</div>

<div id="alphalist">
    <form action="preview_index.php" method="post"><strong>Search: </strong>

        <label for="search"></label><input type="text" name="search" id="search"/>
        <input type="submit" value="Find"/>
    </form>

    <?php
    if ($s) {
        echo "<div id='results'>Search Results: <ul>" . $searchresults . "</ul></div>";
    }
    ?>
    <hr/>
    <strong>&nbsp;&nbsp;Song Index</strong>
    <ul><?php echo $alpha ?></ul>
</div>
<hr/>
<div class="colmask leftmenu">
    <div class="colleft">
        <div class="col1">
            <?php
            if (isset($_GET['s'])) {
//	if (file_exists('xml/' . $_GET['s']))
                if (file_exists($dir1 . $_GET['s'])) {
                    $xml = simplexml_load_file($dir1 . $_GET['s']);
                    $preview = $_GET['s'];
                } else if (file_exists('xml/' . $_GET['s']) . '.txt') {
                    $xml = simplexml_load_file('xml/' . $_GET['s'] . '.txt');
                    $preview = $_GET['s'] . '.txt';
                }

                $xml = simplexml_load_file($dir1 . $preview);
//print_r($xml);
                $l = $xml->lyrics;
                $temp = explode("\n", $l);

                if (!empty($xml->key)) {
                    $key = $xml->key;
                } else {
                    $keystr = substr($l, strpos($l, '.'), 50);
                    $keystr = preg_replace("/\s/", "", $keystr);
                    $key = substr($keystr, 1, 1);
                    $sharp_flat = substr($keystr, 2, 1);
                    if ($sharp_flat == "#" || $sharp_flat == "b") {
                        $key .= $sharp_flat;
                    }
                }
                if (!empty($xml->hymn_number)) {
                    echo "<em>Hymn #" . $xml->hymn_number . " - </em>";
                }

                echo "<strong><a href='preview_song.php?s=" . $xml->title . "'>" . $xml->title . " (Click for full screen display)</a></strong><br />";
                echo "<em>" . $xml->author . "</em></p>";
                if (!empty($xml->presentation)) {
                    echo "<em>Default Presentation Order #" . $xml->presentation . " - </em>";
                }
                if (!empty($xml->user1)) {
                    echo "<p><strong><a href='" . $xml->user1 . "' TARGET='_blank'>Click to display sample video</a></strong>";
                    $song = $xml->user1;
                    $song = substr($song, 31);
                    echo "<br />";
#        echo "Song reference = " . $song;
                    $videolink = str_replace("user1", $song, $videolink);
                    echo $videolink;
                    echo "<br />";

                }

                echo "<pre data-key=\"$key\">";
                foreach ($temp as $line) {
                    $line = str_replace("’", "'", $line);
                    echo ltrim(str_replace("\t", '   ', $line), '.') . "\n";
                }
                echo "</pre>";
                echo "<p><em>Copyright: " . $xml->copyright . "</em><br />";
                echo "<em>CCLI#: " . $xml->ccli . "</em></p>";
            } else {
                ?>
                <b>Choose a song to preview</b>
                <?php
            }
            if (!empty($xml->user2)) {
                echo "<em> (" . $xml->user2 . ")</em>";
                echo "<p><strong><a href='" . $xml->user2 . "' TARGET='_blank'>Click to display Praise Team video</a></strong>";
                $song2 = $xml->user2;
                $song2 = substr($song2, 31);
                echo "<br />";
#       echo "Song reference = " . $song;
                $videolink2 = str_replace("user2", $song2, $videolink2);
                echo $videolink2;
                echo "<br />";

            }
            echo "<br />";
            ?>
        </div>
        <div class="col2">
            <?php
            echo $listing;
            ?>
        </div>
    </div>
</div>
<!-- <div id="setlist"></div> 
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-1466271-7']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
-->
</body>
</html>