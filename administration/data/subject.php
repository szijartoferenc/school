<?php
/**
 * Tantárgyak (Subjects) adatbázis műveletek
 * Ez a fájl tartalmazza a tantárgyak lekérdezéséhez szükséges függvényeket.
 */

/**
 * Összes tantárgy lekérdezése az adatbázisból.
 *
 * @param PDO $pdo Az adatbázis kapcsolat objektum
 * @return array|int Tantárgyak tömbként vagy 0, ha nincs adat
 */
function getAllSubjects($pdo) {
    $sql = "SELECT * FROM subjects";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return ($stmt->rowCount() >= 1) ? $stmt->fetchAll(PDO::FETCH_ASSOC) : 0;
}

/**
 * Egy tantárgy lekérdezése azonosító alapján.
 *
 * @param int $subject_id A keresett tantárgy azonosítója
 * @param PDO $pdo Az adatbázis kapcsolat objektum
 * @return array|int A tantárgy adatai tömbként vagy 0, ha nincs találat
 */
function getSubjectById($subject_id, $pdo) {
    $sql = "SELECT * FROM subjects WHERE subject_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$subject_id]);

    return ($stmt->rowCount() === 1) ? $stmt->fetch(PDO::FETCH_ASSOC) : 0;
}
?>
