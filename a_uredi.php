<?php
session_start();
/*if(!isset($_SESSION["korisnik"])){
  header("Location:login.php");
}
if(!isset($_POST["id"])){
  header("location:a_svi_korisnici.php");
}*/
$naslov = "Uredi korisnika";
require_once("header.php");
require_once("a_navigacija.php");

echo '<script>
  document.getElementById("korisnici").classList.add("active");  
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

     require_once("PFBC/Form.php");
     require_once("baza.php");
     
     if(isset($_POST["provjera"])){
      $id=$_POST["id"];
      $korisnik = htmlentities(trim($_POST["korisnik"]));
      if(Form::isValid("uredikorisnik", false)){
        $izjava = $baza->prepare("SELECT * from korisnici WHERE korisnik=? AND id!=? LIMIT 1");
        $izjava->bind_param('sd',$korisnik, $id);
        if($izjava->execute()){
          $rezultat = $izjava->get_result();
          if(count($rezultat->fetch_assoc())){
            Form::setError("uredikorisnik","Username already exist!");
          }
          else{
            if($_POST["lozinka"]=="" && $_POST["lozinka2"]==""){
              $izjava = $baza->prepare("UPDATE korisnici SET korisnik=? WHERE id=?");
              $izjava->bind_param('sd', $korisnik, $id);
              if($izjava->execute()){
                echo '<div class="alert alert-success" role="alert">
                      Username changed!
                      </div>';
              }
              else{
                Form::setError("uredikorisnik","Username not changed");
              }
            }
            else if($_POST["lozinka"]==$_POST["lozinka2"]){
              $izjava = $baza->prepare("UPDATE korisnici SET korisnik=?, lozinka=? WHERE id=?");
              $lozinka = password_hash(htmlentities(trim($_POST["lozinka"])),PASSWORD_DEFAULT);
              $izjava->bind_param('ssd',$korisnik, $lozinka, $id);
              if($izjava->execute()){
                echo '<div class="alert alert-success" role="alert">
                      Username and password changed!
                      </div>';
              }
              else{
                Form::setError("uredikorisnik","Username and password not changed.");
              }
            }
            else{
              Form::setError("uredikorisnik","Passwords don't match.");
            }
          }
        }
        else{
          Form::setError("uredikorisnik","Error!");
        }
      }
     }
     else{
      $id = $_POST["id"];      
      $izjava = $baza->prepare("SELECT * FROM korisnici WHERE id=? LIMIT 1");
      $izjava->bind_param('d',$id);
      if($izjava->execute()){
        $rezultat = $izjava->get_result();
        $redak = $rezultat->fetch_assoc();
        $korisnik = $redak["korisnik"];
        Form::clearErrors("uredikorisnik");
        Form::clearValues("uredikorisnik");
      }
     }
     
     Form::open("uredikorisnik","",["view"=>"SideBySide4"]);
     Form::Hidden("provjera","da");
     Form::Hidden("id",$id);
     echo "<h1 align=center>Edit user</h1><br>";
     Form::TextBox("Username: ","korisnik",array("required"=>1,
                                                 "validation"=> new Validation_RegExp("/^[a-z0-9_\-.]{5,50}$/", "%element% sadrži od 5 do 50 znakova. Dozvoljeni znakovi: slova, brojke, _, - i ."),
                                                 "value"=>$korisnik));
     Form::Password("Password: ","lozinka",array("validation"=> new Validation_RegExp("/^[a-zA-Z0-9_\-.]{6,50}$/", "%element% sadrži od 5 do 50 znakova. Dozvoljeni znakovi: slova, brojke, _, - i .")));
     Form::Password("Repeat password: ","lozinka2");
     Form::Button("Save");
     Form::Button("Cancel","button", array("onclick"=>"location.href='a_svi_korisnici.php'"));
     Form::close(false);
     
     require_once("footer.php");
  ?>
