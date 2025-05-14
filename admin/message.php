<?php 
session_start();

// Jogosultság ellenőrzése
if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'Admin') {
        
        include "../db.php";
        include "data/message.php";

        // Összes üzenet lekérdezése
        $messages = getAllMessages($pdo);
        ?>
        <!DOCTYPE html>
        <html lang="hu">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Admin – Üzenetek</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
            <link rel="stylesheet" href="../css/style.css">
            <link rel="icon" href="../logo.png">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
            <script src="https://kit.fontawesome.com/929e407827.js" crossorigin="anonymous"></script>
        </head>
        <body>

        <?php include "include/navbar.php"; ?>

        <div class="container mt-5" style="width: 90%; max-width: 700px;">
            <h4 class="text-center p-3">Beérkezett üzenetek</h4>

            <?php if ($messages != 0): ?>
                <div class="accordion accordion-flush" id="accordionFlushExample">
                    <?php foreach ($messages as $message): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-heading_<?= $message['message_id'] ?>">
                            <button class="accordion-button collapsed" type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapse_<?= $message['message_id'] ?>"
                                    aria-expanded="false"
                                    aria-controls="flush-collapse_<?= $message['message_id'] ?>">
                                <?= htmlspecialchars($message['sender_full_name']) ?>
                            </button>
                        </h2>
                        <div id="flush-collapse_<?= $message['message_id'] ?>"
                             class="accordion-collapse collapse"
                             aria-labelledby="flush-heading_<?= $message['message_id'] ?>"
                             data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                <?= nl2br(htmlspecialchars($message['message'])) ?>
                                <div class="d-flex mt-3">
                                    <div class="p-2">Email: <strong><?= htmlspecialchars($message['sender_email']) ?></strong></div>
                                    <div class="ms-auto p-2">Dátum: <?= htmlspecialchars($message['date_time']) ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info mt-5" role="alert">
                    Nincs elérhető üzenet.
                </div>
            <?php endif; ?>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            $(document).ready(function(){
                $("#navLinks li:nth-child(9) a").addClass('active');
            });
        </script>

        </body>
        </html>
        <?php 
    } else {
        header("Location: ../login.php");
        exit;
    }
} else {
    header("Location: ../login.php");
    exit;
}
?>
