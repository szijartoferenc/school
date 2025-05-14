<?php 
session_start();

if (isset($_SESSION['teacher_id'], $_SESSION['role']) && $_SESSION['role'] == 'Teacher') {

    if (isset($_POST['old_pass'], $_POST['new_pass'], $_POST['c_new_pass'])) {
    
        include '../../db.php';
        include "../data/student.php";

        $old_pass = $_POST['old_pass'];
        $new_pass = $_POST['new_pass'];
        $c_new_pass = $_POST['c_new_pass'];

        $teacher_id = $_SESSION['teacher_id'];

        // Üres mezők ellenőrzése
        if (empty($old_pass)) {
            $em = "Régi jelszó szükséges";
            header("Location: ../pass.php?perror=$em");
            exit;
        }
        if (empty($new_pass)) {
            $em = "Új jelszó szükséges";
            header("Location: ../pass.php?perror=$em");
            exit;
        }
        if (empty($c_new_pass)) {
            $em = "Megerősítő jelszó szükséges";
            header("Location: ../pass.php?perror=$em");
            exit;
        }
        // Jelszavak egyezőségének ellenőrzése
        if ($new_pass !== $c_new_pass) {
            $em = "Az új jelszó és a megerősítési jelszó nem egyezik";
            header("Location: ../pass.php?perror=$em");
            exit;
        }
        // Régi jelszó ellenőrzése
        if (!studentPasswordVerify($old_pass, $pdo, $teacher_id)) {
            $em = "Incorrect old password";
            header("Location: ../pass.php?perror=$em");
            exit;
        }
        
        // Új jelszó hashelése
        $new_pass = password_hash($new_pass, PASSWORD_DEFAULT);

        // Jelszó frissítése az adatbázisban
        $sql = "UPDATE teachers SET password = ? WHERE teacher_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$new_pass, $teacher_id]);

        // Sikerüzenet
        $sm = "A jelszó sikeresen megváltoztatva!";
        header("Location: ../pass.php?psuccess=$sm");
        exit;
    
    } else {
        $em = "Váratlan hiba történt!";
        header("Location: ../pass.php?error=$em");
        exit;
    }

} else {
    header("Location: ../../logout.php");
    exit;
}
?>
