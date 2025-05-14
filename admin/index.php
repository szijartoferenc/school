<?php 
session_start();

// Ellenőrizzük, hogy az admin be van-e jelentkezve
if (isset($_SESSION['admin_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'Admin') {
?>
<!DOCTYPE html>
<html lang="hu">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin - Kezdőlap</title>
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
	<!-- Saját stíluslap -->
	<link rel="stylesheet" href="../css/style.css">
	<!-- Weboldal ikon -->
	<link rel="icon" href="../logo.png">
	<!-- jQuery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<!-- Font Awesome ikonok -->
	<script src="https://kit.fontawesome.com/929e407827.js" crossorigin="anonymous"></script>
</head>
<body>

	<!-- Navigációs sáv betöltése -->
	<?php include "include/navbar.php"; ?>

	<!-- Admin funkciók gombjai -->
	<div class="container mt-5">
		<div class="text-center">
			<div class="row row-cols-5 justify-content-center">
				<!-- Tanárok -->
				<a href="teacher.php" class="col btn btn-dark m-2 py-3">
				<i class="fa-solid fa-person-chalkboard fs-1" aria-hidden="true"></i><br>Tanár
				</a>
				<!-- Diákok -->
				<a href="student.php" class="col btn btn-dark m-2 py-3">
					<i class="fa fa-graduation-cap fs-1" aria-hidden="true"></i><br>Diák
				</a>
				<!-- Adminisztráció -->
				<a href="registrationoffice.php" class="col btn btn-dark m-2 py-3">
					<i class="fa fa-pencil-square fs-1" aria-hidden="true"></i><br>Adminisztráció
				</a>
				<!-- Osztály -->
				<a href="class.php" class="col btn btn-dark m-2 py-3">
					<i class="fa fa-cubes fs-1" aria-hidden="true"></i><br>Osztály
				</a>
				<!-- Szekció -->
				<a href="section.php" class="col btn btn-dark m-2 py-3">
					<i class="fa fa-columns fs-1" aria-hidden="true"></i><br>Szekció
				</a>
				<!-- Évfolyam -->
				<a href="grade.php" class="col btn btn-dark m-2 py-3">
					<i class="fa fa-level-up fs-1" aria-hidden="true"></i><br>Évfolyam
				</a>
				<!-- Tantárgy -->
				<a href="course.php" class="col btn btn-dark m-2 py-3">
					<i class="fa fa-book fs-1" aria-hidden="true"></i><br>Tantárgy
				</a>
				<!-- Üzenetek -->
				<a href="message.php" class="col btn btn-dark m-2 py-3">
					<i class="fa fa-envelope fs-1" aria-hidden="true"></i><br>Üzenetek
				</a>
				<!-- Beállítások -->
				<a href="settings.php" class="col btn btn-primary m-2 py-3 col-5">
					<i class="fa fa-cogs fs-1" aria-hidden="true"></i><br>Beállítások
				</a>
				<!-- Kijelentkezés -->
				<a href="../logout.php" class="col btn btn-warning m-2 py-3 col-5">
					<i class="fa fa-sign-out fs-1" aria-hidden="true"></i><br>Kijelentkezés
				</a>
			</div>
		</div>
	</div>

	<!-- Bootstrap JS Bundle -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>	

	<!-- Aktív menüpont kijelölése -->
	<script>
		$(document).ready(function(){
			$("#navLinks li:nth-child(1) a").addClass('active');
		});
	</script>

</body>
</html>

<?php 
} else {
	// Ha nem admin vagy nem bejelentkezett, visszairányítás a login oldalra
	header("Location: ../login.php");
	exit;
}
?>
