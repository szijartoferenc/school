<?php

// Összes tantárgy lekérése
function getAllSubjects($pdo): array|null {
   $sql = "SELECT * FROM subjects";
   $stmt = $pdo->prepare($sql);
   $stmt->execute();

   // Ha van találat, visszaadjuk az összes tantárgyat
   return $stmt->rowCount() > 0 ? $stmt->fetchAll() : null;
}

// Egy adott tantárgy lekérése azonosító alapján
function getSubjectById(int $subject_id, $pdo): array|null {
   $sql = "SELECT * FROM subjects WHERE subject_id = ?";
   $stmt = $pdo->prepare($sql);
   $stmt->execute([$subject_id]);

   // Egyetlen találat esetén visszaadjuk a tantárgy adatait
   return $stmt->rowCount() === 1 ? $stmt->fetch() : null;
}

?>
