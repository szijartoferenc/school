<?php 
session_start();

// Csak akkor engedjük az oldal betöltését, ha be van jelentkezve egy admin felhasználó
if (isset($_SESSION['admin_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'Admin') {

    require_once '../db.php';
    require_once 'data/grade.php';
    require_once 'data/section.php';

    // Lekérjük az összes évfolyamot és szekciót
    $grades = getAllGrades($pdo);
    $sections = getAllSections($pdo);
?>
<!DOCTYPE html>
<html lang="hu">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin - Osztály létrehozása</title>

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
	<!-- Saját stíluslap -->
	<link rel="stylesheet" href="../css/style.css">
	<!-- Favicon -->
	<link rel="icon" href="../logo.png">
    <!-- jQuery & Font Awesome -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/929e407827.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php include "include/navbar.php"; ?>

    <div class="container mt-5">

        <!-- Ha még nincs évfolyam vagy szekció létrehozva -->
        <?php if ($sections == 0 || $grades == 0): ?>
            <div class="alert alert-info" role="alert">
                Első évfolyam és szekció létrehozása
            </div>
            <a href="class.php" class="btn btn-dark">Vissza</a>
        <?php else: ?>

        <!-- Vissza gomb -->
        <a href="class.php" class="btn btn-dark mb-4">Vissza</a>

        <!-- Űrlap új osztály hozzáadásához -->
        <form method="post" action="request/addClass.php" class="shadow p-4 form-w bg-white rounded">
            <h3>Új osztály hozzáadása</h3>
            <hr>

            <!-- Hibák megjelenítése -->
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($_GET['error']) ?>
                </div>
            <?php endif; ?>

            <!-- Sikeres művelet megjelenítése -->
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success" role="alert">
                    <?= htmlspecialchars($_GET['success']) ?>
                </div>
            <?php endif; ?>

            <!-- Évfolyam kiválasztása -->
            <div class="mb-3">
                <label class="form-label">Évfolyam</label>
                <select name="grade" class="form-control" required>
                    <?php foreach ($grades as $grade): ?>
                        <option value="<?= $grade['grade_id'] ?>">
                            <?= htmlspecialchars($grade['grade_code'] . ' - ' . $grade['grade']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Szekció kiválasztása -->
            <div class="mb-3">
                <label class="form-label">Szekció</label>
                <select name="section" class="form-control" required>
                    <?php foreach ($sections as $section): ?>
                        <option value="<?= $section['section_id'] ?>">
                            <?= htmlspecialchars($section['section']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Beküldés gomb -->
            <button type="submit" class="btn btn-primary">Létrehozás</button>
        </form>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>	

    <!-- Aktív menüpont beállítása -->
    <script>
        $(document).ready(function(){
            $("#navLinks li:nth-child(6) a").addClass('active');
        });
    </script>
</body>
</html>

<?php 
} else {
    // Ha a felhasználó nem admin, visszairányítjuk a bejelentkezésre
    header("Location: ../login.php");
    exit;
}
?>
