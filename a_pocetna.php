<?php
session_start();
/*if(!isset($_SESSION["korisnik"])){
  header("Location:login.php");
}*/
$naslov = "Korisnici sustava";
require_once("header.php");

  require_once("a_navigacija.php");


echo '<script>
  document.getElementById("pocetna").classList.add("active");
  
  
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
  </style>



';


echo '


<div class="jumbotron text-center">
  <h1>Love2Travel <span class="glyphicon glyphicon-plane logo-small"></span></h1> 
  <p>Web aplikacija namijenjena osobama koje vole putovati i podijeliti svoja iskustva sa drugima.</p> 
</div>

<div class="container-fluid">
  <div class="row">
    <div class="col-sm-4">
      <span class="glyphicon glyphicon-globe logo"></span>
    </div>
    <div class="col-sm-8">
      <h2>Ciljevi aplikacije</h2>
      </br>
      <h4><strong>MISIJA:</strong> Omogućiti velikoj količini avanturista da se poveže na jednom mjestu !</h4>      
    </div>
  </div>
</div>



<div class="container-fluid text-center bg-grey">
  <h2>Popularna odredišta</h2><br>
  <h4>Da li ste ih već posjetili?</h4>
  <div class="row text-center">
    <div class="col-sm-4">
      <div class="thumbnail">
        <img src="london.jpg" alt="London" width="400" height="300">
        <p><strong>London</strong></p>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="thumbnail">
        <img src="newyork.jpg" alt="New York" width="400" height="300">
        <p><strong>New York</strong></p>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="thumbnail">
        <img src="rome.jpg" alt="Rim" width="400" height="300">
        <p><strong>Rim</strong></p>
      </div>
    </div>
  </div>

  <div id="myCarousel" class="carousel slide text-center" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
      <li data-target="#myCarousel" data-slide-to="1"></li>
      <li data-target="#myCarousel" data-slide-to="2"></li>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
      <div class="item active">
        <h4>"The journey not the arrival matters."<br><span>T.S. Elliot</span></h4>
      </div>
      <div class="item">
        <h4>"The most beautiful in the world is, of course, the world itself."<br><span>Wallace Stevens</span></h4>
      </div>
      <div class="item">
        <h4>"A ship in a harbor is safe, but it is not what ships are built for."<br><span>John A. Shedd</span></h4>
      </div>
    </div>

    <!-- Left and right controls -->
    <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
</div>    
    </div>    
  </div>
</div>


';

     
     require_once("footer.php");
  ?>
