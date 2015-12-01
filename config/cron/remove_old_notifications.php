<?php

# Delete Old notifications from database every 5days #
include("../inc/connect.php");
mysql_query("DELETE FROM std_notifications WHERE viewed = '1'");

?>