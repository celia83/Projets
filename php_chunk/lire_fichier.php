<?php
    //lit le contenu du fichier passé en post
    //et affiche ce contenu

    $file = 'corpus/'.$_POST['fichier']; //récupère le nom du fichier passé en POST (avec le chemin concaténé)
    //vérifier si le fichier existe
    if (is_file($file)){
        //ouvrir le fichier
        $hf = fopen($file, "r");
        while ($ligne=fgets($hf)){ //fgets = obtenir une chaine dans le fichier, tant que je peux lire je le fais
            print $ligne;
        }
        fclose($hf);
    } else {
        print "Erreur";
    }
?>
