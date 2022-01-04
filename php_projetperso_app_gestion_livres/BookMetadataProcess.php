<?php

require_once ("DataBase.php");

/**
 * Class BookMetadataProcess
 *
 * Cette classe permet de faire différente action concernant un livre donné. Notamment son affichage, sa suppression de la
 * liste des favoris, l'ajout de note et de commentaires etc.
 *
 * PHP version 7.4
 *
 * @author Célia Martin <celia.ma@free.fr>
 */
class BookMetadataProcess
{
    private $isbn;
    private $book_id;

    public function __construct(int $isbn){
        $this->isbn = $isbn;
    }

    /**
     * Fonction get_book_metadata_from_db
     *
     * Fonction à utiliser lorsque l'utilisateur clique sur UN livre de la RECHERCHE, on souhaite donc en afficher les données
     * depuis la base de données. On utilise l'ISBN qui est un code unique existant pour chaque livre.
     *
     * @throws Exception
     */
    public function get_book_metadata_from_db(): array
    {
        $db = new DataBase();
        $book_metadata = $db->getData("SELECT * FROM `object` WHERE `field1` = ".$this->isbn, "milibrary", "root", "");
        $this->book_id = $book_metadata[0]["id"];
        return $book_metadata;
    }

    /**
     * Fonction get_user_book_metadata_from_db
     *
     * Fonction à utiliser lorsque l'utilisateur clique sur UN livre de SA BIBLIOTHEQUE. Restitue les informations liées à ce livre
     * en récoltant les infos de la table user_object (note et commentaire) et les métadonnées du livre depuis la table "object".
     *
     * @param $id_user_owner int Identifiant de l'utilisateur
     * @throws Exception
     */
    public function get_user_book_metadata_from_db(int $id_user_owner): array
    {
        $db = new DataBase();
        $request ='SELECT * FROM `object` JOIN `user_object` ON object.id = user_object.id_object WHERE `id_user_owner` = '.$id_user_owner.' AND `id_object` = '. $this->book_id;
        return $db->getData($request, "milibrary", "root", "");
    }

    /**
     * Fonction add_book_to_user_objects
     *
     * Cette fonction permet d'enregistrer dans les objects de l'utilisateur le livre qu'il aura sélectionné.
     *
     * @param $id_user_owner int Identifiant de l'utilisateur qui enregistre le livre
     * @param $rate int Note donnée par l'utilisateur s'il a lu le livre
     * @param $comment String Commentaire à propos du livre
     * @throws Exception
     */
    public function add_book_to_user_objects(int $id_user_owner, int $rate = 0 , String $comment = ""): string
    {
        $db = new DataBase();
        $request = 'INSERT INTO `user_object` (`id_user_owner`, `id_object`, `rate`, `comment`) VALUES ('.$id_user_owner.', '.$this->book_id.', '.$rate.', "'.$comment.'")';
        try {
            $db->addOrDelData($request, "milibrary", "root", "");
            return "Le livre a été ajouté à votre bibliothèque.";
        } catch (Exception $e) {
            return "Ce livre est déjà dans votre bibliothèque.";
        }
    }

    /**
     * Fonction delete_book_from_user_objects
     *
     * Cette fonction permet à l'utilisateur de supprimer le livre de sa bibliothèque.
     *
     * @param $id_user_owner int Identifiant de l'utilisateur
     * @throws Exception
     */
    public function delete_book_from_user_objects(int $id_user_owner): string
    {
        $db = new DataBase();
        $request = 'DELETE FROM `user_object` WHERE `id_user_owner` = '.$id_user_owner.' AND `id_object` = '. $this->book_id;
        $db->addOrDelData($request, "milibrary", "root", "");
        return "Le livre a été supprimé de votre bilbiothèque.";
    }

    /**
     * Fonction update_comment
     *
     * Cette fonction permet de mettre à jour le commentaire disponible pour le livre sélectionné.
     *
     * @param $id_user_owner int Identifiant de l'utilisteur qui possède le livre
     * @param $new_rate int Note du livre (facultatif)
     * @param $new_comment String Commentaire à propos du livre (facultatif)
     * @throws Exception
     */
    public function update_comment_and_rate(int $id_user_owner, int $new_rate, String $new_comment): string
    {
        $db = new DataBase();
        $request = 'UPDATE `user_object` SET `comment` = "'.$new_comment.'", `rate` = '.$new_rate.' WHERE `id_user_owner` = '.$id_user_owner.' AND `id_object` = '.$this->book_id;
        $db->addOrDelData($request, "milibrary", "root", "");
        return "Informations modifiées";
    }
}