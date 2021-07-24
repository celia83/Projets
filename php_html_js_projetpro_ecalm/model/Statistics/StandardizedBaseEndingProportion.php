<?php

include_once "model/DataBase.php";

/**
 * Classe StandardizedBaseEndingProportion
 *
 * Cette classe n'a qu'une seule fonction et permet de créer le tableau des proportions de bases et désinences normées et non normées.
 *
 * PHP version 5.6
 *
 * @author Célia Martin <celia.ma@free.fr>
 */
class StandardizedBaseEndingProportion {

    /**
     * Fonction createTabStandardizedBaseEndingProportion($verbGroup, $tense)
     *
     * Cette fonction prend en entrée les choix de l'utilisateur concernant le groupe du verbe et son temps (choix entre les verbes en er ou non et entre les quatre temps [infinitif, présent, imparfait, passé simple]) et retourne un tableau contenant les informations nombre de base / désinences erronées et nombre de base / désinence normées, en fonction des informations contenues dans la base de données.
     *
     * @param string $verbGroup Choix du groupe du verbe (-er | non -er | tous_les_verbes)
     * @param string $tense Choix du temps (Infinitif | Présent | Imparfait | Passé Simple | tous_les_temps)
     * @return array
     * @throws Exception
     */
    public function createTabStandardizedBaseEndingProportion($verbGroup, $tense) {
        #Créer la requête
        #Normaliser le temps des verbes pour la requête sql
        if ($tense == "Présent") {
            $tenseSQL = "VER:pres";
        } elseif ($tense == "Infinitif") {
            $tenseSQL = "VER:infi";
        } elseif ($tense == "Imparfait") {
            $tenseSQL = "VER:impf";
        } elseif ($tense == "Passé simple") {
            $tenseSQL = "VER:simp";
        } else {
            $tenseSQL = "VER%";
        }

        #Sélectionner soit les verbes en -er, soit les verbes qui ne sont pas en -er, soit tous et insérer le temps voulu
        if ($verbGroup == "er") {
            $request = 'SELECT Niv, ErrVerBase, ErrVerDes, ErrVerBaseEtDes FROM `ecalm` WHERE Categorie LIKE "' . $tenseSQL . '" AND Categorie LIKE "VER:pper" =0 AND Lemme LIKE "%er"';;
            #Si l'utilisateur veut les verbes qui ne sont pas en er
        } elseif ($verbGroup == "nonEr") {
            $request = 'SELECT Niv, ErrVerBase, ErrVerDes, ErrVerBaseEtDes FROM `ecalm` WHERE Categorie LIKE "' . $tenseSQL . '" AND Categorie LIKE "VER:pper"=0 AND Lemme LIKE "%er" = 0';;
            #Si l'utilisateur veut tous les verbes
        } else {
            $request = 'SELECT Niv, ErrVerBase, ErrVerDes, ErrVerBaseEtDes FROM `ecalm` WHERE Categorie LIKE "' . $tenseSQL . '" AND Categorie LIKE "VER:pper" = 0';
        }

        #Récupération des données dans la base de données
        $database = new DataBase();
        $tabVerbs = $database->getData($request);

        $verbByLevelTab = array(
            "CP" => array(
                "Base normée" => array(),
                "Base erronée" => array(),
                "TotalBase" => array(),
                "Désinence normée" => array(),
                "Désinence erronée" => array(),
                "TotalDes" => array()
            ),
            "CE1" => array(
                "Base normée" => array(),
                "Base erronée" => array(),
                "TotalBase" => array(),
                "Désinence normée" => array(),
                "Désinence erronée" => array(),
                "TotalDes" => array()
            ),
            "CE2" => array(
                "Base normée" => array(),
                "Base erronée" => array(),
                "TotalBase" => array(),
                "Désinence normée" => array(),
                "Désinence erronée" => array(),
                "TotalDes" => array()
            ),
            "CM1" => array(
                "Base normée" => array(),
                "Base erronée" => array(),
                "TotalBase" => array(),
                "Désinence normée" => array(),
                "Désinence erronée" => array(),
                "TotalDes" => array()
            ),
            "CM2" => array(
                "Base normée" => array(),
                "Base erronée" => array(),
                "TotalBase" => array(),
                "Désinence normée" => array(),
                "Désinence erronée" => array(),
                "TotalDes" => array()
            ));

        foreach($tabVerbs as $verb){
            #Base Normée
            if ($verb["ErrVerBase"] == 0 && $verb["ErrVerBaseEtDes"] == 0 ){
                array_push($verbByLevelTab[$verb["Niv"]]["Base normée"],$verb);
                array_push($verbByLevelTab[$verb["Niv"]]["TotalBase"],$verb);
            #Base Erronée
            } elseif ($verb["ErrVerBase"] == 1 && $verb["ErrVerBaseEtDes"] == 1){
                array_push($verbByLevelTab[$verb["Niv"]]["Base erronée"],$verb);
                array_push($verbByLevelTab[$verb["Niv"]]["TotalBase"],$verb);
           #Désinence Normée
            }elseif ($verb["ErrVerDes"] == 0 && $verb["ErrVerBaseEtDes"] == 0 ){
                array_push($verbByLevelTab[$verb["Niv"]]["Désinence normée"],$verb);
                array_push($verbByLevelTab[$verb["Niv"]]["TotalDes"],$verb);
            #Désinence Erronée
            }elseif ($verb["ErrVerDes"] == 1 && $verb["ErrVerBaseEtDes"] == 1 ){
                array_push($verbByLevelTab[$verb["Niv"]]["Désinence erronée"],$verb);
                array_push($verbByLevelTab[$verb["Niv"]]["TotalDes"],$verb);
            }
        }

        #Création du tableau final
        $finalTab = array();

        #Pour chaque niveau
        foreach ($verbByLevelTab as $level => $tabBaseDes){
            #Total de verbes à base normées et erronées
            $totalBase = sizeof($tabBaseDes["TotalBase"]);

            #Nombre de bases normées
            $nbStandardizedBase = sizeof($tabBaseDes["Base normée"]);

            #Nombre de base erronées
            $nbWrongBase = sizeof($tabBaseDes["Base erronée"]);

            #Total de verbes à désinence normées et erronées
            $totalDes = sizeof($tabBaseDes["TotalDes"]);

            #Nombre de désinences normées
            $nbStandardizedDes = sizeof($tabBaseDes["Désinence normée"]);

            #Nombre de désinences erronées
            $nbWrongDes = sizeof($tabBaseDes["Désinence erronée"]);

            #Calcul des pourcentages et ajout au tableau
            #On vérifie à chaque fois qu'on a bien un total (donc qu'on a des verbes pour faire le calcul) : éviter la division par 0
            if ($totalBase != 0) {
                $finalTab["Base normée"][$level] = (string) round($nbStandardizedBase / $totalBase * 100 ,2). "%";
                $finalTab["Base erronée"][$level] = (string) round($nbWrongBase / $totalBase * 100 ,2). "%";
                $finalTab["TotalBase"][$level] = (string) round(($nbStandardizedBase + $nbWrongBase) / $totalBase * 100,2) . "%";
            }else {
                $finalTab["Base normée"][$level] = "0%";
                $finalTab["Base erronée"][$level] = "0%";
                $finalTab["TotalBase"][$level] = "0%";
            }

            if ($totalDes != 0) {
                $finalTab["Désinence normée"][$level] = (string) round( $nbStandardizedDes  / $totalDes * 100,2) . "%";
                $finalTab["Désinence erronée"][$level] = (string)  round($nbWrongDes / $totalDes * 100,2) . "%";
                $finalTab["TotalDes"][$level] = (string)  round(($nbStandardizedDes + $nbWrongDes)  / $totalDes * 100,2) . "%";
            }else {
                $finalTab["Désinence normée"][$level] = "0%";
                $finalTab["Désinence erronée"][$level] = "0%";
                $finalTab["TotalDes"][$level] = "0%";
            }
        }

        return $finalTab;
    }
}