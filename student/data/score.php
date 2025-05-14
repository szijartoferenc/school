<?php
/**
 * Egy adott diák összes pontszámának lekérdezése azonosító alapján.
 *
 * @param int $student_id A diák azonosítója
 * @param PDO $pdo Az adatbázis kapcsolat
 * @return array|null A diák pontszámai (év szerinti csökkenő sorrendben) vagy null, ha nincs adat
 */
function getScoreById(int $student_id, PDO $pdo): ?array {
    $sql = "SELECT * FROM student_score WHERE student_id = ? ORDER BY year DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$student_id]);

    $student_scores = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $student_scores ?: null;
}

/**
 * Érdemjegy kiszámítása százalékos pontszám alapján.
 *
 * @param int|float $grade A pontszám (0–100 közötti érték)
 * @return string Az érdemjegy betűjele
 */
function gradeCalc($grade){
    $g = "";
    if ($grade >= 90) {
        $g = "5 (Jeles)";
    } else if ($grade >= 75) {
        $g = "4 (Jó)";
    } else if ($grade >= 60) {
        $g = "3 (Közepes)";
    } else if ($grade >= 40) {
        $g = "2 (Elégséges)";
    } else {
        $g = "1 (Elégtelen)";
    }
    return $g;
 }
 
?>
