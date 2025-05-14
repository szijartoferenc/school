<?php 
session_start();

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve és jogosult-e (Registrar Office)
if (isset($_SESSION['r_user_id']) && isset($_SESSION['role'])) {

    if ($_SESSION['role'] == 'Registrar Office') {

        // Szükséges fájlok betöltése (adatbázis kapcsolat, lekérdező függvények)
        include "../db.php";
        include "data/student.php";
        include "data/subject.php";
        include "data/grade.php";
        include "data/section.php";

        // Ellenőrizzük, hogy meg van-e adva a diák azonosító
        if (isset($_GET['student_id'])) {

            $student_id = $_GET['student_id'];
            $student = getStudentById($student_id, $pdo); // Diák adatainak lekérése

            ?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diák adatlap - Tanulmányi Osztály</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/929e407827.js" crossorigin="anonymous"></script>
</head>
<body>

<?php 
    // Ha a diák létezik, akkor megjelenítjük az adatokat
    if ($student != 0) {
?>
<div class="container mt-5">
    <div class="card mx-auto shadow p-3" style="max-width: 500px;">
        <!-- Diák profilkép neme alapján -->
        <img src="../img/student-<?=$student['gender']?>.png" class="card-img-top" alt="Profilkép">

        <div class="card-body">
            <h5 class="card-title text-center">@<?=$student['username']?></h5>
        </div>

        <!-- Diák alapadatai -->
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><strong>Vezetéknév:</strong> <?=$student['lname']?></li>
            <li class="list-group-item"><strong>Keresztnév:</strong> <?=$student['fname']?></li>
            <li class="list-group-item"><strong>Felhasználónév:</strong> <?=$student['username']?></li>
            <li class="list-group-item"><strong>Lakcím:</strong> <?=$student['address']?></li>
            <li class="list-group-item"><strong>Születési dátum:</strong> <?=$student['date_of_birth']?></li>
            <li class="list-group-item"><strong>Email cím:</strong> <?=$student['email_address']?></li>
            <li class="list-group-item"><strong>Nem:</strong> <?=$student['gender']?></li>
            <li class="list-group-item"><strong>Beiratkozás dátuma:</strong> <?=$student['date_of_joined']?></li>

            <!-- Osztály -->
            <li class="list-group-item"><strong>Osztály:</strong> 
                <?php 
                    $grade = $student['grade'];
                    $g = getGradeById($grade, $pdo);
                    echo $g ? $g['grade_code'].' - '.$g['grade'] : 'N/A';
                ?>
            </li>

            <!-- Szekció -->
            <li class="list-group-item"><strong>Szekció:</strong> 
                <?php 
                    $section = $student['section'];
                    $s = getSectionById($section, $pdo);
                    echo $s ? $s['section'] : 'N/A';
                ?>
            </li>

            <br>

            <!-- Szülői adatok -->
            <li class="list-group-item"><strong>Szülő vezetékneve:</strong> <?=$student['parent_lname']?></li>
            <li class="list-group-item"><strong>Szülő keresztneve:</strong> <?=$student['parent_fname']?></li>
            <li class="list-group-item"><strong>Szülő telefonszáma:</strong> <?=$student['parent_phone_number']?></li>
        </ul>

        <div class="card-body text-center">
            <a href="student.php" class="btn btn-dark">Vissza a diákokhoz</a>
        </div>
    </div>
</div>
<?php 
    } else {
        // Ha a diák nem létezik, visszairányítás a diák listához
        header("Location: student.php");
        exit;
    }
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>    
</body>
</html>

<?php 
        } else {
            header("Location: student.php");
            exit;
        }

    } else {
        header("Location: ../login.php");
        exit;
    }

} else {
    header("Location: ../login.php");
    exit;
} 
?>
