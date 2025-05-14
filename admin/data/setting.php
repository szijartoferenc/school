<?php 

/**
 * Beállítások lekérése
 * 
 * @param PDO $pdo Az adatbázis kapcsolat
 * @return array|null A beállítások tömbje vagy null, ha nincs adat
 */
function getSetting(PDO $pdo): ?array {
    try {
        // SQL lekérdezés, ami az összes beállítást lekéri a 'setting' táblából
        $sql = "SELECT * FROM setting LIMIT 1";  // Csak az első rekordot kérjük le
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        // Ha találunk rekordot, visszaadjuk a beállításokat
        $settings = $stmt->fetch(PDO::FETCH_ASSOC);
        return $settings ?: null;  // Ha nincs találat, null-t adunk vissza
    } catch (PDOException $e) {
        // Hiba esetén null-t adunk vissza
        return null;
    }
}
?>

