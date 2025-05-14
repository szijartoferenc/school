<?php 
session_start();

// Ellenőrizzük, hogy a felhasználó be van jelentkezve és jogosult-e
if (isset($_SESSION['r_user_id'], $_SESSION['role']) && $_SESSION['role'] === 'Registration Office') {
?>
<!DOCTYPE html>
<html lang="hu">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Tanulmányi Osztály - Kezdőlap</title>
	
	<!-- Bootstrap & külső stílusok -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/style.css">
	<link rel="icon" href="../logo.png">
    <script src="https://kit.fontawesome.com/929e407827.js" crossorigin="anonymous"></script>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>

    <!-- Navigációs gombok konténer -->
    <div class="container mt-5">
        <div class="text-center">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3 justify-content-center">

                <!-- Új diák regisztrálása -->
                <a href="addStudent.php" class="col btn btn-dark py-4">
                    <i class="fa fa-user-plus fs-1" aria-hidden="true"></i><br>
                    Tanuló regisztálássa
                </a> 

                <!-- Diáklista megtekintése -->
                <a href="student.php" class="col btn btn-dark py-4">
                    <i class="fa fa-user fs-1" aria-hidden="true"></i><br>
                    Összes tanuló
                </a> 

                <!-- Kijelentkezés -->
                <a href="../logout.php" class="col btn btn-warning py-4">
                    <i class="fa fa-sign-out fs-1" aria-hidden="true"></i><br>
                    Kijelentkezés
                </a> 
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>	

    <!-- Aktív menüpont kiemeléshez (ha van nav) -->
    <script>
        $(document).ready(function(){
            $("#navLinks li:nth-child(1) a").addClass('active');
        });
    </script>

</body>
</html>
<?php 
} else {
    // Jogosulatlan hozzáférés esetén visszairányítás a belépéshez
    header("Location: ../login.php");
    exit;
}
?>
