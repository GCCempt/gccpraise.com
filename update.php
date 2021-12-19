<?php


function dirCleanup($directory)
{
    $filesToDelete = scandir($directory);
    foreach ($filesToDelete as $file) {
        if (!is_dir($directory . $file)) {
            unlink($directory . $file);
        }
    }
}


function dropboxApiUpdate($apiEndpoint, $downloadDir, $copyToDir, $dropboxDownloadSourceDir)
{
    if (!is_dir($downloadDir . $copyToDir)) {
        mkdir($downloadDir . $copyToDir);
    }

    # Clean up the Dropbox_Copy Dir (Sets/ or Songs/)
    dirCleanup($downloadDir . $copyToDir);

    # Clean up the Set Index Folder (Sets/ or Songs/)
    dirCleanup($copyToDir);

    $env = getenv('DROPBOX_TOKEN');
    $curl = curl_init($apiEndpoint);
    curl_setopt($curl, CURLOPT_URL, $apiEndpoint);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $headers = array('Accept: text/plain', 'Content-Type: text/plain', 'Authorization: Bearer ' . $env, 'Dropbox-API-Arg: {"path":"' . $dropboxDownloadSourceDir . '"}');
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $resp = curl_exec($curl);
    curl_close($curl);
    $filename = str_replace("/", ".zip", $copyToDir);
    $songs = fopen($filename, 'w');
    fwrite($songs, $resp);
    $zip = new ZipArchive;
    $res = $zip->open($filename);
    if ($res === TRUE) {
        $zip->extractTo($downloadDir);
        $zip->close();
    }
    $files = scandir($downloadDir . $copyToDir);
    foreach ($files as $filename) {
        if (!is_dir($filename)) {
            copy($downloadDir . $copyToDir . $filename, $copyToDir . $filename);
        }

    }

}

dropboxApiUpdate("https://content.dropboxapi.com/2/files/download_zip", "dropbox_copy/", "Songs/", "/OpenSongV2/OpenSong Data/Songs/");
dropboxApiUpdate("https://content.dropboxapi.com/2/files/download_zip", "dropbox_copy/", "Sets/", '/OpenSongV2/OpenSong Data/Sets/');
header("Location: preview_index.php");