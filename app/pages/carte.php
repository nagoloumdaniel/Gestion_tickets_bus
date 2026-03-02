<?php 
session_start();
unset($_SESSION['search']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>Carte du Cameroun</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.0.0/css/all.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/mdb.min.css" />
    <link rel="stylesheet" href="sweetalert/sweetalert2.min.css">
    <link rel="stylesheet" href="styler.css" />
</head>
<style>
    body {
    font-family: "Raleway", sans-serif;
    font-optical-sizing: auto;
    font-weight: <weight>;
    font-style: normal;
    color: #0a0a0a;
    line-height: 1;
    } 
    .navbar .nav-link {
        color: #000000 !important;
      }
      
      .navbar .nav-link:hover {
        color: blue !important;
      }
</style>
<body>
    
<header>
    <?php include "a.php" ?>
</header>
<hr class="my-5" />
<section class="text-center  mb-5" >
    <h3 ><strong class="text-primary" id="agence" >La Carte du Cameroun...</strong></h3>
</section>
<main class="mt-5 d-flex justify-content-center align-items-center">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d8104017.442651555!2d6.9982325995768955!3d7.3494314150342825!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x10613753703e0f21%3A0x2b03c44599829b53!2sCameroun!5e0!3m2!1sfr!2scm!4v1712496218323!5m2!1sfr!2scm" width="1280" height="768" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>    
</main>
<footer>
    <div class="text-center text-body p-3" style="background-color: rgba(0, 0, 0, 0.2);margin-top: 100px;bottom:0;">
        © 2024 Copyright:
        <a class="text-primary" href="accueil.php">Travel</a>
    </div>
</footer>
<script type="text/javascript" src="js/mdb.umd.min.js"></script>

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