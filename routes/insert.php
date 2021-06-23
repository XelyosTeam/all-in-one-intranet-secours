<?php
use Josantonius\Session\Session;

Flight::route('/insert/arret', function() {
  verif_connecter();
  /* Variable de POST */
  $civil = $_POST['id_civil'];
  $duree = $_POST['time'];
  $rapport = $_POST['motif'];
  /* Variable de POST */

  $agent = Agent::getInfoAgent();
  addArret($civil, $duree, $agent->ems_id, $rapport);
  addHistorique($agent->matricule, "1¤0¤0¤$civil");

  $info = Arret::getIDArret($civil, $agent->ems_id);
  Flight::redirect("/arret-travail/$info->id");
});

Flight::route('/insert/certificat', function() {
  verif_connecter();
  /* Variable de POST */
  $civil = $_POST['citoyen'];
  $etat = $_POST['etat_job'];
  $rapport = $_POST['motif'];
  $metier = $_POST['job'];
  /* Variable de POST */

  $agent = Agent::getInfoAgent();
  addCertificat($civil, $etat, $agent->ems_id, $metier, $rapport);
  addHistorique($agent->matricule, "1¤0¤1¤$civil");

  $info = Certificat::getIDCertificat($civil, $agent->ems_id);
  Flight::redirect("/certificat-travail/$info->id");
});

Flight::route('/insert/medicaments', function() {
  verif_connecter();
  verif_admin();
  /* Variable de POST */
  $nom = $_POST['name'];
  $etat = $_POST['description'];
  /* Variable de POST */

  $agent = Agent::getInfoAgent();
  addMedicaments($nom, $etat);
  addHistorique($agent->matricule, "0¤1¤2¤$nom" . "¤" . $etat);
});

Flight::route('/insert/'. serveurIni('Faction', 'membre'), function() {
  verif_connecter();
  verif_enseignant();
  /* Variable de POST */
  $civil = $_POST['nom_civil'];
  /* Variable de POST */

  $agent = Agent::getInfoAgent();
  if ($agent->hab_1 != 2) { // N'est pas autorisé à ajouter
    Flight::redirect("/recrutement");
  }
  else {
    $matricule = addEMS($civil);
    addHistorique($agent->matricule, "2¤2¤0¤$matricule");
    Flight::redirect("/" . serveurIni('Faction', 'membre') . "/$matricule"); // Redirection vers la page de l'agent
  }
});

Flight::route('/insert/intervention', function() {
  verif_connecter();
  /* Variable de POST */
  $civil = $_POST['id_civil'];
  $inter = $_POST['inter_name'];
  $rapport = $_POST['rapport'];
  /* Variable de POST */

  if ($inter == 'NULL') {
    Flight::redirect("/intervention");
  }

  $agent = Agent::getInfoAgent();
  addIntervention($civil, $inter, $agent->ems_id, $rapport);
  addHistorique($agent->matricule, "1¤0¤2¤$civil" . "¤" . $inter);

  $info = Intervention::getIDIntervention($civil, $agent->ems_id);
  Flight::redirect("/intervention/$info->inter_id");
});

Flight::route('/insert/intervention-type', function() {
  verif_connecter();
  verif_admin();
  /* Variable de POST */
  $nom = $_POST['inter_name'];
  /* Variable de POST */

  $agent = Agent::getInfoAgent();
  addInterventionList($nom);
  addHistorique($agent->matricule, "0¤1¤0¤$nom");
});

Flight::route('/insert/ppa', function() {
  verif_connecter();
  /* Variable de POST */
  $civil = $_POST['id_civil'];
  $etat = $_POST['etat_ppa'];
  $motif = $_POST['motif'];
  /* Variable de POST */

  $agent = Agent::getInfoAgent();
  addPPA($civil, $etat, $motif, $agent->ems_id);
  addHistorique($agent->matricule, "1¤0¤3¤$civil");

  $info = PPA::getIDPPA($civil, $agent->ems_id);
  Flight::redirect("/certificat-ppa/$info->id");
});

?>
