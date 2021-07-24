<?php

include_once "model/DataBase.php";

/**
 * Classe TenseRepartition
 *
 * Cette classe n'a qu'une seule fonction et permet de générer le tableau : Répartition des tiroirs verbaux.
 *
 * PHP version 5.6
 *
 * @author Célia Martin <celia.ma@free.fr>
 */
class TenseRepartition {
    /**
     * Fonction createTabTenseRepartition()
     *
     * Cette fonction retourne un tableau montrant la répartition des tirois verbaux présents dans la base de données en fonction des niveaux.
     *
     * @return array
     * @throws Exception
     */
    public function createTabTenseRepartition() {
        #Mettre dans un tableau tous les verbes depuis la base de données
        $request = 'SELECT Niv, Categorie, SegNorm FROM `ecalm` WHERE Categorie LIKE "VER%" AND Categorie LIKE "VER:pper" = 0';

        #Récupération des données dans la base de données
        $database = new DataBase();
        $tabVerbs= $database->getData($request);

        #Contiendra par niveau le nombre total de verbes et le nombre de verbe appartenant à un tiroir vebral
        $nbVerbsByLevel = array(
            "CP" => array(
                "totalVerbs" => 0,
                "Conditionnel" => 0,
                "Futur" => 0,
                "Impératif" => 0,
                "Imparfait" => 0,
                "Infinitif" => 0,
                "ParticipePrésent" => 0,
                "Présent" => 0,
                "PasséSimple" => 0,
                "SubjImparfait" => 0,
                "SubjPrésent" => 0),
            "CE1" => array(
                "totalVerbs" => 0,
                "Conditionnel" => 0,
                "Futur" => 0,
                "Impératif" => 0,
                "Imparfait" => 0,
                "Infinitif" => 0,
                "ParticipePrésent" => 0,
                "Présent" => 0,
                "PasséSimple" => 0,
                "SubjImparfait" => 0,
                "SubjPrésent" => 0),
            "CE2" => array(
                "totalVerbs" => 0,
                "Conditionnel" => 0,
                "Futur" => 0,
                "Impératif" => 0,
                "Imparfait" => 0,
                "Infinitif" => 0,
                "ParticipePrésent" => 0,
                "Présent" => 0,
                "PasséSimple" => 0,
                "SubjImparfait" => 0,
                "SubjPrésent" => 0),
            "CM1" => array(
                "totalVerbs" => 0,
                "Conditionnel" => 0,
                "Futur" => 0,
                "Impératif" => 0,
                "Imparfait" => 0,
                "Infinitif" => 0,
                "ParticipePrésent" => 0,
                "Présent" => 0,
                "PasséSimple" => 0,
                "SubjImparfait" => 0,
                "SubjPrésent" => 0),
            "CM2" => array(
                "totalVerbs" => 0,
                "Conditionnel" => 0,
                "Futur" => 0,
                "Impératif" => 0,
                "Imparfait" => 0,
                "Infinitif" => 0,
                "ParticipePrésent" => 0,
                "Présent" => 0,
                "PasséSimple" => 0,
                "SubjImparfait" => 0,
                "SubjPrésent" => 0),
            "Total" => array(
                "totalVerbs" => 0,
                "Conditionnel" => 0,
                "Futur" => 0,
                "Impératif" => 0,
                "Imparfait" => 0,
                "Infinitif" => 0,
                "ParticipePrésent" => 0,
                "Présent" => 0,
                "PasséSimple" => 0,
                "SubjImparfait" => 0,
                "SubjPrésent" => 0)
            );

        #Sélectionner un verbe avec ses informations
        foreach ($tabVerbs as $verb){
            #On est sur un verbe
            #On ajoute 1 au niveau correspondant au verbe et au total de tous les verbes pour tous le sniveaux
            $nbVerbsByLevel[$verb["Niv"]]["totalVerbs"] ++;
            $nbVerbsByLevel["Total"]["totalVerbs"] ++;

            #On ajoute 1 au tiroir verbal correspondant
            if ($verb["Categorie"] == "VER:cond"){
                $nbVerbsByLevel[$verb["Niv"]]["Conditionnel"] ++;
                $nbVerbsByLevel["Total"]["Conditionnel"] ++;
            } elseif ($verb["Categorie"] == "VER:futu"){
                $nbVerbsByLevel[$verb["Niv"]]["Futur"] ++;
                $nbVerbsByLevel["Total"]["Futur"] ++;
            } elseif ($verb["Categorie"] == "VER:impe"){
                $nbVerbsByLevel[$verb["Niv"]]["Impératif"] ++;
                $nbVerbsByLevel["Total"]["Impératif"] ++;
            } elseif ($verb["Categorie"]== "VER:impf"){
                $nbVerbsByLevel[$verb["Niv"]]["Imparfait"] ++;
                $nbVerbsByLevel["Total"]["Imparfait"] ++;
            } elseif ($verb["Categorie"] == "VER:infi") {
                $nbVerbsByLevel[$verb["Niv"]]["Infinitif"] ++;
                $nbVerbsByLevel["Total"]["Infinitif"] ++;
            } elseif ($verb["Categorie"] == "VER:ppre"){
                $nbVerbsByLevel[$verb["Niv"]]["ParticipePrésent"] ++;
                $nbVerbsByLevel["Total"]["ParticipePrésent"] ++;
            } elseif ($verb["Categorie"] == "VER:pres"){
                $nbVerbsByLevel[$verb["Niv"]]["Présent"] ++;
                $nbVerbsByLevel["Total"]["Présent"] ++;
            } elseif ($verb["Categorie"] == "VER:simp"){
                $nbVerbsByLevel[$verb["Niv"]]["PasséSimple"] ++;
                $nbVerbsByLevel["Total"]["PasséSimple"] ++;
            } elseif ($verb["Categorie"]== "VER:subi"){
                $nbVerbsByLevel[$verb["Niv"]][ "SubjImparfait"] ++;
                $nbVerbsByLevel["Total"][ "SubjImparfait"] ++;
            } elseif ($verb["Categorie"]== "VER:subp"){
                $nbVerbsByLevel[$verb["Niv"]]["SubjPrésent"] ++;
                $nbVerbsByLevel["Total"]["SubjPrésent"] ++;
            }
        }

        #Création du tableau final
        $percentageVerbsByLevel = array("CP" => array(),"CE1" => array(),"CE2" => array(),"CM1" => array(),"CM2" => array(), "Total" => array());

        #Pour chaque niveau on calcule le pourcentage de chaque tiroir verbal : nombre de verbe du tiroir verbal / nombre total de verbes pour le niveaux
        #On calcule également le total : pourcentage de chaque tiroir verbal pour tous les niveaux confondus
        foreach($nbVerbsByLevel as $level => $tabTense){
            $sumVerbs=0;
            foreach($tabTense as $tense => $nbVerb) {
                if ($tense == "totalVerbs") {
                    $totalVerbs = $nbVerb;
                } elseif($totalVerbs ==0) {
                    $percentageVerbsByLevel[$level][$tense] = "0%";
                } else {
                    $percentageVerbsByLevel[$level][$tense] =(string) round($nbVerb / $totalVerbs * 100 ,2)."%";
                    $sumVerbs += $nbVerb;
                }
            }
            if($totalVerbs != 0){
                $percentageVerbsByLevel[$level]["Total"] = (string) round($sumVerbs / $totalVerbs * 100,2) . "%";
            } else {
                $percentageVerbsByLevel[$level]["Total"] = "0%";
            }
        }

        return $percentageVerbsByLevel;
    }
}