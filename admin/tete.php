<link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../sweetalert/sweetalert2.min.css">


</head>

<body id="page-top" onload="disablePastDates()">

    <div id="wrapper">

        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="admin.php">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Admin</div>
            </a>

            <hr class="sidebar-divider my-0">

            <li class="nav-item active">
                <a class="nav-link" href="admin.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Tableau de bord</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="Users.php">
                    <i class="fas fa-solid fa-user"></i>
                    <span>Utilisateurs</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Agences.php">
                    <i class="fas fa-solid fa-building"></i>
                    <span>Agences</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Bus.php">
                    <i class="fas fa-solid fa-bus"></i>
                    <span>Bus</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Reservation.php">
                    <i class="fas fa-solid fa-check"></i>
                    <span>Reservations</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="trajets.php">
                    <i class="fas fa-solid fa-thumbtack"></i>
                    <span>Trajets</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="paiement.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Paiements</span></a>
            </li>

            <hr class="sidebar-divider d-none d-md-block">

            <li class="nav-item">
                <a class="nav-link" href="../accueil.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Acceder au site</span></a>
            </li>

            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                <?php
                                    if (isset($_SESSION['useradmin'])){
                                        echo $_SESSION['useradmin']; 
                                    }else{
                                        session_unset ();

                                        session_destroy ();

                                        header ('location: index.php');
                                        exit();
                                    }
                                    include "../conn.php";

                                    $login = $_SESSION['useradmin'];
                                    $email = $_SESSION['pass'];

                                    $query = "SELECT Administrateurs.profil FROM Administrateurs INNER JOIN Utilisateurs ON Utilisateurs.ID_utilisateur = Administrateurs.ID_utilisateur WHERE Utilisateurs.ID_utilisateur = Administrateurs.ID_utilisateur";

                                    $result = $conn->query($query);

                                    if ($result->num_rows > 0) {
                                        $row = $result->fetch_assoc();
                                        $profil = $row["profil"];
                                    }
                                    
                                    $conn->close();
                                ?>
                                </span>
                                <img class="img-profile rounded-circle"
                                    src="<?php echo $profil ; ?>">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#profil">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400" ></i>
                                    Profil
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Parametres
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="deconnexion.php" >
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Se deconnecter
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>

                <div class="container-fluid">
                <div class="modal fade" id="profil" tabindex="-1" width="80%" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modifier la photo de profil</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" class="user" action="" id="uploadForm" enctype="multipart/form-data">

                    <div class="form-group">
                        <input type="file" required class="form-control" id="photoInput" name="photo" accept="image/*">
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-danger" type="button" data-dismiss="modal">Annuler</button>
                        <input class="btn btn-success" type="submit" value="Modifier" name="profil">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('uploadForm').addEventListener('submit', function(e) {
        e.preventDefault();

        var fileInput = document.getElementById('photoInput');
        var file = fileInput.files[0];
        var formData = new FormData();
        formData.append('photo', file);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'upload.php', true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        var filePath = response.filePath;
                        displayPhoto(filePath);
                        $('#profil').modal('hide');
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
                if (isset($_POST['profil'])) {
                    include "../conn.php";
                    
                    $uploadDir = 'img1/';
                    
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    
                    $targetFile = $uploadDir . basename($_FILES['photo']['name']);
                    $uploadSuccess = move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile);
                    
                    if ($uploadSuccess) {
                        $filePath = $targetFile;
                        
                        $updated_at = date("Y-m-d H:i:s");
                        
                        $sql = "UPDATE Administrateurs SET profil = ?, updated_at = ? WHERE ID_administrateur = ?";
                        $stmt = $conn->prepare($sql);
                        
                        if ($stmt) {
                            $stmt->bind_param("ssi", $filePath, $updated_at, $_SESSION['ID_administrateur']);
                            
                            if ($stmt->execute()) {
                                echo "<script>alert('Modification du profil réussie !');</script>";
                            } else {
                                echo "<script>alert('Erreur lors de la modification du profil : " . $stmt->error . "');</script>";
                            }
                            
                            $stmt->close();
                        } else {
                            echo "<script>alert('Erreur lors de la préparation de la requête : " . $conn->error . "');</script>";
                        }
                    } else {
                        echo "<script>alert('Erreur lors du téléversement de la photo.');</script>";
                    }
                    
                    $conn->close();
                }
                ?>