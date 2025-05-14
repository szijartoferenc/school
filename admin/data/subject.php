<?php 

/**
 * Összes tantárgy lekérése az adatbázisból.
 * 
 * @param PDO $pdo Az adatbázis kapcsolat.
 * @return array A tantárgyak tömbje, vagy üres tömb ha nincs eredmény.
 */
function getAllSubjects($pdo){
   try {
      $sql = "SELECT * FROM subjects";
      $stmt = $pdo->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
   } catch (PDOException $e) {
      // Hiba naplózása (opcionálisan)
      return [];
   }
}


/**
 * Egy tantárgy lekérése azonosító alapján.
 * 
 * @param int $subject_id A tantárgy egyedi azonosítója.
 * @param PDO $pdo Az adatbázis kapcsolat.
 * @return array A tantárgy adatai, vagy üres tömb ha nem található.
 */
function getSubjectById($subject_id, $pdo){
   try {
      $sql = "SELECT * FROM subjects WHERE subject_id = ?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$subject_id]);
      return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
   } catch (PDOException $e) {
      return [];
   }
}


/**
 * Tantárgy törlése azonosító alapján.
 * 
 * @param int $id A törlendő tantárgy azonosítója.
 * @param PDO $pdo Az adatbázis kapcsolat.
 * @return bool Igaz, ha sikerült törölni, hamis egyébként.
 */
function removeCourse($id, $pdo){
   try {
      $sql = "DELETE FROM subjects WHERE subject_id = ?";
      $stmt = $pdo->prepare($sql);
      return $stmt->execute([$id]);
   } catch (PDOException $e) {
      return false;
   }
}

?>
