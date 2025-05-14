<?php 
session_start();
if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) {

    // Csak admin szerepkörrel rendelkező felhasználók férhetnek hozzá
    if ($_SESSION['role'] == 'Admin') {
        include "../db.php"; // Csatlakozás az adatbázishoz
        include "data/registrationoffice.php"; // Az adatkezelő funkciók

        // Lekérjük az összes felhasználót a registrar irodából
        $r_users =getAllRUsers($pdo);

        // HTML megjelenítése
?>
<!DOCTYPE html>
<html lang="hu">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin - Adminisztráció</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/style.css">
	<link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/929e407827.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php 
        include "include/navbar.php"; // Navigációs sáv betöltése
    ?>
    <div class="container mt-5">
        <!-- Felhasználó hozzáadása gomb -->
        <a href="addRegistrationOffice.php" class="btn btn-dark">Új felhasználó hozzáadása</a>

        <!-- Hibaüzenet vagy sikerüzenet megjelenítése -->
        <?php if (isset($_GET['error'])) { ?>
            <div class="alert alert-danger mt-3 n-table" role="alert">
                <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php } ?>

        <?php if (isset($_GET['success'])) { ?>
            <div class="alert alert-info mt-3 n-table" role="alert">
                <?= htmlspecialchars($_GET['success']) ?>
            </div>
        <?php } ?>

        <!-- Felhasználók listájának megjelenítése táblázatban -->
        <div class="table-responsive">
            <table class="table table-bordered mt-3 n-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">ID</th>
                        <th scope="col">Vezetéknév</th>
                        <th scope="col">Keresztnév</th>
                        <th scope="col">Felhasználónév</th>
                        <th scope="col">Művelet</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0; 
                    // Felhasználók megjelenítése
                    if ($r_users != 0) { 
                        foreach ($r_users as $r_user ) { 
                            $i++;  ?>
                        <tr>
                            <th scope="row"><?=$i?></th>
                            <td><?=$r_user['r_user_id']?></td>
                            <td><a href="viewRegistrationOffice.php?r_user_id=<?=$r_user['r_user_id']?>"><?= htmlspecialchars($r_user['lname']) ?></a></td>
                            <td><?= htmlspecialchars($r_user['fname']) ?></td>
                            <td><?= htmlspecialchars($r_user['username']) ?></td>
                            <td>
                                <a href="updateRegistrationOffice.php?r_user_id=<?=$r_user['r_user_id']?>" class="btn btn-warning">Szerkesztés</a>
                                <a href="deleteRegistrationOffice.php?r_user_id=<?=$r_user['r_user_id']?>" class="btn btn-danger">Törlés</a>
                            </td>
                        </tr>
                    <?php } 
                    } else { ?>
                        <tr>
                            <td colspan="6" class="text-center">Felhasználó nem található</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

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
        // Ha nincs admin jogosultság, irányítsuk a belépési oldalra
        header("Location: ../login.php");
        exit;
    }
} else {
    // Ha nincs aktív session, irányítsuk a belépési oldalra
    header("Location: ../login.php");
    exit;
}
?>
