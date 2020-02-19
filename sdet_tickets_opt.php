<html>
<head>
<?php
function createNonceStr($length = 8){
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return "z".$str;
    }

function arithmetic($timeStamp,$randomStr){
        $arr['timeStamp'] = $timeStamp;
        $arr['randomStr'] = $randomStr;
        $arr['token'] = 'SDETTICKETTOKEN';
        //按照首字母大小写顺序排序
        sort($arr,SORT_STRING);
        //拼接成字符串
        $str = implode($arr);
        //进行加密
        #$signature = sha1($str);
        $signature = md5($signature);
        //转换成大写
        $signature = strtoupper($signature);
        return $signature;
    }

function lang($key){
    $randomstr = createNonceStr();
    $timeStamp = time();
        $lang = array (
        "randomStr" => $randomstr,
        "timeStamp" => $timeStamp,
        "signature" => arithmetic($timeStamp, $randomStr)
        );
        return $lang[$key];
    }

    $language_code_available= array("randomStr", "timeStamp", "signature");
    $langs = array();
    for($i=0; $i <= count($language_code_available) - 1; $i++){
        $langs[$language_code_available[$i]] = lang($language_code_available[$i]);
        }
?>



<script type="text/javascript">
var temple_edit_ticket_value = "";
var temple_edit_ticket_type_value = "";
function showUser(loadtype) {
    var js_lang = <?php echo json_encode($langs) ?>;
    if (verify_token("t="+js_lang["timeStamp"]+"&r="+js_lang["randomStr"]+"&s="+js_lang["signature"]) == false){
        alert("Not authorization Access!")
        return
    }
    var ticket_no = document.forms['unpark']['addTicket'].value
    var ticket_type = document.forms['unpark']['type'].value
    var link_case = document.forms['unpark']['linkcase'].value
    if (loadtype != 0){
        if (verify_ticket(ticket_no)){
            document.forms['unpark']['addTicket'].value = ""
            ajax_operation("opt=add&q="+ticket_no+"&t="+ticket_type+"&lc="+link_case)
        }
    }
    ajax_operation("opt=&q=&t=")
}


function verify_ticket(ticket_no=""){
    if (ticket_no == ""){
        alert("Please enter ticket number!")
        return false
    }
    if (isNaN(ticket_no)){
        alert("Please enter numberic ticket number!")
        return false
    }
    return true
}

function verify_ticket_type(ticket_type=""){
    if (ticket_type == ""){
        alert("Please enter ticket type!")
        return false
    }
    return true
}

function edit_ticket(id){
    var input_element_value = document.getElementById('input_unpark_text' + id).value;
    if (temple_edit_ticket_value == input_element_value)
        return
    if (verify_ticket(input_element_value)){
        ajax_operation("opt=updateticket&id="+id+"&q="+input_element_value)
    }
}

function edit_ticket_type(id){
    var type_element_value = document.getElementById('edit_ticket_type' + id).value;
    if (temple_edit_ticket_type_value == type_element_value)
        return
    ajax_operation("opt=updatetype&id="+id+"&status="+type_element_value)
}

function delete_ticket(id=""){
    var result = confirm("Want to delete?");
    if (result) {
        //Logic to delete the item
        ajax_operation("opt=delete&q="+id)
    }
}

function entry_ticket(ticketid=""){
    var result = confirm("Want to Entry?");
    if (result) {
        //Logic to delete the item
        ajax_operation("opt=entry&ticketid="+ticketid);
    }
}

function edit_text_in(id=""){
    text_element = document.getElementById("input_unpark_text"+id)
    text_element.style.background = "yellow";
    temple_edit_ticket_value = text_element.value;
}

function edit_text_out(id=""){
    var ticket_element = document.getElementById("input_unpark_text"+id);
    ticket_element.style.background = "";
    if (!isNaN(id))
        {   
            edit_ticket(id);
        }
}

function edit_ticket_type_in(id=""){
    text_element = document.getElementById("edit_ticket_type"+id)
    text_element.style.background = "yellow";
    temple_edit_ticket_type_value = text_element.value;
}

function edit_ticket_type_out(id=""){
    var ticket_element = document.getElementById("edit_ticket_type"+id);
    ticket_element.style.background = "";
    edit_ticket_type(id);
}

function edit_textfield_in(id=""){
    document.getElementById(id).style.background = "yellow";
}

function edit_textfield_out(id="",optid=''){
    document.getElementById(id).style.background = "";
    if (!isNaN(optid))
        {   
            var des = document.getElementById(id).value;
            ajax_operation("opt=editdes&ticketid="+optid+"&des="+des);
        }
}

function change_type(id=""){
        select_element = document.getElementById("type"+id).value;
        ajax_operation("opt=changetype&id="+id+"&select_value="+select_element);

}

function checkboxChange(checkbox){
    if(checkbox.checked){
        ajax_operation("opt=&q=&t=&enable=1");
    }
    else{
        ajax_operation("opt=&q=&t=&enable=0");
    }
}

function ajax_operation(parameters){
    if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } 
    else{
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("txtHint").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET","add_unpark_ticket.php?"+parameters,true);
    xmlhttp.send();
}

function verify_token(parameters){
    if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } 
    else{
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
    xmlhttp.onreadystatechange = function() {

        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText == "1"){
                return true
            }else{
                return false
            }
            
        }
    };

    xmlhttp.open("GET","authorization.php?"+parameters,true);
    xmlhttp.send();
}

</script>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
<script src='https://kit.fontawesome.com/a076d05399.js'></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body onload="showUser(0)" align='center'>
<div class="jumbotron text-center">
    <div><h1>Auto-Test Tickets Management</h1></div>
    <div align="center">All Tickets are test data, used for automation scripts. Stored in in sentry DB.</div>
</div>

<div class="container" style="margin-top:0px">
<div class="row">
    <div class="col-sm-2">
    </div>
<div class="col-sm-4">
    <h5><i class="material-icons" style="font-size:18px">&#xe39d;</i> Add Tickets</h5>
        <form name="unpark">
        <input type="text" name="addTicket" placeholder="Ticket ID" required>
        <br>
        <input type="text" name="linkcase" placeholder="Link to case">
        <br>
        <select name="type" width=10 required>
             <option value='b'>1 Day</option>
             <option value='c'>2 Day</option>
             <option value='a' selected>Annual Ticket</option>
             <option value='d'>GA</option>
             <option value='e'>Other</option>
        </select>
        <input type="button" value="     Add     " onclick="showUser()" class="btn btn-outline-primary btn-sm">
        <div></div>
        <div></div>
        </form>
</div>
<div class="col-sm-4">
    <h5><i class='fab fa-leanpub'></i> Related Links</h5>
        <ul class="nav nav-pills flex-column">
        <li class="nav-item"><a href="tickets_server.php?format=xml">View Tickets - XML</a></li>
        <li class="nav-item"><a href="tickets_server.php?format=json">View Tickets - JSON</a></li>
    <div>
        <label>
            <input type="checkbox" id="enablestatus" class="form-check-input" onchange="checkboxChange(this)">Enable Ticket Status 
        </label>
    </div>
</div>

</div>
<div id="txtHint" align='center'><b></b></div>
<div class="jumbotron text-center" style="margin-bottom:0">
  <p>Sentry Studio Deserved</p>
</div>
<script>
$(document).ready(function(){
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#myTable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>
</body>
</html>