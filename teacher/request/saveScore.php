<?php
session_start();
include '../../db.php';

// POST adatok fogadása
$student_id = $_POST['student_id'] ?? null;
$teacher_id = $_POST['teacher_id'] ?? null;
$subject_id = $_POST['subject_id'] ?? null;
$year = $_POST['current_year'] ?? date('Y');
$semester = $_POST['current_semester'] ?? 'I';
$grade = $_POST['grade'] ?? null;
$type = $_POST['type'] ?? null;
$date = $_POST['date'] ?? null;

// Ellenőrzés
if (!$student_id || !$subject_id || !$teacher_id || !$grade || !$type || !$date) {
    echo "Hiányzó adat!";
    exit;
}

if ($teacher_id <= 0) {
    echo "Hibás tanár azonosító!";
    exit;
}

// Mentés az adatbázisba
$stmt = $pdo->prepare("INSERT INTO student_score 
    (student_id, teacher_id, subject_id, year, semester, grade, type, date) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

try {
    $stmt->execute([$student_id, $teacher_id, $subject_id, $year, $semester, $grade, $type, $date]);
    header("Location: ../studentGrade.php?student_id=$student_id&subject_id=$subject_id&year=$year&semester=$semester");
    exit;
} catch (PDOException $e) {
    echo "Hiba a mentés során: " . $e->getMessage();
}
