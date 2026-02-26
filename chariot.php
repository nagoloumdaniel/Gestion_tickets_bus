<?php   
    session_start();  
    unset($_SESSION['aaaa']);
    unset($_SESSION['search']); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>Mon panier</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.0.0/css/all.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/mdb.min.css" />
    <link rel="stylesheet" href="sweetalert/sweetalert2.min.css">

    <style>
        body {
            font-family: "Raleway", sans-serif;
            font-optical-sizing: auto;
            font-weight: <weight>;
            font-style: normal;
            color: #0a0a0a;
            line-height: 1;
        }

        .navbar .nav-link {
            color: #000000 !important;
        }
        
        .navbar .nav-link:hover {
            color: blue !important;
        }
    </style>
</head>
<body>
    <?php

        if(isset($_SESSION['factureeffectue']) && !isset($_SESSION['eff'])){
            echo "
              <script>
                alert('Facture Genere avec succes !');
              </script>
            ";
            $_SESSION['eff'] = true;
        }

        if(isset($_SESSION['factureerror']) && !isset($_SESSION['err'])){
            echo "
              <script>
                alert('Echec lors de la generation de la facture !');
              </script>
            ";
            $_SESSION['err'] = true;
        }

        include "conn.php";

        if(!isset($_SESSION['payerorange']) && isset($_POST['orange'])){
            $trajet = $_SESSION['ID_trajet'];

            $_SESSION['payerorange'] = true;

            $phoneNumber = $_POST['numerorange'];

            $methodePaiement = "Orange Money";

            $datePaiement = date('Y-m-d H:i:s'); 
            $statutPaiement = "Effectue";

            $sqlInsertPaiement = "INSERT INTO Paiements (ID_reservation, Montant_paye, Methode_paiement, Date_paiement, Statut_paiement, is_deleted, is_enabled, created_at, updated_at, deleted_at)
                    VALUES ('{$_SESSION['idReservation']}', '{$_SESSION['Prix']}', '$methodePaiement', '$datePaiement', '$statutPaiement', 0, 1, NOW(), NOW(), NULL)";

            if ($conn->query($sqlInsertPaiement) === TRUE) {
                $sqlUpdate = "UPDATE Trajets SET Places_disponibles = Places_disponibles - 1 WHERE ID_trajet = ? AND is_deleted = 'false'";
                $stmtUpdate = $conn->prepare($sqlUpdate);
                $stmtUpdate->bind_param("i", $trajet);
                $stmtUpdate->execute();
                $stmtUpdate->close();
                echo "
                    <script>
                        alert('Paiement Effectue !');
                    </script>
                ";
            } else {
                echo "
                    <script>
                        alert('Erreur lors du paiement !');
                    </script>
                ";
            }

            $nouveauStatutReservation = "Payée";

            $sqlUpdateReservation = "UPDATE Reservations SET Statut = '$nouveauStatutReservation' WHERE ID_reservation = '{$_SESSION['idReservation']}'";

            if ($conn->query($sqlUpdateReservation) === TRUE) {
                echo "
                    <script>
                        alert('Effectue !');
                    </script>
                ";
            } else {
                echo "
                    <script>
                        alert('Erreur lors de la mise à jour du statut de la réservation !');
                    </script>
                ";
            }
        
        }

        if(!isset($_SESSION['payercredit']) && isset($_POST['credit'])){
            $trajet = $_SESSION['ID_trajet'];

            $_SESSION['payercredit'] = true;

            $carte = $_POST['carte'];
            $nomcarte = $_POST['nom_carte'];

            $_SESSION['carte'] = $carte;
            $_SESSION['nom_carte'] = $nomcarte;
            
            $methodePaiement = "Carte de crédit";
            $datePaiement = date('Y-m-d H:i:s'); 
            $statutPaiement = "Effectue";

            $sqlInsertPaiement = "INSERT INTO Paiements (ID_reservation, Montant_paye, Methode_paiement, Date_paiement, Statut_paiement, is_deleted, is_enabled, created_at, updated_at, deleted_at)
                    VALUES ('{$_SESSION['idReservation']}', '{$_SESSION['Prix']}', '$methodePaiement', '$datePaiement', '$statutPaiement', 0, 1, NOW(), NOW(), NULL)";

            if ($conn->query($sqlInsertPaiement) === TRUE) {
                $sqlUpdate = "UPDATE Trajets SET Places_disponibles = Places_disponibles - 1 WHERE ID_trajet = ? AND is_deleted = 'false'";
                $stmtUpdate = $conn->prepare($sqlUpdate);
                $stmtUpdate->bind_param("i", $trajet);
                $stmtUpdate->execute();
                $stmtUpdate->close();
                echo "
                    <script>
                        alert('Paiement Effectue !');
                    </script>
                ";
            } else {
                echo "
                    <script>
                        alert('Erreur lors du paiement !');
                    </script>
                ";  
            }

            $nouveauStatutReservation = "Payée";

            $sqlUpdateReservation = "UPDATE Reservations SET Statut = '$nouveauStatutReservation' WHERE ID_reservation = '{$_SESSION['idReservation']}'";

            if ($conn->query($sqlUpdateReservation) === TRUE) {
                echo "
                    <script>
                        alert('Effectue !');
                    </script>
                ";
            } else {
                echo "
                    <script>
                        alert('Erreur lors de la mise à jour du statut de la réservation !');
                    </script>
                ";
            }
        }

        $conn->close();
    ?>
    
    <header>
        <?php include "a.php" ?>
    <header>

    <main class="mt-5 pt-4 d-flex justify-content-center align-items-center">
        <div class="container">
            <section class="text-center mt-5 mb-5" >
                <h3 class="card-title mt-5"><strong class="text-primary" id="agence" >Mon panier...</strong></h3>
            </section>
            <hr class="my-5" />
            <section>
                <?php
                    include "conn.php";

                    if (!isset($_SESSION['supprimer_reservation']) && isset($_GET['ID_reservation'])) {
                        $id = $_GET['ID_reservation'];

                        $is_enabled = FALSE;
                        $is_deleted = TRUE;
                        $updated_at = date("Y-m-d H:i:s");
                        $deleted_at = date("Y-m-d H:i:s");

                        $sql = "UPDATE Reservations SET is_deleted = ?, is_enabled = ?, updated_at = ?, deleted_at = ? WHERE ID_reservation = ?";
                        $stmt = $conn->prepare($sql);

                        if ($stmt === false) {
                            die("Erreur de préparation de la requête : " . $conn->error);
                        }

                        $stmt->bind_param("iissi", $is_deleted, $is_enabled, $updated_at, $deleted_at, $id);

                        if ($stmt->execute()) {
                            echo "<script>alert('La reservation a été supprimée avec succès !');</script>";
                        } else {
                            echo "Erreur lors de la suppression de la ligne : " . $stmt->error;
                        }

                        $stmt->close();
                    }

                    $today = date('Y-m-d H:i:s');
                    
                    $sql = "SELECT Reservations.*, Trajets.*, Bus.Photo, Bus.Immatriculation, Bus.Capacite
                    FROM Reservations 
                    INNER JOIN Trajets ON Reservations.ID_trajet = Trajets.ID_trajet 
                    INNER JOIN Bus ON Trajets.ID_bus = Bus.ID_bus 
                    WHERE Reservations.ID_utilisateur = ? AND Reservations.is_deleted = 'false' AND Trajets.Date_depart > '$today'";

                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $_SESSION['ID_utilisateur']);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $ID_reservation = $row["ID_reservation"];
                            $ID_trajet_reservation = $row["ID_trajet"];
                            $Statutreservation = $row["Statut"];
                            $Date_reservation = $row["Date_reservation"];
                            $place_reservation = $row["place"];
                            $Ville_depart = $row["Ville_depart"];
                            $Ville_arrivee = $row["Ville_arrivee"];
                            $Date_depart = $row["Date_depart"];
                            $Date_arrivee = $row["Date_arrivee"];
                            $Prix = $row["Prix"];
                            $Places_disponibles = $row["Places_disponibles"];
                            $Numero_bus = $row["Immatriculation"];
                            $Capacite = $row["Capacite"];
                            $Photo = $row["Photo"];

                            echo '<div class="grid">';
                            echo '  <div class="g-col-6">';
                            echo '    <div class="card mb-5">';
                            echo '      <div class="row g-0">';
                            echo '        <div class="col-md-3">';
                            echo '          <img src="admin/' . $Photo . '" alt="Photo du bus" style="width: 100%; height: 100%;" class="img-fluid rounded-start" />';
                            echo '        </div>';
                            echo '        <div class="col-md-4">';
                            echo '          <div class="card-body">';
                            echo '            <h5 class="card-text">Trajet : ' . $Ville_depart . ' - ' . $Ville_arrivee . '</h5><br>';
                            echo '            <h5 class="card-text">Date de départ : ' . $Date_depart . '</h5>';
                            echo '            <h5 class="card-text">Date d\'arrivée : ' . $Date_arrivee . '</h5>';
                            echo '            <h5 class="card-text">Coût du trajet : ' . $Prix . ' XAF</h5>';
                            echo '            <h5 class="card-text">Nombre de places disponibles : ' . $Places_disponibles . '</h5>';
                            echo '          </div>';
                            echo '        </div>';
                            echo '        <div class="col-md-4">';
                            echo '          <div class="card-body">';
                            echo '            <h5 class="card-title">Place : ' . $place_reservation . '</h5>';
                            echo '            <h5 class="card-text">Numero bus : ' . $Numero_bus . '</h5>';
                            echo '            <h5 class="card-text">Nombre de place du bus : ' . $Capacite . '</h5>';
                            echo '            <h5 class="card-text">Date de la reservation : ' . $Date_reservation . '</h5>';
                            echo '            <h5 class="card-text">Statut de la reservation : ' . $Statutreservation . '</h5>';
                            echo '          </div>';
                            echo '        </div>';
                            echo '        <div class="col-md-1">';
                            echo '          <div class="card-body">';

                            if($Statutreservation == "Payée"){
                                echo '            <a href="facture.php?ID_reservation='. $ID_reservation . '" class="btn btn-outline-warning btn-lg m-4">Facture</a>';
                            }else{
                                echo '            <a href="chariot.php?ID_reservation='. $ID_reservation . '" class="btn btn-outline-danger btn-lg m-4">Supprimer</a>';
                                echo '            <a href="paiement.php?ID_reservation='. $ID_reservation . '" class="btn btn-outline-success btn-lg m-4">Continuer paiement</a>';
                            }
                            echo '          </div>';
                            echo '        </div>';
                            echo '      </div>';
                            echo '    </div>';
                            echo '  </div>';
                            echo '</div>';
                        }
                    }else{
                        echo"<p class='text-center'>Panier Vide ! </p>";
                        echo'<p> <a class="btn btn-outline-primary btn-lg" data-mdb-ripple-init href="trajet.php" role="button">Reserver maintenent !</a></p>';
                    }

                    $stmt->close();
                    $conn->close();

                ?>
            </section>

            <hr class="my-5" />
        </div>
    </main>
    <footer>
        <div class="text-center text-body p-3" style="background-color: rgba(0, 0, 0, 0.2);margin-top: 24%;bottom:0;">
            © 2024 Copyright:
            <a class="text-primary" href="accueil.php">Travel</a>
        </div>
    </footer>
    <script type="text/javascript" src="js/mdb.umd.min.js"></script>
    <script src="sweetalert/sweetalert2.all.min.js"></script>
    <script>
        const themeStitcher = document.getElementById("themingSwitcher");
        const isSystemThemeSetToDark = window.matchMedia("(prefers-color-scheme: dark)").matches;

        if (isSystemThemeSetToDark) {
            themeStitcher.checked = true;
        }

        themeStitcher.addEventListener("change", (e) => {
            toggleTheme(e.target.checked);
        });

        const toggleTheme = (isChecked) => {
            const theme = isChecked ? "dark" : "light";
            document.documentElement.dataset.mdbTheme = theme;
        }

        document.addEventListener("keydown", (e) => {
            if (e.shiftKey && e.key === "D") {
            themeStitcher.checked = !themeStitcher.checked;
            toggleTheme(themeStitcher.checked);
            }
        });
    </script>

</body>
</html>