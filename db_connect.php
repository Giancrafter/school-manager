<?php
require_once __DIR__ . '/vendor/autoload.php';
    try{
    $m = new MongoDB\Client;

    $db = $m->school_manager;
    }
    catch (Exception $e){
        die("Error. Couldn't connect to the server. Please Check");
    }
       session_start();
?>