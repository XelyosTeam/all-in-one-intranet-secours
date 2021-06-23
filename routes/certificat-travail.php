<?php
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
  $certificat->motif = renderHTMLFromMarkdown(htmlspecialchars(strip_tags($certificat->motif)));

  Flight::view()->display('fiche/certificat.twig', array(
    'civil' => Personne::getinfoPersonne($certificat->personne),
    'arret' => $certificat,
    'ems' => Agent::getInfoAgentIdEms($certificat->enregistrer_par)
  ));
});

?>
