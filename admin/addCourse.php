<?php 
session_start();

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve és admin szerepkörrel rendelkezik
if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) {

    // Ha a felhasználó admin szerepkörrel rendelkezik
    if ($_SESSION['role'] == 'Admin') {

        // Adatbázis kapcsolat és szükséges fájlok betöltése
        include '../db.php';  // Adatbázis kapcsolat
        include 'data/grade.php';  // Évfolyamokkal kapcsolatos funkciók

        // Lekérjük az összes évfolyamot
        $grades = getAllGrades($pdo); 
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Új tantárgy hozzáadása</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/929e407827.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php 
        // Navigációs sáv betöltése
        include "include/navbar.php"; 
    ?>

    <div class="container mt-5">
        <!-- Visszajelző gomb a tantárgyak listájához -->
        <a href="course.php" class="btn btn-dark">Vissza</a> <br><br>

        <!-- Ha nincsenek évfolyamok, akkor figyelmeztető üzenet -->
        <?php if ($grades == 0) { ?>
            <div class="alert alert-info" role="alert">
                Először hozz létre évfolyamot!
            </div>
        <?php } else { ?>

        <!-- Tantárgy hozzáadása űrlap -->
        <form method="post" class="shadow p-3 mt-5 form-w" action="request/addCourse.php">
            <h3>Új tantárgy hozzáadása</h3><hr>

            <!-- Hibák és sikerüzenetek megjelenítése -->
            <?php if (isset($_GET['error'])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?=$_GET['error']?>
                </div>
            <?php } ?>
            <?php if (isset($_GET['success'])) { ?>
                <div class="alert alert-success" role="alert">
                    <?=$_GET['success']?>
                </div>
            <?php } ?>

            <!-- Tantárgy neve -->
            <div class="mb-3">
                <label class="form-label">Tantárgy neve</label>
                <input type="text" class="form-control" name="course_name" required>
            </div>

            <!-- Tantárgy kódja -->
            <div class="mb-3">
                <label class="form-label">Tantárgy kódja</label>
                <input type="text" class="form-control" name="course_code" required>
            </div>

            <!-- Évfolyam kiválasztása -->
            <div class="mb-3">
                <label class="form-label">Évfolyam</label>
                <select name="grade" class="form-control" required>
                    <?php foreach ($grades as $grade) { ?>
                        <option value="<?=$grade['grade_id']?>">
                            <?=$grade['grade_code'].'-'.$grade['grade']?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <!-- Submit gomb a tantárgy hozzáadásához -->
            <button type="submit" class="btn btn-primary">Létrehozás</button>
        </form>
    </div>
    <?php } ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Aktív menüpont beállítása a navigációs sávban
        $(document).ready(function(){
            $("#navLinks li:nth-child(8) a").addClass('active');
        });
    </script>

</body>
</html>

<?php 
    // Ha a felhasználó nem admin, átirányítjuk a bejelentkezési oldalra
    } else {
        header("Location: ../login.php");
        exit;
    } 
} else {
    // Ha a felhasználó nincs bejelentkezve, átirányítjuk a bejelentkezési oldalra
    header("Location: ../login.php");
    exit;
} 
?>
