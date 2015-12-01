<?php
session_start();
ini_set('max_execution_time', 3000); //3000 seconds = 5 minutes

include('../config/inc/connect.php') ;
include('../config/fn/lib.php') ;
include('fn.php') ;

if(!isset($_POST['action'])){
echo 'Access Denied' ;
exit();
}

$max = 15 ;  // max request size

$action = clean($_POST['action']) ;

if($action == 'delete-file'){
$note = $_POST['filename'] ;
$file = 'Uploaded_Notes/'.$note ;
if(file_exists($file)){
if(unlink($file)){
mysql_query("DELETE FROM std_notes WHERE filename = '$note'");
}
}

exit();
}

if($action == 'LoadFiles'){
$page = $_POST['page'] ;
$searchMode = clean($_POST['searchMode']) ;  // search mode
$load = $page *  $max ;

$DATA = Load_Files( $load , $searchMode );

$html = $pagLinks = $class = '' ;
$i = 1 ;


$q = mysql_query("SELECT COUNT(note_id) AS resultcount FROM std_notes") ;
$r = mysql_fetch_array($q);
extract($r);


foreach( $DATA as $inner){
$html .= $inner ;
}

if( $searchMode !== 'OFF' ){
if( $html == ''){
$html = '<tr><td colspan=5>No Result</td></tr>' ;
}
}

$js = "var html = \"$html\" ;
      $('#ldhere').html(html) ; " ;
	  
	 // if first load, load pagination
	if( $page == 0){
	while($resultcount > $max){
	
	if($i == 1){
	$class = "class='selected'" ;
	}
	else{
	$class = '' ;
	}
	
	$get = $i - 1 ;
	$pagLinks .= "<a href='$get' $class >$i</a>" ;
	$resultcount = $resultcount - $max ;
	$i++ ;
	}
	$get = $i - 1 ;
	// Remaining Page
	if( $resultcount !== 0){
	$pagLinks .= "<a href='$get' >$i</a>" ;
	}

	
	$js .= "var links = \"$pagLinks\" ;
	        $('.navPages').html(links) ; " ;
	}
	
	/// if result is less than max hide nav bar and is not first load
	if( count($DATA) < $max  and $page == 0 ){
	$js .= "$('.navPages').hide(); " ;
	}

	$ret = '$(function(){ '.$js.' })' ;
	echo $ret ;
exit();
}

?>