<?php 
session_start();

// Ellenőrizzük, hogy a felhasználó be van jelentkezve és szerepe 'Teacher'
if (isset($_SESSION['teacher_id']) && isset($_SESSION['role'])) {

    if ($_SESSION['role'] == 'Teacher') {
        include "../db.php";
        include "data/student.php";
        include "data/grade.php";
        include "data/class.php";
        include "data/section.php";
        
        // Ha nincs 'class_id' paraméter, átirányítjuk az oldalt
        if (!isset($_GET['class_id'])) {
            header("Location: students.php");
            exit;
        }

        $class_id = $_GET['class_id'];
        $students = getAllStudents($pdo);
        $class = getClassById($class_id, $pdo);

?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tanár - Diákok</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/929e407827.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php 
    include "include/navbar.php";
    if ($students != 0) {
        $check = 0;
    ?>

    <?php 
    $i = 0; 
    foreach ($students as $student) { 
        $g = getGradeById($class['grade'], $pdo);
        $s = getSectionById($class['section'], $pdo);
        
        // Ellenőrizzük, hogy a diák osztálya és szekciója megfelel-e
        if ($g['grade_id'] == $student['grade'] && $s['section_id'] == $student['section']) {
            $i++; 
            if ($i == 1) { 
                $check++;
    ?>
                <div class="container mt-5">
                    <div class="table-responsive">
                        <table class="table table-bordered mt-3 n-table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">ID</th>
                                    <th scope="col">Keresztnév</th>
                                    <th scope="col">Vezetéknév</th>
                                    <th scope="col">Felhasználónév</th>
                                    <th scope="col">Évfolyam</th>
                                </tr>
                            </thead>
                            <tbody>  
    <?php 
            } 
        ?>
            <tr>
                <th scope="row"><?=$i?></th>
                <td><?=$student['student_id']?></td>
                <td>
                    <a href="studentGrade.php?student_id=<?=$student['student_id']?>">
                        <?=$student['fname']?>
                    </a>
                </td>
                <td><?=$student['lname']?></td>
                <td><?=$student['username']?></td>
                <td>
                    <?php 
                        $grade = $student['grade'];
                        $g_temp = getGradeById($grade, $pdo);
                        if ($g_temp != 0) {
                            echo $g_temp['grade_code'].'-'.
                                 $g_temp['grade'];
                        }
                    ?>
                </td>
            </tr>
        <?php 
        } 
    } 
    ?>
        </tbody>
    </table>
    </div>
</div>
<?php } else { ?>
    <div class="alert alert-info .w-450 m-5" role="alert">
        Nincs adat!
    </div>
<?php } ?>
</div>

<?php 
    // Ha nem találtunk diákokat az adott osztályhoz, átirányítunk
    if ($check == 0) {
        header("Location: students.php");
        exit;
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
} else {
    header("Location: ../login.php");
    exit;
} 
?>
