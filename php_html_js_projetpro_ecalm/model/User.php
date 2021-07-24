<?php
//include_once "model/DataBase.php";

include_once "DataBase.php";

/**
 * Classe User
 *
 * Cette classe contient toutes les fonctions relatives à l'utilisateur.
 *
 * PHP version 5.6
 *
 * @author Célia Martin <celia.ma@free.fr>
 */
class User{
    private $login;
    private $psw;

    /**
     * Constructeur de User.
     *
     * @param string $login Identifiant de l'utilisateur
     * @param string $psw Mot de passe de l'utilisateur
     */
    public function __construct($login, $psw){
        $this->login = $login;
        $this->psw = $psw;
    }

    /**
     * Fonction verifyUser()
     *
     * Cette fonction vérifie que le login entré par l'utilisateur est présent dans la base. S'il est présent elle vérifie le mot de passe. Elle retourne true si la personne est effectivement présente dans la base de données, elle envoie un message d'erreur si ce n'est pas le cas.
     *
     * @return boolean
     * @throws Exception
     */
    public function verifyUser(){
        $request ="SELECT `Password` FROM `users` WHERE `Login`= '".$this->login."'";
        $database = new DataBase();
        $tabpsw= $database->getData($request);
        if ($tabpsw==false) {
            return "Identifiant incorrect.";
        } else {
            if ($tabpsw[0]['Password'] == $this->psw) {
                $_SESSION['login'] = $this->login;
                return true;
            } else {
                return "Mot de passe incorrect.";
            }
        }
    }

    /**
     * Fonction destroySession()
     *
     * Cette fonction détruit la session en cours pour permettre la déconnexion de l'utilisateur.
     *
     * @return void
     */
    public function destroySession(){
        session_destroy();
    }
}