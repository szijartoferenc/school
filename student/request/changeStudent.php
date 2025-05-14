<?php 
session_start();

// Jogosultság-ellenőrzés
if (isset($_SESSION['student_id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'Student') {

    // Form input ellenőrzés
    if (isset($_POST['old_pass'], $_POST['new_pass'], $_POST['c_new_pass'])) {

        include '../../db.php';
        include "../data/student.php";

        $old_pass = $_POST['old_pass'];
        $new_pass = $_POST['new_pass'];
        $c_new_pass = $_POST['c_new_pass'];
        $student_id = $_SESSION['student_id'];

        // Ellenőrzések
        if (empty($old_pass)) {
            $em = "A régi jelszó megadása kötelező.";
        } elseif (empty($new_pass)) {
            $em = "Az új jelszó megadása kötelező.";
        } elseif (empty($c_new_pass)) {
            $em = "Az új jelszó megerősítése kötelező.";
        } elseif ($new_pass !== $c_new_pass) {
            $em = "Az új jelszó és a megerősítés nem egyezik.";
        } elseif (!studentPasswordVerify($old_pass, $pdo, $student_id)) {
            $em = "A régi jelszó hibás.";
        }

        // Ha hiba történt, visszairányítás
        if (isset($em)) {
            header("Location: ../pass.php?perror=" . urlencode($em));
            exit;
        }

        // Jelszó frissítése
        $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);

        $sql = "UPDATE students SET password = ? WHERE student_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$hashed_pass, $student_id]);

        $sm = "A jelszavad sikeresen frissítve lett!";
        header("Location: ../pass.php?psuccess=" . urlencode($sm));
        exit;

    } else {
        // Hibás formküldés
        $em = "Hiányzó adat a jelszó módosításához.";
        header("Location: ../pass.php?perror=" . urlencode($em));
        exit;
    }

} else {
    // Nem bejelentkezett vagy nem tanuló
    header("Location: ../../logout.php");
    exit;
}
