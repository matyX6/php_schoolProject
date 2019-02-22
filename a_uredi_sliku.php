<?php
session_start();
if(!isset($_SESSION["korisnik"])){
  header("Location:login.php");
}
if(!isset($_POST['id'])){
  header("Location:a_svi_korisnici.php");
}
else{
  $id = $_POST['id'];
}
$naslov = "Edit image";
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
     require_once("baza.php");
     $query = $baza->prepare("SELECT * FROM korisnici WHERE id = ? LIMIT 1");
     $query->bind_param("d",$id);
     if(isset($_POST['provjera']) && Form::isValid("uredisliku", false)){             
                
                if($query->execute()){
                    $procitano = $query->get_result();   
                    $redak = $procitano->fetch_assoc();
                        echo "<div><h2>Change profile picture</h2>
                        <img src='avatari/".$redak['avatar']."/>
                        </div>";
                        //rad sa slikom
                        $dozvoljeni_mime = array("image/gif","image/jpeg","image/png");
                        $upload_greske = array(
                          UPLOAD_ERR_OK =>"Success.",                   
                          UPLOAD_ERR_INI_SIZE=>"File to big.",                          
                          UPLOAD_ERR_FORM_SIZE=>"File to big.",                  
                          UPLOAD_ERR_PARTIAL=>"Something went wrong.",                  
                          UPLOAD_ERR_NO_FILE=>"File not uploaded.",                  
                          UPLOAD_ERR_NO_TMP_DIR=>"Something went wrong.",                
                          UPLOAD_ERR_CANT_WRITE=>"Something went wrong.",                  
                          UPLOAD_ERR_EXTENSION=>"Something went wrong."
                        );
                        if(!empty($_FILES['slika']['type']) && !in_array($_FILES['slika']['type'],$dozvoljeni_mime)){
                          Form::setError("uredisliku","Wrong file type (use gif, png or jpeg)");
                        }else{
                          $greska = $_FILES['slika']['error'];
                          if($greska>0){
                            Form::setError("uredisliku",$upload_greske[$greska]);
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
                            $spremanje = $naziv.$ekstenzija;
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
                                Form::setError("uredisliku",$greska);
                                unlink($putanja);
                              }
                              else{
                                //upis u bazu podataka                                  
                                  $query= $baza->prepare("UPDATE korisnici SET avatar=? WHERE id=?");
                                  $query->bind_param("sd",$spremanje, $id);
                                  if($query->execute()){
                                      //uspješno upisano
                                      Form::clearValues("uredisliku");
                                       echo ('<div class="alert alert-success" role="alert">Image changed</div>');
                                  }
                                  else{
                                      Form::setError("uredisliku","Image not changed");
                                  }
                              }
                            }
                            else{
                              Form::setError("uredisliku","Something went wrong. Try again");
                            }
                          }
                        }
                        
                        
                    
                    
                }
                else{
                    Form::setError("novikorisnik","Nije moguće pročitati bazu");
                }            
        
        
     }
     if(!isset($_POST["provjera"])) Form::clearErrors("novikorisnik");
     

     Form::open("uredisliku","",["view"=>"SideBySide4",'enctype'=> 'multipart/form-data']);
     Form::Hidden("provjera","da");
     Form::Hidden("id",$id);
     echo "<h1 align=center>Change profile picture</h1><br>";
     Form::File("Profile picture: ","slika", array("required"=>1));     
     Form::Button("Save");
     Form::Button("Cancel","button",array("onclick"=>"location.href='a_svi_korisnici.php'"));
     Form::close(false);
     
     require_once("footer.php");
  ?>
