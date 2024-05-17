<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type'])) {
    header("Location: index.php");
    exit;
}

// Vérifie si l'utilisateur est un étudiant
if ($_SESSION['user_type'] !== 'etudiant') {
    header("Location: index.php"); // Redirige vers la page d'accueil
    exit;
}

require_once '../../include/database.php';

// Récupère l'ID de l'utilisateur connecté
$user_id = $_SESSION['user_id'];

// Requête pour récupérer les informations de l'étudiant
$sql_etudiant = "SELECT id_filiere FROM etudiant WHERE id = :user_id";
$stmt_etudiant = $pdo->prepare($sql_etudiant);
$stmt_etudiant->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt_etudiant->execute();
$etudiant_info = $stmt_etudiant->fetch(PDO::FETCH_ASSOC);

if ($etudiant_info) {
    $filiere_id = $etudiant_info['id_filiere'];

    // Requête pour récupérer le nom de la filière et l'année correspondante
    $sql_filiere = "SELECT Nom_filiere, annee FROM filiere WHERE id = :filiere_id";
    $stmt_filiere = $pdo->prepare($sql_filiere);
    $stmt_filiere->bindParam(':filiere_id', $filiere_id, PDO::PARAM_INT);
    $stmt_filiere->execute();
    $filiere_info = $stmt_filiere->fetch(PDO::FETCH_ASSOC);

    if ($filiere_info) {
        $nom_table = 'emploi_temps_' . strtolower(str_replace(' ', '_', $filiere_info['Nom_filiere'])) . '_' . $filiere_info['annee'];
        $sql_check_table = "SHOW TABLES LIKE :nom_table";
        $stmt_check_table = $pdo->prepare($sql_check_table);
        $stmt_check_table->bindParam(':nom_table', $nom_table, PDO::PARAM_STR);
        $stmt_check_table->execute();
        $table_exists = $stmt_check_table->rowCount() > 0;

        if ($table_exists) {
            $emploi_du_temps = [];
            $plages_horaires = ['08:00 - 10:00', '10:00 - 12:00', '12:00 - 14:00', '14:00 - 16:00', '16:00 - 18:00'];
            $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
            foreach ($jours as $jour) {
                $sql_select = "SELECT * FROM $nom_table WHERE nom_jour = :jour";
                $stmt_select = $pdo->prepare($sql_select);
                $stmt_select->bindParam(':jour', $jour, PDO::PARAM_STR);
                $stmt_select->execute();
                $emploi_du_temps[$jour] = $stmt_select->fetchAll(PDO::FETCH_ASSOC);
            }
        } else {
            $error_message = "L'emploi du temps pour cette filière et cette année n'a pas encore été créé.";
        }
    } else {
        $error_message = "La filière de l'étudiant n'a pas été trouvée.";
    }
}

// Générer le PDF
require_once('../tcpdf/tcpdf.php');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Votre nom');
$pdf->SetTitle('Emploi du temps');
$pdf->SetSubject('Emploi du temps');
$pdf->SetKeywords('PDF, emploi du temps, université');

// Ajouter une page
$pdf->AddPage();

// Style pour le PDF
$pdf->SetFont('helvetica', '', 10);
$pdf->SetFillColor(240, 240, 240);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetLineWidth(0.3);
$pdf->setCellPaddings(4, 3, 3, 3);

// Largeur des colonnes
$columnWidths = array(40, 60, 60, 60, 60, 60, 60);

// Contenu du PDF
$html = '<h1>Emploi du temps</h1>';
$html .= '<table border="1" cellpadding="5">'; // Ajout de cellpadding pour l'espacement entre les cellules
$html .= '<tr><th width="100">Heures</th>'; // Largeur fixe pour la colonne des heures
foreach ($jours as $jour) {
    $html .= "<th width='100'>$jour</th>"; // Largeur fixe pour chaque colonne de jour
}
$html .= '</tr>';
foreach ($plages_horaires as $plage_horaire) {
    $html .= "<tr><td width='100'>$plage_horaire</td>"; // Largeur fixe pour la colonne des heures
    foreach ($jours as $jour) {
        $html .= "<td width='100'>"; // Largeur fixe pour chaque colonne de jour
        $emploi_jour = $emploi_du_temps[$jour];
        $cours_trouve = false;
        foreach ($emploi_jour as $cours) {
            if ($cours['temps'] === $plage_horaire) {
                $html .= $cours['Nom_prof'] . " - ";
                $html .= $cours['Nom_salle'] . " - ";
                $html .= $cours['Nom_module'] . " - ";
                $html .= $cours['bloc'] . " - ";
                $html .= $cours['type_sceance'];
                $cours_trouve = true;
                break;
            }
        }
        if (!$cours_trouve) {
            $html .= '---';
        }
        $html .= '</td>';
    }
    $html .= '</tr>';
}
$html .= '</table>';

$pdf->writeHTML($html, true, false, true, false, '');

// Télécharger le PDF
$pdf->Output('emploi_du_temps.pdf', 'D');
