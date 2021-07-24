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
    <title>Mooking</title>
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

<section id="zone"><p id="t">Notez la personne qui a réservé la salle avant vous</p>
    <hr/>
    <p>(La personne a-t-elle libérée la salle à l'heure ? Etait-elle cordiale ?)</p>
    <br/><br/>
    <div id="note">
        <br/><br/>
        <img id="tde_1" src="Images\etoile.png" class="tde"/>
        <img id="tde_2" src="Images\etoile.png" class="tde"/>
        <img id="tde_3" src="Images\etoile.png" class="tde"/>
        <img id="tde_4" src="Images\etoile.png" class="tde"/>
        <img id="tde_5" src="Images\etoile.png" class="tde"/>
    </div>
    <br/><br/>
    <input type="button" id="envoie" value="Envoyer"/>
    <br/><br/>
</section>

<script>
    $(".tde").mouseover(function(){
        var nbr = $(this).prop('id').substring(4);
//couleur jaune dans le fond transparent de l'étoile
        $(this).css( "backgroundColor", "#E0E001" );
//Toutes les étoiles en-dessous de nombre sont colorées en jaune.
        $( ".tde").slice(0, nbr).css( "backgroundColor", "#E0E001" );
//et toutes celles au-dessus de nombre en gris
        $( ".tde").slice(nbr).css( "backgroundColor", "RGB(51, 51, 51" );
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

    $("#envoie").on("click", function() {
        alert("La notation a été prise en compte");
        location.href="messagerie.php";
    })
</script>

</body>
</html>