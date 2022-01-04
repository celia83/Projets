<?php
require_once ('User.php');

class Friends extends User
{
    public function __construct(string $user_email, string $psw){
        Parent::__construct($user_email, $psw);
    }

    /**
     * Fonction get_friends_infos.
     *
     * Retourne la liste des amis de l'utilisateurs (sans leur mot de passe).
     *
     * @return array
     * @throws Exception
     */
    public function get_friends_infos(): array
    {
        $db = new DataBase();
        $request = 'SELECT `id`, `username`, `firstname`, `lastname`, `email`, `avatar`, `description` FROM `friend` JOIN `user` ON friend.id_user2 = user.id WHERE `id_user1` = (SELECT `id` FROM `user` WHERE `email` = "'. $this->email.'")';
        return $db->getData($request, "milibrary", "root", "");
    }

    /**
     * Fonction add_friend.
     *
     * Cette fonction permet d'ajouter un utilisateur parmi ses amis.
     *
     * @param $friend_id int Identifiant de l'utilisateur à ajouter.
     * @return false|int
     * @throws Exception
     */
    public function add_friend(int $friend_id){
        $db = new DataBase();
        $request = 'INSERT INTO `friend` (`id_user1`, `id_user2`) VALUES ((SELECT `id` FROM `user` WHERE `email` = "'.$this->email.'"), '.$friend_id.'), ('.$friend_id.', (SELECT `id` FROM `user` WHERE `email` = "'.$this->email.'"))';
        try {
            return $db->addOrDelData($request, "milibrary", "root", "");
        } catch (Exception $e){
            return "Cet utilisateur fait déjà parti de vos amis.";
        }
    }

    /**
     * Fonction del_friend.
     *
     * Cette fonction permet de supprimer un utilisateur de ses amis.
     *
     * @param $friend_id int Identifiant de l'utilisateur à supprimer.
     * @return false|int
     * @throws Exception
     */
    public function del_friend(int $friend_id){
        $db = new DataBase();
        $request = 'DELETE FROM `friend` WHERE `id_user1` = (SELECT `id` FROM `user` WHERE `email` = "'.$this->email.'") AND `id_user2` = '.$friend_id.';DELETE FROM `friend` WHERE `id_user2` = (SELECT `id` FROM `user` WHERE `email` = "'.$this->email.'") AND `id_user1` = '.$friend_id.';';
        try {
            return $db->addOrDelData($request, "milibrary", "root", "");
        } catch (Exception $e){
            return "Cet utilisateur a déjà été supprimé de vos amis.";
        }
    }

    /**
     * Fonction lend_book.
     *
     * Permet à l'utilisateur de préciser qu'un livre a été prêté à un ami.
     *
     * @param $friend_id int Identifiant de l'ami
     * @param $id_object int Identifiant de l'object qui est prêté
     * @return false|int|string
     */
    public function lend_book(int $friend_id, int $id_object){
        $db = new DataBase();
        $request = 'INSERT INTO `friend_object` (`id_user`, `id_user_owner`, `id_object`) VALUES ('.$friend_id.', (SELECT `id` FROM `user` WHERE `email` = "'.$this->email.'"), '.$id_object.')';
        try {
            return $db->addOrDelData($request, "milibrary", "root", "");
        } catch (Exception $e){
            return "Vôtre ami est déjà en possession de ce livre";
        }
    }

    /**
     * Fonction get_lent_books
     *
     * Permet à l'utilisateur de visionner les livres qu'il a prêtés (titre / auteur)
     * et à qui (pseudo / avatar).
     *
     * @return array
     * @throws Exception
     */
    public function get_lent_books(): array
    {
        $db = new DataBase();
        $request = 'SELECT user.username, user.avatar, object.field2, object.field3 FROM `user`, `object`, `friend_object`WHERE friend_object.id_user_owner = (SELECT `id` FROM `user` WHERE `email` = "'.$this->email.'") AND  friend_object.id_object = object.id AND friend_object.id_user = user.id';
        return $db->getData($request, "milibrary", "root", "");
    }

    public function get_borrowed_books(): array
    {
        $db = new DataBase();
        $request = 'SELECT user.username, user.avatar, object.field2, object.field3 FROM `user`, `object`, `friend_object`WHERE friend_object.id_user = (SELECT `id` FROM `user` WHERE `email` = "'.$this->email.'") AND friend_object.id_object = object.id AND friend_object.id_user_owner = user.id';
        return $db->getData($request, "milibrary", "root", "");
    }
}