<?php
session_start();

// Csak adminisztrátor férhet hozzá ehhez a funkcióhoz
if (isset($_SESSION['admin_id'], $_SESSION['role']) && $_SESSION['role'] === 'Admin') {

    // Ellenőrzés, hogy az összes szükséges POST érték be van-e állítva
    if (
        isset($_POST['admin_pass'], $_POST['new_pass'], $_POST['c_new_pass'], $_POST['r_user_id'])
    ) {
        include '../../DB_connection.php';
        include '../data/registrationoffice.php';
        include '../data/admin.php';

        // Adatok lekérdezése és tisztítása
        $admin_pass   = trim($_POST['admin_pass']);
        $new_pass     = trim($_POST['new_pass']);
        $c_new_pass   = trim($_POST['c_new_pass']);
        $r_user_id    = trim($_POST['r_user_id']);
        $admin_id     = $_SESSION['admin_id'];

        // Visszatérő hivatkozáshoz paraméter
        $data = 'r_user_id=' . urlencode($r_user_id) . '#change_password';

        // Validációs ellenőrzések
        if (empty($admin_pass)) {
            $error = "Admin password is required";
        } elseif (empty($new_pass)) {
            $error = "New password is required";
        } elseif (empty($c_new_pass)) {
            $error = "Confirmation password is required";
        } elseif ($new_pass !== $c_new_pass) {
            $error = "New password and confirmation do not match";
        } elseif (!adminPasswordVerify($admin_pass, $pdo, $admin_id)) {
            $error = "Incorrect admin password";
        }

        // Hiba esetén visszairányítás
        if (isset($error)) {
            header("Location: ../updateRegistrationOffice.php?perror=" . urlencode($error) . "&$data");
            exit;
        }

        // Jelszó biztonságos titkosítása
        $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);

        // Jelszó frissítése adatbázisban
        $sql = "UPDATE registrar_office SET password = ? WHERE r_user_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$hashed_pass, $r_user_id]);

        // Sikeres üzenet visszaküldése
        $success = "The password has been changed successfully!";
        header("Location: ../updateRegistrationOffice.php?psuccess=" . urlencode($success) . "&$data");
        exit;

    } else {
        // POST adatok hiányosak
        $error = "An error occurred";
        header("Location: ../registrationoffice.php?error=" . urlencode($error));
        exit;
    }

} else {
    // Jogosulatlan hozzáférés → kijelentkeztetés
    header("Location: ../../logout.php");
    exit;
}
?>
