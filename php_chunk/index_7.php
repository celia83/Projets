<!DOCTYPE html>
<html>
<head>
    <!-- Entête de la page-->
    <title>Système de Chunks</title>
    <meta charset="UTF-8"/> <!-- dit en quel encodage je suis-->
    <link rel="stylesheet" href = "style.css"/> <!-- donne la feuille de style en css utilisée-->
    <script type="text/javascript" src="jquery-3.4.1.js"></script> <!--appel de JQuery-->
</head>
<body>
<header> <h1>Système de chunks</h1> </header>
<?php
###############################################################################################
######Permet d'utiliser toutes les fonctions présentes dans le script php "fonctions.php"######
include_once("fonctions.php");
###############################################################################################
?>

<!-- Télécharger un fichier sur le serveur -->
<form id = "telecharge_fichier" method ='post' action = 'index_7.php' enctype="multipart/form-data">
    <input type="file" name="fichier_telecharge"/>
    <input id ="telecharge" type ='submit' value = 'Télécharger le fichier'/>
    <?php
    if (isset($_FILES['fichier_telecharge'])){
        $chemin = $_FILES['fichier_telecharge']['tmp_name'];
        $nom = $_FILES['fichier_telecharge']['name'];
        move_uploaded_file($chemin, "corpus/".$nom);
    }
    ?>
</form>

<!-- Boutons de sauvegarde du lexique et de la base de règles-->
<button type ="button" id = "enregistre_lexique">Sauvegarder le lexique</button>
<button type ="button" id = "enregistre_br">Sauvegarder la base de règles</button>

<!--Remplir les textarea qui contiendront le lexique, la base de règles et le texte-->
<form id="form_donnees"  action ="index_7.php" method="post">
    <div id="zone_donnees">
        <label for="lexique">Lexique</label>
        <textarea id = "lexique" name = "lexique" cols = "30" rows = "30"><?php
            if (isset($_POST['lexique'])) {
                #Réafficher le lexique présent dans la bd après l'envoi du formulaire
                $lex= charge_lex();
                foreach ($lex as $mot=>$mdc){
                    echo $mot.":".$mdc."\n";
                }
            } else { #Charger le lexique à l'ouverture de la page
                $lex= charge_lex();
                foreach ($lex as $mot=>$mdc){
                    echo $mot.":".$mdc."\n";
                }
            }

            ?></textarea>


        <label for="BR">Base de règles</label>
        <textarea id="BR" name ="BR" cols = "30" rows = "30"><?php
            if (isset($_POST['BR'])) {
                #Réafficher la base de règles présente dans la bd après l'envoi du formulaire
                $br = charge_rb();
                foreach ($br as $mdc => $chunk) {
                    echo $mdc . "->" . $chunk . "\n";
                }
            } else {    #Charger la base de règles à l'ouverture de la page
                $br = charge_rb();
                foreach ($br as $mdc => $chunk) {
                    echo $mdc . "->" . $chunk . "\n";
                }
            }
            ?></textarea>
        <section id ="zone_texte">
            <label for="texte">Texte à analyser</label>
            <article>
                <select id = "textes_serveur">
                    <option></option>
                    <?php
                    //calcule et affiche la liste des fichiers du dossier corpus présent sur le serveur
                    $source = "corpus";
                    //vérifie que le dossier corpus est bien un dossier
                    if (is_dir($source)){
                        //lecture du dossier
                        $tabFiles = scandir($source);
                        //affichage du contenu dans une liste déroulante
                        foreach ($tabFiles as $file){
                            if ($file!="."&&$file!="..") { //pour ne pas faire apparaitre le dossier courant et le dossier parent
                                print "\t\t\t<option value='$file'>$file </option>\n";
                            }
                        }
                    } else {
                        print "$source n'est pas un dossier.";
                    }
                    ?>
                </select>
                <input id = 'ok' type ='button' value = 'Charger le texte'/>
                <input id = 'modif' type ='button' value = 'Enregistrer les modifications'/>
            </article>
            <textarea id ="texte" name = "texte" cols = "30" rows = "30" ><?php
                if (isset($_POST['texte'])) {
                    print stripslashes($_POST['texte']);
                }
                ?></textarea>
        </section>
    </div>
    <input id ="analyser" type = "submit" name = "go" value = Analyser />
</form>

