<?php 
session_start();

// Ellenőrizzük, hogy az admin be van-e jelentkezve
if (isset($_SESSION['admin_id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'Admin') {

    include "../db.php";
    include "data/subject.php";
    include "data/grade.php";
    include "data/section.php";
    include "data/class.php";

    $subjects = getAllSubjects($pdo);
    $classes = getAllClasses($pdo);

    // Űrlap adatok előkészítése (hibakezeléshez visszatöltés esetén)
    $formData = [
        'fname' => $_GET['fname'] ?? '',
        'lname' => $_GET['lname'] ?? '',
        'uname' => $_GET['uname'] ?? '',
        'address' => $_GET['address'] ?? '',
        'en' => $_GET['en'] ?? '',
        'pn' => $_GET['pn'] ?? '',
        'qf' => $_GET['qf'] ?? '',
        'email' => $_GET['email'] ?? ''
    ];
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Új tanár hozzáadása</title>
    <link rel="icon" href="../logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://kit.fontawesome.com/929e407827.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>
<?php include "include/navbar.php"; ?>

<div class="container mt-5">
    <a href="teacher.php" class="btn btn-dark">Vissza</a>

    <form method="post" action="request/addTeacher.php" class="shadow p-4 mt-4 form-w">
        <h3>Új tanár hozzáadása</h3><hr>

        <!-- Hibaüzenetek megjelenítése -->
        <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger"><?=htmlspecialchars($_GET['error'])?></div>
        <?php endif; ?>
        <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success"><?=htmlspecialchars($_GET['success'])?></div>
        <?php endif; ?>

        <!-- Alapadatok -->
        <div class="mb-3">
            <label class="form-label">Vezetéknév</label>
            <input type="text" class="form-control" name="lname" value="<?=$formData['lname']?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Keresztnév</label>
            <input type="text" class="form-control" name="fname" value="<?=$formData['fname']?>">
        </div>
     
        <div class="mb-3">
            <label class="form-label">Felhasználónév</label>
            <input type="text" class="form-control" name="username" value="<?=$formData['uname']?>">
        </div>

        <!-- Jelszó generátor -->
        <div class="mb-3">
            <label class="form-label">Jelszó</label>
            <div class="input-group">
                <input type="text" class="form-control" id="passInput" name="pass">
                <button class="btn btn-secondary" id="gBtn">Véletlen</button>
            </div>
        </div>

        <!-- Kapcsolattartási adatok -->
        <div class="mb-3">
            <label class="form-label">Cím</label>
            <input type="text" class="form-control" name="address" value="<?=$formData['address']?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Dolgozói azonosító</label>
            <input type="text" class="form-control" name="employee_number" value="<?=$formData['en']?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Telefonszám</label>
            <input type="text" class="form-control" name="phone_number" value="<?=$formData['pn']?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Végzettség</label>
            <input type="text" class="form-control" name="qualification" value="<?=$formData['qf']?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Email cím</label>
            <input type="email" class="form-control" name="email_address" value="<?=$formData['email']?>">
        </div>

        <!-- Egyéb adatok -->
        <div class="mb-3">
            <label class="form-label">Nem</label><br>
            <input type="radio" name="gender" value="Male" checked> Férfi &nbsp;&nbsp;
            <input type="radio" name="gender" value="Female"> Nő
        </div>
        <div class="mb-3">
            <label class="form-label">Születési dátum</label>
            <input type="date" class="form-control" name="date_of_birth">
        </div>

        <!-- Tantárgyak kiválasztása -->
        <div class="mb-3">
            <label class="form-label">Tantárgy(ak)</label>
            <div class="row row-cols-2 row-cols-md-4">
                <?php foreach ($subjects as $subject): ?>
                <div class="col">
                    <input type="checkbox" name="subjects[]" value="<?=$subject['subject_id']?>">
                    <?=htmlspecialchars($subject['subject'])?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Osztályok kiválasztása -->
        <div class="mb-3">
            <label class="form-label">Osztály(ok)</label>
            <div class="row row-cols-2 row-cols-md-4">
                <?php foreach ($classes as $class): 
                    $grade = getGradeById($class['grade'], $pdo);
                    $section = getSectionById($class['section'], $pdo);
                ?>
                <div class="col">
                    <input type="checkbox" name="classes[]" value="<?=$class['class_id']?>">
                    <?=htmlspecialchars($grade['grade_code'] . '-' . $grade['grade'] . $section['section'])?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Hozzáadás</button>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Saját JS -->
<script>
    $(document).ready(function() {
        // Menü kiemelés
        $("#navLinks li:nth-child(2) a").addClass('active');
    });

    // Véletlenszerű jelszó generálása
    function makePass(length) {
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let result = '';
        for (let i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * characters.length));
        }
        document.getElementById('passInput').value = result;
    }

    // Gomb eseménykezelő
    document.getElementById('gBtn').addEventListener('click', function(e) {
        e.preventDefault();
        makePass(8); // hosszabb jelszó biztonság kedvéért
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
