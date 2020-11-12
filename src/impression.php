<?php
// Include Dompdf required namespaces
use Dompdf\Dompdf;
use Dompdf\Options;

class generatePDF {

  private $html = "<h1>Null</h1>";
  private $nomDossier = null;

  private function encodeIMG() {
    $path = "http://" . serveurIni('Serveur', 'url') . "/assets/img/impression.png";
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    return 'data:image/' . $type . ';base64,' . base64_encode($data);
  }

  private function general() {
    $pdfOptions = new Options();
    $pdfOptions->set('defaultFont', 'Arial');

    $dompdf = new Dompdf($pdfOptions);

    $dompdf->loadHtml($this->html);
    $dompdf->setPaper('A4', 'portrait');

    $dompdf->render();
    $dompdf->stream($this->nomDossier, [
        "Attachment" => true
    ]);
  }

  public function civil($civil) {
    // Retrieve the HTML generated in our twig file
    ob_start();
    Flight::view()->display('impression/fiche.twig', array(
      'civil' => $civil,
      'interventions' => Intervention::getListIntervention($civil->id),
      'img' => $this->encodeIMG()
    ));
    $this->html = ob_get_clean();
    $this->nomDossier = "Dossier Médicale " . $civil->nom . " " . $civil->prenom . ".pdf";

    $this->general();
  }

  public function intervention($intervention) {
    ob_start();
    Flight::view()->display('impression/info_inter.twig', array(
      'civil' => Personne::getinfoPersonne($intervention->id_civil),
      'intervention' => $intervention,
      'ems' => Agent::getInfoAgentIDUser($intervention->enregistre_par),
      'img' => $this->encodeIMG()
    ));
    $this->html = ob_get_clean();
    $this->nomDossier = "Intervention n° " . $intervention->inter_id . ".pdf";

    $this->general();
  }

  public function arret($arret) {
    ob_start();
    Flight::view()->display('impression/info_arret.twig', array(
      'arret' => $arret,
      'civil' => Personne::getinfoPersonne($arret->personne),
      'ems' => Agent::getInfoAgentIDUser($arret->enregistrer_par),
      'img' => $this->encodeIMG()
    ));
    $this->html = ob_get_clean();
    $this->nomDossier = "Arrêt de travail n° " . $arret->id . ".pdf";

    $this->general();
  }

  public function travail($certificat) {
    $personne = Personne::getinfoPersonne($certificat->personne);
    ob_start();
    Flight::view()->display('impression/info_travail.twig', array(
      'civil' => $personne,
      'certif' => $certificat,
      'ems' => Agent::getInfoAgentIDUser($certificat->enregistrer_par),
      'img' => $this->encodeIMG()
    ));
    $this->html = ob_get_clean();
    $this->nomDossier = "Certificat de travail " . $personne->nom . " " . $personne->prenom . ".pdf";

    $this->general();
  }

  public function ppa($ppa) {
    $personne = Personne::getinfoPersonne($ppa->personne);
    ob_start();
    Flight::view()->display('impression/info_ppa.twig', array(
      'civil' => $personne,
      'ppa' => $ppa,
      'ems' => Agent::getInfoAgentIDUser($ppa->enregistrer_par),
      'img' => $this->encodeIMG()
    ));
    $this->html = ob_get_clean();
    $this->nomDossier = "Certificat de travail " . $personne->nom . " " . $personne->prenom . ".pdf";

    $this->general();
  }

  public function ordonnance($ordonnance) {
    $personne = Personne::getinfoPersonne($ordonnance->patient);
    ob_start();
    Flight::view()->display('impression/info_ordonnance.twig', array(
      'civil' => $personne,
      'ems' => Agent::getInfoAgentIDUser($ordonnance->enregistrer_par),
      'medicaments' => Info_Ordonnance::getList($ordonnance->id),
      'ordonnance' => Ordonnance::getInfo($ordonnance->id),
      'img' => $this->encodeIMG()
    ));
    $this->html = ob_get_clean();
    $this->nomDossier = "Ordonnance " . $personne->nom . " " . $personne->prenom . ".pdf";

    $this->general();
  }
}
?>
