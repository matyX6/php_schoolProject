<?php
session_start();
//unistavanje sesijskih varijabli
$_SESSION = array();
//unistavanje sesijskog cookie
if(isset($_COOKIE[session_name()])){
    setcookie(session_name(), "", time()-3600, '/');
}
//unistiti sesiju
session_destroy();
header("Location:login.php");
?>