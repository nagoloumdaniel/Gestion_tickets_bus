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

    <title>Admin - Agences</title>

                    <?php include "tete.php"; ?>

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Agences</h1>
                    </div>

                    <?php include "card.php"; ?>

                <div class="container-fluid">   

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <a href="#" data-toggle="modal" data-target="#agence" class="btn btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-plus"></i>
                                </span>
                                <span class="text">Ajouter une Agence</span>
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nom Agence</th>
                                            <th>Adresse Agence</th>
                                            <th>Telephone_Agence</th>
                                            <th>Email_Agence</th>
                                            <th>profil</th>
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

                                        if (isset($_GET['ID_agence'])) {
                                            
                                            $id = $_GET['ID_agence'];

                                            $is_enabled = FALSE;
                                            $is_deleted = TRUE;
                                            $updated_at = date("Y-m-d H:i:s");
                                            $deleted_at = date("Y-m-d H:i:s");

                                            $sql1 = "SELECT Nom_agence FROM agences WHERE ID_agence = ?";
                                            $stmt1 = $conn->prepare($sql1);
                                            $stmt1->bind_param("i", $id);
                                            $stmt1->execute();
                                            $result = $stmt1->get_result()->fetch_assoc()['Nom_agence'];

                                            $sql = "UPDATE agences SET is_deleted = ?, is_enabled = ?, updated_at = ?, deleted_at = ? WHERE ID_agence = ?";
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

                                        $sql2 = "SELECT * FROM agences WHERE is_deleted = 'false' ";
                                        $result2 = $conn->query($sql2);

                                        if ($result2->num_rows > 0) {
                                            while ($row = $result2->fetch_assoc()) {
                                                echo "<tbody>";
                                                echo "<tr>";
                                                echo "<td>" . $row['ID_agence'] . "</td>";
                                                echo "<td>" . $row['Nom_agence'] . "</td>";
                                                echo "<td>" . $row['Adresse_agence'] . "</td>";
                                                echo "<td>" . $row['Telephone_agence'] . "</td>";
                                                echo "<td>" . $row['Email_agence'] . "</td>";
                                                echo "<td>" . $row['profil'] . "</td>";
                                                echo "<td>" . $row['is_deleted'] . "</td>";
                                                echo "<td>" . $row['is_enabled'] . "</td>";
                                                echo "<td>" . $row['created_at'] . "</td>";
                                                echo "<td>" . $row['updated_at'] . "</td>";
                                                echo "<td>" . $row['deleted_at'] . "</td>";
                                                echo "<td>
                                                        <a href='?ID_agence=" . $row['ID_agence'] . "' data-toggle='modal' data-target='#mod'><i class='fas fa-edit'></i></a> |
                                                        <a href='?ID_agence=" . $row['ID_agence'] . "'><i class='fas fa-trash'></i></a> 
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

    <div class="modal fade" id="agence" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ajouter une agence</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" class="user" action="Agences.php" id="uploadForm" enctype="multipart/form-data">
                        <div class="form-group">
                            <input type="text" class="form-control form-control-user" name="nom_agence" placeholder="Nom de l'agence">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control form-control-user" name="adresse" placeholder="Adresse de l'agence">
                        </div>
                        <div class="form-group">
                            <input type="number" min="1" class="form-control form-control-user" name="tel" placeholder="Telephone de l'agence">
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control form-control-user" name="email" placeholder="Email de l'agence">
                        </div>
                        <div class="form-group">
                            <input type="file" class="form-control " id="photoInput" name="photo" accept="image/*">
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-danger" type="button" data-dismiss="modal">Annuler</button>
                            <input class="btn btn-success" type="submit" value="Ajouter" href="#" name="agenceadd">
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
                    <h5 class="modal-title" id="exampleModalLabel">Modifier une agence</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" class="user" action="" id="uploadForm1" enctype="multipart/form-data">
                        <div class="form-group">
                            <input type="text" class="form-control form-control-user" name="nom_agence" placeholder="Nom de l'agence">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control form-control-user" name="adresse_agence" placeholder="Adresse de l'agence">
                        </div>
                        <div class="form-group">
                            <input type="number" min="1" class="form-control form-control-user" name="telephone_agence" placeholder="Telephone de l'agence">
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control form-control-user" name="email_agence" placeholder="Email de l'agence">
                        </div>
                        <div class="form-group">
                            <input type="file" class="form-control" id="photoInput" name="photo" accept="image/*">
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-primary" type="button" data-dismiss="modal">Annuler</button>
                            <input class="btn btn-success" type="submit" value="Modifier" href="#" name="modifier">
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
                $nomAgence = $_POST['nom_agence'];
                $adresseAgence = $_POST['adresse'];
                $telephoneAgence = $_POST['tel'];
                $emailAgence = $_POST['email'];
            ?>

            var fileInput = document.getElementById('photoInput');
            var file = fileInput.files[0];
            var formData = new FormData();
            formData.append('photo', file);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'Agences.php', true);
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
            xhr.open('POST', 'Agences.php', true);
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

        function displayPhoto(filePath) {
            var photoContainer = document.getElementById('photoContainer');
            photoContainer.innerHTML = '<img src="' + filePath + '" alt="Photo">';
        }

    </script>

    <?php

    if (isset($_POST['agenceadd'])) {
        include "../conn.php";

        $sqlCheck = "SELECT COUNT(*) FROM Agences WHERE Nom_agence = ? AND is_deleted = 'false'";
        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->bind_param("s", $nomAgence);
        $stmtCheck->execute();
        $stmtCheck->bind_result($count);
        $stmtCheck->fetch();
        $stmtCheck->close();

        if ($count > 0) {
            echo "<script>alert('L\\'agence $nomAgence existe déjà !');</script>";
        } else {
            $uploadDir = 'uploads/agences/';

            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true); 
            }

            $targetFile = $uploadDir . basename($_FILES['photo']['name']);
            $uploadSuccess = move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile);

            if ($uploadSuccess) {
                $filePath = $targetFile;

                $sql = "INSERT INTO Agences (Nom_agence, Adresse_agence, Telephone_agence, Email_agence, profil, is_deleted, is_enabled, created_at, updated_at, deleted_at) VALUES (?, ?, ?, ?, ?, false, true, NOW(), NOW(), null)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssss", $nomAgence, $adresseAgence, $telephoneAgence, $emailAgence, $filePath);
                $stmt->execute();
                $stmt->close();

                if ($stmt->num_rows > 0) {
                    echo "<script>alert('Enregistrement de l\\'agence réussi.');</script>";
                } else {
                    echo "<script>alert('Erreur lors de l\\'enregistrement de l\\'agence dans la base de données.');</script>";
                }

            } else {
                echo "<script>alert('Erreur lors du téléversement de la photo.');</script>";
            }
        }
        $conn->close();

    }

    if (isset($_POST['modifier'])) {
        
        include "../conn.php";

        if (isset($_GET['ID_agence'])) {
            $id = $_GET['ID_agence'];

            $sql = "SELECT * FROM Agences WHERE ID_agence = ?";
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