<?php

include_once "model/DataBase.php";

/**
 * Class InsertData
 *
 * Cette classe permet d'ajouter à la base de données les lignes présentes dans un fichier csv fourni par l'utilisateur.
 *
 * PHP version 5.6 
 *
 * @author Jingyu Liu <jingyu.liu@etu.univ-grenoble-alpes.fr>
 */
class InsertData
{
	/**
	 * Fonction inputCSV($handle)
	 *
	 * Cette fonction permet de charger les données du fichier csv.
	 *
	 * @param $handle
	 * @return array Tableau des lignes du fichier csv
	 */
	public function inputCSV($handle)
		{   
			$out = array ();   
			$n = 0;   
			while ($data = fgetcsv($handle, 100000)){
				$num = count($data);   
				for ($i = 0; $i < $num; $i++)   
				{   
					$out[$n][$i] = $data[$i];   
				}   
				$n++;   
			}   
			return $out;   
		}

	/**
	 * Fonction addCSV($filePath)
	 *
	 * La fonction addCSV() permet d'écrire dans la base de données les lignes du fichier csv donné par l'utilisateur. Elle génère une erreur si le fichier est vide.
	 *
	 * @param string $filePath Le chemin vers le fichier csv (chemin temporaire sur le serveur)
	 * @return int $nbLineAffected Le nombre de lignes affectées par l'ajout
	 * @throws Exception
	 */
	public function addCSV($filePath){
			if ( file_exists ( $filePath )){
			    $handle = fopen($filePath, 'r');
			}
			
			#traiter le fichier csv
			$result = self::inputCSV($handle);
			$len_result = count($result);  

			#voir si le fichier est vide
			if($len_result==0)   
			{
				throw new Exception('Le fichier est vide.');
			} 
			  
			#un boucle pour obtenir les valeurs
			$data_values="";
			for($i = 1; $i < $len_result; $i++){

				#split chaque ligne par ';'
				$listre =  explode (';',implode($result[$i])); 
				
				#trouver les valeurs des colonnes 
				$IdTok = ($listre[0]);   
				$IdProd = ($listre[1]);   
				$Niv = ($listre[2]);
				$SegNorm = ($listre[3]);
				$SegTrans= ($listre[4]);
				$PhonNorm = ($listre[5]);
				$PhonTrans = ($listre[6]);
				$SyllabNorm	= ($listre[7]);
				$SyllabTrans = ($listre[8]);
				$Categorie = ($listre[9]);	
				$Lemme = ($listre[10]);
				$StatuErreurSimp = ($listre[11]);
				$StatutErreur = ($listre[12]);
				$StatutSegm = ($listre[13]);
				$Genre = ($listre[14]);
				$Nombre	= ($listre[15]);
				$BaseAdjNorm = ($listre[16]);
				$GenreAdjNorm = ($listre[17]);
				$NombreAdjNorm = ($listre[18]);
				$BaseAdjTrans = ($listre[19]);
				$GenreAdjTrans = ($listre[20]);
				$NombreAdjTrans	= ($listre[21]);
				$ErreurAdjBase = ($listre[22]);
				$ErreurAdjGenre	= ($listre[23]);
				$ErreurAdjNombre = ($listre[24]);
				$VerPers = ($listre[25]);
				$BaseVerForme = ($listre[26]);
				$DesiVerForme = ($listre[27]);
				$BaseVerProd = ($listre[28]);
				$DesiVerProd = ($listre[29]);
				$ErrVerBase	= ($listre[30]);
				$ErrVerDes= ($listre[31]);
				$ErrVerBaseEtDes= ($listre[32]);

				$data_values .= "('$IdTok','$IdProd','$Niv','$SegNorm','$SegTrans','$PhonNorm','$PhonTrans','$SyllabNorm','$SyllabTrans','$Categorie','$Lemme','$StatuErreurSimp','$StatutErreur','$StatutSegm','$Genre','$Nombre','$BaseAdjNorm','$GenreAdjNorm','$NombreAdjNorm','$BaseAdjTrans','$GenreAdjTrans','$NombreAdjTrans','$ErreurAdjBase','$ErreurAdjGenre','$ErreurAdjNombre','$VerPers','$BaseVerForme','$DesiVerForme','$BaseVerProd','$DesiVerProd	','$ErrVerBase','$ErrVerDes','$ErrVerBaseEtDes'),";
			}

			#fermer la fonction de handle et supprimer le dernier indice    
			$data_values = substr($data_values,0,-1);
			
			#fermer la fonction de handle   
			fclose($handle); 
			
			#insérer les données aux tables
			$request = "INSERT INTO `ecalm` (IdTok,IdProd,Niv,SegNorm,SegTrans,PhonNorm,PhonTrans,SyllabNorm,SyllabTrans,Categorie,Lemme,StatuErreurSimp,StatutErreur,StatutSegm,Genre,Nombre,BaseAdjNorm,GenreAdjNorm,NombreAdjNorm,BaseAdjTrans,GenreAdjTrans,NombreAdjTrans,ErreurAdjBase,ErreurAdjGenre,ErreurAdjNombre,VerPers,BaseVerForme,DesiVerForme,BaseVerProd,DesiVerProd,ErrVerBase,ErrVerDes,ErrVerBaseEtDes) VALUES $data_values";

			#call la fonction de getData
			$database = new DataBase();
			$nbLineAffected=$database->addOrDelData($request);

			return $nbLineAffected;
	 } 	
 }
