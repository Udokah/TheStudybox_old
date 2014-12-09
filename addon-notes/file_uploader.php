<?php
session_start();
ini_set('upload_max_filesize', '10MB');
ini_set('post_max_size', '10MB');
ini_set('max_input_time', 600);
ini_set('max_execution_time', 600);

include('../config/inc/connect.php') ;
include('../config/fn/lib.php') ;

if(!isset($_POST['action'])){
echo 'Access Denied' ;
exit();
}

$action = clean($_POST['action']) ;

if( $action !== 'upload-notes'){
exit();
}

	$title = ucwords(strtolower(clean($_POST['title']))) ;
	$course = ucwords(strtolower(clean($_POST['course']))) ;
	$level = clean($_POST['level']) ;
	
unset($action , $_POST);  // speed optimization

/// check if title has been used before
$q = mysql_query("SELECT title FROM std_notes WHERE title = '$title'") ;
$r = mysql_fetch_array($q);
if($r['title']){
    $ret = '$(function(){ ' . 'alert(\'This title has been used before, please change it.\')'.' })' ;
	echo $ret ;
	exit();
}

$note = $_FILES['files'] ;
unset($_FILES , $r ); /// speed optimization


	$ret = '' ;

	 	// Check if image exceeds file size 9mb
	if( $note['size'] > 9437184 ){
    $ret = '$(function(){ ' . 'alert(\'The Note exceeds the file size limit\')'.' })' ;
	echo $ret ;
	exit();
	}
	
	/// Upload File
	$doUpload = Upload_image( $note['name'] , $note['tmp_name'] , 'Uploaded_Notes/' ) ;
	unset($note); /// speed optimization
	
	if($doUpload == false){
	$ret = '$(function(){ ' . 'alert(\'File Upload Failed due to poor connectivity, please try again later\')'.' })' ;
	}
	else{
	$uid = $_SESSION['uid'] ;
	$delete = "<a class='remove' href='".$doUpload."'>remove</a>" ;
	if(mysql_query("INSERT INTO std_notes SET uid='$uid', title='$title', course='$course', level='$level', filename='$doUpload'")){
	$html = "<tr><td>Just Now</td><td><a href='addon-notes/Uploaded_Notes/$doUpload'>$title</a></td><td>$course</td><td>$level</td><td>$delete</td></tr>" ;
	$js = "var html = \"$html\" ;
	      $('.notes').find('#ldhere').prepend(html) ; $('#uploadNote').reset(); " ;
	$ret = '$(function(){ '.$js.' })' ;
	} /// Success
	else{
	$ret = '$(function(){ ' . 'alert(\'File Upload Failed, please try again later\')'.' })' ;
	unlink('Uploaded_Notes/'.$doUpload);  // delete uploaded note
	}
	}
	
	echo $ret ;
	exit();
	
	



?>