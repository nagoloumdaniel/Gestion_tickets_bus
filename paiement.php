<?php 
session_start(); 
unset($_SESSION['payerorange']);
unset($_SESSION['payercredit']);
unset($_SESSION['search']);
if (isset($_SESSION['user'])){

}else{
session_unset ();

session_destroy ();

header ('location: index.php');
exit();
}

?>

<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>Paiement du trajet</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="sweetalert/sweetalert2.min.css">
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css'>
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css'>
  <link rel="stylesheet" href="./style1.css">

</head>
<body>
  <style>
    a:hover {
      color: #246eea;
    }
    body {
      font-family: "Raleway", sans-serif;
      font-optical-sizing: auto;
      font-weight: <weight>;
      font-style: normal;
      display: grid;
      min-height: 100vh;
      color: #0a0a0a;
      line-height: 1;
    }
  </style>
     <?php
      include "conn.php";

      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $ID_trajet = $_SESSION['ID_trajet'];
        $sql = "SELECT Trajets.*, Bus.*
                FROM Trajets 
                INNER JOIN Bus ON Trajets.ID_bus = Bus.ID_bus 
                WHERE Trajets.is_deleted = 'false' 
                AND Trajets.ID_trajet = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $ID_trajet);
        $stmt->execute();
        $result = $stmt->get_result();
 
        if ($result->num_rows > 0) {
          $row = $result->fetch_assoc();

          $villeDepart = $row['Ville_depart'];
          $villeArrivee = $row['Ville_arrivee'];
          $dateDepart = $row['Date_depart'];
          $dateArrivee = $row['Date_arrivee'];
          $_SESSION['Prix'] = $row['Prix'];
          $_SESSION['Capacite'] = $row['Capacite'];
          $placesDisponibles = $row['Places_disponibles'];
          $photo = $row['Photo'];
          $trajet = $row['ID_trajet'];
          $num = $row['immatriculation'];
          $place2 = $_POST['place'];
          $capacite = $row['Capacite'];
          $typebus = $row['Type_bus'];

          $ID_utilisateur = $_SESSION['ID_utilisateur'];
          $Statut = "En attente";
          $Date_reservation = date("Y-m-d H:i:s");
          $is_deleted = false;
          $is_enabled = true;
          $created_at = date("Y-m-d H:i:s");
          $updated_at = date("Y-m-d H:i:s");
          $deleted_at = null;
          
          if(isset($_SESSION['ID_trajet']) && !isset($_SESSION['aaaa'])){
            $sqlCheck = "SELECT COUNT(*) AS count FROM Reservations WHERE ID_trajet = ? AND place = ? AND is_deleted = 0";
            $stmtCheck = $conn->prepare($sqlCheck);
            $stmtCheck->bind_param("ii", $trajet, $place);
            $stmtCheck->execute();
            $resultCheck = $stmtCheck->get_result();
            $rowCheck = $resultCheck->fetch_assoc();
            $count = $rowCheck["count"];

            if ($count == 0) {
              $sql = "INSERT INTO Reservations (ID_utilisateur, ID_trajet, Statut, Date_reservation, place, is_deleted, is_enabled, created_at, updated_at, deleted_at)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
              $stmt = $conn->prepare($sql);
              $stmt->bind_param("iisssiisss", $ID_utilisateur, $trajet, $Statut, $Date_reservation, $place2, $is_deleted, $is_enabled, $created_at, $updated_at, $deleted_at);

              if ($stmt->execute()) {
                $_SESSION['idReservation'] = $stmt->insert_id;
                $_SESSION['ajouteaupanier'] = true;
                $_SESSION['aaaa'] = true;

                echo "
                  <script>
                    alert('Ajouté au panier !');
                  </script>
                ";
              } else {
                echo "
                  <script>
                    alert('Erreur lors de l'insertion de la réservation !');
                  </script>
                ";
              }
            } elseif ($count > 0 && !isset($_SESSION['ajouteaupanier'])) {
              $_SESSION['paiementerrorplace'] = true;
              $_SESSION['placeerronee'] = $place;
              header('location: plus.php');
              exit();
            } else {
              echo "
                <script>
                  alert('Vous avez reserver la place $place !');
                </script>
              ";
            }
          }
        }
      }

      if (isset($_GET['ID_reservation'])) {

        $id = $_GET['ID_reservation'];
        $_SESSION['idReservation'] = $_GET['ID_reservation'];

        $sqlID = "SELECT * FROM Reservations WHERE ID_reservation = ? ";

        $stmtTR = $conn->prepare($sqlID);
        $stmtTR->bind_param("i", $id);
        $stmtTR->execute();
        $resultTR = $stmtTR->get_result();

        if ($resultTR->num_rows > 0) {
          while ($rowT = $resultTR->fetch_assoc()) {
            $_SESSION['ID_trajet'] = $rowT["ID_trajet"];
            $place1 = $rowT["place"];
          }
        }
        $stmtTR->close();

        $sql23 = "SELECT Trajets.*, Bus.*
        FROM Trajets 
        INNER JOIN Bus ON Trajets.ID_bus = Bus.ID_bus 
        WHERE Trajets.is_deleted = 'false' 
        AND Trajets.ID_trajet = '{$_SESSION['ID_trajet']}'";
        $result24 = $conn->query($sql23);

        if ($result24->num_rows > 0) {
          while ($row3 = $result24->fetch_assoc()) {

            $villeDepart = $row3['Ville_depart'];
            $villeArrivee = $row3['Ville_arrivee'];
            $dateDepart = $row3['Date_depart'];
            $dateArrivee = $row3['Date_arrivee'];
            $_SESSION['Prix'] = $row3['Prix'];
            $capacite = $row3['Capacite'];
            $placesDisponibles = $row3['Places_disponibles'];
            $photo = $row3['Photo'];
            $trajet = $row3['ID_trajet'];
            $num = $row3['immatriculation'];
            $typebus = $row3['Type_bus'];
            
          }
        }

      }
      
      $conn->close();
    ?>
  <header>
    <div class="container">
      <div class="navigation">
        <a href="accueil.php">
          <i class="fa fa-arrow-left mr-1"></i> Retourner à l'accueil
        </a>
        <div class="secure">
          <i class="icon icon-shield"></i>
          <span>Paiement securisé</span>
        </div>
        <div class="secure">
          <a href="chariot.php">
            <i class="fa fa-cart-shopping mr-1"></i> Aller au panier <i class="fa fa-arrow-right ml-1"></i>
          </a>
        </div>
      </div>
      <div class="notification" style="color: #4984ea;">
        Terminer les achats...
      </div>
    </div>
  </header>
  <section class="content">

    <div class="details shadow">
      <div class="details__item">

        <div class="item__image">
          <img class="iphone" src="admin/<?php echo $photo ;?>" alt="bus">
        </div>
        <div class="item__details">
          <div class="">
            <?php echo '<h2 style="color: #4984ea;">Trajet : ' . $villeDepart . ' - ' . $villeArrivee . '</h2>'; ?>
          </div>
          <div class="item__price " style="color:#FCDC12 ;">
            <span class="fa fa-star checked"></span>
            <span class="fa fa-star checked"></span>
            <span class="fa fa-star checked"></span>
            <span class="fa fa-star checked"></span>
            <span class="fa fa-star checked"></span>
          </div>
          <div class="item__quantity">
            <strong>Nombre de billets: </strong><strong style="color: #4984ea;">1</strong>
          </div>
          <div class="item__description">
            <?php
            echo '      <h3><ul>';
            echo '            <li class="card-text">Date de départ : <strong style="color: #4984ea;">' . $dateDepart . '</strong></li>';
            echo '            <li class="card-text">Date d\'arrivée : <strong style="color: #4984ea;">' . $dateArrivee . '</strong></li>';
            echo '            <li class="card-text">Nombre de places du bus : <strong style="color: #4984ea;">' . $capacite . '</strong></li>';
            echo '            <li class="card-text">Nombre de places disponibles : <strong style="color: #4984ea;">' . $placesDisponibles . '</strong></li>';
            echo '            <li class="card-text">Type de bus : <strong style="color: #4984ea;">' . $typebus . '</strong></li>';
            echo '            <li class="card-text">Immatriculation de bus : <strong style="color: #4984ea;">' . $num . '</strong></li>';
            if (isset($_SESSION['aaaa'])) {
              echo '            <li class="card-text">Place : <strong style="color: #4984ea;">' . $place2 . '</strong></li>';
            }
            if (isset($_GET['ID_reservation'])) {
              echo '            <li class="card-text">Place : <strong style="color: #4984ea;">' . $place1 . '</strong></li>';
            }
            echo '      </ul></h3>';
            ?>
          </div>
          <div class="item__price" style="color: #4984ea;">
            <strong>Cout total: <?php echo $_SESSION['Prix'] ;?> XAF</strong>
          </div>
          
        </div>
      </div>

    </div>

    <div class="container">
      <div class="payment">
        <div class="payment__title">
          Methode de Paiement...
        </div>
        <div class="payment__types">
          <div class="payment__type payment__type--cc" onclick="activatePaymentType(this)" id="creditcard">
            <i class="icon icon-credit-card"></i>Carte credit
          </div>
          <div class="payment__type active" onclick="activatePaymentType(this)" id="orangemoney">
            <img src="img/or.png" style="width: 15%;" alt="">Orange Money
          </div>
        </div>

        <div class="payment__info">
          <div class="payment__cc">
            <div class="payment__title">
              <i class="icon icon-user"></i>Informations Personnelles...
            </div>
            <form id="creditc" class="credit" action="chariot.php" method="POST">
              <div class="form__cc">
                <div class="row">
                  <div class="field">
                    <div class="title">Numero de la carte de credit</div>
                    <input type="number" maxlength="19" name="carte" required class="input txt text-validated" placeholder="4542 9931 9292 2293" />
                  </div>
                </div>
                <div class="row">
                  <div class="field small">
                    <div class="title">Date d'expiration
                    </div>
                    <input type="number" required value="03" min="1" max="12" class="input ddl"/>
                    <input type="number" required value="15" min="1" max="31" class="input ddl"/>
                  </div>
                  <div class="field small">
                    <div class="title">CCV
                    </div>
                    <input type="number" required maxlength="3" placeholder="485" class="input txt text-validated" />
                  </div>
                </div>
                <div class="row">
                  <div class="field">
                    <div class="title">Nom de la carte
                    </div>
                    <input type="text" name="nom_carte" required class="input txt text-validated" placeholder="Fotso Arthur..."/>
                  </div>
                </div>
                <div class="actions">
                  <button name="credit" type="submit" class="btn action__submit">
                    confirmer le paiement
                  </button>
                </div>
              </div>
            </form>
            <form id="orangem" class="orange" action="chariot.php" method="POST">
              <div class="form__cc">
                <div class="row">
                  <div class="field">
                    <div class="title">Numero Orange
                    </div>
                    <input type="tel" min="1" required maxlength="12" name="numerorange" class="input txt text-validated" placeholder="6XX XX XX XX" />
                  </div>
                </div>
                <div class="actions">
                  <button name="orange" type="submit" class="btn action__submit">
                    confirmer le paiement
                  </button> 
                </div>
              </div>
            </form>
          </div>
          
          <div class="payment__shipping">
            <div class="payment__title">
              <i class="icon icon-plane"></i> Informations de paiement...
            </div>
            <div class="details__user">
              <div class="user__name"><?php echo $_SESSION['user']; ?></div>
              <div class="user__name"><?php echo $_SESSION['mail']; ?></div>
              <div class="user__address">Cameroun</div>
            </div>

          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="actions">
        <strong><a href="trajet.php" class="backBtn"> <i class="fa fa-arrow-left" style="margin-right: 10px;"></i> Retourner au choix du trajet</a></strong>
      </div>
    </div>
  </section>
  
</div>

<script src="sweetalert/sweetalert2.all.min.js"></script>
<script>
 function activatePaymentType(element) {
  var paymentTypes = document.getElementsByClassName('payment__type');
  for (var i = 0; i < paymentTypes.length; i++) {
    paymentTypes[i].classList.remove('active');
  }
  element.classList.add('active');

  var orangebtn = document.getElementById("orangemoney");
  var ccbtn = document.getElementById("creditcard");

  var orangeform = document.getElementById("orangem");
  var ccform = document.getElementById("creditc");

  function creditcart() {
    orangeform.style.display = "none";
    ccform.style.display = "block";
  }
  
  function money() {    
    orangeform.style.display = "block";
    ccform.style.display = "none";
  }
  
  ccbtn.addEventListener("click", creditcart);
  orangebtn.addEventListener("click", money);
  }
  var orangebtn = document.getElementById("orangemoney");
  var ccbtn = document.getElementById("creditcard");

  var orangeform = document.getElementById("orangem");
  var ccform = document.getElementById("creditc");

  orangeform.style.display = "block";
  ccform.style.display = "none";
</script>
<style>

.payment__type.active {
  font-weight: bold;
}

.orange {
  display: none;
}

.credit{
  display: none;
}
</style>

</body>
</html>
