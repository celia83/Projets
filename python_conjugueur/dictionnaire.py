# -*- coding: utf8 -*-
#####################################
## Noms : GIROUD Océane et MARTIN Célia
##
## Créé le : 27/11/2019
##
## Fonctionnalité : crée un dictionnaire de verbes à partir du dictionnaire abu.csv
#####################################
# Importation de fonctions externes :
#/////////////
#####################################

with open("ABU.csv", "r") as dico:
    abu = dico.readlines()

    with open("liste_des_verbes_en_h.txt", 'r') as h:
        ver_h = []
        for line in h.readlines():
            ver_h.append(line[0:-1])

    with open("abu_verbes.csv", "w") as new_abu:
        # ajouter l'en-tête :
        new_abu.write("Date de création : 27/11/2019\tGIROUD Océane et MARTIN Célia\n\nINFORMATIONS AJOUTEES PAR RAPPORT AU DICTIONNAIRE ORIGINAL : code 'ASP' sur les verbes en 'h' aspiré.\n\n")
        # écrire la licence dans le nouveau fichier :
        for i in range(0, 59):  # on sélectionne les 59 première lignes (qui contiennent la licence)
            LicenceLine = abu[i]
            new_abu.write(LicenceLine)
        # trier les verbes
        for j in range(60, len(abu)):  # on sélectionne les lignes qui suivent la licence jusqu'à la fin
            WordLine = abu[j]
            if "Ver" in WordLine:
                CutWordLine = WordLine.split("\t")
                lemme = CutWordLine[1]
                form = CutWordLine[0]
                if lemme in ver_h: #pour les verbe ayant un h on ajoute ou non "ASPIRE" (ASP)
                    categories = CutWordLine[2][0:-1] + "\tASP\n" #on supprime le "\n" pour ajouter ASP à la fin de la ligne
                    subcat = categories.split(":")[1:] # on enlève l'information de catégorie VER
                    subcat = ":".join(subcat) #on rejoint les morceaux de sous ctaégorie en remettant un ":"
                    NewWordLine = lemme + "\t" + form + "\t" + subcat #on recrée la ligne
                    new_abu.write(NewWordLine)
                else: #et pour les autres on réorganise seulement
                    # réorganiser les informations :
                    categories = CutWordLine[2]
                    subcat = categories.split(":")[1:]
                    subcat = ":".join(subcat)
                    NewWordLine = lemme + "\t" + form + "\t" + subcat
                    # écrire la nouvelle ligne dans le fichier
                    new_abu.write(NewWordLine)
print ("Fichier abu_verbes.csv créé.")