<?php 
session_start();

// Ellenőrizzük, hogy az admin be van-e jelentkezve és van-e student_id paraméter
if (
    isset($_SESSION['admin_id']) &&
    isset($_SESSION['role']) &&
    $_SESSION['role'] === 'Admin' &&
    isset($_GET['student_id'])
) {
    // Szükséges fájlok betöltése (adatbázis és adatlekérő függvények)
    include "../db.php";
    include "data/subject.php";
    include "data/grade.php";
    include "data/student.php";
    include "data/section.php";

    // Adatok lekérdezése az adatbázisból
    $student_id = intval($_GET['student_id']);
    $student = getStudentById($student_id, $pdo);

    // Ha nincs ilyen diák, visszairányítjuk
    if (!$student) {
        header("Location: student.php");
        exit;
    }

    // Alap adatok lekérdezése a form elemekhez
    $subjects = getAllSubjects($pdo);
    $grades = getAllGrades($pdo);
    $sections = getAllsections($pdo);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Edit Student</title>
    <!-- Bootstrap és egyéb stílusfájlok betöltése -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/929e407827.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php include "include/navbar.php"; ?>

    <div class="container mt-5">
        <a href="student.php" class="btn btn-dark">Go Back</a>

        <!-- DIÁK ADATOK SZERKESZTÉSE -->
        <form method="post" class="shadow p-3 mt-5 form-w" action="request/updateStudent.php">
            <h3>Hallgatói adatok szerkesztése</h3><hr>

            <!-- Hibák és sikeres üzenetek -->
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger"><?=htmlspecialchars($_GET['error'])?></div>
            <?php endif; ?>
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success"><?=htmlspecialchars($_GET['success'])?></div>
            <?php endif; ?>

            <!-- DIÁK ALAPADATOK -->
            <?php
            $fields = [
                "lname" => "Vezetéknév",
                "fname" => "Keresztnév",                
                "address" => "Cím",
                "email_address" => "Email cím",
                "date_of_birth" => "Születési idő",
                "username" => "Felhasználónév",
                "parent_lname" => "Szülő vezetékneve",
                "parent_fname" => "Szülő keresztneve",                
                "parent_phone_number" => "Szülő telefonszáma"
            ];
            foreach ($fields as $key => $label): ?>
                <div class="mb-3">
                    <label class="form-label"><?=$label?></label>
                    <input type="<?=($key == 'date_of_birth') ? 'date' : 'text'?>" 
                           class="form-control"
                           name="<?=$key?>"
                           value="<?=htmlspecialchars($student[$key])?>">
                </div>
            <?php endforeach; ?>

            <!-- NEM -->
            <div class="mb-3">
                <label class="form-label">Nem</label><br>
                <?php foreach (["Male", "Female"] as $gender): ?>
                    <input type="radio"
                           value="<?=$gender?>"
                           name="gender"
                           <?=($student['gender'] === $gender) ? 'checked' : ''?>> <?=$gender?> &nbsp;&nbsp;
                <?php endforeach; ?>
            </div>

            <!-- Elrejtett diákazonosító -->
            <input type="hidden" name="student_id" value="<?=$student['student_id']?>">

            <!-- OSZTÁLY (Grade) -->
            <div class="mb-3">
                <label class="form-label">Évfolyam</label>
                <div class="row row-cols-5">
                    <?php foreach ($grades as $grade): ?>
                        <div class="col">
                            <input type="radio"
                                   name="grade"
                                   value="<?=$grade['grade_id']?>"
                                   <?=($student['grade'] == $grade['grade_id']) ? 'checked' : ''?>>
                            <?=$grade['grade_code']?> - <?=$grade['grade']?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- SZEKCIÓ (Section) -->
            <div class="mb-3">
                <label class="form-label">Szekció</label>
                <div class="row row-cols-5">
                    <?php foreach ($sections as $section): ?>
                        <div class="col">
                            <input type="radio"
                                   name="section"
                                   value="<?=$section['section_id']?>"
                                   <?=($student['section'] == $section['section_id']) ? 'checked' : ''?>>
                            <?=$section['section']?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- MENTÉS GOMB -->
            <button type="submit" class="btn btn-primary">Módosítás</button>
        </form>

        <!-- JELSZÓ MÓDOSÍTÁS -->
        <form method="post" class="shadow p-3 my-5 form-w" action="req/student-change.php" id="change_password">
            <h3>Jelszócsere</h3><hr>

            <!-- Hibák és sikeres jelszócsere üzenetek -->
            <?php if (isset($_GET['perror'])): ?>
                <div class="alert alert-danger"><?=htmlspecialchars($_GET['perror'])?></div>
            <?php endif; ?>
            <?php if (isset($_GET['psuccess'])): ?>
                <div class="alert alert-success"><?=htmlspecialchars($_GET['psuccess'])?></div>
            <?php endif; ?>

            <!-- Admin jelszó és új jelszó -->
            <div class="mb-3">
                <label class="form-label">Admin jelszó</label>
                <input type="password" class="form-control" name="admin_pass">
            </div>
            <div class="mb-3">
                <label class="form-label">Új jelszó</label>
                <div class="input-group">
                    <input type="text" class="form-control" name="new_pass" id="passInput">
                    <button class="btn btn-secondary" id="gBtn">Random</button>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Új jelszó megerősítése</label>
                <input type="text" class="form-control" name="c_new_pass" id="passInput2">
            </div>

            <input type="hidden" name="student_id" value="<?=$student['student_id']?>">

            <button type="submit" class="btn btn-primary">Módosít</button>
        </form>
    </div>

    <!-- Bootstrap script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Aktív navigációs link beállítása
        $(document).ready(function(){
            $("#navLinks li:nth-child(3) a").addClass('active');
        });

        // Véletlenszerű jelszó generátor
        function makePass(length) {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            let result = '';
            for (let i = 0; i < length; i++) {
                result += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById('passInput').value = result;
            document.getElementById('passInput2').value = result;
        }

        document.getElementById('gBtn').addEventListener('click', function(e){
            e.preventDefault();
            makePass(8);
        });
    </script>
</body>
</html>
<?php 
} else {
    // Jogosultság hiánya vagy hiányzó paraméter esetén visszairányítás
    header("Location: student.php");
    exit;
}
?>
