<?php 
require_once 'terceros/dropbox/vendor/autoload.php';
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;

$dropboxKey ="t1ddzra2rhbuzou";
$dropboxSecret ="eg0nujcek0f394h";
$dropboxToken="sl.Afj0LjT8rFo3YlHvubZqcwgg3bYU3ugD0uFcHk9FzYbmICVKZNqiOrpd-h0IU9SKwb-aa1ZvmHfqWJWIhcFZqtGZA-K1Djhtyykt9em1yySDLQdTcssQj9iBRjvx-5DyZHV6jmnmJpI";


$app = new DropboxApp($dropboxKey,$dropboxSecret,$dropboxToken);
$dropbox = new Dropbox($app);

if(!empty($_FILES)){
    $nombre = uniqid();
    $nombre = '1500';
    $tempfile = $_FILES['file']['tmp_name'];
    $ext = explode(".",$_FILES['file']['name']);
    $ext = end($ext);
    $nombredropbox = "/" .$nombre . "." .$ext;

   try{
        $file = $dropbox->simpleUpload( $tempfile,$nombredropbox, ['autorename' => true]);
        //ajax request
        //http_response_code(200);
        echo "archivo subido";
   }catch(\exception $e){
        print_r($e);
        
   }
}
