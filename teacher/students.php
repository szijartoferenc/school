<?php 
session_start();

// Ellenőrizzük, hogy a felhasználó be van jelentkezve és szerepe 'Teacher'
if (isset($_SESSION['teacher_id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'Teacher') {

    include "../db.php";
    include "data/class.php";
    include "data/grade.php";
    include "data/section.php";
    include "data/teacher.php";
    
    // Tanár adatainak lekérése
    $teacher_id = $_SESSION['teacher_id'];
    $teacher = getTeacherById($teacher_id, $pdo);
    $teacher_classes = str_split(trim($teacher['class']));  // Tanár által oktatott osztályok

    // Az osztályok lekérése
    $classes = getAllClasses($pdo);
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tanárok - Tanulók</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/929e407827.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php 
        include "include/navbar.php"; 

        if ($classes != 0) {
    ?>
    <div class="container mt-5">
        <div class="table-responsive">
            <table class="table table-bordered mt-3 n-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Osztály</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 0; 
                    foreach ($classes as $class) { 
                        $grade  = getGradeById($class['grade'], $pdo);
                        $section = getSectionById($class['section'], $pdo);
                        $class_code = $grade['grade_code'].'-'.$grade['grade'].$section['section'];

                        // Ellenőrizzük, hogy a tanár az adott osztályhoz tartozik
                        if (in_array($class['class_id'], $teacher_classes)) {
                            $i++;
                    ?>
                        <tr>
                            <th scope="row"><?=$i?></th>
                            <td><a href="studentsClass.php?class_id=<?=$class['class_id']?>"><?= $class_code ?></a></td>
                        </tr>
                    <?php 
                        }
                    } 
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php 
        } else { 
    ?>
        <div class="alert alert-info .w-450 m-5" role="alert">Nincs adat!</div>
    <?php 
        }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>    
    <script>
        $(document).ready(function(){
            $("#navLinks li:nth-child(3) a").addClass('active');
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
