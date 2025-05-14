<?php 
session_start();

// Ellenőrizzük, hogy az admin be van-e jelentkezve és jogosultsága megfelelő
if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'Admin') {
?>

<!DOCTYPE html>
<html lang="hu">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin - Új Szekció hozzáadása</title>
	
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
	<!-- Saját stíluslap -->
	<link rel="stylesheet" href="../css/style.css">
	<!-- Favicon -->
	<link rel="icon" href="../logo.png">
	<!-- jQuery és ikonok -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script src="https://kit.fontawesome.com/929e407827.js" crossorigin="anonymous"></script>
</head>
<body>

	<!-- Navigációs sáv -->
    <?php include "include/navbar.php"; ?>

    <div class="container mt-5">

        <!-- Vissza gomb a szekciók listájához -->
        <a href="section.php" class="btn btn-dark">Vissza</a>

        <!-- Szekció hozzáadása űrlap -->
        <form method="POST" action="request/addSection.php" class="shadow p-3 mt-5 form-w">
            <h3>Új szekció hozzáadása</h3>
            <hr>

            <!-- Hibaüzenet megjelenítése (ha van) -->
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($_GET['error']) ?>
                </div>
            <?php endif; ?>

            <!-- Sikerüzenet megjelenítése (ha van) -->
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success" role="alert">
                    <?= htmlspecialchars($_GET['success']) ?>
                </div>
            <?php endif; ?>

            <!-- Szekció név beviteli mező -->
            <div class="mb-3">
                <label class="form-label">Szekció neve</label>
                <input type="text" 
                       name="section" 
                       class="form-control" 
                       placeholder="Add meg a szekció nevét" 
                       required>
            </div>

            <!-- Küldés gomb -->
            <button type="submit" class="btn btn-primary">Létrehozás</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Aktív menüpont kijelölése -->
    <script>
        $(document).ready(function(){
            $("#navLinks li:nth-child(5) a").addClass('active');
        });
    </script>

</body>
</html>

<?php 
    } else {
        // Ha a felhasználónak nincs admin jogosultsága, visszairányítjuk a bejelentkezési oldalra
        header("Location: ../login.php");
        exit;
    }
} else {
    // Ha nincs érvényes munkamenet, visszairányítjuk a bejelentkezési oldalra
    header("Location: ../login.php");
    exit;
}
?>
