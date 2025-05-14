<?php  

// Összes szekció lekérése
function getAllSections($pdo){
   $sql = "SELECT * FROM section";
   $stmt = $pdo->prepare($sql);
   $stmt->execute();

   return $stmt->rowCount() >= 1 ? $stmt->fetchAll() : null;
}

// Szekció lekérése ID alapján
function getSectionById($section_id, $pdo){
   $sql = "SELECT * FROM section WHERE section_id=?";
   $stmt = $pdo->prepare($sql);
   $stmt->execute([$section_id]);

   return $stmt->rowCount() === 1 ? $stmt->fetch() : null;
}

// Szekció törlése ID alapján
function removeSection($id, $pdo){
   $sql = "DELETE FROM section WHERE section_id=?";
   $stmt = $pdo->prepare($sql);
   return $stmt->execute([$id]) ? true : false;
}
