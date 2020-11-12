function get_connexion(matricule) {
  /* Mise en forme des données */
  const data = new FormData();
  data.append('matricule_ems', matricule);

  /* Envoi de la requêtes */
  const requeteAjax = new XMLHttpRequest();
  requeteAjax.open('POST', '/administration/historique_connexion/get_info');
  requeteAjax.send(data);

  /* On récupère les informations de la requêtes */
  requeteAjax.onload = function() {
    const resultat = JSON.parse(requeteAjax.responseText);

    if (resultat.etat == 0) {
      protocole_0_historique(resultat);
    }
    else {
      protocole_affichage_historique(resultat);
    }
  }
}

function protocole_0_historique(resultat) {
  // On indique le nom de la pesonne ou de l'agent
  document.getElementById("agent_nom3").innerHTML = "- " + resultat.nom;
  document.getElementById("contenu_historique2").innerHTML = `<p class="vide">Aucune action effectuée</p>`;
}

function protocole_affichage_historique(resultat) {
  document.getElementById("agent_nom3").innerHTML = "- " + resultat[(resultat.length-1)].etat;

  let content = [];

  for (var i = 0; i < resultat.length - 1; i++) {
    content[i] = `
    <div id="${i}">
      <p class="date">${resultat[i].date}</p>
      <p>${resultat[i].content}</p>
    </div>`
  }

  document.getElementById("contenu_historique2").innerHTML = content.join(' ');
}

function get_historique(matricule) {
  /* Mise en forme des données */
  const data = new FormData();
  data.append('matricule_ems', matricule);

  /* Envoi de la requêtes */
  const requeteAjax = new XMLHttpRequest();
  requeteAjax.open('POST', '/administration/historique/get_info');
  requeteAjax.send(data);

  /* On récupère les informations de la requêtes */
  requeteAjax.onload = function() {
    const resultat = JSON.parse(requeteAjax.responseText);

    if (resultat.etat == 0) {
      protocole_0(resultat);
    }
    else {
      protocole_affichage(resultat);
    }
  }
}

function protocole_0(resultat) {
  // On indique le nom de la pesonne ou de l'agent
  document.getElementById("agent_nom").innerHTML = "- " + resultat.nom;
  document.getElementById("agent_nom2").innerHTML = "- " + resultat.nom;
  document.getElementById("contenu_historique").innerHTML = `<p class="vide">Aucune action effectuée</p>`;
}

function protocole_affichage(resultat) {
  document.getElementById("agent_nom").innerHTML = "- " + resultat[(resultat.length-1)].etat;
  document.getElementById("agent_nom2").innerHTML = "- " + resultat[(resultat.length-1)].etat;

  let content = [];

  for (var i = 0; i < resultat.length - 1; i++) {
    content[i] = `
    <div id="${i}">
      <p class="date">${resultat[i].date}</p>
      <p>${resultat[i].event}</p>
    </div>`
  }

  document.getElementById("contenu_historique").innerHTML = content.join(' ');
}

function get_statistique(matricule) {
  /* Mise en forme des données */
  const data = new FormData();
  data.append('matricule_cop', matricule);

  /* Envoi de la requêtes */
  const requeteAjax = new XMLHttpRequest();
  requeteAjax.open('POST', '/administration/historique/get_stats');
  requeteAjax.send(data);

  /* On récupère les informations de la requêtes */
  requeteAjax.onload = function() {
    const resultat = JSON.parse(requeteAjax.responseText);
    if (resultat.etat == 1) {
      document.getElementById("stats_null").style.display = "none";
      document.getElementById("stat_full").style.display = "grid";
      document.getElementById("nb_connect").innerHTML = resultat.login;
      document.getElementById("nb_action").innerHTML = resultat.action;
      document.getElementById("nb_arret").innerHTML = resultat.arret;
      document.getElementById("nb_ppa").innerHTML = resultat.ppa;
      document.getElementById("nb_travail").innerHTML = resultat.travail;
    }
  }
}

function protocoleHistorique(matricule) {
  document.location.href="#agent_nom"; // Retour en haut de page
  get_historique(matricule);
  get_statistique(matricule);
  get_connexion(matricule);
}

// On masque la partie stats
document.getElementById("stat_full").style.display = "none";
