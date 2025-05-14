<?php 
session_start();

// Ellenőrizzük, hogy az adminisztrátor be van-e jelentkezve
if (isset($_SESSION['admin_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'Admin') {
    
    include "../db.php";

    // Mezők alapértelmezett értékei
    $fname = $_GET['fname'] ?? '';
    $lname = $_GET['lname'] ?? '';
    $uname = $_GET['uname'] ?? '';
    $address = $_GET['address'] ?? '';
    $en = $_GET['en'] ?? '';
    $pn = $_GET['pn'] ?? '';
    $qf = $_GET['qf'] ?? '';
    $email = $_GET['email'] ?? '';
    $gender = $_GET['gender'] ?? 'Male';
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Új Regisztrátor</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/929e407827.js" crossorigin="anonymous"></script>
</head>
<body>
<?php include "include/navbar.php"; ?>

<div class="container mt-5">
    <a href="registrationoffice.php" class="btn btn-dark">Vissza</a>

    <form method="post" class="shadow p-3 mt-5 form-w" action="request/addRegistrationOffice.php">
        <h3>Új adminisztártor felvétele</h3><hr>

        <!-- Hibaüzenet -->
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php endif; ?>

        <!-- Sikeres hozzáadás -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success" role="alert">
                <?= htmlspecialchars($_GET['success']) ?>
            </div>
        <?php endif; ?>

        <!-- Mezők -->
        <div class="mb-3">
            <label class="form-label">Vezetéknév</label>
            <input type="text" class="form-control" name="lname" value="<?= htmlspecialchars($lname) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Keresztnév</label>
            <input type="text" class="form-control" name="fname" value="<?= htmlspecialchars($fname) ?>" required>
        </div>
       
        <div class="mb-3">
            <label class="form-label">Felhasználónév</label>
            <input type="text" class="form-control" name="username" value="<?= htmlspecialchars($uname) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Jelszó</label>
            <div class="input-group mb-3">
                <input type="password" class="form-control" name="pass" id="passInput" required>
                <button class="btn btn-secondary" id="gBtn">Véletlen</button>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Cím</label>
            <input type="text" class="form-control" name="address" value="<?= htmlspecialchars($address) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Dolgozói azonosító</label>
            <input type="text" class="form-control" name="employee_number" value="<?= htmlspecialchars($en) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Telefonszám</label>
            <input type="text" class="form-control" name="phone_number" value="<?= htmlspecialchars($pn) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Végzettség</label>
            <input type="text" class="form-control" name="qualification" value="<?= htmlspecialchars($qf) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email cím</label>
            <input type="email" class="form-control" name="email_address" value="<?= htmlspecialchars($email) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nem</label><br>
            <input type="radio" name="gender" value="Male" <?= $gender === 'Male' ? 'checked' : '' ?>> Férfi
            &nbsp;&nbsp;&nbsp;
            <input type="radio" name="gender" value="Female" <?= $gender === 'Female' ? 'checked' : '' ?>> Nő
        </div>

        <div class="mb-3">
            <label class="form-label">Születési dátum</label>
            <input type="date" class="form-control" name="date_of_birth" required>
        </div>

        <button type="submit" class="btn btn-primary">Hozzáadás</button>
    </form>
</div>

<!-- Bootstrap és saját szkriptek -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    // Aktív menü kijelölése
    $("#navLinks li:nth-child(7) a").addClass('active');

    // Jelszó generálása
    $("#gBtn").click(function(e) {
        e.preventDefault();
        const length = 8;
        const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        let password = "";
        for (let i = 0; i < length; i++) {
            password += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        $("#passInput").val(password);
    });
});
</script>

</body>
</html>
<?php 
} else {
    // Nincs jogosultság vagy nincs bejelentkezve
    header("Location: ../login.php");
    exit;
}
?>
