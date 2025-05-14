<?php 
session_start();

// Csak akkor folytatódhat a művelet, ha be van jelentkezve az admin
if (isset($_SESSION['admin_id'], $_SESSION['role']) && $_SESSION['role'] === 'Admin') {

    // Ellenőrzés: a szükséges POST adatok jelen vannak-e
    if (isset($_POST['course_name'], $_POST['course_code'], $_POST['grade'])) {

        include '../../db.php';

        // Bemeneti adatok beolvasása és tisztítása
        $course_name = trim($_POST['course_name']);
        $course_code = trim($_POST['course_code']);
        $grade       = trim($_POST['grade']);

        // Alapvető validáció
        if (empty($course_name)) {
            $error = "Kurzus név kötelező";
            header("Location: ../addCourse.php?error=" . urlencode($error));
            exit;
        }

        if (empty($course_code)) {
            $error = "Kurzus kód kötelező";
            header("Location: ../addCourse.php?error=" . urlencode($error));
            exit;
        }

        if (empty($grade)) {
            $error = "Évfolyam kötelező";
            header("Location: ../addCourse.php?error=" . urlencode($error));
            exit;
        }

        // Ellenőrizzük, hogy a kurzus már létezik-e az adott évfolyamon
        $checkSql = "SELECT * FROM subjects WHERE grade = ? AND subject_code = ?";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->execute([$grade, $course_code]);

        if ($checkStmt->rowCount() > 0) {
            $error = "A kurzus már létezik ezen az évfolyamon";
            header("Location: ../addCourse.php?error=" . urlencode($error));
            exit;
        }

        // Új kurzus hozzáadása az adatbázishoz
        $insertSql = "INSERT INTO subjects (grade, subject, subject_code) VALUES (?, ?, ?)";
        $insertStmt = $pdo->prepare($insertSql);
        $insertStmt->execute([$grade, $course_name, $course_code]);

        $success = "Új kurzus sikeresen létrehozva";
        header("Location: ../addCourse.php?success=" . urlencode($success));
        exit;

    } else {
        // POST adatok hiányoznak
        $error = "Váratlan hiba történt";
        header("Location: ../addCourse.php?error=" . urlencode($error));
        exit;
    }

} else {
    // Jogosultság hiánya vagy nincs bejelentkezve
    header("Location: ../../logout.php");
    exit;
}
?>

