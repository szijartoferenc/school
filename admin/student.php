<?php 
session_start();

// Ellenőrzés: admin be van-e jelentkezve
if (isset($_SESSION['admin_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'Admin') {

    include "../db.php";
    include "data/student.php";
    include "data/grade.php";

    // Diákok lekérdezése
    $students = getAllStudents($pdo);
?>
<!DOCTYPE html>
<html lang="hu">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin - Tanulók</title>
  <!-- Bootstrap, stíluslapok, ikonok -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="icon" href="../logo.png">
  <script src="https://kit.fontawesome.com/929e407827.js" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>
  <?php include "include/navbar.php"; ?>

  <div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <a href="addStudent.php" class="btn btn-dark">Új diák hozzáadása</a>
    </div>

    <!-- Kereső űrlap -->
    <form action="searchStudent.php" method="get" class="n-table mb-4">
      <div class="input-group">
        <input type="text" class="form-control" name="searchKey" placeholder="Keresés...">
        <button class="btn btn-primary" type="submit">
          <i class="fa fa-search" aria-hidden="true"></i>
        </button>
      </div>
    </form>

    <!-- Üzenetek megjelenítése (hiba vagy siker) -->
    <?php if (isset($_GET['error'])): ?>
      <div class="alert alert-danger mt-3" role="alert">
        <?= htmlspecialchars($_GET['error']) ?>
      </div>
    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>
      <div class="alert alert-info mt-3" role="alert">
        <?= htmlspecialchars($_GET['success']) ?>
      </div>
    <?php endif; ?>

    <!-- Diákok táblázata -->
    <?php if ($students != 0): ?>
      <div class="table-responsive">
        <table class="table table-bordered mt-3">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>ID</th>
              <th>Vezetéknév</th>
              <th>Keresztnév</th>
              <th>Felhasználónév</th>
              <th>Osztály</th>
              <th>Műveletek</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1; foreach ($students as $student): ?>
              <tr>
                <td><?= $i++ ?></td>
                <td><?= $student['student_id'] ?></td>
                <td>
                  <a href="viewStudent.php?student_id=<?= $student['student_id'] ?>">
                    <?= htmlspecialchars($student['lname']) ?>
                  </a>
                </td>
                <td><?= htmlspecialchars($student['fname']) ?></td>
                <td><?= htmlspecialchars($student['username']) ?></td>
                <td>
                  <?php 
                    $g = getGradeById($student['grade'], $pdo); 
                    if ($g != 0) echo $g['grade_code'] . '-' . $g['grade']; 
                  ?>
                </td>
                <td>
                  <a href="updateStudent.php?student_id=<?= $student['student_id'] ?>" class="btn btn-sm btn-warning">Szerkesztés</a>
                  <a href="deleteStudent.php?student_id=<?= $student['student_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Biztosan törölni szeretnéd?');">Törlés</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-info mt-4" role="alert">
        Nincs elérhető diák.
      </div>
    <?php endif; ?>
  </div>

  <!-- Bootstrap JS és navigációs link aktiválása -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>	
  <script>
    $(document).ready(function(){
      $("#navLinks li:nth-child(3) a").addClass('active');
    });
  </script>
</body>
</html>

<?php 
} else {
  // Jogosulatlan hozzáférés
  header("Location: ../login.php");
  exit;
}
?>
