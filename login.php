<!DOCTYPE html>
<html lang="hu">
<head>
	<meta charset="UTF-8">
	<!-- Mobilbarát nézet -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Bejelentkezés - St Mary Elementary School</title>
	
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
	
	<!-- Egyéni stíluslap -->
	<link rel="stylesheet" href="css/style.css">
	
	<!-- Weboldal favicon -->
	<link rel="icon" href="logo.png">
</head>
<body class="body-login">
    <div class="black-fill"><br><br>
    	<div class="d-flex justify-content-center align-items-center flex-column">
    		
    		<!-- Login űrlap -->
	    	<form class="login" method="post" action="request/login.php">

	    		<!-- Logo -->
	    		<div class="text-center">
	    			<img src="logo.png" width="100" alt="Y School Logo">
	    		</div>

	    		<!-- Címsor -->
	    		<h3>BEJELENTKEZÉS</h3>

	    		<!-- Hibaüzenet megjelenítése (ha van) -->
	    		<?php if (isset($_GET['error'])) { ?>
		    		<div class="alert alert-danger" role="alert">
					  <?=htmlspecialchars($_GET['error'])?>
					</div>
				<?php } ?>

				<!-- Felhasználónév mező -->
				<div class="mb-3">
				    <label class="form-label">Felhasználónév</label>
				    <input type="text" class="form-control" name="uname" required>
				</div>

				<!-- Jelszó mező -->
				<div class="mb-3">
				    <label class="form-label">Jelszó</label>
				    <input type="password" class="form-control" name="pass" required>
				</div>

				<!-- Szerepkör kiválasztása -->
				<div class="mb-3">
				    <label class="form-label">Bejelentkezés, mint</label>
				    <select class="form-control" name="role" required>
				    	<option value="">-- Szerep kiválasztása --</option>
				    	<option value="1">Admin</option>
				    	<option value="2">Tanár</option>
				    	<option value="3">Tanuló</option>
				    	<option value="4">Adminisztráció</option>
				    </select>
				</div>

				<!-- Bejelentkezés gomb -->
				<button type="submit" class="btn btn-primary w-100">Login</button>

				<!-- Vissza a főoldalra link -->
				<div class="text-center mt-2">
					<a href="index.php" class="text-decoration-none">← Vissza a kezdőoldalra</a>
				</div>
			</form>
        
	        <!-- Lábléc -->
	        <br><br>
	        <div class="text-center text-light">
	        	Copyright &copy; <?=date("Y")?> St Mary Elementary School. All rights reserved.
	        </div>
    	</div>
    </div>

    <!-- Bootstrap JS bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>	
</body>
</html>
