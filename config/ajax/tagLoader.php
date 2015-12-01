<?php 
session_start();
if(!isset($_POST['action'])){
echo "Access Denied" ;
exit();
}

$action = $_POST['action'] ;

require_once("../inc/configurations.php"); 
require_once("../inc/connect.php"); 
require_once("../fn/lib.php"); 
require_once("../fn/fn.php"); 


// Load questions for Questions Page
if($action == 'LoadFullList'){
$qlist = Tags_que_Load(0,$maxRes) ; 

if($qlist == ''){
$tag = $_SESSION['tag'] ;
echo "
<script>
$(function() {
$('.pages').remove();
 });
 </script>
 <br><br>
 <h2>No question related to '$tag' was found !</h2>
" ;
exit();
}

$data = '' ;
foreach($qlist as $value){
$data .=  $value ;
}


$count = count($qlist);
if($count <= $maxRes){  // if result is less than expected then end has reached
$data .= "
<script>
$(function() {
$('.next').css('visibility','hidden');
 });
 </script>
" ;
}
echo $data ;
}

// Load Previous question list 
if($action == 'LoadPrev'){

$current = $_POST['current'] ;
$current-- ;
$current-- ;

$next =  $maxRes * $current  ;

$qlist = Tags_que_Load($next,$maxRes) ; 
foreach($qlist as $value){
echo $value ;
}
}

// Load Next question list 
if($action == 'LoadNext'){

$current = $_POST['current'] ;
$next = $maxRes * $current ;

$qlist = Tags_que_Load($next,$maxRes) ; 

$data = '' ;

foreach($qlist as $value){
$data .=  $value ;
}

$count = count($qlist);
if($count < $maxRes){  // if result is less than expected then end has reached
$data .= "
<script>
$(function() {
$('.next').css('visibility','hidden');
 });
 </script>
" ;
}

echo $data;
}


?>