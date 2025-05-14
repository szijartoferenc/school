<?php 
session_start();

if (isset($_SESSION['admin_id'], $_SESSION['role'], $_GET['teacher_id']) && $_SESSION['role'] === 'Admin') {
    
    include "../db.php";
    include "data/subject.php";
    include "data/grade.php";
    include "data/section.php";
    include "data/class.php";
    include "data/teacher.php";

    $teacher_id = filter_var($_GET['teacher_id'], FILTER_SANITIZE_NUMBER_INT);
    $teacher = getTeacherById($teacher_id, $pdo);

    if ($teacher == 0) {
        header("Location: teacher.php");
        exit;
    }

    $subjects = getAllSubjects($pdo);
    $classes = getAllClasses($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Edit Teacher</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/929e407827.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php include "include/navbar.php"; ?>

    <div class="container mt-5">
        <a href="teacher.php" class="btn btn-dark">Vissza</a>

        <form method="post" class="shadow p-3 mt-5 form-w" action="request/updateTeacher.php">
            <h3>Tanár szerkesztése</h3><hr>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
            <?php endif; ?>
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
            <?php endif; ?>

            <?php 
            $teacher_fields = [
                'lname' => 'Vezetéknév',
                'fname' => 'Keresztnév',
                'username' => 'Felhasználónév',
                'address' => 'Lakcím',
                'employee_number' => 'Alkalmazotti azonosító',
                'date_of_birth' => 'Születési dátum',
                'phone_number' => 'Telefonszám',
                'qualification' => 'Végzettség',
                'email_address' => 'Email cím'
            ];
            foreach ($teacher_fields as $field => $label): ?>
                <div class="mb-3">
                    <label class="form-label"><?= $label ?></label>
                    <input type="text" class="form-control" name="<?= $field ?>" value="<?= htmlspecialchars($teacher[$field] ?? '') ?>">
                </div>
            <?php endforeach; ?>

            <div class="mb-3">
                <label class="form-label">Nem</label><br>
                <input type="radio" name="gender" value="Male" <?= ($teacher['gender'] ?? '') == 'Male' ? 'checked' : '' ?>> Férfi
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio" name="gender" value="Female" <?= ($teacher['gender'] ?? '') == 'Female' ? 'checked' : '' ?>> Nő
            </div>

            <input type="hidden" name="teacher_id" value="<?= htmlspecialchars($teacher['teacher_id'] ?? '') ?>">

            <div class="mb-3">
                <label class="form-label">Tantárgyak</label>
                <div class="row row-cols-5">
                    <?php 
                    $subject_ids = str_split(trim($teacher['subjects'] ?? ''));
                    foreach ($subjects as $subject): 
                        $checked = in_array($subject['subject_id'], $subject_ids);
                    ?>
                        <div class="col">
                            <input type="checkbox" name="subjects[]" value="<?= $subject['subject_id'] ?>" <?= $checked ? 'checked' : '' ?>> <?= $subject['subject'] ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Osztályok</label>
                <div class="row row-cols-5">
                <?php 
                    $class_ids = str_split(trim($teacher['class'] ?? ''));
                    foreach ($classes as $class): 
                        if (!isset($class['class_id'])) continue; // <<< EZ a védelem
                        $checked = in_array($class['class_id'], $class_ids);
                        $grade = getGradeById($class['class_id'], $pdo);

                        if (!$grade) continue; // <<< ha a getGradeById sem talál semmit, azt is kihagyjuk
                ?>

                        <div class="col">
                            <input type="checkbox" name="classes[]" value="<?= $grade['grade_id'] ?>" <?= $checked ? 'checked' : '' ?>> <?= $grade['grade_code'] ?> - <?= $grade['grade'] ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Mentés</button>
        </form>

        <form method="post" class="shadow p-3 my-5 form-w" action="req/teacher-change.php" id="change_password">
            <h3>Jelszó módosítása</h3><hr>

            <?php if (isset($_GET['perror'])): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($_GET['perror']) ?></div>
            <?php endif; ?>
            <?php if (isset($_GET['psuccess'])): ?>
                <div class="alert alert-success"><?= htmlspecialchars($_GET['psuccess']) ?></div>
            <?php endif; ?>

            <div class="mb-3">
                <label class="form-label">Admin jelszó</label>
                <input type="password" class="form-control" name="admin_pass">
            </div>

            <label class="form-label">Új jelszó</label>
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="new_pass" id="passInput">
                <button class="btn btn-secondary" id="gBtn">Generálás</button>
            </div>

            <input type="hidden" name="teacher_id" value="<?= htmlspecialchars($teacher['teacher_id'] ?? '') ?>">

            <div class="mb-3">
                <label class="form-label">Új jelszó megerősítése</label>
                <input type="text" class="form-control" name="c_new_pass" id="passInput2">
            </div>

            <button type="submit" class="btn btn-primary">Módosítás</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#navLinks li:nth-child(2) a").addClass('active');
        });

        function makePass(length) {
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            let result = '';
            for (let i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() * characters.length));
            }
            document.getElementById('passInput').value = result;
            document.getElementById('passInput2').value = result;
        }

        document.getElementById('gBtn').addEventListener('click', function(e){
            e.preventDefault();
            makePass(8);
        });
    </script>
</body>
</html>

<?php 
    } else {
        header("Location: teacher.php");
        exit;
    }
?>
