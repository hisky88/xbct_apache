<?php
global $con;
$con = mysqli_connect("127.0.0.1", "webdiff", "db_passw0rd");
mysqli_select_db($con,"webdiff") or die("Cannot select DB");
?>
