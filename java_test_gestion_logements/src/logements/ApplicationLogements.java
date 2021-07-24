package logements;

import java.util.*;

public class ApplicationLogements {

	public static void main(String[] args) {
		//Lire une action
		Scanner sc = new Scanner(System.in);
		System.out.println("Quelle action voulez-vous effectuer ? (E : voir les logements existants, "
				+ "C : créer un nouveau logement, M : modifier les caractéristiques d'un logement, "
				+ "S : modifier la superficie d'une maison, Q : quitter) " );
		
		String action = sc.nextLine();
		Logements log;	//permettra de créer les différents types de logement
		ArrayList<Logements> tabLogements = new ArrayList<Logements>(); // créer un tableau qui contiendra tous les logements créés
		
		//Tant que l'utilisateur ne quitte l'application pas en appuyant sur "Q"
		while (!action.equals("Q")) {
			switch(action) {
			case "E" : // Montrer les logements et leurs caractéristiques
				for (int i=0; i<tabLogements.size(); i++) {
					System.out.println(tabLogements.get(i).caracteristiques());
				}
				System.out.println("Voulez-vous effectuer une autre action ? (E : voir les logements existants, "
						+ "C : créer un nouveau logement, M : modifier les caractéristiques d'un logement, "
						+ "S : modifier la superficie d'une maison, Q : quitter) " );
				action = sc.nextLine();
				break;
				
			case "C" : //Créer un logement, une maison ou un appartement
				System.out.println("Quel type de logement voulez-vous créer ? (L : normal, M : maison, A : Appartement) ");
				String type = sc.nextLine();
				System.out.println("Quelle est l'adresse du logement ? ");
				String adresse = sc.nextLine();
				System.out.println("Quelle est la superficie du logement ? ");
				float superficie = Float.parseFloat(sc.nextLine());
				if (type.equals("M")){	//Créer une maison
					log = new Maison(adresse, superficie);
					tabLogements.add(log);
				} else if (type.equals("A")){ //Créer un appartement et demander s''il est aux normes
					System.out.println("L'appartement est-il au normes ? (oui/non)");
					String setNormes = sc.nextLine();
					boolean normes;
					if (setNormes.equals("oui")) {
						normes = true;
					} else {
						normes = false;
					}
					log = new Appartement(adresse, superficie, normes);
					tabLogements.add(log);
				} else {	//Sinon on crée un logement normal
					log = new Logements(adresse, superficie);
					tabLogements.add(log);
				}
				//Refaire une action
				System.out.println("Voulez-vous effectuer une autre action ? (E : voir les logements existants, "
						+ "C : créer un nouveau logement, M : modifier les caractéristiques d'un logement, "
						+ "S : modifier la superficie d'une maison, Q : quitter) " );
				action= sc.nextLine();
				break;
				
			case "M" : //Modifier les catactéristiques des logements créés
				//Demander à l'utilisateur ce qu'il veut modifier
				System.out.println("Que voulez-vous faire ? (C : modifier le prix, P : modifier le propriétaire, "
						+ "A : l'adresse du logement, T : faire des travaux de valorisation, M : modifier le quartier ");
				String sous_action = sc.nextLine();
				//Comme pour le code principal : tant que l'utilisateur ne quitte pas en faisant "Entrée"
				while (!sous_action.equals("")) {
					switch(sous_action) {
					case "C" : //Modifier le prix du logement
						System.out.println("De quelle logement voulez-vous modifier le prix ? (entrez l'identifiant)");
						String logement = sc.nextLine();
						System.out.println("Entrez le nouveau prix : ");
						float prix = Float.parseFloat(sc.nextLine()); // transformer le String en float car nextFloat() ne permet pas d'utiliser le prochain Scanner
						for (int i =0; i<tabLogements.size(); i++) { //parcours du tableau de logement pour trouver le logement à modifier
							if (tabLogements.get(i).getId().equals(logement)) { //comparer l'identifiant donné par l'utilisateur et l'id des logements du tableau de logement
								tabLogements.get(i).modifierPrix(prix);
							}
						}
						//Relancer la demande d'action
						System.out.println("Voulez-vous faire d'autres modifications ou appuyer sur entrée pour revenir au menu principal ? (C : modifier le prix, "
								+ "P : modifier le propriétaire, A : l'adresse du logement, T : faire des travaux de valorisation, M : modifier le quartier ");
						sous_action = sc.nextLine();
						break;
						
					case "P" : //Changer de propriétaire
						System.out.println("De quelle logement voulez-vous changer le propriétaire ? (entrez l'identifiant)");
						String logement_proprio = sc.nextLine();
						System.out.println("Entrez le nom du propriétaire : ");
						String proprietaire  = sc.nextLine();
						for (int i =0; i<tabLogements.size(); i++) {
							if (tabLogements.get(i).getId().equals(logement_proprio)) {
								tabLogements.get(i).changeProprio(proprietaire);
							}
						}
						System.out.println("Voulez-vous faire d'autres modifications ou appuyer sur entrée pour revenir au menu principal ? (C : modifier le prix, "
								+ "P : modifier le propriétaire, A : l'adresse du logement, T : faire des travaux de valorisation, M : modifier le quartier ");
						sous_action = sc.nextLine();
						break;
					
					
					case "A" : //Changer l'adresse
						System.out.println("De quelle logement voulez-vous changer l'adresse ? (entrez l'identifiant)");
						String logement_adresse = sc.nextLine();
						System.out.println("Entrez l'adresse : ");
						String new_adresse  = sc.nextLine();
						for (int i =0; i<tabLogements.size(); i++) {
							if (tabLogements.get(i).getId().equals(logement_adresse)) {
								tabLogements.get(i).nouvelleAdresse(new_adresse);
							}
						}
						System.out.println("Voulez-vous faire d'autres modifications ou appuyer sur entrée pour revenir au menu principal ? (C : modifier le prix, "
								+ "P : modifier le propriétaire, A : l'adresse du logement, T : faire des travaux de valorisation, M : modifier le quartier ");
						sous_action = sc.nextLine();
						break;
						
					case "T" : //Effectuer des travaux
						System.out.println("Dans quel logement voulez-vous effectuer les travaux ? (entrez l'identifiant)");
						String logement_travaux = sc.nextLine();
						for (int i =0; i<tabLogements.size(); i++) {
							if (tabLogements.get(i).getId().equals(logement_travaux)) {
								tabLogements.get(i).travauxValorisation();
							}
						}
						System.out.println("Voulez-vous faire d'autres modifications ou appuyer sur entrée pour revenir au menu principal ? (C : modifier le prix, "
								+ "P : modifier le propriétaire, A : l'adresse du logement, T : faire des travaux de valorisation, M : modifier le quartier ");
						sous_action = sc.nextLine();
						break;
						
					case "M" : //Modifier le contexte du quartier
						System.out.println("De quel logement voulez-vous modifier le quartier ? (entrez l'identifiant)");
						String logement_quartier = sc.nextLine();
						for (int i =0; i<tabLogements.size(); i++) {
							if (tabLogements.get(i).getId().equals(logement_quartier)) {
								tabLogements.get(i).modifQuartier();
							}
						}
						System.out.println("Voulez-vous faire d'autres modifications ou appuyer sur entrée pour revenir au menu principal ? (C : modifier le prix, "
								+ "P : modifier le propriétaire, A : l'adresse du logement, T : faire des travaux de valorisation, M : modifier le quartier ");
						sous_action = sc.nextLine();
						break;
						
					default : 
						System.out.println("Que voulez-vous faire ? (C : modifier le prix, P : modifier le propriétaire, "
								+ "A : l'adresse du logement, T : faire des travaux de valorisation, M : modifier le quartier, entrée pour revenir au menu principal) ");
						sous_action = sc.nextLine();
					}
				}
				System.out.println("Voulez-vous effectuer une autre action ? (E : voir les logements existants, "
						+ "C : créer un nouveau logement, M : modifier les caractéristiques d'un logement, "
						+ "S : modifier la superficie d'une maison,  Q : quitter) " );
				action = sc.nextLine();
				break;
				
			case "S" : //Agrandir une maison (augmenter la superficie)
				System.out.println("Quelle maison voulez-vous agrandir ? (entrez son identifiant) ");
				String maison = sc.nextLine();
				System.out.println("Combien de m² voulez-vous ajouter ? ");
				float nouvelle_superficie = sc.nextFloat();
				for (int i =0; i<tabLogements.size(); i++) {
					if (tabLogements.get(i).getId().equals(maison)) {
						tabLogements.get(i).agrandir(nouvelle_superficie);
					}
				}
				System.out.println("Voulez-vous effectuer une autre action ? (E : voir les logements existants, "
						+ "C : créer un nouveau logement, M : modifier les caractéristiques d'un logement, "
						+ "S : modifier la superficie d'une maison, Q : quitter) " );
				action = sc.nextLine();
				break;
				
				
			default : 
				System.out.println("Nous n'avons pas compris votre demande, voulez-vous effectuer une autre action ? (E : voir les logements existants, "
					+ "C : créer un nouveau logement, M : modifier les caractéristiques d'un logement, "
					+ "S : modifier la superficie d'une maison, Q : quitter) " );
				action = sc.nextLine();
				break;
			}
		}
		sc.close(); //fermer le Scanner
	}

}
