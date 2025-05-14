<?php 
session_start();

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve és admin szerepkörrel rendelkezik, valamint hogy van-e 'class_id' paraméter az URL-ben
if (isset($_SESSION['admin_id']) && isset($_SESSION['role']) && isset($_GET['class_id'])) {

    // Csak akkor engedjük tovább, ha a felhasználó adminisztrátori jogosultsággal rendelkezik
    if ($_SESSION['role'] == 'Admin') {

        // Adatbázis kapcsolat és szükséges fájlok betöltése
        include "../db.php"; // Adatbázis kapcsolat
        include "data/class.php"; // Osztályhoz kapcsolódó függvények
        include "data/grade.php"; // Osztályokhoz kapcsolódó jegyek
        include "data/section.php"; // Osztályokhoz kapcsolódó szekciók

        // Az osztály adatait lekérjük az adatbázisból
        $class = getClassById($_GET['class_id'], $pdo);
        $grades = getAllGrades($pdo); // Minden elérhető osztály
        $sections = getAllSections($pdo); // Minden elérhető szekció

        // Ha nem található az osztály, visszairányítjuk a listára
        if ($class == 0) {
            header("Location: class.php");
            exit;
        }

        // HTML struktúra kezdete
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Osztály szerkesztése</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/929e407827.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php 
        include "include/navbar.php"; // Navigációs menü betöltése
    ?>

    <div class="container mt-5">
        <!-- Gomb a visszatéréshez az osztályok listájára -->
        <a href="class.php" class="btn btn-dark">Vissza</a>

        <!-- Osztály szerkesztő űrlap -->
        <form method="post" class="shadow p-3 mt-5 form-w" action="request/updateClass.php">
            <h3>Osztály szerkesztése</h3><hr>

            <!-- Hiba és siker üzenetek -->
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

            <!-- Osztályok közötti választás -->
            <div class="mb-3">
                <label class="form-label">Évfolyam</label>
                <select name="grade" class="form-control">
                    <?php foreach ($grades as $grade) { 
                        $selected = 0;
                        if ($grade['grade_id'] == $class['grade']) {
                            $selected = 1;
                        }
                    ?>
                    <option value="<?=$grade['grade_id']?>" <?php if ($selected) echo "selected"; ?>>
                        <?=$grade['grade_code'].'-'.$grade['grade']?>
                    </option>
                    <?php } ?>
                </select>
            </div>

            <!-- Szekciók közötti választás -->
            <div class="mb-3">
                <label class="form-label">Szekció</label>
                <select name="section" class="form-control">
                    <?php foreach ($sections as $section) {
                        $selected = 0;
                        if ($section['section_id'] == $class['section']) {
                            $selected = 1;
                        }
                    ?>
                    <option value="<?=$section['section_id']?>" <?php if ($selected) echo "selected"; ?>>
                        <?=$section['section']?>
                    </option>
                    <?php } ?>
                </select>
            </div>

            <!-- Rejtett input az osztály azonosítójának tárolására -->
            <input type="text" class="form-control" value="<?=$class['class_id']?>" name="class_id" hidden>

            <!-- Frissítés gomb -->
            <button type="submit" class="btn btn-primary">Frissítés</button>
        </form>
    </div>

    <!-- Bootstrap JavaScript betöltése -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Aktív menüpont beállítása a navigációs menüben -->
    <script>
        $(document).ready(function(){
            $("#navLinks li:nth-child(6) a").addClass('active');
        });
    </script>
</body>
</html>
<?php 

    // Ha a felhasználó nem admin, vagy nem található a kívánt osztály, visszairányítjuk a class.php oldalra
    } else {
        header("Location: class.php");
        exit;
    }

} else {
    // Ha nincs bejelentkezve, vagy nincs 'class_id' paraméter, visszairányítjuk a class.php oldalra
    header("Location: class.php");
    exit;
} 
?>
