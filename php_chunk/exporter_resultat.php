<?php
if(!empty($_POST['sortie'])){
    $contenu = $_POST['sortie'];
    header('Content-disposition: attachment; filename="analyse.txt"');
    print $contenu;
}
?>
