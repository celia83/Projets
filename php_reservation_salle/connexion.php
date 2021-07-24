<?php
try { /* tentative de connexion à la BD*/
    $bdd=new PDO('mysql:host=localhost;dbname=marticel','marticel','Lmcmcjl83');
    /* print "La base est ouverte !<br/>\n"; */
}
catch (Exception $e) {
    die('Erreur : '.$e->getMessage());
    }
?>
