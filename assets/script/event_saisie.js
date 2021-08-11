/*
  Le projet All in One est un produit Xelyos mis à disposition gratuitement
  pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
  ne pas supprimer le ou les auteurs du projet.
  Created by : Xelyos - Aros
  Edited by :
*/

/* Mise en forme des données à envoyer*/
function createData(var_tab, var_tab2) {
  const data = new FormData();
  for (var i = 0; i < var_tab.length; i++) {
    let content = document.getElementById(var_tab2[i]).value;
    data.append(var_tab[i], content);
  }

  return data;
}

/* On affiche la photo de la personne en fonction de son id */
function affiche_photo_personne() {
  let civil_id = document.getElementById("personne_type").value;

  // On met en forme les données
  const data = new FormData();
  data.append('id', civil_id);

  // On envoi le modèle
  const requeteAjax = new XMLHttpRequest();
  requeteAjax.open('POST', '/recherche/photo/personne');
  requeteAjax.send(data);

  // On récupère le résultat de la requête
  requeteAjax.onload = function(){
    const resultat = JSON.parse(requeteAjax.responseText);
    document.getElementById("image_civil").setAttribute('src', 'https://intranet-lspd.xelyos.fr/assets/img/identite/' + resultat.photo);
    document.getElementById("image_civil").setAttribute('alt', 'Photo ' + resultat.nom + ' ' + resultat.prenom);
  }
}

/* On affiche la photo d'un agent */
function affiche_photo_ems() {
  let cop_id = document.getElementById("ems_type").value;

  // On met en forme les données
  const data = new FormData();
  data.append('id', cop_id);

  // On envoi le modèle
  const requeteAjax = new XMLHttpRequest();
  requeteAjax.open('POST', '/recherche/photo/ems');
  requeteAjax.send(data);

  // On récupère le résultat de la requête
  requeteAjax.onload = function(){
    const resultat = JSON.parse(requeteAjax.responseText);
    document.getElementById("image_ems").setAttribute('src', 'https://intranet-lspd.xelyos.fr/assets/img/identite/' + resultat.photo);
    document.getElementById("image_ems").setAttribute('alt', 'Photo ' + resultat.grade + ' ' + resultat.nom);
  }
}

/* On affiche la photo d'un agent */
function affiche_photo_ems_2() {
  let cop_id = document.getElementById("ems_type_2").value;

  // On met en forme les données
  const data = new FormData();
  data.append('id', cop_id);

  // On envoi le modèle
  const requeteAjax = new XMLHttpRequest();
  requeteAjax.open('POST', '/recherche/photo/ems');
  requeteAjax.send(data);

  // On récupère le résultat de la requête
  requeteAjax.onload = function(){
    const resultat = JSON.parse(requeteAjax.responseText);
    document.getElementById("image_ems2").setAttribute('src', 'https://intranet-lspd.xelyos.fr/assets/img/identite/' + resultat.photo);
    document.getElementById("image_ems2").setAttribute('alt', 'Photo ' + resultat.grade + ' ' + resultat.nom);
  }
}

/* Ajout interventions liste*/
function addInterventionList() {
  event.preventDefault(); // Arret du formulaire

  let inter_name = document.getElementById("name").value;

  const data = new FormData();
  data.append('inter_name', inter_name);

  // On envoi le modèle
  const requeteAjax = new XMLHttpRequest();
  requeteAjax.open('POST', '/insert/intervention-type');
  requeteAjax.send(data);

  document.getElementById("confirm_inter").style.display = "block";
  document.getElementById("name").value = "";
}

/* Ajout Médicaments liste*/
function addMedicamentList() {
  event.preventDefault(); // Arret du formulaire

  let med_name = document.getElementById("med_name").value;
  let med_descript = document.getElementById("med_descript").value;

  if ((med_name == '') || (med_descript == '')) {
    document.getElementById("confirm_med").innerHTML = "Saisie incomplète";
    document.getElementById("confirm_med").style.display = "block";
  }
  else {
    const data = new FormData();
    data.append('name', med_name);
    data.append('description', med_descript);

    // On envoi le modèle
    const requeteAjax = new XMLHttpRequest();
    requeteAjax.open('POST', '/insert/medicaments');
    requeteAjax.send(data);

    document.getElementById("confirm_med").innerHTML = "Médicament ajouté";
    document.getElementById("confirm_med").style.display = "block";
    document.getElementById("med_name").value = "";
    document.getElementById("med_descript").value = "";
  }

}

/* Récupérer description médicaments */
function changeDescriptionMedicament() {
  let med_id = document.getElementById("med_id").value;

  // On met en forme les données
  const data = new FormData();
  data.append('med_id', med_id);

  // On envoi le modèle
  const requeteAjax = new XMLHttpRequest();
  requeteAjax.open('POST', '/medicament/get_info');
  requeteAjax.send(data);

  // On récupère le résultat de la requête
  requeteAjax.onload = function(){
    const resultat = JSON.parse(requeteAjax.responseText);
    document.getElementById("med_descript").setAttribute('placeholder', resultat.info);
  }
}

/* Modifier Description médicaments */
function editDescriptionMed() {
  event.preventDefault(); // Arret du formulaire

  let var_tab = ['id', 'dscpt'];
  let var_tab2 = ['med_id', 'med_descript'];
  const data = createData(var_tab, var_tab2);

  // On envoi le modèle
  const requeteAjax = new XMLHttpRequest();
  requeteAjax.open('POST', '/medicament/edit/desc');
  requeteAjax.send(data);

  document.getElementById("confirm_med").style.display = "block";
  document.getElementById("med_descript").value = "";
  changeDescriptionMedicament();
}

/* Modification Serveur Param */
function EditServParam() {
  event.preventDefault(); // Arret du formulaire

  let value_tab = [
    "failed_connexion",
    "etat_recrut"
  ];

  const data = createData(value_tab, value_tab);

  // On envoi le modèle
  const requeteAjax = new XMLHttpRequest();
  requeteAjax.open('POST', '/administration/parametre-serveur/modification');
  requeteAjax.send(data);
  document.getElementById("confirm_admin_modifserv").style.display = "block";
}

/* Aficher la liste box suivante */
function afficheNext(actuel, contentValue, suivant) {
  var act = document.getElementById(actuel);
  var actValue = document.getElementById(contentValue);
  if (act && actValue) {
    if (actValue.value) {
      var next = document.getElementById(suivant);
      if (next) {
        next.style.display = "block";
      }
    }
  }
}

/* Ajouter intervention */
function AddIntervention() {
  event.preventDefault(); // Arret du formulaire

  cpt = 0;

  for (var i = 0; i <= 50; i++) {
    let element = document.getElementById(`saisie_intervention_${i}`);
    if (element) {
      let content = document.getElementById(`intervention_value_${i}`);
      if (content.value != 'NULL') {
        // On ajoute le délit
        let entete = ['inter_name', 'rapport', 'id_civil'];
        let contentEntete = [
          `intervention_value_${i}`, 'intervention_rapport',
          'personne_type',
        ];
        let data = createData(entete, contentEntete);
        cpt += 1;

        // On envoi le modèle
        var requeteAjax = new XMLHttpRequest();
        requeteAjax.open('POST', '/insert/intervention');
        requeteAjax.send(data);
      }
    }
  }

  if (cpt > 0) {
    if (cpt == 1) {
      alert("L'intervention a été ajoutée");
    }
    else {
      alert(`Les ${cpt} interventions ont été ajoutées !`);
    }

    window.location.replace(`/civil/${document.getElementById('personne_type').value}`);
  }
}
