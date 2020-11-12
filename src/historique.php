<?php

function decryptHistorique($code) {
  $code = explode("¤", $code);
  $prefix = $code[0] . "¤" . $code[1] . "¤" . $code[2];

  switch ($prefix) {
    /* Mode Admin */
    // Modification Paramètre Serveur
    case '0¤0¤0':
      return "ADMIN : Modification Paramètre Serveur - Echec Maximum - " . $code[3];
      break;
    case '0¤1¤0':
      return "ADMIN : Ajout intervention dans la liste => " . $code[3];
      break;
    case '0¤1¤1':
      return "ADMIN : Déblocage accès - " . $code[3];
      break;
    case '0¤1¤2':
      return "ADMIN : Ajout Médicament - " . $code[3] . " - " . $code[4];
      break;
    case '0¤2¤0':
      return "ADMIN : Modification mot de passe - " . $code[3] . " =>" . getEmsName($code[3]);
      break;
    case '0¤2¤1':
      return "ADMIN : Modification description médicament - " . $code[3] . " =>" . getMedName($code[3]) . " || " . $code[4] . " >>> " . $code[5];
      break;
    case '0¤3¤0':
      return "ADMIN : Nouveau " . serveurIni('HABILITATION', 'hab_1') . " => " . $code[3] . " =>" . getEmsName($code[3]);
      break;

    /* Ajout dans le registre */
    case '1¤0¤0':
      return "Arrêt de travail - " . $code[3] . " => " . getCivilName($code[3]);
      break;
    case '1¤0¤1':
      return "Certificat de travail - " . $code[3] . " => " . getCivilName($code[3]);
      break;
    case '1¤0¤2':
      return "Intervention - " . $code[3] . " => " . getCivilName($code[3]) . " || Nom intervention : " . $code[4];
      break;
    case '1¤0¤3':
      return "Certificat PPA - " . $code[3] . " => " . getCivilName($code[3]);
      break;
    case '1¤1¤0':
      return "Création Ordonnance n°" . $code[3] . " - " . $code[4] . " => " . getCivilName($code[4]);
      break;
    case '1¤1¤1':
      return "Odonnance n°" . $code[4] . " - Ajout - " . getMedName($code[3]);
      break;

    /* Modification Fiche */
    case '2¤0¤0':
      return "Changement mot de passe";
      break;
    case '2¤0¤1':
      return "Modification Groupe sanguin - " . $code[3] . " => " . getCivilName($code[3]) . " || " . $code[4] . " >>> " . $code[5];
      break;

    /* Ecole */
    case '2¤1¤0':
      return "Ecole : Edition " . serveurIni('Faction', 'metierBDD') . " - Changement Note - " . $code[3] . " => "
            . getEmsName($code[3]) . " || " . $code[4] . " >>> " . $code[5];
      break;
    case '2¤1¤1':
      return "Ecole : Edition " . serveurIni('Faction', 'metierBDD') . " - Changement Grade - " . $code[3] . " => "
            . getEmsName($code[3]) . " || " . getGrade($code[4]) . " >>> " . getGrade($code[5]);
      break;
    case '2¤1¤2':
    return "Ecole : Edition " . serveurIni('Faction', 'metierBDD') . " - Changement " . serveurIni('HABILITATION', 'hab_1') . " - " . $code[3] . " => "
          . getEmsName($code[3]) . " || " . getEtat($code[4]) . " >>> " . getEtat($code[5]);
      break;
    case '2¤1¤3':
    return "Ecole : Edition " . serveurIni('Faction', 'metierBDD') . " - Changement " . serveurIni('HABILITATION', 'hab_2') . " - " . $code[3] . " => "
          . getEmsName($code[3]) . " || " . getEtat($code[4]) . " >>> " . getEtat($code[5]);
      break;
    case '2¤1¤4':
    return "Ecole : Edition " . serveurIni('Faction', 'metierBDD') . " - Changement " . serveurIni('HABILITATION', 'hab_3') . " - " . $code[3] . " => "
          . getEmsName($code[3]) . " || " . getEtat($code[4]) . " >>> " . getEtat($code[5]);
      break;
    case '2¤1¤5':
    return "Ecole : Edition " . serveurIni('Faction', 'metierBDD') . " - Changement " . serveurIni('HABILITATION', 'hab_4') . " - " . $code[3] . " => "
          . getEmsName($code[3]) . " || " . getEtat($code[4]) . " >>> " . getEtat($code[5]);
      break;
    case '2¤1¤6':
    return "Ecole : Edition " . serveurIni('Faction', 'metierBDD') . " - Changement " . serveurIni('HABILITATION', 'hab_5') . " - " . $code[3] . " => "
          . getEmsName($code[3]) . " || " . getEtat($code[4]) . " >>> " . getEtat($code[5]);
      break;
    case '2¤1¤7':
    return "Ecole : Edition " . serveurIni('Faction', 'metierBDD') . " - Changement " . serveurIni('HABILITATION', 'hab_6') . " - " . $code[3] . " => "
          . getEmsName($code[3]) . " || " . getEtat($code[4]) . " >>> " . getEtat($code[5]);
      break;
    case '2¤1¤8':
    return "Ecole : Edition " . serveurIni('Faction', 'metierBDD') . " - Changement " . serveurIni('HABILITATION', 'hab_7') . " - " . $code[3] . " => "
          . getEmsName($code[3]) . " || " . getEtat($code[4]) . " >>> " . getEtat($code[5]);
      break;
    case '2¤1¤9':
    return "Ecole : Edition " . serveurIni('Faction', 'metierBDD') . " - Changement " . serveurIni('HABILITATION', 'hab_8') . " - " . $code[3] . " => "
          . getEmsName($code[3]) . " || " . getEtat($code[4]) . " >>> " . getEtat($code[5]);
      break;
    case '2¤1¤10':
    return "Ecole : Edition " . serveurIni('Faction', 'metierBDD') . " - Changement " . serveurIni('HABILITATION', 'hab_9') . " - " . $code[3] . " => "
          . getEmsName($code[3]) . " || " . getEtat($code[4]) . " >>> " . getEtat($code[5]);
      break;
    case '2¤1¤11':
    return "Ecole : Edition " . serveurIni('Faction', 'metierBDD') . " - Changement " . serveurIni('HABILITATION', 'hab_10') . " - " . $code[3] . " => "
          . getEmsName($code[3]) . " || " . getEtat($code[4]) . " >>> " . getEtat($code[5]);
      break;
    case '2¤1¤12':
    return "Ecole : Edition " . serveurIni('Faction', 'metierBDD') . " - Changement " . serveurIni('HABILITATION', 'hab_11') . " - " . $code[3] . " => "
          . getEmsName($code[3]) . " || " . getEtat($code[4]) . " >>> " . getEtat($code[5]);
      break;
    case '2¤1¤13':
    return "Ecole : Edition " . serveurIni('Faction', 'metierBDD') . " - Changement " . serveurIni('HABILITATION', 'hab_12') . " - " . $code[3] . " => "
          . getEmsName($code[3]) . " || " . getEtat($code[4]) . " >>> " . getEtat($code[5]);
      break;
    case '2¤1¤14':
    return "Ecole : Edition " . serveurIni('Faction', 'metierBDD') . " - Changement " . serveurIni('HABILITATION', 'hab_13') . " - " . $code[3] . " => "
          . getEmsName($code[3]) . " || " . getEtat($code[4]) . " >>> " . getEtat($code[5]);
      break;
    case '2¤1¤15':
    return "Ecole : Edition " . serveurIni('Faction', 'metierBDD') . " - Changement " . serveurIni('HABILITATION', 'hab_14') . " - " . $code[3] . " => "
          . getEmsName($code[3]) . " || " . getEtat($code[4]) . " >>> " . getEtat($code[5]);
      break;
    case '2¤1¤16':
    return "Ecole : Edition " . serveurIni('Faction', 'metierBDD') . " - Changement " . serveurIni('HABILITATION', 'hab_15') . " - " . $code[3] . " => "
          . getEmsName($code[3]) . " || " . getEtat($code[4]) . " >>> " . getEtat($code[5]);
      break;

    case '2¤2¤0':
      return "Ecole : Ajout " . serveurIni('Faction', 'metierBDD') . " - " . $code[3] . " => " . getEmsName($code[3]);
      break;
    case '2¤2¤1':
      return "Ecole : Licenciement " . serveurIni('Faction', 'metierBDD') . " - " . $code[3] . " => " . getEmsName($code[3]);
      break;
    case '2¤2¤2':
      return "Ecole : Réhabilitation " . serveurIni('Faction', 'metierBDD') . " - " . $code[3] . " => " . getEmsName($code[3]);
      break;

    default:
      return "Erreur dans le décryptage de la solution";
      break;
  }
}

