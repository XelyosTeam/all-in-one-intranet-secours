<?php
/*
Le projet All in One est un produit Xelyos mis à disposition gratuitement
pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
ne pas supprimer le ou les auteurs du projet.
Created by : Xelyos - Aros
Edited by :
*/
use Josantonius\Session\Session;

Flight::route('/certificat-travail', function() {
  verif_connecter();

  Flight::view()->display('add/certificat.twig', array(
    'civils' => Personne::getListPersonne()
  ));
});

Flight::route('/certificat-travail/@id_certificat', function($id_certificat) {
  verif_connecter();
  $certificat = Certificat::getCertificat($id_certificat);
  // Traitement texte
  $certificat->job_vise = urldecode($certificat->job_vise);
  $certificat->motif = renderHTMLFromMarkdown(htmlspecialchars(strip_tags(urldecode($certificat->motif))));

  Flight::view()->display('fiche/certificat.twig', array(
    'civil' => Personne::getinfoPersonne($certificat->personne),
    'arret' => $certificat,
    'ems' => Agent::getInfoAgentIdEms($certificat->enregistrer_par)
  ));
});

Flight::route('/certificat-travail/@id_certificat/impression', function($id_certificat) {
  verif_connecter();
  $impression = new generatePDF();

  $certificat = Certificat::getCertificat($id_certificat);
  $certificat->job_vise = urldecode($certificat->job_vise);
  $certificat->motif = urldecode($certificat->motif);
  $impression->travail($certificat);
});
?>
