<?php
echo '
<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
  <!-- Brand -->
  <a class="navbar-brand" href="#"><b>lionfred</b></a>

  <!-- Links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a id="pocetna" class="nav-link" href="a_pocetna.php">Games</a>
    </li>
        <li class="nav-item">
      <a class="nav-link" id="info" href="info.php">About</a>
    </li>
	
	    <!-- Dropdown -->
    <li class="nav-item dropdown">
      <a id="korisnici" class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
        Users
      </a>
      <div class="dropdown-menu">
        <a class="dropdown-item" id="novikorisnik" href="a_novikorisnik.php">+ New user</a>
        <a class="dropdown-item" id="svikorisnici" href="a_svi_korisnici.php">User list</a>
      </div>
    </li>
	
    <li class="nav-item">
      <a class="nav-link"  href="logout.php"><b>Log out</b></a>
    </li>
  </ul>
</nav>';
?>