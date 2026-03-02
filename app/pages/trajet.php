<?php session_start();
 unset($_SESSION['aaaa']); 
 ?>
<!DOCTYPE html>
<html lang="en">
<head>  
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>Nos trajets</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.0.0/css/all.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/mdb.min.css" />
    <link rel="stylesheet" href="search.css" />
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
    </style>
    
</head>
<body>
    <header>
        <?php include "a.php" ?>
    </header>
    
    <main class="mt-5 d-flex justify-content-center align-items-center" id="search">
      <div class="container">
        <section class="text-center  mb-5" >
          <h3 class="mb-5 mt-5"><strong class="text-primary" id="agence" >Rechercher un Trajet...</strong></h3>
          <div class="recherche-animee">
            <div class="recherche-horizontale">
              <form action="#" method="POST">
                <input type="date" id="date-depart" name="date-depart" placeholder="Date de départ">
                <input type="time" id="heure-depart" name="heure-depart" placeholder="Heure de départ">
                <?php
                  include "conn.php";
                  $today = date('Y-m-d H:i:s');
                  $sql = "SELECT Ville_depart FROM Trajets WHERE Date_depart > '$today' AND is_deleted = 'false'";
                  $result = $conn->query($sql);
                  
                  if ($result->num_rows > 0) {
                      echo '<select id="ville-depart" name="ville-depart">';
                      echo '<option value="" style="display:none">Ville de départ</option>';
                  
                      $elementsAffiches = array();
                  
                      while ($row = $result->fetch_assoc()) {
                          $villeDepart = $row['Ville_depart'];
                  
                          if (!in_array($villeDepart, $elementsAffiches)) {
                              $elementsAffiches[] = $villeDepart;
                  
                              echo '<option value="' . $villeDepart . '">' . $villeDepart . '</option>';
                          }
                      }
                  
                      echo '</select>';
                  }

                  $sql = "SELECT Ville_arrivee FROM Trajets WHERE Date_depart > '$today' AND is_deleted = 'false'";
                  $result = $conn->query($sql);

                  if ($result->num_rows > 0) {
                      echo '<select id="ville-arrivee" name="ville-arrivee">';
                      echo '<option value="" style="display:none">Ville d\'arrivée</option>';

                      $elementsAffiches = array();

                      while ($row = $result->fetch_assoc()) {
                          $villeArrivee = $row['Ville_arrivee'];

                          if (!in_array($villeArrivee, $elementsAffiches)) {
                              $elementsAffiches[] = $villeArrivee;

                              echo '<option value="' . $villeArrivee . '">' . $villeArrivee . '</option>';
                          }
                      }

                      echo '</select>';
                  }
                  $conn->close();

                ?>

                <button type="submit" class="btn-recherche">
                  <i class="fa fa-search"></i>
                </button>
              </form>
            </div>
          </div>
        </section>
      </div>
    </main>

    <hr class="my-5" />
    <section class="text-center  mb-5" >
      <h3 ><strong class="text-primary" id="agence" >Les Trajets Disponibles...</strong></h3>
    </section>
    <hr class="my-5" />
    <section class="mt-5 d-flex justify-content-center">

      <?php
        include "conn.php";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
          $_SESSION['search'] = true;

          $dateDepart = $_POST["date-depart"];
          $heureDepart = $_POST["heure-depart"];
          $villeDepart = $_POST["ville-depart"];
          $villeArrivee = $_POST["ville-arrivee"];
      
          $sql = "SELECT t.ID_trajet, t.ID_bus, t.Ville_depart, t.Ville_arrivee, t.Date_depart, t.Date_arrivee, t.Prix, t.Places_disponibles, b.Photo
          FROM Trajets t
          INNER JOIN Bus b ON t.ID_bus = b.ID_bus
          WHERE t.Ville_depart = '$villeDepart' AND t.Ville_arrivee = '$villeArrivee' AND t.Date_depart >= '$dateDepart $heureDepart' AND t.is_deleted = 'false'
          ORDER BY t.Date_depart ASC";
          $result = $conn->query($sql);
      
          if ($result->num_rows > 0) {
            echo '<div class="container">';
            echo '<div class="row">';

            while ($row = $result->fetch_assoc()) {
              $villeDepart = $row['Ville_depart'];
              $villeArrivee = $row['Ville_arrivee'];
              $dateDepart = $row['Date_depart'];
              $dateArrivee = $row['Date_arrivee'];
              $prix = $row['Prix'];
              $placesDisponibles = $row['Places_disponibles'];
              $photo = $row['Photo'];
              $trajet = $row['ID_trajet'];

              echo '<div class=" mt-3 col-sm-6">';
              echo '  <div class="card-horizontal">';
              echo '    <div class="image-container">';
              echo '      <img src="admin/' . $photo . '" alt="Image du trajet ' . $villeDepart . ' - ' . $villeArrivee . '">';
              echo '    </div>';
              echo '    <div class="content-container">';
              echo '      <strong><h4 class="text-primary">Trajet : ' . $villeDepart . ' - ' . $villeArrivee . '</h4></strong>';
              echo '      <br><ul class="text-body"><b>';
              echo '        <li>Date de départ : ' . $dateDepart . '</li>';
              echo '        <br><li>Date d\'arrivée : ' . $dateArrivee . '</li>';
              echo '        <br><li>Coût du trajet : ' . number_format($prix, 2) . ' XAF</li>';
              echo '        <br><li>Places disponibles : ' . $placesDisponibles . '</li>';
              echo '      </b></ul>';
              echo '      <a href="plus.php?ID_trajet=' . $trajet . '" class="btn btn-outline-primary" style="float:right;">En savoir plus</a>';
              echo '    </div>';
              echo '  </div>';
              echo '</div>';

            }

            echo '</div>';
            echo '</div>';

          } else {
            echo '<p>Aucun trajet disponible pour le moment.</p>';
          }
        }

        if (!isset($_SESSION['iddelagence']) && !isset($_SESSION['search'])) {
          $today = date('Y-m-d H:i:s');

          $sql = "SELECT t.ID_trajet, t.ID_bus, t.Ville_depart, t.Ville_arrivee, t.Date_depart, t.Date_arrivee, t.Prix, t.Places_disponibles, b.Photo
                  FROM Trajets t
                  INNER JOIN Bus b ON t.ID_bus = b.ID_bus
                  WHERE t.Date_depart > '$today' AND t.is_deleted = 'false'
                  ORDER BY t.Date_depart ASC";

          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
            echo '<div class="container">';
            echo '<div class="row">';

            while ($row = $result->fetch_assoc()) {
              $villeDepart = $row['Ville_depart'];
              $villeArrivee = $row['Ville_arrivee'];
              $dateDepart = $row['Date_depart'];
              $dateArrivee = $row['Date_arrivee'];
              $prix = $row['Prix'];
              $placesDisponibles = $row['Places_disponibles'];
              $photo = $row['Photo'];
              $trajet = $row['ID_trajet'];

              echo '<div class=" mt-3 col-sm-6">';
              echo '  <div class="card-horizontal">';
              echo '    <div class="image-container">';
              echo '      <img src="admin/' . $photo . '" alt="Image du trajet ' . $villeDepart . ' - ' . $villeArrivee . '">';
              echo '    </div>';
              echo '    <div class="content-container">';
              echo '      <strong><h4 class="text-primary">Trajet : ' . $villeDepart . ' - ' . $villeArrivee . '</h4></strong>';
              echo '      <br><ul class="text-body"><b>';
              echo '        <li>Date de départ : ' . $dateDepart . '</li>';
              echo '        <br><li>Date d\'arrivée : ' . $dateArrivee . '</li>';
              echo '        <br><li>Coût du trajet : ' . number_format($prix, 2) . ' XAF</li>';
              echo '        <br><li>Places disponibles : ' . $placesDisponibles . '</li>';
              echo '      </b></ul>';
              echo '      <a href="plus.php?ID_trajet=' . $trajet . '" class="btn btn-outline-primary" style="float:right;">En savoir plus</a>';
              echo '    </div>';
              echo '  </div>';
              echo '</div>';

            }

            echo '</div>';
            echo '</div>';

          } else {
            echo '<p>Aucun trajet disponible pour le moment.</p>';
          }
        }
        $conn->close();
      ?>

    </section>
    <hr class="my-5" />
    <footer>
        <div class="text-center text-body p-3">
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
    <script>
      function disablePastDates() {
        var today = new Date().toISOString().split('T')[0];
        document.getElementById('date-depart').setAttribute('min', today);
      }

      disablePastDates();
    </script>

</body>
</html>