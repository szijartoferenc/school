<?php 

/**
 * Ellenőrzi az admin jelszavát az adatbázisban tárolt hash alapján.
 *
 * @param string $admin_pass A megadott jelszó
 * @param PDO $pdo Az adatbázis kapcsolat
 * @param int $admin_id Az admin felhasználó azonosítója
 * @return bool TRUE, ha a jelszó helyes, egyébként FALSE
 */
function adminPasswordVerify(string $admin_pass, PDO $pdo, int $admin_id): bool {
    // Előkészített SQL lekérdezés az admin adatok lekérésére
    $sql = "SELECT password FROM admin WHERE admin_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$admin_id]);

    // Ellenőrizzük, hogy létezik-e ilyen admin
    if ($stmt->rowCount() === 1) {
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        $hashedPassword = $admin['password'];

        // Jelszó ellenőrzése a hash alapján
        return password_verify($admin_pass, $hashedPassword);
    }

    // Ha nem találtuk meg az admint, vagy valami hiba történt
    return false;
}
