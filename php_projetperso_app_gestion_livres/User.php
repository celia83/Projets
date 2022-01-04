<?php

require_once "DataBase.php";

/**
 * Class User
 *
 * Cette classe contient toutes les fonctions relatives à l'utilisateur.
 *
 * PHP version 7.4
 *
 * @author Célia Martin <celia.ma@free.fr>
 */
class User
{
    protected $email;
    protected $psw;

    /**
     * Constructeur de User.
     *
     * @param string $email Identifiant de l'utilisateur
     * @param string $psw Mot de passe de l'utilisateur
     */
    public function __construct(string $email, string $psw){
        $this->email = $email;
        $this->psw = $psw;
    }

    /**
     * Fonction verifyUser()
     *
     * Cette fonction vérifie que le login entré par l'utilisateur est présent dans la base.
     * S'il est présent elle vérifie le mot de passe.
     * Elle retourne true si la personne est effectivement présente dans la base de données,
     * elle envoie un message d'erreur si ce n'est pas le cas.
     *
     * @return boolean
     * @throws Exception
     */
    public function verify_user(){
        $request ="SELECT `password` FROM `user` WHERE `email`= '".$this->email."'";
        $database = new DataBase();
        $tabpsw= $database->getData($request, "milibrary", "root", "");
        if ($tabpsw==false) {
            return "Email incorrect.";
        } else {
            if ($tabpsw[0]['password'] == $this->psw) {
                $_SESSION['email'] = $this->email;
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
    public function destroy_session(){
        session_destroy();
    }

    /**
     * Fonction get_users_infos.
     *
     * Retourne la liste des utilisateurs (sans leur mot de passe).
     *
     * @return array
     * @throws Exception
     */
    public function get_users_infos(): array
    {
        $db = new DataBase();
        $request = 'SELECT `id`, `username`, `firstname`, `lastname`, `email`, `avatar`, `description` FROM `user` WHERE `email` NOT IN (SELECT `email` FROM `user` WHERE `email` = "'. $this->email.'")';
        return $db->getData($request, "milibrary", "root", "");
    }


}