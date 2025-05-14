<?php
session_start();
include '../db.php';
include 'data/student.php';
include 'data/subject.php';
include 'data/scoreStudent.php';

// GET paraméterek biztonságos lekérdezése
$student_id = $_GET['student_id'] ?? null;
$subject_id = $_GET['subject_id'] ?? null;
$teacher_id = $_GET['teacher_id'] ?? null;
$year = $_GET['year'] ?? date('Y');
$semester = $_GET['semester'] ?? 'I';

if (!$teacher_id && isset($_SESSION['teacher_id'])) {
    $teacher_id = $_SESSION['teacher_id'];
}

// Ha nincs subject_id, az első tantárgyat választjuk ki
if (!$subject_id) {
    $stmt = $pdo->prepare("SELECT subject_id FROM subjects LIMIT 1");
    $stmt->execute();
    $first_subject = $stmt->fetch();
    $subject_id = $first_subject['subject_id'] ?? null;
}

// Diák, tantárgy, jegyek lekérdezése
$student = getStudentById($student_id, $pdo);
$subjects = getAllSubjects($pdo);
$scores = getStudentScoresByPeriod($student_id, $year, $semester, $pdo);
$current_subject = getSubjectById($subject_id, $pdo);

// Érvényes adatok ellenőrzése
if (!$student || !$current_subject) {
    echo "Érvénytelen diák vagy tantárgy!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Jegyek - <?= htmlspecialchars($student['lname'] . ' ' . $student['fname']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
</head>
<body>
    <?php include "include/navbar.php"; ?>

    <div class="container mt-4">
        <h2><?= htmlspecialchars($student['lname'] . ' ' . $student['fname']) ?> - Jegyek</h2>

        <form method="get" action="" class="row g-3 align-items-end">
            <input type="hidden" name="student_id" value="<?= $student_id ?>">

            <div class="col-md-2">
                <label for="year" class="form-label">Év</label>
                <input type="number" id="year" name="year" value="<?= $year ?>" class="form-control">
            </div>

            <div class="col-md-2">
                <label for="semester" class="form-label">Félév</label>
                <select name="semester" id="semester" class="form-select">
                    <option value="I" <?= $semester === 'I' ? 'selected' : '' ?>>I</option>
                    <option value="II" <?= $semester === 'II' ? 'selected' : '' ?>>II</option>
                </select>
            </div>

            <div class="col-md-4">
                <label for="subject_id" class="form-label">Tantárgy</label>
                <select name="subject_id" id="subject_id" class="form-select">
                    <?php foreach ($subjects as $subject): ?>
                        <option value="<?= $subject['subject_id'] ?>" <?= $subject_id == $subject['subject_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($subject['subject']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Szűrés</button>
            </div>
        </form>

        <h3 class="mt-4">Jegyek (<?= htmlspecialchars($current_subject['subject']) ?> | <?= $year ?>. <?= $semester ?> félév)</h3>
        <?php if ($scores): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped mt-2">
                    <thead>
                        <tr>
                            <th>Dátum</th>
                            <th>Jegy</th>
                            <th>Típus</th>
                            <th>Tantárgy</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($scores as $score): ?>
                            <?php if ($score['subject_id'] == $subject_id): ?>
                                <tr>
                                    <td><?= htmlspecialchars($score['date']) ?></td>
                                    <td>
                                        <?php
                                            $grades_text = [
                                                5 => '(5) jeles',
                                                4 => '(4) jó',
                                                3 => '(3) közepes',
                                                2 => '(2) elégséges',
                                                1 => '(1) elégtelen'
                                            ];
                                            echo htmlspecialchars($grades_text[(int)$score['grade']] ?? $score['grade']);
                                        ?>
                                    </td>
                                    <td><?= htmlspecialchars($score['type']) ?></td>
                                    <td><?= htmlspecialchars($score['subject']) ?></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-muted">Nincs elérhető jegy ebben az időszakban.</p>
        <?php endif; ?>

        <h3 class="mt-5">Új jegy hozzáadása</h3>
        <form method="post" action="request/saveScore.php" class="row g-3">
            <input type="hidden" name="student_id" value="<?= $student_id ?>">
            <input type="hidden" name="subject_id" value="<?= $subject_id ?>">
             <input type="hidden" name="teacher_id" value="<?= $teacher_id ?>">
            <input type="hidden" name="current_year" value="<?= $year ?>">
            <input type="hidden" name="current_semester" value="<?= $semester ?>">

            <div class="col-md-2">
                <label for="grade" class="form-label">Jegy</label>
                <select name="grade" id="grade" class="form-select" required>
                    <option value="">-- Válassz --</option>
                    <option value="1">1 (elégtelen)</option>
                    <option value="2">2 (elégséges)</option>
                    <option value="3">3 (közepes)</option>
                    <option value="4">4 (jó)</option>
                    <option value="5">5 (jeles)</option>
                </select>
            </div>

            <div class="col-md-4">
                <label for="type" class="form-label">Típus</label>
                <select name="type" id="type" class="form-select" required>
                    <option value="">-- Válassz --</option>
                    <option value="felelés">Felelés</option>
                    <option value="dolgozat">Dolgozat</option>
                    <option value="témazáró">Témazáró</option>
                    <option value="projekt">Projekt</option>
                </select>
            </div>

            <div class="col-md-3">
                <label for="date" class="form-label">Dátum</label>
                <input type="date" id="date" name="date" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>

            <div class="col-md-3 d-grid">
                <button type="submit" class="btn btn-success mt-4">Jegy mentése</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
