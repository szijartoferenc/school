<?php 
session_start();

// Ellenőrizzük, hogy az admin be van-e jelentkezve
if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'Admin') {

        include "../db.php";
        include "data/student.php";
        include "data/subject.php";
        include "data/grade.php";
        include "data/section.php";

        // Ha a hallgató ID meg van adva az URL-ben
        if (isset($_GET['student_id'])) {
            $student_id = $_GET['student_id'];
            $student = getStudentById($student_id, $pdo);    
?>
<!DOCTYPE html>
<html lang="hu">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin - Tanulók</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/style.css">
	<link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/929e407827.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php include "include/navbar.php"; ?>

    <?php if ($student != 0) { ?>
    <div class="container mt-5">
        <div class="card mx-auto shadow-lg" style="max-width: 24rem;">
            <img src="../img/student-<?=$student['gender']?>.png" class="card-img-top" alt="Student Image">
            <div class="card-body text-center">
                <h5 class="card-title">@<?=$student['username']?></h5>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Vezetéknév:</strong> <?=$student['lname']?></li>
                <li class="list-group-item"><strong>Keresztnév:</strong> <?=$student['fname']?></li>
                <li class="list-group-item"><strong>Felhasználónév:</strong> <?=$student['username']?></li>
                <li class="list-group-item"><strong>Cím:</strong> <?=$student['address']?></li>
                <li class="list-group-item"><strong>Születési dátum:</strong> <?=$student['date_of_birth']?></li>
                <li class="list-group-item"><strong>Email:</strong> <?=$student['email_address']?></li>
                <li class="list-group-item" style="display: none;"><strong>Nem:</strong> <?=$student['gender']?></li>
                <li class="list-group-item"><strong>Csatlakozás dátuma:</strong> <?=$student['date_of_joined']?></li>
                <li class="list-group-item"><strong>Osztály:</strong> 
                    <?php 
                        $grade = $student['grade'];
                        $g = getGradeById($grade, $pdo);
                        echo $g ? $g['grade_code'].' - '.$g['grade'] : 'N/A';
                    ?>
                </li>
                <li class="list-group-item"><strong>Section:</strong> 
                    <?php 
                        $section = $student['section'];
                        $s = getSectionById($section, $pdo);
                        echo $s ? $s['section'] : 'N/A';
                    ?>
                </li>
                <li class="list-group-item"><strong>Szülő vezetékneve:</strong> <?=$student['parent_lname']?></li>
                <li class="list-group-item"><strong>Szülő keresztneve:</strong> <?=$student['parent_fname']?></li>                
                <li class="list-group-item"><strong>Szülő telefonszáma:</strong> <?=$student['parent_phone_number']?></li>
            </ul>
            <div class="card-body text-center">
                <a href="student.php" class="btn btn-dark">Vissza</a>
            </div>
        </div>
    </div>
    <?php } else { 
        // Ha nem található a hallgató, átirányítjuk a listára
        header("Location: student.php");
        exit;
    } ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>	
    <script>
        $(document).ready(function(){
            // Aktív menüpont kiemelése
            $("#navLinks li:nth-child(3) a").addClass('active');
        });
    </script>
</body>
</html>

<?php 
        } else {
            // Ha nincs megadva student_id
            header("Location: student.php");
            exit;
        }
    } else {
        // Nem admin felhasználó
        header("Location: ../login.php");
        exit;
    }
} else {
    // Nincs bejelentkezve
	header("Location: ../login.php");
	exit;
} 
?>
