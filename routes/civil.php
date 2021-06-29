<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session;

Flight::route('/civil', function() {
  verif_connecter();
  $nom = $_GET['civil_name'];
  $prenom = $_GET['civil_firstname'];
  $phone = $_GET['civil_phone'];
  /* Variable récupérées dans le get */
  Flight::view()->display('recherche/civil.twig', array(
    'civils' => Personne::getListPersonneTri($nom, $prenom, $phone),
    'nom' => $nom,
    'prenom' => $prenom,
    'phone' => $phone
  ));
});

Flight::route('/civil/@id_civil', function($id_civil) {
  verif_connecter();
  $civil = Personne::getinfoPersonne($id_civil);

  Flight::view()->display('fiche/civil.twig', array(
    'civil' => $civil,
    'interventions' => Intervention::getListIntervention($civil->id),
    'arrets' => Arret::getListArret($civil->id),
    'certificats' => Certificat::getListCertificat($civil->id),
    'ppas' => PPA::getListCertificat($civil->id),
    'ems' => Agent::getInfoAgentIDUser($civil->id),
    'ordonnances' => Ordonnance::getListID($civil->id)
  ));
});

Flight::route('/civil/@id_civil/edit', function($id_civil) {
  verif_connecter();
  $civil = Personne::getinfoPersonne($id_civil);

  Flight::view()->display('edit/civil.twig', array(
    'perso' => $civil,
    'interventions' => Intervention::getListIntervention($civil->id),
    'arrets' => Arret::getListArret($civil->id),
    'certificats' => Certificat::getListCertificat($civil->id),
    'ppas' => PPA::getListCertificat($civil->id),
    'ems' => Agent::getInfoAgentIDUser($civil->id)
  ));
});

Flight::route('/civil/@id_civil/modification', function($id_civil) {
  verif_connecter();
  if (!isset($_POST['sang'])) {
    Flight::redirect("/civil/$id_civil");
    exit();
  }
  else {
    $sang = $_POST['sang'];
    $donneur = $_POST['organe'];
    $agent = Agent::getInfoAgent();
    $old_info = Personne::getinfoPersonne((int)$id_civil)->grp_sanguin;
    editCivil((int)$id_civil, $sang, $donneur);

    if ($old_info != $sang) {
      addHistorique($agent->matricule, "2¤0¤1¤" . (int)$id_civil . "¤" . $old_info . "¤" . $sang);
    }
  }

  Flight::redirect("/civil/$id_civil");
});

?>
