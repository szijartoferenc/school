<?php 
session_start();

// Ellenőrzés: Be van-e jelentkezve a felhasználó és "Registration Office" szerepkörű-e
if (isset($_SESSION['r_user_id']) && isset($_SESSION['role'])) {

    if ($_SESSION['role'] == 'Registration Office') {

        // Ellenőrzés: létezik-e keresési kulcsszó GET paraméterként
        if (isset($_GET['searchKey'])) {

            $search_key = $_GET['searchKey'];

            // Adatbázis és szükséges fájlok betöltése
            include "../db.php";
            include "data/student.php";
            include "data/grade.php";

            // Keresés a kulcsszó alapján
            $students = searchStudents($search_key, $pdo);
?>
<!DOCTYPE html>
<html lang="hu">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Diákok keresése - Tanulmányi Osztály</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/style.css">
	<link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/929e407827.js" crossorigin="anonymous"></script>
</head>
<body>

<div class="container mt-5">
    <!-- Navigációs gombok -->
    <a href="addStudent.php" class="btn btn-dark">Új diák hozzáadása</a>
    <a href="student.php" class="btn btn-dark">Vissza</a>

    <!-- Kereső űrlap -->
    <form action="student-search.php" class="mt-3 n-table" method="get">
        <div class="input-group mb-3">
            <input type="text" class="form-control" name="searchKey" placeholder="Keresés...">
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

    <!-- Sikerüzenet megjelenítése -->
    <?php if (isset($_GET['success'])) { ?>
        <div class="alert alert-info mt-3 n-table" role="alert">
            <?=htmlspecialchars($_GET['success'])?>
        </div>
    <?php } ?>

    <!-- Találatok megjelenítése -->
    <?php if ($students != 0) { ?>
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
                        <th>Művelet</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0; foreach ($students as $student) { $i++; ?>
                        <tr>
                            <th scope="row"><?=$i?></th>
                            <td><?=$student['student_id']?></td>
                            <td>
                                <a href="student-view.php?student_id=<?=$student['student_id']?>">
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
                                        echo htmlspecialchars($g_temp['grade_code'] . '-' . $g_temp['grade']);
                                    }
                                ?>
                            </td>
                            <td>
                                <a href="updateStudent.php?student_id=<?=$student['student_id']?>" class="btn btn-warning">Szerkesztés</a>
                                <a href="deleteStudent.php?student_id=<?=$student['student_id']?>" class="btn btn-danger"
                                   onclick="return confirm('Biztosan törölni szeretnéd a diákot?');">Törlés</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } else { ?>
        <!-- Nincs találat -->
        <div class="alert alert-info mt-5" role="alert">
            Nincs találat a keresésre.
            <a href="student.php" class="btn btn-dark ms-3">Vissza</a>
        </div>
    <?php } ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php 
        } else {
            // Ha nincs keresési kulcs, visszairányítás
            header("Location: student.php");
            exit;
        } 

    } else {
        // Ha nem megfelelő a szerepkör
        header("Location: ../login.php");
        exit;
    } 

} else {
    // Ha nincs bejelentkezve
    header("Location: ../login.php");
    exit;
} 
?>
