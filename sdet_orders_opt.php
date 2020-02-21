<html>
<head>
<?php
    include 'c_lang.php';
    $language_code_available= array("randomStr", "timeStamp", "signature");
    $langs = array();
    for($i=0; $i <= count($language_code_available) - 1; $i++){
        $langs[$language_code_available[$i]] = (new c_lang())->lang($language_code_available[$i]);
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
    var order_no = document.forms['unpark']['addOrder'].value
    var phone_no = document.forms['unpark']['phoneNumber'].value
    var link_case = document.forms['unpark']['linkcase'].value
    var order_type = document.forms['unpark']['type'].value
    if (loadtype != 0){
        if (verify_value_not_blank(order_no, "order number")){
            document.forms['unpark']['addOrder'].value = ""
            document.forms['unpark']['phoneNumber'].value = ""
            document.forms['unpark']['linkcase'].value = ""
            ajax_operation("opt=add&q="+order_no+"&pn="+phone_no+"&ot="+order_type+"&lc="+link_case)
        }
    }
    ajax_operation("opt=&q=&pn=&ot=")
}

function verify_phone_number(phone_no=""){
    if (phone_no == ""){
        alert("Please enter phone number!")
        return false
    }
    return true
}

function verify_value_not_blank(value, msg){
    if(value == ""){
        alert("Please enter: " + msg + " !")
        return false
    }
    return true
}

function verify_order_type(order_type=""){
    if (order_type == ""){
        alert("Please enter order type!")
        return false
    }
    return true
}

function edit_order(id){
    var value = document.getElementById('input_order_number_text' + id).value;
    if (temple_order_number_value == value)
        return
    if (verify_value_not_blank(value, "order number")){
        ajax_operation("opt=updateorder&id="+id+"&q="+value)
    }
}

function edit_phone_number(id){
    var value = document.getElementById('input_phone_number_text' + id).value;
    if (temple_phone_number_value == value)
        return
    if (verify_phone_number(value)){
        ajax_operation("opt=updatephone&id="+id+"&pn="+value)
    }
}

function edit_link_case(id){
    var value = document.getElementById('edit_link_case' + id).value;
    if (temp_link_case_value == value)
        return
    if (verify_value_not_blank(value, "link case")){
        ajax_operation("opt=updatelinkcase&id="+id+"&lc="+value)
    }
}

function delete_ticket(id=""){
    var result = confirm("Want to delete?");
    if (result) {
        //Logic to delete the item
        ajax_operation("opt=delete&q="+id)
    }
}

// function entry_ticket(ticketid=""){
//     var result = confirm("Want to Entry?");
//     if (result) {
//         //Logic to delete the item
//         ajax_operation("opt=entry&ticketid="+ticketid);
//     }
// }

function edit_text_in(id=""){
    var ele = document.getElementById("input_order_number_text"+id)
    ele.style.background = "yellow";
    temple_order_number_value = ele.value;
}

function edit_text_out(id=""){
    var ele = document.getElementById("input_order_number_text"+id);
    ele.style.background = "";
    edit_order(id);
}

function edit_phone_number_in(id=""){
    var ele = document.getElementById("input_phone_number_text"+id)
    ele.style.background = "yellow";
    temple_phone_number_value = ele.value;
}

function edit_phone_number_out(id=""){
    var ele = document.getElementById("input_phone_number_text"+id);
    ele.style.background = "";
    if (!isNaN(id))
        {
            edit_phone_number(id);
        }
}

// function edit_ticket_type_in(id=""){
//     text_element = document.getElementById("edit_ticket_type"+id)
//     text_element.style.background = "yellow";
//     temple_edit_ticket_type_value = text_element.value;
// }
//
// function edit_ticket_type_out(id=""){
//     var ticket_element = document.getElementById("edit_ticket_type"+id);
//     ticket_element.style.background = "";
//     edit_ticket_type(id);
// }

function edit_link_case_in(id=""){
    var lc_element = document.getElementById("edit_link_case" + id)
    lc_element.style.background = "yellow";
    temp_link_case_value = text_element.value;
}

function edit_link_case_out(id=""){
    var lc_element = document.getElementById("edit_link_case"+id);
    lc_element.style.background = "";
    edit_link_case(id);
}

function edit_textfield_in(id=""){
    document.getElementById(id).style.background = "yellow";
}

function edit_textfield_out(id="",optid=''){
    document.getElementById(id).style.background = "";
    if (!isNaN(optid))
        {   
            var des = document.getElementById(id).value;
            ajax_operation("opt=editdes&id="+optid+"&des="+des);
        }
}

//Edit order type
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
    xmlhttp.open("GET","add_unlink_order.php?"+parameters,true);
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
<script src="/data_storage/js/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
<script src='https://kit.fontawesome.com/a076d05399.js'></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body onload="showUser(0)" align='center'>
<div class="jumbotron text-center" style="background-color: #D1EEEE">
    <div><h1>Auto-Test Orders Management</h1></div>
    <div align="center">All Orders are test data, used for automation scripts. Stored in in sentry DB.</div>
</div>

<div class="container" style="margin-top:0px">
<div class="row">
    <div class="col-sm-2">
    </div>
<div class="col-sm-4">
    <h5><i class="material-icons" style="font-size:18px">&#xe39d;</i> Add Orders</h5>
        <form name="unpark">
        <input type="text" name="addOrder" placeholder="Order Number" required>
        <br>
        <input type="text" name="phoneNumber" placeholder="Phone Number">
        <br>
        <input type="text" name="linkcase" placeholder="Link to case">
        <br>
        <select name="type" width=10 required>
             <option value='b'>1 Day</option>
             <option value='c'>2 Day</option>
             <option value='a' selected>Annual Ticket</option>
             <option value='d'>GA</option>
            <option value='e'>Half Day</option>
             <option value='f'>Other</option>
        </select>
        <input type="button" value="     Add     " onclick="showUser()" class="btn btn-outline-primary btn-sm">
        <div></div>
        <div></div>
        </form>
</div>
<div class="col-sm-4">
    <h5><i class='fab fa-leanpub'></i> Related Links</h5>
        <ul class="nav nav-pills flex-column">
        <li class="nav-item"><a href="orders_server.php?format=xml">View Orders - XML</a></li>
        <li class="nav-item"><a href="orders_server.php?format=json">View Orders - JSON</a></li>
<!--    <div>-->
<!--        <label>-->
<!--            <input type="checkbox" id="enablestatus" class="form-check-input" onchange="checkboxChange(this)">Enable Ticket Status -->
<!--        </label>-->
<!--    </div>-->
</div>

</div>
<div id="txtHint" align='center'><b></b></div>
<div class="jumbotron text-center" style="margin-bottom:0; background-color: #D1EEEE">
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