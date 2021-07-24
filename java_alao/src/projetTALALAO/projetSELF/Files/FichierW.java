package projetTALALAO.projetSELF.Files;

import java.io.BufferedWriter;
import java.io.FileWriter;
import java.io.IOException;
import java.io.PrintWriter;

public class FichierW {
	PrintWriter pw;
	//Constructeur
	public FichierW(String chemin) {
		try {
			//créer un pointeur (ou plutôt un handler) sur un fichier, le fichier est ouvert et près à être rédigé :
			pw = new PrintWriter( // permet d'écrire et manipuler les chaines de caractères dans un fichier qui utilise l'écriture d'octets
					new BufferedWriter( // permet d'accéler l'écriture dans des fichiers
							new FileWriter(chemin))); //permet de créer le fichier
		} catch (IOException e){
			System.out.println("Impossible d'ouvrir le fichier : " + chemin);
		}
	}
	
	public void fermer() {
		pw.close();
	}
	
	public boolean ecrire(String ligne) {
		try  {
			pw.println(ligne);
			return true;
		}catch (Exception e){
			return false;
		}
	}
	
}

