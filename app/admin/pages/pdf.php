<?php
session_start();
unset($_SESSION['effl']);
unset($_SESSION['errl']);

include '../conn.php';
if (isset($_SESSION['genererfact'])) {
    $id_reservation = $_SESSION['genererfact'];

    $query = "SELECT p.*, r.*, t.*, b.*, u.*
        FROM Paiements p
        INNER JOIN Reservations r ON p.ID_reservation = r.ID_reservation
        INNER JOIN Trajets t ON r.ID_trajet = t.ID_trajet
        INNER JOIN Bus b ON t.ID_bus = b.ID_bus
        INNER JOIN Utilisateurs u ON r.ID_utilisateur = u.ID_utilisateur
        WHERE r.ID_reservation = $id_reservation AND r.is_deleted = 'false'";

    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $login = $row['Login'];
        $email = $row['Adresse_email'];
        $id_paiement = $row["ID_paiement"];
        $date_paiement = $row["Date_paiement"];
        $methode_paiement = $row["Methode_paiement"];
        $montant_paye = $row["Montant_paye"];
        $place = $row["place"];
        $ville_arrivee = $row["Ville_arrivee"];
        $ville_depart = $row["Ville_depart"];
        $date_arrivee = $row["Date_arrivee"];
        $date_depart = $row["Date_depart"];
        $numero_bus = $row["immatriculation"];
        $typebus = $row["Type_bus"];

        require('../fpdf/fpdf.php');

        $pdf = new FPDF();

        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 16);

        $pdf->Cell(0, 10, 'Facture n_' . $id_paiement, 0, 1, 'C');
        
        $pdf->Ln(); 

        $pdf->SetFont('Arial', '', 12);

        $data = array(
            array('Trajet:', $ville_depart . ' - ' . $ville_arrivee),
            array('Date de départ:', $date_depart),
            array('Date d\'arrivée:', $date_arrivee),
            array('Numéro de Bus:', $numero_bus),
            array('Type de Bus:', $typebus),
            array('Place:', $place),
            array('Date Paiement:', $date_paiement),
            array('Méthode de Paiement:', $methode_paiement),
            array('Montant Payé:', $montant_paye . ' XAF'),
        );

        foreach ($data as $row) {
            $pdf->Cell(80, 10, $row[0], 1, 0, 'L');
            $pdf->Cell(110, 10, $row[1], 1, 1, 'L');
        }

        $pdf->Ln();
        
        $pdf->SetFont('Arial', 'B', 16);

        $pdf->Cell(0, 10, 'Travel.com', 0, 1, 'C');


        $facturesDir = __DIR__ . '/../../../factures';
        if (!is_dir($facturesDir)) {
            mkdir($facturesDir, 0775, true);
        }
        $fact = $facturesDir . '/Facture_admin_' . $id_paiement . '.pdf';
        $pdf->Output('F', $fact);

        if (file_exists($fact)) {
            $_SESSION['factureeffectuee'] = true;
            header('location:Reservation.php');
            exit();
        } else {
            $_SESSION['factureerrore'] = true;
            header('location:Reservation.php');
            exit();
        }
    }
}
$conn->close();
?>