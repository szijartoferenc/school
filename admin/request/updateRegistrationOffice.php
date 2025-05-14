<?php
session_start();

// Ellenőrzés: csak admin szerepkörrel rendelkező felhasználó férhet hozzá
if (isset($_SESSION['admin_id'], $_SESSION['role']) && $_SESSION['role'] === 'Admin') {

    // Kötelező POST mezők ellenőrzése
    if (
        isset($_POST['fname'], $_POST['lname'], $_POST['username'], $_POST['r_user_id'],
              $_POST['address'], $_POST['employee_number'], $_POST['phone_number'],
              $_POST['qualification'], $_POST['email_address'], $_POST['gender'],
              $_POST['date_of_birth'])
    ) {
        include '../../db.php';
        include '../data/registrationoffice.php';

        // Beérkező adatok tisztítása
        $fname            = trim($_POST['fname']);
        $lname            = trim($_POST['lname']);
        $uname            = trim($_POST['username']);
        $address          = trim($_POST['address']);
        $employee_number  = trim($_POST['employee_number']);
        $phone_number     = trim($_POST['phone_number']);
        $qualification    = trim($_POST['qualification']);
        $email_address    = trim($_POST['email_address']);
        $gender           = trim($_POST['gender']);
        $date_of_birth    = trim($_POST['date_of_birth']);
        $r_user_id        = trim($_POST['r_user_id']);

        // Visszairányításhoz adat
        $data = 'r_user_id=' . urlencode($r_user_id);

        // Validációs ellenőrzések
        if (empty($fname)) {
            $error = "First name is required";
        } elseif (empty($lname)) {
            $error = "Last name is required";
        } elseif (empty($uname)) {
            $error = "Username is required";
        } elseif (!isUsernameUnique($uname, $pdo, $r_user_id)) {
            $error = "Username is already taken";
        } elseif (empty($address)) {
            $error = "Address is required";
        } elseif (empty($employee_number)) {
            $error = "Employee number is required";
        } elseif (empty($phone_number)) {
            $error = "Phone number is required";
        } elseif (empty($qualification)) {
            $error = "Qualification is required";
        } elseif (empty($email_address)) {
            $error = "Email address is required";
        } elseif (empty($gender)) {
            $error = "Gender is required";
        } elseif (empty($date_of_birth)) {
            $error = "Date of birth is required";
        }

        // Hiba esetén visszairányítás
        if (isset($error)) {
            header("Location: ../updateRegistrationOffice.php?error=" . urlencode($error) . "&$data");
            exit;
        }

        // SQL utasítás az adatok frissítéséhez
        $sql = "UPDATE registrationoffice SET
                    username = ?, fname = ?, lname = ?, address = ?, 
                    employee_number = ?, date_of_birth = ?, phone_number = ?, 
                    qualification = ?, gender = ?, email_address = ?
                WHERE r_user_id = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $uname, $fname, $lname, $address,
            $employee_number, $date_of_birth, $phone_number,
            $qualification, $gender, $email_address, $r_user_id
        ]);

        // Sikeres frissítés üzenet
        $success = "Successfully updated!";
        header("Location: ../updateRegistrationOffice.php?success=" . urlencode($success) . "&$data");
        exit;

    } else {
        // POST mezők hiányosak
        $error = "An error occurred. Please try again.";
        header("Location: ../registrationoffice.php?error=" . urlencode($error));
        exit;
    }

} else {
    // Jogosulatlan hozzáférés esetén kijelentkeztetés
    header("Location: ../../logout.php");
    exit;
}
?>