function getCivilName($id) {
  $personne = Personne::getinfoPersonne($id);
  if ($personne) {
    return $personne->nom . " " . $personne->prenom;
  }
  else {
    return "Civil n°$id inexistant";
  }
}

function getEmsName($matricule) {
  $personne = Agent::getInfoAgentMatricule($matricule);
  if ($personne) {
    return $personne->nom . " " . $personne->prenom;
  }
  else {
    return "Agent matricule $matricule inexistant";
  }
}

function getMedName($id) {
  $name = Medicament_Liste::getInfo($id)->nom;
  if ($name) {
    return $name;
  }
  else {
    return "Medicament n°$id inexistant";
  }
}

function getGrade($grade) {
  $name = Grade::getGrade($grade)->nom;
  if ($name) {
    return $name;
  }
  else {
    return "Grade n°$grade inexistant";
  }
}

function getEtat($etat) {
  switch ($etat) {
    case 0:
      return "Non";
      break;
    case 1:
      return "Formation";
      break;
    case 2:
      return "Validé";
      break;
    default:
      return "Erreur dans le décryptage de la solution";
      break;
  }
}

function addHistorique($matricule, $message) {
  $vehicule = Model::factory('Historique_EMS')->create();
  $vehicule->set(array(
                'matricule' => $matricule,
                'contenu' => $message,
                'date_even' => date("Y-m-d H:i:s")
              )
            );
  $vehicule->save();
}

