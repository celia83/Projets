
$(document).ready(function () {
    //Navigation entre les volets Interrogation des données, Statistiques descriptives (et Suppression/Ajout des données pour le gestionnaire)
        //Bouton Data
    $("body").on("click","#data",function (){
        $("#downloadExemplier").show();
        $("#statisticsSelection").hide();
        $("#dataSelection").show();
        $("button#data").css("box-shadow", "inset 0 0 11px 0px #600000");
        document.getElementById("statistics").removeAttribute("style");
        document.getElementById("manager").removeAttribute("style");
        //$("button#statistics").css("box-shadow", "1px 0px 6px 0px #4b4b4b");
        $("#managerDiv").hide();
        //$("button#manager").css("box-shadow", "1px 0px 6px 0px #4b4b4b");
        $("#resultsArticle").show();
        $("#downloadTable").show();
        $("#resultsTable").html("");
    });
        //Bouton Statistiques descriptives
    $("body").on("click","#statistics",function (){
        $("#statisticsSelection").show();
        $("#dataSelection").hide();
        $("#downloadExemplier").hide();
        //$("button#data").css("box-shadow", "1px 0px 6px 0px #4b4b4b");
        document.getElementById("data").removeAttribute("style");
        $("button#statistics").css("box-shadow", "inset 0 0 11px 0px #600000");
        $("#managerDiv").hide();
        //$("button#manager").css("box-shadow", "1px 0px 6px 0px #4b4b4b");
        document.getElementById("manager").removeAttribute("style");
        $("#resultsArticle").show();
        $("#downloadTable").show();
        $("#resultsTable").html("");
    });
        //Bouton Ajouter et supprimer des données
    $("body").on("click","#manager",function (){
        $("#managerDiv").show();
        $("button#manager").css("box-shadow", "inset 0 0 11px 0px #600000");
        $("#statisticsSelection").hide();
        $("#dataSelection").hide();
        $("#downloadExemplier").hide();
        //$("button#data").css("box-shadow", "1px 0px 6px 0px #4b4b4b");
        document.getElementById("data").removeAttribute("style");
        //$("button#statistics").css("box-shadow", "1px 0px 6px 0px #4b4b4b");
        document.getElementById("statistics").removeAttribute("style");
        $("#resultsArticle").hide();
        $("#downloadTable").hide();
        $("#resultsTable").html("");
    });

    //Zone de sélection des critères pour l'affichage des données de la base de données
        //Permet d'afficher les critères avancés pour les verbes et les adjectifs
    $("#pos").change(function (){
        var selected = $("#pos > option:selected").val();
        if (selected === "Verbe"){
            $("#adjectiveCriteria").hide();
            $("#verbCriteria").show();
        } else if (selected === "Adjectif") {
            $("#adjectiveCriteria").show();
            $("#verbCriteria").hide();
        } else {
            $("#adjectiveCriteria").hide();
            $("#verbCriteria").hide();
        }
    });

    //Permet d'envoyer les critères au routeur et d'afficher dans la zone de résultats les résultats de la requête faite par l'utilisateur
    $("#getResults").on("click", function (event) {
        //Empêche l'envoi du formulaire
        event.preventDefault();
        //Récupération des critères généraux
        var corpus = $("#corpus > option:selected").val();
        var level = $("#level > option:selected").val();
        var pos = $("#pos > option:selected").val();
        var errStatus = $("#errStatus > option:selected").val();
        var segmStatus = $("#segmStatus > option:selected").val();
        var lemma = $("#lemma").val();

        //Sélection des données à envoyer si on a sélectionné un verbe
        if (pos === "Verbe"){
            var tense = $("#tense > option:selected").val();
            var person = $("#person > option:selected").val();
            var typeErr = $("#typeErr > option:selected").val();
            var base = $("#base").val();

            var ending = $("#ending").val();
            console.log(ending);
            var data = 'corpus=' + corpus + '&level=' + level + '&pos=' + pos + '&errStatus=' + errStatus + '&segmStatus=' + segmStatus + '&lemma=' + lemma+ '&tense=' + tense+ '&person=' + person+ '&typeErr=' + typeErr+ '&base=' + base+ '&ending=' + ending;
            //Sélection des données si on a sélectionné un adjectif
        } else if (pos === "Adjectif") {
            var genre = document.querySelector('input[name="genre"]:checked').value;
            var number = document.querySelector('input[name="number"]:checked').value;
            var errGenre = document.querySelector('input[name="errGenre"]:checked').value;
            var errNumber = document.querySelector('input[name="errNumber"]:checked').value;
            var baseAdj = $("#baseAdj").val();
            data = 'corpus=' + corpus + '&level=' + level + '&pos=' + pos + '&errStatus=' + errStatus + '&segmStatus=' + segmStatus + '&lemma=' + lemma+ '&genre=' + genre+ '&number=' + number+ '&errGenre=' + errGenre+ '&errNumber=' + errNumber+ '&baseAdj=' + baseAdj;
            //Si on n'a sélectionné ni un verbe ni un adjectif
        }else {
            data ='corpus=' + corpus + '&level=' + level + '&pos=' + pos + '&errStatus=' + errStatus + '&segmStatus=' + segmStatus + '&lemma=' + lemma
        }

        //Envoyer les données à la page d'index et récupérer le résultat
        $.ajax({
            url: 'index.php?action=showResults',
            method: 'POST',
            data: data,
            success: function (result) {
                //décoder le json
                var message = JSON.parse(result);

                //créer le tableau à injecter dans la page html
                if(pos === "Adjectif" || pos === "Nom"){
                    $("#resultsTable").html("<tr id ='headerTab'><td>SCAN</td><td>CORPUS</td><td>NIVEAU</td><td>ELEVE</td><td>LEMME</td><td>FORME<br/>NORMEE</td><td>FORME<br/>TRANSCRITE</td><td>PHONOLOGIE<br/>NORMEE</td><td>PHONOLOGIE<br/>TRANSCRITE</td><td>CATEGORIE</td><td>STATUT<br/>D'ERREUR</td><td>STATUT<br/>SEGMENTATION</td><td>GENRE</td><td>NOMBRE</td></tr>");
                } else if (pos === "Verbe"){
                    $("#resultsTable").html("<tr id ='headerTab'><td>SCAN</td><td>CORPUS</td><td>NIVEAU</td><td>ELEVE</td><td>LEMME</td><td>FORME<br/>NORMEE</td><td>FORME<br/>TRANSCRITE</td><td>PHONOLOGIE<br/>NORMEE</td><td>PHONOLOGIE<br/>TRANSCRITE</td><td>CATEGORIE</td><td>STATUT<br/>D'ERREUR</td><td>STATUT<br/>SEGMENTATION</td><td>TIROIR<br/>VERBAL</td><td>PERSONNE</td></tr>");
                } else if (pos==="Tous") {
                    $("#resultsTable").html("<tr id ='headerTab'><td>SCAN</td><td>CORPUS</td><td>NIVEAU</td><td>ELEVE</td><td>LEMME</td><td>FORME<br/>NORMEE</td><td>FORME<br/>TRANSCRITE</td><td>PHONOLOGIE<br/>NORMEE</td><td>PHONOLOGIE<br/>TRANSCRITE</td><td>CATEGORIE</td><td>STATUT<br/>D'ERREUR</td><td>STATUT<br/>SEGMENTATION</td><td>AUTRE<br/>INFO</td><td>AUTRE<br/>INFO</td></tr>");
                } else {
                    $("#resultsTable").html("<tr id ='headerTab'><td>SCAN</td><td>CORPUS</td><td>NIVEAU</td><td>ELEVE</td><td>LEMME</td><td>FORME<br/>NORMEE</td><td>FORME<br/>TRANSCRITE</td><td>PHONOLOGIE<br/>NORMEE</td><td>PHONOLOGIE<br/>TRANSCRITE</td><td>CATEGORIE</td><td>STATUT<br/>D'ERREUR</td><td>STATUT<br/>SEGMENTATION</td></tr>");
                }

                for (var i = 0 ; i< message.length; i++){
                    //permettra d'afficher en couleur les différentes parties du mot produit par l'élève
                    if (pos==="Verbe") {
                        var base = message[i].BaseVerProd;
                        var middle ="";
                        var ending = message[i].DesiVerProd;
                    } else {
                        //découpage en syllabes
                        var transSeg = message[i].SyllabTrans;
                        var cuttransSeg = transSeg.split("-");

                        //La désinence est le dernier élément (sauf s'il n'y a qu'une syllabe)
                        if (cuttransSeg.length > 1){
                            base = cuttransSeg[0];
                            ending = cuttransSeg[cuttransSeg.length - 1];
                        } else {
                            base = "";
                            ending = cuttransSeg[0];
                        }

                        //S'il y a plus de deux syllabes on récupère les autres éléments
                        if (cuttransSeg.length > 2){
                            var index = cuttransSeg.length - 2;
                            middle =cuttransSeg[1,index];
                        } else {
                            middle = "";
                        }
                    }

                    //Traitement pour connaitre le corpus
                    var regexScoledit = new RegExp("[A-Z]+-[a-zA-Z]+[0-9]+-[0-9]+-([a-zA-Z]+|[0-9]+)-[a-zA-Z][0-9]-S[0-9]+-[A-Z][0-9]-[0-9]+-[0-9]+");
                    var regexEcriscol = new RegExp("[A-Z]+-[a-zA-Z]+[0-9]+-[0-9]+-([a-zA-Z]+|[0-9]+)-[a-zA-Z][0-9]-E[0-9]+-[A-Z][0-9]-[0-9]+-[0-9]+");
                    var regexResolco = new RegExp("[A-Z]+-[a-zA-Z]+[0-9]+-[0-9]+-([a-zA-Z]+|[0-9]+)-[a-zA-Z][0-9]-R[0-9]+-[A-Z][0-9]-[0-9]+-[0-9]+");
                    var regexLitteracie = new RegExp("[A-Z]+-[a-zA-Z]+[0-9]+-[0-9]+-([a-zA-Z]+|[0-9]+)-[a-zA-Z][0-9]-L[0-9]+-[A-Z][0-9]-[0-9]+-[0-9]+");
                    var corpus ="";
                    if (regexScoledit.test(message[i].IdTok)){
                        corpus = "Scoledit";
                    } else if (regexEcriscol.test(message[i].IdTok)){
                        corpus = "Ecriscol";
                    } else if (regexResolco.test(message[i].IdTok)){
                        corpus = "Resolco";
                    } else if (regexLitteracie.test(message[i].IdTok)){
                        corpus = "Littéracie";
                    }

                    //Normalisation des catégories
                    var posMessage = message[i].Categorie;
                    if (posMessage === "ADV"){
                        pos = "Adverbe";
                    } else if (posMessage === "ADJ"){
                        pos = "Adjectif";
                    } else if (posMessage.search("VER") === 0){
                        pos = "Verbe";
                    } else if (posMessage === "NOM"){
                        pos = "Nom";
                    } else if (posMessage === "NAM"){
                        pos = "Nom propre";
                    } else if (posMessage.search("DET") === 0) {
                        pos = "Déterminant";
                    } else if (posMessage.search("PRO") === 0){
                        pos = "Pronom";
                    } else if (posMessage.search("PRP") === 0){
                        pos = "Préposition";
                    } else if (posMessage === "KON"){
                        pos = "Conjonction";
                    } else if (posMessage === "ABR"){
                        pos = "Abréviation";
                    } else if (posMessage === "NUM"){
                        pos = "Chiffre";
                    } else if (posMessage === "INT"){
                        pos = "Interjection";
                    } else {
                        pos = "Aucune";
                    }

                    //Constuire la ligne du tableau
                    if (pos === "Verbe") {
                        //Trouver le tiroir verbal
                        var tense = normalizeTense(posMessage);
                        $("#resultsTable").append("<tr id = 'mot"+[i]+"'><td class = 'scans' id='" + message[i].IdTok +"'>"+message[i].Scan+"</td><td>" + corpus +"</td><td>" + message[i].Niv +"</td><td>" + message[i].IdProd +"</td><td>" + message[i].Lemme +"</td><td>" + message[i].SegNorm +"</td><td><span class='base' id = 'base"+[i]+"'>" + base+"</span><span class='middle' id = 'middle"+[i]+"'>" + middle+"</span><span class='ending' id = 'ending"+[i]+"'>" + ending+"</span></td><td>" + message[i].PhonNorm+"</td><td>" + message[i].PhonTrans+"</td><td>" + pos+"</td><td>" + message[i].StatutErreur+"</td><td>" + message[i].StatutSegm+"</td><td>" + tense +"</td><td>" + message[i].VerPers +"</td></tr>");
                        //Dans tous les autres cas
                    } else if (pos === "Adjectif" || pos === "Nom"){
                        $("#resultsTable").append("<tr id = 'mot"+[i]+"'><td class = 'scans' id='" + message[i].IdTok +"'>"+message[i].Scan+"</td><td>" + corpus +"</td><td>" + message[i].Niv +"</td><td>" + message[i].IdProd +"</td><td>" + message[i].Lemme +"</td><td>" + message[i].SegNorm +"</td><td><span class='base' id = 'base"+[i]+"'>" + base+"</span><span class='middle' id = 'middle"+[i]+"'>" + middle+"</span><span class='ending' id = 'ending"+[i]+"'>" + ending+"</span></td><td>" + message[i].PhonNorm+"</td><td>" + message[i].PhonTrans+"</td><td>" + pos+"</td><td>" + message[i].StatutErreur+"</td><td>" + message[i].StatutSegm+"</td><td>" + message[i].Genre +"</td><td>" + message[i].Nombre +"</td></tr>");
                    } else {
                        $("#resultsTable").append("<tr id = 'mot"+[i]+"'><td class = 'scans' id='" + message[i].IdTok +"'>"+message[i].Scan+"</td><td>" + corpus +"</td><td>" + message[i].Niv +"</td><td>" + message[i].IdProd +"</td><td>" + message[i].Lemme +"</td><td>" + message[i].SegNorm +"</td><td><span class='base' id = 'base"+[i]+"'>" + base+"</span><span class='middle' id = 'middle"+[i]+"'>" + middle+"</span><span class='ending' id = 'ending"+[i]+"'>" + ending+"</span></td><td>" + message[i].PhonNorm+"</td><td>" + message[i].PhonTrans+"</td><td>" + pos+"</td><td>" + message[i].StatutErreur+"</td><td>" + message[i].StatutSegm+"</td></tr>");
                    }

                    //Ajouter les classes correct ou false en fonction d'où se trouve l'erreur pour les verbes
                    if(message[i].ErrVerBase === "1"){
                        $("#base"+[i]).addClass("false");
                        $("#ending"+[i]).addClass("correct");
                    } else if (message[i].ErrVerDes === "1"){
                        $("#base"+[i]).addClass("correct");
                        $("#ending"+[i]).addClass("false");
                    } else if (message[i].ErrVerBaseEtDes === "1"){
                        $("#base"+[i]).addClass("false");
                        $("#ending"+[i]).addClass("false");
                    } else if (message[i].ErrVerBase === "_" && message[i].ErrVerDes === "_" && message[i].ErrVerBaseEtDes === "_") {
                        $("#base"+[i]).addClass("none");
                        $("#ending"+[i]).addClass("none");
                    } else {
                        $("#base" + [i]).addClass("correct");
                        $("#ending" + [i]).addClass("correct");
                    }

                    //De même pour les adjectifs
                    if(message[i].ErreurAdjGenre === "1" || message[i].ErreurAdjNombre === "1"){
                        $("#ending"+[i]).addClass("false");
                    } else {
                        $("#ending"+[i]).addClass("correct");
                    }

                }
                //Changer leur couleur en fonction des erreurs
                $(".correct").css("color", "#4CA86A");
                $(".false").css("color", "#a84c4c");
            }
        });
    });

    //Utilisée dans la fonction précédente pour convertir les pos de treetagger en leur équivalent français
    function normalizeTense(posMessage){
        if (posMessage.search("cond") === 4){
            var tense = "Conditionnel";
        } else if (posMessage.search("futu")=== 4){
            tense = "Futur";
        } else if (posMessage.search("impe") === 4){
            tense = "Impératif";
        } else if (posMessage.search("impf") === 4){
            tense = "Imparfait";
        } else if (posMessage.search("infi") === 4) {
            tense = "Infinitif";
        } else if (posMessage.search("ppre") === 4){
            tense = "Participe présent";
        } else if (posMessage.search("pres") ===4){
            tense = "Présent";
        } else if (posMessage.search("simp") === 4){
            tense= "Passé simple";
        } else if (posMessage.search("subi")=== 4){
            tense = "Subjonctif imparfait";
        } else if (posMessage.search("subp")=== 4){
            tense = "Subjonctif présent";
        }else {
            tense = "Inexistant";
        }
        return tense;
    }


    //Zone qui permet de sélectionner un tableau de statistiques
        //Permet d'afficher la partie permettant la sélection des verbes en -er ou non et de choisir le tiroir verbal
    $("#tabStats").change(function (){
        var selected = $("#tabStats > option:selected").val();
        switch (selected){
            case "FailureAndSuccessTenses" :
                $("#tenseSelection").hide();
                $("#groupSelection").show();
                break;
            case "StandardizedBaseOrEnding" :
            case "StandardizedBaseEndingProportion" :
            case "VerbalFormsRepartitionBaseAndEndingPhono":
                $("#groupSelection").show();
                $("#tenseSelection").show();
                break;
            default :
                $("#groupSelection").hide();
                $("#tenseSelection").hide();
        }
    });

        //Permet d'envoyer au controleur le tableau sélectionné (avec le groupe et le tiroir verbal le cas échéant) et d'afficher dans la zone de résultats les résultats de la requête faite par l'utilisateur
    $("#getStats").on("click", function (event) {
        //Empêche l'envoi du formulaire
        event.preventDefault();
        //Savoir quel tableau afficher
        var tabName = $("#tabStats > option:selected").val();
        switch (tabName){
            case "FailureAndSuccessTenses" :
                var verbGroup = document.querySelector('input[name="verbGroup"]:checked').value;
                var tense = "";
                break;
            case "StandardizedBaseOrEnding" :
            case "StandardizedBaseEndingProportion" :
            case "VerbalFormsRepartitionBaseAndEndingPhono":
                verbGroup = document.querySelector('input[name="verbGroup"]:checked').value;
                tense = document.querySelector('input[name="tense"]:checked').value;
                break;
            default :
                verbGroup = "";
                tense = "";
        }

        //Données à envoyer vers le serveur avec ajax
        data = 'tabName=' + tabName + '&verbGroup=' + verbGroup + '&tense=' + tense;

        //Envoyer les données à la page d'index et récupérer le résultat
        $.ajax({
            url: 'index.php?action=showStats',
            method: 'POST',
            data: data,
            success: function (result) {
                //décoder le json
                var message = JSON.parse(result);
                switch (tabName) {
                    //Tableau : Nombre de mots des productions
                    case "NbWordProd" :
                    //Tableau : Nombre de formes verbales analysées
                    case "NbVerbalForms" :
                    //Tableau : Répartition des formes verbales selon si leur base et/ou leur désinence sont normées
                    case "StandardizedBaseOrEnding" :
                    //Tableau : Proportion de bases et de désinences normées et non normées
                    case "StandardizedBaseEndingProportion" :
                    //Tableau : Répartition des formes verbales non normées selon si leur base et/ou leur désinence respectent ou non la phonologie
                    case "VerbalFormsRepartitionBaseAndEndingPhono":
                        $("#resultsTable").html("<tr id ='headerTab'><td></td><td>CP</td><td>CE1</td><td>CE2</td><td>CM1</td><td>CM2</td></tr>");
                        for (var key in message){
                            var value = message[key];
                            $("#resultsTable").append("<tr><td>" + key + "</td><td>" + value["CP"] + "</td><td>" + value["CE1"] + "</td><td>" + value["CE2"] + "</td><td>"+ value["CM1"]+ "</td><td>"+value["CM2"]+"</td></tr>");
                        }
                        break;
                    //Tableau : Répartition des POS (le même que les tiroirs verbaux)
                    case "POSRepartitionByLevel" :
                    //Tableau : Répartition des tiroirs verbaux
                    case "TenseRepartition" :
                        $("#resultsTable").html("<tr id ='headerTab'><td></td><td>CP</td><td>CE1</td><td>CE2</td><td>CM1</td><td>CM2</td><td>Total</td></tr>");
                        var infos = Object.keys(message["CP"]);
                        for (var i = 0 ; i< infos.length; i++) {
                            var info = infos[i];
                            $("#resultsTable").append("<tr><td>"+info+"</td><td>" + message["CP"][info] + "</td><td>" + message["CE1"][info] + "</td><td>" + message["CE2"][info] + "</td><td>" + message["CM1"][info] + "</td><td>" + message["CM2"][info] + "</td><td>" + message["Total"][info] + "</td></tr>");
                        }
                        break;
                    //Tableau : Répartition des échecs et réussites pour les tiroirs verbaux les plus employés
                    case "FailureAndSuccessTenses" :
                        $("#resultsTable").html("<tr id ='headerTab'><td></td><td></td><td>CP</td><td>CE1</td><td>CE2</td><td>CM1</td><td>CM2</td></tr>");
                        var infos = Object.keys(message["CP"]);
                        var subInfos = Object.keys(message["CP"]["Ensemble des Verbes"]);
                        for (var j = 0 ; j< infos.length; j++) {
                            $("#resultsTable").append("<tr id = 'ligne"+j+"'><td rowspan='4'>"+infos[j]+"</td></tr>");
                            for (var k = 0 ; k<subInfos.length; k++){
                                var info = infos[j];
                                var subInfo = subInfos[k];
                                //$("#resultsTable").append("<tr><td>"+info+"</td><td>"+subInfo+"</td><td>" + message["CP"][info][subInfo] + "</td><td>" + message["CE1"][info][subInfo] + "</td><td>" + message["CE2"][info][subInfo] + "</td><td>" + message["CM1"][info][subInfo] + "</td><td>" + message["CM2"][info][subInfo] + "</td></tr>");
                                if(k === 0){
                                    $("#ligne"+j).append("<td>"+subInfo+"</td><td>" + message["CP"][info][subInfo] + "</td><td>" + message["CE1"][info][subInfo] + "</td><td>" + message["CE2"][info][subInfo] + "</td><td>" + message["CM1"][info][subInfo] + "</td><td>" + message["CM2"][info][subInfo] + "</td>");
                                } else {
                                    $("#resultsTable").append("<tr><td>"+subInfo+"</td><td>" + message["CP"][info][subInfo] + "</td><td>" + message["CE1"][info][subInfo] + "</td><td>" + message["CE2"][info][subInfo] + "</td><td>" + message["CM1"][info][subInfo] + "</td><td>" + message["CM2"][info][subInfo] + "</td></tr>");
                                }

                            }
                        }
                        break;
                    default:
                        $("#resultsTable").html("<tr id ='headerTab'><td></td><td>CP</td><td>CE1</td><td>CE2</td><td>CM1</td><td>CM2</td></tr>");
                }

            }
        })
    });

    //Bouton de téléchargement des résultats : Récupère le tableau contenu dans la balise html <table>, le convertit en string (chaque ligne du tableau séparée par \n et chaque cellule par \t)
    $("#downloadTable").on("click", function (){
        //récupérer le contenu de la balise <table>
        var tableHTML = document.getElementById("resultsTable").rows;
        var table = []; //tableau des lignes du tableau html
        //Pourcourir les lignes du tableau
        for (var i = 0; i < tableHTML.length; i++){
            var lineTable = []; //tableau des cellules de la ligne
            //Parcourir les cellules de la ligne
            for (var j = 0; j < tableHTML[i].cells.length; j++){
                //Récupérer le texte contenu dans les cellules
                var line = tableHTML[i].cells[j].innerText;
                lineTable.push(line.replace("\n", " "));
            }
            //Convertir le tableau  de cellules en string (chaque cellule séparée par une tabulation
            //Traitement particulier du tableau sur les échecs et réussites qui a des cellules fusionnées
            if($("#tabStats > option:selected").val() === "FailureAndSuccessTenses"){
                if(i===0||i===1||i===5||i===9||i===13||i===17){
                    var stringLine = lineTable.join("\t");
                } else {
                    stringLine = "\t" + lineTable.join("\t");
                }
                //Traitement de tous les autres tableaux
            } else {
                stringLine = lineTable.join("\t");
            }
            table.push(stringLine);
        }

        //Convertir le tableau des lignes en string (chaque ligne séparée par un retour chariot)
        var stringTable = table.join("\n");

        //Télécharger le tableau au format tsv
        strDownload(stringTable, 'resultats.tsv');
    });

    //Bouton de téléchargement d'un exemplier : Récupère le tableau contenu dans la balise html <table> et l'envoi au routeur sous forme d'array avec une ligne du tableau html par case de l'array
        //Affichage des mots disponibles pour créer l'exemplier
    $("body").on("click","#downloadExemplier", function (){
        //récupérer le contenu de la balise <table>
        var tableHTML = document.getElementById("resultsTable").rows;
        var tableWords = [];
        for (var i = 1; i < tableHTML.length; i++){
            var lemma = tableHTML[i].cells[4].innerHTML;
            if (tableWords.includes(lemma) === false){
                tableWords.push(lemma);
            }
        }
        //Affichage de la fenêtre de sélection du mot et du nombre de lignes à télécharger
        $("#downloadExemplierSection").show();
        $("select#word").html("<option id='"+tableWords[0]+"'>"+tableWords[0]+"</option>");
        for (var j = 1; j < tableWords.length; j++){
            $("select#word").append("<option id='"+tableWords[j]+"'>"+tableWords[j]+"</option>");
        }
    });

	    // Récupération du mot et du nombre de lignes choisis par l'utilisateur our les envoyer au routeur. Le résultat est téléchargé sur l'ordinateur de l'utilisateur
    $("body").on("click", "#getExemplier",function (event){
        event.preventDefault();
        var word = $("#word > option:selected").val();
        var nbLine = $("#nbLine > option:selected").val();
        $.ajax({
            url: 'index.php?action=exportExemplier',
            method: 'POST',
            data: "word="+word+"&nbLine="+nbLine,
            success: function (result) {
                //décoder le json
                var message = JSON.parse(result);
                //Télécharger le tableau au format tsv
                strDownload(message, 'exemplier.tsv');
            }
        });
    });

        //Croix de la fenêtre de l'exemplier : Permet de faire disparaitre la fenêtre de l'exemplier en appuyant sur la croix
    $("body").on("click",".fa-times", function (){
        $("#downloadExemplierSection").hide();
    });

    //Page de Connection des utilisateurs : récupère le login et mdp entrés par l'utilisateur, l'envoie au routeur et affiche l'erreur si les id ne sont pas bons, sinon renvoie l'utilisateur à la page d'accueil
    $("body").on("click","#connectionButton", function (event){
        //Ne pas envoyer le formulaire
        event.preventDefault();
        //Récupération des infos de connection
        var login = $("#login").val();
        var psw = $("#psw").val();
        $.ajax({
            url: '../index.php?action=connection',
            method: 'POST',
            data : 'login='+login+"&psw="+psw,
            success: function (result) {
                //Décodage du json
                var message = JSON.parse(result);
                //Si les identifiants sont corrects on redirige la personne vers la page d'accueil
                if(message === true){
                    document.location.href='../index.php';
                    //Sinon on affiche le message renvoyé par le serveur
                } else {
                    $("#alerts").html("<i class=\"fas fa-exclamation-circle\"></i>"+message);
                }
            }
        });
    });

    //Zone pour la gestion de la base de données
        //On affiche les formulaires d'ajout et suppression seulement quand le gestionnaire appuie sur le bouton (donc quand la personne est connectée)
    $("body").on("click","#manager", function (){
        $("#managerArticle").html("<form id = 'addDataForm' action ='index.php?action=addData' method='POST' enctype='multipart/form-data'><h2 id = 'addDataTitle'>Ajouter un jeu de données</h2><div id='addFileDiv'><input id='chooseFile' name ='chooseFile' value ='addFile' type='file' /><label id = 'addFileLabel' for='chooseFile'><i class=\"fas fa-upload\"></i></label><aside>Ajouter un jeu de données depuis votre ordinateur (format csv)</aside></div><div><label for='addData'></label><input id='addData' value ='Ajouter' type='submit' /></div></form><form id = 'deleteDataForm' action ='index.php?action=deleteData' method='POST'><h2 id ='deleteDataTitle'>Supprimer un jeu de données</h2><div id = 'levelDiv'><label for='chooseLevel'>Niveau : </label><select  id='chooseLevel' name='chooseLevel'></select></div><div id = 'corpusDiv'><label for='chooseCorpus'>Corpus de provenance : </label><select  id='chooseCorpus' name='chooseCorpus'><option value = 'Scoledit'>Scoledit</option><option value = 'Ecriscol'>Ecriscol</option><option value = 'Resolco'>Resolco</option></select></div><div><input id='deleteData' value ='Supprimer' type='submit' /></div></form>");
        var levels = ["CP","CE1","CE2","CM1","CM2", "6EME", "5EME", "4EME", "3EME", "2NDE", "1ERE", "TERMINALE", "L1", "L2", "L3", "M1", "M2"];
        for (var i = 0; i < levels.length;i++){
            $("#chooseLevel").append("<option value = '"+levels[i]+"'>"+levels[i]+"</option>");
        }
    });

    //Permet le téléchargement d'un texte (string) : utilisée pour les boutons de téléchargement des données et de téléchargement de l'exemplier
    function strDownload(text, fileName) {
        text = '' + text;
        fileName = fileName || 'strDownload.txt';
        var c = document.createElement('A'),
            d = document.body;
        d.appendChild(c);
        c.href = 'data:text/plain;charset=utf-8,' + encodeURIComponent(text);
        c.download = fileName;
        c.click();
        d.removeChild(c);
    }
});

