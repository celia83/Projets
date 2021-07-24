package projetTALALAO.projetSELF;

import java.util.ArrayList;
import java.util.Hashtable;

import projetTALALAO.projetSELF.Files.FichierR;

public class Word {
	//PROPRIETES
		private String value;
		private Hashtable<String, ArrayList<String>> lexique ; // contient lexique3
		
		//CONSTRUCTEURS
		public Word() {
			//on d�finit ce qu'il se passe lors de l'instanciation
			value="";
			//on enregistre les infos de lexique3 pour les méthodes de mots
			FichierR file = new FichierR("src/projetTALALAO/projetSELF/Lexique383.csv");
			lexique = file.contenu();
		}
		
		public Word(String value) {
			this.value = value;
			//on enregistre les infos de lexique3 pour les méthodes de mots
			FichierR file = new FichierR("src/projetTALALAO/projetSELF/Lexique383.csv");
			lexique = file.contenu();
		}
		
		//METHODES
			//ACCESSEURS (GETTERS)
		public String getValeur() {
			return value;
		}
		
			//MODIFIEURS
		public void setValeur(String newValue) {
			value= newValue;
			
		}
		
			//METHODES SPECIFIQUES
				//Retourne un booléen permettant de savoir si le mot existe dans lexique3
		public boolean exists() {
			String[] tabPronouns={"je","j'","tu", "il", "elle", "on", "vous", "ils", "elles"};
			if(lexique.containsKey(value.toLowerCase())) {
				return true;
			}  else if (Sentence.inTab(value.toLowerCase(), tabPronouns)){
				return true;
			}
			else {
				return false;
			}
		}
				
				//Retourne la fréquence du mot
		public String freqLemMovie() {
			if(lexique.containsKey(value.toLowerCase())) {
				ArrayList<String> wordInfos = lexique.get(value.toLowerCase());
				String frequence = wordInfos.get(1);
				return frequence;
			} else {
				return "0";
			}
		}		
}
