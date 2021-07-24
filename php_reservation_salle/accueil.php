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
        <section id="boutons">
            <button type="button" class="filtre"><i class="fas fa-filter"></i></button>
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

            <input type="button" id="modif_date" value="Modifier la date"/>

            <div id="tableau">

            </div>

            <button type="button" id="mode_calendrier" ><i class="fas fa-calendar-alt"></i> Passer en mode calendrier</button>
        </section>
        <section id="zone_reservation">
            <div id="informations">

            </div>
            <div id="carte_calendrier">
                <div class="map">
                <input type="image" id="plan" src="Images\batiment_stendhal.png"  alt="" usemap="#Plan" width="990" height="900">
                    <map name="Plan">
                        <?php
                        include_once("connexion2.php");
                        $requete = "SELECT shape, coords, image, alt FROM plan";
                        if ($reponse=$bdd2->query($requete)) {
                            while ($enr = $reponse->fetch()) {
                                echo "<area shape=" . $enr['shape'] . " coords=" . $enr['coords'] . " href=". $enr['image'] . " alt=" . $enr['alt'] . " />" . "\n";
                            }
                        }
                        ?>

                    </map>
                </div>
            </div>

        <script>
            //Cacher et montrer le mini calendrier
            $("#modif_date").on("click", function() {
                if ($("#tableau").hasClass('open')) {
                    $("#tableau").hide();
                    $("#tableau").removeClass('open');
                } else {
                    $("#tableau").show();
                    $("#tableau").addClass('open');
                }
            });

            var body = $("body");

            body.on("click","#modif_date", function(){
                var currentMonth = $("#currentMonth").val();
                var currentYear = $("#currentYear").val();
                var currentDay = $("#currentDay").val();
                $.ajax({
                    url : 'traitement_calendrier.php',
                    method : 'POST', //methode d'envoi, de quelle façon sont envoyées les données
                    dataType : 'html', // on reçoit du HTML en réponse, format de récéption des données
                    data : "currentMonth="+currentMonth + "&currentYear=" + currentYear + "&currentDay="+currentDay,
                    success : function(reponse){
                        //traitement de la réponse
                        $("#tableau").html(reponse);
                    }
                })
            });

            body.on("click",".bouton_calendrier", function(){
                var currentMonth = $("#currentMonth").val();
                var currentYear = $("#currentYear").val();
                var currentDay = $("#currentDay").val();
                var id_bouton = $(this).attr("id");
                $.ajax({
                    url : 'traitement_calendrier.php',
                    method : 'POST', //methode d'envoi, de quelle façon sont envoyées les données
                    dataType : 'html', // on reçoit du HTML en réponse, format de récéption des données
                    data : "currentMonth="+currentMonth + "&currentYear=" + currentYear + "&currentDay="+currentDay + "&"+id_bouton+"="+id_bouton,
                    success : function(reponse){
                        //traitement de la réponse
                        $("#tableau").html(reponse);
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
            })

            //Montrer et cacher le filtre
            $('.filtre').on("click", function(){
                if ($(".menu_filtre").hasClass('open')) {
                    $(".menu_filtre").hide();
                    $(".menu_filtre").removeClass('open');
                } else {
                    $(".menu_filtre").show();
                    $(".menu_filtre").addClass('open');
                }
            })


        </script>

    </body>
</html>
