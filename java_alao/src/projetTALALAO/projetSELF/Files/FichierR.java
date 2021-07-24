package projetTALALAO.projetSELF.Files;

import java.io.BufferedReader;
import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.IOException;
import java.util.ArrayList;
import java.util.*;


public class FichierR {
	private String nom;
	private BufferedReader dis;
	Hashtable<String, ArrayList<String>> tabWords; //contient un hashage {mot : [lemme, frequence_des_lemmes_films]}

	
	public FichierR(String nom) {
		//Ouverture d'un fichier
		this.nom = nom;
		tabWords = new Hashtable<String, ArrayList<String>>();
		try {
			dis = new BufferedReader( // accÃ©lÃ¨re la lecture du fichier
					new FileReader(  //gÃ¨re la lecture du fichier
							new File(nom))); // gÃ¨re la lecture octet par octet		
			
			String line;
			try {
				while ((line=dis.readLine())!=null) { //tant que readLine n'est pas null donc qu'on n'a pas atteint la fin du fichier
					String[] cutLine = line.split(";");
					String word = cutLine[0];
					String lemma = cutLine[2];
					String freqLemMovies = cutLine[6];
					ArrayList<String> wordInfos = new ArrayList<String>();
					wordInfos.add(lemma);
					wordInfos.add(freqLemMovies);
					tabWords.put(word, wordInfos);	
				}
			} catch (IOException e) {
				e.printStackTrace();
			}
		} catch (FileNotFoundException e) {
			e.printStackTrace();
		} catch (IOException e) {
			e.printStackTrace();
		}
	}
	
	public Hashtable contenu() {
		return tabWords; 
	}

}
