<?php
session_start();
include("fn/fn.php") ;
Unset_Login_Params() ; // Unset all login parameters
$ref = $_SERVER['HTTP_REFERER'] ;
header("location: $ref") ;
?>