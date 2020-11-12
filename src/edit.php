<?php
  function editCivil($id_civil, $sang, $donneur) {
    $civil = Model::factory('Personne')->where('id', $id_civil)->find_one();
    $civil->set(array('grp_sanguin' => $sang, 'donneur' => $donneur));
    $civil->save();
  }

  function editEMS($id, $grade, $hab1, $hab2, $hab3, $hab4, $hab5, $hab6, $hab7, $hab8, $hab9, $hab10, $hab11, $hab12, $hab13, $hab14, $hab15, $note) {
    /* Modification du grade du grade */
    $rang = Model::factory('EMS_t')->where('id', $id)->find_one();
    $rang->set(array(
          'rang' => $grade,
          'note' => $note
        ));
    $rang->save();

    /* Upgrade des habilitations */
    $habilitation = Model::factory('Habilitation')->where('matricule', $id)->find_one();
    $habilitation->set(array(
              'hab_1' => $hab1,
              'hab_2' => $hab2,
              'hab_3' => $hab3,
              'hab_4' => $hab4,
              'hab_5' => $hab5,
              'hab_6' => $hab6,
              'hab_7' => $hab7,
              'hab_8' => $hab8,
              'hab_9' => $hab9,
              'hab_10' => $hab10,
              'hab_11' => $hab11,
              'hab_12' => $hab12,
              'hab_13' => $hab13,
              'hab_14' => $hab14,
              'hab_15' => $hab15,
            ));
    $habilitation->save();
  }

  function editDescMed($id, $description) {
    $civil = Model::factory('Medicament_Liste')->where('id', $id)->find_one();
    $civil->set('description', $description);
    $civil->save();
  }

  function editLicenciement($id) {
    /* Bloquage et suppression du grade */
    $bye2 = Model::factory('EMS_t')->where('id', $id)->find_one();
    $bye2->set('rang', 1);
    $bye2->save();

    /* Déclaration de l'individu comme sans emploi */
    $id_civil = Agent::getInfoAgentIDUser($id);
    $bye3 = Model::factory('Personne')->where('id', $id_civil->user_id)->find_one();
    $bye3->set('job', serveurIni('Par_defaut', 'emploi'));
    $bye3->save();
  }

  function mise_a_jour_mdp($matricule, $new_mdp_hashed, $salt) {
    $nex_mdp = Model::factory('EMS_t')->where('matricule', $matricule)->find_one();
    $nex_mdp->set(array(
              'Passwd' => $new_mdp_hashed,
              'Sel_de_table' => $salt
            ));
    $nex_mdp->save();
  }

  function updateProf($id_ems)
  {
    $habilitation = Model::factory('Habilitation')->where('matricule', $id_ems)->find_one();
    $habilitation->set('hab_1', 3);
    $habilitation->save();
  }
?>
