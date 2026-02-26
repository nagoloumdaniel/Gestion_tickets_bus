<?php 
session_start(); 

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Facture</title>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.0.0/css/all.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/mdb.min.css" />
  <link rel="stylesheet" href="search.css" />
  <link rel="stylesheet" href="sweetalert/sweetalert2.min.css">

  <style>

.navbar .nav-link {
  color: #000000 !important;
}

.navbar .nav-link:hover {
  color: blue !important;
}
* {
  box-sizing: border-box;
}
html {
  height: 100%;
}
body {
  min-height: 100%;
  margin: 0;
  display: flex;
  flex-flow: column nowrap;
  justify-content: center;
  align-items: sretch;
  background: #fff;
  color: #666;
  -webkit-print-color-adjust: exact;
  font-family: "Raleway", sans-serif;
  font-optical-sizing: auto;
  font-weight: <weight>;
  font-style: normal;
  line-height: 1;
}
.floating-button {
  position: fixed;
  bottom: 20px;
  right: 20px;
  width: 200px;
  height: 60px;
  background-color: #007bff;
  border-radius: 15px 15px 15px 15px;
  text-align: center;
  box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.2);
}

.floating-button a {
  display: block;
  width: 100%;
  height: 100%;
  line-height: 60px;
  color: #ffffff;
  text-decoration: none;
}

.floating-button a:hover {
  color: #007bff; 
  border-radius: 15px 15px 15px 15px;
  background-color: #fff;
  transition: 0.4s;
}

.floating-button i {
  font-size: 22px;
  margin-left: 10px;
}
header {
  padding: 16px;
  position: relative;
  color: #888;
}
header h1,
header h2 {
  font-weight: 200;
  margin: 0;
}
header h1 {
  font-size: 27pt;
  letter-spacing: 4px;
}
body > * {
  width: 100%;
  max-width: 7in;
  margin: 3px auto;
  background: #f0f0f0;
  text-align: center;
}
footer {
  padding: 16px;
}
footer p {
  font-size: 9pt;
  margin: 0;
  font-family: 'Nunito';
  color: #777;
}
section,
table {
  padding: 8px 0;
  position: relative;
}
dl {
  margin: 0;
  letter-spacing: -4px;
}
dl dt,
dl dd {
  letter-spacing: normal;
  display: inline-block;
  margin: 0;
  padding: 0px 6px;
  vertical-align: top;
}
dl.bloc > dt,
dl:not(.bloc) dt:not(:last-of-type),
dl:not(.bloc) dd:not(:last-of-type) {
  border-bottom: 1px solid #ddd;
}
dl:not(.bloc) dt {
  border-right: 1px solid #ddd;
}
dt {
  width: 49%;
  text-align: right;
  letter-spacing: 1px !important;
  overflow: hidden;
}
dd {
  width: 49%;
  text-align: left;
}
dd,
tr>td {
  font-family: 'Nunito';
}
section.flex {
  display: flex;
  flex-flow: row wrap;
  padding: 8px 16px;
  justify-content: space-around;
}
dl.bloc {
  padding: 0;
  flex: 1;
  vertical-align: top;
  min-width: 240px;
  margin: 0 8px 8px;
}
dl.bloc>dt {
  text-align: left;
  width: 100%;
  margin-top: 12px;
}
dl.bloc>dd {
  text-align: left;
  width: 100%;
  padding: 8px 0 5px 16px;
  line-height: 1.25;
}
dl.bloc>dd>dl dt {
  width: 33%;
}
dl.bloc>dd>dl dd {
  width: 60%;
}
dl.bloc dl {
  margin-top: 12px;
}
dl.bloc dd {
  font-size: 11pt;
}
table {
  width: 100%;
  padding: 0;
  border-spacing: 0px;
}
table tr {
  margin: 0;
  padding: 0;
  background: #fdfdfd;
  border-right: 1px solid #ddd;
  width: 100%;
}
table tr td,
table tr th {
  border: 1px solid #e3e3e3;
  border-top: 1px solid #fff;
  border-left-color: #fff;
  font-size: 11pt;
  background: #fdfdfd;
}
table thead th {
  background: #e9e9e9;
  background: linear-gradient(to bottom, #f9f9f9, #e9e9e9) !important;
  font-weight: 300;
  letter-spacing: 1px;
  padding: 15px 0 5px;
  border: none !important;
}
table tbody tr:last-child td {
  border-bottom: 1px solid #ddd;
}
table tbody td {
  min-width: 75px;
  padding: 3px 6px;
  line-height: 1.25;
}
table tfoot tr td {
/*border 1px solid #e3e3e3
      border-top 1px solid white
      border-left-color #fff*/
  height: 40px;
  padding: 6px 0 0;
  color: #000;
  text-shadow: 0 0 1px rgba(0,0,0,0.25);
  font-family: 'Cambria', 'Raleway', sans-serif;
  font-weight: 400;
  letter-spacing: 1px;
}
table tfoot tr td:first-child {
  font-style: italic;
  color: #997b7b;
}
a {
  color: #992c2c;
}
a:hover {
  color: #b00;
}
@page {
  margin: 0.5cm;
}
html,
body {
  background: #333231;
}
header:before {
  content: '';
  position: absolute;
  top: 0;
  right: 0;
  border-top: 12px solid #333;
  border-left: 12px solid #ddd;
  width: 0;
  box-shadow: 1px 1px 2px rgba(0,0,0,0.18);
}

</style>

</head>

<?php
include "../conn.php";

if(isset($_GET['ID_reservation'])) {
  
  $id_reservation = $_GET['ID_reservation'];

  $query = "SELECT p.*, r.*, t.*, b.*, u.*
    FROM Paiements p
    INNER JOIN Reservations r ON p.ID_reservation = r.ID_reservation
    INNER JOIN Trajets t ON r.ID_trajet = t.ID_trajet
    INNER JOIN Bus b ON t.ID_bus = b.ID_bus
    INNER JOIN Utilisateurs u ON r.ID_utilisateur = u.ID_utilisateur
    WHERE r.ID_reservation = $id_reservation AND r.is_deleted = 'false'";

  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $login = $row['Login'];
    $email = $row['Adresse_email'];
    $id_paiement = $row["ID_paiement"];
    $date_paiement = $row["Date_paiement"];
    $methode_paiement = $row["Methode_paiement"];
    $montant_paye = $row["Montant_paye"];
    $place = $row["place"];
    $ville_arrivee = $row["Ville_arrivee"];
    $ville_depart = $row["Ville_depart"];
    $date_arrivee = $row["Date_arrivee"];
    $date_depart = $row["Date_depart"];
    $numero_bus = $row["immatriculation"];
    $typebus = $row["Type_bus"];
  }
}

