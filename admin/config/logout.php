<?php
session_start();
include('fn/admin-fn.php');
Admin_Unset_Login_Params() ;
header('location: ../login.php');
?>