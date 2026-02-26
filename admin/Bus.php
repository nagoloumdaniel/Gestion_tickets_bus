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

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Admin - Bus</title>

                    <?php include "tete.php"; ?>

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Bus</h1>
                    </div>

                    <?php include "card.php"; ?>

                <div class="container-fluid">   

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <a href="#" data-toggle="modal" data-target="#bus" class="btn btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-plus"></i>
                                </span>
                                <span class="text">Ajouter un bus</span>
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Immatriculation</th>
                                            <th>Capacite</th>
                                            <th>Photo</th>
                                            <th>Type_bus</th>
                                            <th>is_deleted</th>
                                            <th>is_enabled</th>
                                            <th>created_at</th>
                                            <th>updated_at</th>
                                            <th>deleted_at</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <?php
                                        include "../conn.php";

                                        if (isset($_GET['ID_bus'])) {

                                            $id = $_GET['ID_bus'];

                                            $is_enabled = FALSE;
                                            $is_deleted = TRUE;
                                            $updated_at = date("Y-m-d H:i:s");
                                            $deleted_at = date("Y-m-d H:i:s");

                                            $sql1 = "SELECT immatriculation FROM bus WHERE ID_bus = ?";
                                            $stmt1 = $conn->prepare($sql1);
                                            $stmt1->bind_param("i", $id);
                                            $stmt1->execute();
                                            $result = $stmt1->get_result()->fetch_assoc()['immatriculation'];

                                            $sql = "UPDATE bus SET is_deleted = ?, is_enabled = ?, updated_at = ?, deleted_at = ? WHERE ID_bus = ?";
                                            $stmt = $conn->prepare($sql);

                                            if ($stmt === false) {
                                                die("Erreur de préparation de la requête : " . $conn->error);
                                            }

                                            $stmt->bind_param("iissi", $is_deleted, $is_enabled, $updated_at, $deleted_at, $id);

                                            if ($stmt->execute()) {
                                                echo "<script>alert('L\'agence $result a été supprimée avec succès !');</script>";
                                            } else {
                                                echo "Erreur lors de la suppression de la ligne : " . $stmt->error;
                                            }

                                            $stmt1->close();
                                            $stmt->close();
                                        }

                                        $sql2 = "SELECT * FROM bus WHERE is_deleted = 'false'";
                                        $result2 = $conn->query($sql2);

                                        if ($result2->num_rows > 0) {
                                            while ($row = $result2->fetch_assoc()) {
                                                echo "<tbody>";
                                                echo "<tr>";
                                                echo "<td>" . $row['ID_bus'] . "</td>";
                                                echo "<td>" . $row['immatriculation'] . "</td>";
                                                echo "<td>" . $row['Capacite'] . "</td>";
                                                echo "<td>" . $row['Photo'] . "</td>";
                                                echo "<td>" . $row['Type_bus'] . "</td>";
                                                echo "<td>" . $row['is_deleted'] . "</td>";
                                                echo "<td>" . $row['is_enabled'] . "</td>";
                                                echo "<td>" . $row['created_at'] . "</td>";
                                                echo "<td>" . $row['updated_at'] . "</td>";
                                                echo "<td>" . $row['deleted_at'] . "</td>";
                                                echo "<td>
                                                        <a href='?ID_bus=" . $row['ID_bus'] . "' data-toggle='modal' data-target='#mod'><i class='fas fa-edit'></i></a> |
                                                        <a href='?ID_bus=" . $row['ID_bus'] . "'><i class='fas fa-trash'></i></a> 
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

    <div class="modal fade" id="bus" tabindex="-1" width="80%" role="dialog" aria-labelledby="exampleModalLabel"
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
                    <form method="POST" class="user" action="Bus.php" id="uploadForm" enctype="multipart/form-data">

                        <div class="form-group">
                            <input type="text" required class="form-control form-control-user" name="immatriculation" placeholder="immatriculation du bus">
                        </div>
                        <div class="form-group">
                            <input type="number" min="1" required class="form-control form-control-user" name="num_place" placeholder="Nombre de places">
                        </div>
                        <div class="form-group">
                            <input type="file" required class="form-control" id="photoInput" name="photo" accept="image/*">
                        </div>
                        <div class="form-group">
                            <select required class='form-control' name='Type_bus'>
                                <option style='display: none;'>Choisissez le type de bus</option>
                                <option value="VIP">VIP</option>
                                <option value="Standard">Standard</option>
                            </select>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-danger" type="button" data-dismiss="modal">Annuler</button>
                            <input class="btn btn-success" type="submit" value="Ajouter" href="#" name="busadd">
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
                    <h5 class="modal-title" id="exampleModalLabel">Modifier un utilisateur</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" class="user" action="Bus.php" id="uploadForm1" enctype="multipart/form-data">

                        <div class="form-group">
                            <input type="text" min="1" required class="form-control form-control-user" name="immatriculation" placeholder="immatriculation du bus">
                        </div>
                        <div class="form-group">
                            <input type="number" min="1" required class="form-control form-control-user" name="num_place" placeholder="Nombre de places">
                        </div>
                        <div class="form-group">
                            <input type="file" required class="form-control" id="photoInput" name="photo" accept="image/*">
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-primary" type="button" data-dismiss="modal">Annuler</button>
                            <input class="btn btn-success" type="submit" value="Modifier" href="#" name="modifierbus">
                        </div>
                    </form>
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
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            <?php       
                $numero = $_POST['immatriculation'];
                $capacite = $_POST['num_place'];
                $type = $_POST['Type_bus'];
            ?>

            var fileInput = document.getElementById('photoInput');
            var file = fileInput.files[0];
            var formData = new FormData();
            formData.append('photo', file);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'Bus.php', true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            var filePath = response.filePath;
                            displayPhoto(filePath);
                        } else {
                            console.log(response.message);
                        }
                    } else {
                        console.log('Erreur lors de la requête AJAX');
                    }
                }
            };
            xhr.send(formData);
        });

    </script>
    <script>
        document.getElementById('uploadForm1').addEventListener('submit', function(e) {
            e.preventDefault();

            var fileInput = document.getElementById('photoInput');
            var file = fileInput.files[0];
            var formData = new FormData();
            formData.append('photo', file);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'Bus.php', true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            var filePath = response.filePath;
                            displayPhoto(filePath);
                        } else {
                            console.log(response.message);
                        }
                    } else {
                        console.log('Erreur lors de la requête AJAX');
                    }
                }
            };
            xhr.send(formData);
        });

    </script>

    <?php

    if (isset($_POST['busadd'])) {

        include "../conn.php";

        $sqlCheck = "SELECT COUNT(*) FROM bus WHERE immatriculation = ? AND is_deleted = 'FALSE'";
        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->bind_param("s", $numero);
        $stmtCheck->execute();
        $stmtCheck->bind_result($count);
        $stmtCheck->fetch();
        $stmtCheck->close();

        if ($count > 0) {
            echo "<script>alert('L\\'immatriculation de Bus $numero existe déjà !');</script>";
        } else {
            $uploadDir = 'uploads/bus/';

            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true); 
            }

            $targetFile = $uploadDir . basename($_FILES['photo']['name']);
            $uploadSuccess = move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile);

            if ($uploadSuccess) {
                $filePath = $targetFile;

                $sql = "INSERT INTO Bus (immatriculation, Capacite, Photo, Type_bus, is_deleted, is_enabled, created_at, updated_at, deleted_at) VALUES (?, ?, ?, ?, 0, 1, NOW(), NOW(), null)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("siss", $numero, $capacite, $filePath, $type);
                
                if ($stmt->execute()) {
                    echo "<script>alert('Enregistrement du bus réussi !');</script>";
                } else {
                    echo "<script>alert('Erreur lors de l\\'enregistrement du bus : " . $stmt->error . "');</script>";
                }
                
                $stmt->close();
            } else {
                echo "<script>alert('Erreur lors du téléversement de la photo.');</script>";
            }
        }
        
        $conn->close();
    }

    if (isset($_POST['modifierbus'])) {
        
        include "../conn.php";

        if (isset($_GET['ID_agence'])) {
            $id = $_GET['ID_agence'];

            $sql = "SELECT * FROM Agences WHERE ID_agence = ? AND is_deleted = 'FALSE'";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();

            if ($result) {
                $nom_agence = $result['Nom_agence'];
                $adresse_agence = $result['Adresse_agence'];
                $telephone_agence = $result['Telephone_agence'];
                $email_agence = $result['Email_agence'];
                $profil = $result['profil'];
                $is_deleted = $result['is_deleted'];
                $is_enabled = $result['is_enabled'];
            } 
            $stmt->close();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $uploadDir = 'uploads/agences/';

            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true); 
            }

            $targetFile = $uploadDir . basename($_FILES['photo']['name']);
            $uploadSuccess = move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile);

            if ($uploadSuccess) {
                $filePath = $targetFile;

                $nom_agence = $_POST['nom_agence'];
                $adresse_agence = $_POST['adresse_agence'];
                $telephone_agence = $_POST['telephone_agence'];
                $email_agence = $_POST['email_agence'];
                $updated_at = date("Y-m-d H:i:s");

                $sql = "UPDATE Agences SET Nom_agence = ?, Adresse_agence = ?, Telephone_agence = ?, Email_agence = ?, profil = ?, updated_at = ? WHERE ID_agence = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssssi", $nom_agence, $adresse_agence, $telephone_agence, $email_agence, $filePath, $updated_at, $id);

                if ($stmt->execute()) {
                    echo "<script>alert('Les données de l'agence ont été mises à jour avec succès.');</script>";
                } else {
                    echo "<script>alert('Erreur lors de la mise à jour des données de l'agence.');</script>";
                }

                $stmt->close();
            } else {
                echo "<script>alert('Erreur lors du téléversement de la photo.');</script>";
            }
        }
        $conn->close();
    }
        
?>

</body>

</html>