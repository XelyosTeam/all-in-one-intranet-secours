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
  /* Variable récupéré dans le get */
  $nom = $_GET['civil_name'];
  $prenom = $_GET['civil_firstname'];
  $phone = $_GET['civil_phone'];
  /* Variable récupéré dans le get */
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

  $arret = Arret::getListArret($civil->id);
  $certificat = Certificat::getListCertificat($civil->id);

  $convert = array(
    array('arret', 'motif'),
    array('certificat', 'job_vise'),
  );
  foreach ($convert as $element) {
    $tableau = $element[0];
    $attribut = $element[1];
    foreach ($$tableau as $value) {
      $value->{$attribut} = urldecode($value->{$attribut});
    }
  }

  Flight::view()->display('fiche/civil.twig', array(
    'civil' => $civil,
    'interventions' => Intervention::getListIntervention($civil->id),
    'arrets' => $arret,
    'certificats' => $certificat,
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
  $phone = $_POST['telephone'];
  $sang = $_POST['sang'];
  $donneur = $_POST['organe'];
  $agent = Agent::getInfoAgent();
  $old_info = Personne::getinfoPersonne((int)$id_civil);
  editCivil((int)$id_civil, $sang, $donneur, $phone);

  if ($old_info->grp_sanguin != $sang) {
    addHistorique($agent->matricule, "2¤0¤1¤" . (int)$id_civil . "¤" . $old_info->grp_sanguin . "¤" . $sang);
  }

  if ($old_info->donneur != $donneur) {
    addHistorique($agent->matricule, "2¤0¤2¤" . (int)$id_civil . "¤" . $old_info->donneur . "¤" . $donneur);
  }

  if ($old_info->phone != $phone) {
    addHistorique($agent->matricule, "2¤0¤3¤" . (int)$id_civil . "¤" . $old_info->phone . "¤" . $phone);
  }

  Flight::redirect("/civil/$id_civil");
});

Flight::route('/civil/@id_civil/impression', function($id_civil) {
  verif_connecter();
  $impression = new generatePDF();
  $civil = Personne::getinfoPersonne($id_civil);
  $impression->civil($civil);
});
?>
