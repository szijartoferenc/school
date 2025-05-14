<?php
session_start();

// Biztonságos session kezelés
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../logout.php");
    exit;
}

// Az admin jelszó változtatásához szükséges POST változók ellenőrzése
if (isset($_POST['admin_pass'], $_POST['new_pass'], $_POST['c_new_pass'], $_POST['student_id'])) {
    
    include '../../db.php';
    include "../data/admin.php";

    // A POST változók biztonságos kezelése
    $admin_pass = $_POST['admin_pass'];
    $new_pass = $_POST['new_pass'];
    $c_new_pass = $_POST['c_new_pass'];
    $student_id = $_POST['student_id'];
    $id = $_SESSION['admin_id'];

    // URL paraméterek előkészítése
    $data = 'student_id=' . $student_id . '#change_password';

    // Hibakezelés a kötelező mezőkhöz
    if (empty($admin_pass)) {
        $em = "Admin password is required";
        header("Location: ../updateStudent.php?perror=" . urlencode($em) . "&$data");
        exit;
    } elseif (empty($new_pass)) {
        $em = "New password is required";
        header("Location: ../updateStudent.php?perror=" . urlencode($em) . "&$data");
        exit;
    } elseif (empty($c_new_pass)) {
        $em = "Confirmation password is required";
        header("Location: ../updateStudent.php?perror=" . urlencode($em) . "&$data");
        exit;
    } elseif ($new_pass !== $c_new_pass) {
        $em = "New password and confirm password do not match";
        header("Location: ../updateStudent.php?perror=" . urlencode($em) . "&$data");
        exit;
    } elseif (!adminPasswordVerify($admin_pass, $pdo, $id)) {
        $em = "Incorrect admin password";
        header("Location: ../updateStudent.php?perror=" . urlencode($em) . "&$data");
        exit;
    } else {
        // A jelszó titkosítása
        $new_pass = password_hash($new_pass, PASSWORD_DEFAULT);

        // SQL lekérdezés a diák jelszavának frissítéséhez
        try {
            $sql = "UPDATE students SET password = ? WHERE student_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$new_pass, $student_id]);

            // Sikerüzenet
            $sm = "The password has been changed successfully!";
            header("Location: ../updateStudent.php?psuccess=" . urlencode($sm) . "&$data");
            exit;
        } catch (PDOException $e) {
            // Adatbázis hiba kezelése
            $em = "Error occurred: " . $e->getMessage();
            header("Location: ../updateStudent.php?error=" . urlencode($em) . "&$data");
            exit;
        }
    }
} else {
    // Ha bármelyik szükséges POST adat hiányzik
    $em = "An error occurred";
    header("Location: ../student-edit.php?error=" . urlencode($em) . "&$data");
    exit;
}
?>
