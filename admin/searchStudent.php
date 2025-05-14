<?php 
session_start();

// Ellenőrizzük, hogy az admin be van-e jelentkezve
if (isset($_SESSION['admin_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'Admin') {

    // Ellenőrizzük, hogy van-e keresési kulcsszó
    if (isset($_GET['searchKey'])) {

        $search_key = trim($_GET['searchKey']); // Keresési kulcsszó tisztítása
        include "../db.php";
        include "data/student.php";
        include "data/grade.php";

        // Diákok keresése a megadott kulcsszó alapján
        $students = searchStudents($search_key, $pdo);
?>
<!DOCTYPE html>
<html lang="hu">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin - Diák keresése</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/style.css">
	<link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/929e407827.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php include "include/navbar.php"; ?>

    <div class="container mt-5">
        <!-- Új diák hozzáadása gomb -->
        <a href="addStudent.php" class="btn btn-dark mb-3">Új diák hozzáadása</a>

        <!-- Kereső űrlap -->
        <form action="searchStudent.php" class="n-table mb-3" method="get">
            <div class="input-group">
                <input type="text" class="form-control" name="searchKey" placeholder="Keresés..." value="<?=htmlspecialchars($search_key)?>">
                <button class="btn btn-primary" type="submit">
                    <i class="fa fa-search" aria-hidden="true"></i>
                </button>
            </div>
        </form>

        <!-- Hibák és visszajelzések megjelenítése -->
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger n-table" role="alert">
                <?=htmlspecialchars($_GET['error'])?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-info n-table" role="alert">
                <?=htmlspecialchars($_GET['success'])?>
            </div>
        <?php endif; ?>

        <?php if ($students != 0): ?>
        <!-- Diáklista megjelenítése -->
        <div class="table-responsive">
            <table class="table table-bordered n-table">
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
                    <?php $i = 0; foreach ($students as $student): $i++; ?>
                    <tr>
                        <td><?=$i?></td>
                        <td><?=htmlspecialchars($student['student_id'])?></td>
                        <td>
                            <a href="viewStudent.php?student_id=<?=$student['student_id']?>">
                                <?=htmlspecialchars($student['fname'])?>
                            </a>
                        </td>
                        <td><?=htmlspecialchars($student['lname'])?></td>
                        <td><?=htmlspecialchars($student['username'])?></td>
                        <td>
                            <?php 
                                $grade = getGradeById($student['grade'], $pdo);
                                if ($grade != 0) {
                                    echo htmlspecialchars($grade['grade_code'] . '-' . $grade['grade']);
                                } else {
                                    echo "N/A";
                                }
                            ?>
                        </td>
                        <td>
                            <a href="updateStudent.php?student_id=<?=$student['student_id']?>" class="btn btn-warning btn-sm">Szerkesztés</a>
                            <a href="deleteStudent.php?student_id=<?=$student['student_id']?>" class="btn btn-danger btn-sm" onclick="return confirm('Biztosan törölni szeretné ezt a diákot?');">Törlés</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
            <!-- Nincs találat esetén -->
            <div class="alert alert-info mt-4" role="alert">
                Nincs találat.
                <a href="student.php" class="btn btn-dark ms-3">Vissza a listához</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#navLinks li:nth-child(3) a").addClass('active'); // Menü kiemelés
        });
    </script>
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
?>
