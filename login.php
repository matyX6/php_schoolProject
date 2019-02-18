<?php
session_start();
if(isset($_SESSION["korisnik"])){
  header("Location:a_novikorisnik.php");
}
$naslov ="Prijava";
require_once("header.php");
require_once("navigacija.php");
require_once("PFBC/Form.php");
     
echo '<script>
  document.getElementById("login").classList.add("active");
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
    background-color: #d65633
  }
  </style>
';
     
     if(Form::isValid("login", false)){
        $lozinka = trim(htmlentities($_POST["lozinka"]));
        $korisnik=trim(htmlentities($_POST["korisnik"]));
        
            require_once("baza.php");              
                //provjera da li se korisnik nalazi u bazi podataka
                $query = $baza->prepare("SELECT * FROM korisnici WHERE korisnik = ? LIMIT 1");
                $query->bind_param("s",$korisnik);
                if($query->execute()){
                    $procitano = $query->get_result();
                    if($procitano->num_rows==0){
                        //korisnik se ne nalazi u bazi
                        Form::setError("login","Korisnik ne postoji u bazi podataka");
                    }
                    else{
                        //uzeti podatke iz baze i provjeriti lozinku
                        $rezultat = $procitano->fetch_assoc();
                        $lozinkaBaza = $rezultat["lozinka"];
                        if(password_verify($lozinka,$lozinkaBaza)){
                          //ispravni podaci korisnika
                          Form::clearValues("login");
                          $_SESSION["korisnik"] = "admin";
                          header('Location:a_novikorisnik.php');
                        }
                        else{
                          Form::setError("login","Pogrešna lozinka");
                        }           
                       
                    }
                    
                }
                else{
                  Form::setError("login","Nije moguće pročitati bazu");
                }
                         
     }
     if(!isset($_POST["provjera"])) Form::clearErrors("login");
     
     Form::open("login","",["view"=>"SideBySide4"]);
     Form::Hidden("provjera","da");
     echo "<h1 align=center>Log in</h1><br>";
     Form::TextBox("Username: ","korisnik",array("required"=>1,
                                                 "validation"=> new Validation_RegExp("/^[a-z0-9_\-.]{5,50}$/", "%element% sadrži od 5 do 50 znakova. Dozvoljeni znakovi: slova, brojke, _, - i .")));
     Form::Password("Password: ","lozinka",array("required"=>1,
                                                "validation"=> new Validation_RegExp("/^[a-zA-Z0-9_\-.]{6,50}$/", "%element% sadrži od 5 do 50 znakova. Dozvoljeni znakovi: slova, brojke, _, - i .")));     
     Form::Button("Prijavi se");
     Form::close(false);
     
     require_once("footer.php");
  ?>
