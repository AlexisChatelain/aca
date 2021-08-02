<?php

class Login
{
    public $messages = array();

    public function __construct()
    {
		if(!isset($_SESSION)) { 
			session_start();
		}
        if (isset($_GET["logout"])) {
            $this->doLogout();
        }
        elseif (isset($_POST["login"])) {
            $this->dologinWithPostData();
        }
    }

    private function dologinWithPostData()
    {
        if (empty($_POST['mail'])) {
            $this->messages[] = "Adresse e-mail incorrecte.";
        } elseif (empty($_POST['mdp'])) {
            $this->messages[] = "Mot de passe invalide.";
        } else {
		
			require('config/database.php');

			if (!$db->connect_errno) {

                $email = $db->real_escape_string($_POST['mail']);
                $checklogin = $db->query("SELECT id_utilisateur, nom, prenom, mail, mdp, admin, date_inscription FROM utilisateur WHERE mail = '" . $email . "';");

                if ($checklogin->num_rows == 1) {
                    $result_row = $checklogin->fetch_object();

                    if (password_verify($_POST['mdp'], $result_row->mdp)) {
					    $_SESSION['projet'] = "aca";
                        $_SESSION['nom'] = $result_row->nom;
                        $_SESSION['prenom'] = $result_row->prenom;
						$_SESSION['id'] = $result_row->id_utilisateur;
						$_SESSION['user']= $result_row->prenom." ".$result_row->nom;
                        $_SESSION['email'] = $result_row->mail;
						$_SESSION['admin'] = $result_row->admin;
                        $_SESSION['date_inscription'] = $result_row->date_inscription;
                        $_SESSION['logged_in'] = 1;
						
						$query_update_date = $db->query("UPDATE utilisateur SET derniere_connexion=now() WHERE mail='".$_SESSION['email']."';");
						$db->close();

                        $this->logged_in = true;
                    } else {

                        $this->messages[] = "Mot de passe incorrect.";
                    }

				} else {
					$this->messages[] = "Adresse e-mail invalide.";
				}

            } else {
                $this->messages[] = "Erreur interne, veuillez réessayer plus tard.";
            }

		}
    }

    public function doLogout()
    {
        $_SESSION = array();
        session_destroy();
        $this->messages[] = "Vous avez été déconnecté.";
    }

    public function isUserLoggedIn()
    {
        if (isset($_SESSION['logged_in']) AND $_SESSION['logged_in'] == 1 AND $_SESSION['projet'] == "aca") {
            return true;
        }
        return false;
    }
	
	public function isAdmin(){
		if (isset($_SESSION['admin']) AND $_SESSION['admin']) {
            return true;
        }
        return false;
	}
}