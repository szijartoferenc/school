<?php  
/**
 * Tanárkezelő függvények
 * Ezek a segédfüggvények CRUD és keresési műveleteket valósítanak meg a 'teachers' táblán.
 */

/**
 * Egy tanár lekérése azonosító alapján.
 */
function getTeacherById($teacher_id, $pdo) {
    $sql = "SELECT * FROM teachers WHERE teacher_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$teacher_id]);

    return $stmt->rowCount() === 1 ? $stmt->fetch() : null;
}

/**
 * Az összes tanár lekérése.
 */
function getAllTeachers($pdo) {
    $sql = "SELECT * FROM teachers";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->rowCount() >= 1 ? $stmt->fetchAll() : null;
}

/**
 * Felhasználónév egyediségének ellenőrzése.
 * Frissítés esetén megengedett, ha az adott tanárhoz tartozik.
 */
function unameIsUnique($uname, $pdo, $teacher_id = 0) {
    $sql = "SELECT username, teacher_id FROM teachers WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$uname]);

    if ($stmt->rowCount() >= 1) {
        $teacher = $stmt->fetch();
        return $teacher['teacher_id'] == $teacher_id;
    }
    return true;
}

/**
 * Tanár törlése azonosító alapján.
 */
function removeTeacher($id, $pdo) {
    $sql = "DELETE FROM teachers WHERE teacher_id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$id]);
}

/**
 * Tanárok keresése kulcsszó alapján, több mezőben (LIKE).
 */
function searchTeachers($key, $pdo) {
    // Speciális karakterek escape-elése LIKE-hoz
    $key = preg_replace('/(?<!\\\)([%_])/', '\\\$1', $key);
    $key = "%$key%";

    $sql = "SELECT * FROM teachers WHERE 
        teacher_id LIKE ? OR
        fname LIKE ? OR
        lname LIKE ? OR
        username LIKE ? OR
        employee_number LIKE ? OR
        phone_number LIKE ? OR
        qualification LIKE ? OR
        email_address LIKE ? OR
        address LIKE ?";
    
    $stmt = $pdo->prepare($sql);
    $params = array_fill(0, 9, $key);
    $stmt->execute($params);

    return $stmt->rowCount() >= 1 ? $stmt->fetchAll() : null;
}
?>
