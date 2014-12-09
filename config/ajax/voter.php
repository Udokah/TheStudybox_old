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

/// Remove Vote from answer
if($action == 'removeVote'){
$uid = $_SESSION['uid'] ;
$aid = clean($_POST['aid']) ;
if(mysql_query("DELETE FROM std_avotes WHERE uid = '$uid' AND aid = '$aid' ")){
Update_vote_counts($aid);
}
}


// Vote question 
if($action == 'votequestion'){

$type = clean($_POST['type']);
$qid = clean($_POST['qid']);
$uid = clean($_SESSION['uid']) ;

$q = "INSERT INTO std_qvotes SET qid = '$qid' , uid = '$uid' , type = '$type' " ;

if(mysql_query($q)){
//// Update Vote counts
$counts = count_q_votes($qid) ;

if(mysql_query("UPDATE std_questions SET vote_count = '$counts' WHERE qid = '$qid'")){

#### ADD NOTIFICATION ####
NOTIFY_questions_voted($qid,$type);

}
}

exit();
}

?>