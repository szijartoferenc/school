<?php  

// Get Teacher by ID
function getTeacherById($teacher_id, $pdo) {
    try {
        $sql = "SELECT * FROM teachers WHERE teacher_id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$teacher_id]);

        if ($stmt->rowCount() == 1) {
            return $stmt->fetch(); // Visszaadjuk a tanár adatokat
        } else {
            return null; // Ha nincs találat, null-t adunk vissza
        }
    } catch (PDOException $e) {
        return "Hiba történt: " . $e->getMessage(); // Hibakezelés
    }
}

?>
