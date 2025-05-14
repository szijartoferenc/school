<?php 

// Minden diák lekérése
function getAllStudents($pdo){
   // SQL lekérdezés, ami az összes diák rekordot lekéri a 'students' táblából
   $sql = "SELECT * FROM students";
   $stmt = $pdo->prepare($sql);
   $stmt->execute();

   // Ha legalább egy diákot találunk, visszaadjuk őket
   if ($stmt->rowCount() >= 1) {
     $students = $stmt->fetchAll();
     return $students;
   } else {
     // Ha nem találunk diákot, 0-t adunk vissza
     return 0;
   }
}

// Diák törlése ID alapján
function removeStudent($id, $pdo){
   // SQL lekérdezés a diák törlésére a 'students' táblából
   $sql = "DELETE FROM students WHERE student_id=?";
   $stmt = $pdo->prepare($sql);
   $re = $stmt->execute([$id]);

   // Ha sikeres a törlés, 1-et adunk vissza
   if ($re) {
     return 1;
   } else {
     return 0;
   }
}

// Diák lekérése ID alapján
function getStudentById($id, $pdo){
   // SQL lekérdezés, amely egy diák adatát kéri le a 'students' táblából az ID alapján
   $sql = "SELECT * FROM students WHERE student_id=?";
   $stmt = $pdo->prepare($sql);
   $stmt->execute([$id]);

   // Ha megtaláljuk a diákot, visszaadjuk őt
   if ($stmt->rowCount() == 1) {
     $student = $stmt->fetch();
     return $student;
   } else {
     return 0;
   }
}

// Ellenőrzi, hogy a felhasználónév egyedi-e
function unameIsUnique($uname, $pdo, $student_id=0){
   // SQL lekérdezés, ami ellenőrzi a felhasználónevet a 'students' táblában
   $sql = "SELECT username, student_id FROM students WHERE username=?";
   $stmt = $pdo->prepare($sql);
   $stmt->execute([$uname]);
   
   if ($student_id == 0) {
     // Ha nincs megadva diák ID, akkor ellenőrizzük, hogy a felhasználónév már létezik-e
     if ($stmt->rowCount() >= 1) {
       return 0; // Felhasználónév már létezik
     } else {
       return 1; // Felhasználónév egyedi
     }
   } else {
     // Ha van megadott diák ID, ellenőrizzük, hogy a felhasználónév más diákhoz tartozik-e
     if ($stmt->rowCount() >= 1) {
       $student = $stmt->fetch();
       if ($student['student_id'] == $student_id) {
         return 1; // Az aktuális diák felhasználóneve
       } else {
         return 0; // Felhasználónév ütközik egy másik diákkal
       }
     } else {
       return 1; // Felhasználónév egyedi
     }
   }
}

// Diákok keresése
function searchStudents($key, $pdo){
   // A keresett kulcs karakterek érvényesítése
   $key = preg_replace('/(?<!\\\)([%_])/', '\\\$1',$key);
   
   // SQL lekérdezés, amely a 'students' táblában keres a kulcsszóra
   $sql = "SELECT * FROM students
           WHERE student_id LIKE ? 
           OR fname LIKE ?
           OR address LIKE ?
           OR email_address LIKE ?
           OR parent_fname LIKE ?
           OR parent_lname LIKE ?
           OR parent_phone_number LIKE ?
           OR lname LIKE ?
           OR username LIKE ?";
   $stmt = $pdo->prepare($sql);
   $stmt->execute([$key, $key, $key, $key, $key, $key, $key, $key, $key]);

   // Ha találunk találatokat, visszaadjuk őket
   if ($stmt->rowCount() >= 1) {
     $students = $stmt->fetchAll();
     return $students;
   } else {
     return 0; // Ha nincs találat, 0-t adunk vissza
   }
}
?>
