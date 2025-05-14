<?php
session_start();

// Csak akkor engedjük futni a kódot, ha admin van bejelentkezve
if (isset($_SESSION['admin_id'], $_SESSION['role']) && $_SESSION['role'] === 'Admin') {

    // Ellenőrizzük, hogy a szükséges POST változók be vannak állítva
    if (isset($_POST['fname'], $_POST['lname'], $_POST['username'], $_POST['pass'], 
        $_POST['address'], $_POST['gender'], $_POST['email_address'], 
        $_POST['date_of_birth'], $_POST['parent_fname'], $_POST['parent_lname'], 
        $_POST['parent_phone_number'], $_POST['section'], $_POST['grade'])) {

        include '../../db.php';
        include "../data/student.php";

        // A változók biztonságos kezelése (trim() a felesleges szóközök eltávolításához)
        $fname = trim($_POST['fname']);
        $lname = trim($_POST['lname']);
        $uname = trim($_POST['username']);
        $pass = $_POST['pass'];
        $address = trim($_POST['address']);
        $gender = $_POST['gender'];
        $email_address = $_POST['email_address'];
        $date_of_birth = $_POST['date_of_birth'];
        $parent_fname = $_POST['parent_fname'];
        $parent_lname = $_POST['parent_lname'];
        $parent_phone_number = $_POST['parent_phone_number'];
        $grade = $_POST['grade'];
        $section = $_POST['section'];

        // Visszairányító adatok URL paraméterekhez (ha hiba történik)
        $data = http_build_query(compact('uname', 'fname', 'lname', 'address', 'gender', 'email_address', 
            'parent_fname', 'parent_lname', 'parent_phone_number', 'section'));

        // Ellenőrizzük a kötelező mezőket
        $errors = [
            'fname' => "First name is required",
            'lname' => "Last name is required",
            'uname' => "Username is required",
            'pass' => "Password is required",
            'address' => "Address is required",
            'gender' => "Gender is required",
            'email_address' => "Email address is required",
            'date_of_birth' => "Date of birth is required",
            'parent_fname' => "Parent first name is required",
            'parent_lname' => "Parent last name is required",
            'parent_phone_number' => "Parent phone number is required",
            'section' => "Section is required"
        ];

        // Iterálunk a hibák ellenőrzésén
        foreach ($errors as $field => $message) {
            if (empty($$field)) {
                header("Location: ../addStudent.php?error=" . urlencode($message) . "&$data");
                exit;
            }
        }

        // Ellenőrizzük, hogy a felhasználónév egyedi-e
        if (!unameIsUnique($uname, $pdo)) {
            $em = "Username is taken! Try another.";
            header("Location: ../addStudent.php?error=" . urlencode($em) . "&$data");
            exit;
        }

        // Jelszó titkosítása
        $pass = password_hash($pass, PASSWORD_DEFAULT);

        // SQL lekérdezés a diák hozzáadásához
        try {
            $sql = "INSERT INTO students (username, password, fname, lname, grade, section, address, gender, email_address, date_of_birth, parent_fname, parent_lname, parent_phone_number)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$uname, $pass, $fname, $lname, $grade, $section, $address, $gender, $email_address, $date_of_birth, $parent_fname, $parent_lname, $parent_phone_number]);

            // Sikerüzenet
            $sm = "Sikeres új tanuló regisztráció";
            header("Location: ../addStudent.php?success=" . urlencode($sm));
            exit;
        } catch (PDOException $e) {
            // Hibakezelés
            $em = "Database error: " . $e->getMessage();
            header("Location: ../addStudent.php?error=" . urlencode($em));
            exit;
        }

    } else {
        // Ha bármelyik szükséges POST adat hiányzik
        $em = "Hiba történt, hiányzó adatok";
        header("Location: ../addStudent.php?error=" . urlencode($em));
        exit;
    }

} else {
    // Ha nem admin van bejelentkezve, kiléptetjük
    header("Location: ../../logout.php");
    exit;
}
?>
