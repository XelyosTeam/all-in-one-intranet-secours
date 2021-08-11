<?php
/*
  Le projet All in One est un produit Xelyos mis à disposition gratuitement
  pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
  ne pas supprimer le ou les auteurs du projet.
  Created by : Xelyos - Aros
  Edited by :
*/
use Josantonius\Session\Session; // Pour utiliser les variables de sessions

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
?>
