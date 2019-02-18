<?php
session_start();
/*if(!isset($_SESSION["korisnik"])){
  header("Location:login.php");
}*/
$naslov = "Korisnici sustava";
require_once("header.php");
require_once("a_navigacija.php");

echo '<script>
  document.getElementById("korisnici").classList.add("active");
  document.getElementById("svikorisnici").classList.add("active");
</script>';

echo "<h2>Svi korisnici sustava</h2>";

require_once("baza.php");

if(isset($_GET['obrisano'])){
  if($_GET['obrisano']=="da"){
    echo '<div class="alert alert-success" role="alert">
  Korinik je uspješno obrisan iz baze
</div>';
  }
  else{
    echo '<div class="alert alert-danger" role="alert">
  Korisnik nije obrisan iz baze podataka
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
  echo "<table>";
  while($redak = $rezultat->fetch_assoc()){
    echo "<tr>
    <td>
    <img width='50' height='50' src='avatari/".$redak['avatar'] ."'/>
    </td>
    
    <td>".$redak["korisnik"]."</td><td><form action='a_uredi.php' method='post'>
    <input type='hidden' name='id' value='".$redak["id"]."'/>
    <input type='submit' value='uredi' class='btn btn-info' />
    </form></td>
    <td>
    
    <form action='a_uredi_sliku.php' method='post'>
    <input type='hidden' name='id' value='".$redak["id"]."'/>
    <input type='submit' value='Uredi sliku' class='btn btn-primary' />
    </form>
    </td>
    
    <td>".
    '<button class="btn btn-danger" id="'.$redak["id"].'" data-toggle="confirmation"
        data-btn-ok-label="DA" data-btn-ok-class="btn-danger"
        data-btn-ok-icon-class="fa fa-check" data-btn-ok-icon-content=""
        data-btn-cancel-label="NE" data-btn-cancel-class="btn-success"
        data-btn-cancel-icon-class="fa fa-ban" data-btn-cancel-icon-content=""
        data-title="Želite li stvarno obrisati korisnika?" data-content="Ova operacija se ne može poništiti"
        data-singleton="true">
        Obriši
        </button>'."
    </td></tr>";
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
