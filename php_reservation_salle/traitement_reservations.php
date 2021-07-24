<?php
include_once("connexion.php");
$requete = "SELECT * FROM reservation;";
if ($reponse = $bdd->query($requete)) {
    $resultat = array();
    $i = 1;
    while($enr=$reponse->fetch()) {
        $salle = $enr['salle'];
        $mois = $enr['mois'];
        $heure = $enr['heure'];
        $jour= $enr['jour'];
        $formation = $enr['formation'];
        $cours = $enr['cours'];
        $frequence = $enr['frequence'];
        $tableau= array('salle' => $salle, 'mois' => $mois, 'jour' => $jour, 'heure' => $heure,'formation' => $formation,'cours' => $cours,'frequence' => $frequence);
        $resultat["resa$i"] = $tableau;
        $i++;
    }
    header("Content-Type: application/javascript");
    echo json_encode($resultat);
}

?>
