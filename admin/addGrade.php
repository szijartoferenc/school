<?php 
// Munkamenet indítása a felhasználó hitelesítéséhez
session_start();

// Ellenőrizzük, hogy az admin be van-e jelentkezve
if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) {

    // Csak az Admin szerepkörű felhasználó férhet hozzá
    if ($_SESSION['role'] == 'Admin') {
        
        // Alapértelmezett értékek megadása a form mezőknek
        $grade_code = '';
        $grade = '';

        // URL paraméterekből átvett értékek beállítása
        if (isset($_GET['grade_code'])) $grade_code = htmlspecialchars($_GET['grade_code']);
        if (isset($_GET['grade'])) $grade = htmlspecialchars($_GET['grade']);

?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Új évfolyam hozzáadása</title>
    
    <!-- Bootstrap stílusok -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    
    <!-- Saját CSS fájl -->
    <link rel="stylesheet" href="../css/style.css">
    
    <!-- Weboldal ikon -->
    <link rel="icon" href="../logo.png">

    <!-- jQuery könyvtár -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- Font Awesome ikonok -->
    <script src="https://kit.fontawesome.com/929e407827.js" crossorigin="anonymous"></script>
</head>
<body>

    <!-- Navigációs sáv betöltése -->
    <?php include "include/navbar.php"; ?>

    <div class="container mt-5">
        <!-- Visszalépés gomb -->
        <a href="grade.php" class="btn btn-dark">Vissza</a>

        <!-- Űrlap új évfolyam hozzáadásához -->
        <form method="post" action="request/addGrade.php" class="shadow p-3 mt-5 form-w">
            <h3>Új évfolyam hozzáadása</h3>
            <hr>

            <!-- Hibaüzenetek megjelenítése -->
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($_GET['error']) ?>
                </div>
            <?php endif; ?>

            <!-- Sikeres mentés üzenet -->
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success" role="alert">
                    <?= htmlspecialchars($_GET['success']) ?>
                </div>
            <?php endif; ?>

            <!-- Évfolyam kód mező -->
            <div class="mb-3">
                <label class="form-label">Évfolyam kód</label>
                <input type="text" 
                       class="form-control"
                       name="grade_code"
                       value="<?= $grade_code ?>"
                       required>
            </div>

            <!-- Évfolyam név mező -->
            <div class="mb-3">
                <label class="form-label">Évfolyam megnevezése</label>
                <input type="text" 
                       class="form-control"
                       name="grade"
                       value="<?= $grade ?>"
                       required>
            </div>

            <!-- Mentés gomb -->
            <button type="submit" class="btn btn-primary">Létrehozás</button>
        </form>
    </div>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>	

    <!-- Menü aktív elemének kiemelése -->
    <script>
        $(document).ready(function(){
            $("#navLinks li:nth-child(4) a").addClass('active');
        });
    </script>

</body>
</html>

<?php 
    } else {
        // Ha nem admin, visszairányítás a belépési oldalra
        header("Location: ../login.php");
        exit;
    }
} else {
    // Ha nincs bejelentkezve a felhasználó
    header("Location: ../login.php");
    exit;
}
?>
