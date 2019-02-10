<?php
session_start();
   if(!isset($_SESSION['korisnik'])){
    header('Location:login.php');
   }
   else if(!isset($_GET['id'])){
    header('Location:a_svi_korisnici.php');
   }
   else{
    $id = $_GET['id'];
    require_once("baza.php");
    $izjava = $baza->prepare("DELETE FROM korisnici WHERE id=?");
    $izjava->bind_param('d',$id);
    if($izjava->execute()){
        header('Location: a_svi_korisnici.php?obrisano=da');
    }
    else{
        header('Location: a_svi_korisnici.php?obrisano=ne');
    }
   }
?>
    