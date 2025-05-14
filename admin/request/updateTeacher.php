<?php 
session_start();

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve és adminisztrátori jogosultsággal rendelkezik
if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) {

    // Csak akkor engedjük tovább, ha adminisztrátori szerepkörű a felhasználó
    if ($_SESSION['role'] == 'Admin') {

        // Ellenőrizzük, hogy minden szükséges mezőt megadtak-e
        if (isset($_POST['fname']) && isset($_POST['lname']) && isset($_POST['username']) &&
            isset($_POST['teacher_id']) && isset($_POST['address']) && isset($_POST['employee_number']) &&
            isset($_POST['phone_number']) && isset($_POST['qualification']) && isset($_POST['email_address']) &&
            isset($_POST['gender']) && isset($_POST['date_of_birth']) && isset($_POST['subjects']) &&
            isset($_POST['classes'])) {
            
            // DB kapcsolat és szükséges fájlok betöltése
            include '../../db.php';
            include "../data/teacher.php";

            // A POST adatokat változókba rendeljük
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $uname = $_POST['username'];
            $address = $_POST['address'];
            $employee_number = $_POST['employee_number'];
            $phone_number = $_POST['phone_number'];
            $qualification = $_POST['qualification'];
            $email_address = $_POST['email_address'];
            $gender = $_POST['gender'];
            $date_of_birth = $_POST['date_of_birth'];
            $teacher_id = $_POST['teacher_id'];

            // Az osztályok és tantárgyak listáját összefűzzük
            $classes = isset($_POST['classes']) && is_array($_POST['classes']) ? implode(",", $_POST['classes']) : "";
            $subjects = isset($_POST['subjects']) && is_array($_POST['subjects']) ? implode(",", $_POST['subjects']) : "";


            // Átirányításhoz szükséges adatokat előkészítjük
            $data = 'teacher_id=' . $teacher_id;

            // Hibakezelés, ha valamelyik mező üres
            if (empty($fname)) {
                $em  = "A keresztnév megadása kötelező!";
                header("Location: ../updateTeacher.php?error=$em&$data");
                exit;
            } else if (empty($lname)) {
                $em  = "A vezetéknév megadása kötelező!";
                header("Location: ../updateTeacher.php?error=$em&$data");
                exit;
            } else if (empty($uname)) {
                $em  = "A felhasználónév megadása kötelező!";
                header("Location: ../updateTeacher.php?error=$em&$data");
                exit;
            } else if (!unameIsUnique($uname, $pdo, $teacher_id)) {
                // Ha a felhasználónév már foglalt
                $em  = "A felhasználónév már foglalt! Kérlek válassz másikat.";
                header("Location: ../updateTeacher.php?error=$em&$data");
                exit;
            } else if (empty($address)) {
                $em  = "A cím megadása kötelező!";
                header("Location: ../updateTeacher.php?error=$em&$data");
                exit;
            } else if (empty($employee_number)) {
                $em  = "A munkavállalói szám megadása kötelező!";
                header("Location: ../updateTeacher.php?error=$em&$data");
                exit;
            } else if (empty($phone_number)) {
                $em  = "A telefonszám megadása kötelező!";
                header("Location: ../updateTeacher.php?error=$em&$data");
                exit;
            } else if (empty($qualification)) {
                $em  = "A képesítés megadása kötelező!";
                header("Location: ../updateTeacher.php?error=$em&$data");
                exit;
            } else if (empty($email_address)) {
                $em  = "Az email cím megadása kötelező!";
                header("Location: ../updateTeacher.php?error=$em&$data");
                exit;
            } else if (empty($gender)) {
                $em  = "A nem megadása kötelező!";
                header("Location: ../updateTeacher.php?error=$em&$data");
                exit;
            } else if (empty($date_of_birth)) {
                $em  = "A születési dátum megadása kötelező!";
                header("Location: ../updateTeacher.php?error=$em&$data");
                exit;
            } else if (empty($classes)) {
                $em = "Legalább egy osztály kiválasztása kötelező!";
                header("Location: ../updateTeacher.php?error=$em&$data");
                exit;
            } else if (empty($subjects)) {
                $em = "Legalább egy tantárgy kiválasztása kötelező!";
                header("Location: ../updateTeacher.php?error=$em&$data");
                exit;
            } else {
                // SQL lekérdezés a tanár adatainak frissítésére
                $sql = "UPDATE teachers SET
                        username = ?, class = ?, fname = ?, lname = ?, subjects = ?, 
                        address = ?, employee_number = ?, date_of_birth = ?, phone_number = ?, qualification = ?, 
                        gender = ?, email_address = ? WHERE teacher_id = ?";
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$uname, $classes, $fname, $lname, $subjects, $address, $employee_number, 
                                $date_of_birth, $phone_number, $qualification, $gender, $email_address, $teacher_id]);

                // Sikeres frissítés üzenet
                $sm = "Sikeresen frissítve!";
                header("Location: ../updateTeacher.php?success=$sm&$data");
                exit;
            }

        } else {
            // Ha valami hiba történt a POST adatokkal
            $em = "Hiba történt!";
            header("Location: ../teacher.php?error=$em");
            exit;
        }

    } else {
        // Ha a felhasználó nem adminisztrátor, kijelentkeztetjük
        header("Location: ../../logout.php");
        exit;
    }
} else {
    // Ha nincs bejelentkezve a felhasználó, kijelentkeztetjük
    header("Location: ../../logout.php");
    exit;
}
?>
