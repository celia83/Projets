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
    <title>Mooking : Messagerie</title>
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
<section id=section_titre_messagerie">
    <h2 id="titre_messagerie">Messagerie</h2>
</section>
<section id="zone_reservation">
    <div id ="informations">
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
        <div id="zone_boutons_messagerie">
            <nav id = "boutons_messagerie">
                <ul>
                    <hr class = "sep_messagerie"/>
                    <li class="boutons_messagerie" id = "boite_reception"><a href="#">Boite de réception</a></li>
                    <hr class = "sep_messagerie"/>
                    <li class="boutons_messagerie" id = "brouillons"><a href="#">Brouillons</a></li>
                    <hr class = "sep_messagerie"/>
                    <li class="boutons_messagerie" id = "messages_supprimes"><a href="#">Messages supprimés</a></li>
                    <hr class = "sep_messagerie"/>
                    <li class="boutons_messagerie" id = "ecrire_message"><a href="#">Envoyer un message</a></li>
                    <hr class = "sep_messagerie"/>
                </ul>
            </nav>
        </div>
    </div>
    <div id ="carte_calendrier">
        <form class="form_messagerie"  action ="messagerie.php" method="post" hidden >
            <fieldset class = "zone_entete">
                <div class = "entete_messagerie">
                    <label class="messagerie_labels" for="expediteur" > De : </label>
                    <input class= "dest_exp_obj" type="email" id = "expediteur" name = "expediteur" required>
                </div>
                <div class="entete_messagerie">
                    <label class="messagerie_labels" for = "destinataire"> A : </label>
                    <input class= "dest_exp_obj" type="email" id = "destinataire" name = "destinataire" required>
                </div>
                <div class="entete_messagerie">
                    <label class="messagerie_labels" for ="objet">Objet :</label>
                    <input class= "dest_exp_obj" type="text"  id = "objet" name = "objet" required>
                </div>
            </fieldset>
            <div>
                <label class="messagerie_labels" for ="message"></label>
                <textarea class = "zone_message" id = "message" name = "message"></textarea>
            </div>
            <div class="supp_rep">
                <input type="submit" id="supprimer" value="Supprimer"/>
                <input type="submit" id="repondre" value="Répondre"/>
            </div>
        </form>
    </div>
</section>
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

        //Bouton envoyer un message
    $("#ecrire_message").on("click", function(){
        $(".form_messagerie").show();
    })

    $("#boite_reception").on("click", function(){
        $(".form_messagerie").hide();
    })

</script>
</body>
</html>
