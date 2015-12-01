<?php

if(isset($_COOKIE['ref'])){
$ref = $_COOKIE['ref'] ;
}
else{
$ref = $_SERVER['HTTP_REFERER'] ;
}

header("location: $ref");
?>