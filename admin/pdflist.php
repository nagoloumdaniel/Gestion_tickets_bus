<?php
session_start();
unset($_SESSION['efflist']);
unset($_SESSION['errlist']);

include '../conn.php';

if (isset($_SESSION['genererlist'])) {
    $idTrajet = $_SESSION['genererlist'];

    $sql = "SELECT Utilisateurs.*, Reservations.*, Paiements.*
        FROM Utilisateurs
        INNER JOIN Reservations ON Utilisateurs.ID_utilisateur = Reservations.ID_utilisateur
        INNER JOIN Paiements ON Reservations.ID_reservation = Paiements.ID_reservation
        WHERE Reservations.ID_trajet = $idTrajet AND Reservations.is_deleted = 'false' 
        ORDER BY Reservations.place ASC";

    $result = $conn->query($sql);

    
    require('../fpdf/fpdf.php');

    $pdf = new FPDF();

    $pdf->AddPage();

    $pdf->SetFont('Arial', 'B', 16);

    $pdf->Cell(0, 10, 'Liste des passagers', 0, 1, 'C');

    $pdf->SetFont('Arial', '', 12);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

        $login = $row["Login"];
        $role = $row["Role"];
        $placeReservation = $row["place"];
        $id_paiement = $row["ID_paiement"];
        $date_paiement = $row["Date_paiement"];
        $methode_paiement = $row["Methode_paiement"];
        $montant_paye = $row["Montant_paye"];

        $pdf->Cell(0, 10, 'Place ' . $placeReservation . ' | Nom : ' . $login . ' | Methode de paiement : ' . $methode_paiement . ' | Montant paye : ' . $montant_paye .' XAF', 0, 1);
        
        }
    }
    $pdf->Cell(0, 10, 'Travel.com', 0, 1, 'C');

    $fact = 'C:\Users\Esco_bAr\Downloads\FACTURE TRAVEL\Liste des passagers n°' . $idTrajet . '.pdf';
    $pdf->Output('F', $fact);

    if (file_exists($fact)) {
        $_SESSION['listeeffectue'] = true;
        header('location:trajets.php');
        exit();
    } else {
        $_SESSION['listeerror'] = true;
        header('location:trajets.php');
        exit();
    }
}
$conn->close();

?>