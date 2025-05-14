<?php
session_start();

// Ellenőrizzük, hogy az admin be van-e jelentkezve
if (isset($_SESSION['admin_id']) && $_SESSION['role'] === 'Admin') {

    // Minden szükséges mező meglétének ellenőrzése
    if (
        isset($_POST['fname'], $_POST['lname'], $_POST['username'], $_POST['pass'],
        $_POST['address'], $_POST['employee_number'], $_POST['phone_number'], $_POST['qualification'],
        $_POST['email_address'], $_POST['classes'], $_POST['date_of_birth'], $_POST['subjects'], $_POST['gender'])
    ) {
        // Adatbázis kapcsolat és függőségek betöltése
        include '../../db.php';
        include '../data/teacher.php';

        // POST adatok átvétele változókba
        $fname            = trim($_POST['fname']);
        $lname            = trim($_POST['lname']);
        $uname            = trim($_POST['username']);
        $pass             = $_POST['pass'];
        $address          = trim($_POST['address']);
        $employee_number  = trim($_POST['employee_number']);
        $phone_number     = trim($_POST['phone_number']);
        $qualification    = trim($_POST['qualification']);
        $email_address    = trim($_POST['email_address']);
        $gender           = $_POST['gender'];
        $date_of_birth    = $_POST['date_of_birth'];
        $classes          = implode(",", $_POST['classes']);
        $subjects         = implode(",", $_POST['subjects']);

        // Hibák esetén visszatérés az űrlapra az adatokkal
        $data = http_build_query([
            'uname' => $uname,
            'fname' => $fname,
            'lname' => $lname,
            'address' => $address,
            'en' => $employee_number,
            'pn' => $phone_number,
            'qf' => $qualification,
            'email' => $email_address
        ]);

        // Kötelező mezők validálása
        if (
            empty($fname) || empty($lname) || empty($uname) || empty($pass) || empty($address) ||
            empty($employee_number) || empty($phone_number) || empty($qualification) ||
            empty($email_address) || empty($gender) || empty($date_of_birth)
        ) {
            $em = "Minden mező kitöltése kötelező!";
            header("Location: ../addTeacher.php?error=$em&$data");
            exit;
        }

        // Felhasználónév egyediségének ellenőrzése
        if (!unameIsUnique($uname, $pdo)) {
            $em = "A felhasználónév már foglalt! Kérlek válassz másikat.";
            header("Location: ../addTeacher.php?error=$em&$data");
            exit;
        }

        // Jelszó titkosítása
        $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

        // SQL lekérdezés előkészítése és végrehajtása
        $sql = "INSERT INTO teachers 
                (username, password, class, fname, lname, subjects, address, employee_number, date_of_birth, phone_number, qualification, gender, email_address) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $uname, $hashed_pass, $classes, $fname, $lname, $subjects, $address,
            $employee_number, $date_of_birth, $phone_number, $qualification, $gender, $email_address
        ]);

        // Sikeres regisztráció
        $_SESSION['success'] = "Az új tanár sikeresen regisztrálva lett!";
        header("Location: ../addTeacher.php");
        exit;
    } else {
        // Hiányzó POST adatok
        $em = "Hiba történt!";
        header("Location: ../addTeacher.php?error=$em");
        exit;
    }

} else {
    // Nem admin vagy nincs bejelentkezve
    header("Location: ../../logout.php");
    exit;
}
