<?php
use Josantonius\Session\Session;

Flight::route('/impression/@type/@numero', function($type, $numero) {
  verif_connecter();
  $impression = new generatePDF();
  switch ($type) {
    case 'civil':
        $civil = Personne::getinfoPersonne($numero);
        $impression->civil($civil);
      break;
    case 'intervention':
        $intervention = Intervention::getIntervention($numero);
        $impression->intervention($intervention);
      break;
    case 'arret-travail':
        $arret = Arret::getArret($numero);
        $impression->arret($arret);
      break;
    case 'certificat-travail':
        $certificat = Certificat::getCertificat($numero);
        $impression->travail($certificat);
      break;
    case 'certificat-ppa':
        $ppa = PPA::getCertificat($numero);
        $impression->ppa($ppa);
      break;
    case 'ordonnance':
        $ordonnance = Ordonnance::getInfo($numero);
        $impression->ordonnance($ordonnance);
      break;
    default:
      Flight::redirect("/connexion");
      break;
  }
});
?>
