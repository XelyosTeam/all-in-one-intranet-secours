<?php
/*
  Le projet All in One est un produit Xelyos mis à disposition gratuitement
  pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
  ne pas supprimer le ou les auteurs du projet.
  Created by : Xelyos - Aros
  Edited by :
*/
use Josantonius\Session\Session; // Pour utiliser les variables de sessions

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
?>
