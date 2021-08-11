<?php
/*
  Le projet All in One est un produit Xelyos mis à disposition gratuitement
  pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
  ne pas supprimer le ou les auteurs du projet.
  Created by : Xelyos - Aros
  Edited by :
*/
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
  
  public static function getInfoAgentIdEms($id) { // Informations d'un agent de police avec l'ID

    return Agent::where('ems_id', $id)->find_one();
  }
  public static function getInfoAgentIDUser($id) { // Informations d'un agent de police avec l'ID
    return Agent::where('user_id', $id)->find_one();
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
?>
