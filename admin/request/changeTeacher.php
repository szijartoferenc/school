<?php 
session_start();

// Ellenőrizzük, hogy a felhasználó adminisztrátor-e
if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) {

    // Csak akkor engedjük tovább, ha az adminisztrátor szerepkörű a felhasználó
    if ($_SESSION['role'] == 'Admin') {

        // Ellenőrizzük, hogy az admin jelszót és az új jelszót megadták-e
        if (isset($_POST['admin_pass']) && isset($_POST['new_pass']) && isset($_POST['c_new_pass']) && isset($_POST['teacher_id'])) {

            // DB kapcsolat és szükséges fájlok betöltése
            include '../../db.php';
            include "../data/teacher.php";
            include "../data/admin.php";

            // Változók beállítása a POST adatból
            $admin_pass = $_POST['admin_pass'];
            $new_pass = $_POST['new_pass'];
            $c_new_pass = $_POST['c_new_pass'];
            $teacher_id = $_POST['teacher_id'];
            $id = $_SESSION['admin_id'];  // Admin ID az aktuális session-ból
            
            // Adatok a további átirányításhoz
            $data = 'teacher_id=' . $teacher_id . '#change_password';

            // Hibakezelés: ha valamelyik mező üres
            if (empty($admin_pass)) {
                $em = "Admin jelszó megadása kötelező";
                header("Location: ../updateTeacher.php?perror=$em&$data");
                exit;
            } else if (empty($new_pass)) {
                $em = "Új jelszó megadása kötelező";
                header("Location: ../updateTeacher.php?perror=$em&$data");
                exit;
            } else if (empty($c_new_pass)) {
                $em = "Jelszó megerősítése kötelező";
                header("Location: ../updateTeacher.php?perror=$em&$data");
                exit;
            } else if ($new_pass !== $c_new_pass) {
                // Hibakezelés, ha az új jelszó és a megerősítő jelszó nem egyeznek
                $em = "Az új jelszó és a megerősítő jelszó nem egyezik";
                header("Location: ../updateTeacher.php?perror=$em&$data");
                exit;
            } else if (!adminPasswordVerify($admin_pass, $pdo, $id)) {
                // Ellenőrizzük, hogy a megadott admin jelszó helyes-e
                $em = "Hibás admin jelszó";
                header("Location: ../updateTeacher.php?perror=$em&$data");
                exit;
            } else {
                // Jelszó titkosítása
                $new_pass = password_hash($new_pass, PASSWORD_DEFAULT);

                // SQL lekérdezés a tanár jelszavának frissítésére
                $sql = "UPDATE teachers SET password = ? WHERE teacher_id=?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$new_pass, $teacher_id]);

                // Sikerüzenet beállítása és átirányítás a tanár szerkesztés oldalra
                $sm = "A jelszó sikeresen megváltozott!";
                header("Location: ../updateTeacher.php?psuccess=$sm&$data");
                exit;
            }
        } else {
            // Ha valami hiba történt a POST adatokkal
            $em = "Hiba történt";
            header("Location: ../updateTeacher.php?error=$em&$data");
            exit;
        }

    } else {
        // Ha a felhasználó nem admin, akkor kijelentkeztetjük
        header("Location: ../../logout.php");
        exit;
    } 
} else {
    // Ha a session nem tartalmazza az admin id-t vagy szerepkört, akkor kijelentkeztetjük
    header("Location: ../../logout.php");
    exit;
} 
