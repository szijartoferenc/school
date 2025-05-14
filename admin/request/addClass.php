<?php 
session_start();

// Ellenőrizzük, hogy az admin be van-e jelentkezve és jogosult-e
if (isset($_SESSION['admin_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'Admin') {

    // Csak akkor dolgozunk tovább, ha a szükséges adatok be vannak küldve POST-tal
    if (isset($_POST['grade']) && isset($_POST['section'])) {
        
        require_once '../../db.php'; // Adatbázis kapcsolat betöltése

        // Felhasználói bemenetek biztonságos elmentése
        $grade = trim($_POST['grade']);
        $section = trim($_POST['section']);

        // Validáció: Osztálynév (section) megadása kötelező
        if (empty($section)) {
            $error = urlencode("Osztálynév kötelező");
            header("Location: ../addClass.php?error=$error");
            exit;
        }

        // Validáció: Évfolyam (grade) megadása kötelező
        if (empty($grade)) {
            $error = urlencode("Évfolyam megadása kötelező");
            header("Location: ../addClass.php?error=$error");
            exit;
        }

        // Ellenőrizzük, hogy létezik-e már ilyen osztály (évfolyam + szekció)
        $checkQuery = "SELECT 1 FROM class WHERE grade = ? AND section = ?";
        $checkStmt = $pdo->prepare($checkQuery);
        $checkStmt->execute([$grade, $section]);

        if ($checkStmt->rowCount() > 0) {
            // Ha már létezik ilyen osztály, hibaüzenet
            $error = urlencode("Az osztály már létezik");
            header("Location: ../addClass.php?error=$error");
            exit;
        }

        // Ha még nem létezik, akkor létrehozzuk az új osztályt
        $insertQuery = "INSERT INTO class (grade, section) VALUES (?, ?)";
        $insertStmt = $pdo->prepare($insertQuery);
        $insertStmt->execute([$grade, $section]);

        // Sikeres beszúrás után visszairányítunk egy üzenettel
        $success = urlencode("New class created successfully");
        header("Location: ../addClass.php?success=$success");
        exit;

    } else {
        // Ha a POST adatok hiányoznak
        $error = urlencode("An error occurred");
        header("Location: ../addClass.php?error=$error");
        exit;
    }

} else {
    // Jogosulatlan hozzáférés esetén kijelentkeztetjük a felhasználót
    header("Location: ../../logout.php");
    exit;
}
?>
