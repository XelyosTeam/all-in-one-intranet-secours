<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session;

Flight::route('/add/prof', function() {
  verif_connecter();
  verif_admin();
  /* Variable de POST */
  $id_ems = $_POST['nom_ems'];
  /* Variable de POST */

  $agent = Agent::getInfoAgent();
  updateProf($id_ems);
  addHistorique($agent->matricule, "0¤3¤0¤". Agent::getInfoAgentIdEms($id_ems)->matricule);
  Flight::redirect("/administration/ajout");
});

Flight::route("/delete/" . serveurIni('Faction', 'membre'), function() {
  verif_connecter();
  verif_enseignant();
  /* Variable de POST */
  $ems = $_POST['nom_ems'];
  /* Variable de POST */

  $agent = Agent::getInfoAgent();
  if ($agent->hab_1 != 2) { // N'est pas autorisé à ajouter
    Flight::redirect("/recrutement");
  }
  else {
    $info = Agent::getInfoAgentIdEms($ems);
    editLicenciement($info->ems_id);
    addHistorique($agent->matricule, "2¤2¤1¤$info->matricule");
    Flight::redirect("/" . serveurIni('Faction', 'membre') . "/$info->matricule"); // Redirection vers la page de l'agent
  }
});
?>
