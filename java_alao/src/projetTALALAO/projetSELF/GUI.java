package projetTALALAO.projetSELF;

import java.util.ArrayList;
import java.util.Scanner;

import java.util.Enumeration;
import java.util.Hashtable;
import java.io.File;
import java.io.BufferedReader;
import java.io.BufferedWriter;

import javax.swing.*;

import projetTALALAO.projetSELF.Files.FichierW;

import java.awt.event.*;

import javax.imageio.ImageIO;
import java.io.*;
import java.awt.*;
import java.awt.event.*;
import javax.swing.*;

public class GUI extends JFrame {
	public static String Rep1;
	public static String Rep2;
	public static Sentence sentence;
	
	public GUI(){
		super();
		build(); // Initialisation de la fenêtre
	}
	
			
	private void build(){
		setTitle("Exercices SELF"); // Taille
		setSize(650,800); // Dimension de la fenêtre
		setLocationRelativeTo(null); // Centrage de la fenêtre sur l'écran
		setResizable(true); // Le redimensionnement est accepté
		setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE); // Fermeture de l'application lors du clic sur la croix
		setContentPane(buildContentPane());
	}


	private JScrollPane buildContentPane(){ 
		
		final JPanel panel = new JPanel(); // Creation d'un panneau
		panel.setLayout(new BoxLayout(panel, BoxLayout.Y_AXIS)); // Situer le panneau
		panel.setBackground(Color.orange); // Couleur de fond
		
        // Contenu du panneau : 
		// Exercice 1 :
		JLabel label = new JLabel("<html>Voici une série d'exercices<br/><br/><br/></html>");
		Font font = new Font("Arial",Font.BOLD,20);
		label.setFont(font);
		panel.add(label); // Ajout du texte
		
		JLabel label4 = new JLabel("<html><br/>Exercice 1 : Transformation<br/>Sans oublier la ponctuation, écrivez cette phrase au passé composé : L'homme traverse la rue rapidement.<br/><br/><br/><br/> </html>");
		Font font4 = new Font("Arial",Font.BOLD,12);
		label4.setFont(font4);
		panel.add(label4); // Ajout du texte
		
		// Création d'une première zone de texte pour l'exercice 1
		JTextField testField1 = new JTextField("Ecrivez votre réponse à l'exercice 1.");
		JScrollPane scrollPane = new JScrollPane(testField1);
		scrollPane.setPreferredSize(new Dimension(200,70));
		panel.add(scrollPane);
		   
		// Exercice 2 :
		JLabel label2 = new JLabel("<html><br/><br/>Exercice 2 : Transformation<br/>Sans oublier la ponctuation, écrivez cette phrase au pluriel : Le petit chien court dans le pré.<br/> </html>");
		panel.add(label2);
		    
		// Céation d'une deuxième zone de texte pour l'exercice 2 :
		JTextField testField2 = new JTextField("Ecrivez votre réponse à l'exercice 2.");
		JScrollPane scrollPane1 = new JScrollPane(testField2);
		scrollPane1.setPreferredSize(new Dimension(200,70));
		panel.add(scrollPane1);
		
		// Exercice 3 
		JLabel label3 = new JLabel("<html><br/><br/><br/><br/><br/><br/>Exercice 3 : Ecriture<br/>Décrire cette image en deux phrases.<br/> </html>");
		panel.add(label3);
		
		// Affichage de l'image
	    String imgUrl="src/projetTALALAO/projetSELF/Images/chat.jpg";
	    ImageIcon icone = new ImageIcon(imgUrl);
	    JLabel jlabel = new JLabel(icone, JLabel.CENTER);
	    panel.add(jlabel);
	     
		// Création d'une troisième zone de texte	
		JTextField testField3 = new JTextField("Ecrivez votre réponse à l'exercice 3.");
		JScrollPane scrollPane3 = new JScrollPane(testField3);
		scrollPane3.setPreferredSize(new Dimension(200,70));
		panel.add(scrollPane3);
		
		// Exercice 4 
		JLabel label5 = new JLabel("<html><br/><br/><br/><br/><br/><br/>Exercice 4 : Ecriture<br/>Décrire cette image en deux phrases.<br/> </html>");
		panel.add(label5);
				
		// Affichage de l'image
		String imgUrl1="src/projetTALALAO/projetSELF/Images/garcon.jpg";
		ImageIcon icone1 = new ImageIcon(imgUrl1);
		JLabel jlabel1 = new JLabel(icone1, JLabel.CENTER);
		panel.add(jlabel1);
			     
		// Création d'une quatrième zone de texte	
		JTextField testField4 = new JTextField("Ecrivez votre réponse à l'exercice 4.");
		JScrollPane scrollPane4 = new JScrollPane(testField4);
		scrollPane4.setPreferredSize(new Dimension(200,70));
		panel.add(scrollPane4);
		
	    // Création du bouton 
	    JButton b2 = new JButton("click");
	    b2.setBounds(230,200,200,80); // Dimension du bouton : x, y, width, height  
	    panel.add(b2); // Ajout du bouton
	    this.getContentPane().add(new JScrollPane(panel));

	     // Actionneur du bouton
	     b2.addActionListener(new ActionListener(){
	    	 public void actionPerformed(ActionEvent arg0){
	    		 	String Ex1 = testField1.getText();
					if (testField1.getText().equals("L'homme a traversé la rue rapidement.")) {
						String m = JOptionPane.showInputDialog("Bonne réponse !");
						Rep1 = ("Pour l'exercice 1, l'apprenant a donné la bonne réponse.");
					} else {
						String m = JOptionPane.showInputDialog("Mauvaise réponse !","L'homme a traversé la rue rapidement.");
						Rep1 = ("Pour l'exercice 1, l'apprenant n'a pas donné la bonne réponse, puisqu'il a produit la phrase suivante : "+Ex1);
					}
					String Ex2 = testField2.getText();
					if (testField2.getText().equals("Les petits chiens courent dans les prés.")) {
						String m = JOptionPane.showInputDialog("Bonne réponse !");
						Rep2 = ("Pour l'exercice 2, l'apprenant a donné la bonne réponse.");
					} else {
						String m = JOptionPane.showInputDialog("Mauvaise réponse !", "Les petits chiens courent dans les prés.");
						Rep2 = ("Pour l'exercice 2, l'apprenant n'a pas donné la bonne réponse, puisqu'il a produit la phrase suivante : "+Ex2);
					}
					String Ex3 = testField3.getText();	
					String Ex4 = testField4.getText();
					String Ex5 = Ex3+Ex4;	
					Sentence sentence = new Sentence (Ex5);

					// Ouverture du fichier résultat
					FichierW ficOut = new FichierW("resultats.txt");
					//On va écrire dedans :
					ficOut.ecrire("Voici la synthèse des exercices de l'apprenant : \n");
					ficOut.ecrire(Rep1);
					ficOut.ecrire(Rep2);
					ficOut.ecrire("Dans l'exercice 3, l'apprenant a produit la phrase suivante : "+Ex3);					
					ficOut.ecrire("Dans l'exercice 4, l'apprenant a produit la phrase suivante : "+Ex4);
					ficOut.ecrire("La longueur moyenne des mots de la production : "+sentence.wordLength());
					ficOut.ecrire("Les catégories utilisées : "+sentence.repartitionPOS());
					ficOut.ecrire("La longueur moyenne des phrases : "+sentence.sentenceLength());
					try {
						ficOut.ecrire("Le nombre de mots sans doublons utilisés : "+sentence.nbLemma());
					} catch (Exception e1) {
						// TODO Auto-generated catch block
						e1.printStackTrace();
					}
					try {
						ficOut.ecrire("La richesse lexicale : "+sentence.richesseLexicale());
					} catch (Exception e1) {
						// TODO Auto-generated catch block
						e1.printStackTrace();
					}
					Hashtable<Word, Integer> tab = new Hashtable<Word, Integer>();
					tab = sentence.repetedWords();
					Enumeration e = tab.keys() ;
					Enumeration f = tab.elements() ;
					while (e.hasMoreElements() && f.hasMoreElements()){
						Word word = (Word) e.nextElement();
						Object g = f.nextElement();
						ficOut.ecrire("Le mot "+word.getValeur()+" est répété "+g+" fois.");
					}
					if(sentence.nonExistingWords().isEmpty()) {
							ficOut.ecrire("Tous les mots utilisés par l'apprenant existent.");
					}
					else {
						ficOut.ecrire("L'apprenant a utilisé un ou des mot(s) n'existant(s) pas, dont voici la liste : "+sentence.nonExistingWords());
					}
					ficOut.fermer();
				}
	     });	
	     		
	    //Création de la barre de scroll
	    final JScrollPane scroll = new JScrollPane(panel);
	    setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
	    setLayout(new BorderLayout());
	    add(scroll, BorderLayout.CENTER);
	    setVisible(true);
		return scroll;
	}
	
	public static void main(String[] args) throws Exception {				
		//On crée une nouvelle instance de notre FenetreTexte
		GUI fenetre = new GUI();
		fenetre.setVisible(true);//On la rend visible
	}
}