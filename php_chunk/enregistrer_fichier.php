<?php
if (isset($_POST['fichier']) && isset ($_POST['new_text'])) {
    $file = "corpus/". $_POST["fichier"];
    $content = $_POST["new_text"];
    if (is_file($file)) {
        $hf = fopen($file, "w");
        fputs($hf, $content);
        fclose($hf);
        print "Modifications enregistrées";
    } else {
        print "Ecriture impossible, $file n'est pas un fichier.";
    }
} else {
    print "Pas de données.";
}
?>
