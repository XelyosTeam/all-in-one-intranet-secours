<?php
  use Josantonius\Session\Session; // Pour utiliser les variables de sessions

  function add_historique_connexion($matricule, $mdp, $message, $etat, $ip) {
    $vehicule = Model::factory('Historique')->create();
    $vehicule->set(array(
                  'matricule_utilise' => $matricule,
                  'mpd_utilise' => $mdp,
                  'adresse_ip' => $ip,
                  'etat' => $etat,
                  'commentaire' => $message,
                  'date_connexion' => date("Y-m-d H:i:s")
                )
              );
    $vehicule->save();
  }

  function est_vide($matricule, $mdp) {
    if ((empty($matricule) == TRUE) OR (empty($mdp) == TRUE)) {
      Flight::redirect("/connexion"); // Redirige vers la page
      exit();
    }
  }

  function nb_echec($ip) {
    $nb_connexion_max = serveurIni('Parametre', 'echec_maximum');
    $nb_echec = Historique::getNbEchec($ip); // Récupération du nombre d'échec de l'utilisateur
    if ($nb_echec > $nb_connexion_max) {
      return TRUE;
    }
    return FALSE;
  }

  function protocole_connexion($matricule, $mdp, $ip) {
    if (nb_echec($ip)) { // Vérifie le nombre d'échec de l'ip
      $message = "Tentative de connexion trop importante";
      $etat = "Echec";
      add_historique_connexion($matricule, $mdp, $message, $etat, $ip);
      return 1;
    }

    $info = Agent::getInfoAgentMatricule($matricule);

    /* Vérifie que le matricule existe */
    if (!$info) {
      $message = "Le matricule saisi n'est pas correcte";
      $etat = "Echec";
      add_historique_connexion($matricule, $mdp, $message, $etat, $ip);
      return 2;
    }

    /* Vérifie le matricule est toujours en service */
    if ($info->grade_id == 1) {
      $message = "Le matricule n'est plus en service";
      $etat = "Echec";
      add_historique_connexion($matricule, $mdp, $message, $etat, $ip);
      return 3;
    }

    /* Vérification du mot de passe */
    $info_p = EMS_t::getInfoAgentMatricule($info->matricule);
    $mdp_hashed = program_crypt($mdp, $info_p->Sel_de_table);

    if ($mdp_hashed == $info_p->Passwd) {
      $message = "Connexion " . serveurIni('Faction', 'metierBDD');
      $etat = "Réussite";
      add_historique_connexion($matricule, $mdp_hashed, $message, $etat, $ip);
      variable_session($info->matricule); // Enregistrement des variables en variables de sessions
      return $info->grade . " " . $info->nom;
    }
    else if ($mdp_hashed != $info_p->Passwd) {
      $message = "Le mot de passe entré n'est pas valide";
      $etat = "Echec";
      add_historique_connexion($matricule, $mdp, $message, $etat, $ip);
      return 4;
    }
  }

  function variable_session($matricule) {
    Session::set("matricule_ems", $matricule);
  }

?>
