<?php

$naslov = "Korisnici sustava";
require_once("header.php");
require_once("navigacija.php");

echo '<script>
  document.getElementById("info").classList.add("active");
</script>';

echo '  <style>
  .bg-1 { 
    background-color: #1abc9c;
    color: #ffffff;
  }
  .bg-2 { 
    background-color: #474e5d;
    color: #ffffff;
  }
  .bg-3 { 
    background-color: #ffffff;
    color: #555555;
  }
  .container-fluid {
    padding-top: 70px;
    padding-bottom: 70px;
  }
  </style>';


echo '<div class="container-fluid bg-1 text-center">
  <h3>Tko sam ja?</h3>
  <img src="avatar.png" class="img-circle" alt="Ja" width="250" height="250">
  <h3>Avanturist i programer u jednoj osobi !</h3>
</div>

<div class="container-fluid bg-2 text-center">
  <h3>Čime se bavim?</h3>
  <p>Student završne godine računarstva Međimurskog Veleučilišta u Čakovcu koji voli putovati kad god nađe slobodnog vremena.</p>
</div>

<div class="container-fluid bg-3 text-center">
  <h3>Kontakt</h3>
  <p>E-mail: <b>marko.hrncic@student.mev.hr</b></p>
  <p>Mobitel: <b>099-570-6105</b></p>';
     require_once("footer.php");
  ?>
