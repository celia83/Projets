<?php

include_once "model/DataBase.php";

/**
 * Class NbWordProd
 *
 * Cette classe n'a qu'une seule fonction qui permet de générer le tableau : Nombre de mots des productions.
 *
 * PHP version 5.6
 *
 * @author Océane Giroud <oceane.giroud@etu.univ-grenoble-alpes.fr>
 */
class NbWordProd {

    /**
     * Fonction createTabNbWordsProd()
     *
     * Cette fonction sélectionne seulement les mots dans la base de données et les compte (hors ponctuations et balises) : elle crée un tableau final
     * avec le nombre de mots par niveau, la longueur moyenne des productions par niveau et la taille min et max de ces productions par niveau
     *
     * @return array
     * @throws Exception
     */
    public function createTabNbWordsProd(){
        #Sélectionner seulement les mots dans la base de données (pas de ponctuation et de balises telles que <sent>)
        $request = "SELECT IdProd, Niv FROM `ecalm` WHERE SegNorm REGEXP '^<' = 0 AND SegNorm REGEXP '[a-zA-Z]'";

        #Récupération des données dans la base de données
        $database = new DataBase();
        $tabWords= $database->getData($request);

        #Etape 1 : ligne 1 du tableau = compter le nombre de mots en fonction des classes
        $CP = 0;
        $CE1 = 0;
        $CE2 = 0;
        $CM1 = 0;
        $CM2 = 0;

        #Tableau qui permettra de calculer la longueur moyenne des productions pour l'étape 2
        $tabNbProd = array("CP" => array(),"CE1" => array(),"CE2" => array(),"CM1" => array(),"CM2" => array());

        #Pour cela on incrémente les compteurs en fonction du Niveau
        foreach ($tabWords as $word){
            if ($word["Niv"]=="CP"){
                $CP++;
                # Pour calculer la longueur moyenne des production : Si l'id de la production est dans le tableau on ajoute 1 (car on compte la longueur de la production donc on compte un mot de plus)
                if (array_key_exists($word["IdProd"],$tabNbProd["CP"])){
                    $tabNbProd["CP"][$word["IdProd"]]++;
                #Si l'id de la production n'est aps dans le tableau on l'ajoute avec 1 en valeur car on est sur le premier de la production
                } else {
                    $tabNbProd["CP"][$word["IdProd"]] = 1;
                }
            }
            elseif ($word["Niv"]=="CE1"){
                $CE1++;
                #Si l'id de la production est dans le tableau on ajoute 1 (car on compte la longueur de la production donc on compte un mot de plus)
                if (array_key_exists($word["IdProd"],$tabNbProd["CE1"])){
                    $tabNbProd["CE1"][$word["IdProd"]]++;
                    #Si l'id de la production n'est aps dans le tableau on l'ajoute avec 1 en valeur car on est sur le premier de la production
                } else {
                    $tabNbProd["CE1"][$word["IdProd"]] = 1;
                }
            }
            elseif ($word["Niv"]=="CE2"){
                $CE2++;
                #Si l'id de la production est dans le tableau on ajoute 1 (car on compte la longueur de la production donc on compte un mot de plus)
                if (array_key_exists($word["IdProd"],$tabNbProd["CE2"])){
                    $tabNbProd["CE2"][$word["IdProd"]]++;
                    #Si l'id de la production n'est aps dans le tableau on l'ajoute avec 1 en valeur car on est sur le premier de la production
                } else {
                    $tabNbProd["CE2"][$word["IdProd"]] = 1;
                }
            }
            elseif ($word["Niv"]=="CM1"){
                $CM1++;
                #Si l'id de la production est dans le tableau on ajoute 1 (car on compte la longueur de la production donc on compte un mot de plus)
                if (array_key_exists($word["IdProd"],$tabNbProd["CM1"])){
                    $tabNbProd["CM1"][$word["IdProd"]]++;
                    #Si l'id de la production n'est aps dans le tableau on l'ajoute avec 1 en valeur car on est sur le premier de la production
                } else {
                    $tabNbProd["CM1"][$word["IdProd"]] = 1;
                }
            }
            elseif($word["Niv"]=="CM2"){
                $CM2++;
                #Si l'id de la production est dans le tableau on ajoute 1 (car on compte la longueur de la production donc on compte un mot de plus)
                if (array_key_exists($word["IdProd"],$tabNbProd["CM2"])){
                    $tabNbProd["CM2"][$word["IdProd"]]++;
                    #Si l'id de la production n'est aps dans le tableau on l'ajoute avec 1 en valeur car on est sur le premier de la production
                } else {
                    $tabNbProd["CM2"][$word["IdProd"]] = 1;
                }
            }
        }

        #On calcule le nombre total de mots
        $total = $CP + $CE1 + $CE2 + $CM1 + $CM2;

        #Etape 2 : calculer la longueur moyenne des productions
        #Permettra de calculer le nombre moyen de mots par production
        $tabNbProdAverage = array("CP" => 0,"CE1" => 0,"CE2" => 0,"CM1" => 0,"CM2" => 0, "CP-CM2" => 0);
        $totalNbProd = 0; #Nombre total de productions pour tous les niveaux confondus
        $totalNbWord = 0; #Nombre total de mots pour tous le sniveaux confondus

        #Etape 3 : Calculer la production avec le nombre de mots le plus élevé
        $tabNbWordMax = array("CP" => 0,"CE1" => 0,"CE2" => 0,"CM1" => 0,"CM2" => 0, "CP-CM2" => 0);

        #Etape 4 : Calculer la production avec le nombre de mots le plus faible
        $tabNbWordMin = array("CP" => 0,"CE1" => 0,"CE2" => 0,"CM1" => 0,"CM2" => 0, "CP-CM2" => 0);

        foreach($tabNbProd as $level => $tabProd){ #Pour chaque niveau on trouve le nombre de production total et le nombre de mot total et on fait la moyenne
            #Si on n'a pas de production pour un des niveaux on met la longueur moyenne des production à 0
            if (empty($tabProd)) {
                $tabNbProdAverage[$level] = 0;
            #Sinon on fait les calculs
            } else {
                #Pour l'étape 2
                $nbProd = sizeof($tabProd); #Nombre de productions du niveau
                $nbWord = array_sum($tabProd); #Somme des mots de toutes les productions
                $average = $nbWord / $nbProd; #Moyenne du nombre de mots par production
                $tabNbProdAverage[$level] = $average;

                #Pour l'étape 3
                #Ajouter le nombre de productions et de mots pour ce niveau au totaux pour faire la moyenne de tous les niveaux
                $totalNbProd += $nbProd;
                $totalNbWord += $nbWord;

                #Pour l'étape 4
                #Trouver la production pour laquelle le nombre de mots est le plus élevé, et celle pour laquelle il est le plus faible
                $max = max($tabProd);
                $min = min($tabProd);
                $tabNbWordMax[$level] = $max;
                $tabNbWordMin[$level] = $min;
            }
        }

        #On calcule le nombre moyen de mots par production pour tous les niveaux
        $totalAverage = $totalNbWord / $totalNbProd;
        $tabNbProdAverage["CP-CM2"] = $totalAverage;

        #Calculer le nombre max de mot pour tous les niveaux
        $allMax = max($tabNbWordMax);

        #Calculer le nombre max de mot pour tous les niveaux
        $allMin = min($tabNbWordMin);
        $tabNbWordMax["CP-CM2"] = $allMax;
        $tabNbWordMin["CP-CM2"] = $allMin;

        #On crée le tableau final
        $tabProd = array("Nombre de Mots (hors ponctuation)" => array("CP" => $CP,"CE1" => $CE1,"CE2" => $CE2,"CM1" => $CM1, "CM2" => $CM2, "CP-CM2" => $total),
                        "Longueur moyenne de production" => $tabNbProdAverage,
                        "Nombre de mots max." => $tabNbWordMax,
                        "Nombre de mots min." => $tabNbWordMin);

        return $tabProd;
    }
}