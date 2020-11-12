function affiche_suite(id) {
  if (document.getElementById("liste_" + id).value != "0") {
    getDescriptionMedicament(document.getElementById("liste_" + id).value, id);
    document.getElementById("content_" + id).style.display = "block";
    document.getElementById("desc_" + id).style.display = "block";

    if (!document.getElementById("medicament_" + (id + 1))) {
      creationNext(id);
    }
  }
  else {
    document.getElementById("content_" + id).style.display = "none";
    document.getElementById("desc_" + id).style.display = "none";
  }
}

function getDescriptionMedicament(medId, id) {
  // On met en forme les données
  const data = new FormData();
  data.append('med_id', medId);

  // On envoi le modèle
  const requeteAjax = new XMLHttpRequest();
  requeteAjax.open('POST', '/medicament/get_info');
  requeteAjax.send(data);

  // On récupère le résultat de la requête
  requeteAjax.onload = function(){
    const resultat = JSON.parse(requeteAjax.responseText);
    document.getElementById("desc_" + id).innerHTML = resultat.info;
  }
}

function initialisationContent(id) {
  document.getElementById("content_" + id).style.display = "none";
  document.getElementById("desc_" + id).style.display = "none";
}

function creationNext(id) {
  // Création de la section
  var sect = document.createElement("section");
  sect.setAttribute("id", "medicament_" + (id + 1));
  sect.setAttribute("class", "ordonnance");
  var text = document.createTextNode("");
  sect.appendChild(text);
  var element = document.getElementById("Liste_medicament");
  element.appendChild(sect);

  // Div part_left
  var div = document.createElement("div");
  div.setAttribute("id", "nom_med_" + (id + 1));
  div.setAttribute("class", "left_part");
  div.appendChild(text);
  var element = document.getElementById("medicament_" + (id + 1));
  element.appendChild(div);

  // Label nom médicament
  var label = document.createElement("label");
  text = document.createTextNode("Nom médicament");
  label.appendChild(text);
  var element = document.getElementById("nom_med_" + (id + 1));
  element.appendChild(label);

  var slct = document.createElement("select");
  slct.setAttribute("id", "liste_" + (id + 1));
  slct.setAttribute("onchange", `affiche_suite(${id + 1});`);
  element.appendChild(slct);

  // Add first info
  AddBasicInfo(id);

  // Add medicament
  AddMedicament(id);

  // Description medicament
  var para = document.createElement("p");
  para.setAttribute("id", "desc_" + (id + 1));
  text = document.createTextNode("");
  para.appendChild(text);
  var element = document.getElementById("nom_med_" + (id + 1));
  element.appendChild(para);

  // Div part_left
  var div2 = document.createElement("div");
  div2.setAttribute("id", "content_" + (id + 1));
  div2.setAttribute("class", "right_part");
  text = document.createTextNode("");
  div2.appendChild(text);
  var element = document.getElementById("medicament_" + (id + 1));
  element.appendChild(div2);

  // Div Quantité
  var div3 = document.createElement("div");
  div3.setAttribute("id", "qtn_" + (id + 1));
  text = document.createTextNode("");
  div3.appendChild(text);
  var element = document.getElementById("content_" + (id + 1));
  element.appendChild(div3);

  // Label Quantité
  var lbl2 = document.createElement("label");
  text = document.createTextNode("Quantité :");
  lbl2.appendChild(text);
  var element = document.getElementById("qtn_" + (id + 1));
  element.appendChild(lbl2);

  // Input Quantité
  var inpt = document.createElement("input");
  inpt.setAttribute("id", "qtn_value_" + (id + 1));
  inpt.setAttribute("type", "number");
  inpt.setAttribute("min", "0");
  inpt.setAttribute("max", "5");
  inpt.setAttribute("value", "1");
  inpt.setAttribute("placeholder", "Quantité");
  inpt.setAttribute("required", "");
  text = document.createTextNode("Quantité :");
  inpt.appendChild(text);
  var element = document.getElementById("qtn_" + (id + 1));
  element.appendChild(inpt);

  // Div Période
  var div4 = document.createElement("div");
  div4.setAttribute("id", "T_" + (id + 1));
  text = document.createTextNode("");
  div4.appendChild(text);
  var element = document.getElementById("content_" + (id + 1));
  element.appendChild(div4);

  // Label Période
  var lbl3 = document.createElement("label");
  text = document.createTextNode("Période :");
  lbl3.appendChild(text);
  var element = document.getElementById("T_" + (id + 1));
  element.appendChild(lbl3);

  // Input Période
  var inpt2 = document.createElement("input");
  inpt2.setAttribute("id", "T_value_" + (id + 1));
  inpt2.setAttribute("type", "number");
  inpt2.setAttribute("min", "0");
  inpt2.setAttribute("max", "21");
  inpt2.setAttribute("value", "1");
  inpt2.setAttribute("placeholder", "Périodes en jour");
  inpt2.setAttribute("required", "");
  text = document.createTextNode("Quantité :");
  inpt2.appendChild(text);
  var element = document.getElementById("T_" + (id + 1));
  element.appendChild(inpt2);

  // Div Fréquence
  var div5 = document.createElement("div");
  div5.setAttribute("id", "frq_" + (id + 1));
  text = document.createTextNode("");
  div5.appendChild(text);
  var element = document.getElementById("content_" + (id + 1));
  element.appendChild(div5);

  // Label Fréquence
  var lbl4 = document.createElement("label");
  text = document.createTextNode("Fréquences :");
  lbl4.appendChild(text);
  var element = document.getElementById("frq_" + (id + 1));
  element.appendChild(lbl4);

  // Input Fréquence
  var inpt3 = document.createElement("select");
  inpt3.setAttribute("id", "frq_value_" + (id + 1));
  text = document.createTextNode("");
  inpt3.appendChild(text);
  var element = document.getElementById("frq_" + (id + 1));
  element.appendChild(inpt3);

  // Insertion valeur
  let hz = ['Matin' , 'Midi', 'Soir', 'Matin/Soir', 'Matin/Midi', 'Midi/Soir', 'Matin/Midi/Soir'];

  for (var i = 0; i < hz.length; i++) {
    let opt2 = document.createElement("option");
    opt2.setAttribute("value", i+1);
    text = document.createTextNode(hz[i]);
    opt2.appendChild(text);
    var element = document.getElementById("frq_value_" + (id + 1));
    element.appendChild(opt2);
  }


  initialisationContent(id + 1);
}

