<?php 
session_start();

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve és jogosult-e
if (isset($_SESSION['teacher_id']) && isset($_SESSION['role'])) {

    // Csak a Registration Office szerepkör férhet hozzá
    if ($_SESSION['role'] == 'Registration Office') {
        include "../db.php";
        include "data/student.php";
        include "data/grade.php";

        // Minden diák lekérdezése
        $students = getAllStudents($pdo);
?>
<!DOCTYPE html>
<html lang="hu">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Tanulmányi Osztály - Diákok</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/style.css">
	<link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/929e407827.js" crossorigin="anonymous"></script>
</head>
<body>

    <?php if ($students != 0) { ?>
    
    <div class="container mt-5">
        <!-- Új diák hozzáadása gomb -->
        <a href="addStudent.php" class="btn btn-dark">Új diák hozzáadása</a>
        <!-- Vissza gomb -->
        <a href="index.php" class="btn btn-dark">Vissza</a>

        <!-- Keresési űrlap -->
        <form action="student-search.php" class="mt-3 n-table" method="get">
            <div class="input-group mb-3">
                <input type="text" name="searchKey" class="form-control" placeholder="Keresés...">
                <button class="btn btn-primary">
                    <i class="fa fa-search" aria-hidden="true"></i>
                </button>
            </div>
        </form>

        <!-- Hibaüzenet megjelenítése -->
        <?php if (isset($_GET['error'])) { ?>
            <div class="alert alert-danger mt-3 n-table" role="alert">
                <?=htmlspecialchars($_GET['error'])?>
            </div>
        <?php } ?>

        <!-- Sikeres üzenet megjelenítése -->
        <?php if (isset($_GET['success'])) { ?>
            <div class="alert alert-info mt-3 n-table" role="alert">
                <?=htmlspecialchars($_GET['success'])?>
            </div>
        <?php } ?>

        <!-- Diákok táblázata -->
        <div class="table-responsive">
            <table class="table table-bordered mt-3 n-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ID</th>
                        <th>Vezetéknév</th>
                        <th>Keresztnév</th>
                        <th>Felhasználónév</th>
                        <th>Osztály</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0; foreach ($students as $student) { $i++; ?>
                    <tr>
                        <th scope="row"><?=$i?></th>
                        <td><?=$student['student_id']?></td>
                        <td>
                            <!-- Kattintható link a diák részletes adatlapjára -->
                            <a href="viewStudent.php?student_id=<?=$student['student_id']?>">
                                <?=htmlspecialchars($student['lname'])?>
                            </a>
                        </td>
                        <td><?=htmlspecialchars($student['fname'])?></td>
                        <td><?=htmlspecialchars($student['username'])?></td>
                        <td>
                            <?php 
                                $grade = $student['grade'];
                                $g_temp = getGradeById($grade, $pdo);
                                if ($g_temp != 0) {
                                    echo htmlspecialchars($g_temp['grade_code'].' - '.$g_temp['grade']);
                                }
                            ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        
    </div> <!-- .container vége -->

    <?php } else { ?>
        <!-- Ha nincs egyetlen diák sem -->
        <div class="alert alert-info w-50 mx-auto mt-5 text-center" role="alert">
            Nincs elérhető diák!
        </div>
    <?php } ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>	
    <!-- Menü aktív link kijelölése -->
    <script>
        $(document).ready(function(){
            $("#navLinks li:nth-child(3) a").addClass('active');
        });
    </script>

</body>
</html>

<?php 
    // Jogosulatlan hozzáférés visszairányítása
    } else {
        header("Location: ../login.php");
        exit;
    }
} else {
    header("Location: ../login.php");
    exit;
}
?>
