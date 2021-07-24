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
        <div class= "infos_salles" id="informations">
            <p class="Salles" >Salle <?php
                include_once("connexion2.php");
                $requete = "SELECT salle FROM batiment";
                if ($reponse=$bdd2->query($requete)) {
                    $enr = $reponse->fetch();
                    if ($_GET['a'] == 1) {
                        echo "A101";
                        $salle = "A101";
                    } elseif ($_GET['a'] == 2) {
                        echo "A103";
                        $salle = "A103";
                    } elseif ($_GET['a'] == 3) {
                        echo "A104";
                        $salle = "A104";
                    } elseif ($_GET['a'] == 4) {
                        echo "A105";
                        $salle = "A105";
                    } elseif ($_GET['a'] == 5) {
                        echo "A106";
                        $salle = "A106";
                    } elseif ($_GET['a'] == 6) {
                        echo "A107";
                        $salle = "A107";
                    } elseif ($_GET['a'] == 7) {
                        echo "A108";
                        $salle = "A108";
                    } elseif ($_GET['a'] == 8) {
                        echo "A109";
                        $salle = "A109";
                    } elseif ($_GET['a'] == 9) {
                        echo "A110";
                        $salle = "A110";
                    } elseif ($_GET['a'] == 10) {
                        echo "A111";
                        $salle = "A111";
                    } elseif ($_GET['a'] == 11) {
                        echo "A112";
                        $salle = "A112";
                    } elseif ($_GET['a'] == 12) {
                        echo "A113";
                        $salle = "A113";
                    } elseif ($_GET['a'] == 13) {
                        echo "A114";
                        $salle = "A114";
                    } elseif ($_GET['a'] == 14) {
                        echo "A116";
                        $salle = "A116";
                    } elseif ($_GET['a'] == 15) {
                        echo "A118";
                        $salle = "A118";
                    } elseif ($_GET['a'] == 16) {
                        echo "Info";
                        $salle = "Info";
                    }

                }?></p>

            <!--Icones projecteur / accès handicapé et salle info-->
            <div class="icones_infos">
                <i class="fas fa-wheelchair"></i>
                <i class="fas fa-laptop"></i>
                <i class="fas fa-video"></i>
            </div>

            <!-- Infos de disponibilité et de nombre de places-->
            <fieldset class = "places_disponibilite">
                <div class="places_dispo">
                    <i class="fas fa-users"></i><p class="places" ><?php
                            if ($_GET['a']==1) {
                                echo "30 places";
                            } elseif ($_GET['a']==2) {
                                echo "20 places";
                            } elseif ($_GET['a']==3) {
                                echo "30 places";
                            } elseif ($_GET['a']==4) {
                                echo "20 places";
                            } elseif ($_GET['a']==5) {
                                echo "30 places";
                            } elseif ($_GET['a']==6) {
                                echo "20 places";
                            } elseif ($_GET['a']==7) {
                                echo "28 places";
                            } elseif ($_GET['a']==8) {
                                echo "20 places";
                            } elseif ($_GET['a']==9) {
                                echo "20 places";
                            } elseif ($_GET['a']==10) {
                                echo "20 places";
                            } elseif ($_GET['a']==11) {
                                echo "40 places";
                            } elseif ($_GET['a']==12) {
                                echo "19 places";
                            } elseif ($_GET['a']==13) {
                                echo "22 places";
                            } elseif ($_GET['a']==14) {
                                echo "20 places";
                            } elseif ($_GET['a']==15) {
                                echo "23 places";
                            } elseif ($_GET['a']==16) {
                                echo "15 places";
                            }
                        ?></p >
                </div>
                <div class="places_dispo">
                    <i class="far fa-calendar"></i><p class="disponibilite" ><?php
                        include_once("connexion2.php");
                        $requete = "SELECT dispo FROM batiment";
                        if ($reponse=$bdd2->query($requete)) {
                            $enr = $reponse->fetch();
                            if ($_GET['a']==1) {
                                echo "Disponible";
                            }
                            elseif ($_GET['a']==2) {
                                echo "Indisponible";
                            }
                            elseif ($_GET['a']==3) {
                                echo "Indisponible";
                            }
                            elseif ($_GET['a']==4) {
                                echo "Disponible";
                            }
                            elseif ($_GET['a']==5) {
                                echo "Disponible";
                            }
                            elseif ($_GET['a']==6) {
                                echo "Disponible";
                            }
                            elseif ($_GET['a']==7) {
                                echo "Disponible";
                            }
                            elseif ($_GET['a']==8) {
                                echo "Disponible";
                            }
                            elseif ($_GET['a']==9) {
                                echo "Disponible";
                            }
                            elseif ($_GET['a']==10) {
                                echo "Disponible";
                            }
                            elseif ($_GET['a']==11) {
                                echo "Disponible";
                            }
                            elseif ($_GET['a']==12) {
                                echo "Disponible";
                            }
                            elseif ($_GET['a']==13) {
                                echo "Disponible";
                            }
                            elseif ($_GET['a']==14) {
                                echo "Disponible";
                            }
                            elseif ($_GET['a']==15) {
                                echo "Indisponible";
                            }
                            elseif ($_GET['a']==16) {
                                echo "Disponible";
                            }
                        }?></p>
                </div>
            </fieldset>

            <!-- En fonction du mode de connexion on n'affiche pas les mêmes boutons (étudiants/modérateurs/profs)-->
            <?php
            include_once("connexion.php");
            $requete = "SELECT statut FROM mooking WHERE login = '".$_SESSION["login"]."'";
            if ($reponse = $bdd->query($requete)) {
                $enr = $reponse->fetch();
                $statut = $enr['statut'];
            }
            if ($statut=="etudiant"){
                echo '<div class="option_resa">
                <article id ="warning"><i class="fas fa-exclamation-triangle"></i>Attention vous ne pouvez réserver cette salle qu\'avec l\'accord d\'un modérateur</article> 
                <button type = "button" id="resa_gde_salle" ><a class = "resa_gde_salle" href="messagerie.php">DEMANDER A RESERVER UNE GRANDE SALLE</a></button>
            </div>';
            } else {
                echo '<div class="option_resa">
                <input type = "button" id="option" value = "POSER UNE OPTION" />
                <input type = "button" id="reserver" value = "RESERVER" />
            </div>';
            }
            ?>
            <div class="fenetre_resa" hidden>
                <h3 class="titre_fenetreResa">RESERVER</h3>
                <h4>Salle <?php echo $salle; ?></h4>
                <form id = "envoi_resa" name="envoi_resa" action = "salle.php?a=1" method="post">
                    <div class="qui_reserve">
                        <label for ="personne_resa">Qui réserve ?</label>
                        <div class="personne_resa"><?php
                            include_once("connexion.php");
                            if ($statut == "professeur"){
                                $requete = "SELECT prenom, nom FROM mooking WHERE login = '".$_SESSION["login"]."'";
                                if ($reponse = $bdd->query($requete)) {
                                    $enr = $reponse->fetch();
                                    $prenom = $enr['prenom'];
                                    $nom = $enr['nom'];
                                    echo $prenom." ".$nom;
                                } else {
                                    print 'Erreur';
                                }
                            } elseif ($statut == "moderateur"){
                                #Menu déroulant des noms de la bdd
                                echo '<select id ="deroulant_inscrits">';
                                $requete = "SELECT prenom, nom FROM mooking";
                                if ($reponse = $bdd->query($requete)) {
                                    while($enr=$reponse->fetch()) {
                                        echo "<option>".$enr["prenom"]." ".$enr["nom"]."</option>";
                                    }
                                echo '</select>';
                                }
                            }

                            ?></div>
                    </div>
                    <div class = "dateEtheure">
                        <label for="date_heure">Date et Heure</label>
                        <article id ="date_heure" class="date_heure">Le <label for = "heures" id="jour"><?php $today = getdate();
                        if ($today["mday"] <10){
                            $jourActuel = "0".$today["mday"];
                        } else {
                            $jourActuel = $today["mday"];
                        }
                        if ($today["mon"] <10){
                            $moisActuel = "0".$today["mon"];
                        } else {
                            $moisActuel = $today["mon"];
                        }
                        echo $jourActuel."/".$moisActuel;
                        ?></label> à <input type="text" name="heures" id="heures"/>h30</article>
                        <input type="text" id="jour_cache" name="jour_cache" value = "<?php echo $jourActuel ; ?>" hidden/>
                        <input type="text" id="mois_cache" name="mois_cache" value = "<?php echo $moisActuel ; ?>" hidden/>
                    </div>
                    <div class="amenagements_dispo">
                        <label for = "amenagements">Aménagements disponibles dans la salle : </label>
                        <article id = "amenagements" class = "amenagements"><i class="fas fa-wheelchair"></i>
                            <i class="fas fa-laptop"></i>
                            <i class="fas fa-video"></i></article>
                    </div>
                    <div class="nom_formation">
                        <label for ="deroulant_formation">Nom de la formation</label>
                        <select id ="deroulant_formation" name ="deroulant_formation" class = "deroulant_formation">
                            <option id="L1SDL">L1 Sciences du Langage</option>
                            <option id="L3SS">L3 Sciences Sociales</option>
                            <option id="M2IDL">M2 Industrie de la Langue</option>
                            <option id="M2PC">M2 Psychologie Cognitive</option>
                        </select>
                    </div>
                    <div class="frequence_resa">
                        <label>Fréquence</label>
                        <div class="checkbox_frequence">
                            <div class="checkbox_frequence">
                                <input type="checkbox" id="ponctuel" name="ponctuel" checked>
                                <label class="labels_frequence" for="ponctuel">Ponctuel</label>
                            </div>
                            <div class="checkbox_frequence">
                                <input type="checkbox" id="unesemainesurdeux" name="unesemainesurdeux" >
                                <label class="labels_frequence" for="unesemainesurdeux">Une semaine sur deux</label>
                            </div>
                            <div class="checkbox_frequence">
                                <input type="checkbox" id="chaquelmmjv" name="chaquelmmjv" >
                                <label class="labels_frequence" for="chaquelmmjv">Chaque ...</label>
                            </div>
                            <div class="checkbox_frequence">
                                <input type="checkbox" id="periode_resa" name="periode" >
                                <label class="labels_frequence" for="periode">Sur une période : </label>
                                <input type="text" id = "periode"> semaines
                            </div>
                        </div>
                    </div>
                    <div class="nom_cours">
                        <label for = "deroulant_cours">Nom du cours</label>
                        <select id = "deroulant_cours" name="deroulant_cours">
                            <option>Sociolinguistique</option>
                            <option>Phonologie</option>
                            <option>Informatique</option>
                        </select>
                    </div>
                    <div>
                        <input id="enr_resa" type="submit" value="Enregistrer la réservation"/>
                    </div>
                </form>
                <?php
                if (isset($_POST['jour_cache'])) {
                    $jour_resa = $_POST['jour_cache'];
                    $mois_resa = $_POST['mois_cache'];
                    $heure_resa = $_POST['heures'];
                    $formation_resa= $_POST['deroulant_formation'];
                    $cours_resa= $_POST['deroulant_cours'];
                    $requete2 = "INSERT INTO `reservation` (`salle`, `jour`, `heure`, `formation`, `cours`, `frequence`, `mois`) VALUES ('".$salle."', '".$jour_resa."', '".$heure_resa."', '".$formation_resa."', '".$cours_resa."','Ponctuel', '".$mois_resa."')";
                    $bdd->query($requete2);
                }
                ?>
            </div>
        </div >

        <!--Affichage du plan -->
        <div id="carte_calendrier">
            <?php
            include_once("connexion2.php");
            $requete = "SELECT salle, shape, coords, image, page FROM batiment";
            if ($reponse=$bdd2->query($requete)) {
                $enr = $reponse->fetch();
                    echo "<img src=" . $enr['image'] . " style=\"width:985px; height:550px\" class='img' />";
            }?>
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
;
        //Afficher la fenetre de reservation quand on clique sur "Reserver"
        $("#reserver").on('click', function(){
            $(".fenetre_resa").show();
        });

        //Faire fonctionner le calendrier
        $("#modif_date").on("click", function() {
            if ($("#mini_calendrier").hasClass('open')) {
                $("#mini_calendrier").hide();
                $("#mini_calendrier").removeClass('open');
            } else {
                $("#mini_calendrier").show();
                $("#mini_calendrier").addClass('open');
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


       //Quand on clique sur un jour de la semaine, renvoyer le numéro du jour
       body.on("click",".cellules", function() {
           var id = $(this).attr("id");
           var mois = $("#currentMonth").attr("value");
           if (mois<10){
               mois = "0" + mois;
           }
           if (id<10){
               id = "0" + id;
           }
           $("#jour").html(id + "/" + mois);
           $("#jour_cache").val(id);
           $("#mois_cache").val(mois);
       });

    </script>
    </body>
</html>
