<?php
###############################################################################################
######Permet d'utiliser toutes les fonctions présentes dans le script php "fonctions.php"######
include_once("fonctions.php");
###############################################################################################
#Ajouter à la bd le lexique ajouté par l'utilisateur

if (isset($_POST['lexique'])) {
    $lexique = urldecode($_POST["lexique"]);
    $lignes_lex = explode("\n", $lexique);
    foreach ($lignes_lex as $ligne) {
        if (verifie($ligne) == false) {          #Vérifie que la ligne existe dans la base de donnée
            ajoute($ligne);                     #Ajoute la ligne dans la base de donnée si elle n'y existe pas
        }
    }
    #Supprimer les lignes effacées par l'utilisateur
    supprime($lignes_lex);
}

if (isset($_POST['BR'])) {
    $br = urldecode($_POST["BR"]);
    $lignes_br = explode("\n", $br);
    foreach ($lignes_br as $ligne) {
        if (verifie($ligne) == false) {     #Vérifie que la ligne existe dans la base de donnée
            ajoute($ligne);                 #Ajoute la ligne dans la base de donnée si elle n'y existe pas
        }
    }
    supprime($lignes_br);
}

    ?>