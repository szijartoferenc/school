<?php 
session_start();

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve, admin szerepkörrel rendelkezik, és hogy a course_id paraméter jelen van
if (isset($_SESSION['admin_id']) && 
    isset($_SESSION['role']) && 
    isset($_GET['course_id'])) {

  // Ha a felhasználó admin szerepkörrel rendelkezik
  if ($_SESSION['role'] == 'Admin') {

     // Adatbázis kapcsolat és szükséges fájlok betöltése
     include "../db.php";  // Adatbázis kapcsolat
     include "data/subject.php";  // Tantárgyak kezelésével kapcsolatos funkciók
     include "data/grade.php";  // Osztályok kezelésével kapcsolatos funkciók

     // Az aktuális tantárgy azonosítója
     $course_id = $_GET['course_id'];

     // Lekérjük a tantárgy adatait az adatbázisból
     $course = getSubjectById($course_id, $pdo);

     // Lekérjük az összes osztályt
     $grades = getAllGrades($pdo);

     // Ha a tantárgy nem létezik, átirányítjuk a section.php oldalra
     if ($course == 0) {
         header("Location: section.php");
         exit;
     }

     ?>
<!DOCTYPE html>
<html lang="hu">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin - Kurzus szerkesztése</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/style.css">
	<link rel="icon" href="../logo.png">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://kit.fontawesome.com/929e407827.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php 
        include "include/navbar.php";  // Navigáció betöltése
    ?>
    <div class="container mt-5">
        <!-- Vissza gomb -->
        <a href="course.php" class="btn btn-dark">Vissza</a>

        <!-- Tantárgy szerkesztés űrlap -->
        <form method="post" class="shadow p-3 mt-5 form-w" action="request/updateCourse.php">
        <h3>Kurzus szerkesztése</h3><hr>

        <!-- Hiba vagy sikerüzenet megjelenítése -->
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
          <label class="form-label">Kurzus neve</label>
          <input type="text" class="form-control" value="<?=$course['subject']?>" name="course_name">
        </div>

        <!-- Tantárgy kódja -->
        <div class="mb-3">
          <label class="form-label">Kurzus azonosítója</label>
          <input type="text" class="form-control" value="<?=$course['subject_code']?>" name="course_code">
        </div>

        <!-- Osztály kiválasztása -->
        <div class="mb-3">
          <label class="form-label">Osztály</label>
          <select name="grade" class="form-control">
            <?php foreach ($grades as $grade) { 
                // Az aktuális tantárgyhoz tartozó osztály kijelölése
                $selected = ($grade['grade_id'] == $course['grade']) ? 1 : 0;
            ?>
              <option value="<?=$grade['grade_id']?>" <?php if ($selected) echo "selected"; ?>>
                 <?=$grade['grade_code'].'-'.$grade['grade']?>
              </option> 
            <?php } ?>
          </select>
        </div>

        <!-- Rejtett input a tantárgy azonosítójának tárolásához -->
        <input type="text" class="form-control" value="<?=$course['subject_id']?>" name="course_id" hidden>

        <!-- Frissítés gomb -->
        <button type="submit" class="btn btn-primary">Feltöltés</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>  
    <script>
        // Aktív navigációs elem beállítása
        $(document).ready(function(){
             $("#navLinks li:nth-child(8) a").addClass('active');
        });
    </script>

</body>
</html>
<?php 

  } else {
    // Ha nem admin a felhasználó, átirányítjuk a tantárgyak listájára
    header("Location: course.php");
    exit;
  } 
} else {
    // Ha nem vagyunk bejelentkezve vagy nincs course_id, átirányítjuk a tantárgyak listájára
    header("Location: course.php");
    exit;
} 
?>
