<?php
$baza = new MySQLi("localhost","root", "", "mojabaza");
            $baza->set_charset("utf-8");
            if($baza->connect_error){                
                echo '<div class="alert alert-danger" role="alert">
                      <strong>Error: </strong> Greška sa spajanjem na bazu podataka.
                       </div>';
                exit();
            }            
?>