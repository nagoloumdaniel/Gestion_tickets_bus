<nav class="navbar navbar-expand-lg navbar-light bg-light d-lg-block" style="z-index: 2000;position: fixed;top: 0;left: 0;width: 100%;">
      <div class="container-fluid">
        <a class="navbar-brand nav-link" href="accueil.php">
          <img src="img/OIP (11).jpeg" alt="" style="width: 20%;">
          <strong> TRAVEL</strong>
        </a>
        
        <button class="navbar-toggler" type="button" data-mdb-collapse-init data-mdb-target="#navbarExample01"
          aria-controls="navbarExample01" aria-expanded="false" aria-label="Toggle navigation">
          <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarExample01">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item active">
              <a class="nav-link" aria-current="page" href="accueil.php"><strong>Accueil</strong></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="accueil.php#about"><strong>A Propos de Nous</strong></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="accueil.php#agence"><strong>Nos Partenaires</strong></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="trajet.php"><strong>Nos Destinations</strong></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="carte.php"><strong>Voir la Carte du Cameroun</strong></a>
            </li>
          </ul>

          <ul class="navbar-nav list-inline">

            <li class="nav-item align-items-center d-flex" >
              <i class="fas fa-sun"></i>
              <div class="ms-2 form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="themingSwitcher" />
              </div>
              <i class="fas fa-moon"></i>
            </li>

            <li class="nav-item align-items-center d-flex dropdown">
              <a
                data-mdb-dropdown-init
                class="nav-link dropdown-toggle hidden-arrow"
                href="#"
                id="navbarDropdownMenuLink1"
                role="button"
                aria-expanded="false"
              >
                <i class="fas fa-bell fa-lg"></i>
                <span class="badge bg-danger badge-dot"></span>
              </a>
              <ul
                class="dropdown-menu dropdown-menu-end"
                aria-labelledby="navbarDropdownMenuLink1"
                style="max-width: 500px;"
                p-4 text-muted
              >
                <?php
                  $oda = date("H:i:s");
                  $idus = $_SESSION['ID_utilisateur'];
                  $today = date('Y-m-d H:i:s');            
                ?>
                <li><h5 style="font-weight:bold;" class="dropdown-header text-primary">Centre de Notifications</h5></li>
                
                <?php
                  include "conn.php";

                  $sql = "SELECT Utilisateurs.*, Reservations.*, Trajets.*, Paiements.*
                  FROM Utilisateurs
                  INNER JOIN Reservations ON Utilisateurs.ID_utilisateur = Reservations.ID_utilisateur
                  INNER JOIN Trajets ON Reservations.ID_trajet = Trajets.ID_trajet
                  INNER JOIN Paiements ON Reservations.ID_reservation = Paiements.ID_reservation
                  WHERE Utilisateurs.ID_utilisateur = $idus AND Reservations.is_deleted = 'false' AND Trajets.is_deleted = 'false' AND Trajets.Date_depart > '$today'";

                  $result = $conn->query($sql); 
                  if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                      echo'<li>';
                      echo'<a class="dropdown-item" style="font-weight:bold;" href="#">';
                      echo'<p class="mt-0 mb-4">'. $oda .'</p>';
                      echo '<p><i class="fas fa-question-circle me-2 text-warning"></i>' . $row["Ville_depart"] . ' - ' . $row["Ville_arrivee"] . '</p>';
                      echo'<p>Vous avez un trajet prevu pour le '. $row["Date_depart"] .' .</p>';
                      echo'<p>Votre place est: '. $row["place"] .'</p>';
                      echo'</a>';
                      echo'</li>';
                    }
                  }else
                  {
                    echo'<li><span class="dropdown-item">Aucune notification</span></li>';
                  }

                  $conn->close();
                ?>
              </ul>
            </li>

            <li class="nav-item dropdown me-3 me-lg-1 align-items-center d-flex">
              <a
                class="nav-link"
                href="chariot.php"
                role="button"
                aria-expanded="false"
              >
                <i class="fas fa-cart-shopping fa-lg"></i>
                <span class="badge bg-danger badge-dot"></span>
                <?php
                  include "conn.php"; 

                  $login = $_SESSION['user'];
                  $email = $_SESSION['passwd'];

                  $query = "SELECT profil FROM Utilisateurs WHERE login = '$login' AND Password = '$email'";

                  $result = $conn->query($query);

                  if ($result->num_rows > 0) {
                      $row = $result->fetch_assoc();
                      $profil = $row["profil"];

                  }
                    
                  $conn->close();
                ?>
              </a>
            </li>
            
            <li class="nav-item dropdown me-3 me-lg-1">
              <a data-mdb-dropdown-init class="nav-link d-sm-flex dropdown-toggle hidden-arrow  align-items-sm-center" id="navbarDropdownMenuLink" role="button" aria-expanded="false" href="#">
                <img
                  src="<?php echo $profil;?>"
                  class="rounded-circle"
                  height="26"
                  alt="profil"
                  loading="lazy"
                />
                <strong class="d-sm-block ms-1">
                <?php
                    if (isset($_SESSION['user'])){
                        echo $_SESSION['user']; 
                    }else{
                      session_unset ();

                      session_destroy ();

                      header ('location: index.php');
                      exit();
                    }
                    
                ?>
                </strong>
              </a>
              <ul
                class="dropdown-menu dropdown-menu-end"
                aria-labelledby="navbarDropdownMenuLink"
              >
                <li>
                  <a class="dropdown-item" style="font-weight:bold;" href="#">Profil</a>
                </li>

                <li>
                  <a class="dropdown-item" style="font-weight:bold;" href="deconnexion.php">Se deconnecter</a>
                </li>
              </ul>
            </li>

          </ul>
        </div>
      </div>
    </nav>
