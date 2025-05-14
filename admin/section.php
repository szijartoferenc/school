<?php 
session_start();

// Ellenőrizzük, hogy az admin be van-e jelentkezve
if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) {

    if ($_SESSION['role'] === 'Admin') {
        // Adatbázis kapcsolat és segédfüggvények betöltése
        include "../db.php";
        include "data/section.php";

        // Összes szekció lekérdezése
        $sections = getAllSections($pdo);
?>
<!DOCTYPE html>
<html lang="hu">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin – Szekciók kezelése</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/style.css">
	<link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/929e407827.js" crossorigin="anonymous"></script>
</head>
<body>

<?php include "include/navbar.php"; ?>

<div class="container mt-5">
    <a href="addSection.php" class="btn btn-dark">Új szekció hozzáadása</a>

    <!-- Hibák és visszajelzések megjelenítése -->
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger mt-3" role="alert">
            <?=htmlspecialchars($_GET['error'])?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success mt-3" role="alert">
            <?=htmlspecialchars($_GET['success'])?>
        </div>
    <?php endif; ?>

    <!-- Szekciók táblázata -->
    <?php if ($sections && count($sections) > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered mt-3">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Szekció neve</th>
                        <th>Műveletek</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach ($sections as $section): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= htmlspecialchars($section['section']) ?></td>
                        <td>
                            <a href="updateSection.php?section_id=<?=intval($section['section_id'])?>" class="btn btn-warning btn-sm">
                                Szerkesztés
                            </a>
                            <a href="deleteSection.php?section_id=<?=intval($section['section_id'])?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Biztosan törölni szeretnéd ezt a szekciót?');">
                                Törlés
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info mt-4" role="alert">
            Jelenleg nincs egyetlen szekció sem a rendszerben.
        </div>
    <?php endif; ?>
</div>

<!-- Menü kiemelés JS-sel -->
<script>
    $(document).ready(function(){
        $("#navLinks li:nth-child(5) a").addClass('active');
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php 
    } else {
        // Ha nem admin, átirányítás a belépési oldalra
        header("Location: ../login.php");
        exit;
    }
} else {
    // Ha nincs bejelentkezve
    header("Location: ../login.php");
    exit;
}
?>
