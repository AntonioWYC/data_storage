<?php
$con = mysqli_connect('127.0.0.1','root','root','park_ticket');
if (!$con) {
    die('Could not connect: ' . mysql_error($con));
}
?>