<?php
$conn = mysqli_connect('127.0.0.1','root','wyc94072','park_ticket');
if (!$conn) {
    die('Could not connect: ' . mysql_error($conn));
}
?>