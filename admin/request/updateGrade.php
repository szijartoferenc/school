<?php 
session_start();

// Csak admin jogosultsággal használható
if (isset($_SESSION['admin_id'], $_SESSION['role']) && $_SESSION['role'] === 'Admin') {

    // Szükséges POST adatok ellenőrzése
    if (isset($_POST['grade_code'], $_POST['grade'], $_POST['grade_id'])) {

        include '../../db.php';

        // Bemeneti adatok tisztítása
        $grade_code = trim($_POST['grade_code']);
        $grade      = trim($_POST['grade']);
        $grade_id   = trim($_POST['grade_id']);

        // Hibák esetén az adatok visszatöltéséhez
        $data = 'grade_code=' . urlencode($grade_code) . 
                '&grade='     . urlencode($grade) . 
                '&grade_id='  . urlencode($grade_id);

        // Üres mezők ellenőrzése
        if (empty($grade_code)) {
            $error = "Grade Code is required";
            header("Location: ../updateGrade.php?error=" . urlencode($error) . "&$data");
            exit;
        }

        if (empty($grade)) {
            $error = "Grade is required";
            header("Location: ../updateGrade.php?error=" . urlencode($error) . "&$data");
            exit;
        }

        // Évfolyam frissítése az adatbázisban
        $sql  = "UPDATE grades SET grade = ?, grade_code = ? WHERE grade_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$grade, $grade_code, $grade_id]);

        $success = "Grade updated successfully";
        header("Location: ../updateGrade.php?success=" . urlencode($success) . "&$data");
        exit;

    } else {
        // Ha hiányoznak a POST adatok
        $error = "An error occurred";
        header("Location: ../grade.php?error=" . urlencode($error));
        exit;
    }

} else {
    // Jogosulatlan hozzáférés esetén kijelentkeztetés
    header("Location: ../../logout.php");
    exit;
}
?>
