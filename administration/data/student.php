<?php
/**
 * Diákok (Students) adatbázis műveletek
 * Ez a fájl tartalmazza a diákokra vonatkozó CRUD funkciókat és keresési logikát.
 */

/**
 * Összes diák lekérdezése az adatbázisból.
 *
 * @param PDO $pdo Az adatbázis kapcsolat objektum
 * @return array|int Diákok tömbként vagy 0, ha nincs adat
 */
function getAllStudents($pdo) {
    $sql = "SELECT * FROM students";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return ($stmt->rowCount() >= 1) ? $stmt->fetchAll(PDO::FETCH_ASSOC) : 0;
}

/**
 * Egy diák lekérdezése azonosító alapján.
 *
 * @param int $id A keresett diák azonosítója
 * @param PDO $pdo Az adatbázis kapcsolat objektum
 * @return array|int A diák adatai tömbként vagy 0, ha nincs találat
 */
function getStudentById($id, $pdo) {
    $sql = "SELECT * FROM students WHERE student_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    return ($stmt->rowCount() === 1) ? $stmt->fetch(PDO::FETCH_ASSOC) : 0;
}

/**
 * Ellenőrzi, hogy egy felhasználónév (username) egyedi-e.
 *
 * @param string $uname A vizsgált felhasználónév
 * @param PDO $pdo Az adatbázis kapcsolat objektum
 * @param int $student_id Az aktuális diák azonosítója (alapértelmezett: 0)
 * @return int 1 ha egyedi, 0 ha nem
 */
function unameIsUnique($uname, $pdo, $student_id = 0) {
    $sql = "SELECT username, student_id FROM students WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$uname]);

    if ($stmt->rowCount() >= 1) {
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        // Ha frissítésről van szó, engedélyezzük a meglévő usernevét
        return ($student['student_id'] == $student_id) ? 1 : 0;
    }

    return 1;
}

/**
 * Diákok keresése kulcsszó alapján.
 *
 * @param string $key A keresési kulcsszó
 * @param PDO $pdo Az adatbázis kapcsolat objektum
 * @return array|int Találatok tömbként vagy 0, ha nincs találat
 */
function searchStudents($key, $pdo) {
    // Speciális karakterek escape-elése
    $key = "%".preg_replace('/(?<!\\\)([%_])/', '\\\$1', $key)."%";

    $sql = "SELECT * FROM students
            WHERE student_id LIKE ? 
            OR fname LIKE ?
            OR lname LIKE ?
            OR username LIKE ?
            OR address LIKE ?
            OR email_address LIKE ?
            OR parent_fname LIKE ?
            OR parent_lname LIKE ?
            OR parent_phone_number LIKE ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $key, $key, $key, $key, $key, $key, $key, $key, $key
    ]);

    return ($stmt->rowCount() >= 1) ? $stmt->fetchAll(PDO::FETCH_ASSOC) : 0;
}
?>
