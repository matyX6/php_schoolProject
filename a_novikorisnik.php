<?php
session_start();
if(!isset($_SESSION["korisnik"])){
  header("Location:login.php");
}
$naslov = "New user";
require_once("header.php");
require_once("a_navigacija.php");

echo '<script>
  document.getElementById("korisnici").classList.add("active");
  document.getElementById("novikorisnik").classList.add("active");
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
  </style>
';


     require_once("PFBC/Form.php");
     
     if(isset($_POST['provjera']) && Form::isValid("novikorisnik", false)){
        $lozinka = trim(htmlentities($_POST["lozinka"]));
        $lozinka2 = trim(htmlentities($_POST["lozinka2"]));
        if($lozinka == $lozinka2){
            require_once("baza.php");
                $korisnik = trim(htmlentities($_POST["korisnik"]));
                //provjera da li se korisnik već nalazi u bazi
                $query = $baza->prepare("SELECT * FROM korisnici WHERE korisnik = ? LIMIT 1");
                $query->bind_param("s",$korisnik);
                if($query->execute()){
                    $procitano = $query->get_result();
                    if(count($procitano->fetch_array())){
                        //korisnik se već nalazi u bazi
                        Form::setError("novikorisnik","Username already exist!");
                    }
                    else{
                        //korisnika nema  u bazi
                        //rad sa slikom
                        $dozvoljeni_mime = array("image/gif","image/jpeg","image/png");
                        $upload_greske = array(
                          UPLOAD_ERR_OK =>"File successfuly uploaded!",                   
                          UPLOAD_ERR_INI_SIZE=>"File is to big to upload",                          
                          UPLOAD_ERR_FORM_SIZE=>"File is to big to upload",                  
                          UPLOAD_ERR_PARTIAL=>"Something went wrong!",                  
                          UPLOAD_ERR_NO_FILE=>"Error, file not uploaded!",                  
                          UPLOAD_ERR_NO_TMP_DIR=>"Error!",                
                          UPLOAD_ERR_CANT_WRITE=>"Error!",                  
                          UPLOAD_ERR_EXTENSION=>"Error!"
                        );
                        if(!empty($_FILES['slika']['type']) && !in_array($_FILES['slika']['type'],$dozvoljeni_mime)){
                          Form::setError("novikorisnik","Wrong file format(use gif, png or jpeg)");
                        }else{
                          $greska = $_FILES['slika']['error'];
                          if($greska>0){
                            Form::setError("novikorisnik",$upload_greske[$greska]);
                          }
                          else{
                            $tempdat = $_FILES['slika']['tmp_name'];
                            $spremanje = basename($_FILES['slika']['name']);
                            $pozPosljednjeTocke = strrpos($spremanje,".");
                            $ekstenzija = substr($spremanje,$pozPosljednjeTocke);
                            $naziv = substr($spremanje,0,$pozPosljednjeTocke);
                            $naziv = str_replace(".","",$naziv);
                            $naziv = str_replace(" ","",$naziv);
                            if(strlen($naziv)>50){
                              $naziv = substr($naziv,0,50);
                            }
                            $spremanje = $naziv . $ekstenzija;
                            $dir = "avatari/";
                            $i=0;
                            while(file_exists($dir.$spremanje)){
                              $spremanje=rtrim($naziv,strval($i-1)).$i.$ekstenzija;
                              $i++;
                            }
                            $putanja = $dir.$spremanje;
                            if(move_uploaded_file($tempdat,$putanja)){
                              require_once("crop.php");
                              $greska = image_resize($putanja,$putanja,200,200,1);
                              if($greska !== true){
                                Form::setError("novikorisnik",$greska);
                                unlink($putanja);
                              }
                              else{
                                //upis u bazu podataka
                                  $lozinka = password_hash($lozinka, PASSWORD_DEFAULT);
                                  $query= $baza->prepare("INSERT INTO korisnici (korisnik, lozinka, avatar) VALUES (?, ?, ?)");
                                  $query->bind_param("sss",$korisnik, $lozinka, $spremanje);
                                  if($query->execute()){
                                      //uspješno upisano
                                      Form::clearValues("novikorisnik");
                                       echo ('<div class="alert alert-success" role="alert">User successfully created!</div>');
                                  }
                                  else{
                                      Form::setError("novikorisnik","User not created, try again!");
                                  }
                              }
                            }
                            else{
                              Form::setError("novikorisnik","Error!");
                            }
                          }
                        }
                        
                        
                    }
                    
                }
                else{
                    Form::setError("novikorisnik","Something went wrong, try again!");
                }            
        }
        else{
            Form::setError("novikorisnik","Passwords don't match");
        }
     }
     if(!isset($_POST["provjera"])) Form::clearErrors("novikorisnik");
     
     Form::open("novikorisnik","",["view"=>"SideBySide4",'enctype'=> 'multipart/form-data']);
     Form::Hidden("provjera","da");
     echo "<h1 align=center> Create a new user account</h1><br>";
     Form::File("Profile picture: ","slika", array("required"=>1));
     Form::TextBox("Username: ","korisnik",array("required"=>1,
                                                 "validation"=> new Validation_RegExp("/^[a-z0-9_\-.]{5,50}$/", "%element% minimum 5 characters (letters, numbers, symbols:_, -, .)")));
     Form::Password("Password: ","lozinka",array("required"=>1,
                                                "validation"=> new Validation_RegExp("/^[a-zA-Z0-9_\-.]{6,50}$/", "%element% minimum 5 characters (letters, numbers, symbols:_, -, .)")));
     Form::Password("Repeat password: ","lozinka2",array("required"=>1));
     Form::Button("Create user");
     Form::close(false);
     
     require_once("footer.php");
  ?>
