<?php

  /* -------------------------------------------------------------- */
  /*                            Cryptage                            */
  /* -------------------------------------------------------------- */

  function cryptage_mdp($mdp_init) {
    $mdp_init = hash("sha512", $mdp_init); // On crypt une première fois le mot de passe
    $mdp_init = division_mdp($mdp_init); // On divise par deux le mot de passe crypter
    $mdp_hashed = hash("sha512", $mdp_init); // On recrypt le tout

    return $mdp_hashed;
  }

  function division_mdp($mdp) {
    $moitie = round(strlen($mdp)/2); // Moitié du mdp

    $result[0] =  substr($mdp, 0, $moitie);
    $result[1] =  substr($mdp, 1 - $moitie);

    $mdp = $result[0] . $mdp . $result[1]; // On ajoute au début et à la fin
    return  $mdp;
  }

  function program_crypt($mdp, $salt) {
    $mdp = division_mdp($mdp); // On double le mot de passe
    $mdp = cryptage_mdp($mdp); // On passe par un algo de cryptage
    $mdp = salage_mdp($mdp, $salt); // On applique un salage sur le mot de passe
    $mdp = cryptage_mdp($mdp); // On passe par un algo de cryptage

    return $mdp;
  }

  function salage_mdp($mdp_init, $salt) {
    /* Division du salt */
    $moitie = round(strlen($salt)/2); // Moitié du salt
    $semi_salt[0] =  substr($salt, 0, $moitie);
    $semi_salt[1] =  substr($salt, 1 - $moitie);

    /* Division du mot de passe */
    $moitie = round(strlen($mdp_init)/2); // Moitié du mdp
    $semi_mdp[0] =  substr($mdp_init, 0, $moitie);
    $semi_mdp[1] =  substr($mdp_init, 1 - $moitie);

    $mdp_salt = $semi_salt[0] . $semi_mdp[0] . $salt . $semi_mdp[1] . $semi_salt[1];

    return $mdp_salt;
  }

?>
