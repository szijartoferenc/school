<?php
/**
 * Osztálykezelő függvények admin felülethez
 * Minden függvény PDO-val dolgozik és biztonságos, optimalizált megoldást nyújt.
 */

/**
 * Lekér minden osztályt az adatbázisból.
 *
 * @param PDO $pdo Az adatbázis kapcsolat
 * @return array Az osztályok tömbje (üres tömb, ha nincs találat)
 */
function getAllClasses(PDO $pdo): array {
    $sql = "SELECT * FROM class";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Ha találat van, visszaadjuk a tömböt, egyébként üres tömböt
    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}

/**
 * Lekér egy adott osztályt ID alapján.
 *
 * @param int $class_id Az osztály azonosítója
 * @param PDO $pdo Az adatbázis kapcsolat
 * @return array|null Az osztály adatai vagy null, ha nincs találat
 */
function getClassById(int $class_id, PDO $pdo): ?array {
    $sql = "SELECT * FROM class WHERE class_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$class_id]);

    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

/**
 * Töröl egy osztályt az adatbázisból ID alapján.
 *
 * @param int $id Az osztály azonosítója
 * @param PDO $pdo Az adatbázis kapcsolat
 * @return bool TRUE, ha a törlés sikeres, különben FALSE
 */
function removeClass(int $id, PDO $pdo): bool {
    $sql = "DELETE FROM class WHERE class_id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$id]);
}
?>
