<?php
use Josantonius\Session\Session;

Flight::route('/medicament/get_info', function() {
  header("Access-Control-Allow-Origin: *");
  verif_connecter();
  verif_admin();

  $data = [
    'info' => Medicament_Liste::getInfo($_POST['med_id'])->description
  ];

  Flight::json($data);
});

Flight::route('/medicament/get_list', function() {
  header("Access-Control-Allow-Origin: *");
  verif_connecter();
  verif_admin();

  $liste = Medicament_Liste::getList();
  foreach ($liste as $key => $med) {
    $data[$key] = [
      'id' => $med->id,
      'name' => $med->nom,
      // 'info' => $med->description
    ];
  }

  Flight::json($data);
});

Flight::route('/medicament/add/ordonnance', function() {
  header("Access-Control-Allow-Origin: *");
  verif_connecter();

  $id = $_POST['personne_type'];
  $agent = Agent::getInfoAgent();
  $date = addOronnance($id, $agent->ems_id);
  $info = Ordonnance::getLastOrdonnance($id, $agent->ems_id, $date);

  $data = [
    'n_ordonnance' => $info->id
  ];

  addHistorique($agent->matricule, "1¤1¤0¤" . $info->id . "¤" . $id);

  Flight::json($data);
});

Flight::route('/medicament/add/medicament', function() {
  verif_connecter();

  $id = $_POST['id'];
  $nom = $_POST['nom'];
  $quantite = $_POST['quantite'];
  $periode = $_POST['periode'];
  $hz = $_POST['hz'];

  addMedicamentOrdonnance($id, $nom, $quantite, $periode, $hz);

  $agent = Agent::getInfoAgent();
  addHistorique($agent->matricule, "1¤1¤1¤" . $nom . "¤" . $id);
});

Flight::route('/medicament/edit/desc', function() {
  verif_connecter();
  verif_admin();

  $id = $_POST['id'];
  $desc = $_POST['dscpt'];

  $old = Medicament_Liste::getInfo($id);

  if ($old->description != $desc) {
    editDescMed($id, $desc);
    addHistorique(Agent::getInfoAgent()->matricule, "0¤2¤1¤" . $id . "¤" . $old->description . "¤" . $desc);
  }
});

?>
