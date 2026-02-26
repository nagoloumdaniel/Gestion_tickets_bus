<?php
session_start();
include "conn.php";

if (isset($_POST['conn'])) {
    $login = $_POST['user'];
    $password = $_POST['pass'];

    $sqla = "SELECT ID_utilisateur,Adresse_email FROM Utilisateurs WHERE Login = ? AND Password = ?";
    $stmtt = $conn->prepare($sqla);
    $stmtt->bind_param("ss", $login, $password);
    $stmtt->execute();
    $stmtt->bind_result($ID_utilisateur,$adresseEmail);

    if ($stmtt->fetch()) {
        $_SESSION['mail'] = $adresseEmail;
        $_SESSION['ID_utilisateur'] = $ID_utilisateur;
    }
    $stmtt->close();
    
    $sql = "SELECT * FROM Utilisateurs WHERE Login = ? AND Password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $login, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $_SESSION['user'] = $login;
        $_SESSION['passwd'] = $password;
        
        header("Location: accueil.php");
        exit();
    } elseif ($result->num_rows == 0) {
        echo "<script>alert('Nom d\\'utilisateur ou mot de passe invalides !');</script>";
    } else {
        echo "<script>alert('Aucun compte trouvé ! Veuillez créer un compte.');</script>";
    }
    $stmt->close();
}

if (isset($_POST['ins'])) {
    $login = $_POST['user'];
    $email = $_POST['mail'];
    $password = $_POST['pass'];
    $role = "utilisateur";
    $profil_utilisateur = "img/undraw_profile.svg";
    $is_deleted = 0;
    $is_enabled = 1;
    $created_at = date("Y-m-d H:i:s");
    $updated_at = date("Y-m-d H:i:s");
    $deleted_at = NULL;

    $sql = "SELECT COUNT(*) AS total FROM Utilisateurs WHERE Login = ? AND Adresse_email = ? AND Password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $login, $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        $row = $result->fetch_assoc();
        $count = $row['total'];

        if ($count > 0) {
            echo "<script>alert('L\\'utilisateur existe déjà ! Essayez de vous connecter.');</script>";
        } else {
            $stmt = $conn->prepare("INSERT INTO Utilisateurs (Login, Adresse_email, Password, Role, profil, is_deleted, is_enabled, created_at, updated_at, deleted_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssisss", $login, $email, $password, $role, $profil_utilisateur, $is_deleted, $is_enabled, $created_at, $updated_at, $deleted_at);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                header("Location: index.php");
                exit();
            } else {
                echo "<script>alert('Erreur lors de l\\'ajout de l\\'administrateur !');</script>";
            }

            $stmt->close();
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
    <title>Connexion</title>
    <?php include "style.css"; ?>

</head>
<body>
    
    <div class="container">
        <div class="forms-container">
            <div class="signin-signup">
                <form action="#" class="sign-in-form" method="POST">
                    <h2 class="title">Se connecter</h2>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="text" name="user" id="user" required placeholder="Nom d'utilisateur">
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="pass" id="pass" required placeholder="Mot de passe">
                    </div>
                    <input type="submit" name="conn" value="Se connecter" class="btn solid">

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
                    <h2 class="title">Créer Un Compte</h2>
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
                    <input type="submit" value="S'inscrire" name="ins" class="btn solid">

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

                <img src="img/hey.svg" class="image" alt="">
            </div>

            <div class="panel right-panel">
                <br><br><br><br><div class="content">
                    <h3> Deja Membre ?</h3>
                    <p>Si vous avez deja un compte, cliquer sur ce bouton pour vous connecter</p>
                    <button class="btn transparent" id="sign-in-btn">Se connecter</button>
                </div>

                <br><br><img src="img/ter.svg" class="image" alt="">
            </div>
        </div>
    </div>
    <?php include "app.js"; ?>

</body>
</html>