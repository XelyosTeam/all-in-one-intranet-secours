<?php
/*
  Le projet All in One est un produit Xelyos mis à disposition gratuitement
  pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
  ne pas supprimer le ou les auteurs du projet.
  Created by : Xelyos - Aros
  Edited by :
*/
use Josantonius\Session\Session; // Pour utiliser les variables de sessions

class Info_Ordonnance extends Model {
  public static $_table = 'info_ordonnance'; // Liaison avec la table

  /* Récupération des valeurs dans les tables */
  public static function getList($id) {
    return Info_Ordonnance::where('ordonnance_id', $id)
                          ->order_by_asc('nom_med')
                          ->find_many();
  }

}
?>
