<?php
/*
  Le projet All in One est un produit Xelyos mis à disposition gratuitement
  pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
  ne pas supprimer le ou les auteurs du projet.
  Created by : Xelyos - Aros
  Edited by :
*/
use Josantonius\Session\Session; // Pour utiliser les variables de sessions

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
?>
