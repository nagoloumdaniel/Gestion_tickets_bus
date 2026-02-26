<?php session_start();?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Passagers</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="vendor/fontawesome-free/css/all.min.css" type="text/css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css'>
    <link rel="stylesheet" href="../sweetalert/sweetalert2.min.css">
</head>
<body>
    <div class="container">
        <br>
        <h2>Liste des Passagers</h2>
        <br><br><br>

        <form method="post" action="#">
            <input type="search" name="place" id="place" placeholder="rechercher une place ...">
            <a class="fa fa-search" style="text-decoration:none;" type="submit" name="search"></a>
        </form>

        <div class="box">
            <?php
                include "../conn.php";

                if(isset($_GET['ID_trajet'])){
                    $idTrajet = $_GET['ID_trajet'];
                
                    $sql = "SELECT Utilisateurs.*, Reservations.*, Paiements.*
                        FROM Utilisateurs
                        INNER JOIN Reservations ON Utilisateurs.ID_utilisateur = Reservations.ID_utilisateur
                        INNER JOIN Paiements ON Reservations.ID_reservation = Paiements.ID_reservation
                        WHERE Reservations.ID_trajet = $idTrajet AND Reservations.is_deleted = 'false' 
                        ORDER BY Reservations.place ASC";
                    $result = $conn->query($sql);  

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            
                            $login = $row["Login"];
                            $role = $row["Role"];
                            $profil = $row["profil"];
                            $placeReservation = $row["place"];

                            echo'    <div class="list">';
                            echo'        <div class="imgBx">';
                            echo'            <img src="../'.$profil.'"/>';
                            echo'        </div>';
                            echo'        <div class="content">';
                            echo'            <h2 class="rank"><small>#</small>'.$placeReservation.'</h2>';
                            echo'            <h4>'.$login.'</h4>';
                            echo'            <p>'.$role.'</p>';
                            echo'        </div>';
                            echo'    </div>';
                        
                        }   
                    }else{
                        echo'Aucun client n\'a réservé un ticket pour ce trajet !';
                    }
                }
                $conn->close();
            ?>
        </div>
    </div>
    <?php $_SESSION['genererlist'] = $_GET['ID_trajet']; ?>
    <a href="pdflist.php" class="floating-button">Générer la liste</a> 
    <script src="../sweetalert/sweetalert2.all.min.js"></script>
</body>
</html>