/* Longue Fonction Ajout Historique */
function AddHistoriqueModifAgent($id_ems, $matricule_ems, $grade, $hab1, $hab2, $hab3, $hab4, $hab5, $hab6, $hab7, $hab8, $hab9, $hab10, $hab11, $hab12, $hab13, $hab14, $hab15, $note) {

  $agent = Agent::getInfoAgent();

  if ($id_ems->grade_id != $grade) {
    addHistorique($agent->matricule, "2¤1¤1¤" . $matricule_ems . "¤" . $id_ems->grade_id . "¤" . $grade);
  }

  if (serveurIni('HABILITATION', 'hab_1') != "") {
    if ($id_ems->hab_1 != $hab1) {
      addHistorique($agent->matricule, "2¤1¤2¤" . $matricule_ems . "¤" . $id_ems->hab_1 . "¤" . $hab1);
    }
  }

  if (serveurIni('HABILITATION', 'hab_2') != "") {
    if ($id_ems->hab_2 != $hab2) {
      addHistorique($agent->matricule, "2¤1¤3¤" . $matricule_ems . "¤" . $id_ems->hab_2 . "¤" . $hab2);
    }
  }

  if (serveurIni('HABILITATION', 'hab_3') != "") {
    if ($id_ems->hab_3 != $hab3) {
      addHistorique($agent->matricule, "2¤1¤4¤" . $matricule_ems . "¤" . $id_ems->hab_3 . "¤" . $hab3);
    }
  }

  if (serveurIni('HABILITATION', 'hab_4') != "") {
    if ($id_ems->hab_4 != $hab4) {
      addHistorique($agent->matricule, "2¤1¤5¤" . $matricule_ems . "¤" . $id_ems->hab_4 . "¤" . $hab4);
    }
  }

  if (serveurIni('HABILITATION', 'hab_5') != "") {
    if ($id_ems->hab_5 != $hab5) {
      addHistorique($agent->matricule, "2¤1¤6¤" . $matricule_ems . "¤" . $id_ems->hab_5 . "¤" . $hab5);
    }
  }

  if (serveurIni('HABILITATION', 'hab_6') != "") {
    if ($id_ems->hab_6 != $hab6) {
      addHistorique($agent->matricule, "2¤1¤7¤" . $matricule_ems . "¤" . $id_ems->hab_6 . "¤" . $hab6);
    }
  }

  if (serveurIni('HABILITATION', 'hab_7') != "") {
    if ($id_ems->hab_7 != $hab7) {
      addHistorique($agent->matricule, "2¤1¤8¤" . $matricule_ems . "¤" . $id_ems->hab_7 . "¤" . $hab7);
    }
  }

  if (serveurIni('HABILITATION', 'hab_8') != "") {
    if ($id_ems->hab_8 != $hab8) {
      addHistorique($agent->matricule, "2¤1¤9¤" . $matricule_ems . "¤" . $id_ems->hab_8 . "¤" . $hab8);
    }
  }

  if (serveurIni('HABILITATION', 'hab_9') != "") {
    if ($id_ems->hab_9 != $hab9) {
      addHistorique($agent->matricule, "2¤1¤10¤" . $matricule_ems . "¤" . $id_ems->hab_9 . "¤" . $hab9);
    }
  }

  if (serveurIni('HABILITATION', 'hab_10') != "") {
    if ($id_ems->hab_10 != $hab10) {
      addHistorique($agent->matricule, "2¤1¤11¤" . $matricule_ems . "¤" . $id_ems->hab_10 . "¤" . $hab10);
    }
  }

  if (serveurIni('HABILITATION', 'hab_11') != "") {
    if ($id_ems->hab_11 != $hab11) {
      addHistorique($agent->matricule, "2¤1¤12¤" . $matricule_ems . "¤" . $id_ems->hab_11 . "¤" . $hab11);
    }
  }

  if (serveurIni('HABILITATION', 'hab_12') != "") {
    if ($id_ems->hab_12 != $hab12) {
      addHistorique($agent->matricule, "2¤1¤13¤" . $matricule_ems . "¤" . $id_ems->hab_12 . "¤" . $hab12);
    }
  }

  if (serveurIni('HABILITATION', 'hab_13') != "") {
    if ($id_ems->hab_13 != $hab13) {
      addHistorique($agent->matricule, "2¤1¤14¤" . $matricule_ems . "¤" . $id_ems->hab_13 . "¤" . $hab13);
    }
  }

  if (serveurIni('HABILITATION', 'hab_14') != "") {
    if ($id_ems->hab_14 != $hab14) {
      addHistorique($agent->matricule, "2¤1¤15¤" . $matricule_ems . "¤" . $id_ems->hab_14 . "¤" . $hab14);
    }
  }

  if (serveurIni('HABILITATION', 'hab_15') != "") {
    if ($id_ems->hab_15 != $hab15) {
      addHistorique($agent->matricule, "2¤1¤16¤" . $matricule_ems . "¤" . $id_ems->hab_15 . "¤" . $hab15);
    }
  }

  if ($id_ems->note != $note) {
    addHistorique($agent->matricule, "2¤1¤0¤" . $matricule_ems . "¤" . $id_ems->note . "¤" . $note);
  }
}


?>
