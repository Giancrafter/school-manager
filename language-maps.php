<?php
$languages = array (
    array("de","Deutsch"),
    array("en","English"),
  );
if (!isset($_SESSION['lang'])){
    $language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    $acceptLang = ['de', 'en']; 
    $language = in_array($language, $acceptLang) ? $language : 'en';
    include("lang/{$language}.lang.php"); 

}
?>