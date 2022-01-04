<?php

require_once ("DataBase.php");

/**
 * Classe ProcessGoogleBooksAPIMetadata
 *
 * Cette classe réunit les fonctions permettant la connexion à l'API GoogleBooks, la récupération des métadonnées sur un livre
 * et l'enregistrement des informations du livre dans la base de données.
 *
 * PHP version 7.4
 *
 * @author Célia Martin <celia.ma@free.fr>
 */
class ProcessGoogleBooksAPIMetadata
{
    protected $book_metadata_requested;
    protected $api_key = 'AIzaSyAUc2AIbUgEn4dzSMVVY6xR2bcRrVnz0Qc';
    protected $retrieved_book_metadata;

    public function __construct($book_info_requested){
        $this->book_metadata_requested = $book_info_requested;
    }

    /**
     * Fonction get_books_metadata
     *
     * Se connecte à Google Books API et réstitue les résultats de la recherche réalisée par l'utilisateur.
     *
     * @return bool|string
     */
    public function get_books_metadata()
    {
        $q = urlencode($this->book_metadata_requested);
        $endpoint = 'https://www.googleapis.com/books/v1/volumes?q=' . $q . '&key=' . $this->api_key;
        $ch = curl_init($endpoint);

        # Dire à curl où se trouve le certificat
        $certificate_location = "D:\Documents\Applications\WAMP\bin\php\php7.4.9\extras\ssl\cacert.pem";
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $certificate_location);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $certificate_location);

        try {
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                return curl_error($ch);
            } else {
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                if ($http_code == intval(200)) {
                    $this->retrieved_book_metadata = $response;
                    return $this->retrieved_book_metadata;
                } else {
                    return "Ressource introuvable : " . $http_code;
                }
            }
        } catch
        (\Throwable $th) {
            throw $th;
        } finally {
            curl_close($ch);
        }
    }

    /** Fonction save_books_info_in_db.
     *
     * Cette fonction enregistre dans la base de données tous les livres retournés par la recherche effectuée par
     * l'utilisateur.
     *
     * @throws Exception
     */
    public function save_books_info_in_db()
    {
        $books_metadata = json_decode($this->retrieved_book_metadata, $assoc = true);
        $db = new DataBase();
        $request= "INSERT INTO `object` (`field1`, `field2`, `field3`, `field4`, `field5`, `field6`, `id_type_object`) VALUES ";
        $books_requests = [];

        # Sélectionner les valeurs pour chaque livre et les transcrire en requête SQL
        foreach ($books_metadata["items"] as $book) {
            //var_dump($book["volumeInfo"]);
            foreach ($book["volumeInfo"]["industryIdentifiers"] as $industry_identifier) {
                if ($industry_identifier["type"] == "ISBN_13") {
                    $isbn = addslashes($industry_identifier["identifier"]);
                }
            }
            #On prend un autre isbn si le 13 n'est pas présent
            if (!isset($isbn)) {
                $isbn = $book["volumeInfo"]["industryIdentifiers"][0]["identifier"];
            }
            #Vérifier que le livre n'est pas déjà présent dans la base, si non faire la transcription et sauver le morceau de requête
            $isbn_in_db = $db->getData("SELECT field1 FROM `object` WHERE `field1` = '" . $isbn . "'", "milibrary", "root", "");
            if (empty($isbn_in_db)) {
                $title = addslashes($book["volumeInfo"]["title"]);
                if (array_key_exists("authors", $book["volumeInfo"])) {
                    $authors = addslashes(implode(",", $book["volumeInfo"]["authors"]));
                } else {
                    $authors = "Inconnu";
                }
                if (array_key_exists("publisher", $book["volumeInfo"])) {
                    $publisher = addslashes($book["volumeInfo"]["publisher"]);
                } else {
                    $publisher = addslashes("Inconnu");
                }
                if (array_key_exists("publishedDate", $book["volumeInfo"])) {
                    $publication_date = addslashes($book["volumeInfo"]["publishedDate"]);
                } else {
                    $publication_date = "Inconnue";
                }
                if (array_key_exists("description", $book["volumeInfo"])) {
                    $synopsis = addslashes($book["volumeInfo"]["description"]);
                } else {
                    $synopsis = "Aucune description disponible";
                }
                array_push($books_requests, "('" . $isbn . "','" . $title . "','" . $authors . "','" . $publisher . "','" . $publication_date . "','" . $synopsis . "','1')");
            }
        }
        //var_dump($books_requests);

        #Le tableau de valeur peut être vide si la personne refait la même recherche (on essaie alors d'enregistrer des livres dont on a déjà les infos),
        # on ne fait la requête que si le tableau n'est pas vide
        if (!empty($books_requests)){
            $request = $request.implode(",", $books_requests);
            $db->addOrDelData($request, "milibrary", "root", "");
        }
    }
}