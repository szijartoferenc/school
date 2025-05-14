<?php 
session_start();

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve és admin szerepkörrel rendelkezik
if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) {

    // Ha a felhasználó admin szerepkörrel rendelkezik
    if ($_SESSION['role'] == 'Admin') {

        // Adatbázis kapcsolat és szükséges fájlok betöltése
        include "../db.php";  // Adatbázis kapcsolat
        include "data/class.php";  // Osztályokkal kapcsolatos funkciók
        include "data/grade.php";  // Évfolyamokkal kapcsolatos funkciók
        include "data/section.php";  // Szekciókkal kapcsolatos funkciók

        // Lekérjük az összes osztály adatot
        $classes = getAllClasses($pdo); 
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Osztályok</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <<script src="https://kit.fontawesome.com/929e407827.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php 
        // Navigációs sáv betöltése
        include "include/navbar.php"; 

        // Ha van osztály adat
        if ($classes != 0) {
    ?>
    <div class="container mt-5">
        <!-- Gomb új osztály hozzáadásához -->
        <a href="addClass.php" class="btn btn-dark">Új osztály hozzáadása</a>

        <!-- Hibaüzenet, ha van -->
        <?php if (isset($_GET['error'])) { ?>
            <div class="alert alert-danger mt-3 n-table" role="alert">
                <?=$_GET['error']?>
            </div>
        <?php } ?>

        <!-- Sikerüzenet, ha van -->
        <?php if (isset($_GET['success'])) { ?>
            <div class="alert alert-info mt-3 n-table" role="alert">
                <?=$_GET['success']?>
            </div>
        <?php } ?>

        <!-- Osztályok megjelenítése táblázatban -->
        <div class="table-responsive">
            <table class="table table-bordered mt-3 n-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Osztály</th>
                        <th scope="col">Műveletek</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Az osztályok listázása táblázatban
                    $i = 0; 
                    foreach ($classes as $class) { 
                        $i++;  
                    ?>
                    <tr>
                        <th scope="row"><?=$i?></th>
                        <td>
                            <?php 
                                // Az osztályhoz tartozó évfolyam és szekció megjelenítése
                                $grade  = getGradeById($class['grade'], $pdo);
                                $section = getSectionById($class['section'], $pdo);
                                echo $grade['grade_code'].'-'.$grade['grade'].$section['section'];
                            ?>
                        </td>
                        <td>
                            <!-- Szerkesztés és törlés gombok -->
                            <a href="updateClass.php?class_id=<?=$class['class_id']?>" class="btn btn-warning">Szerkesztés</a>
                            <a href="deleteClass.php?class_id=<?=$class['class_id']?>" class="btn btn-danger">Törlés</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php 
        // Ha nincs osztály, információs üzenet jelenik meg
        } else { 
    ?>
        <div class="alert alert-info .w-450 m-5" role="alert">
            Nincs osztály!
        </div>
    <?php } ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>    
    <script>
        // Aktív menüpont beállítása a navigációban
        $(document).ready(function(){
            $("#navLinks li:nth-child(6) a").addClass('active');
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
