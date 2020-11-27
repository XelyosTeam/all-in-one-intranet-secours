<?php
  require "vendor/autoload.php";

  /* Initialisation des variables de sessions */
  require __DIR__ . '/vendor/autoload.php';
  use Josantonius\Session\Session;

  Session::init();

  /* Initialisation des variables de sessions */

  /* Associer Flight à Twig */
  $loader = new \Twig\Loader\FilesystemLoader(dirname(__FILE__) . '/views');
  $twigConfig = array(
      // 'cache' => './cache/twig/',
      // 'cache' => false,
      'debug' => true,
  );

  Flight::register('view', '\Twig\Environment', array($loader, $twigConfig), function ($twig) {
      $twig->addExtension(new \Twig\Extension\DebugExtension()); // Add the debug extension
      $twig->addGlobal('_agent', Agent::getInfoAgent());

      /* Paramètre de page server.ini */
      // Faction
      $twig->addGlobal('_membreFaction', serveurIni('Faction', 'membre'));
      $twig->addGlobal('_nomFaction', serveurIni('Faction', 'nom'));
      $twig->addGlobal('_nomcompletFaction', serveurIni('Faction', 'nomcomplet'));
      $twig->addGlobal('_BDDFaction', serveurIni('Faction', 'metierBDD'));
      //Serveur
      $twig->addGlobal('_nomServeur', serveurIni('Serveur', 'nom'));
      $twig->addGlobal('_jeuServeur', serveurIni('Serveur', 'jeu'));
      $twig->addGlobal('_Version', serveurIni('Serveur', 'version'));
      $twig->addGlobal('_CopServeur', serveurIni('Serveur', 'url_cop'));
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

  Flight::route('/', function()
  {
    verif_connecter();
    $agent = Agent::getInfoAgent();
    Flight::view()->display('fiche/ems.twig', array(
      'agent' => $agent,
      'voitures' => Voiture::getListCarEMS($agent->user_id)
    ));
  });

  Flight::route('/discussion-interne', function()
  {
    verif_connecter();

    Flight::view()->display('tchat.twig');
  });

  Flight::route('/dossier-candidat', function()
  {
    verif_connecter();
    verif_enseignant(); // Vérifie que la personne soit bien un enseignant
    /* Variable récupéré dans le get */
    $identifiant = $_GET['identifiant'];
    /* Variable récupéré dans le get */
    $en_attente = Candidature::getListCandidature(1);
    $refusee = Candidature::getListCandidature(2);
    $accepte = Candidature::getListCandidature(3);
    $personne = NULL;
    $ecole = NULL;
    $vacance = NULL;
    $travail = NULL;
    $concat = NULL;

    if ($identifiant != NULL) {
      $personne = Candidature::getCandidature($identifiant);
      $ecole = explode('-', $personne->detail_ecole);
      $vacance = explode('-', $personne->detail_vacance);
      $travail = explode('-', $personne->detail_travail);
      $concat = explode('-', $personne->concat);
    }

    Flight::view()->display('ecole/dossier_candidat.twig', array(
      'attente' => $en_attente,
      'refuser' => $refusee,
      'accepter' => $accepte,
      'personne' => $personne,
      'ecole' => $ecole,
      'vacance' => $vacance,
      'travail' => $travail,
      'concat' => $concat
    ));
  });

  Flight::route('/dossier-candidat/@decision/@num', function($decision, $num)
  {
    verif_connecter();
    verif_enseignant();

    switch ($decision) {
      case 'accepter':
        editCandidature($num, 3);
        addHistorique(Agent::getInfoAgent()->matricule, "2¤3¤0¤" . $num);
        break;
      case 'refuser':
        editCandidature($num, 2);
        addHistorique(Agent::getInfoAgent()->matricule, "2¤3¤1¤" . $num);
        break;
      default:
        editCandidature($num, 2);
        addHistorique(Agent::getInfoAgent()->matricule, "2¤3¤1¤" . $num);
        break;
    }
    Flight::redirect("/dossier-candidat?identifiant=");
  });

  Flight::route('/chat', function()
  {
    verif_connecter();
    /* On doit analyser la demande faite via l'URL (GET) afin de déterminer si on souhaite récupérer les messages ou en écrire un  */
    $task = "list";

    if(array_key_exists("task", $_GET)) {
      $task = $_GET['task'];
    }

    if($task == "write") {
      postMessage();
    } else {
      getMessages();
    }
  });

  Flight::route('/', function()
  {
    verif_connecter();
    $agent = Agent::getInfoAgent();
    Flight::view()->display('fiche/ems.twig', array(
      'agent' => $agent,
      'voitures' => Voiture::getListCarEMS($agent->user_id)
    ));
  });

  Flight::route('/administration/ajout', function()
  {
    verif_connecter();
    verif_admin();
    Flight::view()->display('administration/ajout.twig', array(
      'agents' => Agent::getListNonProf()
    ));
  });

  Flight::route('/administration/details/echec', function()
  {
    verif_connecter();
    verif_admin();
    if (!isset($_POST['adresse'])) {
      Flight::redirect("/administration/modification");
      exit();
    }
    else {
      $ip = $_POST['adresse'];
    }

    Flight::view()->display('administration/detail_echec.twig', array(
      'ip' => $ip,
      'listes' => Historique::getEchecAdresse($ip)
    ));
  });

  Flight::route('/administration/modification', function()
  {
    verif_connecter();
    verif_admin();
    Flight::view()->display('administration/modification.twig', array(
      'ems' => Agent::getListAgent(),
      'echecs' =>  Historique::getEchec(),
      'medicaments' => Medicament_Liste::getList()
    ));
  });

  Flight::route('/administration/parametre-serveur', function()
  {
    verif_connecter();
    verif_admin(); // Vérifie si l'utilisateur fait partie des administrateurs

    Flight::view()->display('administration/server_param.twig', array(
      'fail' => serveurIni('Parametre', 'echec_maximum')
    ));
  });

  Flight::route('/administration/parametre-serveur/modification', function()
  {
    verif_connecter();
    verif_admin(); // Vérifie si l'utilisateur fait partie des administrateurs

    if ($_POST['failed_connexion'] != '') {
      editserveurIni('Parametre', 'echec_maximum', $_POST['failed_connexion']);
      addHistorique(Session::get('matricule_ems'), "0¤0¤0¤" . $_POST['failed_connexion']);
    }

    Flight::redirect("/administration/parametre-serveur");
  });

  Flight::route('/administration/historique', function()
  {
    verif_connecter();
    verif_admin();

    Flight::view()->display('administration/historique.twig', array(
      'ems' => Agent::getListAgent(),
      'oldems' => Agent::getListOldAgent()
    ));
  });

  Flight::route('/administration/historique/get_info', function()
  {
    verif_connecter();
    verif_admin();

    $agent = Agent::getInfoAgentMatricule($_POST['matricule_ems']);
    $liste = Historique_EMS::getAction($_POST['matricule_ems']);

    if ($agent == NULL) { // L'agent n'existe pas
      $data = [
        'etat' => 0,
        'nom' => "Erreur dans l'envoi du matricule"
      ];
    }
    else { // L'agent existe
      // On récupère le nom de l'individu
      if ($agent->grade_id != 1) {
        $name = $agent->grade . " " . $agent->nom;
      }
      else {
        $name = $agent->nom . " " . $agent->prenom;
      }

      if ($liste == NULL) { // L'agent n'a pas envore fait d'action
        $data = [
          'etat' => 0,
          'nom' => $name
        ];
      }
      else {
        $i = 0;
        foreach ($liste as $key => $action) {
          $data[$key] = [
            'id' => $action->id,
            'date' => $action->date_even,
            'event' => decryptHistorique($action->contenu)
          ];
          $i++;
        }

        $data[$i] = ['etat' => $name];
      }
    }

    Flight::json($data);
  });

  Flight::route('/administration/historique_connexion/get_info', function()
  {
    verif_connecter();
    verif_admin();

    $agent = Agent::getInfoAgentMatricule($_POST['matricule_ems']);
    $liste = Historique::getListMatricule($_POST['matricule_ems']);

    if ($agent == NULL) { // L'agent n'existe pas
      $data = [
        'etat' => 0,
        'nom' => "Erreur dans l'envoi du matricule"
      ];
    }
    else { // L'agent existe
      // On récupère le nom de l'individu
      if ($agent->grade_id != 1) {
        $name = $agent->grade . " " . $agent->nom;
      }
      else {
        $name = $agent->nom . " " . $agent->prenom;
      }

      if ($liste == NULL) { // L'agent n'a pas envore fait d'action
        $data = [
          'etat' => 0,
          'nom' => $name
        ];
      }
      else {
        $i = 0;
        foreach ($liste as $key => $action) {
          $data[$key] = [
            'id' => $action->id,
            'date' => $action->date_connexion,
            'content' => $action->commentaire
          ];
          $i++;
        }

        $data[$i] = ['etat' => $name];
      }
    }

    Flight::json($data);
  });

  Flight::route('/administration/historique/get_stats', function()
  {
    verif_connecter();
    verif_admin();

    $agent = Agent::getInfoAgentMatricule($_POST['matricule_cop']);

    if ($agent) {
      $data = [
        'etat' => 1,
        'login' => Historique::getNbConnect($agent->matricule),
        'action' => Historique_EMS::getNbAction($agent->matricule),
        'arret' => Arret::getNbArret($agent->ems_id),
        'ppa' => PPA::getPPA($agent->ems_id),
        'travail' => Certificat::getNbCertificat($agent->ems_id)
      ];
    }
    else {
      $data = [
        'etat' => 0
      ];
    }

    Flight::json($data);
  });

  Flight::route('/arret-travail', function()
  {
    verif_connecter();
    $civils = Personne::getListPersonne();
    Flight::view()->display('add/arret-travail.twig', array(
      'civils' => $civils
    ));
  });

  Flight::route('/arret-travail/@id_arret', function($id_arret)
  {
    verif_connecter();
    $arret = Arret::getArret($id_arret);

    Flight::view()->display('fiche/arret.twig', array(
      'civil' => Personne::getinfoPersonne($arret->personne),
      'arret' => $arret,
      'ems' => Agent::getInfoAgentIDUser($arret->enregistrer_par)
    ));
  });

  Flight::route('/certificat-ppa', function()
  {
    verif_connecter();

    Flight::view()->display('add/ppa.twig', array(
      'civils' => Personne::getPPA()
    ));
  });

  Flight::route('/certificat-ppa/@id_ppa', function($id_ppa)
  {
    verif_connecter();
    $ppa = PPA::getCertificat($id_ppa);

    Flight::view()->display('fiche/ppa.twig', array(
      'civil' => Personne::getinfoPersonne($ppa->personne),
      'ppa' => $ppa,
      'ems' => Agent::getInfoAgentIDUser($ppa->enregistrer_par)
    ));
  });

  Flight::route('/certificat-travail', function()
  {
    verif_connecter();

    Flight::view()->display('add/certificat.twig', array(
      'civils' => Personne::getListPersonne()
    ));
  });

  Flight::route('/certificat-travail/@id_certificat', function($id_certificat)
  {
    verif_connecter();
    $certificat = Certificat::getCertificat($id_certificat);

    Flight::view()->display('fiche/certificat.twig', array(
      'civil' => Personne::getinfoPersonne($certificat->personne),
      'arret' => $certificat,
      'ems' => Agent::getInfoAgentIDUser($certificat->enregistrer_par)
    ));
  });

  Flight::route('/civil', function()
  {
    verif_connecter();
    /* Variable récupéré dans le get */
    $nom = $_GET['civil_name'];
    $prenom = $_GET['civil_firstname'];
    $phone = $_GET['civil_phone'];
    /* Variable récupéré dans le get */
    Flight::view()->display('recherche/civil.twig', array(
      'civils' => Personne::getListPersonneTri($nom, $prenom, $phone),
      'nom' => $nom,
      'prenom' => $prenom,
      'phone' => $phone
    ));
  });

  Flight::route('/civil/@id_civil', function($id_civil)
  {
    verif_connecter();
    $civil = Personne::getinfoPersonne($id_civil);

    Flight::view()->display('fiche/civil.twig', array(
      'civil' => $civil,
      'interventions' => Intervention::getListIntervention($civil->id),
      'arrets' => Arret::getListArret($civil->id),
      'certificats' => Certificat::getListCertificat($civil->id),
      'ppas' => PPA::getListCertificat($civil->id),
      'ems' => Agent::getInfoAgentIDUser($civil->id),
      'ordonnances' => Ordonnance::getListID($civil->id)
    ));
  });

  Flight::route('/civil/@id_civil/edit', function($id_civil)
  {
    verif_connecter();
    $civil = Personne::getinfoPersonne($id_civil);

    Flight::view()->display('edit/civil.twig', array(
      'perso' => $civil,
      'interventions' => Intervention::getListIntervention($civil->id),
      'arrets' => Arret::getListArret($civil->id),
      'certificats' => Certificat::getListCertificat($civil->id),
      'ppas' => PPA::getListCertificat($civil->id),
      'ems' => Agent::getInfoAgentIDUser($civil->id)
    ));
  });

  Flight::route('/civil/@id_civil/modification', function($id_civil)
  {
    verif_connecter();
    if (!isset($_POST['sang'])) {
      Flight::redirect("/civil/$id_civil");
      exit();
    }
    else {
      $sang = $_POST['sang'];
      $donneur = $_POST['organe'];
      $agent = Agent::getInfoAgent();
      $old_info = Personne::getinfoPersonne((int)$id_civil)->grp_sanguin;
      editCivil((int)$id_civil, $sang, $donneur);

      if ($old_info != $sang) {
        addHistorique($agent->matricule, "2¤0¤1¤" . (int)$id_civil . "¤" . $old_info . "¤" . $sang);
      }
    }

    Flight::redirect("/civil/$id_civil");
  });

  Flight::route('/connect', function()
  {
    /* Variable de POST */
    $matricule = $_POST['user_matricule'];
    $mdp = $_POST['user_mdp'];
    $ip_user = $_SERVER['REMOTE_ADDR'];
    /* Variable de POST */

    // protocole_connexion($matricule, $mdp, $ip_user);
    $etat = protocole_connexion($matricule, $mdp, $ip_user);
    switch ($etat) {
      case 1:
        $message = "Tentative de connexion trop importante !";
        break;
      case 2:
        $message = "Le matricule saisi n'est pas valide";
        break;
      case 3:
        $message = "Le matricule saisi n'est plus en service";
        break;
      case 4:
        $message = "Le mot de passe entré n'est pas valide";
        break;
      default:
        $message = $etat;
        $etat = 0;
        break;
    }

    $data = [
      'etat' => $etat,
      'message' => $message
    ];

    Flight::json($data);
  });

  Flight::route('/connexion', function()
  {
    Session::destroy(); // Destruction de session
    Flight::view()->display('connect.twig', array());
  });

  Flight::route('/edit/mdp', function()
  {
    verif_connecter();
    /* Variable de POST */
    $ancien = $_POST['ancien_mdp'];
    $new = $_POST['new_mdp'];
    $new2 = $_POST['confirm_mdp'];
    /* Variable de POST */

    if (($new != $new2) or ($ancien == $new2)) {
      Flight::redirect("/nouveau-mot-de-passe");
      exit();
    }

    $agent = Agent::getInfoAgent();
    $ems = EMS_t::getInfoAgentMatricule($agent->matricule);

    $ancien_ashed = program_crypt($ancien, $ems->Sel_de_table);

    if ($ems->Passwd != $ancien_ashed) { // L'ancien mot de passe n'est pas identique au nouveau
      Flight::redirect("/nouveau-mot-de-passe");
      exit();
    }

    $salt = bin2hex(random_bytes(15)); // Génération  du sault
    $new_mdp_hashed = program_crypt($new, $salt); // On génère le mot de passe avec le nouveau salt

    mise_a_jour_mdp($agent->matricule, $new_mdp_hashed, $salt);

    addHistorique($agent->matricule, "2¤0¤0");

    Flight::redirect("/connexion"); // Redirige vers la page
  });

  Flight::route("/" . serveurIni('Faction', 'membre'), function()
  {
    verif_connecter();
    verif_enseignant(); // Vérifie que la personne soit bien un enseignant
    /* Variable récupéré dans le get */
    $nom = $_GET['ems_name'];
    $prenom = $_GET['ems_firstname'];
    $mat = $_GET['ems_matricule'];
    /* Variable récupéré dans le get */

    Flight::view()->display('recherche/liste_ems.twig', array(
      'agents' => Agent::getListAgentTri($nom, $prenom, $mat),
      'nom' => $nom,
      'prenom' => $prenom,
      'matricule' => $mat
    ));
  });

  Flight::route("/" . serveurIni('Faction', 'membre') . "/@matricule", function($matricule)
  {
    verif_connecter();
    $agent = Agent::getInfoAgentMatricule($matricule);

    Flight::view()->display('fiche/ems.twig', array(
      'agent' => $agent,
      'voitures' => Voiture::getListCarEMS($agent->user_id)
    ));
  });

  Flight::route("/" . serveurIni('Faction', 'membre') . "/@matricule/edit", function($matricule)
  {
    verif_connecter();
    verif_enseignant();

    Flight::view()->display('edit/ems.twig', array(
      'agent' => Agent::getInfoAgentMatricule($matricule),
      'grades' => Grade::getList()
    ));
  });

  Flight::route("/" . serveurIni('Faction', 'membre') . "/@matricule/modification", function($matricule)
  {
    verif_connecter();
    verif_enseignant();

    /* Variable récupéré dans le post */
    $grade = $_POST['grade'];
    $note = $_POST['note'];
    if (isset($_POST['hab1'])) {
      $hab1 = $_POST['hab1'];
    }
    else {
      $hab1 = 1;
    }

    if (isset($_POST['hab2'])) {
      $hab2 = $_POST['hab2'];
    }
    else {
      $hab2 = 1;
    }

    if (isset($_POST['hab3'])) {
      $hab3 = $_POST['hab3'];
    }
    else {
      $hab3 = 1;
    }

    if (isset($_POST['hab4'])) {
      $hab4 = $_POST['hab4'];
    }
    else {
      $hab4 = 1;
    }

    if (isset($_POST['hab5'])) {
      $hab5 = $_POST['hab5'];
    }
    else {
      $hab5 = 1;
    }

    if (isset($_POST['hab6'])) {
      $hab6 = $_POST['hab6'];
    }
    else {
      $hab6 = 1;
    }

    if (isset($_POST['hab7'])) {
      $hab7 = $_POST['hab7'];
    }
    else {
      $hab7 = 1;
    }

    if (isset($_POST['hab8'])) {
      $hab8 = $_POST['hab8'];
    }
    else {
      $hab8 = 1;
    }

    if (isset($_POST['hab9'])) {
      $hab9 = $_POST['hab9'];
    }
    else {
      $hab9 = 1;
    }

    if (isset($_POST['hab10'])) {
      $hab10 = $_POST['hab10'];
    }
    else {
      $hab10 = 1;
    }

    if (isset($_POST['hab11'])) {
      $hab11 = $_POST['hab11'];
    }
    else {
      $hab11 = 1;
    }

    if (isset($_POST['hab12'])) {
      $hab12 = $_POST['hab12'];
    }
    else {
      $hab12 = 1;
    }

    if (isset($_POST['hab13'])) {
      $hab13 = $_POST['hab13'];
    }
    else {
      $hab13 = 1;
    }

    if (isset($_POST['hab14'])) {
      $hab14 = $_POST['hab14'];
    }
    else {
      $hab14 = 1;
    }

    if (isset($_POST['hab15'])) {
      $hab15 = $_POST['hab15'];
    }
    else {
      $hab15 = 1;
    }
    /* Variable récupéré dans le post */

    $oldinfo = Agent::getInfoAgentMatricule($matricule);
    $agent = Agent::getInfoAgent();

    if ($grade == 1) {
      editLicenciement($oldinfo->ems_id);
      addHistorique($agent->matricule, "2¤2¤1¤$oldinfo->matricule");
      Flight::redirect("/" . serveurIni('Faction', 'membre') . "/$oldinfo->matricule"); // Redirection vers la page de l'agent
    }
    else {
      editEMS($oldinfo->ems_id, $grade, $hab1, $hab2, $hab3, $hab4, $hab5, $hab6, $hab7, $hab8, $hab9, $hab10, $hab11, $hab12, $hab13, $hab14, $hab15, $note);
      update_metier($oldinfo->user_id, serveurIni('Faction', 'metierBDD'));
      AddHistoriqueModifAgent($oldinfo, $matricule, $grade, $hab1, $hab2, $hab3, $hab4, $hab5, $hab6, $hab7, $hab8, $hab9, $hab10, $hab11, $hab12, $hab13, $hab14, $hab15, $note);
    }

    Flight::redirect("/" . serveurIni('Faction', 'membre') . "/$matricule");

  });

  Flight::route('/intervention', function()
  {
    verif_connecter();
    Flight::view()->display('add/intervention.twig', array(
      'civils' => Personne::getListPersonne(),
      'interventions' => InterventionList::getList()
    ));
  });

  Flight::route('/intervention/@id_intervention', function($id_intervention)
  {
    verif_connecter();
    $intervention = Intervention::getIntervention($id_intervention);

    Flight::view()->display('fiche/intervention.twig', array(
      'civil' => Personne::getinfoPersonne($intervention->id_civil),
      'intervention' => $intervention,
      'ems' => Agent::getInfoAgentIDUser($intervention->enregistre_par)
    ));
  });

  Flight::route('/formation', function()
  {
    verif_connecter();
    Flight::view()->display('page_formation.twig');
  });

  Flight::route('/nouveau-mot-de-passe', function()
  {
    verif_connecter();
    Flight::view()->display('new_mdp.twig'); // Page de connexion --> Faire en sorte que si il n'existe pas de variable d'environnement, ce soit la page par défaut
  });

  Flight::route('/recrutement', function()
  {
    verif_connecter();
    verif_enseignant();
    Flight::view()->display('ecole/ems.twig', array(
      'civils' => Personne::OldEMS(),
      'ems' => Agent::getListAgent(),
      'oldems' => Agent::getListOldAgent()
    ));
  });

  Flight::route('/recherche/photo/ems', function()
  {
    verif_connecter();
    $id = $_POST["id"];

    // Récupération de l'info image véhicule
    $info = Agent::getInfoAgentIDUser($id);
    $data = [
      'grade' => $info->grade,
      'nom' => $info->nom,
      'photo' => $info->photo
    ];

    Flight::json($data);
  });

  Flight::route('/recherche/photo/personne', function()
  {
    verif_connecter();
    $id = $_POST["id"];

    // Récupération de l'info image véhicule
    $info = Personne::getinfoPersonne($id);
    $data = [
      'nom' => $info->nom,
      'prenom' => $info->prenom,
      'photo' => $info->photo
    ];

    Flight::json($data);
  });

  Flight::route('/insert/arret', function()
  {
    verif_connecter();
    /* Variable de POST */
    $civil = $_POST['id_civil'];
    $duree = $_POST['time'];
    $rapport = $_POST['motif'];
    /* Variable de POST */

    $agent = Agent::getInfoAgent();
    addArret($civil, $duree, $agent->ems_id, $rapport);
    addHistorique($agent->matricule, "1¤0¤0¤$civil");

    $info = Arret::getIDArret($civil, $agent->ems_id);
    Flight::redirect("/arret-travail/$info->id");
  });

  Flight::route('/insert/certificat', function()
  {
    verif_connecter();
    /* Variable de POST */
    $civil = $_POST['citoyen'];
    $etat = $_POST['etat_job'];
    $rapport = $_POST['motif'];
    $metier = $_POST['job'];
    /* Variable de POST */

    $agent = Agent::getInfoAgent();
    addCertificat($civil, $etat, $agent->ems_id, $metier, $rapport);
    addHistorique($agent->matricule, "1¤0¤1¤$civil");

    $info = Certificat::getIDCertificat($civil, $agent->ems_id);
    Flight::redirect("/certificat-travail/$info->id");
  });

  Flight::route('/insert/medicaments', function()
  {
    verif_connecter();
    verif_admin();
    /* Variable de POST */
    $nom = $_POST['name'];
    $etat = $_POST['description'];
    /* Variable de POST */

    $agent = Agent::getInfoAgent();
    addMedicaments($nom, $etat);
    addHistorique($agent->matricule, "0¤1¤2¤$nom" . "¤" . $etat);
  });

  Flight::route("/insert/". serveurIni('Faction', 'membre'), function()
  {
    verif_connecter();
    verif_enseignant();
    /* Variable de POST */
    $civil = $_POST['nom_civil'];
    /* Variable de POST */

    $agent = Agent::getInfoAgent();
    if ($agent->hab_1 != 2) { // N'est pas autorisé à ajouter
      Flight::redirect("/recrutement");
    }
    else {
      $matricule = addEMS($civil);
      addHistorique($agent->matricule, "2¤2¤0¤$matricule");
      Flight::redirect("/" . serveurIni('Faction', 'membre') . "/$matricule"); // Redirection vers la page de l'agent
    }
  });

  Flight::route('/insert/intervention', function()
  {
    verif_connecter();
    /* Variable de POST */
    $civil = $_POST['id_civil'];
    $inter = $_POST['inter_name'];
    $rapport = $_POST['rapport'];
    /* Variable de POST */

    if ($inter == 'NULL') {
      Flight::redirect("/intervention");
    }

    $agent = Agent::getInfoAgent();
    addIntervention($civil, $inter, $agent->ems_id, $rapport);
    addHistorique($agent->matricule, "1¤0¤2¤$civil" . "¤" . $inter);

    $info = Intervention::getIDIntervention($civil, $agent->ems_id);
    Flight::redirect("/intervention/$info->inter_id");
  });

  Flight::route('/insert/intervention-type', function()
  {
    verif_connecter();
    verif_admin();
    /* Variable de POST */
    $nom = $_POST['inter_name'];
    /* Variable de POST */

    $agent = Agent::getInfoAgent();
    addInterventionList($nom);
    addHistorique($agent->matricule, "0¤1¤0¤$nom");
  });

  Flight::route('/insert/ppa', function()
  {
    verif_connecter();
    /* Variable de POST */
    $civil = $_POST['id_civil'];
    $etat = $_POST['etat_ppa'];
    $motif = $_POST['motif'];
    /* Variable de POST */

    $agent = Agent::getInfoAgent();
    addPPA($civil, $etat, $motif, $agent->ems_id);
    addHistorique($agent->matricule, "1¤0¤3¤$civil");

    $info = PPA::getIDPPA($civil, $agent->ems_id);
    Flight::redirect("/certificat-ppa/$info->id");
  });

  Flight::route('/add/prof', function()
  {
    verif_connecter();
    verif_admin();
    /* Variable de POST */
    $id_ems = $_POST['nom_ems'];
    /* Variable de POST */

    $agent = Agent::getInfoAgent();
    updateProf($id_ems);
    addHistorique($agent->matricule, "0¤3¤0¤". Agent::getInfoAgentIDUser($id_ems)->matricule);
    Flight::redirect("/administration/ajout");
  });

  Flight::route("/delete/" . serveurIni('Faction', 'membre'), function()
  {
    verif_connecter();
    verif_enseignant();
    /* Variable de POST */
    $ems = $_POST['nom_ems'];
    /* Variable de POST */

    $agent = Agent::getInfoAgent();
    if ($agent->hab_1 != 2) { // N'est pas autorisé à ajouter
      Flight::redirect("/recrutement");
    }
    else {
      $info = Agent::getInfoAgentIDUser($ems);
      editLicenciement($info->ems_id);
      addHistorique($agent->matricule, "2¤2¤1¤$info->matricule");
      Flight::redirect("/" . serveurIni('Faction', 'membre') . "/$info->matricule"); // Redirection vers la page de l'agent
    }
  });

  Flight::route('/delete/@adress_ip', function($adresse_ip)
  {
    verif_connecter();
    verif_admin();

    $adresse_ip = str_replace('-', '.', $adresse_ip);
    deleteIP($adresse_ip);
    $op = Agent::getInfoAgent();
    addHistorique($op->matricule, "0¤1¤1¤$adresse_ip");
    Flight::redirect("/administration/modification"); // Redirige vers la page
  });

  Flight::route('/edit/mdp/admin', function()
  {
    verif_connecter();
    verif_admin();
    /* Variable de POST */
    $id_ems = $_POST['nom'];
    $new_mdp = $_POST['mdp'];
    /* Variable de POST */

    $salt = bin2hex(random_bytes(15)); // On génère un nouveau salt
    $new_mdp_hashed = program_crypt($new_mdp, $salt);

    $agent = Agent::getInfoAgentIDUser($id_ems);
    mise_a_jour_mdp($agent->matricule, $new_mdp_hashed, $salt);

    $op = Agent::getInfoAgent();
    addHistorique($op->matricule, "0¤2¤0$agent->matricule");
    Flight::redirect("/" . serveurIni('Faction', 'membre') . "/$agent->matricule"); // Redirige vers la page
  });

  Flight::route('/edit/rehabilitaton', function()
  {
    verif_connecter();
    /* Variable de POST */
    $id_ems = $_POST['id_ems'];
    /* Variable de POST */

    $agent = Agent::getInfoAgent();
    if ($agent->hab_1 != 2) { // N'est pas autorisé à ajouter
      Flight::redirect("/recrutement");
    }
    else {
      editEms($id_ems, 2, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, "");
      $old = Agent::getInfoAgentIDUser($id_ems);

      addHistorique($agent->matricule, "2¤2¤2¤$old->matricule");
      Flight::redirect("/" . serveurIni('Faction', 'membre') . "/$old->matricule");
    }
  });

  Flight::route('/ordonnance/@n_ordonnance', function($n_ordonnance)
  {
    verif_connecter();

    $ordonnance = Ordonnance::getInfo($n_ordonnance);
    if ($ordonnance == NULL) {
      Flight::redirect("/ordonnance");
      return;
    }

    Flight::view()->display('fiche/ordonnance.twig', array(
      'civil' => Personne::getinfoPersonne($ordonnance->patient),
      'ems' => Agent::getInfoAgentIDUser($ordonnance->enregistrer_par),
      'medicaments' => Info_Ordonnance::getList($n_ordonnance),
      'ordonnance' => Ordonnance::getInfo($n_ordonnance)
    ));
  });

  Flight::route('/ordonnance', function()
  {
    verif_connecter();

    Flight::view()->display('add/ordonnance.twig', array(
      'civils' => Personne::getListPersonne(),
      "medicaments" => Medicament_Liste::getList()
    ));
  });

  Flight::route('/medicament/get_info', function()
  {
    verif_connecter();
    verif_admin();

    $data = [
      'info' => Medicament_Liste::getInfo($_POST['med_id'])->description
    ];

    Flight::json($data);
  });

  Flight::route('/medicament/get_list', function()
  {
    verif_connecter();
    verif_admin();

    $liste = Medicament_Liste::getList();
    foreach ($liste as $key => $med) {
      $data[$key] = [
        'id' => $med->id,
        'name' => $med->nom,
        // 'info' => $med->description
      ];
    }

    Flight::json($data);
  });

  Flight::route('/medicament/add/ordonnance', function()
  {
    verif_connecter();

    $id = $_POST['personne_type'];
    $agent = Agent::getInfoAgent();
    $date = addOronnance($id, $agent->ems_id);
    $info = Ordonnance::getLastOrdonnance($id, $agent->ems_id, $date);

    $data = [
      'n_ordonnance' => $info->id
    ];

    addHistorique($agent->matricule, "1¤1¤0¤" . $info->id . "¤" . $id);

    Flight::json($data);
  });

  Flight::route('/medicament/add/medicament', function()
  {
    verif_connecter();

    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $quantite = $_POST['quantite'];
    $periode = $_POST['periode'];
    $hz = $_POST['hz'];

    addMedicamentOrdonnance($id, $nom, $quantite, $periode, $hz);

    $agent = Agent::getInfoAgent();
    addHistorique($agent->matricule, "1¤1¤1¤" . $nom . "¤" . $id);
  });

  Flight::route('/medicament/edit/desc', function()
  {
    verif_connecter();
    verif_admin();

    $id = $_POST['id'];
    $desc = $_POST['dscpt'];

    $old = Medicament_Liste::getInfo($id);

    if ($old->description != $desc) {
      editDescMed($id, $desc);
      addHistorique(Agent::getInfoAgent()->matricule, "0¤2¤1¤" . $id . "¤" . $old->description . "¤" . $desc);
    }
  });

  Flight::route('/impression/@type/@numero', function($type, $numero)
  {
    verif_connecter();
    $impression = new generatePDF();
    switch ($type) {
      case 'civil':
          $civil = Personne::getinfoPersonne($numero);
          $impression->civil($civil);
        break;
      case 'intervention':
          $intervention = Intervention::getIntervention($numero);
          $impression->intervention($intervention);
        break;
      case 'arret-travail':
          $arret = Arret::getArret($numero);
          $impression->arret($arret);
        break;
      case 'certificat-travail':
          $certificat = Certificat::getCertificat($numero);
          $impression->travail($certificat);
        break;
      case 'certificat-ppa':
          $ppa = PPA::getCertificat($numero);
          $impression->ppa($ppa);
        break;
      case 'ordonnance':
          $ordonnance = Ordonnance::getInfo($numero);
          $impression->ordonnance($ordonnance);
        break;
      default:
        Flight::redirect("/connexion");
        break;
    }
  });

  /* ================================================= */
  /*      Section sécurité des chemins sur le site     */
  /* ================================================= */

  Flight::route('/@nimportequoi', function($nimportequoi) {
    verif_connecter();
    Flight::redirect("/"); // Redirige vers la page
  });

  Flight::route('/@nimportequoi/@nimportequoi2', function($nimportequoi, $nimportequoi2) {
    verif_connecter();
    Flight::redirect("/"); // Redirige vers la page
  });

  Flight::route('/@nimportequoi/@nimportequoi2/@nimportequoi3', function($nimportequoi, $nimportequoi2, $nimportequoi3) {
    verif_connecter();
    Flight::redirect("/"); // Redirige vers la page
  });

  Flight::route('/@nimportequoi/@nimportequoi2/@nimportequoi3/@nimportequoi4', function($nimportequoi, $nimportequoi2, $nimportequoi3, $nimportequoi4) {
    verif_connecter();
    Flight::redirect("/"); // Redirige vers la page
  });

  Flight::route('/@nimportequoi/@nimportequoi2/@nimportequoi3/@nimportequoi4/@nimportequoi5', function($nimportequoi, $nimportequoi2, $nimportequoi3, $nimportequoi4, $nimportequoi5) {
    verif_connecter();
    Flight::redirect("/"); // Redirige vers la page
  });

  Flight::route('/@nimportequoi/@nimportequoi2/@nimportequoi3/@nimportequoi4/@nimportequoi5/@nimportequoi6', function($nimportequoi, $nimportequoi2, $nimportequoi3, $nimportequoi4, $nimportequoi5, $nimportequoi6) {
    verif_connecter();
    Flight::redirect("/"); // Redirige vers la page
  });

  Flight::start(); // Dernière ligne du fichier
?>
