<?php
//Vérifier que l'on est connecté
session_start();
if (!isset($_SESSION["login"])){
    header('Location: index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
    <!-- Entête de la page-->
    <title>Mooking : Mes Réservations</title>
    <meta charset="UTF-8"/> <!-- dit en quel encodage je suis-->
    <link rel="stylesheet" href = "style.css"/> <!-- donne la feuille de style en css utilisée-->
    <script type="text/javascript" src="jquery-3.4.1.js"></script> <!--appel de JQuery-->
    <script src="https://kit.fontawesome.com/bafcb5074c.js" crossorigin="anonymous"></script>
</head>
<body>
<header class="header_general">
    <h1><a class = "titre_general" href="accueil.php">MK</a></h1>
    <i class="fas fa-bars"></i>
</header>
<div id= "menu">
    <nav id = "menu_burger" hidden>
        <ul>
            <li id = "mes_reservations"><a href="mes_reservations.php">Mes Réservations</a></li>
            <li id = "rechercher_salle"><a href="rechercher_salle.php">Rechercher une salle</a></li>
            <li id = "messagerie"><a href="messagerie.php">Messagerie</a></li>
            <li id = "changer_mdp"><a href="changer_mdp.php">Changer de mot de passe</a></li>
            <li id = "deconnexion"><a href="deconnexion.php">Déconnexion</a></li>
        </ul>
    </nav>
</div>
<section id="boutons">
    <button type="button" class="filtre filtre_resa"><i class="fas fa-filter"></i></button>
    <div class="filter_menu">
        <nav class="menu_filtre" hidden>
            <div class="checkbox_filtre">
                <input type="checkbox" id="acces_handicape" name="acces_handicape" checked>
                <label class="labels_filtre" for="acces_handicape">Accès handicapé</label>
            </div>
            <div class="checkbox_filtre">
                <input type="checkbox" id="disponibles" name="disponibles" >
                <label class="labels_filtre" for="disponibles">Disponibles</label>
            </div>
            <div class="checkbox_filtre">
                <input type="checkbox" id="avec_projecteur" name="avec_projecteur" >
                <label class="labels_filtre" for="avec_projecteur">Avec rétroprojecteur</label>
            </div>
            <div class="checkbox_filtre">
                <input type="checkbox" id="salles_infos" name="salles_infos" >
                <label class="labels_filtre" for="salles_infos">Salles Informatiques</label>
            </div>
        </nav>
    </div>

</section>
<section id="zone_reservation">
    <div id ="boutons_mes_reservations">
        <article id="nom_prenom">
            <!--Afficher le nom et le prénom associés à la personne qui s'est connectée-->
            <?php
            include_once("connexion.php");
            $requete = "SELECT prenom, nom FROM mooking WHERE login = '".$_SESSION["login"]."'";
            if ($reponse = $bdd->query($requete)) {
                $enr = $reponse->fetch();
                $prenom = $enr['prenom'];
                $nom = $enr['nom'];
                echo $prenom." ".$nom;
            } else {
                print 'Erreur';
            }
            ?>
        </article>
        <hr/>
        <div id="zone_boutons">
            <button type="button" class="boutons_gestion" id="dupliquer">DUPLIQUER UNE RESERVATION</button>
            <button type="button" class="boutons_gestion" id="annuler">ANNULER UNE RESERVATION</button>
            <button type="button" class="boutons_gestion" id="enregistrer_cal">ENREGISTRER LE CALENDRIER</button>
            <button type="button" class="boutons_gestion" id="imprimer">IMPRIMER</button>
            <button type="button" class="boutons_gestion" id="envoyer">ENVOYER PAR MAIL</button>
        </div>
    </div>
    <div id ="planning">
        <?php
        date_default_timezone_set('UTC');
        $today = getdate();
        $month = $today["mon"];     #mois
        $week_day = $today["wday"];     #numéro de jour de la semaine (1 = lundi)
        $year = $today["year"];         #année
        if (isset ($_POST['currentWeek'])){
            $nbday = $_POST['currentWeek'];
        } else {
            $nbday = $today["mday"];    #jour
        }
        if (isset($_POST['suivante'])){
            $nbday +=7;
        } else if (isset($_POST['precedente'])){
            $nbday -=7;
        } else if (isset($_POST['actuelle'])) {
            $nbday = $today["mday"];    #jour
        }

        ?>
        <form name="semaine_suivante" action = "mes_reservations.php" method="post">
            <div id="boutons_planning">
                <button id = "semaine_precedente" type="submit" name = "precedente"><i class="fas fa-chevron-left"></i></button>
                <button id = "semaine_actuelle" type="submit" name = "actuelle">Revenir à la semaine actuelle</i></button>
                <button id ="semaine_suivante" type="submit" name = "suivante"><i class="fas fa-chevron-right"></i></button>
            </div>
            <table id="planning_resa">
                <tr class = "planning_resa">
                    <th class = "planning_resa">Heures</th>
                    <!--Faire apparaitre les jours de la semaine-->
                    <?php
                    $liste_date = array();
                    for ($j=$week_day-1 ; $j>=0 ; $j--){
                        #Jours en français
                        $jour_en = date ("l",mktime(0,0,0, $month, $nbday-$j, $year));
                        switch($jour_en){
                            case $jour_en=="Monday" : $jour_fr = "Lundi";break;
                            case $jour_en=="Tuesday" : $jour_fr = "Mardi";break;
                            case $jour_en=="Wednesday" : $jour_fr = "Mercredi";break;
                            case $jour_en=="Thursday" : $jour_fr = "Jeudi";break;
                            case $jour_en=="Friday" : $jour_fr = "Vendredi";break;
                        }
                        $date_sans_jour = date ("d-m",mktime(0,0,0, $month, $nbday-$j, $year));
                        $date = $jour_fr." ".$date_sans_jour;
                        array_push($liste_date, $date_sans_jour);
                        echo "\t\t\t\t<th class = \"planning_resa\" >".$date."</th>";
                    }
                    for ($k=1 ; $k<=5-$week_day ; $k++){
                        $jour_en = date ("l",mktime(0,0,0, $month, $nbday+$k, $year));
                        switch($jour_en){
                            case $jour_en=="Monday" : $jour_fr = "Lundi";break;
                            case $jour_en=="Tuesday" : $jour_fr = "Mardi";break;
                            case $jour_en=="Wednesday" : $jour_fr = "Mercredi";break;
                            case $jour_en=="Thursday" : $jour_fr = "Jeudi";break;
                            case $jour_en=="Friday" : $jour_fr = "Vendredi";break;
                        }
                        $date_sans_jour = date ("d-m",mktime(0,0,0, $month, $nbday+$k, $year));
                        $date = $jour_fr." ".$date_sans_jour;
                        array_push($liste_date, $date_sans_jour);
                        echo "\t\t\t\t<th class = \"planning_resa\" >".$date."</th>";
                    }
                    ?>
                </tr>
                <!--faire apparaitre le reste du tableau-->
                <?php
                for ($i=8;$i<=20;$i++){
                    $j=$i+1;
                    echo "\t\t\t<tr class = \"planning_resa\" ><td class = \"planning_resa\">".$i."h30<br><br>".$j."h30"."</td>";
                    for ($k = 0; $k <= 4; $k++){
                        $id_date = $liste_date[$k];
                        echo "<td class = 'planning_resa' id = ".$i.$id_date."></td>";
                    }
                }
                ?>
            </table>
            <input type ="text" name = "currentWeek" value ="<?php echo $nbday; ?>" hidden />
        </form>
    </div>
</section>
<script>
    $(document).ready(function(){
        $.ajax({
            url : 'traitement_reservations.php',
            success : function(reponse){
                //traitement de la réponse
                var newreponse = JSON.parse(reponse);
                for (var reservation in newreponse){
                    var infos_resa = newreponse[reservation];
                    var mois = infos_resa.mois;
                    var jour= infos_resa.jour;
                    var heure = infos_resa.heure;
                    var salle = infos_resa.salle;
                    var cours = infos_resa.cours;
                    var formation = infos_resa.formation;
                    var frequence = infos_resa.frequence;
                    var id = heure + jour + "-" + mois;
                    console.log(id);
                    $("#"+id).html ("Salle "+ salle +"-" + heure +"h30<br> " + formation + "<br>"+ cours + "<br>"+ frequence);
                    $("#"+id).css("background-color", "green");
                    $("#"+id).css("text-align", "center");
                }
            }
        })
    });

    //Montrer et cacher le menu burger
    $('.fa-bars').on("click", function(){
        if ($("#menu_burger").hasClass('open')) {
            $("#menu_burger").hide();
            $("#menu_burger").removeClass('open');
        } else {
            $("#menu_burger").show();
            $("#menu_burger").addClass('open');
        }
    });

    //Montrer et cacher le filtre
    $('.filtre').on("click", function(){
        if ($(".menu_filtre").hasClass('open')) {
            $(".menu_filtre").hide();
            $(".menu_filtre").removeClass('open');
        } else {
            $(".menu_filtre").show();
            $(".menu_filtre").addClass('open');
        }
    });

    //
        $("#827-04").html ("Salle D104 - 8h30 à 9h30<br>L1 Scences du Langage<br>Sociolinguistique<br>Tous les lundis");
        $("#827-04").css("background-color", "green");
        $("#827-04").css("text-align", "center");

</script>
</body>
</html>
