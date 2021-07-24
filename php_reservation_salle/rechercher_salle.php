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
    <title>Mooking : Rechercher une salle</title>
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
<section id ="boutons">
    <input type="button" id="modif_date" value="Modifier la date"/>
    <?php
    // Récuperation des variables passées, on donne soit année; mois; année+mois
    if(!isset($_GET['mois'])) $num_mois = date("n"); else $num_mois = $_GET['mois'];
    if(!isset($_GET['annee'])) $num_an = date("Y"); else $num_an = $_GET['annee'];

    // pour pas s'embeter a les calculer a l'affchage des fleches de navigation...
    if($num_mois < 1) { $num_mois = 12; $num_an = $num_an - 1; }
    elseif($num_mois > 12) {	$num_mois = 1; $num_an = $num_an + 1; }

    // nombre de jours dans le mois et numero du premier jour du mois
    $int_nbj = date("t", mktime(0,0,0,$num_mois,1,$num_an));
    $int_premj = date("w",mktime(0,0,0,$num_mois,1,$num_an));

    // tableau des jours, tableau des mois...
    $tab_jours = array("","Lu","Ma","Me","Je","Ve","Sa","Di");
    $tab_mois = array("","Janvier","Fevrier","Mars","Avril","Mai","Juin","Juillet","Aout","Septembre","Octobre","Novembre","Decembre");

    $int_nbjAV = date("t", mktime(0,0,0,($num_mois-1<1)?12:$num_mois-1,1,$num_an)); // nb de jours du moi d'avant
    $int_nbjAP = date("t", mktime(0,0,0,($num_mois+1>12)?1:$num_mois+1,1,$num_an)); // b de jours du mois d'apres

    // on affiche les jours du mois et aussi les jours du mois avant/apres, on les indique par une * a l'affichage on modifie l'apparence des chiffres *
    $tab_cal = array(array(),array(),array(),array(),array(),array()); // tab_cal[Semaine][Jour de la semaine]
    $int_premj = ($int_premj == 0)?7:$int_premj;
    $t = 1; $p = "";
    for($i=0;$i<6;$i++) {
        for($j=0;$j<7;$j++) {
            if($j+1 == $int_premj && $t == 1) { $tab_cal[$i][$j] = $t; $t++; } // on stocke le premier jour du mois
            elseif($t > 1 && $t <= $int_nbj) { $tab_cal[$i][$j] = $p.$t; $t++; } // on incremente a chaque fois...
            elseif($t > $int_nbj) { $p="*"; $tab_cal[$i][$j] = $p."1"; $t = 2; } // on a mis tout les numeros de ce mois, on commence a mettre ceux du suivant
            elseif($t == 1) { $tab_cal[$i][$j] = "*".($int_nbjAV-($int_premj-($j+1))+1); } // on a pas encore mis les num du mois, on met ceux de celui d'avant
        }
    }
    ?>

    <table id="mini_calendrier" hidden>
        <tr><td colspan="7" align="center"><a href="rechercher_salle.php?mois=<?php echo $num_mois-1; ?>&amp;annee=<?php echo $num_an; ?>"><<</a>&nbsp;&nbsp;<?php echo $tab_mois[$num_mois];  ?>&nbsp;&nbsp;<a href="rechercher_salle.php?mois=<?php echo $num_mois+1; ?>&amp;annee=<?php echo $num_an; ?>">>></a></td></tr>
        <tr><td colspan="7" align="center"><a href="rechercher_salle.php?mois=<?php echo $num_mois; ?>&amp;annee=<?php echo $num_an-1; ?>"><<</a>&nbsp;&nbsp;<?php echo $num_an;  ?>&nbsp;&nbsp;<a href="rechercher_salle.php?mois=<?php echo $num_mois; ?>&amp;annee=<?php echo $num_an+1; ?>">>></a></td></tr>
        <?php
        echo'<tr>';
        for($i = 1; $i <= 7; $i++){
            echo('<td>'.$tab_jours[$i].'</td>');
        }
        echo'</tr>';

        for($i=0;$i<6;$i++) {
            echo "<tr>";
            for($j=0;$j<7;$j++) {
                echo "<td".(($num_mois == date("n") && $num_an == date("Y") && $tab_cal[$i][$j] == date("j"))?' style="color: #FFFFFF; background-color: #000000;"':null).">".((strpos($tab_cal[$i][$j],"*")!==false)?'<font color="#aaaaaa">'.str_replace("*","",$tab_cal[$i][$j]).'</font>':$tab_cal[$i][$j])."</td>";
            }
            echo "</tr>";
        }
        ?>
    </table>
    <input type="button" id="mode_calendrier" value="Passer en mode calendrier"/>
</section>
<section id="zone_reservation">
    <div id ="informations">

    </div>
    <div id = "carte_calendrier">

    </div>
</section>
<script>
    $("#modif_date").on("click", function() {
            if ($("#mini_calendrier").hasClass('open')) {
                $("#mini_calendrier").hide();
                $("#mini_calendrier").removeClass('open');
            } else {
                $("#mini_calendrier").show();
                $("#mini_calendrier").addClass('open');
            }
        })

    //Montrer et cacher le menu burger
    $('.fa-bars').on("click", function(){
        if ($("#menu_burger").hasClass('open')) {
            $("#menu_burger").hide();
            $("#menu_burger").removeClass('open');
        } else {
            $("#menu_burger").show();
            $("#menu_burger").addClass('open');
        }
    })
</script>
</body>
</html>

