<?php
$languages = array (
    array("de","Deutsch"),
    array("en","English"),
  );
$available_lang = ['de', 'en']; 
if (!isset($_SESSION['language'])){
    $language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    $language = in_array($language, $available_lang) ? $language : 'en';
    include("lang/{$language}.lang.php"); 
} else {
  include("lang/{$_SESSION['language']}.lang.php");
}
?>