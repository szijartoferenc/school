<?php
/**
 * Az összes osztályzat lekérdezése az adatbázisból.
 *
 * @param PDO $pdo Az adatbázis kapcsolat
 * @return array|null Osztályzatok tömbje vagy null, ha nincs adat
 */
function getAllGrades(PDO $pdo): ?array {
    $sql = "SELECT * FROM grades";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $grades = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $grades ?: null;
}

/**
 * Egy adott osztályzat lekérdezése azonosító alapján.
 *
 * @param int $grade_id Az osztályzat azonosítója
 * @param PDO $pdo Az adatbázis kapcsolat
 * @return array|null Az osztályzat adatai vagy null, ha nem található
 */
function getGradeById(int $grade_id, PDO $pdo): ?array {
    $sql = "SELECT * FROM grades WHERE grade_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$grade_id]);

    $grade = $stmt->fetch(PDO::FETCH_ASSOC);
    return $grade ?: null;
}

/**
 * Osztályzat törlése azonosító alapján.
 *
 * @param int $id Az osztályzat azonosítója
 * @param PDO $pdo Az adatbázis kapcsolat
 * @return bool TRUE, ha sikeres a törlés, különben FALSE
 */
function removeGrade(int $id, PDO $pdo): bool {
    $sql = "DELETE FROM grades WHERE grade_id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$id]);
}
?>
