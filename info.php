<?php
session_start();
if(!isset($_SESSION["korisnik"])){
  header("Location:login.php");
}
$naslov = "About";
require_once("header.php");
require_once("a_navigacija.php");

echo '<script>
  document.getElementById("info").classList.add("active");
</script>';

echo '  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>';

echo '

<style>
  .jumbotron {
    background-color: #D8D8D8;
    color: #000;
    padding: 100px 25px;
  }
  .container-fluid {
    padding: 60px 50px;
  }
  .bg-grey {
    background-color: #f6f6f6;
  }
  .logo-small {
    color: #1e90ff;
    font-size: 50px;
  }
  .logo {
    color: #1e90ff;
    font-size: 200px;
  }
  .thumbnail {
    padding: 0 0 15px 0;
    border: none;
    border-radius: 0;
  }
  .thumbnail img {
    width: 100%;
    height: 100%;
    margin-bottom: 10px;
  }
  .carousel-control.right, .carousel-control.left {
    background-image: none;
    color: #1e90ff;
  }
  .carousel-indicators li {
    border-color: #1e90ff;
  }
  .carousel-indicators li.active {
    background-color: #1e90ff;
  }
  .item h4 {
    font-size: 19px;
    line-height: 1.375em;
    font-weight: 400;
    font-style: italic;
    margin: 70px 0;
  }
  .item span {
    font-style: normal;
  }
  .panel {
    border: 1px solid #1e90ff; 
    border-radius:0 !important;
    transition: box-shadow 0.5s;
  }
  .panel:hover {
    box-shadow: 5px 0px 40px rgba(0,0,0, .2);
  }
  .panel-footer .btn:hover {
    border: 1px solid #1e90ff;
    background-color: #fff !important;
    color: #f4511e;
  }
  .panel-heading {
    color: #fff !important;
    background-color: #1e90ff !important;
    padding: 25px;
    border-bottom: 1px solid transparent;
    border-top-left-radius: 0px;
    border-top-right-radius: 0px;
    border-bottom-left-radius: 0px;
    border-bottom-right-radius: 0px;
  }
  .panel-footer {
    background-color: white !important;
  }
  .panel-footer h3 {
    font-size: 32px;
  }
  .panel-footer h4 {
    color: #aaa;
    font-size: 14px;
  }
  .panel-footer .btn {
    margin: 15px 0;
    background-color: #1e90ff;
    color: #fff;
  }
  @media screen and (max-width: 768px) {
    .col-sm-4 {
      text-align: center;
      margin: 25px 0;
    }
  }
  body
  {
    background-color: #D3D3D3
  }
  </style>



';



echo '<div class="jumbotron text-center bg-light ">
  <img src="avatar.png" class="img-circle" alt="Ja" width="250" height="250">
  <h3>Lion Fred Entertainment</h3>
</div>

<div class="container-fluid bg-3 text-center">
  <h3>Contact</h3>
  <p>e-mail: <b>lionfredentertainment@gmail.com</b></p>
  <p>phone: <b>090 0000 000</b></p>';
     require_once("footer.php");
  ?>

