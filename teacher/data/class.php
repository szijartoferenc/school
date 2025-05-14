<?php
// Minden osztály lekérdezése
function getAllClasses($pdo) {
    try {
        $sql = "SELECT * FROM class";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        // Ellenőrzés, hogy van-e találat
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll(); // Minden osztály adatának visszaadása
        } else {
            return []; // Ha nincs találat, üres tömböt adunk vissza
        }
    } catch (PDOException $e) {
        // Hibakezelés
        return "Hiba történt: " . $e->getMessage();
    }
}

// Osztály lekérdezése ID alapján
function getClassById($class_id, $pdo) {
    try {
        $sql = "SELECT * FROM class WHERE class_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$class_id]);

        if ($stmt->rowCount() == 1) {
            return $stmt->fetch(); // Osztály adatának visszaadása
        } else {
            return null; // Ha nincs találat, null értéket adunk vissza
        }
    } catch (PDOException $e) {
        // Hibakezelés
        return "Hiba történt: " . $e->getMessage();
    }
}

// Osztály törlése
function removeClass($id, $pdo) {
    try {
        $sql = "DELETE FROM class WHERE class_id = ?";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([$id]);

        // Ellenőrizzük, hogy sikeres volt-e a törlés
        if ($result) {
            return true; // Törlés sikeres
        } else {
            return false; // Törlés nem sikerült
        }
    } catch (PDOException $e) {
        // Hibakezelés
        return "Hiba történt: " . $e->getMessage();
    }
}
?>
