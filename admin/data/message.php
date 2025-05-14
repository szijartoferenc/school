<?php
/**
 * Az összes üzenet lekérdezése, csökkenő sorrendben (legfrissebb elöl).
 *
 * @param PDO $pdo Az adatbázis kapcsolat
 * @return array|int Az üzenetek tömbje vagy 0, ha nincs adat
 */
function getAllMessages(PDO $pdo): array|int {
    $sql = "SELECT * FROM message ORDER BY message_id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
 
    return ($stmt->rowCount() > 0) ? $stmt->fetchAll(PDO::FETCH_ASSOC) : 0;
 }
 