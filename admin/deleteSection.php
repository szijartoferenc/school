<?php 
session_start();

// Ellenőrizzük, hogy az admin be van-e jelentkezve, és van szekció ID a GET-ben
if (
    isset($_SESSION['admin_id']) && 
    isset($_SESSION['role']) && 
    isset($_GET['section_id'])
) {
    // Csak admin jogosultság esetén engedélyezett a törlés
    if ($_SESSION['role'] === 'Admin') {

        // Adatbázis-kapcsolat és szekciókezelő függvények betöltése
        include "../db.php";
        include "data/section.php";

        // A törölni kívánt szekció azonosítója (GET paraméterből)
        $id = intval($_GET['section_id']); // Biztonságosabbá tesszük (pl. XSS ellen)

        // Törlési művelet végrehajtása
        if (removeSection($id, $pdo)) {
            // Sikeres törlés után visszairányítás sikerüzenettel
            $successMsg = "Szekció sikeresen törölve!";
            header("Location: section.php?success=" . urlencode($successMsg));
            exit;
        } else {
            // Hiba történt törlés közben
            $errorMsg = "Ismeretlen hiba történt a törlés során.";
            header("Location: section.php?error=" . urlencode($errorMsg));
            exit;
        }

    } else {
        // Ha nem admin a felhasználó, visszairányítjuk
        header("Location: section.php");
        exit;
    }

} else {
    // Ha hiányzik valamelyik kötelező adat, visszairányítjuk
    header("Location: section.php");
    exit;
}
