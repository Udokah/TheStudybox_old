<?php
$host="localhost:3306";
$databaseuser="root";
$databasepassword="root";
$database="thestudy_data";
$connect = mysql_connect($host,$databaseuser,$databasepassword);
if(!$connect) {
  die("can't connect:" . mysql_error());
}
$db = mysql_select_db($database,$connect) or die ("unable to select database.");
?>
