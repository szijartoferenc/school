<?php

/**
 * Egy regisztrátor felhasználó lekérdezése azonosító alapján
 */
function getRUserById($r_user_id, $pdo) {
    $sql = "SELECT * FROM registrationoffice WHERE r_user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$r_user_id]);

    return ($stmt->rowCount() === 1) ? $stmt->fetch() : null;
}

/**
 * Összes regisztrátor felhasználó lekérdezése
 */
function getAllRUsers($pdo) {
    $sql = "SELECT * FROM registrationoffice";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return ($stmt->rowCount() > 0) ? $stmt->fetchAll() : [];
}

/**
 * Felhasználónév egyediségének ellenőrzése
 * 
 * @param string $uname - vizsgált felhasználónév
 * @param object $pdo - adatbázis kapcsolat
 * @param int $r_user_id - opcionális: saját ID frissítéskor (kihagyható új létrehozáskor)
 */
function isUsernameUnique($uname, $pdo, $r_user_id = 0) {
    $sql = "SELECT username, r_user_id FROM registrationoffice WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$uname]);

    if ($stmt->rowCount() === 0) return true;

    $existingUser = $stmt->fetch();
    return ($r_user_id != 0 && $existingUser['r_user_id'] == $r_user_id);
}

/**
 * Regisztrátor felhasználó törlése ID alapján
 */
function removeRUser($id, $pdo) {
    $sql = "DELETE FROM registrationoffice WHERE r_user_id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$id]);
}

