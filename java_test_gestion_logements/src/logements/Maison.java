package logements;

public class Maison extends Logements {
	//PROPRIETES
		
	//CONSTRUCTEURS
	public Maison (String a, float s) {
		super (a, s);
	}
	
	//METHODES
	public void agrandir (float s) {
		superficie += s;
		prix += 500 * s ;
		
	}

}
