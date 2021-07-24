<?php

include_once "model/DataBase.php";

/**
 * Classe CriterionVerb : classe fille de la classe Criterion.
 *
 * Cette classe permet de récupérer des données dans la base de données en fonction des critères sélectionnés par l'utilisateur. Cette classe ne fonctionne que pour les verbes (pour les adjectifs et les autres catégories se référer aux classes correspondantes : Criterion et CriteronAdjective).
 *
 * PHP version 5.6
 *
 * @author Célia Martin <celia.ma@free.fr>
 *
 */
class CriterionVerb extends Criterion{

    private $tense;
    private $person;
    private $typeErr;
    private $ending;
    private $base;

    /**
     * Constructeur de CriterionVerb.
     *
     * Reprend les propriétés de la classe mère Criterion (de corpus à lemme). Les autres propriétés sont spécifiques à la classe des verbes.
     *
     * @param string $corpus
     * @param string $level
     * @param string $pos
     * @param string $errStatus
     * @param string $segmStatus
     * @param string $lemma
     * @param string $tense Tiroir verbal sélectionné (Conditionnel, Futur, Impératif, Imparfait, Infinitif, Participe présent, Présent, Passé simple, Subjonctif imparfait, Subjonctif présent)
     * @param string $person Personne sélectionnée (1S, 2S, 3S, 1P, 2P, 3P)
     * @param string $typeErr Présence d'une erreur soit sur la base, soit sur la désinence soit sur les deux (Erreur Base, Erreur Désinence, Erreur Base et Désinence)
     * @param string $base Base sélectionnée
     * @param string $ending Désinence sélectionnée
     */
    public function __construct($corpus, $level, $pos, $errStatus, $segmStatus, $lemma,$tense, $person, $typeErr, $base, $ending){
        parent::__construct($corpus, $level, $pos, $errStatus, $segmStatus, $lemma);
        $this->tense=$tense;
        $this->person=$person;
        $this->typeErr=$typeErr;
        $this-> ending= $ending;
        $this->base=$base;
    }

    /**
     * Fonction getResultsVerb()
     *
     * Cette fonction permet de retourner un tableau contenant les lignes retournées par la requête en fonction des critères sélectionnés par l'utilisateur. La requête est spécifique aux verbes (elle contient en plus les champs de tiroir verbal, personne, type d'erreur, désinence et base).
     *
     * @return array
     * @throws Exception
     */
    public function getResultsVerb(){
        /* Exemple de requete qui fonctionne avec les critères principaux (pour le corpus on recherche S dans idTok pour le corpus Scoledit:
         SELECT * FROM `ecalm`
         WHERE IdTok LIKE "%S%"
         AND Niv LIKE "CM2"
         AND Categorie LIKE "NOM"
         AND StatutErreur LIKE "01-Norm%"
         AND StatutSegm LIKE "01-Norm%"
         And Lemme LIKE "fois"
         */

        #Normaliser les critères pour que la requête soit adaptées au données de la bdd
        $this->normalizeCriterions();

        #Rédiger la requête (pour les verbes la catégorie et le tiroir verbal sont les mêmes donc on sélectionne soit tous les verbes soit les verbes au conditionnel etc. dans la partie "Catégorie
        $request = 'SELECT * FROM `ecalm` 
WHERE IdTok REGEXP "'.$this->corpus.'" 
AND Niv LIKE "'.$this->level.'" 
AND Categorie LIKE "'.$this->tense.'"
AND Categorie LIKE "VER:pper" = 0  
AND StatutErreur LIKE "'.$this->errStatus.'" 
AND StatutSegm LIKE "'.$this->segmStatus.'" 
AND Lemme LIKE "'.$this->lemma.'" 
AND VerPers LIKE "'.$this->person.'" 
AND BaseVerForme LIKE "'.$this->base.'" 
AND DesiVerForme LIKE "'.$this-> ending.'"'.$this->typeErr;

        #Récupération des données
        $database = new DataBase();
        $tab= $database->getData($request);

        #Ajout du lien vers le scan s'il existe
        $finalTab =$this->addScanLink($tab);
        return $finalTab;
    }

    /**
     * Cette fonction est la même que celle présente dans la classe mère, elle normalise en plus les informations spécifiques aux verbes (genre, nombre, erreur de genre, de nombre et base).
     *
     * @return void
     */
    protected function normalizeCriterions(){
        parent::normalizeCriterions();

        #Normaliser les tiroirs verbaux
        if ($this->tense == "Conditionnel"){
            $this->tense = "VER:cond";
        } elseif ($this->tense == "Futur"){
            $this->tense = "VER:futu";
        } elseif ($this->tense == "Impératif"){
            $this->tense = "VER:impe";
        } elseif ($this->tense == "Imparfait"){
            $this->tense = "VER:impf";
        } elseif ($this->tense == "Infinitif") {
            $this->tense = "VER:infi";
        } elseif ($this->tense == "Participe présent"){
            $this->tense = "VER:ppre";
        } elseif ($this->tense == "Présent"){
            $this->tense = "VER:pres";
        } elseif ($this->tense == "Passé simple"){
            $this->tense= "VER:simp";
        } elseif ($this->tense== "Subjonctif imparfait"){
            $this->tense = "VER:subi";
        } elseif ($this->tense== "Subjonctif présent"){
            $this->tense = "VER:subp";
        } else {
            $this->tense = "VER%";
        }

        #Normaliser les personnes
        if ($this->person == "1S"){
            $this->person = "P1%";
        } elseif ($this->person == "2S"){
            $this->person = "%P2";
        } elseif ($this->person == "3S"){
            $this->person = "P3";
        } elseif ($this->person == "1P"){
            $this->person = "P4";
        } elseif ($this->person == "2P") {
            $this->person = "P5";
        } elseif ($this->person == "3P"){
            $this->person = "P6";
        } else {
            $this->person = "%";
        }

        #Normaliser les types d'erreur
        if ($this->typeErr == "Erreur Base"){
            $this->typeErr = ' AND ErrVerBase LIKE "1"';
        } elseif ($this->typeErr== "Erreur Désinence") {
            $this->typeErr = ' AND ErrVerDes LIKE "1"';
        } elseif ($this->typeErr== "Erreur Base et Désinence"){
            $this->typeErr= ' AND ErrVerBaseEtDes LIKE "1"';
        } else {
            $this->typeErr = ' AND ErrVerBase LIKE "0" AND ErrVerDes LIKE "0" AND ErrVerBaseEtDes LIKE "0"';
        }

        #Normaliser la base
        if($this->base == ""){
            $this->base = "%";
        }

        #Normaliser la désinence
        if($this->ending == ""){
            $this->ending = "%";
        }
    }
}
