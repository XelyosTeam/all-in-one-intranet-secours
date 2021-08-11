<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
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
  $ppa->rapport = renderHTMLFromMarkdown(htmlspecialchars(strip_tags(urldecode($ppa->rapport))));

  Flight::view()->display('fiche/ppa.twig', array(
    'civil' => Personne::getinfoPersonne($ppa->personne),
    'ppa' => $ppa,
    'ems' => Agent::getInfoAgentIdEms($ppa->enregistrer_par)
  ));
});

Flight::route('/certificat-ppa/@id_ppa/impression', function($id_ppa) {
  verif_connecter();
  $impression = new generatePDF();

  $ppa = PPA::getCertificat($id_ppa);
  $ppa->rapport = urldecode($ppa->rapport);
  $impression->ppa($ppa);
});
?>
