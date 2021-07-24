<?php

/**
 * Classe HomeController
 *
 * Cette classe contient tous les contrôleurs utilisés dans la page d'accueil (hors gestionnaire)
 *
 * PHP version 5.6
 *
 * @author Célia Martin <celia.ma@free.fr>
 */

#Chargement des classes
require_once('model/SearchByCriteria/Criterion.php');
require_once('model/SearchByCriteria/CriterionVerb.php');
require_once('model/SearchByCriteria/CriterionAdjective.php');
require_once('model/Statistics/FailureAndSuccessTenses.php');
require_once('model/Statistics/NbVerbalForms.php');
require_once('model/Statistics/NbWordProd.php');
require_once('model/Statistics/POSRepartitionByLevel.php');
require_once('model/Statistics/VerbalFormsRepartitionBaseAndEndingPhono.php');
require_once('model/Statistics/StandardizedBaseEndingProportion.php');
require_once('model/Statistics/StandardizedBaseOrEnding.php');
require_once('model/Statistics/TenseRepartition.php');

class HomeController {
    /**
     * Fonction home()
     *
     * Cette fonction permet d'afficher la page d'accueil.
     *
     * @return void
     */
    public function home(){
        require('view/home.php');
    }

    /**
     * Fonction showResultsCriteria($corpus, $level, $pos, $errStatus, $segmStatus, $lemma)
     *
     * Contrôleur pour les critères généraux : Permet de retourner les résultats de la requête (interrogation simple, exclut les verbes et les adjectifs) faite par l'utilisateur au format JSON pour un traitement avec ajax..
     *
     * @param $corpus
     * @param $level
     * @param $pos
     * @param $errStatus
     * @param $segmStatus
     * @param $lemma
     * @return void
     * @throws Exception
     */
    function showResultsCriteria($corpus, $level, $pos, $errStatus, $segmStatus, $lemma){
        $criterion = new Criterion($corpus, $level, $pos, $errStatus, $segmStatus, $lemma);
        $results = $criterion->getResults();
        echo json_encode($results);
    }

    /**
     * Fonction showResultsCriteriaVerbs($corpus, $level, $pos, $errStatus, $segmStatus, $lemma, $tense, $person, $typeErr, $base, $ending).
     *
     * Contrôleur pour les critères des verbes : Permet de retourner les résultats de la requête faite par l'utilisateur  pour les verbes au format JSON pour un traitement avec ajax.
     *
     * @param $corpus
     * @param $level
     * @param $pos
     * @param $errStatus
     * @param $segmStatus
     * @param $lemma
     * @param $tense
     * @param $person
     * @param $typeErr
     * @param $desinence
     * @param $base
     * @return void
     * @throws Exception
     */
    function showResultsCriteriaVerbs($corpus, $level, $pos, $errStatus, $segmStatus, $lemma, $tense, $person, $typeErr, $base, $ending){
        $criterion = new CriterionVerb($corpus, $level, $pos, $errStatus, $segmStatus, $lemma, $tense, $person, $typeErr, $base, $ending);
        $results = $criterion->getResultsVerb();
        echo json_encode($results);
    }

    /**
     * Fonction showResultsCriteriaAdjectives($corpus, $level, $pos, $errStatus, $segmStatus, $lemma, $genre, $numbre, $errGenre, $errNumber, $base).
     *
     * Contrôleur pour les critères des adjectifs : Permet de retourner les résultats de la requête faite par l'utilisateur pour les adjectifs au format JSON pour un traitement avec ajax..
     *
     * @param $corpus
     * @param $level
     * @param $pos
     * @param $errStatus
     * @param $segmStatus
     * @param $lemma
     * @param $genre
     * @param $numbre
     * @param $errGenre
     * @param $errNumber
     * @param $base
     * @return void
     * @throws Exception
     */
    function showResultsCriteriaAdjectives($corpus, $level, $pos, $errStatus, $segmStatus, $lemma, $genre, $numbre, $errGenre, $errNumber, $base){
        $criterion = new CriterionAdjective($corpus, $level, $pos, $errStatus, $segmStatus, $lemma, $genre, $numbre, $errGenre, $errNumber, $base);
        $results = $criterion->getResultsAdjective();
        echo json_encode($results);
    }


    /**
     * Fonction showStatsTab($tabName, $verbGroup, $tense)
     *
     * Controleur pour les différents tableaux de statistiques. Il retourne le tableau au format JSON pour un traitement avec ajax.
     *
     * @param string $tabName Nom du tableau à générer
     * @param string $verbGroup Groupe verbal pour lequel afficher le tableau
     * @param string $tense Tiroir verbal pour lequel afficher le tableau
     * @return void
     * @throws Exception
     */
    function showStatsTab($tabName, $verbGroup, $tense){
        # Tableau supprimé: Nombre de formes verbales, de bases et de désinences analysées
        switch ($tabName) {
            #Tableau : Nombre de mots des productions
            case "NbWordProd" :
                $nbWordProd = new NbWordProd();
                $tab = $nbWordProd->createTabNbWordsProd();
                echo json_encode($tab);
                break;
            #Tableau : Répartition des POS
            case "POSRepartitionByLevel" :
                $posRepartitionByLevel = new POSRepartitionByLevel();
                $tab = $posRepartitionByLevel->createTabPOSRepartitionByLevel();
                echo json_encode($tab);
                break;
            #Tableau : Répartition des tiroirs verbaux
            case "TenseRepartition" :
                $tenseRepartition = new TenseRepartition();
                $tab = $tenseRepartition->createTabTenseRepartition();
                echo json_encode($tab);
                break;
            #Tableau : Nombre de formes verbales analysées
            case "NbVerbalForms" :
                $nbVerbalForms = new NbVerbalForms();
                $tab = $nbVerbalForms->createTabNbVerbalForms();
                echo json_encode($tab);
                break;
            #Tableau : Répartition des échecs et réussites pour les tiroirs verbaux les plus employés
            case "FailureAndSuccessTenses" :
                $failureAndSuccessTenses = new FailureAndSuccessTenses();
                $tab =$failureAndSuccessTenses->createTabFailureSuccess($verbGroup);
                echo json_encode($tab);
                break;
            #Tableau : Répartition des formes verbales selon si leur base et/ou leur désinence sont normées
            case "StandardizedBaseOrEnding" :
                $standardizedBaseOrEnding = new StandardizedBaseOrEnding();
                $tab =$standardizedBaseOrEnding->createTabStandardizedBaseOrEnding($verbGroup, $tense);
                echo json_encode($tab);
                break;
            #Tableau : Proportion de bases et de désinences normées et non normées
            case "StandardizedBaseEndingProportion" :
                $standardizedBaseEndingProportion = new StandardizedBaseEndingProportion();
                $tab =$standardizedBaseEndingProportion ->createTabStandardizedBaseEndingProportion($verbGroup, $tense);
                echo json_encode($tab);
                break;
            #Tableau : Répartition des formes verbales non normées selon si leur base et/ou leur désinence respectent ou non la phonologie
            case "VerbalFormsRepartitionBaseAndEndingPhono" :
                $verbalFormsRepartitionBaseAndEndingPhono = new VerbalFormsRepartitionBaseAndEndingPhono();
                $tab =$verbalFormsRepartitionBaseAndEndingPhono ->createTabVerbalFormsRepartitionBaseAndEndingPhono($verbGroup, $tense);
                echo json_encode($tab);
                break;
            default:
                throw new Exception("Erreur : le tableau n'a pas pu être généré.");
        }
    }
}
