<?php
#########################################
#
#Se connecte à la base de données et retourne un objet permettant d'utiliser les fonctions sur la base de données
#
#########################################
function connexion(){
    try { /* tentative de connexion à la BD*/
        $bdd = new PDO('mysql:host=localhost;dbname=marticel', 'marticel', 'Lmcmcjl83');
        return $bdd;
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
}

#########################################
#
#Enregistre le lexique depuis la base de données dans une table de hashage
#
#########################################

function charge_lex(){
    $bdd=connexion();
    $tablex = array();
    $requete = "SELECT * FROM lexique";
    if ($reponse = $bdd->query($requete)) {
        while($enr=$reponse->fetch()) {
            $tablex[$enr["mot"]]=$enr["mdc"];
        }
    }
    return $tablex;
}

#########################################
#
#Enregistre le lexique de la textarea dans une table de hashage. Retourne une table de hashage avec le mot en clé et le MDC en valeur.
#
#########################################

function table_lex($lexique){
    foreach ($lexique as $ligne){
        if ($ligne!="" && strlen(trim($ligne))!=0){
            $entree = explode(":", $ligne);
            $tablex [$entree[0]] = $entree[1];
        }
    }
    return $tablex;
}

#########################################
#
#Enregistre la base de règles depuis la base de données dans une table de hashage.
#
#########################################

function charge_rb(){
    $bdd=connexion();
    $tabrb = array();
    $requete = "SELECT * FROM base_regles";
    if ($reponse = $bdd->query($requete)) {
        while($enr=$reponse->fetch()) {
            $tabrb[$enr["mdc"]]=$enr["chunk"];
        }
    }
    return $tabrb;
}

#########################################
#
#Enregistre les règles de la textarea dans une table de hashage. Retourne une table de hashage avec le MDC en clé et le Chunk en valeur.
#
#########################################

function table_rb($RB){
    foreach($RB as $ligne){
        if ($ligne!="" && strlen(trim($ligne))!=0){
            $entree = explode("->", $ligne);
            $tabrb [$entree[0]] = trim($entree[1])." ";
        }
    }
    return $tabrb;
}

#########################################
#
#Ajoute du lexique et des règles dans les bases de données. Ne retourne rien.
#
#########################################

function ajoute($ligne){
    $bdd=connexion();
    $ligne = explode("\n", $ligne)[0];
    if (strstr($ligne,":")){        #Traitement des lignes de lexique
        $ligne_cut = explode(":", $ligne);
        $mot = $ligne_cut[0];
        $mdc = $ligne_cut[1];
        $requete = 'INSERT INTO `lexique` (`mot`, `mdc`, `id`) VALUES ("'.$mot.'", "'.$mdc.'", NULL);';
        $bdd->query($requete);
    } else if (strstr($ligne,"->")){         #Traitement des lignes de la base de règles
        $ligne_cut = explode("->", $ligne);
        $mdc = $ligne_cut[0];
        $chunk = $ligne_cut[1];
        $requete = 'INSERT INTO `base_regles` (`mdc`, `chunk`, `id`) VALUES ("'.$mdc.'", "'.$chunk.'", NULL);';
        $bdd->query($requete);
    }
}

#########################################
#
#Supprime de la base de données les lignes du lexique et des règles qui ont été effacées par l'utilisateur dans l'interface. Ne retourne rien.
#
#########################################

function supprime($element){
    if($element == explode("\n", $_POST["lexique"])){       #Est-ce qu'on supprime des éléments du lexique ?
        $lex_bd = charge_lex();     #Charger le contenu de la bdd dans une table de hachage
        $lex = table_lex($element); #Charger le contenu de la textarea dans une table de hachage
        $bdd=connexion();
        foreach($lex_bd as $mot_bd =>$mdc){
            if (!array_key_exists($mot_bd, $lex)){  #Comparer les deux tables de hachage
                $requete = 'DELETE FROM `lexique` WHERE `lexique`.`mot` = "'.$mot_bd.'"';
                $bdd->query($requete);
            }
        }
    } else if ($element == explode("\n", $_POST["BR"])){    #Si ce n'est pas du lexique est-ce que c'est la base de règles ?
        $br_bd = charge_rb();
        $br = table_rb($element);
        $bdd=connexion();
        foreach($br_bd as $mdc_bd =>$chunk){
            if (!array_key_exists($mdc_bd, $br)){   #Comparer les deux tables de hachage
                $requete = 'DELETE FROM `base_regles` WHERE `base_regles`.`mdc` = "'.$mdc_bd.'"';
                $bdd->query($requete);
            }
        }
    }

}

#########################################
#
#Vérifie qu'un élément du lexique ou de la base de règles est dans la base de données. Retourne vrai si l'élément est dans la BD, faux si non.
#
#########################################

function verifie ($ligne){
    $bdd=connexion();
    if (strstr($ligne,":")){    #Traitement pour le lexique
        $ligne_cut = explode(":", $ligne);
        $mot = $ligne_cut[0];
        $requete = 'SELECT mot FROM lexique WHERE mot = "'.$mot.'"';
        if ($reponse = $bdd->query($requete)) {
            $enr = $reponse->fetch();
        }
        if ($enr['mot']==$mot){
            return true;
        }else {
            return false;
        }
    }else if (strstr($ligne,"->")) {        #Traitement pour la base de règles
        $ligne_cut = explode("->", $ligne);
        $mdc = $ligne_cut[0];
        $requete = 'SELECT mdc FROM base_regles WHERE mdc = "'.$mdc.'"';
        if ($reponse = $bdd->query($requete)) {
            $enr = $reponse->fetch();
        }
        if ($enr['mdc']==$mdc){
            return true;
        }else {
            return false;
        }
    }
}

#########################################
#
#Ajoute un espace autour des ponctuations. Retourne le texte normalisé.
#
#########################################

function normalisation ($texte){
    $ponctuations = array(".",",","'","-");
    $ponctuation_remplace= array(" ."," ,","' "," - ");
    $texte_normalise = str_replace ($ponctuations, $ponctuation_remplace, $texte);
    return $texte_normalise;
}

#########################################
#
#Compte le nombre de morceaux qui composent une règle. Retourne un tableau indexé numériquement avec le nb de partie à l'indice 0, et les différentes parties dans les indices suivants.
#
#########################################
function nbParties_et_Parties($regle) {
    $regle_cut = explode("&", $regle);
    $cpt = count($regle_cut);
    $tab_morceaux[0] = $cpt;
    foreach ($regle_cut as $morceau){
        array_push($tab_morceaux,$morceau);
    }
    return $tab_morceaux;
}

#########################################
#
# Retourne un string avec ce qu'il faut afficher en tant que résultat de la règle.
#
#########################################

function resultat($resultat, $mot, $regle_juste, $indice, $longueur) {
    $resultat = trim ($resultat);
    if (strstr($resultat, "-/>")) {     #Si le résultat de la règle est en deux parties
        $resultat_cut = explode("-/>", $resultat); #on décompose ces parties
        $resultat_part1 = $resultat_cut[0];
        $resultat_part2 = $resultat_cut[1];
        if ($regle_juste){              #Dans le cas où la règles est juste
            if($indice == 0) {          #Si c'est le premier mot du texte
                $resultat_part1 = substr($resultat_part1, 1);
                return $resultat_part1." ".$mot." ";
            } elseif ($indice == $longueur){        #Si c'est le dernier mot du texte
                return $resultat_part1." ".$mot." ]";
            } else {
                return $resultat_part1." ".$mot." ";
            }
        } else {        #Si la règle est fausse
            if($indice == 0) {      #Si c'est le premier mot du texte
                $resultat_part2 = substr($resultat_part2, 1);
                return $resultat_part2." ".$mot." ";
            }elseif($indice==$longueur){        #Si c'est le dernier mot du texte
                return $resultat_part2." ".$mot." ]";
            } else{
                return $resultat_part2." ".$mot." ";
            }
        }
    } else {                                    #Si le résultat de la règle est en une partie
        if ($regle_juste){
            if($indice == 0) {
                $resultat = substr($resultat, 1);
                return $resultat." ".$mot." ";
            }elseif($indice==$longueur){
                return $resultat." ".$mot." ]";
            } else {
                return $resultat." ".$mot." ";
            }
        } else {
            if ($indice == $longueur){
                return $mot." ]";
            } else {
                return $mot." ";
            }
        }
    }

}

#########################################
#
#Permet de savoir si un mot commence ou finit par une série de lettres proposée dans la lexique. Retourne la particularité trouvée dans le mot (par exemple : finit par "er")
#
#########################################
function FindParticularity($mot, $lexique) {
    $trouve = false ;
    while ($trouve == false) {          #Faire l'action jusqu'à ce que qu'on a trouvé une particularité ou non
        foreach ($lexique as $elt_lex => $MDC){
            $debut_elt = substr($elt_lex, 0,1); #trouver le début du mot de lexique pour savoir s'il commence par un ? ou non
            $fin_elt = substr($elt_lex, -1);    #trouver la fin du mot de lexique pour savoir s'il finit par un ? ou non
            if ($debut_elt == "?"){             #Traitement des séries de lettre en fin de mot
                $morpho = substr($elt_lex, 1);
                $longueur = strlen($morpho);
                $fin_mot = substr($mot, -$longueur);
                if(strstr($morpho, $fin_mot)){
                    $particularite = "?".$morpho;
                    $trouve = true;
                }
            } else if ($fin_elt == "?"){        #Traitement des séries de lettre en début de mot
                $morpho = substr($elt_lex, 0,-1);
                if ($morpho == "Maj"){
                    $premiere_lettre = $mot[0];     #Vérifier si le mot a une majuscule pour pouvoir faire des traitements pour les noms propres par exemple
                    if (strstr("ABCDEFGHIJKLMNOPQRSTUVWXYZ", $premiere_lettre)){
                        $particularite = "Maj?";
                        $trouve = true;
                    }
                } else {                #Traitement de la majuscule
                    $longueur = strlen($morpho);
                    $debut_mot = substr($mot, 0,$longueur-1);
                    if(strstr($morpho, $debut_mot)){
                        $particularite = $morpho."?";
                        $trouve = true;
                    }
                }
            }
        }
        if($trouve == false){
            $particularite = "none";
            $trouve = true;
        }
    }
    return $particularite;
}
?>