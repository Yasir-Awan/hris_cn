<?php
$con = odbc_connect("checkinout","","");
if($con){
    echo "connected";
}else{
    echo "failed";
}
?>