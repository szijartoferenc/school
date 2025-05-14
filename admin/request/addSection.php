<?php
session_start();

// Admin jogosultság ellenőrzése
if (isset($_SESSION['admin_id'], $_SESSION['role']) && $_SESSION['role'] === 'Admin') {

    // Ellenőrizzük, hogy be lett-e küldve a "section" mező
    if (isset($_POST['section'])) {
        include '../../db.php';

        // Tisztítjuk az adatot
        $section = trim($_POST['section']);

        // Validáció: a mező nem lehet üres
        if (empty($section)) {
            $error = "Szekció kötelező";
            header("Location: ../addSection.php?error=" . urlencode($error));
            exit;
        }

        try {
            // Előkészített utasítás a beszúráshoz
            $sql  = "INSERT INTO section (section) VALUES (?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$section]);

            // Sikeres beszúrás
            $success = "Új mező sikeresen beszúrva";
            header("Location: ../addSection.php?success=" . urlencode($success));
            exit;

        } catch (PDOException $e) {
            // Hiba történt az adatbázis művelet során
            $error = "Adatbázis művelet hiba: " . $e->getMessage();
            header("Location: ../addSection.php?error=" . urlencode($error));
            exit;
        }

    } else {
        // Hiányzó POST mező
        $error = "Érvénytelen kérés";
        header("Location: ../addSection.php?error=" . urlencode($error));
        exit;
    }

} else {
    // Jogosulatlan hozzáférés → kijelentkeztetés
    header("Location: ../../logout.php");
    exit;
}
?>
