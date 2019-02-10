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
$naslov = "Novi korisnik";
require_once("header.php");
require_once("a_navigacija.php");


echo '<script>
  document.getElementById("korisnici").classList.add("active");
  document.getElementById("novikorisnik").classList.add("active");
</script>';

     require_once("PFBC/Form.php");
     require_once("baza.php");
     $query = $baza->prepare("SELECT * FROM korisnici WHERE id = ? LIMIT 1");
     $query->bind_param("d",$id);
     if(isset($_POST['provjera']) && Form::isValid("uredisliku", false)){             
                
                if($query->execute()){
                    $procitano = $query->get_result();   
                    $redak = $procitano->fetch_assoc();
                        echo "<div><h2>Izmijeni sliku korisnika</h2>
                        <img src='avatari/".$redak['avatar']."/>
                        </div>";
                        //rad sa slikom
                        $dozvoljeni_mime = array("image/gif","image/jpeg","image/png");
                        $upload_greske = array(
                          UPLOAD_ERR_OK =>"Datoteka je uspješno prenesena na server",                   
                          UPLOAD_ERR_INI_SIZE=>"Prevelika datoteka",                          
                          UPLOAD_ERR_FORM_SIZE=>"Prevelika datoteka",                  
                          UPLOAD_ERR_PARTIAL=>"Datoteka nije u potpunosti prenesena na server",                  
                          UPLOAD_ERR_NO_FILE=>"Nije predana datoteka",                  
                          UPLOAD_ERR_NO_TMP_DIR=>"Greška na serveru",                
                          UPLOAD_ERR_CANT_WRITE=>"Greška na serveru",                  
                          UPLOAD_ERR_EXTENSION=>"Greška na serveru"
                        );
                        if(!empty($_FILES['slika']['type']) && !in_array($_FILES['slika']['type'],$dozvoljeni_mime)){
                          Form::setError("uredisliku","Niste odabrali ispravan tip datoteke (koristite samo gif, png i jpeg)");
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
                                       echo ('<div class="alert alert-success" role="alert">Slika je promijenjena!</div>');
                                  }
                                  else{
                                      Form::setError("uredisliku","Slika nije promijenjena");
                                  }
                              }
                            }
                            else{
                              Form::setError("uredisliku","Greška sa premještanjem datoteke");
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
     echo "<legend>Promijeni sliku</legend>";
     Form::File("Avatar: ","slika", array("required"=>1));     
     Form::Button("Spremi");
     Form::Button("Odustani","button",array("onclick"=>"location.href='a_svi_korisnici.php'"));
     Form::close(false);
     
     require_once("footer.php");
  ?>
