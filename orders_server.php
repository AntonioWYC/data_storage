<?php
    include 'connect_db.php';
    include 'response.php';

    #$opt_v = intval($_GET["q"]);
    #$ticket_opt = $_GET["opt"];

    isset($_GET["type"])?:$_GET["type"]="";
    $opt_t = $_GET["type"];

    isset($_GET["linkcase"])?:$_GET["linkcase"]="";
    $opt_lc = $_GET["linkcase"];


    mysqli_select_db($conn,"park_ticket");
    $sql = "";
    if ($opt_t == ""){
        $sql="SELECT * FROM orders";
    }
    else{
        if ($opt_lc == ""){
            $sql="SELECT * FROM orders where order_type='".$opt_t."'";
        }
        else{
            $sql="SELECT * FROM orders where order_type='".$opt_t."' and linkcase='".$opt_lc."'";
        }
    }
    $result = mysqli_query($conn,$sql);
    
    while($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    mysqli_close($conn);
    return Response::api_response(200, 'Success', $rows);

