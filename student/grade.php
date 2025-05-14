<?php
session_start();
require_once '../db.php';

// Feltételezzük, hogy a diák be van jelentkezve
$student_id = $_SESSION['student_id'] ?? null;

if (!$student_id) {
    echo "Hiba: nincs bejelentkezett diák.";
    exit;
}

// Diák adatainak lekérdezése
$sqlStudent = "SELECT fname, lname FROM students WHERE student_id = ?";
$stmtStudent = $pdo->prepare($sqlStudent);
$stmtStudent->execute([$student_id]);
$student = $stmtStudent->fetch(PDO::FETCH_ASSOC);

// Ellenőrzés, hogy a diák adataink sikeresen lekérhetők
if (!$student) {
    echo "Hiba: nem található diák a megadott azonosítóval.";
    exit;
}

// Jegyek lekérdezése a megfelelő mezőnevekkel
$sql = "SELECT 
            s.year, 
            s.semester, 
            subj.subject AS subject, 
            subj.subject_code,
            s.type, 
            s.grade, 
            s.date, 
            CONCAT(t.fname, ' ', t.lname) AS teacher
        FROM student_score s
        JOIN subjects subj ON s.subject_id = subj.subject_id
        JOIN teachers t ON s.teacher_id = t.teacher_id
        WHERE s.student_id = ?
        ORDER BY s.year DESC, s.semester, subj.subject, s.date";

$stmt = $pdo->prepare($sql);
$stmt->execute([$student_id]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Jegyek szöveges megfeleltetése
$gradeLabels = [
    5 => 'jeles',
    4 => 'jó',
    3 => 'közepes',
    2 => 'elégséges',
    1 => 'elégtelen'
];
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diák - Érdemjegy Összegzés</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/929e407827.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php include "include/navbar.php"; ?>

    <h3>Osztályzataim - <?= htmlspecialchars($student['lname'] . ' ' . $student['fname']) ?></h3>
    
    <div class="d-flex justify-content-center align-items-center flex-column pt-4">
    <?php
    if (empty($results)) {
        echo "<div class='alert alert-info w-100 m-5 text-center' role='alert'>Nincs elérhető jegy.</div>";
    } else {
        $currentHeader = "";
        foreach ($results as $row) {
            $header = "Év: {$row['year']} | Félév: {$row['semester']} | Tantárgy: {$row['subject']} ({$row['subject_code']})";
            if ($header !== $currentHeader) {
                if ($currentHeader !== "") {
                    echo "</tbody></table></div>";
                }
                // Új fejléc megjelenítése
                echo "<h3 class='text-center mt-4 mb-3'>$header</h3>";
                echo "<div class='table-responsive' style='width: 90%; max-width: 700px;'>
                        <table class='table table-bordered mt-1 mb-5'>
                            <thead class='thead-dark'>
                                <tr>
                                    <th scope='col'>Dátum</th>
                                    <th scope='col'>Típus</th>
                                    <th scope='col'>Jegy</th>
                                    <th scope='col'>Tanár</th>
                                </tr>
                            </thead>
                            <tbody>";
                $currentHeader = $header;
            }

            // Szöveges jegy előállítása
            $gradeNum = (int)$row['grade'];
            $gradeText = $gradeLabels[$gradeNum] ?? 'ismeretlen';

            echo "<tr>
                    <td>{$row['date']}</td>
                    <td>{$row['type']}</td>
                    <td>{$gradeNum} - {$gradeText}</td>
                    <td>{$row['teacher']}</td>
                  </tr>";
        }
        echo "</tbody></table></div>"; // A táblázat lezárása
    }
    ?>
    </div>

     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>    
    <script>
        $(document).ready(function(){
            $("#navLinks li:nth-child(2) a").addClass('active');
        });
    </script>
</body>
</html>
