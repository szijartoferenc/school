<?php 
session_start();

// Bejelentkezés és jogosultság ellenőrzése
if (isset($_SESSION['admin_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'Admin') {

    // Szükséges fájlok betöltése
    include "../db.php";
    include "data/teacher.php";
    include "data/subject.php";
    include "data/grade.php";
    include "data/class.php";
    include "data/section.php";

    // Tanárok lekérdezése
    $teachers = getAllTeachers($pdo);
    ?>
    <!DOCTYPE html>
    <html lang="hu">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin - Tanárok</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="../css/style.css">
        <link rel="icon" href="../logo.png">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://kit.fontawesome.com/929e407827.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    </head>
    <body>
    
    <?php include "include/navbar.php"; ?>

    <div class="container mt-5">
        <!-- Új tanár hozzáadása -->
        <a href="addTeacher.php" class="btn btn-dark mb-3">Új tanár hozzáadása</a>

        <!-- Kereső űrlap -->
        <form action="searchTeacher.php" method="get" class="mb-3">
            <div class="input-group">
                <input type="text" class="form-control" name="searchKey" placeholder="Keresés...">
                <button class="btn btn-primary" type="submit">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </form>

        <!-- Üzenetek -->
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger"><?=$_GET['error']?></div>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-info"><?=$_GET['success']?></div>
        <?php endif; ?>

        <?php if ($teachers != 0): ?>
            <!-- Tanárok táblázata -->
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>ID</th>
                            <th>Vezetéknév</th>
                            <th>Keresztnév</th>
                            <th>Felhasználónév</th>
                            <th>Tantárgyak</th>
                            <th>Osztályok</th>
                            <th>Művelet</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 0; foreach ($teachers as $teacher): $i++; ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td><?= $teacher['teacher_id'] ?></td>
                                <td>
                                    <a href="viewTeacher.php?teacher_id=<?= $teacher['teacher_id'] ?>">
                                        <?= htmlspecialchars($teacher['lname']) ?>
                                    </a>
                                </td>
                                <td><?= htmlspecialchars($teacher['fname']) ?></td>
                                <td><?= htmlspecialchars($teacher['username']) ?></td>
                                <td>
                                    <?php 
                                        $subjectNames = [];
                                        $subjectIds = explode(',', trim($teacher['subjects'], ','));
                                        foreach ($subjectIds as $subjectId) {
                                            $subject = getSubjectById($subjectId, $pdo);
                                            if ($subject) {
                                                $subjectNames[] = $subject['subject_code'];
                                            }
                                        }
                                        echo implode(', ', $subjectNames);
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                        $classLabels = [];

                                        if (!empty($teacher['class'])) {
                                            $classIds = explode(',', trim($teacher['class'], ','));

                                            foreach ($classIds as $classId) {
                                                $classId = trim($classId);
                                                if (is_numeric($classId)) {
                                                    $class = getClassById((int)$classId, $pdo);
                                                    if ($class) {
                                                        $grade = getGradeById($class['grade'], $pdo);
                                                        $section = getSectionById($class['section'], $pdo);
                                                        if ($grade && $section) {
                                                            $classLabels[] = $grade['grade_code'].'-'.$grade['grade'].$section['section'];
                                                        }
                                                    }
                                                }
                                            }
                                        }

                                        echo implode(', ', $classLabels);
                                    ?>
                                </td>
                                <td>
                                    <a href="updateTeacher.php?teacher_id=<?= $teacher['teacher_id'] ?>" class="btn btn-warning btn-sm">Szerkesztés</a>
                                    <a href="deleteTeacher.php?teacher_id=<?= $teacher['teacher_id'] ?>" class="btn btn-danger btn-sm">Törlés</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">Nincsenek tanárok az adatbázisban.</div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#navLinks li:nth-child(2) a").addClass('active');
        });
    </script>

    </body>
    </html>

<?php 
    } else {
        // Ha nincs jogosultság, átirányítás a bejelentkezésre
        header("Location: ../login.php");
        exit;
    }
?>
