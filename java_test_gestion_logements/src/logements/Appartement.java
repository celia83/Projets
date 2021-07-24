package logements;

public class Appartement extends Logements {
	//PROPRIETES
	private boolean normes;
	//CONSTRUCTEURS
	public Appartement (String a, float s, boolean n) {
		super (a, s);
		normes = n;
	}
	
	//METHODES
	public void travauxValorisation() {
		if (normes == false) {
			prix = prix + (prix * 15 / 100);
			normes = true;
		} else {
			prix = prix + (prix * 5 / 100);
		}
	}
}
