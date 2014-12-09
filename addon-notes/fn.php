<?php

/* NOTE ADDON FUNCTIONS */

function Load_Files($page , $searchMode ){
global $max ;
$downloadlink = $html = '' ;
$data = array();
$i = 0 ;

if( $searchMode == 'OFF' ){
$qry = "SELECT * FROM std_notes ORDER BY note_id DESC LIMIT $page, $max" ;
}
else{
$qry = "SELECT DISTINCT * FROM std_notes WHERE title LIKE '%".$searchMode."%' OR course LIKE '%".$searchMode."%' OR level LIKE '%".$searchMode."%' OR MATCH (title,course) AGAINST ('".$searchMode."') ORDER BY note_id DESC LIMIT $page, $max" ;
}
$delete = '' ;
$q = mysql_query($qry) or die(mysql_error());
while($r = mysql_fetch_array($q)){

$file = 'addon-notes/Uploaded_Notes/'.$r['filename'] ;
$title = $r['title'] ;
$course = $r['course'] ;
$level = $r['level'] ;
$date = time_since($r['datestamp']) ;

if(file_exists('Uploaded_Notes/'.$r['filename'])){
$file = 'addon-notes/Uploaded_Notes/'.$r['filename'] ;
$downloadlink = "<a href='$file'>".$title."</a>" ;
}
else{
$downloadlink = 'File not found !' ;
}

if(isset($_SESSION['uid'])){
if($_SESSION['uid'] == $r['uid']){
$delete = "<a class='remove' href='".$r['filename']."'>remove</a>" ;
}
else{
$delete = '' ;
}
}

$html = '<tr><td>'.$date.'</td><td>'.$downloadlink.'</td><td>'.$course.'</td><td>'.$level.'</td><td>'.$delete.'</td></tr>' ;

$data[$i] = $html ;

$i++ ;
}

return $data ;
}


?>