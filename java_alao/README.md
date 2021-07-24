Ce prototype s’inscrit dans le projet Innovalangue dont le but est de trouver des solutions innovantes pour apprendre et enseigner les langues. Dans ce projet général, notre travail s'inscrit plus précisément dans le projet SELF (Système d’Évaluation en Langues à visée Formative) qui vise à évaluer les productions écrites des apprenants (ici des apprenants en français). Dans ce contexte, nous proposons un prototype développé en Java qui permet de calculer différentes statistiques pour donner une idée de la production écrite de l’étudiant (sur un texte court de quelques phrases produites dans le cadre de quelques exercices simples proposés à l'étudiant).
Ce prototype se compose des fichiers suivants :

- Les classes d'ouverture et écriture des fichiers dans le dossier Files
- Les images utilisées pour les exercices dans le dossier
- Le fichier csv contenant les données de la ressource Lexique3
- Les classes Sentence et Word qui permettent les différentes analyses des phrases produites
- La classe GUI qui permet l'affichage des exercices dans une interface graphique
- La classe projetSELF qui nous a permis de vérifier le fonctionnement des différentes méthodes utilisées (elle n'est donc pas essentielle au fonctionnement du prototype)


Pour lancer l'application il suffit donc de lancer le fichier GUI.java. Une fenêtre s'ouvre alors et il est possible de remplir les réponses aux exercices. L'envoie des réponses génère un fichier résultats.txt qui est enregistré directement sur l'ordinateur de l'utilisateur avec les informations suivantes :

- Réponse correcte ou non pour l'exercice 1 avec affichage de la phrase écrite par l'étudiant si l'exercice est faux.
- Réponse correcte ou non pour l'exercice 2 avec affichage de la phrase écrite par l'étudiant si l'exercice est faux.
- Phrase produite pour l'exercice 3.
- Phrase produite pour l'exercice 4.
- La longueur moyenne des mots de la production
- Les catégories grammaticales utilisées
- La longueur moyenne des phrases
- Les mots qui sont répétés et le nombre de fois qu'ils apparaissent dans les deux exercices (3 et 4)
- La liste des mots utilisés par l'étudiants qui ne figurent pas dans le dictionnaire


Attention l'exercice 4 dépasse de la page, l'interface permet d'utiliser le scroll pour y avoir accès.
Normalement il n'est pas nécessaire de modifier les chemins d'accès aux différents fichiers.
