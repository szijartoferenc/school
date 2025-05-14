<?php 
include "db.php";
include "data/setting.php";
$setting = getSetting($pdo);

// Ellenőrzés: ha nincs beállítás, akkor átirányítás a bejelentkezésre
if ($setting != 0):
?>
<!DOCTYPE html>
<html lang="hu">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Üdvözöljük <?= htmlspecialchars($setting['school_name']) ?></title>
  
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" />
  
  <!-- Saját stíluslap -->
  <link rel="stylesheet" href="css/style.css" />
  
  <!-- Weboldal ikon -->
  <link rel="icon" href="logo.png" />
</head>
<body class="body-home">
  <div class="black-fill">
        <br /><br />
        <div class="container">

        <!-- Navigációs sáv -->
        <nav class="navbar navbar-expand-lg bg-light" id="homeNav">
            <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="logo.png" width="60" alt="Logo" />
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navigációs linkek -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#about">Rólunk</a></li>
                <li class="nav-item"><a class="nav-link" href="#contact">Kapcsolat</a></li>
                </ul>
                <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="login.php">Bejelentkezés</a></li>
                </ul>
            </div>
            </div>
        </nav>

        <!-- Üdvözlő rész -->
        <section class="welcome-text d-flex justify-content-center align-items-center flex-column text-center">
            <img src="logo.png" alt="School Logo" />
            <h4>Üdvözöljük <?= htmlspecialchars($setting['school_name']) ?>&nbsp;weboldalán</h4>
            <p><?= htmlspecialchars($setting['slogan']) ?></p>
        </section>

        <!-- Rólunk szekció -->
        <section id="about" class="d-flex justify-content-center align-items-center flex-column">
            <div class="about-card responsive-box card mb-3">
            <div class="row g-0 align-items-center">
                <div class="col-md-4 text-center">
                <img src="logo.png" class="img-fluid rounded-start" alt="About Image" />
                </div>
                <div class="col-md-8">
                <div class="card-body">
                    <h5 class="section-title">Rólunk</h5>
                    <p class="card-text"><?= nl2br(htmlspecialchars($setting['about'])) ?></p>
                    <p class="card-text"><small class="text-muted"><?= htmlspecialchars($setting['school_name']) ?></small></p>
                </div>
                </div>
            </div>
            </div>
        </section>

        <!-- Kapcsolat szekció -->
        <section id="contact" class="d-flex justify-content-center align-items-center flex-column">
            <form method="post" action="request/contact.php" class="contact-form responsive-box">

            <h3 class="section-title">Lépjen kapcsolatba velünk!</h3>

            <!-- Hibák és sikeres üzenetek megjelenítése -->
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger" role="alert"><?= htmlspecialchars($_GET['error']) ?></div>
            <?php endif; ?>
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success" role="alert"><?= htmlspecialchars($_GET['success']) ?></div>
            <?php endif; ?>

            <!-- Email mező -->
            <div class="mb-3">
                <label for="inputEmail" class="form-label">Email cím</label>
                <input type="email" class="form-control" id="inputEmail" name="email" required />
                <div class="form-text">Soha nem osztjuk meg e-mail-címét másokkal.</div>
            </div>

            <!-- Teljes név mező -->
            <div class="mb-3">
                <label class="form-label">Teljes név</label>
                <input type="text" name="full_name" class="form-control" required />
            </div>

            <!-- Üzenet mező -->
            <div class="mb-3">
                <label class="form-label">Üzenet</label>
                <textarea class="form-control" name="message" rows="4" required></textarea>
            </div>

            <!-- Küldés gomb -->
            <button type="submit" class="btn btn-primary w-100">Küldés</button>
            </form>
        </section>

        <!-- Lábléc -->
        <footer class="text-center text-light mt-4 py-3">
            &copy; <?= htmlspecialchars($setting['current_year']) ?> <?= htmlspecialchars($setting['school_name']) ?>. All rights reserved.
        </footer>
        
        </div>
  </div>

  <!-- Bootstrap JS bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php 
else:
  // Ha nincs beállítás, átirányítás a login oldalra
  header("Location: login.php");
  exit;
endif; 
?>
