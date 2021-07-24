package logements;

public class Logements {
	//PROPRIETES
	private String id;
	private String adresse;
	protected float superficie;
	private String proprietaire;
	protected float prix;
	private static int cpt=0;
	
	//CONSTRUCTEURS
	public Logements(String a, float s) {
		cpt++;
		id = "log" + cpt;
		adresse = a;
		superficie = s;
		prix = 0;
		proprietaire = "";
	}
	
	//METHODES
	public String caracteristiques () {
		return "Le logement " + id + ", situé à  l'adresse " + adresse + ", appartenant à " + proprietaire + ", est au prix de " + prix + "€ et possède une superficie de "
+ superficie + " m².";
	}
	
	public void changeProprio(String p) {
		proprietaire = p;
	}
	
	public void modifierPrix(float pr) {
		prix = pr;
	}
	
	public void nouvelleAdresse(String na) {
		adresse = na;
	}
	
	public void agrandir(float as) {
		superficie += as;
	}
	
	public void travauxValorisation() {
		prix = (float) (prix * 1.05);
	}
	
	public void modifQuartier() {
		prix = (float) (prix * 0.97);
	}
	
	public String getId() {
		return id;
	}
	
	public String getAdresse() {
		return adresse;
	}
	
	public String getProprio() {
		return proprietaire;
	}
	
	public float getPrix() {
		return prix;
	}
	
	public float getSuperficie() {
		return superficie;
	}		
}
	

