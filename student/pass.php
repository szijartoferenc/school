<?php 
session_start();

// Ellenőrzés, hogy a felhasználó be van-e jelentkezve és diák-e
if (isset($_SESSION['student_id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'Student') {
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diák - Jelszó Módosítása</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/929e407827.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php 
        include "include/navbar.php";
    ?>

    <div class="d-flex justify-content-center align-items-center flex-column">
        <form method="post" class="shadow p-3 my-5 form-w" action="request/changeStudent.php" id="change_password">
            <h3>Jelszó módosítása</h3><hr>
            
            <!-- Hibák és sikerüzenetek -->
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

            <!-- Régi jelszó -->
            <div class="mb-3">
                <label class="form-label">Régi jelszó</label>
                <input type="password" class="form-control" name="old_pass">
            </div>

            <!-- Új jelszó -->
            <div class="mb-3">
                <label class="form-label">Új jelszó</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="new_pass" id="passInput">
                    <button class="btn btn-secondary" id="gBtn">Generálás</button>
                </div>
            </div>

            <!-- Új jelszó megerősítése -->
            <div class="mb-3">
                <label class="form-label">Új jelszó megerősítése</label>
                <input type="text" class="form-control" name="c_new_pass" id="passInput2">
            </div>

            <!-- Jelszó módosítás gomb -->
            <button type="submit" class="btn btn-primary">Módosítás</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>    
    <script>
        $(document).ready(function(){
            // Navigációs menü aktív link beállítása
            $("#navLinks li:nth-child(3) a").addClass('active');
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

        var gBtn = document.getElementById('gBtn');
        gBtn.addEventListener('click', function(e) {
            e.preventDefault();
            makePass(8); // Jelszó hossza 8 karakter
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
