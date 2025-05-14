<?php

function getAllScores($pdo) {
    try {
        $sql = "SELECT * FROM student_score";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return "Hiba történt: " . $e->getMessage();
    }
}

function getScoreById($student_id, $teacher_id, $subject_id, $semester, $year, $pdo) {
    try {
        $sql = "SELECT * FROM student_score 
                WHERE student_id=? AND teacher_id=? AND subject_id=? AND semester=? AND year=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$student_id, $teacher_id, $subject_id, $semester, $year]);
        return $stmt->rowCount() == 1 ? $stmt->fetch() : null;
    } catch (PDOException $e) {
        return "Hiba történt: " . $e->getMessage();
    }
}

function getStudentScores($student_id, $pdo) {
    try {
        $sql = "SELECT * FROM student_score WHERE student_id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$student_id]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return "Hiba történt: " . $e->getMessage();
    }
}

// Új függvény: szűrés év és félév alapján
function getStudentScoresByPeriod($student_id, $year, $semester, $pdo) {
    try {
        $sql = "SELECT ss.*, s.subject 
                FROM student_score ss
                JOIN subjects s ON ss.subject_id = s.subject_id
                WHERE ss.student_id=? AND ss.year=? AND ss.semester=?
                ORDER BY ss.date DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$student_id, $year, $semester]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

?>
