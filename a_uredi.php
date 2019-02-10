<?php
session_start();
if(!isset($_SESSION["korisnik"])){
  header("Location:login.php");
}
if(!isset($_POST["id"])){
  header("location:a_svi_korisnici.php");
}
$naslov = "Uredi korisnika";
require_once("header.php");
require_once("a_navigacija.php");

echo '<script>
  document.getElementById("korisnici").classList.add("active");  
</script>';

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
            Form::setError("uredikorisnik","Korisničko ime je zauzeto, koristite drugo ime");
          }
          else{
            if($_POST["lozinka"]=="" && $_POST["lozinka2"]==""){
              $izjava = $baza->prepare("UPDATE korisnici SET korisnik=? WHERE id=?");
              $izjava->bind_param('sd', $korisnik, $id);
              if($izjava->execute()){
                echo '<div class="alert alert-success" role="alert">
                      Korisničko ime je izmijenjeno
                      </div>';
              }
              else{
                Form::setError("uredikorisnik","Korisničko ime nije izmijenjeno");
              }
            }
            else if($_POST["lozinka"]==$_POST["lozinka2"]){
              $izjava = $baza->prepare("UPDATE korisnici SET korisnik=?, lozinka=? WHERE id=?");
              $lozinka = password_hash(htmlentities(trim($_POST["lozinka"])),PASSWORD_DEFAULT);
              $izjava->bind_param('ssd',$korisnik, $lozinka, $id);
              if($izjava->execute()){
                echo '<div class="alert alert-success" role="alert">
                      Korisničko ime i lozinka su izmijenjeni
                      </div>';
              }
              else{
                Form::setError("uredikorisnik","Korisničko ime i lozinka nisu izmijenjeni");
              }
            }
            else{
              Form::setError("uredikorisnik","Lozinke se ne podudaraju");
            }
          }
        }
        else{
          Form::setError("uredikorisnik","Nije moguće pročitati bazu podataka");
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
     echo "<legend>Uređivanje postojećeg korisnika</legend>";
     Form::TextBox("Korisnik: ","korisnik",array("required"=>1,
                                                 "validation"=> new Validation_RegExp("/^[a-z0-9_\-.]{5,50}$/", "%element% sadrži od 5 do 50 znakova. Dozvoljeni znakovi: slova, brojke, _, - i ."),
                                                 "value"=>$korisnik));
     Form::Password("Lozinka: ","lozinka",array("validation"=> new Validation_RegExp("/^[a-zA-Z0-9_\-.]{6,50}$/", "%element% sadrži od 5 do 50 znakova. Dozvoljeni znakovi: slova, brojke, _, - i .")));
     Form::Password("Ponovi lozinku: ","lozinka2");
     Form::Button("Izmjeni");
     Form::Button("Odustani","button", array("onclick"=>"location.href='a_svi_korisnici.php'"));
     Form::close(false);
     
     require_once("footer.php");
  ?>
