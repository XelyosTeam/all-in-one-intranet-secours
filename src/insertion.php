<?php
  /*
    Le projet All in One est un produit Xelyos mis à disposition gratuitement
    pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
    ne pas supprimer le ou les auteurs du projet.
    Created by : Xelyos - Aros
    Edited by :
  */
  function addArret($civil, $duree, $matricule, $rapport) {
    //date de départ : 01 avril 2013
    $dateDepart = date("Y-m-d");
    //la première étape est de transformer cette date en timestamp
    $dateDepartTimestamp = strtotime($dateDepart);
    //on calcule la date de fin
    $dateFin = date('Y-m-d', strtotime('+'.$duree.'days', $dateDepartTimestamp ));

    $arret_travail = Model::factory('Arret')->create();
    $arret_travail->set(array(
                  'personne' => $civil,
                  'motif' => $rapport,
                  'enregistrer_par' => $matricule,
                  'enregistrer_le' => $dateDepart,
                  'fin_le' => $dateFin
                )
              );
    $arret_travail->save();
  }

  function addEMS($id) {
    do {
      $matricule_ems = rand(10, 99);
    }
    while(Agent::getInfoAgentMatricule($matricule_ems));

    $salt = bin2hex(random_bytes(15)); // Génération du sault
    $mdp_temp = program_crypt($matricule_ems, $salt);

    insertEMS($id, $matricule_ems, $salt, $mdp_temp); // Insertion dans la table ems
    update_metier($id, serveurIni('Faction', 'metierBDD')); // update du métier dans la table
    $info = EMS_t::getInfoAgentMatricule($matricule_ems); // Récupératoin de l'ID en fonction du matricule
    create_habilitation($info->id); // Ajout des habilitations

    return $matricule_ems;
  }

  function addCertificat($civil, $etat, $matricule, $metier, $rapport) {
    $certificat_travail = Model::factory('Certificat')->create();
    $certificat_travail->set(array(
                  'personne' => $civil,
                  'etat_job' => $etat,
                  'job_vise' => $metier,
                  'motif' => $rapport,
                  'enregistrer_par' => $matricule,
                  'enregistrer_le' => date("Y-m-d H:i:s")
                )
              );
    $certificat_travail->save();
  }

  function addIntervention($civil, $inter, $matricule, $rapport) {
    $intervention = Model::factory('Intervention_t')->create();
    $intervention->set(array(
                  'personne' => $civil,
                  'numero_intervention' => $inter,
                  'enregistrer_par' => $matricule,
                  'enregistrer_le' => date("Y-m-d H:i:s"),
                  'remarque' => $rapport
                )
              );
    $intervention->save();
  }

  function addInterventionList($nom) {
    $intervention = Model::factory('InterventionList')->create();
    $intervention->set('intitule', $nom);
    $intervention->save();
  }

  function addMedicaments($nom, $description) {
    $intervention = Model::factory('Medicament_Liste')->create();
    $intervention->set(array(
                  'nom' => $nom,
                  'description' => $description
                )
              );
    $intervention->save();
  }

  function addMedicamentOrdonnance($id, $nom, $quantite, $periode, $hz) {
    $Ordonnance = Model::factory('Medicament_Ordonnance')->create();
    $Ordonnance->set(array(
                  'n_ordonnance' => $id,
                  'nom_medicament' => $nom,
                  'quantite' => $quantite,
                  'periode' => $periode,
                  'frequence' => $hz,
                )
              );
    $Ordonnance->save();
  }

  function addOronnance($patient, $agent) {
    $date = date("Y-m-d");

    $Ordonnance = Model::factory('Ordonnance')->create();
    $Ordonnance->set(array(
                  'patient' => $patient,
                  'enregistrer_par' => $agent,
                  'enregistrer_le' => $date
                )
              );
    $Ordonnance->save();

    return $date;
  }

  function addPPA($civil, $etat, $motif, $matricule) {
    $certificat_travail = Model::factory('PPA')->create();
    $certificat_travail->set(array(
                  'personne' => $civil,
                  'etat_ppa' => $etat,
                  'rapport' => $motif,
                  'enregistrer_par' => $matricule,
                  'enregistrer_le' => date("Y-m-d H:i:s")
                )
              );
    $certificat_travail->save();
  }

  function create_habilitation($id) {
    $habilitation = Model::factory('Habilitation')->create();
    $habilitation->set('matricule', $id);
    $habilitation->save();
  }

  function insertEMS($id, $matricule_ems, $salt, $mdp_temp) {
    /* Insertion dans la BDD */
    $policier = Model::factory('EMS_t')->create();
    $policier->set(array(
                  'matricule' => $matricule_ems,
                  'Passwd' => $mdp_temp,
                  'Sel_de_table' => $salt,
                  'personne' => $id,
                  'rang' => '2'
                )
              );
    $policier->save();
  }

  function update_metier($id, $metier) {
    $change_job = Model::factory('Personne')->where('id', $id)->find_one();
    $change_job->set('job', $metier);
    $change_job->save();
  }
?>
