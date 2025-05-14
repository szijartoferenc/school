<?php 
session_start();
if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) {

    // Csak admin szerepkörrel rendelkező felhasználók férhetnek hozzá
    if ($_SESSION['role'] == 'Admin') {
        include "../db.php";  // Csatlakozás az adatbázishoz
        include "data/registrationoffice.php";  // Az adatkezelő funkciók

        // Ellenőrizzük, hogy meg van-e adva az 'r_user_id'
        if (isset($_GET['r_user_id'])) {
            $r_user_id = $_GET['r_user_id'];

            // Megpróbáljuk lekérni a felhasználót az adatbázisból az 'r_user_id' alapján
            $r_user = getRUserById($r_user_id, $pdo);    

            // Ha találunk felhasználót, megjelenítjük az adatokat
            if ($r_user != 0) {
?>
<!DOCTYPE html>
<html lang="hu">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin - Adminisztráció - Felhasználó</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/style.css">
	<link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/929e407827.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php 
        include "include/navbar.php";  // Navigációs sáv betöltése
    ?>
    <div class="container mt-5">
        <!-- Felhasználói adatok megjelenítése egy kártyában -->
        <div class="card" style="width: 22rem;">
          <img src="../img/registrar-office-<?=$r_user['gender']?>.jpg" class="card-img-top" alt="User Image">
          <div class="card-body">
            <h5 class="card-title text-center">@<?=$r_user['username']?></h5>
          </div>
          <ul class="list-group list-group-flush">
          <li class="list-group-item">vezetéknév: <?=$r_user['lname']?></li>
            <li class="list-group-item">Keresztnév: <?=$r_user['fname']?></li>            
            <li class="list-group-item">Felhasználónév: <?=$r_user['username']?></li>
            <li class="list-group-item">Munkavállaló azonosítója: <?=$r_user['employee_number']?></li>
            <li class="list-group-item">Cím: <?=$r_user['address']?></li>
            <li class="list-group-item">Születési dátum: <?=$r_user['date_of_birth']?></li>
            <li class="list-group-item">Telefonszám: <?=$r_user['phone_number']?></li>
            <li class="list-group-item">Végzettség: <?=$r_user['qualification']?></li>
            <li class="list-group-item">Email cím: <?=$r_user['email_address']?></li>
            <li class="list-group-item"  style="display: none;">Nem: <?=$r_user['gender']?></li>
            <li class="list-group-item">Csatlakozás dátuma: <?=$r_user['date_of_joined']?></li>
          </ul>
          <div class="card-body">
            <a href="registrationoffice.php" class="card-link">Vissza</a>
          </div>
        </div>
    </div>

    <?php 
        } else {
            // Ha a felhasználó nem található, irányítsuk vissza a regisztrációs irodához
            header("Location: registrationoffice.php");
            exit;
        }
    } else {
        // Ha nincs 'r_user_id' paraméter, visszairányítjuk a regisztrációs irodához
        header("Location: registrationoffice.php");
        exit;
    }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Aktív link beállítása a navigációs menüben
        $(document).ready(function(){
             $("#navLinks li:nth-child(7) a").addClass('active');
        });
    </script>
</body>
</html>

<?php 
    } else {
        // Ha nincs admin jogosultság, irányítsuk vissza a belépési oldalra
        header("Location: ../login.php");
        exit;
    }
} else {
    // Ha nincs aktív session, irányítsuk a belépési oldalra
    header("Location: ../login.php");
    exit;
}
?>
