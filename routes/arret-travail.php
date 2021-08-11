<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
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
  $arret->motif = renderHTMLFromMarkdown(htmlspecialchars(strip_tags(urldecode($arret->motif))));

  Flight::view()->display('fiche/arret.twig', array(
    'civil' => Personne::getinfoPersonne($arret->personne),
    'arret' => $arret,
    'ems' => Agent::getInfoAgentIdEms($arret->enregistrer_par)
  ));
});

Flight::route('/arret-travail/@id_arret/impression', function($id_arret) {
  verif_connecter();
  $impression = new generatePDF();

  $arret = Arret::getArret($id_arret);
  $arret->motif = urldecode($arret->motif);
  $impression->arret($arret);
});
?>
