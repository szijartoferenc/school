<?php 
session_start();

// Admin jogosultság ellenőrzése
if (isset($_SESSION['admin_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'Admin') {
    include "../db.php";
    include "data/setting.php";

    // Beállítások lekérése
    $setting = getSetting($pdo);
    ?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin – Beállítások</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/929e407827.js" crossorigin="anonymous"></script>
</head>
<body>

<?php include "include/navbar.php"; ?>

<div class="container mt-5">
    <form method="post"
          action="request/updateSetting.php"
          class="shadow p-4 rounded bg-white w-50 mx-auto">
          
        <h3 class="mb-4">Beállítások szerkesztése</h3>

        <!-- Üzenetek -->
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
        <?php endif; ?>

        <?php if ($setting && is_array($setting)): ?>
            <!-- Iskola neve -->
            <div class="mb-3">
                <label class="form-label">Iskola neve</label>
                <input type="text" name="school_name" class="form-control"
                       value="<?= htmlspecialchars($setting['school_name']) ?>" required>
            </div>

            <!-- Szlogen -->
            <div class="mb-3">
                <label class="form-label">Szlogen</label>
                <input type="text" name="slogan" class="form-control"
                       value="<?= htmlspecialchars($setting['slogan']) ?>" required>
            </div>

            <!-- Leírás -->
            <div class="mb-3">
                <label class="form-label">Leírás (Rólunk)</label>
                <textarea name="about" class="form-control" rows="4" required><?= htmlspecialchars($setting['about']) ?></textarea>
            </div>

            <!-- Aktuális tanév -->
            <div class="mb-3">
                <label class="form-label">Aktuális tanév</label>
                <input type="text" name="current_year" class="form-control"
                       value="<?= htmlspecialchars($setting['current_year']) ?>" required>
            </div>

            <!-- Aktuális félév -->
            <div class="mb-4">
                <label class="form-label">Aktuális félév</label>
                <input type="text" name="current_semester" class="form-control"
                       value="<?= htmlspecialchars($setting['current_semester']) ?>" required>
            </div>

            <!-- Mentés -->
            <button type="submit" class="btn btn-primary w-100">Mentés</button>
        <?php else: ?>
            <div class="alert alert-warning text-center">
                ⚠️ Nem találhatóak beállítások az adatbázisban.
            </div>
        <?php endif; ?>
    </form>
</div>

<script>
    $(document).ready(function(){
        $("#navLinks li:nth-child(10) a").addClass('active');
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php 
} else {
    header("Location: ../login.php");
    exit;
}
?>
