<?php 
session_start();

// Ellenőrzés, hogy a felhasználó be van-e jelentkezve és diák-e
if (isset($_SESSION['student_id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'Student') {

    include "../db.php";
    include "data/student.php";
    include "data/subject.php";
    include "data/grade.php";
    include "data/section.php";

    $student_id = $_SESSION['student_id'];

    // Diák adatainak lekérése
    $student = getStudentById($student_id, $pdo);
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diák - Főoldal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/929e407827.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php 
        include "include/navbar.php";
    ?>
    
    <?php 
        // Ha a diák adatai elérhetők
        if ($student != 0): 
    ?>
    <div class="container mt-5">
        <div class="card" style="width: 22rem;">
            <img src="../img/student-<?=$student['gender']?>.png" class="card-img-top" alt="Diák képe">
            <div class="card-body">
                <h5 class="card-title text-center">@<?=$student['username']?></h5>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">Keresztnév: <?=$student['fname']?></li>
                <li class="list-group-item">Vezetéknév: <?=$student['lname']?></li>
                <li class="list-group-item">Felhasználónév: <?=$student['username']?></li>
                <li class="list-group-item">Lakcím: <?=$student['address']?></li>
                <li class="list-group-item">Születési dátum: <?=$student['date_of_birth']?></li>
                <li class="list-group-item">Email cím: <?=$student['email_address']?></li>
                <li class="list-group-item">Neme: <?=$student['gender']?></li>
                <li class="list-group-item">Csatlakozás dátuma: <?=$student['date_of_joined']?></li>

                <li class="list-group-item">Évfolyam: 
                    <?php 
                        $grade = $student['grade'];
                        $g = getGradeById($grade, $pdo);
                        echo $g['grade_code'].'-'.$g['grade'];
                    ?>
                </li>
                <li class="list-group-item">Osztály: 
                    <?php 
                        $section = $student['section'];
                        $s = getSectionById($section, $pdo);
                        echo $s['section'];
                    ?>
                </li>
                <br><br>
                <li class="list-group-item">Szülő keresztneve: <?=$student['parent_fname']?></li>
                <li class="list-group-item">Szülő vezetékneve: <?=$student['parent_lname']?></li>
                <li class="list-group-item">Szülő telefonszáma: <?=$student['parent_phone_number']?></li>
            </ul>
        </div>
    </div>
    <?php 
        else:
            header("Location: student.php");
            exit;
        endif;
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>    
    <script>
        $(document).ready(function(){
            // Aktív navigációs link beállítása
            $("#navLinks li:nth-child(1) a").addClass('active');
        });
    </script>
</body>
</html>

<?php 
} else {
    header("Location: ../login.php");
    exit;
}
?>
