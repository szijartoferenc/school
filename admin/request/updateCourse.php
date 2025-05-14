<?php 
session_start();

// Ellenőrzés: admin be van jelentkezve és jogosult
if (isset($_SESSION['admin_id'], $_SESSION['role']) && $_SESSION['role'] === 'Admin') {

    // POST mezők meglétének ellenőrzése
    if (isset($_POST['course_name'], $_POST['course_code'], $_POST['grade'], $_POST['course_id'])) {
        
        include '../../db.php';

        // Bemeneti adatok tisztítása
        $course_name = trim($_POST['course_name']);
        $course_code = trim($_POST['course_code']);
        $grade       = trim($_POST['grade']);
        $course_id   = trim($_POST['course_id']);

        // Hibaüzenetek visszairányításához szükséges adat
        $data = 'course_id=' . urlencode($course_id);

        // Alapvető validáció
        if (empty($course_id)) {
            $error = "Course ID is required";
            header("Location: ../updateCourse.php?error=" . urlencode($error) . "&$data");
            exit;
        }

        if (empty($grade)) {
            $error = "Grade is required";
            header("Location: ../updateCourse.php?error=" . urlencode($error) . "&$data");
            exit;
        }

        if (empty($course_name)) {
            $error = "Course name is required";
            header("Location: ../updateCourse.php?error=" . urlencode($error) . "&$data");
            exit;
        }

        if (empty($course_code)) {
            $error = "Course code is required";
            header("Location: ../updateCourse.php?error=" . urlencode($error) . "&$data");
            exit;
        }

        // Ellenőrizzük, hogy van-e másik kurzus ugyanazzal a kóddal és évfolyammal
        $checkSql = "SELECT * FROM subjects WHERE grade = ? AND subject_code = ?";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->execute([$grade, $course_code]);

        if ($checkStmt->rowCount() > 0) {
            $existingCourse = $checkStmt->fetch();

            // Ha ugyanaz a kurzus, akkor engedélyezett a frissítés
            if ($existingCourse['subject_id'] == $course_id) {
                $updateSql = "UPDATE subjects SET subject = ?, subject_code = ?, grade = ? WHERE subject_id = ?";
                $updateStmt = $pdo->prepare($updateSql);
                $updateStmt->execute([$course_name, $course_code, $grade, $course_id]);

                $success = "Course updated successfully";
                header("Location: ../updateCourse.php?success=" . urlencode($success) . "&$data");
                exit;
            } else {
                // Másik kurzus már létezik ugyanezzel a kóddal
                $error = "The course already exists with this code and grade";
                header("Location: ../updateCourse.php?error=" . urlencode($error) . "&$data");
                exit;
            }
        }

        // Ha nincs ütközés, akkor frissítés
        $updateSql = "UPDATE subjects SET subject = ?, subject_code = ?, grade = ? WHERE subject_id = ?";
        $updateStmt = $pdo->prepare($updateSql);
        $updateStmt->execute([$course_name, $course_code, $grade, $course_id]);

        $success = "Course updated successfully";
        header("Location: ../updateCourse.php?success=" . urlencode($success) . "&$data");
        exit;

    } else {
        // POST mezők hiányoznak
        $error = "An error occurred";
        header("Location: ../course.php?error=" . urlencode($error));
        exit;
    }

} else {
    // Nincs jogosultság
    header("Location: ../../logout.php");
    exit;
}
?>
