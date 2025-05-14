<?php
session_start();

// Csak akkor engedjük futni a kódot, ha admin van bejelentkezve
if (isset($_SESSION['admin_id'], $_SESSION['role']) && $_SESSION['role'] === 'Admin') {

    // Ellenőrizzük, hogy a szükséges POST változók be vannak állítva
    if (isset($_POST['section'], $_POST['section_id'])) {
        
        include '../../db.php';

        $section = trim($_POST['section']);
        $section_id = $_POST['section_id'];
        $data = 'section_id=' . urlencode($section_id);

        // Ellenőrizzük, hogy a mező nem üres
        if (empty($section)) {
            $error = "Section is required";
            header("Location: ../updateSection.php?error=" . urlencode($error) . "&$data");
            exit;
        }

        try {
            // Frissítési lekérdezés előkészítése
            $sql = "UPDATE section SET section = ? WHERE section_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$section, $section_id]);

            $success = "Section updated successfully";
            header("Location: ../updateSection.php?success=" . urlencode($success) . "&$data");
            exit;

        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
            header("Location: ../updateSection.php?error=" . urlencode($error) . "&$data");
            exit;
        }

    } else {
        $error = "Invalid form submission";
        header("Location: ../section.php?error=" . urlencode($error));
        exit;
    }

} else {
    // Nem admin vagy nincs bejelentkezve → kijelentkeztetés
    header("Location: ../../logout.php");
    exit;
}
?>
