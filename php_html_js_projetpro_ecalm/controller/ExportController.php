<?php


include_once "model/Export.php";

// Chargement de la classe
include_once "model/Export.php";


/**
 * Classe ExportController
 *
 * Cette classe contient le contrôleur nécessaire à l'exportation de l'exemplier
 *
 * PHP version 5.6
 *
 * @author Océane Giroud <oceane.giroud@etu.univ-grenoble-alpes.fr>
 */
class ExportController{

	/**
     * Fonction Export($word, $nbLine)
     *
     * Contrôleur pour l'exporation de l'exemplier : Permet de retourner les phrases d'exemple à partir du mot choisi par l'utilisateur au format JSON pour un traitement avec ajax..
     *
     * @param $word
     * @param $nbLine
     * @return void
     * @throws Exception
     */
    public function Export($word,$nbLine){
        $exporter = new Export($word,$nbLine);
		$result = $exporter->sentences();
		echo json_encode($result);
    }
}
