package projetTALALAO.projetSELF;

import static java.util.Arrays.asList;

import java.util.ArrayList;
import java.util.Enumeration;
import java.util.HashSet;
import java.util.List;
import java.util.Properties;
import java.util.Set;
import java.util.Hashtable;

import org.annolab.tt4j.TokenHandler;
import org.annolab.tt4j.TreeTaggerWrapper;
import org.apache.commons.lang3.ArrayUtils;
import org.apache.log4j.BasicConfigurator;

import edu.stanford.nlp.ling.CoreAnnotations;
import edu.stanford.nlp.ling.CoreLabel;
import edu.stanford.nlp.ling.CoreAnnotations.LemmaAnnotation;
import edu.stanford.nlp.ling.CoreAnnotations.PartOfSpeechAnnotation;
import edu.stanford.nlp.ling.CoreAnnotations.TokensAnnotation;
import edu.stanford.nlp.pipeline.Annotation;
import edu.stanford.nlp.pipeline.StanfordCoreNLP;
import edu.stanford.nlp.util.CoreMap;


public class Sentence {
	private String value; //la valeur de la phrase
	private ArrayList<Word> tabTokens ; // tableau des mots de la phrase
	private ArrayList<String> tabPOS; // contient les POS de chaque mot de tabToken
	
	
	//CONCTRUCTEURS
	public Sentence() {
		value = "";
	}
	
	public Sentence(String value) {
		this.value = value; //On enregistre le contenu de la phrase entrée
		tabTokens = new ArrayList<Word>(); 
		tabPOS = new ArrayList<String>();
		Hashtable<ArrayList<Word>, ArrayList<String>> tab = tokenizeCORENLP(value); //hashtable : {tableau des tokens : tableau des POS}
		Enumeration e = tab.keys() ;
		Enumeration f = tab.elements() ;
		while (e.hasMoreElements() && f.hasMoreElements()){
			tabTokens = (ArrayList<Word>) e.nextElement(); //enregistrer dans les propriétés le tableau des tokens
			tabPOS = (ArrayList<String>) f.nextElement(); //et des POS
		}
	}
	
	//METHODES
		//ACCESSEURS
	public String getValue() {
		return value;
	}
			
		// retourne un tableau des mots de la phrases
	public ArrayList<String> getTabToken() { 
		ArrayList<String> tabValues = new ArrayList<String>();
		for (int i = 0 ; i < tabTokens.size(); i++) {
			Word word = tabTokens.get(i);
			String valeur =  word.getValeur();
			tabValues.add(valeur);
		}
		return tabValues;
	}
	
		//MODIFIEURS
	public void setValue(String value) {
		this.value = value;
		
		tabTokens = new ArrayList<Word>();
		tabPOS = new ArrayList<String>();
		Hashtable<ArrayList<Word>, ArrayList<String>> tab = tokenizeCORENLP(value);
		Enumeration e = tab.keys() ;
		Enumeration f = tab.elements() ;
		while (e.hasMoreElements() && f.hasMoreElements()){
			tabTokens = (ArrayList<Word>) e.nextElement();
			tabPOS = (ArrayList<String>) f.nextElement();
		}
	}
	
	//METHODES
		//Retourne un tableau des mots non-existants
	public ArrayList<String> nonExistingWords(){
		ArrayList<String> tabNonExistingWords = new ArrayList<String>();
		for (int i = 0 ; i < tabTokens.size() ; i++) {
			if (tabTokens.get(i).exists() == false) {
				String word = tabTokens.get(i).getValeur();
				tabNonExistingWords.add(word);		
				tabNonExistingWords.remove(".");
				tabNonExistingWords.remove("!");
				tabNonExistingWords.remove("?");
				tabNonExistingWords.remove(",");
				tabNonExistingWords.remove(";");
				tabNonExistingWords.remove("...");
			}
		}
		return tabNonExistingWords;
	}
	
		//Retourne le fréquence des mots utilisés
	public Hashtable<Word, String> frequence(){
		Hashtable<Word, String> tabFreq = new Hashtable<Word, String>() ;
		for (int i = 0 ; i < tabTokens.size() ; i++) {
			Word word = tabTokens.get(i);
			String frequence = word.freqLemMovie();
			tabFreq.put(word, frequence);
			
		}
		return tabFreq;
	}
	
