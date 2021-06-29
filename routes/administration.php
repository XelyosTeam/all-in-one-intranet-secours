<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session;

Flight::route('/administration/ajout', function() {
  verif_connecter();
  verif_admin();
  Flight::view()->display('administration/ajout.twig', array(
    'agents' => Agent::getListNonProf()
  ));
});

Flight::route('/administration/details/echec', function() {
  verif_connecter();
  verif_admin();
  if (!isset($_POST['adresse'])) {
    Flight::redirect("/administration/modification");
    exit();
  }
  else {
    $ip = $_POST['adresse'];
  }

  Flight::view()->display('administration/detail_echec.twig', array(
    'ip' => $ip,
    'listes' => Historique::getEchecAdresse($ip)
  ));
});

Flight::route('/administration/modification', function() {
  verif_connecter();
  verif_admin();
  Flight::view()->display('administration/modification.twig', array(
    'ems' => Agent::getListAgent(),
    'echecs' =>  Historique::getEchec(),
    'medicaments' => Medicament_Liste::getList()
  ));
});

Flight::route('/administration/parametre-serveur', function() {
  verif_connecter();
  verif_admin();

  Flight::view()->display('administration/server_param.twig', array(
    'fail' => serveurIni('Parametre', 'echec_maximum')
  ));
});

Flight::route('/administration/parametre-serveur/modification', function() {
  verif_connecter();
  verif_admin();

  if ($_POST['failed_connexion'] != '') {
    editserveurIni('Parametre', 'echec_maximum', $_POST['failed_connexion']);
    addHistorique(Session::get('matricule_ems'), "0¤0¤0¤" . $_POST['failed_connexion']);
  }

  Flight::redirect("/administration/parametre-serveur");
});

Flight::route('/administration/historique', function() {
  verif_connecter();
  verif_admin();

  Flight::view()->display('administration/historique.twig', array(
    'ems' => Agent::getListAgent(),
    'oldems' => Agent::getListOldAgent()
  ));
});

Flight::route('/administration/historique/get_info', function() {
  header("Access-Control-Allow-Origin: *");
  verif_connecter();
  verif_admin();

  $agent = Agent::getInfoAgentMatricule($_POST['matricule_ems']);
  $liste = Historique_EMS::getAction($_POST['matricule_ems']);

  if ($agent == NULL) { // L'agent n'existe pas
    $data = [
      'etat' => 0,
      'nom' => "Erreur dans l'envoi du matricule"
    ];
  }
  else { // L'agent existe
    // On récupère le nom de l'individu
    if ($agent->grade_id != 1) {
      $name = $agent->grade . " " . $agent->nom;
    }
    else {
      $name = $agent->nom . " " . $agent->prenom;
    }

    if ($liste == NULL) { // L'agent n'a pas envore fait d'action
      $data = [
        'etat' => 0,
        'nom' => $name
      ];
    }
    else {
      $i = 0;
      foreach ($liste as $key => $action) {

        try {
          $event = decryptHistorique($action->contenu);
        } catch (\Exception $e) {
          $event = $action->contenu;
        }

        $data[$key] = [
          'id' => $action->id,
          'date' => $action->date_even,
          'event' => $event
        ];
        $i++;
      }

      $data[$i] = ['etat' => $name];
    }
  }

  Flight::json($data);
});

Flight::route('/administration/historique_connexion/get_info', function() {
  header("Access-Control-Allow-Origin: *");
  verif_connecter();
  verif_admin();

  $agent = Agent::getInfoAgentMatricule($_POST['matricule_ems']);
  $liste = Historique::getListMatricule($_POST['matricule_ems']);

  if ($agent == NULL) { // L'agent n'existe pas
    $data = [
      'etat' => 0,
      'nom' => "Erreur dans l'envoi du matricule"
    ];
  }
  else { // L'agent existe
    // On récupère le nom de l'individu
    if ($agent->grade_id != 1) {
      $name = $agent->grade . " " . $agent->nom;
    }
    else {
      $name = $agent->nom . " " . $agent->prenom;
    }

    if ($liste == NULL) { // L'agent n'a pas envore fait d'action
      $data = [
        'etat' => 0,
        'nom' => $name
      ];
    }
    else {
      $i = 0;
      foreach ($liste as $key => $action) {
        $data[$key] = [
          'id' => $action->id,
          'date' => $action->date_connexion,
          'content' => $action->commentaire
        ];
        $i++;
      }

      $data[$i] = ['etat' => $name];
    }
  }

  Flight::json($data);
});

Flight::route('/administration/historique/get_stats', function() {
  header("Access-Control-Allow-Origin: *");
  verif_connecter();
  verif_admin();

  $agent = Agent::getInfoAgentMatricule($_POST['matricule_cop']);

  if ($agent) {
    $data = [
      'etat' => 1,
      'login' => Historique::getNbConnect($agent->matricule),
      'action' => Historique_EMS::getNbAction($agent->matricule),
      'arret' => Arret::getNbArret($agent->ems_id),
      'ppa' => PPA::getPPA($agent->ems_id),
      'travail' => Certificat::getNbCertificat($agent->ems_id)
    ];
  }
  else {
    $data = [
      'etat' => 0
    ];
  }

  Flight::json($data);
});

Flight::route('/delete/@adress_ip', function($adresse_ip) {
  verif_connecter();
  verif_admin();

  $adresse_ip = str_replace('-', '.', $adresse_ip);
  deleteIP($adresse_ip);
  $op = Agent::getInfoAgent();
  addHistorique($op->matricule, "0¤1¤1¤$adresse_ip");
  Flight::redirect("/administration/modification");
});

?>
