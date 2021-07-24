<?php

include_once "model/DataBase.php";

/**
 * Classe NbVerbalForms
 *
 * Cette classe ne contient qu'une seule fonction. Elle permet de créer le tableau :  Nombre de formes verbales analysées.
 *
 * PHP version 5.6
 *
 * @author Jingyu Liu <jingyu.liu@etu.univ-grenoble-alpes.fr>
 *
 */
class NbVerbalForms {

    /**
     * Fonction createTabNbVerbalForms()
     *
     * Cette fonction sélectionne dans la base de données les verbes correspondant aux informations suivantes pour chaque niveau : Nb total de formes verbales (hors p. passés), Nb formes verbales analysées, elle calcule ensuite le pourcentage de formes analysées. Le tableau contenant ces informations est finalement retourné.
     *
     * @return array
     * @throws Exception
     */
    public function createTabNbVerbalForms(){
        #Sélectionner seulement les verbes dans la base de données (hors p.passés)
		$request = 'SELECT * FROM `ecalm` WHERE Categorie LIKE "VER%" AND Categorie <> "VER:pper" ';
        #Récupération des données dans la base de données
        $database = new DataBase();
        $tabverbe= $database->getData($request);        

        #Etape 1 : ligne 1 du tableau = compter le nombre de verbes en fonction des classes
        $CP = 0;
        $CE1 = 0;
        $CE2 = 0;
        $CM1 = 0;
        $CM2 = 0;

        #Pour cela on incrémente les compteurs en fonction du Niveau
        foreach ($tabverbe as $word){
            if ($word["Niv"]=="CP"){
                $CP++;
            }
            elseif ($word["Niv"]=="CE1"){
                $CE1++;
            }
            elseif ($word["Niv"]=="CE2"){
                $CE2++;
            }
            elseif ($word["Niv"]=="CM1"){
                $CM1++;
            }
            elseif($word["Niv"]=="CM2"){
                $CM2++;
            }
        }

        #Etape 2 :  ligne 1 du tableau = compter le nombre formes verbales analysées en fonction des classes        
        $CPAnna = 0;
        $CE1Anna = 0;
        $CE2Anna = 0;
        $CM1Anna = 0;
        $CM2Anna = 0;
        
        #Sélectionner seulement les verbes qui ont un statut de segmentation "normé" dans la base de données
        $request = 'SELECT * FROM `ecalm` WHERE Categorie LIKE "VER%" AND Categorie <> "VER:pper"  AND BaseVerForme <> "_" AND BaseVerForme <> "#" ';

        #Récupération des données dans la base de données
        $Database = new DataBase();
        $tabverbeAnna= $Database->getData($request);
		
		#Pour cela on incrémente les compteurs en fonction du Niveau
        foreach ($tabverbeAnna as $Word){
            if ($Word["Niv"]=="CP"){
                $CPAnna++;
            }
            elseif ($Word["Niv"]=="CE1"){
                $CE1Anna++;
            }
            elseif ($Word["Niv"]=="CE2"){
                $CE2Anna++;
            }
            elseif ($Word["Niv"]=="CM1"){
                $CM1Anna++;
            }
            elseif($Word["Niv"]=="CM2"){
                $CM2Anna++;
            }
        }
        
        #Etape 3 : calculer le pourcentage de NB formes verbales analysées / Nb total de formes verbales   
        $CPpourcentageNbVerbale = 0;
        $CE1pourcentageNbVerbale = 0;
        $CE2pourcentageNbVerbale = 0;
        $CM1pourcentageNbVerbale = 0;
        $CM2pourcentageNbVerbale = 0;
       
        #calcule le pourcentage        
		if ($CP>0){
			$CPpourcentageNbVerbale = round(($CPAnna / $CP*100),2)."％";
		}else{
			$CPpourcentageNbVerbale = 0;
		}
		#calcule le pourcentage
        if ($CE1>0){
			$CE1pourcentageNbVerbale = round(($CE1Anna / $CE1*100),2)."％";
		}else{
			$CE1pourcentageNbVerbale = 0;
		}
		#calcule le pourcentage
		if ($CE2>0){
			$CE2pourcentageNbVerbale = round(($CE2Anna / $CE2*100),2)."％";
		}else{
			$CE2pourcentageNbVerbale = 0;
		}
		#calcule le pourcentage
		if ($CM1>0){
			$CM1pourcentageNbVerbale = round(($CM1Anna / $CM1*100),2)."％";
		}else{
			$CM1pourcentageNbVerbale = 0;
		}
		#calcule le pourcentage
		if ($CM2>0){
			$CM2pourcentageNbVerbale = round(($CM2Anna / $CM2*100),2)."％";
		}else{
			$CM2pourcentageNbVerbale = 0;
		}        

        #On crée le tableau final
        $tabProd = array("Nb total de formes verbales (hors p. passés)" => array("CP" => $CP,"CE1" => $CE1,"CE2" => $CE2,"CM1" => $CM1, "CM2" => $CM2),
                        "Nb formes verbales analysées" => array("CP" => $CPAnna,"CE1" => $CE1Anna,"CE2" => $CE2Anna,"CM1" => $CM1Anna, "CM2" => $CM2Anna),
                        "% de formes analysées" => array("CP" => $CPpourcentageNbVerbale,"CE1" => $CE1pourcentageNbVerbale,"CE2" => $CE2pourcentageNbVerbale,"CM1" => $CM1pourcentageNbVerbale, "CM2" => $CM2pourcentageNbVerbale));

        return $tabProd;
    }
}
