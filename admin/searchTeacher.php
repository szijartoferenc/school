<?php 
// Kezdeményezzük a session-t
session_start();

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve és admin szerepkörrel rendelkezik
if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) {

    // Csak admin jogosultsággal folytatjuk
    if ($_SESSION['role'] == 'Admin') {

        // Ellenőrizzük, hogy érkezett-e keresési kulcsszó
        if (isset($_GET['searchKey']) && !empty(trim($_GET['searchKey']))) {

            // Keresési kulcs biztonságos eltárolása
            $search_key = htmlspecialchars(trim($_GET['searchKey']));

            // Adatbáziskapcsolat és szükséges fájlok betöltése
            include "../db.php";
            include "data/teacher.php";
            include "data/subject.php";
            include "data/grade.php";

            // Tanárok keresése
            $teachers = searchTeachers($search_key, $pdo);
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Tanár keresése</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/929e407827.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php include "include/navbar.php"; ?>

    <div class="container mt-5">
        <a href="addTeacher.php" class="btn btn-dark">Új tanár hozzáadása</a>

        <!-- Kereső űrlap -->
        <form action="searchTeacher.php" method="get" class="mt-3 n-table">
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="searchKey" value="<?=$search_key?>" placeholder="Keresés...">
                <button class="btn btn-primary"><i class="fa fa-search"></i></button>
            </div>
        </form>

        <!-- Üzenetek -->
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger mt-3"><?=htmlspecialchars($_GET['error'])?></div>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-info mt-3"><?=htmlspecialchars($_GET['success'])?></div>
        <?php endif; ?>

        <!-- Találatok -->
        <?php if ($teachers != 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered mt-3 n-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ID</th>
                            <th>Vezetéknév</th>
                            <th>Keresztnév</th>
                            <th>Felhasználónév</th>
                            <th>Tantárgy</th>
                            <th>Osztály</th>
                            <th>Művelet</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($teachers as $i => $teacher): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= $teacher['teacher_id'] ?></td>
                            <td><a href="viewTeacher.php?teacher_id=<?= $teacher['teacher_id'] ?>"><?= htmlspecialchars($teacher['fname']) ?></a></td>
                            <td><?= htmlspecialchars($teacher['lname']) ?></td>
                            <td><?= htmlspecialchars($teacher['username']) ?></td>
                            <td>
                                <?php
                                    $subjects_list = '';
                                    foreach (str_split(trim($teacher['subjects'])) as $subject_id) {
                                        $subject = getSubjectById($subject_id, $pdo);
                                        if ($subject) $subjects_list .= $subject['subject_code'] . ', ';
                                    }
                                    echo rtrim($subjects_list, ', ');
                                ?>
                            </td>
                            <td>
                                <?php
                                    $grades_list = '';
                                    foreach (str_split(trim($teacher['grades'])) as $grade_id) {
                                        $grade = getGradeById($grade_id, $pdo);
                                        if ($grade) $grades_list .= $grade['grade_code'] . '-' . $grade['grade'] . ', ';
                                    }
                                    echo rtrim($grades_list, ', ');
                                ?>
                            </td>
                            <td>
                                <a href="updateTeacher.php?teacher_id=<?= $teacher['teacher_id'] ?>" class="btn btn-warning">Szerkesztés</a>
                                <a href="deleteTeacher.php?teacher_id=<?= $teacher['teacher_id'] ?>" class="btn btn-danger">Törlés</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info mt-5">Nincs találat a megadott kulcsra. <a href="teacher.php" class="btn btn-dark">Vissza</a></div>
        <?php endif; ?>
    </div>

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
            // Ha nincs keresési kulcs, visszairányítjuk
            header("Location: teacher.php");
            exit;
        }

    } else {
        // Nem admin jogosultság
        header("Location: ../login.php");
        exit;
    }

} else {
    // Nem bejelentkezett felhasználó
    header("Location: ../login.php");
    exit;
} 
?>
