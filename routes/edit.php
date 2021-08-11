<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session;

Flight::route('/edit/mdp', function() {
  verif_connecter();
  /* Variable de POST */
  $ancien = $_POST['ancien_mdp'];
  $new = $_POST['new_mdp'];
  $new2 = $_POST['confirm_mdp'];
  /* Variable de POST */

  if (($new != $new2) or ($ancien == $new2)) {
    Flight::redirect("/nouveau-mot-de-passe");
    exit();
  }

  $agent = Agent::getInfoAgent();
  $ems = EMS_t::getInfoAgentMatricule($agent->matricule);

  $ancien_ashed = program_crypt($ancien, $ems->Sel_de_table);

  if ($ems->Passwd != $ancien_ashed) { // L'ancien mot de passe n'est pas identique au nouveau
    Flight::redirect("/nouveau-mot-de-passe");
    exit();
  }

  $salt = bin2hex(random_bytes(15)); // Génération  du sault
  $new_mdp_hashed = program_crypt($new, $salt); // On génère le mot de passe avec le nouveau salt

  mise_a_jour_mdp($agent->matricule, $new_mdp_hashed, $salt);

  addHistorique($agent->matricule, "2¤0¤0");

  Flight::redirect("/connexion"); // Redirige vers la page
});

Flight::route('/edit/mdp/admin', function() {
  verif_connecter();
  verif_admin();
  /* Variable de POST */
  $id_ems = $_POST['nom'];
  $new_mdp = $_POST['mdp'];
  /* Variable de POST */

  $salt = bin2hex(random_bytes(15)); // On génère un nouveau salt
  $new_mdp_hashed = program_crypt($new_mdp, $salt);

  $agent = Agent::getInfoAgentIdEms($id_ems);
  mise_a_jour_mdp($agent->matricule, $new_mdp_hashed, $salt);

  $op = Agent::getInfoAgent();
  addHistorique($op->matricule, "0¤2¤0$agent->matricule");
  Flight::redirect("/" . serveurIni('Faction', 'membre') . "/$agent->matricule"); // Redirige vers la page
});

Flight::route('/edit/rehabilitaton', function() {
  verif_connecter();
  /* Variable de POST */
  $id_ems = $_POST['id_ems'];
  /* Variable de POST */

  $agent = Agent::getInfoAgent();
  if ($agent->hab_1 != 2) { // N'est pas autorisé à ajouter
    Flight::redirect("/recrutement");
  }
  else {
    editEms($id_ems, 2, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, "");
    $old = Agent::getInfoAgentIdEms($id_ems);

    addHistorique($agent->matricule, "2¤2¤2¤$old->matricule");
    Flight::redirect("/" . serveurIni('Faction', 'membre') . "/$old->matricule");
  }
});
?>
