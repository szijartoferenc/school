<?php 
session_start();

// Ellenőrizzük, hogy be van-e jelentkezve a megfelelő szerepkörben
if (isset($_SESSION['r_user_id'], $_SESSION['role']) && $_SESSION['role'] === 'Registration Office') {

    // Ellenőrizzük, hogy minden szükséges POST adat megvan-e
    $required_fields = [
        'fname', 'lname', 'username', 'pass', 'address', 'gender',
        'email_address', 'date_of_birth', 'parent_fname', 'parent_lname',
        'parent_phone_number', 'section', 'grade'
    ];

    $missing_field = false;
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $missing_field = $field;
            break;
        }
    }

    if ($missing_field) {
        $em = ucfirst(str_replace("_", " ", $missing_field)) . " is required";
        $data = http_build_query($_POST); // Eredeti mezők újratöltéséhez
        header("Location: ../addStudent.php?error=$em&$data");
        exit;
    }

    // Adatbázis kapcsolat és szükséges fájlok
    include '../../db.php';
    include '../data/student.php';

    // Felhasználói adatok tisztítása
    $fname  = trim($_POST['fname']);
    $lname  = trim($_POST['lname']);
    $uname  = trim($_POST['username']);
    $pass   = $_POST['pass'];
    $address = trim($_POST['address']);
    $gender = $_POST['gender'];
    $email_address = trim($_POST['email_address']);
    $date_of_birth = $_POST['date_of_birth'];
    $parent_fname = trim($_POST['parent_fname']);
    $parent_lname = trim($_POST['parent_lname']);
    $parent_phone_number = trim($_POST['parent_phone_number']);
    $grade = $_POST['grade'];
    $section = $_POST['section'];

    // Ellenőrzés: Felhasználónév egyedi-e
    if (!unameIsUnique($uname, $pdo)) {
        $em = "Username is taken! Try another.";
        $data = http_build_query($_POST);
        header("Location: ../addStudent.php?error=$em&$data");
        exit;
    }

    // Jelszó titkosítása
    $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

    // Új diák hozzáadása az adatbázishoz
    $sql = "INSERT INTO students (
                username, password, fname, lname, grade, section, 
                address, gender, email_address, date_of_birth, 
                parent_fname, parent_lname, parent_phone_number
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $pdo->prepare($sql);
    $success = $stmt->execute([
        $uname, $hashed_pass, $fname, $lname, $grade, $section,
        $address, $gender, $email_address, $date_of_birth,
        $parent_fname, $parent_lname, $parent_phone_number
    ]);

    if ($success) {
        $sm = "New student registered successfully";
        header("Location: ../addStudent.php?success=$sm");
        exit;
    } else {
        $em = "Database error. Please try again.";
        header("Location: ../addStudent.php?error=$em");
        exit;
    }

} else {
    // Ha nincs jogosultság, kiléptetés
    header("Location: ../../logout.php");
    exit;
}
?>
