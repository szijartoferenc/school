<?php 
session_start();

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve, admin szerepkörrel rendelkezik, és hogy a course_id paraméter jelen van
if (isset($_SESSION['admin_id']) && 
    isset($_SESSION['role']) && 
    isset($_GET['course_id'])) {

  // Ha a felhasználó admin szerepkörrel rendelkezik
  if ($_SESSION['role'] == 'Admin') {

     // Adatbázis kapcsolat és szükséges fájlok betöltése
     include "../db.php";  // Adatbázis kapcsolat
     include "data/subject.php";  // Tantárgyak kezelésével kapcsolatos funkciók

     // A tantárgy azonosító lekérése a GET paraméterből
     $id = $_GET['course_id'];

     // A tantárgy törlésének próbálkozása
     if (removeCourse($id, $pdo)) {
         // Sikeres törlés esetén átirányítás a tantárgyak listájára sikerüzenettel
         $sm = "Tantárgy sikeresen törölve!";
         header("Location: course.php?success=$sm");
         exit;
     } else {
         // Hiba történt a törlés során
         $em = "Ismeretlen hiba történt";
         header("Location: course.php?error=$em");
         exit;
     }

  } else {
    // Ha a felhasználó nem admin, átirányítjuk a tantárgyak listájára
    header("Location: course.php");
    exit;
  } 

} else {
    // Ha a felhasználó nincs bejelentkezve, vagy nincs course_id paraméter, átirányítjuk a tantárgyak listájára
    header("Location: course.php");
    exit;
} 
?>
