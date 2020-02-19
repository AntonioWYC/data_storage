<?php
    include 'connect_db.php';
    include 'response.php';
    $ticket_opt = $_GET["opt"];
    $opt_v = intval($_GET["q"]);
    $opt_t = $_GET["type"];
    $opt_lc = $_GET["linkcase"];
    mysqli_select_db($conn,"ajax_demo");
    $sql = "";
    if ($opt_t == ""){
        $sql="SELECT * FROM unpark_tickets";
    }
    else{
        if ($opt_lc == ""){
            $sql="SELECT * FROM unpark_tickets where ticket_type='".$opt_t."'";
        }
        else{
            $sql="SELECT * FROM unpark_tickets where ticket_type='".$opt_t."' and linkcase='".$opt_lc."'";
        }
    }
    $result = mysqli_query($conn,$sql);
    
    while($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    mysqli_close($conn);
    return Response::api_response(200, 'Success', $rows);
    ?>
