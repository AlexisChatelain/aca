<?php

require_once("ConnexionClass.php");

$login = new Login();

if ($login->isUserLoggedIn() == true) {
    header('Location: /aca');
}
require_once("InscriptionClass.php");

$registration = new Registration();

include("inscription_form.php");
