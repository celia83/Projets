<?php

/**
 * Classe DataBase
 *
 * Cette classe réunit les fonctions permettant la connexion et l'interrogation de la base de données
 *
 * PHP version 5.6
 *
 * @author Célia Martin <celia.ma@free.fr>
 */
class DataBase {

    /**
     * Fonction connection()
     *
     * Cette fonction permet la connexion à la base de données.
     *
     * @return PDO
     * @throws Exception
     */
    private static function connection(){
        if($db = new PDO('mysql:host=localhost;dbname=scoledit', 'scoledit', 'projetpro', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'))){
            return $db;
        } else {
            throw new Exception('Impossible de se connecter à la base de données.');
        }
    }

    /**
     * Fonction getData($request)
     *
     * Cette fonction se connecte à une base de données, exécute la requête passée en paramètre et retourne un tableau des éléments ainsi sélectionnés.
     *
     * @param string $request Une requête SQL
     * @return array
     * @throws Exception
     */
    public function getData($request){
        #Connexion à la base de données
        $db = self::connection();
        #Requêter la base
        if ($response = $db->query($request)){
            #Récupérer les informations de la requête (le mode PDO::FETCH_ASSOC permet d'éviter que le résultats ne dédouble les colonnes)
            $tab = array();
            while ($enr = $response->fetch(PDO::FETCH_ASSOC)) {
                array_push($tab, $enr);
            }
            return $tab;
        } else {
            throw new Exception('Impossible de récupérer les données.');
        }
    }

    /**
     * Fonction addOrDelData($request)
     *
     * Cette fonction se connecte à la base de donnée, exécute la requête passé en paramètre et retourne le nombre de lignes affectées par la requête
     *
     * @param string $request La requête SQL
     * @return false|int
     * @throws Exception
     */
    public function addOrDelData($request){
        #Connexion à la base de données
        $db = self::connection();
        #Requêter la base
        if ($nbLineAffected = $db->exec($request)){
            return $nbLineAffected;
        } else {
            throw new Exception('Impossible de récupérer les données.');
        }
    }
}