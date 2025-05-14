<?php 
// Munkamenet indítása
session_start();

// Ellenőrzés: admin be van jelentkezve és grade_id meg van adva URL-ben
if (isset($_SESSION['admin_id']) && 
    isset($_SESSION['role']) && 
    isset($_GET['grade_id'])) {

    // Csak Admin szerepkörű felhasználók végezhetnek törlést
    if ($_SESSION['role'] == 'Admin') {

        // Adatbázis kapcsolat és segédfüggvények betöltése
        include "../db.php";
        include "data/grade.php";

        // Azonosító lekérése GET paraméterből
        $id = $_GET['grade_id'];

        // Évfolyam törlése az adatbázisból
        if (removeGrade($id, $pdo)) {
            // Sikeres törlés esetén visszairányítás sikerüzenettel
            $sm = urlencode("Sikeres törlés!");
            header("Location: grade.php?success=$sm");
            exit;
        } else {
            // Hiba történt a törlés során
            $em = urlencode("Ismeretlen hiba történt a törlés során.");
            header("Location: grade.php?error=$em");
            exit;
        }

    } else {
        // Ha nem admin, visszairányítás az évfolyam oldalra
        header("Location: grade.php");
        exit;
    }

} else {
    // Ha hiányzik a jogosultság vagy a grade_id, visszairányítás
    header("Location: grade.php");
    exit;
}
?>
