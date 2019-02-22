<?php
session_start();
if(!isset($_SESSION["korisnik"])){
  header("Location:login.php");
}
$naslov = "Users";
require_once("header.php");
require_once("a_navigacija.php");

echo '<script>
  document.getElementById("korisnici").classList.add("active");
  document.getElementById("svikorisnici").classList.add("active");
</script>';

echo '  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>';

echo '

<style>
  h1
  {
    align="center"
  }
  
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
    color: #d65633;
  }
  .carousel-indicators li {
    border-color: #d65633;
  }
  .carousel-indicators li.active {
    background-color: #d65633;
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
  
  .centar
  {
    margin: auto;
  width: 50%;
  border: 3px solid green;
  padding: 10px;
  }
  
  td
  {
    padding:0 2px 0 2px;
  }
  
  </style>
';

echo "<br><h2 align=center>List of all users</h2>";

require_once("baza.php");

if(isset($_GET['obrisano'])){
  if($_GET['obrisano']=="da"){
    echo '<div class="alert alert-success" role="alert">
  User account successfully deleted!
</div>';
  }
  else{
    echo '<div class="alert alert-danger" role="alert">
  Something went wrong, user account is not successfully deleted!
</div>';
  }
  
}

$stranica = (empty($_GET["stranica"])?1:(int) $_GET['stranica']);

$brojPoStranici = 2;
$izjava = $baza->prepare("SELECT COUNT(*) FROM korisnici");
if($izjava->execute()){
  $rezultat=$izjava->get_result();
  $redak = $rezultat->fetch_array();
  $ukupnoKorisnika = $redak[0];
}
$brojStranica = ceil($ukupnoKorisnika/$brojPoStranici);
if($stranica<1){
  $stranica=1;
}
else if($stranica>$brojStranica){
  $stranica=$brojStranica;
}
$odmak = $brojPoStranici*($stranica-1);

$izjava = $baza->prepare("SELECT * FROM korisnici ORDER BY id ASC LIMIT $brojPoStranici OFFSET $odmak");
if($izjava->execute()){
  $rezultat = $izjava->get_result();
  echo "<br><table align=center>";
  while($redak = $rezultat->fetch_assoc()){
    echo "
    <tr>
    
    <td>
    <img width='50' height='50' src='avatari/".$redak['avatar'] ."'/>
    </td>
    
    <td>".$redak["korisnik"]."</td><td><form action='a_uredi.php' method='post'>
    <input type='hidden' name='id' value='".$redak["id"]."'/>
    <input type='submit' value='Edit user' class='btn btn-info' />
    </form></td>
    
        <td>
    <form action='a_uredi_sliku.php' method='post'>
    <input type='hidden' name='id' value='".$redak["id"]."'/>
    <input type='submit' value='New image' class='btn btn-primary' />
    </form>
    </td>
    
    
    <td>".
    '<button class="btn btn-danger" id="'.$redak["id"].'" data-toggle="confirmation"
        data-btn-ok-label="YES" data-btn-ok-class="btn-danger"
        data-btn-ok-icon-class="fa fa-check" data-btn-ok-icon-content=""
        data-btn-cancel-label="NO" data-btn-cancel-class="btn-success"
        data-btn-cancel-icon-class="fa fa-ban" data-btn-cancel-icon-content=""
        data-title="Are you sure?" data-content=" This operation can not be reversed."
        data-singleton="true">
        Delete user
        </button>'."
    </td></tr>
    ";
  }
  echo "</table>";
  echo "<script>$('[data-toggle=confirmation]').confirmation({
  rootSelector: '[data-toggle=confirmation]',
  onConfirm: function(){
    location.href='a_obrisi.php?id='+$(this).attr('id');
  }
  });</script>";
  
  if($brojStranica>1){
    echo "<div style='clear:left'>";
    echo '<ul class="pagination" style="margin:20px 0">';
    if($stranica>1){
      echo "<li class='page-item'><a class='page-link' href='a_svi_korisnici.php?stranica=".($stranica-1)."'>&laquo Prethodna</a></li>";
    }
    for($x=1;$x<=$brojStranica;$x++){
      if($x==$stranica){
        echo "<li class='page-item active'><a class='page-link' href='a_svi_korisnici.php?stranica=".$x."'>".$x."</a></li>";
      }
      else{
        echo "<li class='page-item'><a class='page-link' href='a_svi_korisnici.php?stranica=".$x."'>".$x."</a></li>";
      }
    }
    if($stranica<$brojStranica){
      echo "<li class='page-item'><a class='page-link' href='a_svi_korisnici.php?stranica=".($stranica+1)."'>&raquo Slijedeća</a></li>";
    }
    
    echo '</ul>';
    echo "</div>";
  }
  
  $baza->close();
}
else{
  echo '<div class="alert alert-danger" role="alert">
  Nije bilo moguće pročitati bazu podataka
</div>';
}
     
     require_once("footer.php");
  ?>
