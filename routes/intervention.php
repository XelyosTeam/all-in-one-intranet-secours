<?php
use Josantonius\Session\Session;

Flight::route('/intervention', function() {
  verif_connecter();
  Flight::view()->display('add/intervention.twig', array(
    'civils' => Personne::getListPersonne(),
    'interventions' => InterventionList::getList()
  ));
});

Flight::route('/intervention/@id_intervention', function($id_intervention) {
  verif_connecter();
  $intervention = Intervention::getIntervention($id_intervention);
  $intervention->rapport = renderHTMLFromMarkdown(htmlspecialchars(strip_tags($intervention->rapport)));

  Flight::view()->display('fiche/intervention.twig', array(
    'civil' => Personne::getinfoPersonne($intervention->id_civil),
    'intervention' => $intervention,
    'ems' => Agent::getInfoAgentIdEms($intervention->enregistre_par)
  ));
});

?>
