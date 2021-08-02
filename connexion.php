<?php
require_once("ConnexionClass.php");

$login = new Login();

if ($login->isUserLoggedIn() == true) {
    header('Location: /aca');
} else {
    include("connexion_form.php");
}
?>