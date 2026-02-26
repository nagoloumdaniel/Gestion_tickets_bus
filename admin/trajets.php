<?php 

$inactive_duration = 3600;

ini_set('session.gc_maxlifetime', $inactive_duration);

session_set_cookie_params($inactive_duration);

session_start();

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $inactive_duration)) {
    session_unset();
    session_destroy();
    header("location:index.php");
}

$_SESSION['LAST_ACTIVITY'] = time();

if(isset($_SESSION['listeeffectue']) && !isset($_SESSION['efflist'])){
    echo "
        <script>
            alert('Liste des passagers généré avec succès !');
        </script>
    ";
    $_SESSION['efflist'] = true;
}

if(isset($_SESSION['listeerror']) && !isset($_SESSION['errlist'])){
    echo "
        <script>
            alert('Echec lors de la generation de la facture !');
        </script>
    ";
    $_SESSION['errlist'] = true;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Admin - Trajets</title>

                    <?php include "tete.php"; ?>

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Trajets</h1>
                    </div>

                    <?php include "card.php"; ?>

                    <div class="container-fluid">   

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <a href="#" data-toggle="modal" data-target="#trajet" class="btn btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-plus"></i>
                                </span>
                                <span class="text">Ajouter un trajet</span>
                            </a>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>ID_bus</th>
                                            <th>Ville_depart</th>
                                            <th>Ville_arrivee</th>
                                            <th>Date_depart</th>
                                            <th>Date_arrive</th>
                                            <th>Prix</th>
                                            <th>Places_disponibles</th>
                                            <th>is_deleted</th>
                                            <th>is_enabled</th>
                                            <th>created_at</th>
                                            <th>updated_at</th>
                                            <th>deleted_at</th>
                                            <th>Action</th>
                                            <th>Liste des passagers</th>
                                        </tr>
                                    </thead>
                                    <?php

                                        include "../conn.php";

                                        if (isset($_GET['ID_trajet'])) {

                                            $id = $_GET['ID_trajet'];

                                            $is_enabled = false;
                                            $is_deleted = true;
                                            $updated_at = date("Y-m-d H:i:s");
                                            $deleted_at = date("Y-m-d H:i:s");

                                            $sql1 = "SELECT Ville_depart, Ville_arrivee FROM Trajets WHERE ID_trajet = ?";
                                            $stmt1 = $conn->prepare($sql1);
                                            $stmt1->bind_param("i", $id);
                                            $stmt1->execute();
                                            $result = $stmt1->get_result()->fetch_assoc();
                                            $ville_depart = $result['Ville_depart'];
                                            $ville_arrivee = $result['Ville_arrivee'];

                                            $sql = "UPDATE Trajets SET is_deleted = ?, is_enabled = ?, updated_at = ?, deleted_at = ? WHERE ID_trajet = ?";
                                            $stmt = $conn->prepare($sql);

                                            if ($stmt === false) {
                                                die("Erreur de préparation de la requête : " . $conn->error);
                                            }

                                            $stmt->bind_param("iissi", $is_deleted, $is_enabled, $updated_at, $deleted_at, $id);

                                            if ($stmt->execute()) {
                                                echo "<script>alert('Le trajet $ville_depart - $ville_arrivee a été supprimé avec succès !');</script>";
                                            } else {
                                                echo "Erreur lors de la suppression de la ligne : " . $stmt->error;
                                            }

                                            $stmt1->close();
                                            $stmt->close();
                                        }

                                        $currentDateTime = date('Y-m-d H:i:s');
                                        $sql2 = "SELECT * FROM Trajets WHERE Date_depart > '$currentDateTime' AND is_deleted = false";  
                                        $result2 = $conn->query($sql2);

                                        if ($result2->num_rows > 0) {
                                            while ($row = $result2->fetch_assoc()) {
                                                echo "<tbody>";
                                                echo "<tr>";
                                                echo "<td>" . $row['ID_trajet'] . "</td>";
                                                echo "<td>" . $row['ID_bus'] . "</td>";
                                                echo "<td>" . $row['Ville_depart'] . "</td>";
                                                echo "<td>" . $row['Ville_arrivee'] . "</td>";
                                                echo "<td>" . $row['Date_depart'] . "</td>";
                                                echo "<td>" . $row['Date_arrivee'] . "</td>";
                                                echo "<td>" . $row['Prix'] . "</td>";
                                                echo "<td>" . $row['Places_disponibles'] . "</td>";
                                                echo "<td>" . $row['is_deleted'] . "</td>";
                                                echo "<td>" . $row['is_enabled'] . "</td>";
                                                echo "<td>" . $row['created_at'] . "</td>";
                                                echo "<td>" . $row['updated_at'] . "</td>";
                                                echo "<td>" . $row['deleted_at'] . "</td>";
                                                echo "<td>
                                                        <a href='?ID_trajet=" . $row['ID_trajet'] . "' data-toggle='modal' data-target='#mod'><i class='fas fa-edit'></i></a> |
                                                        <a href='?ID_trajet=" . $row['ID_trajet'] . "'><i class='fas fa-trash'></i></a> 
                                                    </td>";
                                                echo "<td>
                                                        <a href='liste.php?ID_trajet=" . $row['ID_trajet'] . "' style='text-decoration:none;'><i class='fas fa-list'></i> liste</a> 
                                                    </td>";
                                                echo "</tr>";
                                                echo "</tbody>";
                                            }
                                        }

                                        $conn->close();
                                    ?>

                                </table>
                            </div>
                        </div>

                    </div>
    
                </div>
            </div>

            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Admin 2024</span>
                    </div>
                </div>
            </footer>

        </div>

    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <div class="modal fade" id="trajet" tabindex="-1" width="80%" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ajouter un trajet</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" class="user" action="trajets.php">
                        <div class="form-group">
                            <?php
                                include "../conn.php";

                                $sql = "SELECT ID_bus, immatriculation, Type_bus FROM bus WHERE is_deleted = 'FALSE'";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    echo "<select required class='form-control' name='busnum'>";
                                    
                                    echo "<option style='display: none;'>Choisissez l'immatriculation du bus</option>";
                                    while ($row = $result->fetch_assoc()) {
                                        $id = $row['ID_bus'];
                                        $nom = $row['immatriculation'];
                                        $type = $row['Type_bus'];
        
                                        echo "<option value='$id'>$nom ( $type ) </option>";
                                    }

                                    echo '</select>';
                                } else {
                                    echo 'Aucun element trouve.';
                                }
                                $conn->close();

                            ?>
                        </div>
                        <div class="form-group">
                            <input type="text" required class="form-control form-control-user" name="ville_depart" placeholder="ville de depart">
                        </div>
                        <div class="form-group">
                            <input type="text" required class="form-control form-control-user" name="ville_arrive" placeholder="ville d'arrivée">
                        </div>
                        <label>Date et heure de depart</label>

                        <div class="form-group row">

                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <input type="date" class="form-control form-control-user" id="date" name="date_depart" required>
                            </div>
                            <div class="col-sm-6">
                                <input type="time" class="form-control form-control-user" name="heure_depart" required>
                            </div>
                        </div>
                        <label>Date et heure d'arrivée  </label>

                        <div class="form-group row">

                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <input type="date" class="form-control form-control-user" id="date1" name="date_arrive" required>
                            </div>
                            <div class="col-sm-6">
                                <input type="time" class="form-control form-control-user" name="heure_arrive" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <input type="number" min="1" required class="form-control form-control-user" name="prix" placeholder="Prix du trajet">
                        </div>
                        <div class="form-group">
                            <input type="number" min="1" placeholder="Nombre de places disponibles" name="dispo" disabled class="form-control form-control-user">
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-danger" type="button" data-dismiss="modal">Annuler</button>
                            <input class="btn btn-success" type="submit" value="Ajouter" href="#" name="trajetadd">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="mod" tabindex="-1" width="80%" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ajouter un bus</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                <form method="POST" class="user" action="trajets.php">
                        <div class="form-group">
                            <?php
                                include "../conn.php";

                                $sql = "SELECT ID_bus, immatriculation, Type_bus FROM bus WHERE is_deleted = 'FALSE'";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    echo "<select required class='form-control' name='num_bus'>";
                                    
                                    echo "<option style='display: none;'>Choisissez le numero de bus</option>";
                                    while ($row = $result->fetch_assoc()) {
                                        $id = $row['ID_bus'];
                                        $nom = $row['immatriculation'];
                                        $type = $row['Type_bus'];
        
                                        echo "<option value='$id'>$nom ( $type ) </option>";
                                    }

                                    echo '</select>';
                                } else {
                                    echo 'Aucun element trouve.';
                                }
                                $conn->close();

                            ?>
                        </div>
                        <div class="form-group">
                            <input type="text" required class="form-control form-control-user" name="ville_depart" placeholder="ville de depart">
                        </div>
                        <div class="form-group">
                            <input type="text" required class="form-control form-control-user" name="ville_arrive" placeholder="ville d'arrivée">
                        </div>
                        <label>Date et heure de depart</label>

                        <div class="form-group row">

                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <input type="date" class="form-control form-control-user" id="date" name="date_depart" required>
                            </div>
                            <div class="col-sm-6">
                                <input type="time" class="form-control form-control-user" name="heure_depart" required>
                            </div>
                        </div>
                        <label>Date et heure d'arrivée  </label>

                        <div class="form-group row">

                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <input type="date" class="form-control form-control-user" id="date" name="date_arrive" required>
                            </div>
                            <div class="col-sm-6">
                                <input type="time" class="form-control form-control-user" name="heure_arrive" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <input type="number" min="1" required class="form-control form-control-user" name="prix" placeholder="Prix du trajet">
                        </div>
                        <div class="form-group">
                            <input type="number" min="1" placeholder="Nombre de places disponibles" disabled required class="form-control form-control-user">
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-danger" type="button" data-dismiss="modal">Annuler</button>
                            <input class="btn btn-success" type="submit" value="Ajouter" href="#" name="trajetmod">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="mod" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modifier un trajet</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    Etes vous sur de vouloir supprimer cet utilisateur?
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="button" data-dismiss="modal">Annuler</button>
                    <a class="btn btn-success" href="#">Modifier</a>
                </div>
            </div>
        </div>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <script src="js/sb-admin-2.min.js"></script>

    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <script src="js/demo/datatables-demo.js"></script>
    <script src="../sweetalert/sweetalert2.all.min.js"></script>

    <script>
        function disablePastDates() {
        var today = new Date().toISOString().split('T')[0];
        document.getElementById('date').setAttribute('min', today);
        var today1 = new Date().toISOString().split('T')[0];
        document.getElementById('date1').setAttribute('min', today1);
        
        }
        var dateInput = document.getElementById('date');
        var date1Input = document.getElementById('date1');

        dateInput.addEventListener('change', function() {
        var selectedDate = new Date(dateInput.value);
        
        var currentDate = new Date();
        currentDate.setHours(0, 0, 0, 0);
        
        date1Input.removeAttribute('min');
        
        if (selectedDate > currentDate) {
            var minDate = selectedDate.toISOString().split('T')[0];
            
            date1Input.min = minDate;
        }
        });
    </script>

    <?php

