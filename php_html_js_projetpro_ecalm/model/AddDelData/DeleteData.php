<?php

include_once "model/DataBase.php";

/**
 * Classe DeleteData
 *
 * Cette classe permet la suppression des données présentes sur la base de donnée à partir de paramètres choisi par l'utilisateur.
 *
 * PHP version 5.6 
 *
 * @author Jingyu Liu <jingyu.liu@etu.univ-grenoble-alpes.fr>
 */
class DeleteData 
{

	protected $corpus;
    protected $level;

    /**
     * Constructeur de DeleteData.
     *
     * Enregistre les choix faits par l'utilisateur pour la suppression des données.
     *
     * @param string $corpus Le nom d'un corpus choisi par l'utilisateur parmi les trois corpus du projet E-calm (Scoledit, Ecriscol ou Resolco)
     * @param string $level Le niveau pour lequel appliquer la suppression (du CP au M2).
     */
    public function __construct($corpus, $level){
        $this->corpus=$corpus;
        $this->level=$level;
    }

    /**
     * Fonction deleteData() : suppression des données dans la base de données.
     *
     * Cette fonction permet de sélectionner dans la base de données toutes les lignes qui correspondent au corpus et au niveau sélectionnés par l'utilisateur et de les supprimer.
     *
     * @return int $nbLineAffected Le nombre de lignes supprimées lors de l'application de la fonction
     * @throws Exception
     */
	public function deleteData(){

		#Normaliser les critères pour que la requête soit adaptées au données de la bdd
        $this->normalizeCorpusName();
        
        #Rédiger la requête pour supprimer les données de la base de données
		$request = 'DELETE FROM `ecalm` 
WHERE IdTok REGEXP "'.$this->corpus.'" 
AND Niv LIKE "'.$this->level.'"';
		
        $database = new DataBase();
        $nbLineAffected = $database->addOrDelData($request);
        return $nbLineAffected;
	 }

    /**
     * Fonction normalizeCorpusName() : normalisation du nom du corpus sélectionné par l'utilisateur
     *
     * Cette fonction permet de passer du mot désignant le corpus à une expression régulière permettant d'identifier la corpus dans IdTok (ligne unique décrivant les mots présents dans la base de données). En effet, IdTok contient la première lettre du corpus (S, E ou R), c'est cette lettre que les expressions régulières permettent de trouver pour sélectionner le corpus adéquat dans la base de données.
     *
     * @return void
     */
	protected function normalizeCorpusName(){
        #Normalisation du corpus : E : Ecriscol, S : Scoledit, R : Resolco, sinon on sélectionne tous les corpus avec %
        if ($this->corpus == "Scoledit"){
            $this->corpus = "[A-Z]+-[a-zA-Z]+[0-9]+-[0-9]+-([a-zA-Z]+|[0-9]+)-[a-zA-Z][0-9]-S[0-9]+-[A-Z][0-9]-[0-9]+-[0-9]+";
        } elseif ($this->corpus == "Ecriscol"){
            $this->corpus = "[A-Z]+-[a-zA-Z]+[0-9]+-[0-9]+-([a-zA-Z]+|[0-9]+)-[a-zA-Z][0-9]-E[0-9]+-[A-Z][0-9]-[0-9]+-[0-9]+";
        } elseif ($this->corpus == "Resolco"){
            $this->corpus = "[A-Z]+-[a-zA-Z]+[0-9]+-[0-9]+-([a-zA-Z]+|[0-9]+)-[a-zA-Z][0-9]-R[0-9]+-[A-Z][0-9]-[0-9]+-[0-9]+";
        } elseif ($this->corpus == "Littéracie"){
            $this->corpus = "[A-Z]+-[a-zA-Z]+[0-9]+-[0-9]+-([a-zA-Z]+|[0-9]+)-[a-zA-Z][0-9]-L[0-9]+-[A-Z][0-9]-[0-9]+-[0-9]+";
        } else {
            $this->corpus = "[A-Z]+-[a-zA-Z]+[0-9]+-[0-9]+-([a-zA-Z]+|[0-9]+)-[a-zA-Z][0-9]-[a-zA-Z][0-9]+-[A-Z][0-9]-[0-9]+-[0-9]+";
        }
	}
}
