<?php
echo '
<nav class="navbar navbar-expand-sm bg-primary navbar-dark">
  <!-- Brand -->
  <a class="navbar-brand" href="#"><b>Love2Travel</b></a>

  <!-- Links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a id="pocetna" class="nav-link" href="a_pocetna.php">Početna</a>
    </li>

    <!-- Dropdown -->
    <li class="nav-item dropdown">
      <a id="korisnici" class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
        Korisnici
      </a>
      <div class="dropdown-menu">
        <a class="dropdown-item" id="novikorisnik" href="a_novikorisnik.php">Novi korisnik</a>
        <a class="dropdown-item" id="svikorisnici" href="a_svi_korisnici.php">Svi korisnici</a>
        <a class="dropdown-item" href="#">Link 3</a>
      </div>
    </li>
        <li class="nav-item">
      <a class="nav-link" id="info" href="info.php">O nama</a>
    </li>
    <li class="nav-item">
      <a class="nav-link"  href="logout.php">Odjava</a>
    </li>
  </ul>
</nav>';
?>