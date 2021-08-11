<?php
/*
  Le projet All in One est un produit Xelyos mis à disposition gratuitement
  pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
  ne pas supprimer le ou les auteurs du projet.
  Created by : Xelyos - Aros
  Edited by :
*/
use Josantonius\Session\Session; // Pour utiliser les variables de sessions

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
?>
