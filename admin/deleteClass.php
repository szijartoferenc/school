<?php 
session_start();

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve, adminisztrátori jogosultsággal rendelkezik, és hogy van-e 'class_id' paraméter az URL-ben
if (isset($_SESSION['admin_id']) && isset($_SESSION['role']) && isset($_GET['class_id'])) {

    // Csak akkor engedjük tovább, ha a felhasználó adminisztrátori szerepkörű
    if ($_SESSION['role'] == 'Admin') {

        // Adatbázis kapcsolat és szükséges fájlok betöltése
        include "../db.php";
        include "data/class.php";

        // Az 'class_id' paraméter értékének lekérése
        $id = $_GET['class_id'];

        // Próbáljuk meg törölni az osztályt a 'removeClass' függvénnyel
        if (removeClass($id, $pdo)) {
            // Ha sikerült, visszairányítjuk a 'class.php' oldalra sikerüzenettel
            $sm = "Sikeresen törölve!";
            header("Location: class.php?success=$sm");
            exit;
        } else {
            // Ha hiba történt a törlés közben, hibát jelezünk
            $em = "Ismeretlen hiba történt";
            header("Location: class.php?error=$em");
            exit;
        }

    } else {
        // Ha a felhasználó nem adminisztrátor, visszairányítjuk a 'class.php' oldalra
        header("Location: class.php");
        exit;
    }

} else {
    // Ha nincs bejelentkezve a felhasználó vagy hiányzik a 'class_id' paraméter, visszairányítjuk a 'class.php' oldalra
    header("Location: class.php");
    exit;
}
?>
