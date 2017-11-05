<?php
global $con;
$con = mysqli_connect("127.0.0.1", "xbct", "db_passw0rd");
mysqli_select_db($con,"xbct") or die("Cannot select DB");
?>