function AddBasicInfo(id) {
  let basic_info = ['Sélection médicament', '============================'];
  for (var i = 0; i < basic_info.length; i++) {
    let opt = document.createElement("option");
    opt.setAttribute("value", "0");
    let text = document.createTextNode(basic_info[i]);
    opt.appendChild(text);
    let element = document.getElementById("liste_" + (id + 1));
    element.appendChild(opt);
  }
}

function AddMedicament(id) {
  const requeteAjax = new XMLHttpRequest();
  requeteAjax.open('GET', '/medicament/get_list');
  requeteAjax.send();

  requeteAjax.onload = function() {
    const resultat = JSON.parse(requeteAjax.responseText);
    let tab = [];
    for (var i = 0; i < resultat.length; i++) {
      let opt = document.createElement("option");
      opt.setAttribute("value", resultat[i].id);
      let text = document.createTextNode(resultat[i].name);
      opt.appendChild(text);
      let element = document.getElementById("liste_" + (id + 1));
      element.appendChild(opt);
    }
  }
}

function genererOrdonnance() {
  event.preventDefault(); // Arret du formulaire
  let id_civil = document.getElementById("personne_type").value;

  // On met en forme les données
  const data = new FormData();
  data.append('personne_type', id_civil);

  // On envoi le modèle
  const requeteAjax = new XMLHttpRequest();
  requeteAjax.open('POST', '/medicament/add/ordonnance');
  requeteAjax.send(data);

  // On récupère le résultat de la requête
  requeteAjax.onload = function(){
    const resultat = JSON.parse(requeteAjax.responseText);

    for (var i = 1; i < 50; i++) {
      if (document.getElementById("medicament_" + (i)) && (document.getElementById("medicament_" + (i)).value != "0")) {
        addMedicamentOrdonnance(resultat.n_ordonnance, i);
      }
    }

    document.location.href = "/ordonnance/" + resultat.n_ordonnance;
  }
}

function  addMedicamentOrdonnance(id, i) {
  const data = new FormData();
  data.append('id', id);
  data.append('nom', document.getElementById("liste_" + i).value);
  data.append('quantite', document.getElementById("qtn_value_" + i).value);
  data.append('periode', document.getElementById("T_value_" + i).value);
  data.append('hz', document.getElementById("frq_value_" + i).value);

  // On envoi le modèle
  const requeteAjax = new XMLHttpRequest();
  requeteAjax.open('POST', '/medicament/add/medicament');
  requeteAjax.send(data);
}
