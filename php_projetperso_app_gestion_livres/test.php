<?php
require ('ProcessGoogleBooksAPIMetadata.php');
require ('BookMetadataProcess.php');
require ('Library.php');
require ('User.php');
require ('Friends.php');

$book_metadata_request = new ProcessGoogleBooksAPIMetadata("l'ile mystérieuse");
$process_book_data = new BookMetadataProcess(9782253160861);
$library = new Library();
try {
    ### Utilisateurs
    # Connexion et initialisation des traitements des amis
    $users = new User("test2@rouge.fr", "password");
    $friends = new Friends("test2@rouge.fr", "password");
    $id_user_owner = 2;
    $id_friend = 3;
        # Connexion
    $response  = $users->verify_user();
    var_dump($response);

    # Afficher les utilisateurs
    //var_dump($users->get_users_infos());

    # Ajouter un ami
    //var_dump($friends->add_friend($id_friend));

    # Supprimer un ami
    //var_dump($friends->del_friend(3));

    # Afficher les amis
    //var_dump($friends->get_friends_infos());

    # Prêter un livre
    //var_dump($friends->lend_book($id_friend, 350));

    # Afficher les livres prêtés
    var_dump($friends->get_lent_books());

    # Afficher les livres empruntés
    var_dump($friends->get_borrowed_books());


    ### Livres
    # Faire la recherche
    $books_metadata = $book_metadata_request->get_books_metadata();
    var_dump($books_metadata);

    # Enregistrer les infos des livres dans la db
    //$book_metadata_request->save_books_info_in_db();

    # Afficher les métadonnées du livre sélectionné
    $book_data = $process_book_data->get_book_metadata_from_db();
    var_dump($book_data);

    # Ajouter le livre à la bibliothèque de l'utilisateur
    var_dump($process_book_data->add_book_to_user_objects($id_user_owner));

    # Afficher la bibliothèque de l'utilisateur
    var_dump($library->get_books_metadata($id_user_owner));

    # Afficher les métadonnées du livre lorsqu'il est dans la bibliothèque de l'utilisateur
    var_dump($process_book_data->get_user_book_metadata_from_db($id_user_owner));

    # Modifier la note et le commentaire et réafficher le livre
    $process_book_data->update_comment_and_rate($id_user_owner, 3,"Chouette !!");
    var_dump($process_book_data->get_user_book_metadata_from_db($id_user_owner));

    # Supprimer le livre de la bibliothèque de l'utilisateur
    //var_dump($process_book_data->delete_book_from_user_objects($id_user_owner));

} catch (Throwable $e) {
    echo $e;
}
//var_dump($book_metadata);
//$data = json_decode($book_metadata, $assoc = true);


