<?php
use Josantonius\Session\Session;

Flight::route('/recrutement', function() {
  verif_connecter();
  verif_enseignant();
  Flight::view()->display('ecole/ems.twig', array(
    'civils' => Personne::OldEms(),
    'ems' => Agent::getListAgent(),
    'oldems' => Agent::getListOldAgent()
  ));
});
?>
