<?php 
// ✅ SESSION VALIDÁLÁS: Ellenőrizzük, hogy a felhasználó admin jogokkal rendelkezik, és érvényes 'r_user_id' paraméter van az URL-ben
session_start();
if (isset($_SESSION['admin_id']) && 
    isset($_SESSION['role'])     &&
    isset($_GET['r_user_id'])) {

    // ✅ Admin szerepkör ellenőrzése
    if ($_SESSION['role'] == 'Admin') {
      
       // ✅ DB kapcsolat és funkciók betöltése
       include "../db.php";
       include "data/registrationoffice.php";

       // ✅ Felhasználói adat lekérése az URL-ből
       $r_user_id = $_GET['r_user_id'];
       $r_user = getRUserById($r_user_id, $pdo);

       // ✅ Ha a felhasználó nem található, visszairányítjuk az admin oldalra
       if ($r_user == 0) {
         header("Location: registrar-office.php");
         exit;
       }

?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Adminisztráció felhasználó szerkesztése</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/929e407827.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php 
        // ✅ Navigációs sáv betöltése
        include "include/navbar.php";
    ?>

    <div class="container mt-5">
        <!-- Vissza gomb -->
        <a href="registrationoffice.php" class="btn btn-dark">Vissza</a>

        <!-- Felhasználói adatok szerkesztése form -->
        <form method="post" class="shadow p-3 mt-5 form-w" action="request/updateRegistrationOffice.php">
        <h3>Edit Registrar Office User</h3><hr>

        <!-- Hibák és sikeres műveletek üzenetei -->
        <?php if (isset($_GET['error'])) { ?>
          <div class="alert alert-danger" role="alert">
           <?= htmlspecialchars($_GET['error']) ?>
          </div>
        <?php } ?>
        <?php if (isset($_GET['success'])) { ?>
          <div class="alert alert-success" role="alert">
           <?= htmlspecialchars($_GET['success']) ?>
          </div>
        <?php } ?>

        <!-- Felhasználói adatok szerkesztése (keresztnév, vezetéknév stb.) -->
        <div class="mb-3">
          <label class="form-label">Vezetéknév</label>
          <input type="text" class="form-control" value="<?= htmlspecialchars($r_user['lname']) ?>" name="lname" required>
        </div>
        
        <div class="mb-3">
          <label class="form-label">Keresztnév</label>
          <input type="text" class="form-control" value="<?= htmlspecialchars($r_user['fname']) ?>" name="fname" required>
        </div>
       
        <div class="mb-3">
          <label class="form-label">Felhasználónév</label>
          <input type="text" class="form-control" value="<?= htmlspecialchars($r_user['username']) ?>" name="username" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Cím</label>
          <input type="text" class="form-control" value="<?= htmlspecialchars($r_user['address']) ?>" name="address" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Munkavállaló azonosítója</label>
          <input type="text" class="form-control" value="<?= htmlspecialchars($r_user['employee_number']) ?>" name="employee_number" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Születési dátum</label>
          <input type="date" class="form-control" value="<?= htmlspecialchars($r_user['date_of_birth']) ?>" name="date_of_birth" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Telefonszám</label>
          <input type="text" class="form-control" value="<?= htmlspecialchars($r_user['phone_number']) ?>" name="phone_number" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Végzettség</label>
          <input type="text" class="form-control" value="<?= htmlspecialchars($r_user['qualification']) ?>" name="qualification" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Email cím</label>
          <input type="email" class="form-control" value="<?= htmlspecialchars($r_user['email_address']) ?>" name="email_address" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Nem</label><br>
          <input type="radio" value="Male" <?php if($r_user['gender'] == 'Male') echo 'checked'; ?> name="gender"> Férfi
          &nbsp;&nbsp;&nbsp;&nbsp;
          <input type="radio" value="Female" <?php if($r_user['gender'] == 'Female') echo 'checked'; ?> name="gender"> Nő
        </div>

        <!-- Rejtett felhasználói ID mező -->
        <input type="text" value="<?= htmlspecialchars($r_user['r_user_id']) ?>" name="r_user_id" hidden>

        <!-- Frissítés gomb -->
        <button type="submit" class="btn btn-primary">Feltöltés</button>
        </form>

        <!-- Jelszó módosítása form -->
        <form method="post" class="shadow p-3 my-5 form-w" action="request/changeRegistrationOffice.php" id="change_password">
        <h3>Jelszócsere</h3><hr>

        <!-- Jelszó változtatási üzenetek -->
        <?php if (isset($_GET['perror'])) { ?>
          <div class="alert alert-danger" role="alert">
           <?= htmlspecialchars($_GET['perror']) ?>
          </div>
        <?php } ?>
        <?php if (isset($_GET['psuccess'])) { ?>
          <div class="alert alert-success" role="alert">
           <?= htmlspecialchars($_GET['psuccess']) ?>
          </div>
        <?php } ?>

        <!-- Admin jelszó mező -->
        <div class="mb-3">
            <label class="form-label">Admin jelszó</label>
            <input type="password" class="form-control" name="admin_pass" required>
        </div>

        <!-- Új jelszó mező és véletlenszerű generáló gomb -->
        <label class="form-label">Új jelszó</label>
        <div class="input-group mb-3">
            <input type="text" class="form-control" name="new_pass" id="passInput" required>
            <button class="btn btn-secondary" id="gBtn">Random</button>
        </div>

        <!-- Rejtett felhasználói ID mező -->
        <input type="text" value="<?= htmlspecialchars($r_user['r_user_id']) ?>" name="r_user_id" hidden>

        <!-- Új jelszó megerősítése -->
        <div class="mb-3">
            <label class="form-label">Új jelszó megerősítése</label>
            <input type="text" class="form-control" name="c_new_pass" id="passInput2" required>
        </div>

        <!-- Jelszó változtatás gomb -->
        <button type="submit" class="btn btn-primary">Változtatás</button>
        </form>
    </div>

    <!-- Bootstrap JS és egyéni jelszó generátor script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>  
    <script>
        $(document).ready(function(){
             $("#navLinks li:nth-child(7) a").addClass('active');
        });

        // Véletlenszerű jelszó generálása
        function makePass(length) {
            var result = '';
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            var charactersLength = characters.length;
            for (var i = 0; i < length; i++) {
              result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            var passInput = document.getElementById('passInput');
            var passInput2 = document.getElementById('passInput2');
            passInput.value = result;
            passInput2.value = result;
        }

        // Véletlenszerű jelszó generáló gomb eseménykezelője
        var gBtn = document.getElementById('gBtn');
        gBtn.addEventListener('click', function(e){
          e.preventDefault();
          makePass(4);
        });
    </script>
</body>
</html>

<?php 
  // ✅ Ha nem admin szerepkörű felhasználó próbál hozzáférni, irányítsuk át
  } else {
    header("Location: teacher.php");
    exit;
  } 
} else {
	// ✅ Ha nincs érvényes session, irányítsuk át a felhasználót
	header("Location: teacher.php");
	exit;
} 
?>
