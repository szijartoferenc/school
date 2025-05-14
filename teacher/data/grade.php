<?php
// Minden osztályzat lekérdezése
function getAllGrades($pdo) {
    try {
        $sql = "SELECT * FROM grades";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        // Ellenőrizzük, hogy van-e találat
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll(); // Minden osztályzat adatának visszaadása
        } else {
            return []; // Ha nincs találat, üres tömböt adunk vissza
        }
    } catch (PDOException $e) {
        // Hibakezelés
        return "Hiba történt: " . $e->getMessage();
    }
}

// Osztályzat lekérdezése ID alapján
function getGradeById($grade_id, $pdo) {
    try {
        $sql = "SELECT * FROM grades WHERE grade_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$grade_id]);

        if ($stmt->rowCount() == 1) {
            return $stmt->fetch(); // Osztályzat adatának visszaadása
        } else {
            return null; // Ha nincs találat, null értéket adunk vissza
        }
    } catch (PDOException $e) {
        // Hibakezelés
        return "Hiba történt: " . $e->getMessage();
    }
}

// Osztályzat törlése
function removeGrade($id, $pdo) {
    try {
        $sql = "DELETE FROM grades WHERE grade_id = ?";
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