if (isset($_POST['trajetadd'])) {
    $num_bus = $_POST['busnum'];
    $ville_depart = $_POST['ville_depart'];
    $ville_arrivee = $_POST['ville_arrive'];
    $date_depart = $_POST['date_depart'];
    $date_arrive = $_POST['date_arrive'];
    $heure_depart = $_POST['heure_depart'];
    $heure_arrive = $_POST['heure_arrive'];
    $prix = $_POST['prix'];

    include "../conn.php";

    $stmt = $conn->prepare("SELECT Capacite FROM bus WHERE ID_bus = ?");
    $stmt->bind_param("s", $num_bus);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $places = $row['Capacite'];
    } else {
        echo "<script>alert('Le bus spécifié n\'existe pas.');</script>";
        $stmt->close();
        $conn->close();
        exit;
    }

    $stmt->close();

    $formatdate = date('Y-m-d', strtotime($date_depart));
    $formatheure = date('H:i:s', strtotime($heure_depart));
    $formatdate1 = date('Y-m-d', strtotime($date_arrive));
    $formatheure1 = date('H:i:s', strtotime($heure_arrive));
    $depart = $formatdate . ' ' . $formatheure;
    $arrive = $formatdate1 . ' ' . $formatheure1;

    $stmtCheck = $conn->prepare("SELECT COUNT(*) FROM Trajets WHERE ID_bus = ? AND Ville_depart = ? AND Ville_arrivee = ? AND Date_depart = ? AND Date_arrivee = ? AND is_deleted = 'FALSE'");
    $stmtCheck->bind_param("issss", $num_bus, $ville_depart, $ville_arrivee, $depart, $arrive);
    $stmtCheck->execute();
    $stmtCheck->bind_result($count);
    $stmtCheck->fetch();
    $stmtCheck->close();

    if ($count > 0) {
        echo "
            <script>
                alert('Le trajet $ville_depart - $ville_arrivee existe déjà !');
            </script>
        ";
        $conn->close();
        exit;
    }
    $stmt = $conn->prepare("INSERT INTO Trajets (ID_bus, Ville_depart, Ville_arrivee, Date_depart, Date_arrivee, Prix, Places_disponibles, is_deleted, is_enabled, created_at, updated_at, deleted_at) VALUES (?, ?, ?, ?, ?, ?, ?, false, true, NOW(), NOW(), null)");
    $stmt->bind_param("issssii", $num_bus, $ville_depart, $ville_arrivee, $depart, $arrive, $prix, $places);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "
            <script>
                alert('Enregistrement du trajet réussi !');
            </script>
        ";
    } else {
        echo "
            <script>
                alert('Erreur lors de l\'enregistrement du trajet !');
            </script>
        ";
    }

    $stmt->close();
    $conn->close();
}
?>
</body>

</html>