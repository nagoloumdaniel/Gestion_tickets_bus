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
        display: grid;
        place-items: center;
        min-height: 100vh;
        color: #0a0a0a;
        line-height: 1;
      } 

      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }
  
      .container {
        background-color: #fff;
        position: relative;
        display: grid;
        grid-template-columns: 400px 690px;
        border-radius: 5px;
        box-shadow: 0 15px 20px rgba(0, 0, 0, 0.356);
      }
      .container img {
        width: 400px;
        height: 100%;
        border-radius: 5px 0 0 5px;
      }
      .container .btn {
        position: absolute;
        bottom: -30px;
        right: -30px;
        border: none;
        outline: none;
        display: flex;
        align-items: center;
        background-color: #3b71ca;
        color: #fff;
        padding: 17px 40px;
        font-size: 1rem;
        text-transform: uppercase;
        border-radius: 5px;
        cursor: pointer;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.294);
      }
      .container .btn i {
        margin-left: 20px;
        font-size: 1.5rem;
      }
      
      .container__text {
        padding: 40px 40px 0;
      }
      .container__text h1 {
        color: #351897;
        font-weight: 400;
      }
      .container__text .container__text__star span {
        font-size: 0.8rem;
        color: #ffa800;
        margin: -5px 0 20px;
      }
      .container__text p {
        font-size: 0.9rem;
      }
      .container__text .container__text__timing {
        display: flex;
        margin: 20px 0 10px;
      }
      .container__text .container__text__timing .container__text__timing_time {
        margin-right: 40px;
      }
      .container__text .container__text__timing h2 {
        margin-bottom: 5px;
        font-size: 1rem;
        font-weight: 400;
        color: #818189;
      }
      .container__text .container__text__timing p {
        color: #351897;
        font-weight: bold;
        font-size: 1.2rem;
      }
    </style>
     <?php
        include "conn.php";

        if (isset($_GET['ID_trajet'])) {
            $id = $_GET['ID_trajet'];
            $_SESSION['ID_trajet'] = $id;
        }

        $sql23 = "SELECT Trajets.*, Bus.*
        FROM Trajets 
        INNER JOIN Bus ON Trajets.ID_bus = Bus.ID_bus 
        WHERE Trajets.is_deleted = 'false' 
        AND Trajets.ID_trajet = '$id'";
        $result24 = $conn->query($sql23);

        if ($result24->num_rows > 0) {
            while ($row3 = $result24->fetch_assoc()) {

              $villeDepart = $row3['Ville_depart'];
              $villeArrivee = $row3['Ville_arrivee'];
              $dateDepart = $row3['Date_depart'];
              $dateArrive = $row3['Date_arrivee'];
              $prix = $row3['Prix'];
              $placesDisponibles = $row3['Places_disponibles'];
              $photo = $row3['Photo'];
              $immatriculation = $row3['immatriculation'];
              $capacite = $row3['Capacite'];
              $trajet = $row3['ID_trajet'];
              $typebus = $row3['Type_bus'];

            }
        }
    
        $conn->close();
        
        if(isset($_SESSION['paiementerrorplace'])){
          $placeerronee = $_SESSION['placeerronee'];
          echo "
            <script>
              alert('La place $placeerronee que vous avez choisi est deja occupé !');
            </script>
          ";
        }
    ?>
</head>
<body>
    <header>
        <?php include "a.php" ?>
    </header>

    <section class="text-center mt-5 mb-5" >
      <h3 class="card-title mt-5"><strong class="text-primary" id="agence" >Plus d'informations...</strong></h3>
    </section>
    <hr class="my-5" />
    <div class="container">
      <img
        src="admin/<?php echo $photo ;?>"
        alt="bus"
      />
      <div class="container__text">
        
        <?php
        echo '      <h2 class="text-primary">Trajet : ' . $villeDepart . ' - ' . $villeArrivee . '</h2>';

        ?>
        <div class="container__text__star">
          <span class="fa fa-star checked"></span>
          <span class="fa fa-star checked"></span>
          <span class="fa fa-star checked"></span>
          <span class="fa fa-star checked"></span>
          <span class="fa fa-star checked"></span>
        </div>
        <?php
          echo '      <ul>';
          echo '            <li class="card-text"><h5>Date de départ : <strong class="text-primary">' . $dateDepart . '</strong></h5></li>';
          echo '            <li class="card-text"><h5>Date d\'arrivée : <strong class="text-primary">' . $dateArrive . '</strong></h5></li>';
          echo '            <li class="card-text"><h5>Nombre de places disponibles : <strong class="text-primary">' . $placesDisponibles . '</strong></h5></li>';
          echo '      </ul>';
        ?>
        <div class="container__text__timing">
          <div class="container__text__timing_time">
            <h2>Immatriculation du bus :</h2>
            <p><?php echo $immatriculation ?></p>
          </div>
          <div class="container__text__timing_time">
            <h2>Type de bus :</h2>
            <p><?php echo "$typebus ($capacite places)" ?></p>
          </div>
          <div class="container__text__timing_time">
            <h2>Cout du trajet :</h2>
            <p class="text-success"><?php echo $prix ?> XAF</p>
          </div>
        </div>
        <form method="post" action="paiement.php" class="mb-4 d-flex justify-content-left">
          <div class="form-outline " style="width: 175px;" data-mdb-input-init >
              <input type="number" min="1" max="<?php echo $capacite?>" required name="place" class="text-dark border-primary form-control" />
              <label class="form-label text-primary">Choisir une place... </label>
          </div>
          <button class="btn" name="chariot" type="submit">Ajouter au chariot <i class="fa fa-arrow-right"></i></button>
        </form>   
      </div>
    </div>
    <footer>
      <div class="text-center text-body p-3" style="background-color: rgba(0, 0, 0, 0.2);margin-top: 100px;bottom:0;">
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


</body>
</html>