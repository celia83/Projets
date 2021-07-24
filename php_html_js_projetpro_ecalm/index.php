<?php
/**
 * Routeur : permet d'appeler les contrôleurs nécessaires à chaque action réalisée par l'utilisteur.
 *
 * PHP version 5.6
 *
 * @author Célia Martin <celia.ma@free.fr>
 *
 */

session_start();

require('controller/HomeController.php');
require('controller/UserController.php');
require('controller/ManagerController.php');
require('controller/ExportController.php');

try {
    if (isset($_GET['action'])) {
        #Controleurs pour afficher les résultats de la partie interrogation de la base
        if ($_GET['action'] == 'showResults') {
            if($_POST["pos"] == "Verbe"){
                $controller = new HomeController();
                $controller->showResultsCriteriaVerbs($_POST["corpus"], $_POST["level"], $_POST["pos"], $_POST["errStatus"], $_POST["segmStatus"], $_POST["lemma"], $_POST["tense"], $_POST["person"], $_POST["typeErr"],$_POST["base"], $_POST["ending"]);
            } elseif ($_POST["pos"] == "Adjectif"){
                $controller = new HomeController();
                $controller->showResultsCriteriaAdjectives($_POST["corpus"], $_POST["level"], $_POST["pos"], $_POST["errStatus"], $_POST["segmStatus"], $_POST["lemma"], $_POST["genre"], $_POST["number"], $_POST["errGenre"],$_POST["errNumber"], $_POST["baseAdj"]);
            } else {
                $controller = new HomeController();
                $controller->showResultsCriteria($_POST["corpus"], $_POST["level"], $_POST["pos"], $_POST["errStatus"], $_POST["segmStatus"], $_POST["lemma"]);
            }
        #Controleurs pour afficher les résultats des tableaux de stats
        } elseif ($_GET['action'] == 'showStats'){
            $controller = new HomeController();
            $controller -> showStatsTab($_POST["tabName"], $_POST["verbGroup"], $_POST["tense"]);
        #Controleurs pour supprimer des données quand on est gestionnaire
        } elseif ($_GET['action'] == 'deleteData'){
            $controller = new ManagerController();
            $controller -> delete($_POST["chooseCorpus"], $_POST["chooseLevel"]);
        #Controleurs pour ajouter des données quand on est gestionnaire
        } elseif ($_GET['action'] == 'addData'){
            #Vérifier que l'on a bien un fichier et qu'il est au format csv
            if($_FILES['chooseFile']['error'] == 4){
                throw new Exception("Erreur : vous n'avez sélectionné aucun fichier.");
            } else {
                $fileInfos = pathinfo($_FILES['chooseFile']['name']);
                $extension = $fileInfos['extension'];
                $authorizedExtensions = "csv";
                if ($extension == $authorizedExtensions){
                    $controller = new ManagerController();
                    $controller -> add($_FILES["chooseFile"]['tmp_name']);
                } else {
                    throw new Exception('Erreur : extension non valide (csv uniquement).');
                }
            }
        #Controleur pour afficher la page de connexion
        } elseif ($_GET["action"]== "connectionPage") {
            $controller = new UserController();
            $controller->connectionPage();
        #Contrôleur pour connecter l'utilisateur
        } elseif ($_GET["action"]== "connection") {
            $controller = new UserController();
            $controller->login($_POST["login"], $_POST["psw"]);
        #Controleur pour la déconnexion
        } elseif ($_GET["action"]== "disconnection") {
            $controller = new UserController();
            $controller->logout();
        } elseif ($_GET["action"]== "exportExemplier"){
            $controller = new ExportController();
            $controller->Export($_POST["word"], $_POST["nbLine"]);
        }else {
            throw new Exception('Erreur 404 : page non trouvée.');
        }
    #Contrôleur qui redirige l'utilisateur vers la page d'accueil
    } else {
        $controller = new HomeController();
        $controller->home();
    }

} catch(Exception $e) {
        $errorMessage = $e->getMessage();
        require('view/errorView.php');
        echo "<section id='error'><article id='errorContenu'>".$errorMessage."</article></section>";
    }
