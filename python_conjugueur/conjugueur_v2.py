# -*- coding: utf8 -*-
#####################################
## Noms : GIROUD Océane et MARTIN Célia
##
## Créé le : 27/11/2019
##
## Fonctionnalité : conjugaison des verbes à tous les temps simples de tous les modes
#####################################
# Importation de fonctions externes :
# /////////////
#####################################
# ENREGISTREMENT DU DICTIONNAIRE DE VERBES DANS UNE TABLE DE HACHAGE
with open("abu_verbes.csv", "r") as ABUVerbs:
    TabVerbs = ABUVerbs.readlines()
    DicVerbs = {}
    for i in range(62, len(TabVerbs)):  # on traite à partir de la ligne 62 car avant on a la licence
        line = TabVerbs[i]
        cut = line.split("\t")
        lemma = cut[0]  # sélectionner le lemme (indice 0 de la liste contenant la ligne
        form = cut[1]  # sélectionner la forme (indice 1 de la liste contenant la ligne)
        sbcat = cut[2].split(
            ":")  # chercher la sous-catégorie (si la ligne contient 4 morceaux, le dernier contient l'information d'aspiration)
        if len(cut) == 4:
            h_aspir = cut[3][0:-1]  # on coupe le caractère "\n" au moment où on met ASP dans la variable
        else:
            h_aspir = "None"

        if lemma not in DicVerbs.keys():  # si on n'a pas le lemme dans le dico, on crée le lemme, sinon on fait rien, pour ne pas que deux lemmes identiques posent problème (l'un écrase l'autre)
            DicVerbs[lemma] = {}
        if form not in DicVerbs[
            lemma].keys():  # pareil pour la forme, si elle existe on n'écrase pas l'ancienne valeur lors de l'ajout
            DicVerbs[lemma][
                form] = {}  # on crée un dictionnaire dans la forme pour contenir la sous-catégorie et l'info d'aspiration
            DicVerbs[lemma][form]["subcategory"] = []  # on crée une liste des sous-catégories

        for i in range(0, len(
                sbcat)):  # on ajoute toutes les sous-catégories des formes dans la liste que nous avons créée
            DicVerbs[lemma][form]["subcategory"].append(sbcat[i])
        DicVerbs[lemma][form]["aspirated"] = h_aspir  # on ajoute l'information d'aspiration

###############################
# CONJUGONS !!
conjugate = "oui"  # pour savoir si on doit continuer ou non de conjuguer les verbes après avoir fini le script
print(
    "Bonjour ! Je suis un conjugueur très utile pour vous permettre de devenir imbattable dans la conjugaison des verbes du français !")