		// Retourne un hashtable des doublons de la phrase et le nombre de fois qu'ils apparaissent : {chat : 3}
	public Hashtable<Word, Integer> repetedWords(){
		String tabPunct[] = {",","?",".", ";","!",":","'","(",")"};
		Hashtable<Word, Integer> tabRepetedWords = new Hashtable<Word, Integer>();
		for (int i = 0; i< tabTokens.size(); i++) { // Pour chaque token du tableau de tokens
			int freq = 1 ; // COmme on a un mot on peut ajouter 1
			for (int j = i+1 ; j < tabTokens.size() ; j++) { // on parcourt le tableau avec i+1 pour trouver les doublons
				//SI le token n'est pas une ponctuation et est est présent plus d'une fois dans la phrase on ajoute à chaque fois 1 à la fréquence
				if (tabTokens.get(i).getValeur().toLowerCase().equals(tabTokens.get(j).getValeur().toLowerCase())&& !inTab(tabTokens.get(i).getValeur().toLowerCase(), tabPunct)) {
					freq ++;
				}
			}
			if (freq > 1 ) {
				tabRepetedWords.put(tabTokens.get(i), freq);
			}
		}
		return tabRepetedWords;
	}
	
		//Retourne la longueur moyenne des mots de la phrase
	public float wordLength() {
		float totalWordLength =0;
		float nbWords =0 ;
		for (int i = 0; i< tabTokens.size(); i++) {
			if (!tabPOS.get(i).equals("PUNC")) {
				int wordLength = tabTokens.get(i).getValeur().length();
				totalWordLength += wordLength;
				nbWords ++;
			}
		}
		float meanWordLength = totalWordLength / nbWords;
		return meanWordLength;
	}
	
		//Retourne la longueur moyenne des phrases (nombre de mots par phrase)
	public float sentenceLength() {
		float nbSentence = 0;
		float nbWords =0;
		String tabPunct[] = {".","!","?"};
		for (int i = 0; i < tabTokens.size(); i++) { // on prend un mot dans le tableau de mots
			if (inTab(tabTokens.get(i).getValeur(), tabPunct)) { // Si ce mot est dans la liste des ponctuations de fin de phrase on ajoute 1 à nbSentence car ça veut dire qu'on est à la fin d'une phrase
				nbSentence ++;
			} else if (!tabPOS.get(i).equals("PUNCT")) { //Si ce n'est pas un point et que ce n'est pas non plus un autre signe de ponctuation on ajoute 1 au nombre de mots
				nbWords++;
			} //sinon on ne fait rien
		}
		float sentenceLength = nbWords / nbSentence; // une fois qu'on a compté le nombre de mots et de phrases on peut faire la moyenne
		return sentenceLength;
	}
	
		//Retourne le nombre de POS : {verbe : 3; adj : 2}
	public Hashtable<String, Integer> repartitionPOS() {
		Hashtable<String, Integer> tabRepartitionPOS = new Hashtable<String,Integer>();
		for (int i = 0; i< tabPOS.size(); i++) {
			int freq = 0 ;
			for (int j = 0 ; j < tabPOS.size() ; j++) {
				if (tabPOS.get(i).equals(tabPOS.get(j))) {
					freq ++;
				}
			}
			tabRepartitionPOS.put(tabPOS.get(i), freq);
		}
		return tabRepartitionPOS;
	}
	
	//Retourne le nombre de lemmes de la phrase
	public int nbLemma() throws Exception {
		ArrayList<String> tabLemma = new ArrayList<String>(); // contiendra la liste de tous les lemmes de la phrase
		ArrayList<String> tabTokens = this.getTabToken();
		//enlever la ponctuation
		ArrayList<String> newTabTokens = new ArrayList<String>();
		for (int i = 0 ; i < tabTokens.size(); i++) {
			if (!tabPOS.get(i).equals("PUNC")) {
				newTabTokens.add(tabTokens.get(i));
			}
		}
		// on crée un tableau pour le donner à treetagger
		String [] tabWords= new String[newTabTokens.size()]; 
		for (int i = 0 ; i < newTabTokens.size(); i++) { //on prend un mot du tableau de tokens
			//on l'ajoute au tableau des mots
				String word = newTabTokens.get(i);
				tabWords[i] = word;
		}
		//on donne le tableau à treetagger et il en ressort une liste des lemmes
		tabLemma = lemmaTreetagger(tabWords);
		//on enlève les doublons
		Set<String> mySet = new HashSet<String>(tabLemma);
		List<String> tabLemmaSansDoublons = new ArrayList<String>(mySet);
		//on compte le nombre total de lemmes
		int nbLemma = tabLemmaSansDoublons.size();
		return nbLemma;
	}
	
