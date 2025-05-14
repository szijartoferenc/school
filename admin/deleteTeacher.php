<?php 
session_start();

// Ellenőrizzük, hogy az admin be van-e jelentkezve és hogy meg van-e adva a teacher_id paraméter
if (isset($_SESSION['admin_id'], $_SESSION['role'], $_GET['teacher_id'])) {

    // Csak akkor folytatjuk, ha a felhasználó Admin szerepkörrel rendelkezik
    if ($_SESSION['role'] == 'Admin') {

        // Kapcsolódás az adatbázishoz és a tanárkezelő függvények betöltése
        include "../db.php";
        include "data/teacher.php";

        // A tanár ID szűrése szám típusra (biztonságosan)
        $id = filter_var($_GET['teacher_id'], FILTER_SANITIZE_NUMBER_INT);

        // Ha érvényes ID-t kaptunk és sikeres a törlés
        if ($id && removeTeacher($id, $pdo)) {
            $sm = "A tanár sikeresen törölve lett!";
            header("Location: teacher.php?success=" . urlencode(htmlspecialchars($sm)));
            exit;
        } else {
            // Hiba történt a törlés során
            $em = "Ismeretlen hiba történt a törlés során.";
            header("Location: teacher.php?error=" . urlencode(htmlspecialchars($em)));
            exit;
        }

    } else {
        // Ha nem admin, visszairányítjuk
        header("Location: teacher.php");
        exit;
    }

} else {
    // Ha hiányzik valamilyen szükséges paraméter, visszairányítjuk
    header("Location: teacher.php");
    exit;
}
?>
