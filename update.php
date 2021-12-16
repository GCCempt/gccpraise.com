<?php

$env = getenv('DROPBOX_TOKEN');
$url = "https://content.dropboxapi.com/2/files/download_zip";
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$headers = array('Accept: text/plain', 'Content-Type: text/plain', 'Authorization: Bearer ' . $env, 'Dropbox-API-Arg: {"path":"/OpenSongV2/OpenSong Data/Songs/"}');
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
$resp = curl_exec($curl);
curl_close($curl);
$filename = 'Songs.zip';
$songs = fopen($filename, 'w');
fwrite($songs, $resp);
$zip = new ZipArchive;
$res = $zip->open($filename);
if ($res === TRUE) {
    $zip->extractTo('dropbox_copy/');
    $zip->close();
    echo "Success!";
} else {
    echo 'Error!';
}
$files = scandir("dropbox_copy/Songs");
foreach ($files as $filename) {
    if (!is_dir($filename)) {
        copy("dropbox_copy/Songs/" . $filename, "www/xml/" . $filename);
    }
    header("Location: preview_index.php");
}
