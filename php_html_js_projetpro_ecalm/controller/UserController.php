<?php

/**
 * Classe UserController
 *
 * Cette classe contient les contrôleurs nécessaire à tout ce qui concerne l'utilisateur (connection, déconnection etc.)
 *
 * PHP version 5.6
 *
 * @author Célia Martin <celia.ma@free.fr>
 */

require("model/User.php");

class UserController {
    /**
     * Fonction connectionPage()
     *
     * Ce contrôleur dirige l'utilisateur vers la page de connexion
     *
     * @return void
     */
    public function connectionPage(){
        header('Location:view/userConnection.php');
    }

    /**
     * Fonction login($login, $mdp)
     *
     * Ce contrôleur permet de vérifier l'identité de la personne qui se connecte et renvoie la réponse au format JSON pour un traitement avec ajax.
     * @param string $login Identifiant de l'utilisateur
     * @param string $mdp Mot de passe de l'utilisateur
     * @return void
     * @throws Exception
     */
    public function login($login, $mdp){
        $userVerification = new User($login, $mdp);
        $response  = $userVerification->verifyUser();
        echo json_encode($response);
    }

    /**
     * Fonction logout()
     *
     * Ce contrôleur permet de supprimer la session en cours, donc de déconnecter l'utilisateur et le rediriger vers la page d'accueil.
     *
     * @return void
     */
    public function logout(){
        $user = new User("","");
        $user->destroySession();
        header('Location:index.php');
    }
}