while conjugate == "oui":

    Verb = input("\nQuel verbe voulez-vous conjuguer (Aimer par défaut) ? ").lower()
    Tense = input("A quel temps (Présent par défaut) ? ").lower()
    Mod = input("A quel mode (Indicatif par défaut) ? \n").lower()

    if Verb == "":
        Verb = "aimer"

    if Mod in "indicatif":
        Mod = "I"
    elif Mod in "subjonctif":
        Mod = "S"
    elif Mod in "conditionnel":
        Mod = "C"
    elif Mod in "imperatif" or Mod in "impératif":
        Mod = "Im"
    elif Mod in "participe" or Mod == "pp":
        Mod = "P"
    else:  # mode par défaut = indicatif
        Mod = "I"

    if Tense in "present":
        Tense = "Pre"
    elif Tense in "passe" or Tense in "passé":
        Tense = "Pas"
    elif Tense in "imparfait":
        Tense = "Imp"
    elif Tense in "futur":
        Tense = "Fut"
    else:  # Temps par défaut = présent
        Tense = "Pre"

    Mod_Tense = Mod + Tense

    # Ecrire "J'" si on a une voyelle ou un h non aspiré
    Vowels = "aeiouyéè"
    try:  # vérifier que le verbe existe dans l'ABU (qu'il n'y a pas d'erreur de clé)
        aspir = DicVerbs[Verb][list(DicVerbs[Verb].keys())[0]]["aspirated"]
    except KeyError:
        Verb = input("\nVeuillez entrer un verbe présent dans le dictionnaire de l'ABU : ").lower()

    if Verb[0] in Vowels or (Verb[0] == "h" and aspir == "None"):
        je = "J'"
    else:
        je = "Je "

    if Mod_Tense == "SPre" or Mod_Tense == "SImp":  # adapter les pronoms du subjonctif
        Pronouns = ["Que " + je.lower(), "Que tu ", "Qu'il/Elle ", "Que nous ", "Que vous ", "Qu'ils/Elles "]
    elif Mod_Tense == "ImPre":  # adapter les pronoms de l'imperatif
        Pronouns = ["Tu ", "Nous ", "Vous "]
    elif Mod_Tense == "PPas":  # adapter les pronoms pour le participe présent
        Pronouns = ["Mas_sg", "Mas_pl", "Fem_sg", "Fem_pl"]
    elif Mod_Tense == "PPre":  # adapter les pronoms pour le participe présent
        Pronouns = ["PPRE"]
    else:  # dans les autres cas on a les pronoms classiques
        Pronouns = [je, "Tu ", "Il/Elle ", "Nous ", "Vous ", "Ils/Elles "]

    # trouver le verbe
    for line in DicVerbs:
        if Verb in line:
            # trouver les flexions
            DicForm = DicVerbs[Verb]  # On ne garde que le sous dictionnaire des flexions
            FlexOrdered = {}  # on crée une liste pour ordonner les personnes
            for keys in DicForm:  # On trouve toutes les flexions possibles du verbe
                Flex = keys
                NbSbcat = len(
                    DicVerbs[Verb][Flex]["subcategory"])  # parfois on a une forme flechie pour plusieurs sous-catégorie
                # réordonner les flexions dans l'ordre des personnes selon le mode (car pas le même nombre de flexion)
                for i in range(0,
                               NbSbcat):  # faire la réorganisation sur toutes les sous catégories de la flexion quand il y en a plusieurs pour la même forme
                    if Mod_Tense in DicVerbs[Verb][Flex]["subcategory"][i]:
                        if Mod_Tense == "ImPre":  # filtrer les sous-catégories qui correspondent à notre mode et notre temps : impératif pour lui donner le bon nombre de pronoms
                            # print(Flex, DicVerbs[Verb][Flex]["subcategory"][i])
                            if "SG+P2" in DicVerbs[Verb][Flex]["subcategory"][i]:
                                FlexOrdered[Pronouns[0]] = Flex
                            elif "PL+P1" in DicVerbs[Verb][Flex]["subcategory"][i]:
                                FlexOrdered[Pronouns[1]] = Flex
                            elif "PL+P2" in DicVerbs[Verb][Flex]["subcategory"][i]:
                                FlexOrdered[Pronouns[2]] = Flex
                        elif Mod_Tense == "PPas":  # participe passé
                            if "Mas+SG" in DicVerbs[Verb][Flex]["subcategory"][i]:
                                FlexOrdered[Pronouns[0]] = Flex
                            elif "Mas+PL" in DicVerbs[Verb][Flex]["subcategory"][i]:
                                FlexOrdered[Pronouns[1]] = Flex
                            elif "Fem+SG" in DicVerbs[Verb][Flex]["subcategory"][i]:
                                FlexOrdered[Pronouns[2]] = Flex
                            elif "Fem+PL" in DicVerbs[Verb][Flex]["subcategory"][i]:
                                FlexOrdered[Pronouns[3]] = Flex
                        elif Mod_Tense == "PPre":  # participe présent
                            if "PPre" in DicVerbs[Verb][Flex]["subcategory"][i]:
                                FlexOrdered[Pronouns[0]] = Flex
                        else:  # pour les autres temps on a le même nombre de flexions
                            if "SG+P1" in DicVerbs[Verb][Flex]["subcategory"][i]:
                                FlexOrdered[Pronouns[0]] = Flex
                            elif "SG+P2" in DicVerbs[Verb][Flex]["subcategory"][i]:
                                FlexOrdered[Pronouns[1]] = Flex
                            elif "SG+P3" in DicVerbs[Verb][Flex]["subcategory"][i]:
                                FlexOrdered[Pronouns[2]] = Flex
                            elif "PL+P1" in DicVerbs[Verb][Flex]["subcategory"][i]:
                                FlexOrdered[Pronouns[3]] = Flex
                            elif "PL+P2" in DicVerbs[Verb][Flex]["subcategory"][i]:
                                FlexOrdered[Pronouns[4]] = Flex
                            elif "PL+P3" in DicVerbs[Verb][Flex]["subcategory"][i]:
                                FlexOrdered[Pronouns[5]] = Flex

    if Mod_Tense == "ImPre" or Mod_Tense == "PPas" or Mod_Tense == "PPre":  # on n'imprime pas le pronom pour imperatif et participe passé
        try:  # on teste si on a toutes les informations pour le verbe (toutes les personnes) ou non
            for pronouns in Pronouns:
                print(FlexOrdered[pronouns])
        except KeyError:
            print("Désolé, le dictionnaire de l'ABU ne possède pas les informations pour ce verbe.\n")
    else:
        try:
            for pronouns in Pronouns:  # sinon  on imprime les pronoms
                print(pronouns + FlexOrdered[pronouns])
        except KeyError:
            print("Désolé, le dictionnaire de l'ABU ne possède pas les informations pour ce verbe.\n")

    conjugate = input("\nSi vous souhaitez conjuguer un autre verbe, tapez oui :  ").lower()

    if conjugate != "oui":
        print("\nMerci d'avoir utilisé notre conjugueur, à très bientôt !")
