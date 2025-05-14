<?php 
// Munkamenet indítása
session_start();

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve és a szerepköre "Registration Office"
if (isset($_SESSION['r_user_id']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'Registration Office') {

        // Adatbázis kapcsolat és segédfüggvények betöltése
        include "../db.php";
        include "data/grade.php";
        include "data/section.php";

        // Összes osztály és szekció lekérdezése az adatbázisból
        $grades = getAllGrades($pdo);
        $sections = getAllSections($pdo);

        // Alapértelmezett értékek beállítása hibás visszatöltés esetén
        $fname = $_GET['fname'] ?? '';
        $lname = $_GET['lname'] ?? '';
        $uname = $_GET['uname'] ?? '';
        $address = $_GET['address'] ?? '';
        $email = $_GET['email'] ?? '';
        $pfn = $_GET['pfn'] ?? '';
        $pln = $_GET['pln'] ?? '';
        $ppn = $_GET['ppn'] ?? '';
?>

<!DOCTYPE html>
<html lang="hu">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Tanulmányi Osztály – Új diák hozzáadása</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/style.css">
	<link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/929e407827.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container mt-5">
        <!-- Vissza gomb -->
        <a href="index.php" class="btn btn-dark">Vissza</a>

        <!-- Diák hozzáadása űrlap -->
        <form method="post" class="shadow p-4 mt-4 form-w" action="request/addStudent.php">
            <h3>Új diák hozzáadása</h3><hr>

            <!-- Hibaüzenet -->
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?=htmlspecialchars($_GET['error'])?>
                </div>
            <?php endif; ?>

            <!-- Sikeres mentés -->
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success" role="alert">
                    <?=htmlspecialchars($_GET['success'])?>
                </div>
            <?php endif; ?>

            <!-- Diák adatok -->
            <div class="mb-3">
                <label class="form-label">Vezetéknév</label>
                <input type="text" class="form-control" name="lname" value="<?=htmlspecialchars($lname)?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Keresztnév</label>
                <input type="text" class="form-control" name="fname" value="<?=htmlspecialchars($fname)?>" required>
            </div>         
            <div class="mb-3">
                <label class="form-label">Lakcím</label>
                <input type="text" class="form-control" name="address" value="<?=htmlspecialchars($address)?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Email cím</label>
                <input type="email" class="form-control" name="email_address" value="<?=htmlspecialchars($email)?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Születési dátum</label>
                <input type="date" class="form-control" name="date_of_birth" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Nem</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" value="Male" checked>
                    <label class="form-check-label">Férfi</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" value="Female">
                    <label class="form-check-label">Nő</label>
                </div>
            </div><hr>

            <!-- Bejelentkezési adatok -->
            <div class="mb-3">
                <label class="form-label">Felhasználónév</label>
                <input type="text" class="form-control" name="username" value="<?=htmlspecialchars($uname)?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Jelszó</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="pass" id="passInput" placeholder="Generálható jelszó">
                    <button class="btn btn-secondary" id="gBtn">Random</button>
                </div>
            </div><hr>

            <!-- Szülői adatok -->
           <div class="mb-3">
                <label class="form-label">Szülő vezetékneve</label>
                <input type="text" class="form-control" name="parent_lname" value="<?=htmlspecialchars($pln)?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Szülő keresztneve</label>
                <input type="text" class="form-control" name="parent_fname" value="<?=htmlspecialchars($pfn)?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Szülő telefonszáma</label>
                <input type="text" class="form-control" name="parent_phone_number" value="<?=htmlspecialchars($ppn)?>">
            </div><hr>

            <!-- Osztály és szekció -->
            <div class="mb-3">
                <label class="form-label">Osztály</label>
                <div class="row row-cols-5">
                    <?php foreach ($grades as $grade): ?>
                    <div class="col">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="grade" value="<?=$grade['grade_id']?>" required>
                            <label class="form-check-label"><?=$grade['grade_code']?> - <?=$grade['grade']?></label>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Szekció</label>
                <div class="row row-cols-5">
                    <?php foreach ($sections as $section): ?>
                    <div class="col">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="section" value="<?=$section['section_id']?>" required>
                            <label class="form-check-label"><?=$section['section']?></label>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Beküldés gomb -->
            <button type="submit" class="btn btn-primary">Diák hozzáadása</button>
        </form>
    </div>

    <!-- Bootstrap és jQuery szkriptek -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Menü link kiemelése (ha van navigáció)
        $(document).ready(function(){
            $("#navLinks li:nth-child(3) a").addClass('active');
        });

        // Véletlen jelszó generáló függvény
        function makePass(length) {
            let result = '';
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            for (let i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() * characters.length));
            }
            document.getElementById('passInput').value = result;
        }

        // Gomb eseményfigyelő a generáláshoz
        document.getElementById('gBtn').addEventListener('click', function(e){
            e.preventDefault();
            makePass(8); // 8 karakter hosszú jelszó
        });
    </script>
</body>
</html>

<?php 
    } else {
        // Ha nem regisztrátor, átirányítás a belépéshez
        header("Location: ../login.php");
        exit;
    } 
} else {
    // Nincs bejelentkezve → átirányítás
    header("Location: ../login.php");
    exit;
}
?>
