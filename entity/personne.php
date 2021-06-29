<?php
/*
  Le projet All in One est un produit Xelyos mis à disposition gratuitement
  pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
  ne pas supprimer le ou les auteurs du projet.
  Created by : Xelyos - Aros
  Edited by :
*/
use Josantonius\Session\Session; // Pour utiliser les variables de sessions

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
  public static function OldEms() {
    return Personne::raw_query('SELECT * FROM personnes WHERE id NOT IN (SELECT user_id FROM info_ems) AND id <> :ems_id AND job = :job  ORDER BY nom, prenom;', array('ems_id' => serveurIni('Par_defaut', 'id_ems'), 'job' => serveurIni('Par_defaut', 'emploi')))
                   ->find_many();
  }
}
?>
