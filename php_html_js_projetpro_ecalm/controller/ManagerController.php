<?php

/**
 * Classe ManagerController
 *
 * Cette classe contient les contrôleurs pour la partie gestionnaire (ajout et suppression des données).
 *
 * PHP version 5.6
 *
 * @author Célia Martin <celia.ma@free.fr>
 */

require_once('model/AddDelData/DeleteData.php');
require_once('model/AddDelData/InsertData.php');


class ManagerController {
    /**
     * Fonction delete($corpus, $level)
     *
     * Contrôleur pour la suppression des données. Supprime les données correspondant au niveau et au corpus entrés par l'utilisateur et redirige l'utilisateur vers la page d'accueil en générant une alerte montrant le nombre de lignes affectées par l'opération.
     *
     * @param string $corpus Corpus à supprimer
     * @param string $level Niveau à supprimer
     * @return void
     * @throws Exception
     */
    public function delete($corpus, $level){
        $delete = new DeleteData($corpus, $level);
        $nbLineAffected =$delete->deleteData();
        echo "<script>alert(\"Nombre de lignes affectées par la suppression : ".$nbLineAffected."\")</script>";
        require('view/home.php');
    }

    /**
     * Fonction add($file)
     *
     * Contrôleur pour l'ajout de données à partir d'un fichier csv. Il prend en entrée un fichier csv, ajoute ses lignes et redirige l'utilisateur vers la page d'accueil en générant une alerte montrant le nombre de lignes affectées par l'opération.
     *
     * @param string $file Fichier csv à ajouter
     * @return void
     * @throws Exception
     */
    public function add($file){
        $delete = new InsertData();
        $nbLineAffected =$delete->addCSV($file);
        echo "<script>alert(\"Nombre de lignes affectées par l'ajout: ".$nbLineAffected."\")</script>";
        require('view/home.php');
    }
}