<?php 
session_start();

// Ellenőrizzük, hogy admin van-e bejelentkezve és kapott-e student_id paramétert
if (isset($_SESSION['admin_id']) && 
    isset($_SESSION['role']) && 
    $_SESSION['role'] === 'Admin' &&
    isset($_GET['student_id'])) {

    // Adatbázis kapcsolat és függvények betöltése
    include "../db.php";
    include "data/student.php";

    // Diák azonosító kinyerése és biztonságos kezelése
    $id = intval($_GET['student_id']); // mindig konvertáljuk int-re, hogy elkerüljük az SQL injection-t

    // Diák törlése és visszajelzés kezelése
    if (removeStudent($id, $pdo)) {
        $sm = "A diák sikeresen törölve lett!";
        header("Location: student.php?success=" . urlencode($sm));
        exit;
    } else {
        $em = "Ismeretlen hiba történt a törlés során.";
        header("Location: student.php?error=" . urlencode($em));
        exit;
    }

} else {
    // Nem admin vagy hiányzik a student_id → átirányítás tanárok oldalára
    header("Location: teacher.php");
    exit;
}
