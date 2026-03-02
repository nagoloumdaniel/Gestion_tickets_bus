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

    <title>Admin - Utilisateurs</title>

                    <?php include "tete.php"; ?>

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Utilisateurs</h1>
                    </div>

                    <?php include "card.php"; ?>

                <div class="container-fluid">   

                    <div class="card shadow mb-4">

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Login</th>
                                            <th>Adresse_email</th>
                                            <th>Password</th>
                                            <th>Role</th>
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

                                        if (isset($_GET['ID_utilisateur'])) {

                                            $id = $_GET['ID_utilisateur'];

                                            $is_enabled = FALSE;
                                            $is_deleted = TRUE;
                                            $updated_at = date("Y-m-d H:i:s");
                                            $deleted_at = date("Y-m-d H:i:s");

                                            $sql1 = "SELECT Login FROM utilisateurs WHERE ID_utilisateur = ?";
                                            $stmt1 = $conn->prepare($sql1);
                                            $stmt1->bind_param("i", $id);
                                            $stmt1->execute();
                                            $result = $stmt1->get_result()->fetch_assoc()['Numero_bus'];

                                            $sql = "UPDATE utilisateurs SET is_deleted = ?, is_enabled = ?, updated_at = ?, deleted_at = ? WHERE ID_utilisateur = ?";
                                            $stmt = $conn->prepare($sql);

                                            if ($stmt === false) {
                                                die("Erreur de préparation de la requête : " . $conn->error);
                                            }

                                            $stmt->bind_param("iissi", $is_deleted, $is_enabled, $updated_at, $deleted_at, $id);

                                            if ($stmt->execute()) {
                                                echo "<script>alert('L\'utilisateur $result a été supprimée avec succès !');</script>";
                                            } else {
                                                echo "Erreur lors de la suppression de la ligne : " . $stmt->error;
                                            }

                                            $stmt1->close();
                                            $stmt->close();
                                        }

                                        $sql = "SELECT * FROM utilisateurs WHERE is_deleted = 'false'";
                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tbody>";
                                                echo "<tr>";
                                                echo "<td>" . $row['ID_utilisateur'] . "</td>";
                                                echo "<td>" . $row['Login'] . "</td>";
                                                echo "<td>" . $row['Adresse_email'] . "</td>";
                                                echo "<td> ********** </td>";
                                                echo "<td>" . $row['Role'] . "</td>";
                                                echo "<td>" . $row['profil'] . "</td>";
                                                echo "<td>" . $row['is_deleted'] . "</td>";
                                                echo "<td>" . $row['is_enabled'] . "</td>";
                                                echo "<td>" . $row['created_at'] . "</td>";
                                                echo "<td>" . $row['updated_at'] . "</td>";
                                                echo "<td>" . $row['deleted_at'] . "</td>";
                                                echo "<td>
                                                        <a href='?ID_utilisateur=" . $row['ID_utilisateur'] . "' data-toggle='modal' data-target='#mod'><i class='fas fa-edit'></i></a> |       
                                                        <a href='?ID_utilisateur=" . $row['ID_utilisateur'] . "'><i class='fas fa-trash'></i></a> 
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

</body>

</html>