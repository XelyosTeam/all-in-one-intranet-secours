<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
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
  $intervention->rapport = renderHTMLFromMarkdown(htmlspecialchars(strip_tags(urldecode($intervention->rapport))));

  Flight::view()->display('fiche/intervention.twig', array(
    'civil' => Personne::getinfoPersonne($intervention->id_civil),
    'intervention' => $intervention,
    'ems' => Agent::getInfoAgentIdEms($intervention->enregistre_par)
  ));
});

Flight::route('/intervention/@id_intervention/impression', function($id_intervention) {
  verif_connecter();
  $impression = new generatePDF();

  $intervention = Intervention::getIntervention($id_intervention);
  $intervention->remarque = urldecode($intervention->remarque);
  $impression->intervention($intervention);
});
?>
