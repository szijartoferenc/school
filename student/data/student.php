<?php 

// Összes diák lekérése
function getAllStudents($pdo): array|null {
   $sql = "SELECT * FROM students";
   $stmt = $pdo->prepare($sql);
   $stmt->execute();

   return $stmt->rowCount() > 0 ? $stmt->fetchAll() : null;
}

// Diák lekérése ID alapján
function getStudentById(int $id, $pdo): array|null {
   $sql = "SELECT * FROM students WHERE student_id = ?";
   $stmt = $pdo->prepare($sql);
   $stmt->execute([$id]);

   return $stmt->rowCount() === 1 ? $stmt->fetch() : null;
}

// Ellenőrzi, hogy az adott felhasználónév egyedi-e
function unameIsUnique(string $uname, $pdo, int $student_id = 0): bool {
   $sql = "SELECT username, student_id FROM students WHERE username = ?";
   $stmt = $pdo->prepare($sql);
   $stmt->execute([$uname]);

   if ($stmt->rowCount() >= 1) {
       $student = $stmt->fetch();
       // Ha a lekérdezett diák saját magát frissíti
       return $student['student_id'] == $student_id;
   }

   return true;
}

// Jelszó ellenőrzése adott diákhoz
function studentPasswordVerify(string $student_pass, $pdo, int $student_id): bool {
   $sql = "SELECT password FROM students WHERE student_id = ?";
   $stmt = $pdo->prepare($sql);
   $stmt->execute([$student_id]);

   if ($stmt->rowCount() === 1) {
       $student = $stmt->fetch();
       return password_verify($student_pass, $student['password']);
   }

   return false;
}
