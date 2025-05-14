<?php 
session_start();

// Jogosultság és paraméterellenőrzés
if (isset($_SESSION['admin_id']) && 
    isset($_SESSION['role']) && 
    isset($_GET['grade_id'])) {

    if ($_SESSION['role'] == 'Admin') {
      
        // Adatbázis kapcsolat és évfolyam adatkezelő betöltése
        include "../db.php";
        include "data/grade.php";

        // Lekérjük az adott évfolyam adatait az ID alapján
        $grade_id = $_GET['grade_id'];
        $grade = getGradeById($grade_id, $pdo);

        // Ha nem létezik ilyen évfolyam, visszairányítás
        if ($grade == 0) {
            header("Location: grade.php");
            exit;
        }

        // HTML oldal megjelenítése csak akkor, ha minden adat rendben
        ?>
        <!DOCTYPE html>
        <html lang="hu">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Admin – Évfolyam szerkesztése</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
            <link rel="stylesheet" href="../css/style.css">
            <link rel="icon" href="../logo.png">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
            <script src="https://kit.fontawesome.com/929e407827.js" crossorigin="anonymous"></script>
        </head>
        <body>
        
        <?php include "include/navbar.php"; ?>

        <div class="container mt-5">
            <a href="grade.php" class="btn btn-dark">Vissza</a>

            <form method="post" action="request/updateGrade.php" class="shadow p-3 mt-5 form-w">
                <h3>Évfolyam szerkesztése</h3><hr>

                <!-- Hibák vagy sikeres üzenetek megjelenítése -->
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <?=htmlspecialchars($_GET['error'])?>
                    </div>
                <?php endif; ?>
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success" role="alert">
                        <?=htmlspecialchars($_GET['success'])?>
                    </div>
                <?php endif; ?>

                <!-- Évfolyam kód szerkesztése -->
                <div class="mb-3">
                    <label class="form-label">Évfolyam kód</label>
                    <input type="text" 
                           class="form-control"
                           name="grade_code"
                           value="<?=htmlspecialchars($grade['grade_code'])?>">
                </div>

                <!-- Évfolyam neve -->
                <div class="mb-3">
                    <label class="form-label">Évfolyam megnevezés</label>
                    <input type="text" 
                           class="form-control"
                           name="grade"
                           value="<?=htmlspecialchars($grade['grade'])?>">
                </div>

                <!-- Rejtett mező az ID-hez -->
                <input type="hidden" 
                       name="grade_id" 
                       value="<?=htmlspecialchars($grade['grade_id'])?>">

                <button type="submit" class="btn btn-primary">Frissítés</button>
            </form>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>	
        <script>
            $(document).ready(function(){
                $("#navLinks li:nth-child(4) a").addClass('active');
            });
        </script>

        </body>
        </html>

<?php 
    } else {
        // Ha nem admin, visszairányítás
        header("Location: grade.php");
        exit;
    }

} else {
    // Hiányzó jogosultság vagy paraméter esetén visszairányítás
    header("Location: grade.php");
    exit;
}
?>
