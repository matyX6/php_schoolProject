<?php
session_start();
/*if(!isset($_SESSION["korisnik"])){
  header("Location:login.php");
}*/
$naslov = "Novi korisnik";
require_once("header.php");
require_once("a_navigacija.php");

echo '<script>
  document.getElementById("korisnici").classList.add("active");
  document.getElementById("novikorisnik").classList.add("active");
</script>';

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
                        Form::setError("novikorisnik","Korisnik postoji u bazi podataka. Odaberite drugo korisničko ime");
                    }
                    else{
                        //korisnika nema  u bazi
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
                          Form::setError("novikorisnik","Niste odabrali ispravan tip datoteke (koristite samo gif, png i jpeg)");
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
                                       echo ('<div class="alert alert-success" role="alert">Korisnik je dodan u bazu!</div>');
                                  }
                                  else{
                                      Form::setError("novikorisnik","Korisnik nije upisan u bazu");
                                  }
                              }
                            }
                            else{
                              Form::setError("novikorisnik","Greška sa premještanjem datoteke");
                            }
                          }
                        }
                        
                        
                    }
                    
                }
                else{
                    Form::setError("novikorisnik","Nije moguće pročitati bazu");
                }            
        }
        else{
            Form::setError("novikorisnik","Lozinke se ne podudaraju");
        }
     }
     if(!isset($_POST["provjera"])) Form::clearErrors("novikorisnik");
     
     Form::open("novikorisnik","",["view"=>"SideBySide4",'enctype'=> 'multipart/form-data']);
     Form::Hidden("provjera","da");
     echo "<legend>Kreiraj novog korisnika</legend>";
     Form::File("Avatar: ","slika", array("required"=>1));
     Form::TextBox("Korisnik: ","korisnik",array("required"=>1,
                                                 "validation"=> new Validation_RegExp("/^[a-z0-9_\-.]{5,50}$/", "%element% sadrži od 5 do 50 znakova. Dozvoljeni znakovi: slova, brojke, _, - i .")));
     Form::Password("Lozinka: ","lozinka",array("required"=>1,
                                                "validation"=> new Validation_RegExp("/^[a-zA-Z0-9_\-.]{6,50}$/", "%element% sadrži od 5 do 50 znakova. Dozvoljeni znakovi: slova, brojke, _, - i .")));
     Form::Password("Ponovi lozinku: ","lozinka2",array("required"=>1));
     Form::Button("Kreiraj korisnika");
     Form::close(false);
     
     require_once("footer.php");
  ?>
