<?php 

// All Students 
function getAllStudents($pdo) {
    try {
        $sql = "SELECT * FROM students";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        if ($stmt->rowCount() >= 1) {
            return $stmt->fetchAll(); // Ha van találat, visszaadjuk a diákokat
        } else {
            return []; // Ha nincs találat, üres tömböt adunk vissza
        }
    } catch (PDOException $e) {
        return "Hiba történt: " . $e->getMessage(); // Hibakezelés
    }
}

// Get Student By Id 
function getStudentById($id, $pdo) {
    try {
        $sql = "SELECT * FROM students WHERE student_id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);

        if ($stmt->rowCount() == 1) {
            return $stmt->fetch(); // Ha találat van, visszaadjuk a diákot
        } else {
            return null; // Ha nincs találat, null értéket adunk vissza
        }
    } catch (PDOException $e) {
        return "Hiba történt: " . $e->getMessage(); // Hibakezelés
    }
}

// Check if the username is unique
function unameIsUnique($uname, $pdo, $student_id = 0) {
    try {
        $sql = "SELECT username, student_id FROM students WHERE username=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$uname]);

        if ($student_id == 0) {
            return $stmt->rowCount() === 0 ? 1 : 0; // Ha nincs találat, akkor egyedi a név
        } else {
            if ($stmt->rowCount() === 1) {
                $student = $stmt->fetch();
                return $student['student_id'] == $student_id ? 1 : 0;
            } else {
                return 1; // Ha nincs találat, akkor egyedi a név
            }
        }
    } catch (PDOException $e) {
        return "Hiba történt: " . $e->getMessage(); // Hibakezelés
    }
}

// Verify student password
function studentPasswordVerify($student_pass, $pdo, $student_id) {
    try {
        $sql = "SELECT password FROM students WHERE student_id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$student_id]);

        if ($stmt->rowCount() == 1) {
            $student = $stmt->fetch();
            return password_verify($student_pass, $student['password']) ? 1 : 0;
        } else {
            return 0; // Ha nincs találat, akkor hibás
        }
    } catch (PDOException $e) {
        return "Hiba történt: " . $e->getMessage(); // Hibakezelés
    }
}
?>
