<?php 

// All Subjects
function getAllSubjects($pdo) {
    try {
        $sql = "SELECT * FROM subjects";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        if ($stmt->rowCount() >= 1) {
            return $stmt->fetchAll(); // Visszaadjuk az összes találatot
        } else {
            return []; // Üres tömb, ha nincs találat
        }
    } catch (PDOException $e) {
        return "Hiba történt: " . $e->getMessage(); // Hibakezelés
    }
}

// Get Subject by ID
function getSubjectById($subject_id, $pdo) {
    try {
        $sql = "SELECT * FROM subjects WHERE subject_id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$subject_id]);

        if ($stmt->rowCount() == 1) {
            return $stmt->fetch(); // Visszaadjuk a találatot
        } else {
            return null; // Ha nincs találat, null-t adunk vissza
        }
    } catch (PDOException $e) {
        return "Hiba történt: " . $e->getMessage(); // Hibakezelés
    }
}

// Get Subjects by Grade
function getSubjectByGrade($grade, $pdo) {
    try {
        $sql = "SELECT * FROM subjects WHERE grade=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$grade]);

        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll(); // Visszaadjuk az összes találatot
        } else {
            return []; // Üres tömb, ha nincs találat
        }
    } catch (PDOException $e) {
        return "Hiba történt: " . $e->getMessage(); // Hibakezelés
    }
}

?>
