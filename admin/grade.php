<?php 
session_start();

// Jogosultság ellenőrzése
if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'Admin') {
        
        include "../db.php";
        include "data/grade.php";

        // Minden évfolyam lekérdezése
        $grades = getAllGrades($pdo);
        ?>
        <!DOCTYPE html>
        <html lang="hu">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Admin – Évfolyamok</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
            <link rel="stylesheet" href="../css/style.css">
            <link rel="icon" href="../logo.png">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
            <script src="https://kit.fontawesome.com/929e407827.js" crossorigin="anonymous"></script>
        </head>
        <body>

        <?php include "include/navbar.php"; ?>

        <div class="container mt-5">
            <a href="addGrade.php" class="btn btn-dark">Új évfolyam hozzáadása</a>

            <!-- Hibák/sikerek megjelenítése -->
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger mt-3 n-table" role="alert">
                    <?=htmlspecialchars($_GET['error'])?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success mt-3 n-table" role="alert">
                    <?=htmlspecialchars($_GET['success'])?>
                </div>
            <?php endif; ?>

            <?php if ($grades != 0): ?>
                <!-- Évfolyamok táblázata -->
                <div class="table-responsive">
                    <table class="table table-bordered mt-3 n-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Kód</th>
                                <th>Megnevezés</th>
                                <th>Műveletek</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1; foreach ($grades as $grade): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($grade['grade_code']) ?></td>
                                <td><?= htmlspecialchars($grade['grade']) ?></td>
                                <td>
                                    <a href="updateGrade.php?grade_id=<?= $grade['grade_id'] ?>" class="btn btn-warning btn-sm">Szerkesztés</a>
                                    <a href="deleteGrade.php?grade_id=<?= $grade['grade_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Biztosan törlöd ezt az évfolyamot?');">Törlés</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info mt-5" role="alert">
                    Nincs rögzített évfolyam az adatbázisban.
                </div>
            <?php endif; ?>
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
        // Nem admin felhasználó
        header("Location: ../login.php");
        exit;
    }
} else {
    // Jogosulatlan hozzáférés
    header("Location: ../login.php");
    exit;
}
?>
