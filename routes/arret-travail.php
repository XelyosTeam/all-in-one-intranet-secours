<?php
use Josantonius\Session\Session;

Flight::route('/arret-travail', function() {
  verif_connecter();
  $civils = Personne::getListPersonne();
  Flight::view()->display('add/arret-travail.twig', array(
    'civils' => $civils
  ));
});

Flight::route('/arret-travail/@id_arret', function($id_arret) {
  verif_connecter();
  $arret = Arret::getArret($id_arret);
      $arret->motif = renderHTMLFromMarkdown(htmlspecialchars(strip_tags($arret->motif)));

  Flight::view()->display('fiche/arret.twig', array(
    'civil' => Personne::getinfoPersonne($arret->personne),
    'arret' => $arret,
    'ems' => Agent::getInfoAgentIdEms($arret->enregistrer_par)
  ));
});

?>
