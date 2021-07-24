<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <!-- Entête de la page-->
    <title></title>
    <meta charset="UTF-8"/> <!-- dit en quel encodage je suis-->
    <link rel="stylesheet" href = "style.css"/> <!-- donne la feuille de style en css utilisée-->
    <script type="text/javascript" src="jquery-3.4.1.js"></script> <!--appel de JQuery-->
</head>
<body>
<header id="header_connexion">
    <h1 id="titre_connexion">Mooking</h1>
</header>
<section id="zone_connexion">
    <article id= "form_connexion">
        <h2>Connexion</h2>
        <form  action="index.php" method="post">
            <div id ="idf">
                <section class="idf_mdp" >Identifiant</section>
                <input type = "text" name="identifiant"/>
            </div>
            <div id = "mdp">
                <section class="idf_mdp">Mot de passe</section>
                <input type = "password" name = "mdp"/>
            </div>
            <div id="bouton_connexion">
                <input id="soumettre" type="submit" value="Connexion"/>
            </div>
        </form>
    </article>
</section>

    <?php
    if (isset ($_POST["identifiant"]) && isset ($_POST["mdp"])) {
        $login = $_POST["identifiant"];
        $mdp = $_POST["mdp"];
        include_once("connexion.php");
        $requete = "SELECT mdp FROM mooking WHERE login = '".$login."'";
        if ($reponse = $bdd->query($requete)) {
            $enr = $reponse->fetch();
            if ($enr==false) {
                echo "Identifiant ou mot de passe incorrect.";
            } else {
                if ($enr['mdp'] == $mdp) {
                    $_SESSION['login'] = $login;
                    header('Location: accueil.php');
                } else {
                    print "Mot de passe incorrect.";
                }
            }
        } else {
            print "Erreur de connexion à la base de données.";
        }
    }
    ?>
</body>
</html>
