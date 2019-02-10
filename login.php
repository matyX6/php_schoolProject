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
     echo "<legend>Prijavi se</legend>";
     Form::TextBox("Korisnik: ","korisnik",array("required"=>1,
                                                 "validation"=> new Validation_RegExp("/^[a-z0-9_\-.]{5,50}$/", "%element% sadrži od 5 do 50 znakova. Dozvoljeni znakovi: slova, brojke, _, - i .")));
     Form::Password("Lozinka: ","lozinka",array("required"=>1,
                                                "validation"=> new Validation_RegExp("/^[a-zA-Z0-9_\-.]{6,50}$/", "%element% sadrži od 5 do 50 znakova. Dozvoljeni znakovi: slova, brojke, _, - i .")));     
     Form::Button("Prijavi se");
     Form::close(false);
     
     require_once("footer.php");
  ?>
