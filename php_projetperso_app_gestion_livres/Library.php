<?php

/**
 * Class Library
 *
 * Cette classe permet de faire toutes les actions concernant la bibliothèque de l'utilisateur.
 *
 * PHP version 7.4
 *
 * @author Célia Martin <celia.ma@free.fr>
 */
class Library
{
    public function __construct(){

    }

    /**
     * Fonction get_books_metadata
     *
     * Afficher tous les livres présents dans la bibliothèque de l'utilisateur.
     *
     * @param $id_user_owner int Identifiant de l'utilisateur
     * @return array
     * @throws Exception
     */
    public function get_books_metadata(int $id_user_owner): array
    {
        $db = new DataBase();
        $request = 'SELECT * FROM `object` JOIN `user_object` ON object.id = user_object.id_object WHERE `id_user_owner` = '.$id_user_owner;
        return $db->getData($request, "milibrary", "root", "");
    }
}