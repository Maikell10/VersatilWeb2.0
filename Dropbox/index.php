<?php
require_once 'terceros/dropbox/vendor/autoload.php';

use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;

$dropboxKey = "t1ddzra2rhbuzou";
$dropboxSecret = "eg0nujcek0f394h";
$dropboxToken = "Nsp1_XYNsRAAAAAAAAAAAba53aJwNEYmg9Bau0UN3cEXdcWC75REkk_l-ibNUhKm";


$app = new DropboxApp($dropboxKey, $dropboxSecret, $dropboxToken);
$dropbox = new Dropbox($app);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>

    <form action="upload.php" method="post" enctype="multipart/form-data">
        <input type="file" name="file" id="file" />
        <br>
        <input type="submit" name="btnenviar" id="btnenviar" />
    </form>

    <br>
    <hr>

    <a href="download.php" target="_blank">Click for donwload</a>


    <hr>


    <?php

    $file = $dropbox->search('/', '2631.pdf');

    $var = $file->getData();
    $nombre_file = $var['matches'][0]['metadata']['name'];

    $nombre_file;
    if ($nombre_file) {
        echo 'existe- ';
    } else {
        echo 'no existe- ';
    }

    ?>

</body>

</html>