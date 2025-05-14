<?php 
session_start();

if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) {

    if ($_SESSION['role'] == 'Admin') {
        include "../db.php";
        include "data/teacher.php";
        include "data/subject.php";
        include "data/grade.php";
        include "data/section.php";
        include "data/class.php";

        if (isset($_GET['teacher_id'])) {

            $teacher_id = $_GET['teacher_id'];
            $teacher = getTeacherById($teacher_id, $pdo);    
?>
<!DOCTYPE html>
<html lang="hu">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin - Tanárok</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/style.css">
	<link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/929e407827.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php 
        include "include/navbar.php"; 
        if ($teacher != 0) {
     ?>
     <div class="container mt-5">
         <div class="card" style="width: 22rem;">
          <img src="../img/teacher-<?=$teacher['gender']?>.png" class="card-img-top" alt="Tanár kép">
          <div class="card-body">
            <h5 class="card-title text-center">@<?=$teacher['username']?></h5>
          </div>
          <ul class="list-group list-group-flush">
            <li class="list-group-item">Vezetéknév: <?=$teacher['lname']?></li>
            <li class="list-group-item">Keresztnév: <?=$teacher['fname']?></li>            
            <li class="list-group-item">Felhasználónév: <?=$teacher['username']?></li>
            <li class="list-group-item">Munkavállalói szám: <?=$teacher['employee_number']?></li>
            <li class="list-group-item">Cím: <?=$teacher['address']?></li>
            <li class="list-group-item">Születési dátum: <?=$teacher['date_of_birth']?></li>
            <li class="list-group-item">Telefonszám: <?=$teacher['phone_number']?></li>
            <li class="list-group-item">Képesítés: <?=$teacher['qualification']?></li>
            <li class="list-group-item">Email cím: <?=$teacher['email_address']?></li>
            <li class="list-group-item"  style="display: none;">Nem: <?=$teacher['gender']?></li>
            <li class="list-group-item">Belépés dátuma: <?=$teacher['date_of_joined']?></li>

            <!-- Tárgyak listázása -->
            <li class="list-group-item">Tárgyak: 
                <?php 
                   $s = '';
                   $subjects = explode(',', trim($teacher['subjects']));
                   foreach ($subjects as $subject_id) {
                      $subject_id = trim($subject_id);
                      if (!empty($subject_id)) {
                          $s_temp = getSubjectById($subject_id, $pdo);
                          if ($s_temp) {
                              $s .= $s_temp['subject_code'] . ', ';
                          }
                      }
                   }
                   echo rtrim($s, ', ');
                ?>
            </li>
            
            <!-- Osztályok listázása -->
            <li class="list-group-item">Osztályok: 
                <?php 
                    $classLabels = [];
                    if (!empty($teacher['class'])) { // csak akkor dolgozzuk fel, ha van benne valami
                        $classIds = explode(',', trim($teacher['class'], ','));
                        foreach ($classIds as $classId) {
                            $classId = trim($classId); // szóközök eltávolítása
                            if (is_numeric($classId)) {
                                $class = getClassById((int)$classId, $pdo);
                                if ($class) {
                                    $grade = getGradeById($class['grade'], $pdo);
                                    $section = getSectionById($class['section'], $pdo);
                                    if ($grade && $section) {
                                        $classLabels[] = $grade['grade_code'].'-'.$grade['grade'].$section['section'];
                                    }
                                }
                            }
                        }
                    }
                    echo implode(', ', $classLabels);
                ?>          
            </li>
            
          </ul>
          <div class="card-body">
            <a href="teacher.php" class="card-link">Vissza</a>
          </div>
        </div>
     </div>
     <?php 
        } else {
          header("Location: teacher.php");
          exit;
        }
     ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>	
    <script>
        $(document).ready(function(){
             $("#navLinks li:nth-child(2) a").addClass('active');
        });
    </script>

</body>
</html>
<?php 

    } else {
        header("Location: teacher.php");
        exit;
    }

  } else {
    header("Location: ../login.php");
    exit;
  } 
}else {
	header("Location: ../login.php");
	exit;
} 
?>
