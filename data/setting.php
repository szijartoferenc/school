<?php
/**
 * Lekéri az iskola beállításait az adatbázisból.
 *
 * @param PDO $pdo Az adatbázis kapcsolat.
 * @return array|int Beállításokat tartalmazó asszociatív tömb vagy 0 ha nincs bejegyzés.
 */
function getSetting($pdo) {
    // SQL lekérdezés: minden mező lekérése a 'setting' táblából
    $sql = "SELECT * FROM setting";

    // Előkészíti a lekérdezést a biztonság érdekében (SQL injection ellen)
    $stmt = $pdo->prepare($sql);

    // Lekérdezés futtatása
    $stmt->execute();

    // Ellenőrzi, hogy pontosan egy beállítási rekord található-e
    if ($stmt->rowCount() === 1) {
        // Az egyetlen beállítási rekord lekérése asszociatív tömbként
        $settings = $stmt->fetch(PDO::FETCH_ASSOC);
        return $settings;
    } else {
        // Ha nincs vagy több mint egy beállítás található, visszatér 0-val
        return 0;
    }
}
