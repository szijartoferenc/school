<?php  

// Minden szakasz lekérdezése
function getAllSections($pdo) {
    try {
        $sql = "SELECT * FROM section";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll(); // Visszaadjuk az összes szakaszt
        } else {
            return []; // Ha nincs találat, üres tömböt adunk vissza
        }
    } catch (PDOException $e) {
        // Hibakezelés
        return "Hiba történt: " . $e->getMessage();
    }
}

// Szakasz lekérdezése ID alapján
function getSectionById($section_id, $pdo) {
    try {
        $sql = "SELECT * FROM section WHERE section_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$section_id]);

        if ($stmt->rowCount() == 1) {
            return $stmt->fetch(); // Visszaadjuk a szakasz adatokat
        } else {
            return null; // Ha nincs találat, null értéket adunk vissza
        }
    } catch (PDOException $e) {
        // Hibakezelés
        return "Hiba történt: " . $e->getMessage();
    }
}

// Szakasz törlése
function removeSection($id, $pdo) {
    try {
        $sql = "DELETE FROM section WHERE section_id = ?";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([$id]);

        if ($result) {
            return true; // Sikeres törlés
        } else {
            return false; // Sikertelen törlés
        }
    } catch (PDOException $e) {
        // Hibakezelés
        return "Hiba történt: " . $e->getMessage();
    }
}
?>
