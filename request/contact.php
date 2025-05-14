<?php  

// Ellenőrizzük, hogy az űrlap POST-tal küldte-e be az összes mezőt
if (isset($_POST['email']) &&
    isset($_POST['full_name']) &&
    isset($_POST['message'])) {

    include "../db.php"; // Adatbázis kapcsolat betöltése
	
	// Bemeneti adatok eltárolása változókba (tisztítás ajánlott)
	$email     = trim($_POST['email']);
	$full_name = trim($_POST['full_name']);
	$message   = trim($_POST['message']);

	// Üres mezők ellenőrzése
	if (empty($email)) {
		$em = "Email kötelező";
		header("Location: ../index.php?error=$em#contact");
		exit;

	} else if (empty($full_name)) {
		$em = "Teljes név kötelező";
		header("Location: ../index.php?error=$em#contact");
		exit;

	} else if (empty($message)) {
		$em = "Üzenet kötelező"; // 
		header("Location: ../index.php?error=$em#contact");
		exit;

	} else {
		// Üzenet mentése adatbázisba biztonságosan, prepared statement-tel
        $sql  = "INSERT INTO message (sender_full_name, sender_email, message)
                 VALUES(?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$full_name, $email, $message]);

        // Sikeres üzenetküldés után visszairányítás sikeres státusszal
        $sm = "Az üzenet sikeresen elküldve";
        header("Location: ../index.php?success=$sm#contact");
        exit;
	}

} else {
	// Ha valaki közvetlenül próbálja elérni a fájlt POST nélkül
	header("Location: ../login.php");
	exit;
}
