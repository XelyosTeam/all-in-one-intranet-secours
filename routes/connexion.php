<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session;

Flight::route('/connect', function() {
  header("Access-Control-Allow-Origin: *");
  /* Variable de POST */
  $matricule = $_POST['user_matricule'];
  $mdp = $_POST['user_mdp'];
  $ip_user = $_SERVER['REMOTE_ADDR'];
  /* Variable de POST */

  // protocole_connexion($matricule, $mdp, $ip_user);
  $etat = protocole_connexion($matricule, $mdp, $ip_user);
  switch ($etat) {
    case 1:
      $message = "Tentative de connexion trop importante !";
      break;
    case 2:
      $message = "Le matricule saisi n'est pas valide";
      break;
    case 3:
      $message = "Le matricule saisi n'est plus en service";
      break;
    case 4:
      $message = "Le mot de passe entré n'est pas valide";
      break;
    default:
      $message = $etat;
      $etat = 0;
      break;
  }

  $data = [
    'etat' => $etat,
    'message' => $message
  ];

  Flight::json($data);
});

Flight::route('/connexion', function() {
  Session::destroy(); // Destruction de session
  Flight::view()->display('connect.twig', array());
});

Flight::route('/nouveau-mot-de-passe', function() {
  verif_connecter();
  Flight::view()->display('new_mdp.twig'); // Page de connexion --> Faire en sorte que si il n'existe pas de variable d'environnement, ce soit la page par défaut
});
?>
