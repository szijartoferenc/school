<?php
/**
 * Szakaszok (Section) adatbázis műveletek
 * Ez a fájl tartalmazza a szakaszok kezeléséhez szükséges alap CRUD függvényeket.
 */

/**
 * Lekéri az összes szakaszt az adatbázisból.
 *
 * @param PDO $pdo Az adatbázis kapcsolat objektum
 * @return array|int Visszaadja a szakaszok listáját tömbként, vagy 0 ha nincs találat
 */
function getAllSections($pdo) {
    $sql = "SELECT * FROM section";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    if ($stmt->rowCount() >= 1) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        return 0;
    }
}

/**
 * Lekér egy szakaszt ID alapján.
 *
 * @param int $section_id A keresett szakasz egyedi azonosítója
 * @param PDO $pdo Az adatbázis kapcsolat objektum
 * @return array|int A szakasz adatai tömbként, vagy 0 ha nincs találat
 */
function getSectionById($section_id, $pdo) {
    $sql = "SELECT * FROM section WHERE section_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$section_id]);

    if ($stmt->rowCount() === 1) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        return 0;
    }
}

/**
 * Töröl egy szakaszt azonosító (ID) alapján.
 *
 * @param int $id A törlendő szakasz azonosítója
 * @param PDO $pdo Az adatbázis kapcsolat objektum
 * @return int 1 sikeres törlés esetén, 0 ha hiba történt
 */
function removeSection($id, $pdo) {
    $sql = "DELETE FROM section WHERE section_id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$id]) ? 1 : 0;
}
?>
