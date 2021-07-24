package projetTALALAO.projetSELF;

import java.util.ArrayList;
import java.util.Scanner;

import projetTALALAO.projetSELF.Files.FichierW;

import java.util.Enumeration;
import java.util.Hashtable;
import java.io.File;
import java.io.BufferedReader;
import java.io.BufferedWriter;

public class projetSELF {
	public static String Rep1;
	public static String Rep2;

	public static void main(String[] args) throws Exception {
		// TODO Auto-generated method stub
		//Word word1 = new Word("abatteurs");
		//System.out.println(word1.exists());
		
	//	Sentence sentence = new Sentence ("");
		
		Scanner sc = new Scanner(System.in);
		System.out.println("Voici une s�rie d'exercices. \n");
		System.out.println("Appuyez sur 'Entr�e' pour commencer. \n");
		String entree = sc.nextLine();
		System.out.println("Exercice 1 : Transformation");
		System.out.println("Sans oublier la ponctuation, �crivez cette phrase au pass� compos� : ");
		System.out.println("L�homme traverse la rue rapidement.");
		String transformation1 = sc.nextLine();
		if (transformation1.equals("L'homme a travers� la rue rapidement.")) {
			System.out.println("Bonne r�ponse \n");
			Rep1 = ("Pour l'exercice 1, l'apprenant a donn� la bonne r�ponse.");
		} else {
			System.out.println("Mauvaise r�ponse : L'homme a travers� la rue rapidement. \n");
			Rep1 = ("Pour l'exercice 1, l'apprenant n'a pas donn� la bonne r�ponse, puisqu'il a produit la phrase suivante : "+transformation1);
		}
		System.out.println("Exercice 2 : Transformation");
		System.out.println("Sans oublier la ponctuation, �crivez cette phrase au pr�sent :");
		System.out.println("Le chat a mang� lentement la souris.");
		String transformation2 = sc.nextLine();
		if (transformation2.equals("Le chat mange lentement la souris.")) {
			System.out.println("Bonne r�ponse \n");
			Rep2 = ("Pour l'exercice 2, l'apprenant a donn� la bonne r�ponse.");
		} else {
			System.out.println("Mauvaise r�ponse : Le chat mange lentement la souris. \n");
			Rep2 = ("Pour l'exercice 2, l'apprenant n'a pas donn� la bonne r�ponse, puisqu'il a produit la phrase suivante : "+transformation2);
		}
 
		System.out.println("Exercice 3 : Ecriture");
		System.out.println("Racontez ce que vous avez fait hier en quatre phrases.");
		String ecriture = sc.nextLine();
		Sentence sentence = new Sentence (ecriture);
		
		
		
		
	//	System.out.println(sentence.getTabToken());
	//	System.out.println(sentence.nonExistingWords());
	//	System.out.println(sentence.frequence());
		
		
		
		
		// Ouverture du fichier r�sultat
		FichierW ficOut = new FichierW("resultats.txt");
		//On va �crire dedans :
		ficOut.ecrire("Voici la synth�se des exercices de l'apprenant : \n");
		ficOut.ecrire(Rep1);
		ficOut.ecrire(Rep2);
		ficOut.ecrire("La longueur moyenne des mots de la production : "+sentence.wordLength());
		ficOut.ecrire("Les cat�gories utilis�es : "+sentence.repartitionPOS());
		ficOut.ecrire("La longueur moyenne des phrases : "+sentence.sentenceLength());
		ficOut.ecrire("Le nombre de mots sans doublons utilis�s : "+sentence.nbLemma());
		ficOut.ecrire("La richesse lexicale : "+sentence.richesseLexicale());
		Hashtable<Word, Integer> tab = new Hashtable<Word, Integer>();
		tab = sentence.repetedWords();
		Enumeration e = tab.keys() ;
		Enumeration f = tab.elements() ;
		while (e.hasMoreElements() && f.hasMoreElements()){
			Word word = (Word) e.nextElement();
			//System.out.println(word.getValeur());
			//System.out.println(f.nextElement());
			Object g = f.nextElement();
		//	System.out.println(g);
			
			ficOut.ecrire("Le mot "+word.getValeur()+" est r�p�t� "+g+" fois.");
		}
		if(sentence.nonExistingWords().isEmpty()) {
			ficOut.ecrire("Tous les mots utilis�s par l'apprenant existent.");
		}
		else {
			ficOut.ecrire("L'apprenant a utilis� un ou des mot(s) n'existant(s) pas, dont voici la liste : "+sentence.nonExistingWords());
		}
		ficOut.fermer();
		
		
	//	System.out.println(sentence.wordLength());
		
		//System.out.println(sentence.repartitionPOS());
		
	//	System.out.println(sentence.sentenceLength());

		
		//System.out.println(sentence.nbLemma());
		
		//System.out.println(sentence.richesseLexicale());
		
		 
	}

}
