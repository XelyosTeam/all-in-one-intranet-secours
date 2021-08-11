<?php
  /*
    Le projet All in One est un produit Xelyos mis à disposition gratuitement
    pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
    ne pas supprimer le ou les auteurs du projet.
    Created by : Xelyos - Aros
    Edited by :
  */
  require "vendor/autoload.php";

  /* Initialisation des variables de sessions */
  use Josantonius\Session\Session;

  Session::init();

  /* Initialisation des variables de sessions */

  /* Associer Flight à Twig */
  $loader = new \Twig\Loader\FilesystemLoader(dirname(__FILE__) . '/views');
  $twigConfig = array(
    'cache' => './cache/' . serveurIni('Serveur', 'version') . '/twig/',
    // 'debug' => true,
  );

  /* Version 3.0.0 */
  Flight::register('view', '\Twig\Environment', array($loader, $twigConfig), function ($twig) {
      $twig->addExtension(new \Twig\Extension\DebugExtension()); // Add the debug extension
      $twig->addGlobal('_agent', Agent::getInfoAgent());

      /* Paramètre de page server.ini */
      // Faction
      $twig->addGlobal('_membreFaction', serveurIni('Faction', 'membre'));
      $twig->addGlobal('_nomFaction', serveurIni('Faction', 'nom'));
      $twig->addGlobal('_nomcompletFaction', serveurIni('Faction', 'nomcomplet'));
      $twig->addGlobal('_BDDFaction', serveurIni('Faction', 'metierBDD'));
      $twig->addGlobal('_Recrutement', serveurIni('Faction', 'etatRecrutement'));
      //Serveur
      $twig->addGlobal('_nomServeur', serveurIni('Serveur', 'nom'));
      $twig->addGlobal('_jeuServeur', serveurIni('Serveur', 'jeu'));
      $twig->addGlobal('_Version', serveurIni('Serveur', 'version'));
      $twig->addGlobal('_CopServeur', serveurIni('Serveur', 'url_cop'));
      $twig->addGlobal('_Serveur', serveurIni('Serveur', 'url'));
      $twig->addGlobal('_siteVitrine', serveurIni('Serveur', 'url_vitrine'));
      // Habilitation
      $twig->addGlobal('_Hab1', serveurIni('HABILITATION', 'hab_1'));
      $twig->addGlobal('_Hab2', serveurIni('HABILITATION', 'hab_2'));
      $twig->addGlobal('_Hab3', serveurIni('HABILITATION', 'hab_3'));
      $twig->addGlobal('_Hab4', serveurIni('HABILITATION', 'hab_4'));
      $twig->addGlobal('_Hab5', serveurIni('HABILITATION', 'hab_5'));
      $twig->addGlobal('_Hab6', serveurIni('HABILITATION', 'hab_6'));
      $twig->addGlobal('_Hab7', serveurIni('HABILITATION', 'hab_7'));
      $twig->addGlobal('_Hab8', serveurIni('HABILITATION', 'hab_8'));
      $twig->addGlobal('_Hab9', serveurIni('HABILITATION', 'hab_9'));
      $twig->addGlobal('_Hab10', serveurIni('HABILITATION', 'hab_10'));
      $twig->addGlobal('_Hab11', serveurIni('HABILITATION', 'hab_11'));
      $twig->addGlobal('_Hab12', serveurIni('HABILITATION', 'hab_12'));
      $twig->addGlobal('_Hab13', serveurIni('HABILITATION', 'hab_13'));
      $twig->addGlobal('_Hab14', serveurIni('HABILITATION', 'hab_14'));
      $twig->addGlobal('_Hab15', serveurIni('HABILITATION', 'hab_15'));
  });
  /* Associaiton Flight Twig */

  /* Association à la base de donnée */
  Flight::before('start', function(&$params, &$output) {
    $host = serveurIni('BDD', 'host');
    $name = serveurIni('BDD', 'name');
    $user = serveurIni('BDD', 'user');
    $mdp = serveurIni('BDD', 'mdp');
    ORM::configure("mysql:host=$host;dbname=$name;charset=utf8");
    ORM::configure('username', "$user");
    ORM::configure('password', "$mdp");
  });
  /* Association à la base de donnée */

  Flight::route('/', function() {
    verif_connecter();
    $agent = Agent::getInfoAgent();
    Flight::view()->display('fiche/ems.twig', array(
      'agent' => $agent,
      'voitures' => Voiture::getListCarEMS($agent->user_id)
    ));
  });

  Flight::route('/formation', function() {
    verif_connecter();

    $path = dirname(__FILE__);
    $struct = getStructure($path);

    $names = new ArrayObject();
    foreach ($struct->navigation as $value) {
      $names->append($value);
    }

    $files = new ArrayObject();
    foreach ($struct->contenu as $value) {
      $value->fichier = getFileContent($path, $value->fichier);
      $value->fichier = renderHTMLFromMarkdown($value->fichier);
      $files->append($value);
    }

    Flight::view()->display('page_formation.twig', array(
      'index' => $names,
      'content' => $files
    ));
  });

  /* Importation des routes */
  include "routes.php";

  Flight::map('notFound', function(){
    verif_connecter();
    Flight::view()->display('404.twig');
  });

  Flight::start(); // Dernière ligne du fichier
?>
