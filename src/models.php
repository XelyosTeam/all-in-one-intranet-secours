<?php
  use Josantonius\Session\Session; // Pour utiliser les variables de sessions

  class Agent extends Model {
    public static $_table = 'info_ems'; // Liaison avec la table

    /* Récupération des valeurs dans les tables */
    public static function getInfoAgent() { // Informations d'un EMS avec le matricule
      return Agent::where('matricule', Session::get('matricule_ems'))->find_one();
    }
    public static function getInfoAgentMatricule($matricule) { // Informations d'un agent de police avec le matricule
      return Agent::where('matricule', $matricule)->find_one();
    }
    public static function getInfoAgentIDUser($id) { // Informations d'un agent de police avec l'ID
      return Agent::where('ems_id', $id)->find_one();
    }
    public static function getListAgent() {
      return Agent::where_not_equal('grade_id', 1)
                  ->order_by_asc(array('nom', 'prenom'))
                  ->find_many();
    }
    public static function getListAgentTri($nom, $prenom, $matricule) {
      return Agent::where_like(array(
                       'nom' => "%$nom%",
                       'prenom' => "%$prenom%",
                       'matricule' => "%$matricule%"
                     ))
                     ->where_not_equal(array('grade_id' => 1, 'ems_id' => serveurIni('Par_defaut', 'id_ems')))
                     ->order_by_asc(array('nom', 'prenom'))
                     ->find_many();
    }
    public static function getListOldAgent() {
      return Agent::where_like('grade_id', 1)
                  ->where_not_equal('ems_id', serveurIni('Par_defaut', 'id_ems'))
                  ->order_by_asc(array('nom', 'prenom'))
                  ->find_many();
    }
    public static function getListNonProf() {
      return Agent::where_not_equal(array('grade_id' => 1, 'hab_1' => 3))
                  ->find_many();
    }
  }

  class Arret extends Model {
    public static $_table = 'ems_arret_travail'; // Liaison avec la table

    /* Récupération des valeurs dans les tables */
    public static function getListArret($id) {
      return Arret::where('personne', $id)
                  ->order_by_desc('fin_le')
                  ->find_many();
    }
    public static function getArret($id) {
      return Arret::where('id', $id)
                  ->find_one();
    }
    public static function getIDArret($id, $matricule) { // Récuparatoin de l'ID d'une personne enfonction de son nom et prénom
      return Arret::where(array('personne' => $id, 'enregistrer_par' => $matricule))->order_by_desc('id')->find_one();
    }
    public static function getNbArret($id_ems) {
      return Arret::where('enregistrer_par', $id_ems)
                  ->count();
    }
  }

  class Certificat extends Model {
    public static $_table = 'ems_certificat_travail'; // Liaison avec la table

    /* Récupération des valeurs dans les tables */
    public static function getListCertificat($id) {
      return Certificat::where('personne', $id)
                  ->find_many();
    }
    public static function getCertificat($id) {
      return Certificat::where('id', $id)
                  ->find_one();
    }
    public static function getIDCertificat($id, $matricule) { // Récuparatoin de l'ID d'une personne enfonction de son nom et prénom
      return Certificat::where(array('personne' => $id, 'enregistrer_par' => $matricule))->order_by_desc('id')->find_one();
    }
    public static function getNbCertificat($id_ems) {
      return Certificat::where('enregistrer_par', $id_ems)
                       ->count();
    }
  }

  class Chat extends Model {
    public static $_table = 'chat'; // Liaison avec la table

    /* Récupération des valeurs dans les tables */
    public static function getList() { // On récupère la liste du casier judiciaire avec l'ID de la personne
      return Chat::order_by_desc('send_time')->limit(25)->find_many();
    }
  }

  class EMS_t extends Model {
    public static $_table = 'ems_hopital'; // Liaison avec la table

    /* Récupération des valeurs dans les tables */
    public static function getInfoAgentMatricule($matricule) {
      return EMS_t::where('matricule', $matricule)->find_one();
    }
  }

  class Grade extends Model {
    public static $_table = 'ems_grade'; // Liaison avec la table

    /* Récupération des valeurs dans les tables */
    public static function getList() { // On récupère la liste du casier judiciaire avec l'ID de la personne
      return Grade::order_by_desc('position')->find_many();
    }
    public static function getGrade($id) {
      return Grade::where('id', $id)->find_one();
    }
  }

  class Habilitation extends Model {
    public static $_table = 'ems_habilitation'; // Liaison avec la table
  }

  class Historique extends Model {
    public static $_table = 'ems_historique_connexion'; // Liaison avec la table

    /* Récupération des valeurs dans les tables */
    public static function getEchec() {
      return Historique::select_many('adresse_ip')
                       ->select_expr('COUNT(*)', 'NbEchecs')
                       ->where('etat', 'echec')
                       ->group_by('adresse_ip')
                       ->find_many();
    }
    public static function getNbEchec($ip) {
      return Historique::where(array('etat' => 'echec', 'adresse_ip' => $ip))
                       ->count();
    }
    public static function getEchecAdresse($ip) {
      return Historique::where(array('etat' => 'echec', 'adresse_ip' => $ip))
                       ->find_many();
    }
    public static function getNbConnect($matricule) {
      return Historique::where(array('etat' => 'Réussite', 'matricule_utilise' =>$matricule))
                       ->count();
    }
    public static function getListMatricule($matricule) {
      return Historique::where('matricule_utilise', $matricule)
                       ->order_by_desc('id')
                       ->find_many();
    }
  }

  class Historique_EMS extends Model {
    public static $_table = 'ems_historique'; // Liaison avec la table

    /* Récupération des valeurs dans les tables */
    public static function getAction($matricule) {
      return Historique_EMS::where('matricule', $matricule)
                            ->order_by_desc('id')
                            ->find_many();
    }
    public static function getNbAction($matricule) {
      return Historique_EMS::where('matricule', $matricule)
                           ->count();
    }
  }

  class Intervention extends Model {
    public static $_table = 'info_intervention'; // Liaison avec la table

    /* Récupération des valeurs dans les tables */
    public static function getListIntervention($id) {
      return Intervention::where('id_civil', $id)
                         ->order_by_desc('enregistrer_le')
                         ->find_many();
    }
    public static function getIDIntervention($id, $matricule) { // Récuparatoin de l'ID d'une personne enfonction de son nom et prénom
      return Intervention::where(array('id_civil' => $id, 'enregistre_par' => $matricule))->order_by_desc('inter_id')->find_one();
    }
    public static function getIntervention($id) {
      return Intervention::where('inter_id', $id)
                         ->find_one();
    }
  }

  class Intervention_t extends Model {
    public static $_table = 'ems_intervention'; // Liaison avec la table
  }

  class InterventionList extends Model {
    public static $_table = 'ems_liste_intervention'; // Liaison avec la table

    /* Récupération des valeurs dans les tables */
    public static function getList() { // On récupère la liste du casier judiciaire avec l'ID de la personne
      return InterventionList::order_by_asc('intitule')->find_many();
    }
    public static function getInterID($name) {
      return InterventionList::where('intitule', $name)->find_one();
    }
  }

  class Voiture extends Model {
    public static $_table = "info_voiture"; // Liaison avec la table

    /* Récupération des valeurs dans les tables */
    public static function getListCarEMS($personne) { // Liste des voitures d'une personne
      return Voiture::where(array('proprio' => $personne, 'couleur' => serveurIni('Faction', 'couleurVehiculeBDD')))
                    ->order_by_asc('plaque')
                    ->find_many();
    }
  }

  class PPA extends Model {
    public static $_table = 'ems_certificat_ppa'; // Liaison avec la table

    /* Récupération des valeurs dans les tables */
    public static function getListCertificat($id) {
      return PPA::where('personne', $id)
                  ->find_many();
    }
    public static function getCertificat($id) {
      return PPA::where('id', $id)
                  ->find_one();
    }
    public static function getIDPPA($id, $matricule) { // Récuparatoin de l'ID d'une personne enfonction de son nom et prénom
      return PPA::where(array('personne' => $id, 'enregistrer_par' => $matricule))->order_by_desc('id')->find_one();
    }
    public static function getPPA($id_ems) {
      return PPA::where('enregistrer_par', $id_ems)
                ->count();
    }
  }

  class Medicament_Liste extends Model {
    public static $_table = 'ems_liste_medicament'; // Liaison avec la table

    /* Récupération des valeurs dans les tables */
    public static function getList() { // On récupère la liste du casier judiciaire avec l'ID de la personne
      return Medicament_Liste::order_by_asc('nom')->find_many();
    }
    public static function getInfo($id) { // On récupère la liste du casier judiciaire avec l'ID de la personne
      return Medicament_Liste::where('id', $id)->find_one();
    }
  }

  class Medicament_Ordonnance extends Model {
    public static $_table = 'ems_details_ordonnance'; // Liaison avec la table

    /* Récupération des valeurs dans les tables */
  }

  class Ordonnance extends Model {
    public static $_table = 'ems_ordonnance'; // Liaison avec la table

    /* Récupération des valeurs dans les tables */
    public static function getLastOrdonnance($id, $matricule, $date) { // On récupère la liste du casier judiciaire avec l'ID de la personne
      return Ordonnance::where(array(
                                'patient' => $id,
                                'enregistrer_par' => $matricule,
                                'enregistrer_le' => $date
                             ))
                        ->limit(2)
                        ->order_by_desc('id')
                        ->find_one();
    }
    public static function getListID($id) { // On récupère la liste du casier judiciaire avec l'ID de la personne
      return Ordonnance::where('patient', $id)
                       ->order_by_desc('enregistrer_le')
                       ->find_many();
    }
    public static function getInfo($id) { // On récupère la liste du casier judiciaire avec l'ID de la personne
      return Ordonnance::where('id', $id)
                       ->find_one();
    }
  }

  class Info_Ordonnance extends Model {
    public static $_table = 'info_ordonnance'; // Liaison avec la table

    /* Récupération des valeurs dans les tables */
    public static function getList($id) {
      return Info_Ordonnance::where('ordonnance_id', $id)
                            ->order_by_asc('nom_med')
                            ->find_many();
    }

  }

  class Personne extends Model {
    public static $_table = 'personnes'; // Liaison avec la table

    /* Récupération des valeurs dans les tables */
    public static function getinfoPersonne($id_user) { // Information d'une personne
      return Personne::where('id', $id_user)->find_one();
    }
    public static function getListPersonne() {
      return Personne::where('present', 2)
                     ->order_by_asc(array('nom', 'prenom'))
                     ->find_many();
    }
    public static function getListPersonneTri($nom, $prenom, $phone) {
      return Personne::where('present', 2)
                     ->where_like(array(
                       'nom' => "%$nom%",
                       'prenom' => "%$prenom%",
                       'phone' => "%$phone%"
                     ))
                     ->order_by_asc(array('nom', 'prenom'))
                     ->find_many();
    }
    public static function getPPA() {
      return Personne::where(array(
                       'ppa' => 1,
                       'present' => 2
                      ))
                      ->order_by_asc(array('nom', 'prenom'))
                      ->find_many();
    }
    public static function getSansEmploi() {
      return Personne::where(array('present' => 2, 'job' => serveurIni('Par_defaut', 'emploi')))
                     ->order_by_asc(array('nom', 'prenom'))
                     ->find_many();
    }
    public static function OldEMS() {
      return Personne::raw_query('SELECT * FROM personnes WHERE id NOT IN (SELECT user_id FROM info_ems) AND id <> :lspd_id AND job = :job  ORDER BY nom, prenom;', array('lspd_id' => serveurIni('Par_defaut', 'id_ems'), 'job' => serveurIni('Par_defaut', 'emploi')))
                     ->find_many();
    }
  }
?>
