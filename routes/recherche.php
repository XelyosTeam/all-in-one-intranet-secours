<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session;

Flight::route('/recherche/photo/ems', function() {
  header("Access-Control-Allow-Origin: *");
  verif_connecter();
  $id = $_POST["id"];

  // Récupération de l'info image véhicule
  $info = Agent::getInfoAgentIdEms($id);
  $data = [
    'grade' => $info->grade,
    'nom' => $info->nom,
    'photo' => $info->photo
  ];

  Flight::json($data);
});

Flight::route('/recherche/photo/personne', function() {
  header("Access-Control-Allow-Origin: *");
  verif_connecter();
  $id = $_POST["id"];

  // Récupération de l'info image véhicule
  $info = Personne::getinfoPersonne($id);
  $data = [
    'nom' => $info->nom,
    'prenom' => $info->prenom,
    'photo' => $info->photo
  ];

  Flight::json($data);
});
?>
