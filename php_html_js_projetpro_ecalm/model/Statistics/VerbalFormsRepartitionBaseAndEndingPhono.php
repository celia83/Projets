<?php

include_once "model/DataBase.php";

/**
 * Classe VerbalFormsRepartitionBaseAndEndingPhono
 *
 * Cette classe ne possède qu'une seule fonction et permet de générer le tableau : Répartition des formes verbales non normées selon si leur base et/ou leur désinence respectent ou non la phonologie.
 *
 * PHP version 5.6
 *
 * @author Jingyu Liu <jingyu.liu@etu.univ-grenoble-alpes.fr>
 */
class VerbalFormsRepartitionBaseAndEndingPhono{

    /**
     * Fonction createTabVerbalFormsRepartitionBaseAndEndingPhono($verbGroup, $tense)
     *
     * Cette fonction sélectionne dans la base de données les informations relatives à la phonologie et crée un tableau montrant le pourcentage de réussite et d'échecs des base et désinence au niveau phonologique.
     *
     * @param string $verbGroup Choix du groupe du verbe (-er | non -er | tous_les_verbes)
     * @param string $tense Choix du temps (Infinitif | Présent | Imparfait | Passé Simple | tous_les_temps)
     * @return array
     * @throws Exception
     */
    public function createTabVerbalFormsRepartitionBaseAndEndingPhono($verbGroup, $tense){
		#Créer la requête
        #Normaliser le temps des verbes pour la requête sql
        if ($tense == "Présent"){
            $tenseSQL = "VER:pres";
        } elseif ($tense == "Infinitif") {
            $tenseSQL = "VER:infi";
        }elseif ($tense == "Imparfait") {
            $tenseSQL = "VER:impf";
        } elseif ($tense == "Passé simple") {
            $tenseSQL = "VER:simp";
        } else {
            $tenseSQL = "VER%";
        }
        
        #Sélectionner soit les verbes en -er, soit les verbes qui ne sont pas en -er, soit tous et insérer le temps voulu
        if ($verbGroup == "er") {
            $GroupSQL = ' AND Lemme LIKE "%er"' ;
            #Si l'utilisateur veut les verbes qui ne sont pas en er
        } elseif ($verbGroup == "nonEr"){
            $GroupSQL = ' AND Lemme LIKE "%er" = 0' ;
            #Si l'utilisateur veut tous les verbes
        } else {
            $GroupSQL = ' AND Lemme LIKE "%"';
        }
		

		
        #Sélectionner seulement les verbes avec erreurs sur la base seule dans la base de données
		$request = 'SELECT * FROM `ecalm` WHERE Categorie LIKE "' . $tenseSQL . '" AND ErrVerBase LIKE "1" AND Categorie LIKE "VER:pper" = 0 ' . $GroupSQL ;
        #Récupération des données dans la base de données
        $database = new DataBase();
        $tabbase= $database->getData($request);        

        #Etape 1 : ligne 1 du tableau = compter le nombre formes verbales avec erreurs sur la base seule
        $CPbase = 0;
        $CE1base = 0;
        $CE2base = 0;
        $CM1base = 0;
        $CM2base = 0;

        #Pour cela on incrémente les compteurs en fonction du Niveau
        foreach ($tabbase as $word){
            if ($word["Niv"]=="CP"){
                $CPbase++;
            }
            elseif ($word["Niv"]=="CE1"){
                $CE1base++;
            }
            elseif ($word["Niv"]=="CE2"){
                $CE2base++;
            }
            elseif ($word["Niv"]=="CM1"){
                $CM1base++;
            }
            elseif($word["Niv"]=="CM2"){
                $CM2base++;
            }
        }
				
		#Sélectionner seulement les verbes avec erreurs sur la base seule de forme phonologie normée dans la base de données
		$request1 = 'SELECT * FROM `ecalm` WHERE Categorie LIKE "' . $tenseSQL . '" AND ErrVerBase LIKE "1" AND PhonNorm = PhonTrans AND Categorie LIKE "VER:pper" =0' . $GroupSQL ;
        #Récupération des données dans la base de données

        $dAtabase = new DataBase();
        $tabbasenorme= $dAtabase->getData($request1);          
        
        #ligne 1 du tableau = compter le nombre de forme phonologie normée
		$CPbasenorme = 0;
        $CE1basenorme = 0;
        $CE2basenorme = 0;
        $CM1basenorme = 0;
        $CM2basenorme = 0;
        
        #Pour cela on incrémente les compteurs en fonction du Niveau
		foreach ($tabbasenorme as $word){
            if ($word["Niv"]=="CP"){
                $CPbasenorme++;
            }
            elseif ($word["Niv"]=="CE1"){
                $CE1basenorme++;
            }
            elseif ($word["Niv"]=="CE2"){
                $CE2basenorme++;
            }
            elseif ($word["Niv"]=="CM1"){
                $CM1basenorme++;
            }
            elseif($word["Niv"]=="CM2"){
                $CM2basenorme++;
            }
        }
        
        #Etape 2 : ligne 1 du tableau = compter le pourcentage de forme phonologie normée
        $CPpourcentagebasenorme = 0;
        $CE1pourcentagebasenorme = 0;
        $CE2pourcentagebasenorme = 0;
        $CM1pourcentagebasenorme = 0;
        $CM2pourcentagebasenorme = 0;
        
        #Etape 2 : ligne 1 du tableau = compter le pourcentage de forme phonologie normée
        $CPpourcentagebasenonnorme = 0;
        $CE1pourcentagebasenonnorme = 0;
        $CE2pourcentagebasenonnorme = 0;
        $CM1pourcentagebasenonnorme = 0;
        $CM2pourcentagebasenonnorme = 0;
       
        #calcule le pourcentage        
		if ($CPbase>0){
			$CPpourcentagebasenorme = (string) round(($CPbasenorme / $CPbase*100),2)."％";
			$CPpourcentagebasenonnorme = (string) round((($CPbase-$CPbasenorme)/ $CPbase*100),2)."％";
		}else{
			$CPpourcentagebasenorme = "0%";
			$CPpourcentagebasenonnorme = "0%";
		}
		#calcule le pourcentage
        if ($CE1base>0){
			$CE1pourcentagebasenorme = (string) round(($CE1basenorme / $CE1base*100),2)."％";
			$CE1pourcentagebasenonnorme = (string) round((($CE1base-$CE1basenorme) / $CE1base*100),2)."％";
		}else{
			$CE1pourcentagebasenorme = "0%";
			$CE1pourcentagebasenonnorme = "0%";
		}
		#calcule le pourcentage
		if ($CE2base>0){
			$CE2pourcentagebasenorme = (string) round(($CE2basenorme / $CE2base*100),2)."％";
			$CE2pourcentagebasenonnorme =(string) round( (($CE2base-$CE2basenorme) / $CE2base*100),2)."％";
		}else{
			$CE2pourcentagebasenorme = "0%";
			$CE2pourcentagebasenonnorme = "0%";
		}
		#calcule le pourcentage
		if ($CM1base>0){
			$CM1pourcentagebasenorme = (string) round(($CM1basenorme / $CM1base*100),2)."％";
			$CM1pourcentagebasenonnorme = (string) round((($CM1base-$CM1basenorme) / $CM1base*100),2)."％";
		}else{
			$CM1pourcentagebasenorme ="0%";
			$CM1pourcentagebasenonnorme = "0%";
		}
		#calcule le pourcentage
		if ($CM2base>0){
			$CM2pourcentagebasenorme =(string) round( ($CM2basenorme / $CM2base*100),2)."％";
			$CM2pourcentagebasenonnorme = (string) round((($CM2base-$CM2basenorme) / $CM2base*100),2)."％";
		}else{
			$CM2pourcentagebasenorme = "0%";
			$CM2pourcentagebasenonnorme = "0%";
		}
				
		
        #Etape 4 :  ligne 1 du tableau = compter le nombre formes verbales avec erreur sur la désinence seule
        $CPdesin = 0;
        $CE1desin = 0;
        $CE2desin = 0;
        $CM1desin = 0;
        $CM2desin = 0;
        
        #Sélectionner seulement les verbes avec erreur sur la désinence seule dans la base de données 
        $request = 'SELECT * FROM `ecalm` WHERE Categorie LIKE "' . $tenseSQL . '" AND ErrVerDes LIKE "1" AND Categorie LIKE "VER:pper" =0' . $GroupSQL;
        #Récupération des données dans la base de données
        $Database = new DataBase();
        $tabdesin= $Database->getData($request);
		
		#Pour cela on incrémente les compteurs en fonction du Niveau
        foreach ($tabdesin as $Word){
            if ($Word["Niv"]=="CP"){
                $CPdesin++;
            }
            elseif ($Word["Niv"]=="CE1"){
                $CE1desin++;
            }
            elseif ($Word["Niv"]=="CE2"){
                $CE2desin++;
            }
            elseif ($Word["Niv"]=="CM1"){
                $CM1desin++;
            }
            elseif($Word["Niv"]=="CM2"){
                $CM2desin++;
            }
        }
        
		#Sélectionner seulement les verbes avec erreurs sur la désinence seule de forme phonologie normée dans la base de données
		$request2 = 'SELECT * FROM `ecalm` WHERE Categorie LIKE "' . $tenseSQL . '" AND ErrVerDes LIKE "1" AND PhonNorm = PhonTrans AND Categorie LIKE "VER:pper" =0' . $GroupSQL;
        #Récupération des données dans la base de données
        $daTabase = new DataBase();
        $tabbaseNormeDes= $daTabase->getData($request2);          
        
        #ligne 1 du tableau = compter le nombre de forme phonologie normée
		$CPbaseNormeDes = 0;
        $CE1baseNormeDes = 0;
        $CE2baseNormeDes = 0;
        $CM1baseNormeDes = 0;
        $CM2baseNormeDes = 0;
        
        #Pour cela on incrémente les compteurs en fonction du Niveau
		foreach ($tabbaseNormeDes as $word){
            if ($word["Niv"]=="CP"){
                $CPbaseNormeDes++;
            }
            elseif ($word["Niv"]=="CE1"){
                $CE1baseNormeDes++;
            }
            elseif ($word["Niv"]=="CE2"){
                $CE2baseNormeDes++;
            }
            elseif ($word["Niv"]=="CM1"){
                $CM1baseNormeDes++;
            }
            elseif($word["Niv"]=="CM2"){
                $CM2baseNormeDes++;
            }
        }
        
        #Etape 5 : ligne 1 du tableau = compter le pourcentage de forme phonologie normée
        $CPpourcentageNormeDes = 0;
        $CE1pourcentageNormeDes = 0;
        $CE2pourcentageNormeDes = 0;
        $CM1pourcentageNormeDes = 0;
        $CM2pourcentageNormeDes = 0;
        
        #Etape 6 : ligne 1 du tableau = compter le pourcentage de forme phonologie non normée
        $CPpourcentageNonNormeDes = 0;
        $CE1pourcentageNonNormeDes = 0;
        $CE2pourcentageNonNormeDes = 0;
        $CM1pourcentageNonNormeDes = 0;
        $CM2pourcentageNonNormeDes = 0;
       
        #calcule le pourcentage        
		if ($CPdesin>0){
			$CPpourcentageNormeDes = (string)round(($CPbaseNormeDes / $CPdesin*100),2)."％";
			$CPpourcentageNonNormeDes =(string)round( (($CPdesin-$CPbaseNormeDes)/ $CPdesin*100),2)."％";
		}else{
			$CPpourcentageNormeDes = "0%";
			$CPpourcentageNonNormeDes = "0%";
		}
		#calcule le pourcentage
        if ($CE1desin>0){
			$CE1pourcentageNormeDes = (string)round(($CE1baseNormeDes / $CE1desin*100),2)."％";
			$CE1pourcentageNonNormeDes = (string)round((($CE1desin-$CE1baseNormeDes) / $CE1desin*100),2)."％";
		}else{
			$CE1pourcentageNormeDes = "0%";
			$CE1pourcentageNonNormeDes = "0%";
		}
		#calcule le pourcentage
		if ($CE2desin>0){
			$CE2pourcentageNormeDes = (string)round(($CE2baseNormeDes / $CE2desin*100),2)."％";
			$CE2pourcentageNonNormeDes =(string)round( (($CE2desin-$CE2baseNormeDes) / $CE2desin*100),2)."％";
		}else{
			$CE2pourcentageNormeDes = "0%";
			$CE2pourcentageNonNormeDes = "0%";
		}
		#calcule le pourcentage
		if ($CM1desin>0){
			$CM1pourcentageNormeDes = (string)round(($CM1baseNormeDes / $CM1desin*100),2)."％";
			$CM1pourcentageNonNormeDes = (string)round((($CM1desin-$CM1baseNormeDes) / $CM1desin*100),2)."％";
		}else{
			$CM1pourcentageNormeDes = "0%";
			$CM1pourcentageNonNormeDes = "0%";
		}
		#calcule le pourcentage
		if ($CM2desin>0){
			$CM2pourcentageNormeDes = (string)round(($CM2baseNormeDes / $CM2desin*100),2)."％";
			$CM2pourcentageNonNormeDes = (string)round((($CM2desin-$CM2baseNormeDes) / $CM2desin*100),2)."％";
		}else{
			$CM2pourcentageNormeDes = "0%";
			$CM2pourcentageNonNormeDes = "0%";
		}		
		
		#Etape 7 :  ligne 1 du tableau = compter le nombre formes verbales avec erreur sur la base et la désinence 
        $CPbasedesin = 0;
        $CE1basedesin = 0;
        $CE2basedesin = 0;
        $CM1basedesin = 0;
        $CM2basedesin = 0;
        
		#Sélectionner seulement les verbes avec erreur sur la base et la désinence dans la base de données 
        $request3 = 'SELECT * FROM `ecalm` WHERE Categorie LIKE "' . $tenseSQL . '" AND ErrVerBaseEtDes LIKE "1" AND Categorie LIKE "VER:pper" =0 ' . $GroupSQL;
        #Récupération des données dans la base de données
        $DataAbase = new DataBase();
        $tabbasedesin= $DataAbase->getData($request3);
		
		#Pour cela on incrémente les compteurs en fonction du Niveau
        foreach ($tabbasedesin as $Word){
            if ($Word["Niv"]=="CP"){
                $CPbasedesin++;
            }
            elseif ($Word["Niv"]=="CE1"){
                $CE1basedesin++;
            }
            elseif ($Word["Niv"]=="CE2"){
                $CE2basedesin++;
            }
            elseif ($Word["Niv"]=="CM1"){
                $CM1basedesin++;
            }
            elseif($Word["Niv"]=="CM2"){
                $CM2basedesin++;
            }
        }
        
		#Sélectionner seulement les verbes avec erreurs sur la base et la désinence seule de forme phonologie normée dans la base de données
		$request4 = 'SELECT * FROM `ecalm` WHERE Categorie LIKE "' . $tenseSQL . '" AND ErrVerBaseEtDes LIKE "1" AND PhonNorm = PhonTrans AND Categorie LIKE "VER:pper"=0 ' . $GroupSQL;
        #Récupération des données dans la base de données
        $daTabase = new DataBase();
        $tabbaseNormeBasedesin= $daTabase->getData($request4);          
        
        #ligne 1 du tableau = compter le nombre de forme phonologie normée
		$CPbaseNormeBasedesin = 0;
        $CE1baseNormeBasedesin = 0;
        $CE2baseNormeBasedesin = 0;
        $CM1baseNormeBasedesin = 0;
        $CM2baseNormeBasedesin = 0;
        
        #Pour cela on incrémente les compteurs en fonction du Niveau
		foreach ($tabbaseNormeBasedesin as $word){
            if ($word["Niv"]=="CP"){
                $CPbaseNormeBasedesin++;
            }
            elseif ($word["Niv"]=="CE1"){
                $CE1baseNormeBasedesin++;
            }
            elseif ($word["Niv"]=="CE2"){
                $CE2baseNormeBasedesin++;
            }
            elseif ($word["Niv"]=="CM1"){
                $CM1baseNormeBasedesin++;
            }
            elseif($word["Niv"]=="CM2"){
                $CM2baseNormeBasedesin++;
            }
        }
        
        #Etape 8 : ligne 1 du tableau = compter le pourcentage de forme phonologie normée
        $CPpourcentageNormeBasedesin = 0;
        $CE1pourcentageNormeBasedesin = 0;
        $CE2pourcentageNormeBasedesin = 0;
        $CM1pourcentageNormeBasedesin = 0;
        $CM2pourcentageNormeBasedesin = 0;
        
        #Etape 9 : ligne 1 du tableau = compter le pourcentage de forme phonologie normée
        $CPpourcentageNonNormeBasedesin = 0;
        $CE1pourcentageNonNormeBasedesin = 0;
        $CE2pourcentageNonNormeBasedesin = 0;
        $CM1pourcentageNonNormeBasedesin = 0;
        $CM2pourcentageNonNormeBasedesin = 0;
       
        #calcule le pourcentage        
		if ($CPbasedesin>0){
			$CPpourcentageNormeBasedesin =(string) round(($CPbaseNormeBasedesin / $CPbasedesin*100),2)."％";
			$CPpourcentageNonNormeBasedesin = (string)round((($CPbasedesin-$CPbaseNormeBasedesin)/ $CPbasedesin*100),2)."％";
		}else{
			$CPpourcentageNormeBasedesin = "0%";
			$CPpourcentageNonNormeBasedesin = "0%";
		}
		#calcule le pourcentage
        if ($CE1basedesin>0){
			$CE1pourcentageNormeBasedesin = (string)round(($CE1baseNormeBasedesin / $CE1basedesin*100),2)."％";
			$CE1pourcentageNonNormeBasedesin = (string)round((($CE1basedesin-$CE1baseNormeBasedesin) / $CE1basedesin*100),2)."％";
		}else{
			$CE1pourcentageNormeBasedesin = "0%";
			$CE1pourcentageNonNormeBasedesin = "0%";
		}
		#calcule le pourcentage
		if ($CE2basedesin>0){
			$CE2pourcentageNormeBasedesin =(string) round(($CE2baseNormeBasedesin / $CE2basedesin*100),2)."％";
			$CE2pourcentageNonNormeBasedesin = (string)round((($CE2basedesin-$CE2baseNormeBasedesin) / $CE2basedesin*100),2)."％";
		}else{
			$CE2pourcentageNormeBasedesin = "0%";
			$CE2pourcentageNonNormeBasedesin = "0%";
		}
		#calcule le pourcentage
		if ($CM1basedesin>0){
			$CM1pourcentageNormeBasedesin =(string) round(($CM1baseNormeBasedesin / $CM1basedesin*100),2)."％";
			$CM1pourcentageNonNormeBasedesin = (string)round((($CM1basedesin-$CM1baseNormeBasedesin) / $CM1basedesin*100),2)."％";
		}else{
			$CM1pourcentageNormeBasedesin = "0%";
			$CM1pourcentageNonNormeBasedesin = "0%";
		}
		#calcule le pourcentage
		if ($CM2basedesin>0){
			$CM2pourcentageNormeBasedesin =(string) round(($CM2baseNormeBasedesin / $CM2basedesin*100),2)."％";
			$CM2pourcentageNonNormeBasedesin = (string)round((($CM2basedesin-$CM2baseNormeBasedesin) / $CM2basedesin*100),2)."％";
		}else{
			$CM2pourcentageNormeBasedesin = "0%";
			$CM2pourcentageNonNormeBasedesin = "0%";
		}		

        #On crée le tableau final
        $tabProd = array("Nb formes verbales avec erreurs sur la base seule" => array("CP" => $CPbase,"CE1" => $CE1base,"CE2" => $CE2base,"CM1" => $CM1base, "CM2" => $CM2base),
						"% de forme phonologie normée sur la base seule" => array("CP" => $CPpourcentagebasenorme,"CE1" => $CE1pourcentagebasenorme,"CE2" => $CE2pourcentagebasenorme,"CM1" => $CM1pourcentagebasenorme, "CM2" => $CM2pourcentagebasenorme),
                        "% de forme phonologie non normée sur la base seule" => array("CP" => $CPpourcentagebasenonnorme,"CE1" => $CE1pourcentagebasenonnorme,"CE2" => $CE2pourcentagebasenonnorme,"CM1" => $CM1pourcentagebasenonnorme, "CM2" => $CM2pourcentagebasenonnorme),
                        "Nb formes verbales avec erreur sur la désinence seule" => array("CP" => $CPdesin,"CE1" => $CE1desin,"CE2" => $CE2desin,"CM1" => $CM1desin, "CM2" => $CM2desin),
                        "% de forme phonologie normée sur la désinence seule" => array("CP" => $CPpourcentageNormeDes,"CE1" => $CE1pourcentageNormeDes,"CE2" => $CE2pourcentageNormeDes,"CM1" => $CM1pourcentageNormeDes, "CM2" => $CM2pourcentageNormeDes),
                        "% de forme phonologie non normée sur la désinence seule" => array("CP" => $CPpourcentageNonNormeDes,"CE1" => $CE1pourcentageNonNormeDes,"CE2" => $CE2pourcentageNonNormeDes,"CM1" => $CM1pourcentageNonNormeDes, "CM2" => $CM2pourcentageNonNormeDes),
                        "Nb formes verbales avec erreur sur la base et la désinence" => array("CP" => $CPbasedesin,"CE1" => $CE1basedesin,"CE2" => $CE2basedesin,"CM1" => $CM1basedesin, "CM2" => $CM2basedesin),
                        "% de forme phonologie normée sur la base et la désinence" => array("CP" => $CPpourcentageNormeBasedesin,"CE1" => $CE1pourcentageNormeBasedesin,"CE2" => $CE2pourcentageNormeBasedesin,"CM1" => $CM1pourcentageNormeBasedesin, "CM2" => $CM2pourcentageNormeBasedesin),
                        "% de forme phonologie non normée sur la base et la désinence" => array("CP" => $CPpourcentageNonNormeBasedesin,"CE1" => $CE1pourcentageNonNormeBasedesin,"CE2" => $CE2pourcentageNonNormeBasedesin,"CM1" => $CM1pourcentageNonNormeBasedesin, "CM2" => $CM2pourcentageNonNormeBasedesin)
                        );

        return $tabProd;
    }
}
