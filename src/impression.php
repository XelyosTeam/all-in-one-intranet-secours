<?php
  /*
    Le projet All in One est un produit Xelyos mis à disposition gratuitement
    pour tous les serveurs de jeux Role Play. En échange nous vous demandons de
    ne pas supprimer le ou les auteurs du projet.
    Created by : Xelyos - Aros
    Edited by :
  */
// Include Dompdf required namespaces
use Dompdf\Dompdf;
use Dompdf\Options;

class generatePDF {

  private $html = "<h1>Null</h1>";
  private $nomDossier = null;
  private $impression_left = "left.png";
  private $impression_centre = "centre.png";
  private $impression_right = "right.png";

  private function encodeIMG($name) {
    if ($name) {
      $path = "http://" . serveurIni('Serveur', 'url') . "/assets/img/impression/$name";
      $type = pathinfo($path, PATHINFO_EXTENSION);
      $data = file_get_contents($path);
      return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
    else {
      return null;
    }
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
    ob_start();
    Flight::view()->display('impression/fiche.twig', array(
      'civil' => $civil,
      'ems' => Agent::getInfoAgent(),
      'interventions' => Intervention::getListIntervention($civil->id),
      'impression_right' => $this->encodeIMG($this->impression_right),
      'impression_left' => $this->encodeIMG($this->impression_left),
      'impression_centre' => $this->encodeIMG($this->impression_centre),
    ));
    $this->html = ob_get_clean();
    $this->nomDossier = "Dossier Médical " . $civil->nom . " " . $civil->prenom . ".pdf";

    $this->general();
  }

  public function intervention($intervention) {
    $civil = Personne::getinfoPersonne($intervention->id_civil);
    ob_start();
    Flight::view()->display('impression/info_inter.twig', array(
      'civil' => $civil,
      'intervention' => $intervention,
      'ems' => Agent::getInfoAgentIdEms($intervention->enregistre_par),
      'impression_right' => $this->encodeIMG($this->impression_right),
      'impression_left' => $this->encodeIMG($this->impression_left),
      'impression_centre' => $this->encodeIMG($this->impression_centre),
    ));
    $this->html = ob_get_clean();
    $this->nomDossier = "Intervention " . $civil->nom . " " . $civil->prenom . ".pdf";

    $this->general();
  }

  public function arret($arret) {
    $civil = Personne::getinfoPersonne($arret->personne);
    ob_start();
    Flight::view()->display('impression/info_arret.twig', array(
      'arret' => $arret,
      'civil' => $civil,
      'ems' => Agent::getInfoAgentIdEms($arret->enregistrer_par),
      'impression_right' => $this->encodeIMG($this->impression_right),
      'impression_left' => $this->encodeIMG($this->impression_left),
      'impression_centre' => $this->encodeIMG($this->impression_centre),
    ));
    $this->html = ob_get_clean();
    $this->nomDossier = "Arrêt de travail " . $civil->nom . " " . $civil->prenom . ".pdf";

    $this->general();
  }

  public function travail($certificat) {
    $personne = Personne::getinfoPersonne($certificat->personne);
    ob_start();
    Flight::view()->display('impression/info_travail.twig', array(
      'civil' => $personne,
      'certif' => $certificat,
      'ems' => Agent::getInfoAgentIdEms($certificat->enregistrer_par),
      'impression_right' => $this->encodeIMG($this->impression_right),
      'impression_left' => $this->encodeIMG($this->impression_left),
      'impression_centre' => $this->encodeIMG($this->impression_centre),
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
      'ems' => Agent::getInfoAgentIdEms($ppa->enregistrer_par),
      'impression_right' => $this->encodeIMG($this->impression_right),
      'impression_left' => $this->encodeIMG($this->impression_left),
      'impression_centre' => $this->encodeIMG($this->impression_centre),
    ));
    $this->html = ob_get_clean();
    $this->nomDossier = "Certificat PPA " . $personne->nom . " " . $personne->prenom . ".pdf";

    $this->general();
  }

  public function ordonnance($ordonnance) {
    $personne = Personne::getinfoPersonne($ordonnance->patient);
    ob_start();
    Flight::view()->display('impression/info_ordonnance.twig', array(
      'civil' => $personne,
      'ems' => Agent::getInfoAgentIdEms($ordonnance->enregistrer_par),
      'medicaments' => Info_Ordonnance::getList($ordonnance->id),
      'ordonnance' => Ordonnance::getInfo($ordonnance->id),
      'impression_right' => $this->encodeIMG($this->impression_right),
      'impression_left' => $this->encodeIMG($this->impression_left),
      'impression_centre' => $this->encodeIMG($this->impression_centre),
    ));
    $this->html = ob_get_clean();
    $this->nomDossier = "Ordonnance " . $personne->nom . " " . $personne->prenom . ".pdf";

    $this->general();
  }
}
?>
