<?php 
session_start();

// Csak akkor folytatjuk, ha admin be van jelentkezve
if (isset($_SESSION['admin_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'Admin') {

    // Ellenőrizzük, hogy minden szükséges POST adat megvan-e
    if (isset($_POST['section'], $_POST['grade'], $_POST['class_id'])) {

        include '../../db.php';

        // Bemeneti adatok beolvasása
        $section  = trim($_POST['section']);
        $grade    = trim($_POST['grade']);
        $class_id = trim($_POST['class_id']);

        // Hiba esetén visszatérési adatok
        $data = "class_id=" . urlencode($class_id);

        // Üres mezők ellenőrzése
        if (empty($class_id)) {
            $error = "Class ID is required";
            header("Location: ../updateClass.php?error=$error&$data");
            exit;
        } elseif (empty($grade)) {
            $error = "Grade is required";
            header("Location: ../updateClass.php?error=$error&$data");
            exit;
        } elseif (empty($section)) {
            $error = "Section is required";
            header("Location: ../updateClass.php?error=$error&$data");
            exit;
        }

        // Ellenőrizzük, hogy van-e már ilyen osztály (azonos grade + section)
        $checkSql = "SELECT * FROM class WHERE grade = ? AND section = ? AND class_id != ?";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->execute([$grade, $section, $class_id]);

        if ($checkStmt->rowCount() > 0) {
            $error = "The class already exists";
            header("Location: ../updateClass.php?error=$error&$data");
            exit;
        }

        // Osztály frissítése
        $updateSql = "UPDATE class SET grade = ?, section = ? WHERE class_id = ?";
        $updateStmt = $pdo->prepare($updateSql);
        $updateStmt->execute([$grade, $section, $class_id]);

        $success = "Class updated successfully";
        header("Location: ../updateClass.php?success=$success&$data");
        exit;

    } else {
        // Ha POST adatok hiányoznak
        $error = "An error occurred";
        header("Location: ../class.php?error=$error");
        exit;
    }

} else {
    // Nem admin vagy nincs bejelentkezve
    header("Location: ../../logout.php");
    exit;
}
?>