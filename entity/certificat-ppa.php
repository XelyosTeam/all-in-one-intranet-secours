<?php
/*
  Le projet All in One est un produit Xelyos mis à disposition gratuitement
  pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
  ne pas supprimer le ou les auteurs du projet.
  Created by : Xelyos - Aros
  Edited by :
*/
use Josantonius\Session\Session; // Pour utiliser les variables de sessions

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
?>
