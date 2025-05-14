<?php  
/**
 * Az összes szakasz lekérdezése az adatbázisból.
 *
 * @param PDO $pdo Adatbázis kapcsolat
 * @return array Az összes szakasz rekord tömbje, vagy üres tömb ha nincs adat
 */
function getAllSections(PDO $pdo): array {
    $sql = "SELECT * FROM section";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Minden rekord visszaadása tömbként (üres tömb, ha nincs találat)
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Egy adott szakasz lekérdezése azonosító alapján.
 *
 * @param int $section_id A keresett szakasz azonosítója
 * @param PDO $pdo Adatbázis kapcsolat
 * @return array|null A szakasz adatai, vagy null ha nem található
 */
function getSectionById(int $section_id, PDO $pdo): ?array {
    $sql = "SELECT * FROM section WHERE section_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$section_id]);

    // Egyedi rekord visszaadása, vagy null ha nem található
    $section = $stmt->fetch(PDO::FETCH_ASSOC);
    return $section ?: null;
}

/**
 * Egy szakasz törlése azonosító alapján.
 *
 * @param int $id A törlendő szakasz azonosítója
 * @param PDO $pdo Adatbázis kapcsolat
 * @return bool Igaz, ha sikeres volt a törlés, különben hamis
 */
function removeSection(int $id, PDO $pdo): bool {
    $sql = "DELETE FROM section WHERE section_id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$id]);
}
?>
