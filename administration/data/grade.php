<?php
/**
 * Grades adatbázis műveletek
 * Ez a fájl tartalmazza a tanulmányi évfolyamok (grades) kezeléséhez
 * szükséges alapvető függvényeket.
 */

/**
 * Lekéri az összes elérhető évfolyamot az adatbázisból.
 *
 * @param PDO $pdo Az adatbázis kapcsolat
 * @return array|int Visszatér az összes évfolyam tömbbel, vagy 0 ha nincs adat
 */
function getAllGrades($pdo) {
    $sql = "SELECT * FROM grades";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Ha van legalább egy találat, visszatérünk a rekordokkal
    if ($stmt->rowCount() >= 1) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        return 0;
    }
}

/**
 * Lekéri egy konkrét évfolyam adatait azonosító (ID) alapján.
 *
 * @param int $grade_id Az évfolyam egyedi azonosítója
 * @param PDO $pdo Az adatbázis kapcsolat
 * @return array|int Visszatér az évfolyam adataival vagy 0 ha nincs találat
 */
function getGradeById($grade_id, $pdo) {
    $sql = "SELECT * FROM grades WHERE grade_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$grade_id]);

    // Ha pontosan egy találat van, visszatérünk annak adataival
    if ($stmt->rowCount() === 1) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        return 0;
    }
}

/**
 * Törli a megadott ID-hoz tartozó évfolyamot az adatbázisból.
 *
 * @param int $id Az évfolyam azonosítója
 * @param PDO $pdo Az adatbázis kapcsolat
 * @return int 1 siker esetén, 0 hiba esetén
 */
function removeGrade($id, $pdo) {
    $sql = "DELETE FROM grades WHERE grade_id = ?";
    $stmt = $pdo->prepare($sql);
    
    // Visszatérési érték: 1 ha sikeres, 0 ha hiba történt
    return $stmt->execute([$id]) ? 1 : 0;
}
?>
