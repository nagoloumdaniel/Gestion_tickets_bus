<?php
session_start();
include "../conn.php";

if (isset($_POST['conne'])) {
    $login = $_POST['user'];
    $password = $_POST['pass'];

    $sqla = "SELECT Administrateurs.ID_administrateur 
    FROM Administrateurs 
    INNER JOIN Utilisateurs ON Utilisateurs.ID_utilisateur = Administrateurs.ID_utilisateur 
    WHERE Login = ? AND Password = ?";
    $stmtt = $conn->prepare($sqla);
    $stmtt->bind_param("ss", $login, $password);
    $stmtt->execute();
    $stmtt->bind_result($ID_administrateur);

    if ($stmtt->fetch()) {
        $_SESSION['ID_administrateur'] = $ID_administrateur;
    }
    $stmtt->close();

    $sql = "SELECT * FROM Utilisateurs WHERE Login = ? AND Password = ? AND Role = 'administrateur' AND is_deleted = 'false'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $login, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $_SESSION['useradmin'] = $login;
        $_SESSION['pass'] = $password;
        header("location:admin.php");
    
    } else {
    echo "<script>alert('Nom d\'utilisateur ou mot de passe invalides ou vérifier si ce compte dispose des droits d\'administrateur !');</script>";
    }
    $stmt->close();

}

if (isset($_POST['inse'])) {
    $login = $_POST['user'];
    $Adresse_email = $_POST['mail'];
    $Password = $_POST['pass'];
    $Role = "administrateur";
    $profil_utilisateur = "img/undraw_profile.svg";
    $profil_administrateur = "img/undraw_profile.svg";
    $is_deleted = FALSE;
    $is_enabled = TRUE;
    $created_at = date("Y-m-d H:i:s");
    $updated_at = date("Y-m-d H:i:s");
    $deleted_at = NULL;

    $sql = "SELECT COUNT(*) AS total FROM Utilisateurs WHERE Login = ? AND Adresse_email = ? AND is_deleted = 'false'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $login, $Adresse_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        $row = $result->fetch_assoc();
        $count = $row['total'];

        if ($count > 0) {
            echo "<script>alert('L\\'utilisateur existe déjà ! essayez de vous connecter !');</script>";
        } else {
            try {        
                $stmt = $conn->prepare("INSERT INTO Utilisateurs (Login, Adresse_email, Password, Role, profil, is_deleted, is_enabled, created_at, updated_at, deleted_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssisss", $login, $Adresse_email, $Password, $Role, $profil_utilisateur, $is_deleted, $is_enabled, $created_at, $updated_at, $deleted_at);
                $stmt->execute();
        
                $ID_utilisateur = $stmt->insert_id;
        
                $stmt = $conn->prepare("INSERT INTO Administrateurs (ID_utilisateur, profil, is_deleted, is_enabled, created_at, updated_at, deleted_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("issssss", $ID_utilisateur, $profil_administrateur, $is_deleted, $is_enabled, $created_at, $updated_at, $deleted_at);
                $stmt->execute();
        
                header("location: index.php");
            } catch (PDOException $e) {
                $conn->rollback();
                echo "<script>alert('Erreur lors de la création du compte !');</script>" . $e->getMessage();
            }
        
            $stmt->close();
        }
        
        $conn->close();
        
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
    <title>Connexion (ADMIN)</title>
    <?php include "../style.css"; ?>
</head>
<body>
    <div class="container">
        <div class="forms-container">
            <div class="signin-signup">
                <form action="#" class="sign-in-form" method="POST">
                    <h2 class="title">Se connecter (ADMIN)</h2>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="text" name="user" id="user" required placeholder="Nom d'utilisateur">
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="pass" id="pass" required placeholder="Mot de passe">
                    </div>
                    <input type="submit" name="conne" value="Se connecter" class="btn solid">

                    <p class="social-text">Ou Connectez-vous à partir des réseaux sociaux</p>
                    <div class="social-media">
                        <a href="#" class="social-icon">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-google"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </form>


                <form action="#" class="sign-up-form" method="POST">
                    <h2 class="title">Créer Un Compte (ADMIN)</h2>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="text" name="user" id="user" required placeholder="Nom d'utilisateur">
                    </div>
                    <div class="input-field">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="mail" id="mail" required placeholder="Adresse Email">
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="pass" id="passwordInput" required placeholder="Mot de passe">
                    </div>
                    <div>
                        <p id="passwordStrength"></p>
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="field2" required placeholder="Verifier le Mot de passe">
                    </div>
                    <div style="color: red;">
                        <p id="result"></p>
                    </div>
                    <input type="submit" value="S'inscrire" name="inse" class="btn solid">

                    <p class="social-text">Ou Inscrivez vous à partir des réseaux sociaux</p>
                    <div class="social-media">
                        <a href="#" class="social-icon">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-google"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>
        <div class="panels-container">
            <div class="panel left-panel">
                <div class="content">
                    <h3> Vous êtes nouveau ?</h3>
                    <p>Si vous êtes nouveau cliquer sur ce bouton pour vous inscrire</p>
                    <button class="btn transparent" id="sign-up-btn">S'inscrire</button>
                </div>

                <img src="../img/hey.svg" class="image" alt="">
            </div>

            <div class="panel right-panel">
                <br><br><br><br><div class="content">
                    <h3> Deja Membre ?</h3>
                    <p>Si vous avez deja un compte, cliquer sur ce bouton pour vous connecter</p>
                    <button class="btn transparent" id="sign-in-btn">Se connecter</button>
                </div>

                <br><br><img src="../img/ter.svg" class="image" alt="">
            </div>
        </div>
    </div>
    <?php include "../app.js"; ?>
    <script src="../sweetalert/sweetalert2.all.min.js"></script>

</body>
</html>