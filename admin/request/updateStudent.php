<?php
session_start();

// Biztonságos session kezelés
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../logout.php");
    exit;
}

// A diák adatainak frissítése POST változókkal
if (isset($_POST['fname'], $_POST['lname'], $_POST['username'], $_POST['student_id'], 
          $_POST['address'], $_POST['email_address'], $_POST['gender'], 
          $_POST['date_of_birth'], $_POST['section'], $_POST['parent_fname'], 
          $_POST['parent_lname'], $_POST['parent_phone_number'], $_POST['grade'])) {

    include '../../db.php';
    include "../data/student.php";

    // A POST változók biztonságos kezelése
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $uname = $_POST['username'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $section = $_POST['section'];
    $email_address = $_POST['email_address'];
    $date_of_birth = $_POST['date_of_birth'];
    $parent_fname = $_POST['parent_fname'];
    $parent_lname = $_POST['parent_lname'];
    $parent_phone_number = $_POST['parent_phone_number'];
    $student_id = $_POST['student_id'];
    $grade = $_POST['grade'];

    // URL paraméterek előkészítése
    $data = 'student_id=' . $student_id;

    // Hibakezelés a kötelező mezőkhöz
    if (empty($fname)) {
        $em = "First name is required";
        header("Location: ../updateStudent.php?error=" . urlencode($em) . "&$data");
        exit;
    } elseif (empty($lname)) {
        $em = "Last name is required";
        header("Location: ../updateStudent.php?error=" . urlencode($em) . "&$data");
        exit;
    } elseif (empty($uname)) {
        $em = "Username is required";
        header("Location: ../updateStudent.php?error=" . urlencode($em) . "&$data");
        exit;
    } elseif (!unameIsUnique($uname, $pdo, $student_id)) {
        $em = "Username is taken! Try another.";
        header("Location: ../updateStudent.php?error=" . urlencode($em) . "&$data");
        exit;
    } elseif (empty($address)) {
        $em = "Address is required";
        header("Location: ../updateStudent.php?error=" . urlencode($em) . "&$data");
        exit;
    } elseif (empty($gender)) {
        $em = "Gender is required";
        header("Location: ../updateStudent.php?error=" . urlencode($em) . "&$data");
        exit;
    } elseif (empty($email_address)) {
        $em = "Email address is required";
        header("Location: ../updateStudent.php?error=" . urlencode($em) . "&$data");
        exit;
    } elseif (!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
        $em = "Invalid email format";
        header("Location: ../updateStudent.php?error=" . urlencode($em) . "&$data");
        exit;
    } elseif (empty($date_of_birth)) {
        $em = "Date of birth is required";
        header("Location: ../updateStudent.php?error=" . urlencode($em) . "&$data");
        exit;
    } elseif (empty($parent_fname)) {
        $em = "Parent first name is required";
        header("Location: ../updateStudent.php?error=" . urlencode($em) . "&$data");
        exit;
    } elseif (empty($parent_lname)) {
        $em = "Parent last name is required";
        header("Location: ../updateStudent.php?error=" . urlencode($em) . "&$data");
        exit;
    } elseif (empty($parent_phone_number)) {
        $em = "Parent phone number is required";
        header("Location: ../updateStudent.php?error=" . urlencode($em) . "&$data");
        exit;
    } elseif (!preg_match("/^(\+?\d{1,3})? ?\d{1,4} ?\d{3} ?\d{4}$/", $parent_phone_number)) {
        $em = "Invalid phone number format";
        header("Location: ../updateStudent.php?error=" . urlencode($em) . "&$data");
        exit;
    } elseif (empty($section)) {
        $em = "Section is required";
        header("Location: ../updateStudent.php?error=" . urlencode($em) . "&$data");
        exit;
    } else {
        // SQL lekérdezés a diák adatainak frissítéséhez
        try {
            $sql = "UPDATE students SET 
                        username = ?, fname = ?, lname = ?, grade = ?, address = ?, 
                        gender = ?, section = ?, email_address = ?, date_of_birth = ?, 
                        parent_fname = ?, parent_lname = ?, parent_phone_number = ? 
                    WHERE student_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$uname, $fname, $lname, $grade, $address, $gender, 
                            $section, $email_address, $date_of_birth, $parent_fname, 
                            $parent_lname, $parent_phone_number, $student_id]);

            // Sikerüzenet
            $sm = "Successfully updated!";
            header("Location: ../updateStudent.php?success=" . urlencode($sm) . "&$data");
            exit;
        } catch (PDOException $e) {
            // Adatbázis hiba kezelése
            $em = "Error occurred: " . $e->getMessage();
            header("Location: ../updateStudent.php?error=" . urlencode($em) . "&$data");
            exit;
        }
    }
} else {
    // Ha bármelyik szükséges POST adat hiányzik
    $em = "An error occurred";
    header("Location: ../student.php?error=" . urlencode($em));
    exit;
}
?>
