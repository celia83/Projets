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
    <title>Mooking : Changer de mot de passe</title>
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

<section id="zone_chg_mdp">
    <article id="form_changemdp">
        <h2>Changer votre mot de passe</h2>
        <hr/>
        <form  action="changer_mdp.php" method="post">
            <div id ="ancien_mdp">
                <section class="ancien_motdepasse nom_bouton" >Ancien mot de passe</section>
                <input class= "zonetext_change_mdp" type = "password" name="ancienmdp"/>
            </div>
            <div id = "nouveau_mdp">
                <div id=nouveau">
                    <section class="nouv_mdp nom_bouton">Nouveau mot de passe</section>
                    <input class= "zonetext_change_mdp" type = "password" name = "newmdp"/>
                </div>
                <div id=confirmer">
                    <section class="retaper_mdp nom_bouton">Confirmer le nouveau mot de passe</section>
                    <input class= "zonetext_change_mdp" type = "password" name = "retapermdp"/>
                </div>
            </div>
            <div id="bouton_enregistrer">
                <input id="enregistrer" type="submit" value="ENREGISTRER"/>
            </div>
        </form>
    </article>
</section>
<?php
if (isset ($_POST["ancienmdp"]) && isset ($_POST["newmdp"]) && isset ($_POST["retapermdp"])) {
    $ancien_mdp = $_POST["ancienmdp"];
    $new_mdp = $_POST["newmdp"];
    $mdp_confirmer = $_POST["retapermdp"];
    $login = $_SESSION["login"];
    include_once("connexion.php");
    $requete = "SELECT mdp FROM mooking WHERE login = '".$login."'";
    if ($reponse = $bdd->query($requete)) {
        $enr = $reponse->fetch();
    }
    $mdp = $enr['mdp'];
    if ($new_mdp == $mdp_confirmer && $ancien_mdp == $mdp){
        include_once("connexion.php");
        $requete = "UPDATE mooking SET mdp = '".$new_mdp."' WHERE login = '".$login."'";
        if ($reponse = $bdd->query($requete)) {
            print "Le mot de passe a été modifié.";
        }
    } else {
        print "L'un des champs est mal rempli.";
    }
}

?>
<script>
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
