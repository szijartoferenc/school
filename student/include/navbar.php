<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    
    <!-- Logó és főoldal hivatkozás -->
    <a class="navbar-brand" href="index.php">
      <img src="../logo.png" width="40" alt="Logo">
    </a>

    <!-- Kicsi képernyőkön hamburger menü -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" 
            aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Navigációs menüpontok -->
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0" id="navLinks">
        <li class="nav-item">
          <a class="nav-link" href="index.php">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="grade.php">Érdemjegyek</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="pass.php">Jelszócsere</a>
        </li>
      </ul>

      <!-- Jobbra igazított kilépés gomb -->
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="../logout.php">Kijelentkezés</a>
        </li>
      </ul>
    </div>

  </div>
</nav>
