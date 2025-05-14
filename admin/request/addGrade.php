<?php 
session_start();

// Jogosultság ellenőrzés: csak admin férhet hozzá
if (isset($_SESSION['admin_id'], $_SESSION['role']) && $_SESSION['role'] === 'Admin') {

    // Ellenőrzés: minden szükséges mező elküldve
    if (isset($_POST['grade_code'], $_POST['grade'])) {
        
        include '../../db.php';

        // Bemenetek tisztítása
        $grade_code = trim($_POST['grade_code']);
        $grade      = trim($_POST['grade']);

        // Adatok URL-be történő visszatöltéshez
        $data = 'grade_code=' . urlencode($grade_code) . '&grade=' . urlencode($grade);

        // Validáció
        if (empty($grade_code)) {
            $error = "Évfolyam kód kötelező";
            header("Location: ../addGrade.php?error=" . urlencode($error) . "&$data");
            exit;
        }

        if (empty($grade)) {
            $error = "Évfolyam kötelező";
            header("Location: ../addGrade.php?error=" . urlencode($error) . "&$data");
            exit;
        }

        // Új évfolyam hozzáadása az adatbázishoz
        $insertSql = "INSERT INTO grades (grade, grade_code) VALUES (?, ?)";
        $stmt = $pdo->prepare($insertSql);
        $stmt->execute([$grade, $grade_code]);

        $success = "Új évfolyam sikeresen létrehozva";
        header("Location: ../addGrade.php?success=" . urlencode($success));
        exit;

    } else {
        // Hiányzó POST adatok
        $error = "Egy váratlan hiva történt";
        header("Location: ../addGrade.php?error=" . urlencode($error));
        exit;
    }

} else {
    // Nem admin felhasználó → kijelentkeztetés
    header("Location: ../../logout.php");
    exit;
}
?>
