<?php
/* Utilisation de la base de données */
include 'entity/models.php';

/* Fonction de cryptage des mots de passe */
include 'src/cryptage.php';

/* Fonctions générals d'utilisation */
include 'src/functions.php';

/* Procédure de connexion à l'intranet */
include 'src/connect.php';

/* Edition de données dans la base de données */
include 'src/edit.php';

/* Insertion de données dans la base de données */
include 'src/insertion.php';

/* Lecture des informations dans le .env */
include 'src/configParser.php';

/* Gestion de l'historique */
include 'src/historique.php';

/* Gestion des impressions */
include 'src/impression.php';
?>
