<?php
date_default_timezone_set('UTC');
$today = getdate();
$day=$today["mday"];
$month = $today["mon"];     #mois
$week_day = $today["wday"];     #numéro de jour de la semaine (1 = lundi)
$year = $today["year"];         #année


if (isset ($_POST['currentDay']) && $_POST['currentDay']!=null && $_POST['currentDay']!="" && $_POST['currentDay']!="undefined"){
    $day =(int)$_POST['currentDay'];
}

if (isset ($_POST['currentMonth']) && $_POST['currentMonth']!=null && $_POST['currentMonth']!="" && $_POST['currentMonth']!="undefined") {
    $month = (int)$_POST['currentMonth'];
    $year = (int)$_POST['currentYear'];
}

if (isset($_POST['mois_suivant'])){
    $month +=1;
    if ($month>12){
        $year +=1;
        $month=1;
    }
} else if (isset($_POST['mois_precedent'])){
    $month -=1;
    if ($month<=0){
        $year -=1;
        $month=12;
    }
}

?>
<table id="mini_calendrier">
    <tr>

        <button class="bouton_calendrier" type="button" name = "mois_precedent" id ="mois_precedent"><i class="fas fa-chevron-left"></i></button>
        <div id="mois_annee"><?php
            if ($month<10){
                echo "0$month / $year";
            } else {
                echo "$month / $year";
            }

            ?></div>

        <button class="bouton_calendrier" type="button" name = " mois_suivant" id="mois_suivant"><i class="fas fa-chevron-right"></i></button>
    </tr>
    <tr>
        <?php
        echo "<th id='1'>Lundi</th><th id='2'>Mardi</th><th id='3'>Mercredi</th><th id='4'>Jeudi</th><th id='5'>Vendredi</th><th id='6'>Samedi</th><th id='0'>Dimanche</th>";
        ?>
    </tr>
    <?php
    $premier_jour_mois=date("w",mktime(0,0,0,$month, 1, $year)); #num jour semaine (1=lundi)
    $dernier_jour_mois=date("j",mktime(0,0,0,$month+1, 0, $year));
    $num_dernier_jour_mois=date("w",mktime(0,0,0,$month+1, 0, $year)); #num jour semaine
    if ($num_dernier_jour_mois==0){     #Dimanche n'est plus 0 mais 7
        $num_dernier_jour_mois=7;
    }
    if ($premier_jour_mois==0){     #Dimanche n'est plus 0 mais 7
        $premier_jour_mois=7;
    }
    echo"<tr>";
    for ($j=$premier_jour_mois-1 ; $j>0 ; $j--){
        echo "<td></td>";
    }
    $cpt=7-(8-$premier_jour_mois);
    for ($i=1; $i<=$dernier_jour_mois;$i++){
        $date = date ("d",mktime(0,0,0, $month, $i, $year));
        if ($cpt==7 || $cpt==14 || $cpt== 21 || $cpt == 28 || $cpt ==35){
            echo "</tr><tr><td class = 'cellules' id = '$i'>$date</td>";
        } else {
            echo "<td class = 'cellules' id = '$i'>$date</td>";
        }
        $cpt++;
    }
    for ($n=$num_dernier_jour_mois ; $n<7; $n++){
        echo "<td></td>";
    }
    echo "</tr>";

    ?>
</table>
<input type ="text" id="currentMonth" name = "currentMonth" value ="<?php echo $month; ?>" hidden />
<input type ="text" id="currentYear" name = "currentYear" value ="<?php echo $year; ?>" hidden />
<input type ="text" id = "currentDay" name = "currentDay" value ="<?php echo $day; ?>" hidden />