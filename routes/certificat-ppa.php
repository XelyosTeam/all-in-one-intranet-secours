<?php
use Josantonius\Session\Session;

Flight::route('/certificat-ppa', function() {
  verif_connecter();

  Flight::view()->display('add/ppa.twig', array(
    'civils' => Personne::getPPA()
  ));
});

Flight::route('/certificat-ppa/@id_ppa', function($id_ppa) {
  verif_connecter();
  $ppa = PPA::getCertificat($id_ppa);
  $ppa->rapport = renderHTMLFromMarkdown(htmlspecialchars(strip_tags($ppa->rapport)));

  Flight::view()->display('fiche/ppa.twig', array(
    'civil' => Personne::getinfoPersonne($ppa->personne),
    'ppa' => $ppa,
    'ems' => Agent::getInfoAgentIdEms($ppa->enregistrer_par)
  ));
});

?>
