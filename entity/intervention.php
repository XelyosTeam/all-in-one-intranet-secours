<?php
/*
  Le projet All in One est un produit Xelyos mis à disposition gratuitement
  pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
  ne pas supprimer le ou les auteurs du projet.
  Created by : Xelyos - Aros
  Edited by :
*/
use Josantonius\Session\Session; // Pour utiliser les variables de sessions

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
?>
