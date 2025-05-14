<?php
session_start();

// Csak akkor engedjük futni a kódot, ha admin van bejelentkezve
if (isset($_SESSION['admin_id'], $_SESSION['role']) && $_SESSION['role'] === 'Admin') {

    // Ellenőrizzük, hogy a szükséges POST változók be vannak állítva
    if (isset($_POST['school_name'], $_POST['slogan'], $_POST['about'], $_POST['current_year'], $_POST['current_semester'])) {
        
        include '../../db.php';

        // A változók biztonságos kezelése
        $school_name = trim($_POST['school_name']);
        $slogan = trim($_POST['slogan']);
        $about = trim($_POST['about']);
        $current_year = trim($_POST['current_year']);
        $current_semester = trim($_POST['current_semester']);

        // Hibaüzenetek a kötelező mezők ellenőrzésére
        if (empty($school_name)) {
            $error_message = "School name is required";
            header("Location: ../settings.php?error=" . urlencode($error_message));
            exit;
        } else if (empty($slogan)) {
            $error_message = "Slogan name is required";
            header("Location: ../settings.php?error=" . urlencode($error_message));
            exit;
        } else if (empty($about)) {
            $error_message = "About name is required";
            header("Location: ../settings.php?error=" . urlencode($error_message));
            exit;
        } else if (empty($current_year)) {
            $error_message = "Current year is required";
            header("Location: ../settings.php?error=" . urlencode($error_message));
            exit;
        } else if (empty($current_semester)) {
            $error_message = "Current semester is required";
            header("Location: ../settings.php?error=" . urlencode($error_message));
            exit;
        }

        try {
            // Frissítési lekérdezés előkészítése
            $id = 1; // Feltételezzük, hogy csak egyetlen rekordot frissítünk
            $sql = "UPDATE setting SET current_year = ?, current_semester = ?, school_name = ?, slogan = ?, about = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$current_year, $current_semester, $school_name, $slogan, $about, $id]);

            $success_message = "Settings updated successfully";
            header("Location: ../settings.php?success=" . urlencode($success_message));
            exit;

        } catch (PDOException $e) {
            // Hibakezelés adatbázis problémákra
            $error_message = "Database error: " . $e->getMessage();
            header("Location: ../settings.php?error=" . urlencode($error_message));
            exit;
        }

    } else {
        // Ha a szükséges POST változók nincsenek beállítva
        $error_message = "Invalid form submission";
        header("Location: ../settings.php?error=" . urlencode($error_message));
        exit;
    }

} else {
    // Ha a felhasználó nincs bejelentkezve admin szerepkörben, kijelentkeztetjük
    header("Location: ../../logout.php");
    exit;
}
?>
