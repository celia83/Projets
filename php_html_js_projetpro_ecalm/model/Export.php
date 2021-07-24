<?php
include_once "model/DataBase.php";

/**
 * Classe Export
 *
 * Cette classe contient la fonction nécessaire à la création de l'exemplier
 *
 * PHP version 5.6
 *
 * @author Océane Giroud <oceane.giroud@etu.univ-grenoble-alpes.fr>
 */
 
class Export {
	protected $word;
    protected $nbLine;

/**
     * Constructeur de Export.
     * @param string $word Le mot sélectionné par l'utilisateur.
     * @param string $nbLine Le nombre de lignes sélectionné par l'utilisateur.
     */
     
    public function __construct($word, $nbLine){
        $this->word = $word;
        $this->nbLine = $nbLine;
    }
    
     /**
     * Fonction sentences() : sélection des phrases pour l'exemplier.
     *
     * Cette fonction permet de sélectionner un nombre de phrases d'exemples choisies par l'utilisateur contenant le mot sélectionné.
     *
     * @return string $finalSentencesList La liste des phrases contenant le mot choisi par l'utilisateur. Le nombre de lignes est sélectionné par l'utilisateur.

     */
    
    public function sentences() {
        
        // Requête pour sélectionner les productions dans lesquelles apparaissent le lemme sélectionné limité au nombre de lignes sélectionné par l'utilisateur.
		$request="SELECT * FROM `ecalm` WHERE IdProd IN (SELECT * FROM (SELECT DISTINCT IdProd FROM `ecalm` WHERE `Lemme`= '".$this->word."' LIMIT ".$this->nbLine.") AS temp)";
		
		// Récupération des données
		$database = new DataBase();
		$tabSentences =$database->getData($request);
		
		$finalTabSentences =[];
		
		
		// Récupération de la phrase
		while (array_search($this->word, array_column($tabSentences, 'Lemme')) != false) {
            $indexLemme= array_search($this->word, array_column($tabSentences, 'Lemme'));

		    while (strstr($tabSentences[$indexLemme]["SegTrans"],"<sent>")== false) {
                $indexLemme--;
            }

            $sentence = "";
            while (strstr($tabSentences[$indexLemme+1]["SegTrans"],"</sent>") == false) {
                $sentence = $sentence .$tabSentences[$indexLemme+1]["SegTrans"]." ";
                $indexLemme ++;
            }
			
			// Traitement pour enlever toutes les balises inutiles
			$sentence = str_replace(["<FIN>","FIN","</FIN>","</sent>","<sent>","<titre>","</titre>","_","<p/>","<dialogue>","</dialogue>","<s/>","<segmentation/>","<unknown>","</ajout>","<ajout>","<nonfini/>","<omission type=&'pronom&'/>","<incomprehensible/>","<omission type=&'nom&'/>","<omission type=&'adjectif&'/>","<omission type=&'preposition&'/>","<omission type=&'verbe&'/>","<p/","<p/<dialogue>","<re><unknown>","<unsure>","</unsure>","<revision/>"],"",$sentence);
				 if(in_array($sentence, $finalTabSentences) == false ){
                array_push($finalTabSentences ,$sentence);
            }

            $tabSentences= array_slice($tabSentences, $indexLemme); ;
        }

        $finalTabSentences = array_slice($finalTabSentences, 0, $this->nbLine);
        $finalTabSentences = implode("\n",$finalTabSentences);
        
        return $finalTabSentences;
		
		}
		
	}

