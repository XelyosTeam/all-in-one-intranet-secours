<?php
/*
  Le projet All in One est un produit Xelyos mis à disposition gratuitement
  pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
  ne pas supprimer le ou les auteurs du projet.
  Created by : Xelyos - Aros
  Edited by :
*/

use Josantonius\Session\Session; // Pour utiliser les variables de sessions

class Candidature extends Model {
  public static $_table = 'ems_candidature'; // Liaison avec la table

  /* Récupération des valeurs dans les tables */
  public static function getListCandidature($etat) { // On récupère la liste du casier judiciaire avec l'ID de la personne
    return Candidature::where('etat_act', $etat)
                      ->find_many();
  }

  public static function getCandidature($id_candid) { // On récupère la liste du casier judiciaire avec l'ID de la personne
    return Candidature::where('id', $id_candid)
                      ->find_one();
  }
}
?>
