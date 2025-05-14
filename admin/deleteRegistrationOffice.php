<?php 
session_start();

// Ellenőrizzük, hogy admin jogosultság és regisztrátor azonosító elérhető-e
if (
    isset($_SESSION['admin_id']) && 
    isset($_SESSION['role']) && 
    $_SESSION['role'] === 'Admin' && 
    isset($_GET['r_user_id'])
) {
    // Adatbázis kapcsolat és függvények betöltése
    include "../db.php";
    include "data/registrationoffice.php";

    // Regisztrátor azonosító
    $id = $_GET['r_user_id'];

    // Regisztrátor törlése
    if (removeRUser($id, $pdo)) {
        $success = "A regisztrátor sikeresen törölve lett.";
        header("Location: registrationoffice.php?success=" . urlencode($success));
        exit;
    } else {
        $error = "Ismeretlen hiba történt a törlés során.";
        header("Location: registrationoffice.php?error=" . urlencode($error));
        exit;
    }

} else {
    // Nincs jogosultság vagy hiányzik paraméter
    header("Location: registrationoffice.php");
    exit;
}
