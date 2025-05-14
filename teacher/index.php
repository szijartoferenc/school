<?php 
session_start();

// Ellenőrizzük, hogy be van-e jelentkezve a tanár és helyes-e a szerepkör
if (isset($_SESSION['teacher_id'], $_SESSION['role']) && $_SESSION['role'] == 'Teacher') {

    include "../db.php";
    include "data/teacher.php";
    include "data/subject.php";
    include "data/grade.php";
    include "data/section.php";
    include "data/class.php";

    $teacher_id = $_SESSION['teacher_id'];
    $teacher = getTeacherById($teacher_id, $pdo);

    if ($teacher != 0) {
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tanár - Főoldal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/929e407827.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php include "include/navbar.php"; ?>
    
    <div class="container mt-5">
        <div class="card" style="width: 22rem;">
            <img src="../img/teacher-<?=$teacher['gender']?>.png" class="card-img-top" alt="Tanár képe">
            <div class="card-body">
                <h5 class="card-title text-center">@<?=$teacher['username']?></h5>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">Keresztnév: <?=$teacher['fname']?></li>
                <li class="list-group-item">Vezetéknév: <?=$teacher['lname']?></li>
                <li class="list-group-item">Felhasználónév: <?=$teacher['username']?></li>
                <li class="list-group-item">Munkavállalói szám: <?=$teacher['employee_number']?></li>
                <li class="list-group-item">Cím: <?=$teacher['address']?></li>
                <li class="list-group-item">Születési dátum: <?=$teacher['date_of_birth']?></li>
                <li class="list-group-item">Telefonszám: <?=$teacher['phone_number']?></li>
                <li class="list-group-item">Végzettség: <?=$teacher['qualification']?></li>
                <li class="list-group-item">Email cím: <?=$teacher['email_address']?></li>
                <li class="list-group-item">Nem: <?=$teacher['gender']?></li>
                <li class="list-group-item">Csatlakozási dátum: <?=$teacher['date_of_joined']?></li>

                <!-- Tárgyak listázása -->
                <li class="list-group-item">Tárgyak: 
                    <?php 
                    $subjects = str_split(trim($teacher['subjects']));
                    $subject_list = '';
                    foreach ($subjects as $subject) {
                        $subject_data = getSubjectById($subject, $pdo);
                        if ($subject_data != 0) {
                            $subject_list .= $subject_data['subject_code'] . ', ';
                        }
                    }
                    echo rtrim($subject_list, ', ');
                    ?>
                </li>

                <!-- Osztályok listázása -->
                <li class="list-group-item">Osztályok: 
                    <?php 
                    $classes = str_split(trim($teacher['class']));
                    $class_list = '';
                    foreach ($classes as $class_id) {
                        $class_data = getClassById($class_id, $pdo);
                        if ($class_data != 0 && $class_data != null) {
                            $grade_data = getGradeById($class_data['grade'], $pdo);
                            $section_data = getSectionById($class_data['section'], $pdo);
                            if ($grade_data != 0 && $grade_data != null && $section_data != 0 && $section_data != null) {
                                $class_list .= $grade_data['grade_code'] . '-' . $grade_data['grade'] . $section_data['section'] . ', ';
                            } else {
                                $class_list .= 'Ismeretlen osztály, ';
                            }
                        } else {
                            $class_list .= 'Ismeretlen osztály, ';
                        }
                    }
                    echo rtrim($class_list, ', ');
                    ?>
                </li>

            </ul>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>    
    <script>
        $(document).ready(function(){
            $("#navLinks li:nth-child(1) a").addClass('active');
        });
    </script>

</body>
</html>
<?php 
    } else {
        header("Location: logout.php?error=Hiba történt");
        exit;
    }

} else {
    header("Location: ../login.php");
    exit;
}
?>
