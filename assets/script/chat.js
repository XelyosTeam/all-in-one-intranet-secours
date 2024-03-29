/**
 * Codons un chat en HTML/CSS/Javascript avec nos amis PHP et MySQL
 */

/**
 * Il nous faut une fonction pour récupérer le JSON des messages et les afficher correctement
 */
function getMessages() {
  // 1. Elle doit créer une requête AJAX pour se connecter au serveur, et notamment au fichier handler.php
  const requeteAjax = new XMLHttpRequest();
  requeteAjax.open("GET", "/chat");

  // 2. Quand elle reçoit les données, il faut qu'elle les traite (en exploitant le JSON) et il faut qu'elle affiche ces données au format HTML
  requeteAjax.onload = function() {
    const resultat = JSON.parse(requeteAjax.responseText);
    const html = resultat.reverse().map(function(message) {
      return `
        <div class="message ${message.side}">
          <p><strong>${message.send_time.substring(11, 16)} - ${message.author} :</strong> ${message.message}</p>
        </div>
      `
    }).join('');

    const messages = document.querySelector('.message');

    messages.innerHTML = html;
    messages.scrollTop = messages.scrollHeight;
  }

  // 3. On envoie la requête
  requeteAjax.send();
}

/* Il nous faut une fonction pour envoyer le nouveau message au serveur et rafraichir les messages */

function postMessage(event) {
  // 1. Elle doit stoper le submit du formulaire
  event.preventDefault();

  // 2. Elle doit récupérer les données du formulaire
  const content = document.querySelector('#content');

  // 3. Elle doit conditionner les données
  const data = new FormData();
  data.append('content', content.value);

  // 4. Elle doit configurer une requête ajax en POST et envoyer les données
  const requeteAjax = new XMLHttpRequest();
  requeteAjax.open('POST', '/chat?task=write');

  requeteAjax.onload = function() {
    content.value = '';
    content.focus();
    getMessages();
  }

  requeteAjax.send(data);
}

document.querySelector('form').addEventListener('submit', postMessage);

/**
 * Il nous faut une intervale qui demande le rafraichissement
 * des messages toutes les 3 secondes et qui donne
 * l'illusion du temps réel.
 */
const interval = window.setInterval(getMessages, 3000);

getMessages();