	//Calcule la richesse lexicale (lemme / occurrences)
	public float richesseLexicale() throws Exception {
		ArrayList<String> tokens = this.getTabToken();
		float nbLemma = this.nbLemma();
		//enlever la ponctuation
		ArrayList<String> newTabTokens = new ArrayList<String>();
		for (int i = 0 ; i < tokens.size(); i++) {
			if (!tabPOS.get(i).equals("PUNC")) {
				newTabTokens.add(tokens.get(i));
			}
		}
		
		//Compter le nombre d'occurrences
		float nbOccurrences = newTabTokens.size();
		
		//Calculer le rapport lemme/occurrences
		float richesseLexicale = nbLemma / nbOccurrences;
		return richesseLexicale;
	}
	
	//METHODES STATIQUES
		//Cette méthode retourne un tableau d'objets Word qui sont les tokens de la phrase (trouvés à l'aide de CoreNLP)
	private static Hashtable<ArrayList<Word>, ArrayList<String>> tokenizeCORENLP(String value){
		Hashtable<ArrayList<Word>, ArrayList<String>> resultats = new Hashtable<ArrayList<Word>, ArrayList<String>>();
		ArrayList<Word> tabWords = new ArrayList<Word>();
		ArrayList<String> tabPOS = new ArrayList<String>();
		//TOKENISER LA PHRASE AVEC CORENLP
		BasicConfigurator.configure(); // supprimer quelques warning
		// création d'un objet Properties
		Properties props = new Properties();
		// définition du pipeline
		props.setProperty("annotators", "tokenize, ssplit, parse, lemma, ner");
		// paramétrage pour le français
		props.setProperty("props", "StanfordCoreNLP-french.properties");
		props.setProperty("tokenize.language","French");
		props.setProperty("parse.model","edu/stanford/nlp/models/lexparser/frenchFactored.ser.gz");
		props.setProperty("pos.model","edu/stanford/nlp/models/pos-tagger/french/french.tagger");
		props.setProperty("tokenize.verbose","false"); // True = affiche les tokens
		props.setProperty("ner.useSUTime", "false");
		// création du pipeline
		StanfordCoreNLP pipeline = new StanfordCoreNLP(props);
		
		// Lance le pipeline sur la valeur
		String text = value;
		// créer une annotation vide avec le texte
		Annotation document = new Annotation(text);
		// lance l'annotation sur le texte
		pipeline.annotate(document);
		
		List<CoreMap> sentences = document.get(CoreAnnotations.SentencesAnnotation.class);
		for (CoreMap sentence : sentences) {
		// traitement des tokens
			for (CoreLabel token: sentence.get(TokensAnnotation.class)) {
				String pos = token.get(PartOfSpeechAnnotation.class);
				tabPOS.add(pos);
				//System.out.println(pos);
				Word word = new Word(token.word());
				//System.out.println(word.getValeur());
				tabWords.add(word);
				
			}
		}
		resultats.put(tabWords, tabPOS);
		return resultats ;
	}
	
	//Savoir si un élément existe dans un tableau de String
	public static boolean inTab(Object elt,String[] tab) {
		boolean exist = false;
		//System.out.println("élément comparé : " + elt);
		for(int i = 0 ; i<tab.length;i++){
			//System.out.println("Il est comparé à : "+tab[i]);
			   if(elt.equals(tab[i])) {
			     exist = true;
			  }
		}
		//System.out.println("Donc exist est :" + exist);
		return exist;
	}
	

	//Prend une liste de mots en entrée et en sort une liste des lemmes
	public static ArrayList<String> lemmaTreetagger(String[] word) throws Exception {
    // Point TT4J to the TreeTagger installation directory. The executable is expected
    // in the "bin" subdirectory - in this example at "/opt/treetagger/bin/tree-tagger"
		ArrayList<String> tabLemma = new ArrayList<String>();
		
		System.setProperty("treetagger.home", "src/Treetagger");
		TreeTaggerWrapper tt = new TreeTaggerWrapper<String>();
		try {
			tt.setModel("src/Treetagger/lib/french.par");
			tt.setHandler(new TokenHandler<String>() {
				public void token(String token, String pos, String lemma) {
					tabLemma.add(lemma);
				}
			});
			tt.process(asList(word));
		}
		finally {
			tt.destroy();
		}
		return tabLemma;
	}
}
