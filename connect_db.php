<?php
$conn = mysqli_connect('127.0.0.1','root','wyc94072','park_data');
if (!$conn) {
    die('Could not connect: ' . mysql_error($conn));
}
echo "连接成功";
?>