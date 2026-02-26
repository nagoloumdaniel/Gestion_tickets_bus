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

    <title>Admin - Paiements</title>

                    <?php include "tete.php"; ?>

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Paiements</h1>
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
                                            <th>ID_reservation</th>
                                            <th>Montant_paye</th>
                                            <th>Methode_paiement</th>
                                            <th>Date_paiement</th>
                                            <th>Statut_paiement</th>
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

                                        if (isset($_GET['ID_paiement'])) {
                                            
                                            $id = $_GET['ID_paiement'];

                                            $is_enabled = FALSE;
                                            $is_deleted = TRUE;
                                            $updated_at = date("Y-m-d H:i:s");
                                            $deleted_at = date("Y-m-d H:i:s");

                                            $sql = "UPDATE paiements SET is_deleted = ?, is_enabled = ?, updated_at = ?, deleted_at = ? WHERE ID_paiement = ?";
                                            $stmt = $conn->prepare($sql);

                                            if ($stmt === false) {
                                                die("Erreur de préparation de la requête : " . $conn->error);
                                            }

                                            $stmt->bind_param("iissi", $is_deleted, $is_enabled, $updated_at, $deleted_at, $id);

                                            if ($stmt->execute()) {
                                                echo "<script>alert('Le paiement a été supprimée avec succès !');</script>";
                                            } else {
                                                echo "Erreur lors de la suppression de la ligne : " . $stmt->error;
                                            }

                                            $stmt1->close();
                                            $stmt->close();
                                        }

                                        $sql = "SELECT * FROM paiements WHERE is_deleted = 'false'";
                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tbody>";
                                                echo "<tr>";
                                                echo "<td>" . $row['ID_paiement'] . "</td>";
                                                echo "<td>" . $row['ID_reservation'] . "</td>";
                                                echo "<td>" . $row['Montant_paye'] . "</td>";
                                                echo "<td>" . $row['Methode_paiement'] . "</td>";
                                                echo "<td>" . $row['Date_paiement'] . "</td>";
                                                echo "<td>" . $row['Statut_paiement'] . "</td>";
                                                echo "<td>" . $row['is_deleted'] . "</td>";
                                                echo "<td>" . $row['is_enabled'] . "</td>";
                                                echo "<td>" . $row['created_at'] . "</td>";
                                                echo "<td>" . $row['updated_at'] . "</td>";
                                                echo "<td>" . $row['deleted_at'] . "</td>";
                                                echo "<td>
                                                        <a href='?ID_paiement=" . $row['ID_paiement'] . "' data-toggle='modal' data-target='#mod'><i class='fas fa-edit'></i></a> |
                                                        <a href='?ID_paiement=" . $row['ID_paiement'] . "'><i class='fas fa-trash'></i></a> 
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