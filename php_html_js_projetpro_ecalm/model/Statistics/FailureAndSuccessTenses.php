<?php

include_once "model/DataBase.php";

/**
 * Classe CriterionVerb : classe fille de la classe Criterion.
 *
 * Cette classe ne contient qu'une seule fonction. Elle permet de créer le tableau :  Répartition des échecs et réussites pour les tiroirs verbaux les plus employés.
 *
 * PHP version 5.6
 *
 * @author Célia Martin <celia.ma@free.fr>
 */
class FailureAndSuccessTenses {

    /**
     * Fonction createTabFailureSuccess ($verbGroup)
     *
     * Fonction qui retourne un tableau indiquant les pourcentages de formes correctes, de formes incorrectes mais qui permettent de restituer la forme sonore et de formes incorrectes ne permettant pas de restituer la forme sonore, ces informations sont contenues dans la base de données.
     * Elle permet à l'utilisateur de choisir d'afficher ces informations pour les verbes en -er, non en -er ou pour tous
     *
     * @param String $verbGroup "er" | "nonEr" | Tous_les_verbes
     * @return array
     * @throws Exception
     */
    public function createTabFailureSuccess ($verbGroup) {
        /*  Indications :
            - Orthographe normée : forme correcte => SegNorm == SegTrans
            - Phonologie normée : forme incorrecte mais qui permet de restituer la forme sonore => SegNorm != SegTrans et PhonNorm = PhonTrans
            - Phonologie non normée : forme incorrecte ne permettant pas de restituer la forme sonore => SegNorm != SegTrans et PhonNorm != PhonTrans
         */

        #Créer la requête
        #Si l'utilisateur veut les verbes en er
        if ($verbGroup == "er") {
            $request ='SELECT Niv, SegNorm, SegTrans, PhonNorm, PhonTrans, Categorie, ErrVerBase, ErrVerDes, ErrVerBaseEtDes FROM ecalm WHERE Categorie LIKE "VER%" AND Categorie LIKE "VER:pper" = 0 AND Lemme LIKE "%er"' ;;
        #Si l'utilisateur veut les verbes qui ne sont pas en er
        } elseif ($verbGroup == "nonEr"){
            $request = 'SELECT Niv, SegNorm, SegTrans, PhonNorm, PhonTrans, Categorie, ErrVerBase, ErrVerDes, ErrVerBaseEtDes FROM ecalm WHERE Categorie LIKE "VER%" AND Categorie LIKE "VER:pper" = 0 AND Lemme LIKE "%er" = 0' ;;
        #Si l'utilisateur veut tous les verbes
        } else {
            $request = 'SELECT Niv, SegNorm, SegTrans, PhonNorm, PhonTrans, Categorie, ErrVerBase, ErrVerDes, ErrVerBaseEtDes FROM ecalm WHERE Categorie LIKE "VER%" AND Categorie LIKE "VER:pper" = 0' ;
        }

        #Récupération des données dans la base de données
        $database = new DataBase();
        $tabVerbs= $database->getData($request);

        #Classer les verbes récoltés en fonction de leur niveau et de leur tiroir verbal pour les futurs calculs
        $verbsByLevelTab = array(
            "CP" => array(
                "Ensemble des Verbes" => array(),
                "Imparfait" => array(),
                "Infinitif" => array(),
                "Présent" => array(),
                "Passé Simple" => array(),
            ),
            "CE1" => array(
                "Ensemble des Verbes" => array(),
                "Imparfait" => array(),
                "Infinitif" => array(),
                "Présent" => array(),
                "Passé Simple" => array(),
                ),
            "CE2" => array(
                "Ensemble des Verbes" => array(),
                "Imparfait" => array(),
                "Infinitif" => array(),
                "Présent" => array(),
                "Passé Simple" => array(),
            ),
            "CM1" => array(
                "Ensemble des Verbes" => array(),
                "Imparfait" => array(),
                "Infinitif" => array(),
                "Présent" => array(),
                "Passé Simple" => array(),
            ),
            "CM2" => array(
                "Ensemble des Verbes" => array(),
                "Imparfait" => array(),
                "Infinitif" => array(),
                "Présent" => array(),
                "Passé Simple" => array(),
            ));

        foreach ($tabVerbs as $verb) {
            if ($verb["Categorie"] == "VER:impf"){
                array_push ($verbsByLevelTab[$verb["Niv"]]["Imparfait"], $verb);
                array_push ($verbsByLevelTab[$verb["Niv"]]["Ensemble des Verbes"], $verb);
            } elseif ($verb["Categorie"] == "VER:infi"){
                array_push ($verbsByLevelTab[$verb["Niv"]]["Infinitif"], $verb);
                array_push ($verbsByLevelTab[$verb["Niv"]]["Ensemble des Verbes"], $verb);
            }elseif ($verb["Categorie"] == "VER:pres"){
                array_push ($verbsByLevelTab[$verb["Niv"]]["Présent"], $verb);
                array_push ($verbsByLevelTab[$verb["Niv"]]["Ensemble des Verbes"], $verb);
            }elseif ($verb["Categorie"] == "VER:simp"){
                array_push ($verbsByLevelTab[$verb["Niv"]]["Passé Simple"], $verb);
                array_push ($verbsByLevelTab[$verb["Niv"]]["Ensemble des Verbes"], $verb);
            } else {
                array_push ($verbsByLevelTab[$verb["Niv"]]["Ensemble des Verbes"], $verb);
            }
        }

        #Créer le tableau final
        $finalTab = array();

        #Faire les calculs pour chaque niveau
        foreach ($verbsByLevelTab as $level => $tabTense){
            foreach ($tabTense as $tense => $tab) {
                #Calculer le nombre de formes
                $nbForms = sizeof($tab);
                $finalTab[$level][$tense]["Nombre de formes"] = $nbForms;

                #Permettra de calculer le nombre de formes correctes (orthographe normée),
                # de formes incorrectes qui respectent la phonologie (phonologie normée),
                # de formes incorrectes qui ne respectent pas la phonologie (phonologie non normée)
                $orthoNorm = 0;
                $phonoNorm =0;
                $phonoNonNorm =0;
                foreach ($tab as $verb){
                    #Calculer le nombre de formes correctes (orthographe normée) : SegNorm == SegTrans
                    if ($verb["SegNorm"]==$verb["SegTrans"])   {
                        $orthoNorm ++;
                    #Calculer le nombres de formes incorrectes qui respectent la phonologie : SegNorm != SegTrans et PhonNorm == PhonTrans
                    } elseif ($verb["PhonNorm"]==$verb["PhonTrans"]) {
                        $phonoNorm ++;
                    #Calculer le nombres de formes incorrectes qui ne respectent pas la phonologie : SegNorm != SegTrans et PhonNorm != PhonTrans
                    } elseif ($verb["PhonNorm"]!=$verb["PhonTrans"]) {
                        $phonoNonNorm  ++;
                    }
                }

                if ($nbForms == 0 ){
                    $finalTab[$level][$tense]["Orthographe normée"] = "0%";
                    $finalTab[$level][$tense]["Phonologie normée"] = "0%";
                    $finalTab[$level][$tense]["Phonologie non normée"] = "0%";
                } else {
                    $finalTab[$level][$tense]["Orthographe normée"] = (string) round($orthoNorm / $nbForms * 100,2) . "%";
                    $finalTab[$level][$tense]["Phonologie normée"] = (string) round($phonoNorm / $nbForms * 100,2) . "%";
                    $finalTab[$level][$tense]["Phonologie non normée"] = (string) round($phonoNonNorm / $nbForms * 100,2) . "%";
                }

            }
        }

        return $finalTab;
    }
}