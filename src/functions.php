<?php
  /*
    Le projet All in One est un produit Xelyos mis à disposition gratuitement
    pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
    ne pas supprimer le ou les auteurs du projet.
    Created by : Xelyos - Aros
    Edited by :
  */
  use Josantonius\Session\Session;
  use Michelf\Markdown; // Utiliser pour la translation markdown html

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
      Flight::redirect("/connexion?url=" . urlencode(Flight::request()->url));
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
        'message'=> urldecode($msg->message),
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
                  'message' => urlencode($content),
                  'side' => "ems",
                  'send_time' => date("Y-m-d H:i:s")
                )
              );
    $chat->save();

    // 3. Donner un statut de succes ou d'erreur au format JSON
    echo json_encode(["status" => "success"]);
    Flight::redirect("/discussion-interne");
  }

  function renderHTMLFromMarkdown($string_markdown_formatted) {
    return Markdown::defaultTransform($string_markdown_formatted);
  }

  function getStructure($path, $name) {
    $file = file_get_contents($path . "/content/$name.json", TRUE);
    return json_decode($file);
  }

  function getFileContent($path, $file) {
    return file_get_contents($path . "/content/" . $file);
  }

  /* Ajout d'un fichier en lui intégrant un timestamp*/
function uploadFile($destination, $maxsize, $file, $extensionArray) {
  /* check Size */
  if ($file['size'] > $maxsize) {
    return false;
  }

  /* Check Extensions */
  if (!in_array(strtolower(explode(".", $file['name'])[count(explode(".", $file['name']))-1]), $extensionArray)) {
    return false;
  }

  /* Rename file */
  $explode = explode(".", $file['name']);
  $file['name'] = implode('.',array_slice($explode, 0, count($explode) - 1)) . '-' . time() . "." . $explode[count($explode) - 1];
  $toReplace = array(" ", '_', '"', "'", "/", "\\", "(", ")");
  $file['name'] = str_replace($toReplace, "-", $file['name']);

  while (strpos($file['name'], '--')) {
    $file['name'] = str_replace('--', "-", $file['name']);
  }

  try {
    move_uploaded_file($file['tmp_name'], $destination . $file['name']);
    return $file;
  } catch (\Exception $e) {
    return false;
  }
}
?>
