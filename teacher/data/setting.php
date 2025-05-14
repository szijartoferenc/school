<?php 

// Beállítás lekérdezése
function getSetting($pdo) {
    try {
        $sql = "SELECT * FROM setting";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            return $stmt->fetch(); // Ha van találat, visszaadjuk a beállítást
        } else {
            return null; // Ha nincs találat, null értéket adunk vissza
        }
    } catch (PDOException $e) {
        // Hibakezelés
        return "Hiba történt: " . $e->getMessage();
    }
}
?>
