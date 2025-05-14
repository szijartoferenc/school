<?php 
// Munkamenet indítása
session_start();

// Ellenőrzés: minden szükséges adat be lett-e küldve POST-tal
if (isset($_POST['uname']) &&
    isset($_POST['pass']) &&
    isset($_POST['role'])) {

	include "../db.php"; // Adatbázis kapcsolat importálása
	
	// Bemeneti adatok elmentése változókba
	$uname = $_POST['uname'];
	$pass = $_POST['pass'];
	$role = $_POST['role'];

	// Bemenet validálása (üres mezők ellenőrzése)
	if (empty($uname)) {
		$em  = "Username is required";
		header("Location: ../login.php?error=$em");
		exit;
	} else if (empty($pass)) {
		$em  = "Password is required";
		header("Location: ../login.php?error=$em");
		exit;
	} else if (empty($role)) {
		$em  = "An error occurred";
		header("Location: ../login.php?error=$em");
		exit;
	} else {

		// Dinamikus SQL lekérdezés és szerepkör beállítása
        if($role == '1'){
        	$sql = "SELECT * FROM admin WHERE username = ?";
        	$role = "Admin";
        } else if($role == '2'){
        	$sql = "SELECT * FROM teachers WHERE username = ?";
        	$role = "Teacher";
        } else if($role == '3'){
        	$sql = "SELECT * FROM students WHERE username = ?";
        	$role = "Student";
        } else if($role == '4'){
        	$sql = "SELECT * FROM registrationoffice WHERE username = ?";
        	$role = "Registration Office";
        }

        // Lekérdezés előkészítése és végrehajtása
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$uname]);

        // Ha van találat az adatbázisban
        if ($stmt->rowCount() == 1) {
        	$user = $stmt->fetch(); // Felhasználó adatainak lekérése
        	$username = $user['username'];
        	$password = $user['password'];
        	
        	// Felhasználónév ellenőrzése (ez opcionális, mert WHERE már szűrt)
            if ($username === $uname) {
            	// Jelszó ellenőrzése (titkosított jelszavakhoz)
            	if (password_verify($pass, $password)) {
            		
            		// Szerepkör elmentése a session-be
            		$_SESSION['role'] = $role;

            		// Szerepkörtől függően azonosító és irányítás beállítása
            		if ($role == 'Admin') {
                        $_SESSION['admin_id'] = $user['admin_id'];
                        header("Location: ../admin/index.php");
                        exit;

                    } else if ($role == 'Student') {
                        $_SESSION['student_id'] = $user['student_id'];
                        header("Location: ../student/index.php");
                        exit;

                    } else if ($role == 'Registration Office') {
                        $_SESSION['r_user_id'] = $user['r_user_id'];
                        header("Location: ../administration/index.php");
                        exit;

                    } else if ($role == 'Teacher') {
                        $_SESSION['teacher_id'] = $user['teacher_id'];
                        header("Location: ../teacher/index.php");
                        exit;

                    } else {
                    	// Biztonsági alapértelmezett ág
                    	$em = "Incorrect Username or Password";
				        header("Location: ../login.php?error=$em");
				        exit;
                    }

            	} else {
		        	// Hibás jelszó
		        	$em = "Incorrect Username or Password";
				    header("Location: ../login.php?error=$em");
				    exit;
		        }
            } else {
	        	// Hibás felhasználónév
	        	$em = "Incorrect Username or Password";
			    header("Location: ../login.php?error=$em");
			    exit;
	        }
        } else {
        	// Nincs találat az adatbázisban
        	$em = "Incorrect Username or Password";
		    header("Location: ../login.php?error=$em");
		    exit;
        }
	}
} else {
	// Ha valaki közvetlenül próbál hozzáférni a fájlhoz POST nélkül
	header("Location: ../login.php");
	exit;
}
