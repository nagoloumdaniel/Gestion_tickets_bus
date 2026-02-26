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
    <title>Bienvenue sur Travel</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.0.0/css/all.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/mdb.min.css" />
    <link rel="stylesheet" href="sweetalert/sweetalert2.min.css">
    <link rel="stylesheet" href="styler.css" />
    <style>
      body {
        font-family: "Raleway", sans-serif;
        font-optical-sizing: auto;
        font-weight: <weight>;
        font-style: normal;
        color: #0a0a0a;
        line-height: 1;
      } 

      #introCarousel,
      .carousel-inner,
      .carousel-item,
      .carousel-item.active {
        height: 100vh;
      }

      .carousel-item:nth-child(1) {
        background-image: url('img/11.jpg');
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center center;
      }

      .carousel-item:nth-child(2) {
        background-image: url('img/pexels-element-digital-1051073.jpg');
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center center;
      }

      .carousel-item:nth-child(3) {
        background-image: url('img/12.jpg');
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center center;
      }

      @media (min-width: 992px) {
        #introCarousel {
          margin-top: -58.59px;
        }
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
  <header>

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
              

                <?php

                  if (isset($_SESSION['user'])){
  
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
                      echo '
                      <li class="nav-item dropdown me-3 me-lg-1 align-items-center d-flex">
                        <a
                          class="nav-link"
                          href="chariot.php"
                          role="button"
                          aria-expanded="false"
                        >
                          <i class="fas fa-cart-shopping fa-lg"></i>
                          <span class="badge bg-danger badge-dot"></span>
                          </a>
                      </li>
                      <li class="nav-item dropdown me-3 me-lg-1">

                      <a data-mdb-dropdown-init class="nav-link d-sm-flex dropdown-toggle hidden-arrow  align-items-sm-center" id="navbarDropdownMenuLink" role="button" aria-expanded="false" href="#">
                        <img
                          src="'.$profil.'"
                          class="rounded-circle"
                          height="26"
                          alt="profil"
                          loading="lazy"
                        />
                        <strong class="d-sm-block ms-1">
                          '.$_SESSION['user'].'
                        </strong>
                      </a>
                      <ul
                        class="dropdown-menu dropdown-menu-end"
                        aria-labelledby="navbarDropdownMenuLink" 
                      >
                        <li>
                          <a class="dropdown-item" style="font-weight:bold;" href="#" data-toggle="modal" data-target="#profil">Profil</a>
                        </li>
        
                        <li>
                          <a class="dropdown-item" style="font-weight:bold;" href="deconnexion.php">Se deconnecter</a>
                        </li>
                      </ul>
                    </li>';
                  }else{
                    echo '<a class="btn btn-outline-dark btn-lg" data-mdb-ripple-init href="index.php" role="button">Se connecter</a>';
                  }
                    
              ?>

          </ul>
        </div>
      </div>
    </nav>      
    <div id="introCarousel" class="mt-5 carousel slide carousel-fade shadow-2-strong" data-mdb-carousel-init>
      <div class="carousel-indicators">
        <li data-mdb-target="#introCarousel" data-mdb-slide-to="0" class="active"></li>
        <li data-mdb-target="#introCarousel" data-mdb-slide-to="1"></li>
        <li data-mdb-target="#introCarousel" data-mdb-slide-to="2"></li>
      </div>

      <div class="carousel-inner">
        <div class="carousel-item active">
          <div class="mask" style="background-color: rgba(0, 0, 0, 0.6);">
            <div class="d-flex justify-content-center align-items-center h-100">
              <div class="text-white text-center" data-mdb-theme="dark">
                <h1 class="mb-3">Bienvenue sur <b class="text-primary">TRAVEL</b> !</h1>
                <h5 class="mb-4">Voyagez sans tracas. Reservez vos tickets de bus en un clic !</h5>
                <a class="btn btn-outline-light btn-lg m-4" data-mdb-ripple-init href="trajet.php"
                  role="button">Reserver maintenant</a>
              </div>
            </div>
          </div>
        </div>
        
        <div class="carousel-item">
          <div class="mask" style="background-color: rgba(0, 0, 0, 0.6);">
            <div class="d-flex justify-content-center align-items-center h-100">
              <div class="text-white text-center" data-mdb-theme="dark">
                <h1 class="mb-3">Explorez le monde en bus !</h1>
                <h5 class="mb-4">Reservez des maintenant et embarquez pour l'aventure !</h5>
                <a class="btn btn-outline-light btn-lg m-2" data-mdb-ripple-init href="trajet.php"
                  role="button" rel="nofollow">Reserver maintenant</a>
                <a class="btn btn-outline-light btn-lg m-2" data-mdb-ripple-init href="#agences"
                   role="button">Nos Agences de Voyages</a>
              </div>
            </div>
          </div>
        </div>
        
        <div class="carousel-item">
          <div class="mask" style="background-color: rgba(0, 0, 0, 0.6);">
            <div class="d-flex justify-content-center align-items-center h-100">
              <div class="text-white text-center" data-mdb-theme="dark">
                <h1 class="mb-3">Simplifiez vos deplacements !</h1>
                <h5 class="mb-4">Reservez vos trajets en bus facilement et economisez du temps et de l'argent</h5>
                <a class="btn btn-outline-light btn-lg m-2" data-mdb-ripple-init href="trajet.php"
                  role="button" >Reserver maintenant</a>
                <a class="btn btn-outline-light btn-lg m-2" data-mdb-ripple-init href="trajet.php"
                  role="button">Trajets disponibles</a>
              </div>
            </div>
          </div>
        </div>
        
        <a class="carousel-control-prev" href="#introCarousel" role="button" data-mdb-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#introCarousel" role="button" data-mdb-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>
    </header>

  <main class="mt-5 d-flex justify-content-center align-items-center" id="about">
    <div class="container">
      <section>     
        <hr class="my-5" />     
        <section class="text-center  mb-5" data-mdb-theme>
          <h3 class="card-title"><strong class="text-primary">A Propos de Nous...</strong></h3>
        </section>
        <hr class="my-5" />

        <div class="row g-0 bg-body-tertiary position-relative">
          <div class="col-md-6 mb-md-0 p-md-4">
            <img
              src="img/R (2).jpeg"
              class="w-100"
              alt="voyage"
            />
          </div>
          <div class="col-md-6 p-4 ps-md-0">
          <br><br><h3 class="mt-0 text-primary"><strong>A PROPOS DE NOUS</strong></h3>
            <h5><p class="text-body"><br>
              Trouvez facilement les Horaires, les itinéraires et les tarifs de bus disponibles. Notre équipe est là pour répondre à toutes vos questions et vous offrir un soutien personnalisé. Evitez les files d'attentes et les réservations de dernières minutes en choisissant notre plateforme conviviale. Votre sécurité et la confidentialité de vos données sont notre priorité. Réserver dès maintenant et préparez-vous à embarquer pour de nouveaux horizons avec <strong class="text-primary">TRAVEL</strong></p>
            </p>
            <br><a href="trajet.php" class="btn btn-outline-primary link">Reserver maintenant</a><h5>
          </div>
        </div>
      </section>

      <hr class="my-5" />
      <section class="text-center  mb-5" >
        <h3 class="card-title"><strong class="text-primary" id="agence" >Nos Partenaires...</strong></h3>
      </section>
      <hr class="my-5" />

      <section class="articles">

        <?php
          include "conn.php";

          $age = "SELECT * FROM agences WHERE is_deleted = 'false' ";
          $result2 = $conn->query($age);

          if ($result2->num_rows > 0) {
              while ($row = $result2->fetch_assoc()) {
                
                echo '<article>';
                echo '<div class="card"  data-mdb-theme>';
                echo '<div class="article-wrapper">';
                echo '<figure class="text-center">';
                echo '<img src="admin/'. $row['profil'] .'" alt="" />';
                echo '</figure>';
                echo '<div class="article-body">';
                echo "  <h2 class='text-center card-title text-primary'>" . $row['Nom_agence'] . "</h2>";
                echo "  <ul><p> 
                          Adresse: " . $row['Adresse_agence'] . "</p></ul>";
                echo "  <ul><p> 
                          Contact: " . $row['Telephone_agence'] . "</p></ul>";
                echo "  <ul><p> 
                          Email: " . $row['Email_agence'] . "</p></ul>";
                
                echo '</div>
                      </div>
                      </div>';
                echo '</article>';

              }
              
          }

          $conn->close();

        ?>
      </section>

      <hr class="my-5" />

    </div>
  </main>
  <footer>
    <div class="text-center text-body p-3" style="background-color: rgba(0, 0, 0, 0.2);margin-top: 100px;bottom:0;">
        © 2024 Copyright:
        <a class="text-primary" href="accueil.php">Travel</a>
    </div>
  </footer>
    <script src="sweetalert/sweetalert2.all.min.js"></script>
    <script type="text/javascript" src="js/mdb.umd.min.js"></script>

    <?php
    if (!isset($_SESSION['connecteduser'])) {
        echo "
            <script>
                Swal.fire({
                  position: 'center',
                  icon: 'success',
                  title: 'Bienvenue " . $_SESSION['user'] . "',
                  showConfirmButton: false,
                  timer: 1500
                });
            </script>
        ";
    }
    $_SESSION['connecteduser'] = true;
    ?>
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