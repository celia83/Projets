<!DOCTYPE html>
<html>
    <head>
        <!-- Entête de la page-->
        <title>E-Calm</title>
        <meta charset="UTF-8"/> <!-- dit en quel encodage je suis-->
        <link rel="stylesheet" href = "public/css/style.css"/> <!-- donne la feuille de style en css utilisée-->
        <script type="text/javascript" src="public/js/jquery-3.4.1.js"></script> <!--appel de JQuery-->
        <script src="public/js/script.js"></script> <!--Appel du script javascript-->
        <script src="https://kit.fontawesome.com/bafcb5074c.js" crossorigin="anonymous"></script> <!--Site pour utiliser des icônes-->
    </head>
    <body>
        <header>
            <img alt="logo_lidilem" id = "logo_lidilem" src="public/assets/img/logo_LIDILEM_CMJN.jpg"/>
            <img alt="logo_ecalm" id = "logo_ecalm" src="public/assets/img/Ecalm_logo_transparent.png"/>
            <!--<a href="view/userConnection.php">Connexion</a>-->
            <div id="connectionArea"><?php
                if (isset ($_SESSION["login"])){
                echo '<a class = "connectionDisconnection" href="index.php?action=disconnection">Déconnexion</a>';
                } else {
                echo '<a class = "connectionDisconnection"  href="index.php?action=connectionPage">Connexion</a>';
                }?>
            </div>
        </header>

        <!--Boutons pour naviguer entre les articles : soit données soit statistiques quand on n'est pas un gestionnaire-->
        <div id = "navigationButtons">
            <button id = "data" type="button">Données</button>
            <button id = "statistics" type="button">Statistiques descriptives</button>
            <?php
            if (isset ($_SESSION["login"])){
                echo '<button id = "manager" type="button">Ajouter et Supprimer des données</button>';
            }
            ?>
        </div>



        <!--Partie pour la sélection des critères-->
        <article id = "dataSelection">
            <form id="dataSelectionForm" action ="index.php?action=showResults" method="POST">
                <article id = "criteria">
                    <!--Afficher les critères généraux -->
                    <article id = "generalCriteria">
                        <h2>Critères Généraux</h2>
                        <section id = "generalCriteriaSection">
                            <article id="generalCriteriaColumnOne">
                                <div class = "generalCriteriaDivs">
                                    <label for="corpus">Corpus : </label>
                                    <select  id="corpus" name="corpus">
                                        <option value = "Tous">Tous</option>
                                        <option value = "Scoledit">Scoledit</option>
                                        <option value = "Ecriscol">Ecriscol</option>
                                        <option value = "Littéracie">Littéracie</option>
                                        <option value = "Resolco">Resolco</option>
                                    </select>
                                </div>
                                <div class = "generalCriteriaDivs">
                                    <label for="level">Niveau : </label>
                                    <select  id="level" name="level">
                                        <option value = "Tous">Tous</option>
                                        <option value = "CP">CP</option>
                                        <option value = "CE1">CE1</option>
                                        <option value = "CE2">CE2</option>
                                        <option value = "CM1">CM1</option>
                                        <option value = "CM2">CM2</option>
                                        <option value = "CP">6EME</option>
                                        <option value = "CE1">5EME</option>
                                        <option value = "CE2">4EME</option>
                                        <option value = "CM1">3EME</option>
                                        <option value = "2NDE">2NDE</option>
                                        <option value = "1ERE">1ERE</option>
                                        <option value = "TERMINALE">TERMINALE</option>
                                        <option value = "L1">L1</option>
                                        <option value = "L2">L2</option>
                                        <option value = "L3">L3</option>
                                        <option value = "M1">M1</option>
                                        <option value = "M2">M2</option>
                                    </select>
                                </div>
                                <div class = "generalCriteriaDivs">
                                    <label for="pos">Catégorie grammaticale : </label>
                                    <select  id="pos" name="pos">
                                        <option value = "Tous">Tous</option>
                                        <option value = "Adjectif">Adjectif</option>
                                        <option value = "Adverbe">Adverbe</option>
                                        <option value = "Verbe">Verbe</option>
                                        <option value = "Nom">Nom</option>
                                        <option value = "Noms propre">Nom Propre</option>
                                        <option value = "Déterminant">Déterminant</option>
                                        <option value = "Pronom">Pronom</option>
                                        <option value = "Préposition">Préposition</option>
                                        <option value = "Conjonction">Conjonction de coordination et de subordination</option>
                                        <option value = "Abréviation">Abréviations</option>
                                        <option value = "Interjection">Interjections</option>
                                        <option value = "Chiffre">Chiffres</option>
                                    </select>
                                </div>
                            </article>
                            <article id ="generalCriteriaColumnTwo">
                                <div class = "generalCriteriaDivs">
                                    <label for="errStatus">Statut d'erreur : </label>
                                    <select  id="errStatus" name="errStatus">
                                        <option value = "Tous">Tous</option>
                                        <option value = "Norm">Normé</option>
                                        <option value = "Phono">Phono</option>
                                        <option value = "ApproxGraphique">ApproxGraphique </option>
                                        <option value = "ApproxPhono">ApproxPhono </option>
                                        <option value = "ApproxArchiPhono">ApproxArchiPhono</option>
                                        <option value = "Non norm">Non normé</option>
                                        <option value = "Non pertinent">Non pertinent</option>
                                    </select>
                                </div>
                                <div class = "generalCriteriaDivs">
                                    <label for="segmStatus">Statut de segmentation : </label>
                                    <select  id="segmStatus" name="segmStatus">
                                        <option value = "Tous">Tous</option>
                                        <option value = "Norm">Normé</option>
                                        <option value = "HyperSeg">HyperSeg</option>
                                        <option value = "HypoSeg">HypoSeg</option>
                                        <option value = "HypoHyperSeg">HypoHyperSeg</option>
                                        <option value = "Non pertinent">Non pertinent</option>
                                        <option value = "Omis">Omis</option>
                                        <option value = "Inséré">Inséré</option>
                                    </select>
                                </div>
                                <div class = "generalCriteriaDivs">
                                    <label for="lemma">Lemme : </label>
                                    <input type="text" id="lemma" name="lemma" />
                                </div>
                            </article>
                        </section>
                    </article>

                    <!--Afficher les critères spécifiques aux verbes -->
                    <article id = "verbCriteria" hidden>
                        <h2>Critères Avancés</h2>
                        <section id = "verbCriteriaSection">
                            <article id="verbCriteriaColumnOne">
                                <div class = "verbCriteriaDivs">
                                    <label for="tense">Tiroir verbal : </label>
                                    <select  id="tense" name="tense">
                                        <option value = "Tous">Tous</option>
                                        <option value = "Conditionnel">Conditionnel</option>
                                        <option value = "Futur">Futur</option>
                                        <option value = "Impératif">Impératif</option>
                                        <option value = "Imparfait">Imparfait</option>
                                        <option value = "Infinitif">Infinitif</option>
                                        <option value = "Participe présent">Participe présent</option>
                                        <option value = "Présent">Présent</option>
                                        <option value = "Passé simple">Passé simple</option>
                                        <option value = "Subjonctif imparfait">Subjonctif imparfait</option>
                                        <option value = "Subjonctif présent">Subjonctif présent</option>
                                    </select>
                                </div>
                                <div class = "verbCriteriaDivs">
                                    <label for="person">Personne : </label>
                                    <select  id="person" name="person">
                                        <option value = "Tous">Tous</option>
                                        <option value = "1S">1S</option>
                                        <option value = "2S">2S</option>
                                        <option value = "3S">3S</option>
                                        <option value = "1P">1P</option>
                                        <option value = "2P">2P</option>
                                        <option value = "3P">3P</option>
                                    </select>
                                </div>

                                <div class = "verbCriteriaDivs">
                                    <label for="typeErr">Type d'erreur : </label>
                                    <select  id="typeErr" name="typeErr">
                                        <option value = "Aucune">Aucune</option>
                                        <option value = "Erreur Base">Erreur Base</option>
                                        <option value = "Erreur Désinence">Erreur Désinence</option>
                                        <option value = "Erreur Base et Désinence">Erreur Base et Désinence</option>
                                    </select>
                                </div>
                            </article>
                            <article id="verbCriteriaColumnTwo">
                                <div class = "verbCriteriaDivs">
                                    <label for="base">Base : </label>
                                    <input type="text" id="base" name="base" />
                                </div>
                                <div class = "verbCriteriaDivs">
                                    <label for="ending">Désinence : </label>
                                    <input type="text" id="ending" name="ending" />
                                </div>
                            </article>
                        </section>
                    </article>

                    <!--Afficher les critères spécifiques aux adjectifs -->
                    <article id = "adjectiveCriteria" hidden >
                        <h2>Critères Avancés</h2>
                        <section id="adjectiveCriteriaSection">
                            <article id="adjectiveCriteriaColumnOne">
                                <div class = "adjectiveCriteriaDivs">
                                    <label for="genre">Genre : </label><br/>
                                    <input type="radio" id = "Masculin" class="genre" name="genre" value = "Masculin"/><label for="Masculin">Masculin</label>
                                    <input type="radio" id = "Féminin" class="genre" name="genre" value = "Féminin"/><label for="Féminin">Féminin</label>
                                    <input type="radio" id = "Les deux" class="genre" name="genre" value = "Les deux" checked/><label for="Les deux">Les deux</label>
                                </div>
                                <div class = "adjectiveCriteriaDivs">
                                    <label for="number">Nombre : </label><br/>
                                    <input type="radio" id = "Singulier" class="number" name="number" value = "Singulier"/><label for="Singulier">Singulier</label>
                                    <input type="radio" id = "Pluriel" class="number" name="number" value = "Pluriel"/><label for="Pluriel">Pluriel</label>
                                    <input type="radio" id = "Les deux" class="number" name="number" value = "Les deux" checked/><label for="Les deux">Les deux</label>
                                </div>
                                <div class = "adjectiveCriteriaDivs">
                                    <label for="errGenre">Erreur de genre : </label><br/>
                                    <input type="radio" id = "Avec" class="errGenre" name="errGenre" value = "Avec"/><label for="Avec">Avec</label>
                                    <input type="radio" id = "Sans" class="errGenre" name="errGenre" value = "Sans"/><label for="Sans">Sans</label>
                                    <input type="radio" id = "Tous" class="errGenre" name="errGenre" value = "Tous" checked/><label for="Tous">Tous</label>
                                </div>
                            </article>
                            <article id="adjectiveCriteriaColumnTwo">
                                <div class = "adjectiveCriteriaDivs">
                                    <label for="errNumber">Erreur de nombre : </label><br/>
                                    <input type="radio" id = "Avec" class="errNumber" name="errNumber" value = "Avec"/><label for="Avec">Avec</label>
                                    <input type="radio" id = "Sans" class="errNumber" name="errNumber" value = "Sans"/><label for="Sans">Sans</label>
                                    <input type="radio" id = "Tous" class="errNumber" name="errNumber" value = "Tous" checked/><label for="Tous">Tous</label>
                                </div>
                                <div class = "adjectiveCriteriaDivs">
                                    <label for="baseAdj">Base</label><br/>
                                    <input type="text" id="baseAdj" name="baseAdj" />
                                </div>
                            </article>
                        </section>
                    </article>
                </article>


                <div id ="getResultsDiv">
                    <input id="getResults" value ="Résultats" type="submit" />
                </div>
            </form>
        </article>

        <!--Partie pour la sélection des tableaux-->
        <article id = "statisticsSelection" hidden>
            <form id = "statisticsSelectionForm" action ="index.php?action=showStats" method="POST">
                <section id = "statisticTabsSection">
                    <article>
                        <div>
                            <label for="tabStats">Afficher le tableau : </label>
                            <select  id="tabStats" name="tabStats">
                                <option value = "NbWordProd">Nombre de mots des productions</option>
                                <option value = "POSRepartitionByLevel">Répartition des POS en fonction du niveau</option>
                                <option value = "TenseRepartition">Répartition des tiroirs verbaux</option>
                                <option value = "NbVerbalForms">Nombre de formes verbales analysées</option>
                                <option value = "FailureAndSuccessTenses">Répartition des échecs et réussites pour les tiroirs verbaux les plus employés</option>
                                <option value = "StandardizedBaseOrEnding">Répartition des formes verbales selon si leur base et/ou leur désinence sont normées</option>
                                <option value = "StandardizedBaseEndingProportion">Proportion de bases et de désinences normées et non normées</option>
                                <option value = "VerbalFormsRepartitionBaseAndEndingPhono">Répartition des formes verbales non normées selon si leur base et/ou leur désinence respectent ou non la phonologie</option>
                            </select>
                        </div>
                    </article>
                    <article id ="groupSelection" hidden>
                        <div>
                            <label for="verbGroup">Sélectionnez un groupe :  </label>
                            <input class = "verbGroup" type="radio" id = "er"  name="verbGroup" value = "er"/><label for="er">Verbes en -er</label>
                            <input class = "verbGroup" type="radio" id = "nonEr"  name="verbGroup" value = "nonEr"/><label for="nonEr">Verbes non en -er</label>
                            <input class = "verbGroup" type="radio" id = "tous"  name="verbGroup" value = "tous" checked/><label for="tous">Tous les verbes</label>
                        </div>
                    </article>
                    <article id ="tenseSelection" hidden>
                        <div>
                            <label for="tense">Sélectionnez un tiroir verbal :  </label>
                            <input class = "tense" type="radio" id = "Infinitif"  name="tense" value = "Infinitif"/><label for="infinitif">Infinitif</label>
                            <input class = "tense" type="radio" id = "Présent"  name="tense" value = "Présent"/><label for="Présent">Présent</label>
                            <input class = "tense" type="radio" id = "Imparfait"  name="tense" value = "Imparfait"/><label for="Imparfait">Imparfait</label>
                            <input class = "tense" type="radio" id = "Passé simple"  name="tense" value = "Passé simple"/><label for="Passé simple">Passé Simple</label>
                            <input class = "tense" type="radio" id = "tous"  name="tense" value = "tous" checked/><label for="tous">Tous les tiroirs verbaux</label>
                        </div>
                    </article>
                </section>
                <div>
                    <input id="getStats" value ="Afficher les statistiques" type="submit" />
                </div>
            </form>
        </article>

        <!--Partie réservée au gestionnaire. Connexion requise pour afficher son contenu-->
        <div id="managerDiv" hidden>
            <article id="managerArticle" >

            </article>
        </div>

        <!--Téléchargement des informations : résultat de la requête ou exemplier-->
        <div id="downloadTableDiv">
            <input id="downloadExemplier" value ="Télécharger un exemplier" type="submit"/>
            <input id="downloadTable" value ="Télécharger le résultat" type="submit"/>
        </div>

        <!--Critères pour le téléchargement d'un exemplier-->
        <article id="downloadExemplierSection" hidden>
            <div id="cross">
                <h3>Exporter un exemplier</h3>
                <i class="fas fa-times"></i>
            </div>
            <form id="downloadExemplierForm" action ="" method="POST">
                <article id="downloadExemplierArticle">
                    <div id="wordDiv">
                        <label for="word">Quel mot ? </label>
                        <select  id="word" name="word">

                        </select>
                    </div>
                    <div id="nbLineDiv">
                        <label for="nbLine">Combien de lignes souhaitez-vous ? </label>
                        <select  id="nbLine" name="nbLine">
                            <option value = "10">10</option>
                            <option value = "20">20</option>
                            <option value = "30">30</option>
                            <option value = "40">40</option>
                            <option value = "50">50</option>
                        </select>
                    </div>
                </article>
                <input id="getExemplier" value ="Télécharger l'exemplier" type="submit"/>
            </form>
        </article>


        <!--Partie pour l'affichage des résultats-->
        <article id = "resultsArticle">
            <h2 id="resultsTitle">Résultats</h2>
            <div id = "resultsDiv">
                <table id = "resultsTable">
                </table>
            </div>
        </article>
    <footer></footer>
    </body>
</html>
