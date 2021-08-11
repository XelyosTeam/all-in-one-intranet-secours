<?php
/*
  Le projet All in One est un produit Xelyos mis à disposition gratuitement
  pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
  ne pas supprimer le ou les auteurs du projet.
  Created by : Xelyos - Aros
  Edited by :
*/
use Josantonius\Session\Session; // Pour utiliser les variables de sessions

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
?>
