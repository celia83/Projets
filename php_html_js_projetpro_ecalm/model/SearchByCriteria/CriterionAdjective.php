<?php

include_once "model/DataBase.php";
 /**
 * Classe CriterionAdjective : classe fille de la classe Criterion.
 *
 * Cette classe permet de récupérer des données dans la base de données en fonction des critères sélectionnés par l'utilisateur. Cette classe ne fonctionne que pour les adjectifs (pour les verbes et les autres catégories se référer aux classes correspondantes : Criterion et CriteronVerb).
 *
 * PHP version 5.6
 *
 * @author Célia Martin <celia.ma@free.fr>
 *
 */

class CriterionAdjective extends Criterion{

    private $genre;
    private $numbre;
    private $errGenre;
    private $errNumber;
    private $base;

    /**
     * Constructeur de CriterionAdjective.
     *
     * Reprend les propriétés de la classe mère Criterion (de corpus à lemme). Les autres propriétés sont spécifiques à la classe des adjectifs.
     *
     * @param string $corpus
     * @param string $level
     * @param string $pos
     * @param string $errStatus
     * @param string $segmStatus
     * @param string $lemma
     * @param string $genre Genre de l'adjectif sélectionné
     * @param string $numbre Nombre de l'adjectif sélectionné
     * @param string $errGenre Présence ou non d'erreur sur le genre (0, 1 ou Tous)
     * @param string $errNumber Présence ou non d'erreur sur le nombre (0, 1 ou Tous)
     * @param string $base Base sélectionnée
     */
    public function __construct($corpus, $level, $pos, $errStatus, $segmStatus, $lemma, $genre, $numbre, $errGenre, $errNumber, $base){
        Parent::__construct($corpus, $level, $pos, $errStatus, $segmStatus, $lemma);
        $this->genre=$genre;
        $this->numbre=$numbre;
        $this->errGenre=$errGenre;
        $this->errNumber=$errNumber;
        $this->base=$base;
    }

    /**
     * Fonction getResultsAdjective()
     *
     * Cette fonction permet de retourner un tableau contenant les lignes retournées par la requête en fonction des critères sélectionnés par l'utilisateur. La requête est spécifique aux adjectifs (elle contient en plus les champs de genre, nombre, erreur de genre, erreur de nombre et base).
     *
     * @return array
     * @throws Exception
     */
    public function getResultsAdjective(){
        /* Exemple de requete qui fonctionne avec les critères principaux (pour le corpus on recherche S dans idTok pour le corpus Scoledit:
         SELECT * FROM `CM2_Scoledit`
         WHERE IdTok LIKE "%S%"
         AND Niv LIKE "CM2"
         AND Categorie LIKE "NOM"
         AND StatutErreur LIKE "01-Norm%"
         AND StatutSegm LIKE "01-Norm%"
         And Lemme LIKE "fois"
         */

        #Normaliser les critères pour que la requête soit adaptées au données de la bdd
        $this->normalizeCriterions();

        #Rédiger la requête
        $request = 'SELECT * FROM `ecalm` 
WHERE IdTok REGEXP "'.$this->corpus.'" 
AND Niv LIKE "'.$this->level.'" 
AND Categorie LIKE "'.$this->pos.'" 
AND StatutErreur LIKE "'.$this->errStatus.'" 
AND StatutSegm LIKE "'.$this->segmStatus.'" 
AND Lemme LIKE "'.$this->lemma.'" 
AND Genre LIKE "'.$this->genre.'" 
AND Nombre LIKE "'.$this->numbre.'" 
AND ErreurAdjGenre LIKE "'.$this->errGenre.'" 
AND ErreurAdjNombre LIKE "'.$this->errNumber.'" 
AND BaseAdjNorm LIKE "'.$this->base.'"';

        #Connection à la base et récupération des données
        $database = new DataBase();
        $tab= $database->getData($request);

        #Ajout du lien vers la scan s'il existe
        $finalTab =$this->addScanLink($tab);

        return $finalTab;
    }

    /**
     * Fonction normalizeCriterions()
     *
     * Cette fonction est la même que celle présente dans la classe mère, elle normalise en plus les informations spécifiques aux adjectifs (genre, nombre, erreur de genre, de nombre et base).
     *
     * @return void
    */
    protected function normalizeCriterions(){
        parent::normalizeCriterions();
        #Normaliser le genre
        if ($this->genre == "Masculin"){
            $this->genre = "m";
        } elseif ($this->genre == "Féminin"){
            $this->genre = "f";
        } else {
            $this->genre = "%";
        }

        #Normaliser le nombre
        if ($this->numbre == "Singulier"){
            $this->numbre = "s";
        } elseif ($this->numbre == "Pluriel"){
            $this->numbre = "p";
        } else {
            $this->numbre = "%";
        }

        #Normaliser les erreurs de genre
        if ($this->errGenre == "Avec"){
            $this->errGenre = "1";
        } elseif ($this->errGenre== "Sans"){
            $this->errGenre = "0";
        } else {
            $this->errGenre = "%";
        }

        #Normaliser les erreurs de nombre
        if ($this->errNumber == "Avec"){
            $this->errNumber  = "1";
        } elseif ($this->errNumber == "Sans"){
            $this->errNumber  = "0";
        } else {
            $this->errNumber  = "%";
        }

        #Normaliser les requêtes sur la base : si on ne précise pas de base on sélectionne toutes les bases, sinon on sélectionne les entrée qui commencent par ce que l'utilisateur a indiqué
        if($this->base == ""){
            $this->base = "%";
        } else {
            $this->base=$this->base ."%";
        }
    }
}
