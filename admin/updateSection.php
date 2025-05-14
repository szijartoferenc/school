<?php 
session_start();

// Ellenőrizzük, hogy be van-e jelentkezve egy admin, és van-e section_id a GET-ben
if (
    isset($_SESSION['admin_id']) && 
    isset($_SESSION['role']) && 
    isset($_GET['section_id'])
) {
    if ($_SESSION['role'] === 'Admin') {
        
        // Adatbázis kapcsolat és segédfüggvények betöltése
        include "../db.php";
        include "data/section.php";

        // A lekérdezett szekció azonosítója (típusbiztosan)
        $section_id = intval($_GET['section_id']);
        $section = getSectionById($section_id, $pdo); // FIGYELEM: elírás a függvénynévben!

        // Ha a szekció nem található, visszairányítás
        if (!$section) {
            header("Location: section.php");
            exit;
        }
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin – Szekció szerkesztése</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/style.css">
	<link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/929e407827.js" crossorigin="anonymous"></script>
</head>
<body>
    
    <?php include "include/navbar.php"; ?>

    <div class="container mt-5">
        <a href="section.php" class="btn btn-dark">Vissza a listához</a>

        <!-- Szekció szerkesztő űrlap -->
        <form method="post" class="shadow p-3 mt-5 form-w" action="request/updateSection.php">
            <h3>Szekció szerkesztése</h3><hr>

            <!-- Hibaüzenet megjelenítése -->
            <?php if (isset($_GET['error'])) { ?>
              <div class="alert alert-danger" role="alert">
               <?=htmlspecialchars($_GET['error'])?>
              </div>
            <?php } ?>

            <!-- Sikeres frissítés üzenete -->
            <?php if (isset($_GET['success'])) { ?>
              <div class="alert alert-success" role="alert">
               <?=htmlspecialchars($_GET['success'])?>
              </div>
            <?php } ?>

            <!-- Szekció mező -->
            <div class="mb-3">
              <label class="form-label">Szekció neve</label>
              <input type="text" 
                     class="form-control"
                     name="section"
                     value="<?=htmlspecialchars($section['section'])?>"
                     required>
            </div>

            <!-- Rejtett mező az azonosítóhoz -->
            <input type="hidden" 
                   name="section_id"
                   value="<?=intval($section['section_id'])?>">

            <button type="submit" class="btn btn-primary">Frissítés</button>
        </form>
    </div>

    <!-- Aktív menüpont kiemelése JS-sel -->
    <script>
        $(document).ready(function(){
            $("#navLinks li:nth-child(4) a").addClass('active');
        });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php 
    } else {
        // Nem admin szerepkör esetén visszairányítás
        header("Location: grade.php");
        exit;
    }
} else {
    // Ha hiányzik bármelyik szükséges adat, visszairányítás
    header("Location: grade.php");
    exit;
} 
?>
