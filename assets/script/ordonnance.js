/*
  Le projet All in One est un produit Xelyos mis à disposition gratuitement
  pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
  ne pas supprimer le ou les auteurs du projet.
  Created by : Xelyos - Aros
  Edited by :
*/

function affiche_suite(id) {
  if (document.getElementById("liste_" + id).value != "0") {
    getDescriptionMedicament(document.getElementById("liste_" + id).value, id);
    document.getElementById("content_" + id).style.display = "block";
    document.getElementById("desc_" + id).style.display = "block";

    let newelement = document.getElementById("medicament_" + (id + 1))
    if (newelement) {
      newelement.style.display = "grid";
    }
  }
  else {
    document.getElementById("content_" + id).style.display = "none";
    document.getElementById("desc_" + id).style.display = "none";
  }
}

function getDescriptionMedicament(medId, id) {
  // On met en forme les données
  var data = new FormData();
  data.append('med_id', medId);

  // On envoi le modèle
  var requeteAjax = new XMLHttpRequest();
  requeteAjax.open('POST', '/medicament/get_info');
  requeteAjax.send(data);

  // On récupère le résultat de la requête
  requeteAjax.onload = function(){
    var resultat = JSON.parse(requeteAjax.responseText);
    document.getElementById("desc_" + id).innerHTML = resultat.info;
  }
}

function initialisationContent(id) {
  document.getElementById("content_" + id).style.display = "none";
  document.getElementById("desc_" + id).style.display = "none";
}

function genererOrdonnance() {
  event.preventDefault(); // Arret du formulaire
  var id_civil = document.getElementById("personne_type").value;

  // On met en forme les données
  var data = new FormData();
  data.append('personne_type', id_civil);

  // On envoi le modèle
  var requeteAjax = new XMLHttpRequest();
  requeteAjax.open('POST', '/medicament/add/ordonnance');
  requeteAjax.send(data);

  // On récupère le résultat de la requête
  requeteAjax.onload = function(){
    var resultat = JSON.parse(requeteAjax.responseText);

    for (var i = 1; i <= 50; i++) {
      if (document.getElementById("medicament_" + (i)) && (document.getElementById("medicament_" + (i)).value != "0")) {
        addMedicamentOrdonnance(resultat.n_ordonnance, i);
      }
    }
    alert("Ordonnance créée"); // Confirme la création d'une ordonnance
    document.location.href = "/ordonnance/" + resultat.n_ordonnance;
  }
}

function  addMedicamentOrdonnance(id, i) {
  var data = new FormData();
  var nameid = document.getElementById("liste_" + i).value;
  data.append('id', id);
  data.append('nom', nameid);
  data.append('quantite', document.getElementById("qtn_value_" + i).value);
  data.append('periode', document.getElementById("T_value_" + i).value);
  data.append('hz', document.getElementById("frq_value_" + i).value);

  // On envoi le modèle
  if (nameid != "0") {
    var requeteAjax = new XMLHttpRequest();
    requeteAjax.open('POST', '/medicament/add/medicament');
    requeteAjax.send(data);
  }
}
