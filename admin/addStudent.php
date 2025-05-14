<?php 
session_start();

// Csak akkor engedjük be az oldalt, ha Admin be van jelentkezve
if (isset($_SESSION['admin_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'Admin') {

    include "../db.php";
    include "data/grade.php";
    include "data/section.php";

    // Betöltjük az osztályokat és szekciókat az adatbázisból
    $grades = getAllGrades($pdo);
    $sections = getAllSections($pdo);

    // Hibakezeléshez előtöltött változók
    $fields = ['fname', 'lname', 'uname', 'address', 'email', 'pfn', 'pln', 'ppn'];
    foreach ($fields as $field) {
        $$field = isset($_GET[$field]) ? htmlspecialchars($_GET[$field]) : '';
    }
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Admin - Új diák hozzáadása</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>
<?php include "include/navbar.php"; ?>

<div class="container mt-5">
    <a href="student.php" class="btn btn-dark">Vissza a listához</a>

    <form method="post" action="request/addStudent.php" class="shadow p-4 mt-4 form-w">
        <h3>Új diák regisztrálása</h3>
        <hr>

        <!-- Hibák / Sikeres üzenetek -->
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
        <?php endif; ?>

        <!-- Diák adatok -->
        <div class="mb-3">
            <label class="form-label">Vezetéknév</label>
            <input type="text" name="lname" class="form-control" value="<?= $lname ?>" required maxlength="50">
        </div>
        
        <div class="mb-3">
            <label class="form-label">Keresztnév</label>
            <input type="text" name="fname" class="form-control" value="<?= $fname ?>" required maxlength="50">
        </div>
      
        <div class="mb-3">
            <label class="form-label">Lakcím</label>
            <input type="text" name="address" class="form-control" value="<?= $address ?>" maxlength="255">
        </div>
        <div class="mb-3">
            <label class="form-label">Email cím</label>
            <input type="email" name="email_address" class="form-control" value="<?= $email ?>" maxlength="100">
        </div>
        <div class="mb-3">
            <label class="form-label">Születési dátum</label>
            <input type="date" name="date_of_birth" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nem</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="gender" value="Male" id="male" checked>
                <label class="form-check-label" for="male">Férfi</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="gender" value="Female" id="female">
                <label class="form-check-label" for="female">Nő</label>
            </div>
        </div>
        <hr>

        <!-- Bejelentkezési adatok -->
        <div class="mb-3">
            <label class="form-label">Felhasználónév</label>
            <input type="text" name="username" class="form-control" value="<?= $uname ?>" required maxlength="30">
        </div>
        <div class="mb-3">
            <label class="form-label">Jelszó</label>
            <div class="input-group">
                <input type="password" name="pass" id="passInput" class="form-control" required minlength="4" maxlength="32">
                <button class="btn btn-secondary" id="gBtn">Generálás</button>
            </div>
        </div>
        <hr>

        <!-- Szülő adatok -->
        <div class="mb-3">
            <label class="form-label">Szülő vezetékneve</label>
            <input type="text" name="parent_lname" class="form-control" value="<?= $pln ?>" maxlength="50">
        </div>
        
        <div class="mb-3">
            <label class="form-label">Szülő keresztneve</label>
            <input type="text" name="parent_fname" class="form-control" value="<?= $pfn ?>" maxlength="50">
        </div>
     
        <div class="mb-3">
            <label class="form-label">Szülő telefonszáma</label>
            <input type="tel" name="parent_phone_number" class="form-control" value="<?= $ppn ?>" maxlength="20">
        </div>
        <hr>

        <!-- Osztály választás -->
        <div class="mb-3">
            <label class="form-label">Osztály</label>
            <div class="row row-cols-3">
                <?php if (is_array($grades) && count($grades) > 0): ?>
                    <?php foreach ($grades as $grade): ?>
                        <div class="form-check col">
                            <input class="form-check-input" type="radio" name="grade" value="<?= $grade['grade_id'] ?>" required>
                            <label class="form-check-label"><?= $grade['grade_code'] ?> - <?= $grade['grade'] ?></label>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-warning col-12">Nincs elérhető osztály.</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Szekció választás -->
        <div class="mb-3">
            <label class="form-label">Szekció</label>
            <div class="row row-cols-3">
                <?php if (is_array($sections) && count($sections) > 0): ?>
                    <?php foreach ($sections as $section): ?>
                        <div class="form-check col">
                            <input class="form-check-input" type="radio" name="section" value="<?= $section['section_id'] ?>" required>
                            <label class="form-check-label"><?= $section['section'] ?></label>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-warning col-12">Nincs elérhető szekció.</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Beküldés gomb -->
        <button type="submit" class="btn btn-primary">Regisztráció</button>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Menü aktív elem
    $(document).ready(function () {
        $("#navLinks li:nth-child(3) a").addClass('active');
    });

    // Véletlenszerű jelszó generálás
    function makePass(length) {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let result = '';
        for (let i = 0; i < length; i++) {
            result += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('passInput').value = result;
    }

    // Generálás gomb eseménykezelő
    document.getElementById('gBtn').addEventListener('click', function (e) {
        e.preventDefault();
        makePass(8); // Alapértelmezett hossz: 8 karakter
    });
</script>
</body>
</html>
<?php 
} else {
    // Ha nincs bejelentkezve, átirányítjuk a bejelentkezési oldalra
    header("Location: ../login.php");
    exit;
}
?>
