<?php
  use Josantonius\Session\Session;

  function deleteIP($ip) {
    $adresse = Model::factory('Historique')->where_equal(array('adresse_ip' => $ip, 'etat' => 'Echec'))->delete_many();
  }

  function verif_admin() {
    $info = Agent::getInfoAgentMatricule(Session::get('matricule_ems'));
    if ($info->admin != 1) {
      Flight::redirect("/");
      exit();
    }
  }

  function verif_enseignant() {
    $agent = Agent::getInfoAgent();
    if ($agent->hab_1 < 1) {
      Flight::redirect("/");
      exit();
    }
  }

  function verif_connecter() {
    /* Vérification que la personne soit bien connecté */
    if (Session::get('matricule_ems') == NULL) {
      Flight::redirect("/connexion");
      exit();
    }
    /* Vérification que la personne soit bien connecté */
  }

  /* Si on veut récupérer, il faut envoyer du JSON */
  function getMessages() {
    $messages = Chat::getList();
    foreach ($messages as $key => $msg) {
      $data[$key] = [
        'id' => $msg->id,
        'author' => $msg->author,
        'message'=>$msg->message,
        'side'=> $msg->side,
        'send_time'=> $msg->send_time
      ];
    }
    Flight::json($data);
  }

  /* Si on veut écrire au contraire, il faut analyser les paramètres envoyés en POST et les sauver dans la base de données */
  function postMessage() {
    $agent = Agent::getInfoAgent();
    $content = $_POST['content'];

    $chat = Model::factory('Chat')->create();
    $chat->set(array(
                  'author' => "$agent->nom " .  $agent->prenom[0] .".",
                  'message' => $content,
                  'side' => "ems",
                  'send_time' => date("Y-m-d H:i:s")
                )
              );
    $chat->save();

    // 3. Donner un statut de succes ou d'erreur au format JSON
    echo json_encode(["status" => "success"]);
    Flight::redirect("/discussion-interne");
  }
?>
