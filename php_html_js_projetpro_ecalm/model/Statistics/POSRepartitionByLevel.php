<?php

include_once "model/DataBase.php";

/**
 * Classe POSRepartitionByLevel
 *
 * Cette classe n'a qu'une seule fonction et permet de générer le tableau : Répartition des POS en fonction du niveau.
 *
 * PHP version 5.6
 *
 * @author Célia Martin <celia.ma@free.fr>
 */
class POSRepartitionByLevel {

    /**
     * Fonction createTabPOSRepartitionByLevel().
     *
     * Cette fonction sélectionne les mots qui ont pu être analysés par Teetagger dans la base de données et calcule la répartition des catégories grammaticales en fonction des niveaux. Les résultats sont exprimés en pourcentage et enregistrés dans un tableau.
     *
     * @return array
     * @throws Exception
     */
    public function createTabPOSRepartitionByLevel() {
        #Mettre dans un tableau tous les POS avec le niveau correspondant depuis la base de données
        $request = 'SELECT Niv, Categorie FROM `ecalm`';

        #Récupération des données dans la base de données
        $database = new DataBase();
        $tabPOS= $database->getData($request);

        #Contiendra par niveau pourcentage de POS par niveau et le pourcentage total de POS tous niveaux confondus.
        $nbPOSByLevel = array(
            "CP" => array(
				"TotalPOS" => 0,
                "Nom" => 0,
				"Verbe" => 0,
				"ParticipePasse" => 0,
                "Pronom" => 0,
                "Déterminant" => 0,
                "Préposition" => 0,
                "Conjonction" => 0,
                "Adverbe" => 0,
                "Adjectif" => 0,
                "AutresCategories" => 0,
               
               ),
            "CE1" => array(
                "TotalPOS" => 0,
                "Nom" => 0,
                "Verbe" => 0,
				"ParticipePasse" => 0,
                "Pronom" => 0,
                "Déterminant" => 0,
                "Préposition" => 0,
                "Conjonction" => 0,
                "Adverbe" => 0,
                "Adjectif" => 0,
                "AutresCategories" => 0,

				),
            "CE2" => array(
				"TotalPOS" => 0,
                "Nom" => 0,
                "Verbe" => 0,
				"ParticipePasse" => 0,
                "Pronom" => 0,
                "Déterminant" => 0,
                "Préposition" => 0,
                "Conjonction" => 0,
                "Adverbe" => 0,
                "Adjectif" => 0,
                "AutresCategories" => 0,
                ),
            "CM1" => array(
                "TotalPOS" => 0,
                "Nom" => 0,
                "Verbe" => 0,
				"ParticipePasse" => 0,
                "Pronom" => 0,
                "Déterminant" => 0,
                "Préposition" => 0,
                "Conjonction" => 0,
                "Adverbe" => 0,
                "Adjectif" => 0,
                "AutresCategories" => 0,
                ),
            "CM2" => array(
                 "TotalPOS" => 0,
                 "Nom" => 0,
                "Verbe" => 0,
				"ParticipePasse" => 0,
                "Pronom" => 0,
                "Déterminant" => 0,
                "Préposition" => 0,
                "Conjonction" => 0,
                "Adverbe" => 0,
                "Adjectif" => 0,
                "AutresCategories" => 0,
                ),
            "Total" => array(
				"TotalPOS" => 0,
                "Nom" => 0,
                "Verbe" => 0,
				"ParticipePasse" => 0,
                "Pronom" => 0,
                "Déterminant" => 0,
                "Préposition" => 0,
                "Conjonction" => 0,
                "Adverbe" => 0,
                "Adjectif" => 0,
                "AutresCategories" => 0,
                )
            );

        #Sélectionner un POS avec ses informations
        foreach ($tabPOS as $POS){
            #On est sur un POS
            #On ajoute 1 au niveau correspondant au POS et au total de tous les POS pour tous le sniveaux
            $nbPOSByLevel[$POS["Niv"]]["TotalPOS"] ++;
            $nbPOSByLevel["Total"]["TotalPOS"] ++;

            #On ajoute 1 au POS correspondant
            if (($POS["Categorie"] == "NOM") or ($POS["Categorie"] == "NAM")){
                $nbPOSByLevel[$POS["Niv"]]["Nom"] ++;
                $nbPOSByLevel["Total"]["Nom"] ++;
            } elseif (($POS["Categorie"] == "VER:impf") or ($POS ["Categorie"] == "VER:simp")or ($POS ["Categorie"] == "VER:pres") or ($POS ["Categorie"] == "VER:infi") or ($POS ["Categorie"] == "VER:cond") or ($POS ["Categorie"] == "VER:impe") or ($POS ["Categorie"] == "VER:futu") or ($POS ["Categorie"] == "VER:subi") or ($POS ["Categorie"] == "VER:subp") or ($POS["Categorie"] == "VER:ppre")){
                $nbPOSByLevel[$POS["Niv"]]["Verbe"] ++;
                $nbPOSByLevel["Total"]["Verbe"] ++;
            } elseif ($POS["Categorie"] == "VER:pper") {
				$nbPOSByLevel[$POS["Niv"]]["ParticipePasse"] ++;
                $nbPOSByLevel["Total"]["ParticipePasse"] ++;
            } elseif (($POS["Categorie"] == "PRO:PER") or ($POS ["Categorie"] == "PRO:REL")or ($POS ["Categorie"] == "PRO:IND") or ($POS ["Categorie"] == "PRO/PER")){
                $nbPOSByLevel[$POS["Niv"]]["Pronom"] ++;
                $nbPOSByLevel["Total"]["Pronom"] ++;
            } elseif (($POS["Categorie"] == "DET:ART") or ($POS["Categorie"] == "DET:POS")){
                $nbPOSByLevel[$POS["Niv"]]["Déterminant"] ++;
                $nbPOSByLevel["Total"]["Déterminant"] ++;
            } elseif (($POS["Categorie"]== "PRP") or ($POS["Categorie"] == "PRP:det")){
                $nbPOSByLevel[$POS["Niv"]]["Préposition"] ++;
                $nbPOSByLevel["Total"]["Préposition"] ++;
            } elseif ($POS["Categorie"] == "KON") {
                $nbPOSByLevel[$POS["Niv"]]["Conjonction"] ++;
                $nbPOSByLevel["Total"]["Conjonction"] ++;
            } elseif ($POS["Categorie"] == "ADV"){
                $nbPOSByLevel[$POS["Niv"]]["Adverbe"] ++;
                $nbPOSByLevel["Total"]["Adverbe"] ++;
            } elseif ($POS["Categorie"] == "ADJ"){
                $nbPOSByLevel[$POS["Niv"]]["Adjectif"] ++;
                $nbPOSByLevel["Total"]["Adjectif"] ++;
            } elseif (($POS["Categorie"] !="NOM") and ($POS["Categorie"] != "NAM") and($POS["Categorie"] != "PRO:PER") and ($POS ["Categorie"] != "PRO:REL") and ($POS ["Categorie"] != "PRO:IND") and ($POS ["Categorie"] != "PRO/PER") and ($POS["Categorie"] != "DET:ART") and ($POS["Categorie"] != "PRP:det") or ($POS["Categorie"] != "DET:POS") and ($POS["Categorie"] != "PRP") and ($POS["Categorie"] != "KON") and ($POS["Categorie"] != "ADV") and ($POS["Categorie"] == "ADJ"))  {
                $nbPOSByLevel[$POS["Niv"]]["AutresCategories"] ++;
                $nbPOSByLevel["Total"]["AutresCategories"] ++;
            }
        }

        #Création du tableau final
        $percentagePOSByLevel = array("CP" => array(),"CE1" => array(),"CE2" => array(),"CM1" => array(),"CM2" => array(), "Total" => array());

        #Pour chaque niveau on calcule le pourcentage de chaque POS : nombre de POS par catégorie / nombre total de POS pour le niveaux
        #On calcule également le total : pourcentage de chaque POS pour tous les niveaux confondus
        foreach($nbPOSByLevel as $level => $tab1){
            $sumPOS=0;
            foreach($tab1 as $NivPOS => $nbPOS) {
                if ($NivPOS == "TotalPOS") {
                    $TotalPOS = $nbPOS;
                } elseif($TotalPOS == 0) {
                    $percentagePOSByLevel[$level][$NivPOS] = "0%";
                } else {
                    $percentagePOSByLevel[$level][$NivPOS] =(string) round($nbPOS / $TotalPOS * 100,2) ."%";
                    $sumPOS += $nbPOS;
                }
            }
            if($TotalPOS != 0){
                $percentagePOSByLevel[$level]["Total"] = (string)  round($sumPOS / $TotalPOS * 100 ,2). "%";
            } else {
                $percentagePOSByLevel[$level]["Total"] = "0%";
            }
        }

        return $percentagePOSByLevel;
    }
}