$conn->close();

?>
<head>
  <meta charset="UTF-8"/>
  <title>Facture</title>
  <link href="https://fonts.googleapis.com/css?family=Nunito:300|Raleway:200,300" rel="stylesheet" type="text/css"/>
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css'>
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css'>

</head>
<body>

  <header>
    <h1>FACTURE
      <h2>Reçu de paiement - Travel</h2>
    </h1>
  </header>
  <section class="flex">
    <dl>
      <dt>Facture #</dt>
      <dd><?php echo $id_paiement; ?></dd>
      <dt>Date de facturation</dt>
      <dd><?php echo $date_paiement; ?></dd>
    </dl>
  </section>
  <section class="flex">
    <dl class="bloc">
      <dt>Facturé à:</dt>
      <dd><?php echo $_SESSION['useradmin']; ?></dd>
      <dt>Type bus</dt>
      <dd><?php echo $typebus; ?></dd>
    </dl> 
    <dl class="bloc">
      <dt>Place Reservée:</dt>
      <dd><?php echo $place; ?></dd>
      <dt>Immatriculation du bus:</dt>
      <dd><?php echo $numero_bus; ?></dd>
      <dt>Methode de paiement:</dt>
      <dd><?php echo $methode_paiement; ?></dd>
    </dl>
  </section>
  <table>
    <thead>
      <tr> 
        <th style="font-weight:bold;">Trajet</th>
        <th style="font-weight:bold;">date départ</th>
        <th style="font-weight:bold;">date d'arrivée</th>
        <th style="font-weight:bold;">Statut</th>
        <th style="font-weight:bold;">Montant</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><?php echo "$ville_depart - $ville_arrivee"; ?></td>
        <td><?php echo "$date_depart"; ?></td>
        <td><?php echo "$date_arrivee"; ?></td>
        <td>Payé</td>
        <td><?php echo "$montant_paye" . "XAF"; ?></td>
      </tr>
    </tbody>
    <tfoot>
      <tr> 
        <td colspan="3">− Votre satisfaction est notre priorité −</td>
        <td>Total:</td>
        <td><?php echo "$montant_paye" . "XAF"; ?></td>
      </tr>
    </tfoot>
  </table>
  <footer>
    <p style="font-weight:bold;"> Travel − Tél: 698408048 | <a href="accueil.php" style="text-decoration: none;">Travel.com</a></p><br>
    <p style="font-weight:bold;"><a href="Reservation.php" style="text-decoration: none;"><i class="fas fa-arrow-left"></i> Retour</a></p>
  </footer>
  <?php $_SESSION['genererfact'] = $id_reservation;?>
  <div class="floating-button">
    <a href="pdf.php">
      Générer la Facture
      <i class="fa fa-download"></i>
    </a>
  </div>
  
  <script type="text/javascript" src="js/mdb.umd.min.js"></script>
  <script src="../sweetalert/sweetalert2.all.min.js"></script>

</body>


</html>