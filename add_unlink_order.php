<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
table {
    width: 500px;
    border-collapse: collapse;
}
table, td, th {
    border: 1px solid black;
    padding: 5px;
}
th {text-align: left;}
</style>
</head>
<body>
<?php
include 'connect_db.php';
include 'response.php';
#$ticket_opt = $_GET["opt"];
$order_opt = $_GET["opt"];

//isset($_GET["q"])?:$_GET["q"]="";
//$opt_v = $_GET["q"];
isset($_GET["q"])?:$_GET["q"]="";
$opt_orderno = $_GET["q"];

isset($_GET["ot"])?:$_GET["ot"]="";
$opt_ordertype = $_GET["ot"];

//isset($_GET["t"])?:$_GET["t"]="";
//$opt_type = $_GET["t"];
isset($_GET["pn"])?:$_GET["pn"]="";
$opt_phoneno = $_GET["pn"];

//isset($_GET["lc"])?:$_GET["lc"]="";
//$opt_linkcase = $_GET["lc"];
isset($_GET["lc"])?:$_GET["lc"]="";
$opt_linkcase = $_GET["lc"];


$ticket_add = 1;
#Insert new order to DB
if ($order_opt === "add"){
    $sql="insert into orders(order_number,order_type,phone_number, linkcase) values ('".$opt_orderno."','".$opt_ordertype."','".$opt_phoneno."','".$opt_linkcase."')";
    if ($opt_v != "0")
    {
        if (mysqli_query($conn,$sql)) {
            echo "New record *".$opt_v."* created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

#Delete order from DB
if ($order_opt === "delete"){
    $sql="DELETE FROM `park_ticket`.`orders` WHERE (`id` = '".$opt_orderno."')";
    if (mysqli_query($conn,$sql)) {
        echo  "Record *".$opt_orderno."* deleted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
#Update order
if ($order_opt === "updateorder"){
    $opt_id = intval($_GET["id"]);
    $opt_orderno = $_GET["q"];
    $sql="update orders set order_number = '".$opt_orderno."'  where id = ".$opt_id."";
    if ($ticket_add != "0")
    {
        if (mysqli_query($conn,$sql)) {
            echo "Record updated to ".$opt_orderno." successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
if ($order_opt === "updatetype"){
    $opt_id = intval($_GET["id"]);
    $status = $_GET["status"];
    $sql="update orders set linkcase = '".$status."'  where id = ".$opt_id."";
    if ($ticket_add != "0")
    {
        if (mysqli_query($conn,$sql)) {
            echo "Record updated to ".$status." successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
#Edit ticket description
if ($order_opt === "editdes"){
    isset($_GET["ticketid"])?:$_GET["ticketid"]="";
    $opt_id = intval($_GET["ticketid"]);
    $des = $_GET["des"];
    $sql="update orders set des = '".$des."'  where id = ".$opt_id."";
    if ($ticket_add != "0")
    {
        if (!mysqli_query($conn,$sql)) {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

//#change ticket type
//if ($order_opt === "changetype"){
//    $opt_id = intval($_GET["id"]);
//    $select_value = $_GET["select_value"];
//    $sql="update orders set order_type = '".$select_value."'  where id = ".$opt_id."";
//    if ($ticket_add != "0")
//    {
//        if (!mysqli_query($conn,$sql)) {
//            echo "Error: " . $sql . "<br>" . $conn->error;
//        }
//    }
//}

//#Entry ticket
//if ($order_opt === "entry"){
//    $opt_ticketid = $_GET["ticketid"];
//    Response::response_entry_ticket(200, $opt_ticketid);
//
//}

$sql="SELECT * FROM orders";
$result = mysqli_query($conn,$sql);
echo "<table class=\"table table-bordered table-hover\" style='width:1000px'>
<thead class='thead-light'>
    <tr>
    <th align='center'> ID (".mysqli_num_rows($result).")</th>
    <th align='center'>Order Type</th>
    <th align='center'>Order Number</th>
    <th align='center'>Phone Number</th>
    <th align='center'>Link Case</th>
    <th align='center' style='width:200px'>Description</th>
    <th align='center' style='width:50px'>Remove</th>
    </thead>
    </tr>";
while($row = mysqli_fetch_array($result)) {
    $display_type = "";
    switch ($row['order_type']) {
        case 'a':
            $display_type = "Annual Ticket";
            break;
        case 'b':
            $display_type = "1 Day";
            break;
        case 'c':
            $display_type = "2 Day";
            break;
        case 'd':
            $display_type = "GA";
            break;
        case 'e':
            $display_type = "Half Day";
            break;
        case 'f':
            $display_type = "Other";
            break;
        default:
            $display_type = "Other";
            break;
    }
    echo "<tbody id=\"myTable\">";
    echo "<tr>";
    echo "<td style='width:80px'>".$row['id']."</td>";
    echo "<td style='width:100px'>
          <select id='type". $row['id'] ."' onchange='change_type(". $row['id'] .")'>
          <option value='". $display_type ."' selected>". $display_type ."</option>
             <option value='b'>1 Day</option>
             <option value='c'>2 Day</option>
             <option value='a'>Annual Ticket</option>
             <option value='d'>GA</option>
             <option value='e'>Half Day</option>
             <option value='f'>Other</option>
          </select>     
         </td>";
    echo "<td style='width:300px'>
          <input id='input_order_number_text". $row['id'] ."' style='width:100%;border:0px' type='text' value='". $row['order_number'] ."' onfocusin='edit_text_in(\"". $row['id'] ."\")' onfocusout='edit_text_out(\"". $row['id'] ."\")'>
          </td>";
    echo "<td style='width:100px'>
         <input id='input_phone_number_text". $row['id'] ."' style='border:1px' type='text' value='". $row['phone_number'] ."' onfocusin='edit_phone_number_in(\"". $row['id'] ."\")' onfocusout='edit_phone_number_out(\"". $row['id'] ."\")'>
         </td>";
    echo "<td style='width:100px'>
         <input id='edit_link_case". $row['id'] ."' style='border:1px' type='text' value='". $row['linkcase'] ."' onfocusin='edit_link_case_in(\"". $row['id'] ."\")' onfocusout='edit_link_case_out(\"". $row['id'] ."\")'>
         </td>";
//    if ($opt_enable_status === "1") {
//        $status = Response::response_ticket_status(200, $row['ticket_number']);
//    }
//    else{
//        $status = "";
//    }
//
//    if ($status === "false")
//    {
//        echo "<td align='center'>false</td>";
//        echo "<td align='center'><input type='submit' id='entry_btn' class='btn btn-info btn-sm' value='Entry' onclick='entry_ticket(\"". $row['ticket_number'] ."\");'></td>";
//    }
//    elseif($status === "true")
//    {
//        echo "<td align='center'>true</td>";
//        echo "<td align='center'></td>";
//    }
//    else{
//        echo "<td align='center'></td>";
//        echo "<td align='center'></td>";
//    }
    echo "<td align='center'><input id='textfield". $row['id'] ."' style='width:100%; border:0px;' length=30 type='textfield' onfocusin='edit_textfield_in(\"textfield". $row['id'] ."\")' onfocusout='edit_textfield_out(\"textfield". $row['id'] ."\",\"". $row['id'] ."\")' value=". $row['des'] ."></td>";
    echo "<td align='center'><input type='submit' class='btn btn-warning btn-sm' id='delete_unpark_btn". $row['id'] ."' value='Delete' onclick='delete_ticket(". $row['id'] .");'></td>";
    echo "</tr>";
    echo "</tbody>";
}
echo "</table>";
?>
</body>
</html>