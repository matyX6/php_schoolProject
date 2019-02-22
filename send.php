<?php
$naslov = "Send Message";
require_once("header.php");
require_once("navigacija.php");

echo '<script>
  document.getElementById("salji").classList.add("active");
</script>';


echo '  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>';
  ?>
  
<?php

// Ignore Warnings
error_reporting(E_ALL & ~E_NOTICE & ~8192);

// Connect to Database
require_once "baza.php";

// Days,Hours,Minutes Time Format
require_once "time.php";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html"; charset=utf-8" />
<title>Send new message </title>
<style>

  body
  {
    margin: 0;
    height: 100%
    background-color: #D3D3D3;
  }
  
  table, #pages
  {
    margin: 5% auto 0 auto;
    width: 800px;
    height: auto;
    overflow: hidden;
    background:  #f1f1f1;
  }
  
  table tr
  {
    width: 100%;
  }
  
  table tr th
  {
    background: #3e3e3e;
    color: #fff !important;
  }
  
  table tr th, table tr td
  {
    padding: 10px;
    color: #999;
    font-family: arial;
    font-size: 12px;
    text-align: center;
    border-left: 1px solid #fff;
    border-right: 1px solid #fff;
  }
  
  table tr td
  {
    border-bottom: 2px solid #fff;
  }
  
  #pages
  {
    margin: 0 auto;
    background: none;
  }
  
   #pages a
  {
    display: inline-block;
    margin: 0 10px 0 0;
    padding: 5px 10px;
    background: #3e3e3e;
    color: #fff;
    border-radius: 3px;
    -webkit-border-radius: 3px;
    font-family: arial;
    font-size: 12px;
    text-decoration: none;
  }
  
  form
  {
    margin:5% auto 0 auto;
    width: 600px;
    height: auto;
    overflow: hidden;
    background: #f1f1f1;
    font-family: arial;
    font-size: 12px;
    color: #999;
    border:2px solid #ccc
  }
  
  form label
  {
    margin: 10px;
    padding: 0;
    float: left;
    clear: both;
    cursor: pointer;
  }
  
  form textarea
  {
    min-height: 160px;
    max-height: 200px;
    resize: vertical;
  }
  form input, form textarea
  {
    margin: 10px;
    padding: 0;
    float: left;
    clear: both;
  }
  
  form input[type=submit]
  {
    padding: 6px 10px;
    background: #3e3e3e;
    color: #fff;
    cursor: pointer;
    border: none;
    border-radius: 3px;
    -webkit-border-radius: 3px;
  }
  
  form input[type=submit]:hover
  {
    background: #09f;
  }
  
  .required
  {
    font-family: arial;
    font-size: 12px;
    color: #f00;
    text-align: center;
  }
  
  .success
  {
    font-family: arial;
    font-size: 12px;
    color: green;
    text-align: center;
  }
  
  body
  {
    background-color: #D3D3D3
  }
  
</style>
</head>

<body>
  <div class="jumbotron text-center bg-light ">
  <form method= "post">
    
    <table>
      <tr>
        <td><label for="username">Username</label></td>
        <td><input type="text" id="username" name="username" placeholder="Enter your username.." maxlength="20" /></td>
      </tr>
    
      <tr>
        <td><label for="email">Email</label></td>
        <td><input type="email" id="email" name="email" placeholder="Enter your mail.." maxlength="32" /></td>
      </tr>
    
      <tr>
        <td><label for="subject">Subject</label></td>
        <td><input type="text" id="subject" name="subject" placeholder="Enter your subject.." maxlength="32" /></td>
      </tr>
    
      <tr>
        <td><label for="message">Message</label></td>
        <td><textarea id="message" name="message" placeholder="Enter your message.."/></textarea></td>
      </tr>
      
      <tr>
        <td><input type="submit" name="submit" value="Send Message" /></td>
      </tr>
    
    
    </table>
    </div>
  </form>
  
<?php
  
  if(isset($_POST['submit']))
  {
   $username = $_POST['username'];
   $email = $_POST['email'];
   $subject = $_POST['subject'];
   $message = $_POST['message'];
   $date = date("M/d/Y");
   $time = time();
   $open = 0;
   
   if(!$username == '' && !$email == '' && !$subject == '' && !$message == '')
   {
     $query = mysqli_query($baza, "INSERT INTO messages VALUES('', '$username', '$email', '$subject', '$message', '$date', '$time', '$open')");
     
     if($query)
     {
        echo '<p class = "success">Your message has been sent.</p>';
     }
     else
     {
        echo '<p class = "required"> Something went wrong, please try again!</p>';
     }
   }
   else
   {
    echo '<p class = "required"> All fields are required!</p>';
   }
   
  }
  
  
  ?>
  
  
</body>
</html>