<form id="resultat" action = "exporter_resultat.php" method="post">
            <textarea name = "sortie" id = "sortie" cols = "30" rows = "30"><?php
                ##################Analyse en chunks#####################
                if (isset($_POST['lexique'])){
                    $RB = charge_rb();
                    $lexique = charge_lex();
                    $texte = normalisation($_POST['texte']);#normalisation du texte (mettre des espaces autour des éléments)
                    $tabtok = explode(" ", $texte) ;
                    $chunk_prec = "";
                    foreach ($tabtok as $mot){  # Lire chaque mot
                        $indice = array_search($mot, $tabtok);      #Déterminer l'emplacement du mot pour ouvrir ou fermer le crochet au début et à la fin du texte analysé
                        $longueur = count($tabtok)-1;       #Déterminer la longueur du texte pour pouvoir savoir quand on est à la fin de celui ci pour le traitement mentionné dans el commentaire précédent
                        $particularite = FindParticularity($mot, $lexique);     #Déterminer si on a un mot qui finit ou début par une série de lettres inscrite dans le lexique
                        $mot = trim($mot);  #Enlever les espaces autour du mot
                        if (isset($lexique[strtolower($mot)]) || isset($lexique[$particularite])){  #Vérifier que le mot est dans le lexique en enlevant les majuscules ou que le mot a une majuscule
                            if (isset ($lexique[strtolower($mot)])){
                                $categorie_chunk = trim($lexique[strtolower($mot)]);    #Stocker la catégorie de chunk associée au mot en enlevant les espaces autour
                            } else {
                                $categorie_chunk = trim($lexique[$particularite]);
                            }
                            foreach ($RB as $regle => $resultat){ #trouver la règle associée à la catégorie de chunk et appliquer le résultat de la règle
                                $nbPart_Parts = nbParties_et_Parties($regle);#Etablir combien de morceaux composent la règle
                                if ($nbPart_Parts[0]==1){   #Dans le cas où la règle est en 1 partie
                                    if ($regle == $categorie_chunk){    #Si le marqueur de chunk du mot est égal à la règle alors on applique le résultat
                                        $regle_juste = true;
                                        echo resultat($resultat, $mot, $regle_juste, $indice,$longueur);     # Ce qui est affiché dans la textarea
                                        $chunk_prec = $categorie_chunk;     #Stocker la catégorie actuelle pour pouvoir traiter les mots suivants en fonction de celle ci
                                    }
                                } else if ($nbPart_Parts[0]==2){    # Dans le cas où la règle est en 2 parties
                                    $regle_cut=explode ("&", $regle);   #Découper les deux parties
                                    $regle_part1  = $nbPart_Parts[1];              #Stocker la partie 1
                                    $regle_part2  = $nbPart_Parts[2];              #Stocker la partie 2
                                    if ($regle_part1 == $categorie_chunk || $regle_part1 == $mot){         #On vérifie que la première partie de la règle est bien le marqueur de chunk qu'on cherche
                                        if (strstr($regle_part2, "⌐")){     #Cas où la partie 2 est niée
                                            $regle_part2= explode ("⌐", $regle_part2);      #Stocker la partie 2 sans la négation
                                            $categorie_chunk2 = $regle_part2 [1];           #Stocker dans une chaine le chunk présent dans le tableau créé après avoir enlevé la négation
                                            if ($chunk_prec!=$categorie_chunk2){             #Le chunk précédent doit être différent du chunk de la partie 2
                                                $regle_juste = true;
                                                echo resultat($resultat, $mot, $regle_juste, $indice, $longueur);            # Ce qui est affiché dans la textarea
                                                $chunk_prec = $categorie_chunk;         #Stocker la catégorie actuelle pour pouvoir traiter les mots suivants en fonction de celle ci
                                            } else {                                    # Si le chunk est identique on imprime seulement le mot
                                                $regle_juste = false;
                                                echo resultat($resultat, $mot, $regle_juste, $indice, $longueur);       # Ce qui est affiché dans la textarea
                                                $chunk_prec = $categorie_chunk;         #Stocker la catégorie actuelle pour pouvoir traiter les mots suivants en fonction de celle ci
                                            }
                                        } else {                                    #Cas où la partie 2 n'est pas niée
                                            if ($chunk_prec==$regle_part2){         #Le chunk précédent doit être identique au chunk de la partie 2
                                                $regle_juste = true;
                                                echo resultat($resultat, $mot, $regle_juste, $indice, $longueur);         # Ce qui est affiché dans la textarea
                                                $chunk_prec = $categorie_chunk;         #Stocker la catégorie actuelle pour pouvoir traiter les mots suivants en fonction de celle ci
                                            } else {                                    # Si le chunk est différent on imprime seulement le mot
                                                $regle_juste = false;
                                                echo resultat($resultat, $mot, $regle_juste, $indice, $longueur);    # Ce qui est affiché dans la textarea
                                                $chunk_prec = $categorie_chunk;         #Stocker la catégorie actuelle pour pouvoir traiter les mots suivants en fonction de celle ci
                                            }
                                        }
                                    }
                                } else if ($nbPart_Parts[0]==3){   #Dans le cas où la règle est en 3 parties
                                    $regle_cut=explode ("&", $regle);   #Découper les trois parties
                                    $regle_part1  = $nbPart_Parts[1];              #Stocker la partie 1
                                    $regle_part2  = $nbPart_Parts[2];
                                    $regle_part3  = $nbPart_Parts[3];
                                    if ($regle_part1 == $categorie_chunk) {         #On vérifie que la première partie de la règle est bien le marqueur de chunk qu'on cherche
                                        if (strstr($regle_part2, "⌐")){     #Cas où la partie 2 est niée
                                            $regle_part2= explode ("⌐", $regle_part2);      #Stocker la partie 2 sans la négation dans un tableau
                                            $categorie_chunk2 = $regle_part2 [1];           #Stocker dans une chaine le chunk présent dans le tableau créé après avoir enlevé la négation
                                            if (strstr($regle_part3, "⌐")){     # Cas où la partie 3 est niée
                                                $regle_part3= explode ("⌐", $regle_part3);      #Stocker la partie 3 sans la négation dans un tableau
                                                $categorie_chunk3 = $regle_part3 [1];           #Stocker dans une chaine le chunk présent dans le tableau créé après avoir enlevé la négation
                                                if ($chunk_prec !=$categorie_chunk2 && $chunk_prec !=$categorie_chunk3){
                                                    $regle_juste = true;
                                                    echo resultat($resultat, $mot, $regle_juste, $indice, $longueur);
                                                    $chunk_prec = $categorie_chunk;
                                                } else {
                                                    $regle_juste = false;
                                                    echo resultat($resultat, $mot, $regle_juste, $indice, $longueur);
                                                    $chunk_prec = $categorie_chunk;
                                                }
                                            } else {                                    #Cas où la partie 3 n'est pas niée
                                                if ($chunk_prec !=$categorie_chunk2 && $chunk_prec ==$regle_part3){
                                                    $regle_juste = true;
                                                    echo resultat($resultat, $mot, $regle_juste, $indice, $longueur);
                                                    $chunk_prec = $categorie_chunk;
                                                } else {
                                                    $regle_juste = false;
                                                    echo resultat($resultat, $mot, $regle_juste, $indice, $longueur);
                                                    $chunk_prec = $categorie_chunk;
                                                }
                                            }
                                        } else {                                        #Cas où la partie 2 n'est pas niée
                                            if (strstr($regle_part3, "⌐")){     # Cas où la partie 3 est niée
                                                $regle_part3= explode ("⌐", $regle_part3);      #Stocker la partie 3 sans la négation dans un tableau
                                                $categorie_chunk3 = $regle_part3 [1];           #Stocker dans une chaine le chunk présent dans le tableau créé après avoir enlevé la négation
                                                if ($chunk_prec ==$regle_part2 && $chunk_prec !=$categorie_chunk3){
                                                    $regle_juste = true;
                                                    echo resultat($resultat, $mot, $regle_juste, $indice, $longueur);
                                                    $chunk_prec = $categorie_chunk;
                                                } else {
                                                    $regle_juste = false;
                                                    echo resultat($resultat, $mot, $regle_juste, $indice, $longueur);
                                                    $chunk_prec = $categorie_chunk;
                                                }
                                            } else {                                    #Cas où la partie 3 n'est pas niée
                                                if ($chunk_prec ==$regle_part2 && $chunk_prec !=$regle_part3){
                                                    $regle_juste = true;
                                                    echo resultat($resultat, $mot, $regle_juste, $indice, $longueur);
                                                    $chunk_prec = $categorie_chunk;
                                                } else {
                                                    $regle_juste = false;
                                                    echo resultat($resultat, $mot, $regle_juste, $indice, $longueur);
                                                    $chunk_prec = $categorie_chunk;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            echo $mot." ";
                        }
                    }

                } else {
                    echo 'Cliquer sur "Analyser" pour lancer '."l'analyse du texte.";
                }
                ?></textarea>
    <input id ="exporter" type ='submit' value = "Exporter l'analyse"/>
</form>

<script>

    //Enregistrement des modifications du lexique
    $("#enregistre_lexique").on("click", function () {
        var lexique = $("#lexique").val();
        var lexique_normalise = encodeURIComponent(lexique);
        $.ajax({
            url : 'traitement_ajout_suppression.php',
            method : 'POST',
            data : 'lexique=' + lexique_normalise,
            dataType : 'html',
            success : function (reponse){
                console.log(reponse);
            }
        });
    });

    //Enregistrement des modifications de la base de règles
    $("#enregistre_br").on("click", function () {
        var br = $("#BR").val();
        var br_normalise = encodeURIComponent(br);
        $.ajax({
            url : 'traitement_ajout_suppression.php',
            method : 'POST',
            data : 'BR=' + br_normalise,
            dataType : 'html',
            success : function (reponse){
                console.log(reponse);
            }
        });
    });

    //Afficher le contenu d'un texte présent sur le serveur
    $("#ok").on("click", function () {
        var fichier = $("#textes_serveur > option:selected").val();
        $.ajax({
            url : 'lire_fichier.php',
            method : 'POST',
            data : 'fichier=' + fichier,
            dataType : 'html',
            success : function (reponse){
                $("#texte").val(reponse);
            }
        });
    });

    //Enregistrer le contenu modifié du texte
    $("#modif").on("click", function () {
        var new_text = $("#texte").val();
        var fichier = $("#textes_serveur > option:selected").val();
        $.ajax({
            url: 'enregistrer_fichier.php',
            method: 'POST',
            data: 'fichier=' + fichier + '&new_text=' + new_text,
            dataType: 'html',
            success: function (resultat) {
                alert(resultat);
            }
        })
    })

</script>
</body>
</html>



