<?php  
/**
 * Adatbázis kapcsolat létrehozása PDO-val
 * Ez a fájl biztosítja az egész alkalmazás számára az adatbázis-kapcsolatot.
 */

// Adatbázis kapcsolódási paraméterek
$sName   = "localhost";     // Szerver neve (localhost fejlesztéskor)
$uName   = "root";          // Felhasználónév (fejlesztői környezetben általában 'root')
$pass    = "12345";              // Jelszó (alapértelmezés szerint üres XAMPP/MAMP esetén)
$db_name = "ssm";        // Adatbázis neve

try {
    // PDO kapcsolat létrehozása MySQL-hez
	$pdo = new PDO("mysql:host=$sName;dbname=$db_name", $uName, $pass);

    // Hibakezelési mód beállítása: kivételdobás hiba esetén
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // Kapcsolódási hiba esetén hibaüzenet kiírása
	echo "Kapcsolódási hiba: " . $e->getMessage();
	exit;
}
