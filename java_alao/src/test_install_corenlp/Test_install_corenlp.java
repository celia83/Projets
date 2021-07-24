package test_install_corenlp;

import java.util.List;

import java.util.Properties;

import org.apache.log4j.BasicConfigurator;

import edu.stanford.nlp.ling.CoreAnnotations;
import edu.stanford.nlp.ling.CoreAnnotations.PartOfSpeechAnnotation;
import edu.stanford.nlp.ling.CoreAnnotations.TokensAnnotation;
import edu.stanford.nlp.ling.CoreLabel;
import edu.stanford.nlp.pipeline.Annotation;
import edu.stanford.nlp.pipeline.StanfordCoreNLP;
import edu.stanford.nlp.trees.Tree;
import edu.stanford.nlp.trees.TreeCoreAnnotations.TreeAnnotation;
import edu.stanford.nlp.util.CoreMap;

public class Test_install_corenlp {

	public static void main(String[] args) {
		// TODO Auto-generated method stub
		BasicConfigurator.configure();
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
		
		// Lance le pipeline sur une chaine de caractères
		String text = "Le chat dort dans le salon. Les souris dansent. Un fromage dans chaque patte.";
		// créer une annotation vide avec le texte
		Annotation document = new Annotation(text);
		// lance l'annotation sur le texte
		pipeline.annotate(document);
		
		List<CoreMap> sentences = document.get(CoreAnnotations.SentencesAnnotation.class);
		for (CoreMap sentence : sentences) {
		// obtenir l'arbre d'analyse de chaque phrase
		//Tree parseTree = sentence.get(TreeAnnotation.class);
		// Ecrire l'arbre
		//System.out.println(parseTree);
		// traitement des tokens
		for (CoreLabel token: sentence.get(TokensAnnotation.class)) {
			String pos = token.get(PartOfSpeechAnnotation.class);
			String word = token.word();
			System.out.println(pos +" "+ word);
		}
		}

	}

}
