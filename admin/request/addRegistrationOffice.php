<?php 
session_start();

// Csak akkor engedélyezett, ha adminisztrátor van bejelentkezve
if (isset($_SESSION['admin_id'], $_SESSION['role']) && $_SESSION['role'] === 'Admin') {

    // Kötelező mezők meglétének ellenőrzése
    if (isset($_POST['fname'], $_POST['lname'], $_POST['username'], $_POST['pass'],
              $_POST['address'], $_POST['employee_number'], $_POST['phone_number'],
              $_POST['qualification'], $_POST['email_address'], $_POST['gender'], $_POST['date_of_birth'])) {
        
        include '../../db.php';
        include '../data/registrationoffice.php';

        // Bemenetek tisztítása
        $fname           = trim($_POST['fname']);
        $lname           = trim($_POST['lname']);
        $uname           = trim($_POST['username']);
        $pass            = trim($_POST['pass']);
        $address         = trim($_POST['address']);
        $employee_number = trim($_POST['employee_number']);
        $phone_number    = trim($_POST['phone_number']);
        $qualification   = trim($_POST['qualification']);
        $email_address   = trim($_POST['email_address']);
        $gender          = trim($_POST['gender']);
        $date_of_birth   = trim($_POST['date_of_birth']);

        // Hibás értékek visszatöltésére szolgáló adatcsomag (URL-en keresztül)
        $data = http_build_query([
            'uname'  => $uname,
            'fname'  => $fname,
            'lname'  => $lname,
            'address'=> $address,
            'en'     => $employee_number,
            'pn'     => $phone_number,
            'qf'     => $qualification,
            'email'  => $email_address
        ]);

        // Validációs hibák
        if (empty($fname)) {
            $error = "Keresztnév kötelező";
        } elseif (empty($lname)) {
            $error = "Vezetéknév kötelező";
        } elseif (empty($uname)) {
            $error = "Felhasználónév kötelező";
        } elseif (!isUsernameUnique($uname, $pdo)) {
            $error = "Ez a felhasználónév már foglalt!";
        } elseif (empty($pass)) {
            $error = "Jelszó kötelező";
        } elseif (empty($address)) {
            $error = "Lakcím kötelező";
        } elseif (empty($employee_number)) {
            $error = "Munkavállalói azonosító kötelező";
        } elseif (empty($phone_number)) {
            $error = "Telefonszám kötelező";
        } elseif (empty($qualification)) {
            $error = "Végzettség kötelező";
        } elseif (empty($email_address)) {
            $error = "Email cím kötelező";
        } elseif (empty($gender)) {
            $error = "Nem kiválasztása kötelező";
        } elseif (empty($date_of_birth)) {
            $error = "Születési dátum kötelező";
        }

        // Hiba esetén visszairányítás
        if (isset($error)) {
            header("Location: ../addRegistrationOffice.php?error=" . urlencode($error) . "&$data");
            exit;
        }

        // Jelszó biztonságos hash-elése
        $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

        // Adatbázisba illesztés
        $sql = "INSERT INTO registrationoffice (
                    username, password, fname, lname, address, employee_number, 
                    date_of_birth, phone_number, qualification, gender, email_address
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $uname, $hashed_pass, $fname, $lname, $address, $employee_number,
            $date_of_birth, $phone_number, $qualification, $gender, $email_address
        ]);

        // Sikeres mentés után visszajelzés
        $success = "Adminisztrátor sikeresen hozzáadva";
        header("Location: ../addRegistrationOffice.php?success=" . urlencode($success));
        exit;

    } else {
        // Hiányzó POST mezők
        header("Location: ../addRegistrationOffice.php?error=" . urlencode("Hiányzó mezők!"));
        exit;
    }

} else {
    // Jogosulatlan hozzáférés → kijelentkezés
    header("Location: ../../logout.php");
    exit;
}
?>
