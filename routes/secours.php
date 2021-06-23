<?php
use Josantonius\Session\Session;

Flight::route("/" . serveurIni('Faction', 'membre'), function() {
  verif_connecter();
  verif_enseignant(); // Vérifie que la personne soit bien un enseignant
  /* Variable récupéré dans le get */
  $nom = $_GET['ems_name'];
  $prenom = $_GET['ems_firstname'];
  $mat = $_GET['ems_matricule'];
  /* Variable récupéré dans le get */

  Flight::view()->display('recherche/liste_ems.twig', array(
    'agents' => Agent::getListAgentTri($nom, $prenom, $mat),
    'nom' => $nom,
    'prenom' => $prenom,
    'matricule' => $mat
  ));
});

Flight::route("/" . serveurIni('Faction', 'membre') . "/@matricule", function($matricule) {
  verif_connecter();
  $agent = Agent::getInfoAgentMatricule($matricule);

  Flight::view()->display('fiche/ems.twig', array(
    'agent' => $agent,
    'voitures' => Voiture::getListCarEMS($agent->user_id)
  ));
});

Flight::route("/" . serveurIni('Faction', 'membre') . "/@matricule/edit", function($matricule) {
  verif_connecter();
  verif_enseignant();

  Flight::view()->display('edit/ems.twig', array(
    'agent' => Agent::getInfoAgentMatricule($matricule),
    'grades' => Grade::getList()
  ));
});

Flight::route("/" . serveurIni('Faction', 'membre') . "/@matricule/modification", function($matricule) {
  verif_connecter();
  verif_enseignant();

  /* Variable récupéré dans le post */
  $grade = $_POST['grade'];
  $note = $_POST['note'];
  if (isset($_POST['hab1'])) {
    $hab1 = $_POST['hab1'];
  }
  else {
    $hab1 = 1;
  }

  if (isset($_POST['hab2'])) {
    $hab2 = $_POST['hab2'];
  }
  else {
    $hab2 = 1;
  }

  if (isset($_POST['hab3'])) {
    $hab3 = $_POST['hab3'];
  }
  else {
    $hab3 = 1;
  }

  if (isset($_POST['hab4'])) {
    $hab4 = $_POST['hab4'];
  }
  else {
    $hab4 = 1;
  }

  if (isset($_POST['hab5'])) {
    $hab5 = $_POST['hab5'];
  }
  else {
    $hab5 = 1;
  }

  if (isset($_POST['hab6'])) {
    $hab6 = $_POST['hab6'];
  }
  else {
    $hab6 = 1;
  }

  if (isset($_POST['hab7'])) {
    $hab7 = $_POST['hab7'];
  }
  else {
    $hab7 = 1;
  }

  if (isset($_POST['hab8'])) {
    $hab8 = $_POST['hab8'];
  }
  else {
    $hab8 = 1;
  }

  if (isset($_POST['hab9'])) {
    $hab9 = $_POST['hab9'];
  }
  else {
    $hab9 = 1;
  }

  if (isset($_POST['hab10'])) {
    $hab10 = $_POST['hab10'];
  }
  else {
    $hab10 = 1;
  }

  if (isset($_POST['hab11'])) {
    $hab11 = $_POST['hab11'];
  }
  else {
    $hab11 = 1;
  }

  if (isset($_POST['hab12'])) {
    $hab12 = $_POST['hab12'];
  }
  else {
    $hab12 = 1;
  }

  if (isset($_POST['hab13'])) {
    $hab13 = $_POST['hab13'];
  }
  else {
    $hab13 = 1;
  }

  if (isset($_POST['hab14'])) {
    $hab14 = $_POST['hab14'];
  }
  else {
    $hab14 = 1;
  }

  if (isset($_POST['hab15'])) {
    $hab15 = $_POST['hab15'];
  }
  else {
    $hab15 = 1;
  }
  /* Variable récupéré dans le post */

  $oldinfo = Agent::getInfoAgentMatricule($matricule);
  $agent = Agent::getInfoAgent();

  if ($grade == 1) {
    editLicenciement($oldinfo->ems_id);
    addHistorique($agent->matricule, "2¤2¤1¤$oldinfo->matricule");
    Flight::redirect("/" . serveurIni('Faction', 'membre') . "/$oldinfo->matricule"); // Redirection vers la page de l'agent
  }
  else {
    editEMS($oldinfo->ems_id, $grade, $hab1, $hab2, $hab3, $hab4, $hab5, $hab6, $hab7, $hab8, $hab9, $hab10, $hab11, $hab12, $hab13, $hab14, $hab15, $note);
    update_metier($oldinfo->user_id, serveurIni('Faction', 'metierBDD'));
    AddHistoriqueModifAgent($oldinfo, $matricule, $grade, $hab1, $hab2, $hab3, $hab4, $hab5, $hab6, $hab7, $hab8, $hab9, $hab10, $hab11, $hab12, $hab13, $hab14, $hab15, $note);
  }

  Flight::redirect("/" . serveurIni('Faction', 'membre') . "/$matricule");
});

?>
