<?php
try {    /* Tentative de connexion à la BD*/
    $bdd2=new PDO('mysql:host=localhost;dbname=giroudo','giroudo','I3LUGA382020');
        /* print "La base est ouverte !<br/>\n"; */
}
catch (Exception $e) {
    die('Erreur : '.$e->getMessage());
    print "Accès impossible à la base !<br/>\n";
}
?>