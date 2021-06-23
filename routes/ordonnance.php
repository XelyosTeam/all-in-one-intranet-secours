<?php
use Josantonius\Session\Session;

Flight::route('/ordonnance', function() {
  verif_connecter();

  Flight::view()->display('add/ordonnance.twig', array(
    'civils' => Personne::getListPersonne(),
    "medicaments" => Medicament_Liste::getList()
  ));
});

Flight::route('/ordonnance/@n_ordonnance', function($n_ordonnance) {
  verif_connecter();

  $ordonnance = Ordonnance::getInfo($n_ordonnance);
  if ($ordonnance == NULL) {
    Flight::redirect("/ordonnance");
    return;
  }

  Flight::view()->display('fiche/ordonnance.twig', array(
    'civil' => Personne::getinfoPersonne($ordonnance->patient),
    'ems' => Agent::getInfoAgentIdEms($ordonnance->enregistrer_par),
    'medicaments' => Info_Ordonnance::getList($n_ordonnance),
    'ordonnance' => Ordonnance::getInfo($n_ordonnance)
  ));
});

?>
