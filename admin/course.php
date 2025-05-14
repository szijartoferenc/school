<?php 
session_start();

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve, és admin szerepkörrel rendelkezik
if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) {

    // Ha a felhasználó admin szerepkörrel rendelkezik
    if ($_SESSION['role'] == 'Admin') {
      
       // Adatbázis kapcsolat és szükséges fájlok betöltése
       include "../db.php";  // Adatbázis kapcsolat
       include "data/subject.php";  // Tantárgyak kezelésével kapcsolatos funkciók
       include "data/grade.php";  // Osztályok kezelésével kapcsolatos funkciók

       // Az összes tantárgy lekérése az adatbázisból
       $courses = getAllSubjects($pdo);

     ?>
<!DOCTYPE html>
<html lang="hu">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin - Tantárgy</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/style.css">
	<link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/929e407827.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php 
        include "include/navbar.php";  // Navigáció betöltése

        // Ha van tantárgy a rendszerben, akkor megjelenítjük a táblázatot
        if ($courses != 0) {
     ?>
     <div class="container mt-5">
        <!-- Új tantárgy hozzáadása gomb -->
        <a href="addCourse.php" class="btn btn-dark">Új tantárgy hozzáadása</a>

        <!-- Hibaüzenet megjelenítése -->
        <?php if (isset($_GET['error'])) { ?>
            <div class="alert alert-danger mt-3 n-table" role="alert">
                <?=$_GET['error']?>
            </div>
        <?php } ?>

        <!-- Sikerüzenet megjelenítése -->
        <?php if (isset($_GET['success'])) { ?>
            <div class="alert alert-info mt-3 n-table" role="alert">
                <?=$_GET['success']?>
            </div>
        <?php } ?>

        <!-- Tantárgyak megjelenítése táblázatban -->
        <div class="table-responsive">
            <table class="table table-bordered mt-3 n-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Tantárgy</th>
                        <th scope="col">Tantárgy kód</th>
                        <th scope="col">Osztály</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0; foreach ($courses as $course) { 
                        $i++;  ?>
                    <tr>
                        <th scope="row"><?=$i?></th>
                        <td>
                            <?php echo $course['subject']; ?>
                        </td>
                        <td>
                            <?php echo $course['subject_code']; ?>
                        </td>
                        <td>
                            <?php 
                                // Lekérjük az osztály nevét a grade_id alapján
                                $grade = getGradeById($course['grade'], $pdo);
                                if ($grade) {
                                    echo $grade['grade_code'].'-'.$grade['grade'];
                                } else {
                                    echo "<span class='text-danger'>Nincs hozzárendelve osztály</span>";
                                }
                            ?>
                        </td>
                        <td>
                            <!-- Műveletek: szerkesztés és törlés -->
                            <a href="updateCourse.php?course_id=<?=$course['subject_id']?>" class="btn btn-warning">Szerkesztés</a>
                            <a href="deleteCourse.php?course_id=<?=$course['subject_id']?>" class="btn btn-danger">Törlés</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php 
        // Ha nincs tantárgy a rendszerben, akkor üzenetet jelenítünk meg
        } else { ?>
            <div class="alert alert-info .w-450 m-5" role="alert">
                Nem taláható eredmény!
            </div>
    <?php } ?>
    </div>
    
    <!-- Bootstrap JS betöltése -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>    

    <!-- Aktív navigáció beállítása -->
    <script>
        $(document).ready(function(){
            $("#navLinks li:nth-child(8) a").addClass('active');
        });
    </script>

</body>
</html>

<?php 
    // Ha a felhasználó nem admin, átirányítjuk a belépési oldalra
    } else {
        header("Location: ../login.php");
        exit;
    }
} else {
    // Ha nincs bejelentkezve a felhasználó, átirányítjuk a belépési oldalra
    header("Location: ../login.php");
    exit;
} 
?>
