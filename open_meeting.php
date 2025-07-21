<?php
$id = $_GET['id'] ?? null;

$meetings = [
    1 => ['date' => '9 Febbraio 2024', 'notes' => 'Preparare presentazione progetto X.'],
    2 => ['date' => '15 Marzo 2024', 'notes' => 'Contattare fornitore Y e inviare report.'],
    3 => ['date' => '7 Giugno 2024', 'notes' => 'Aggiornare roadmap e pianificare Q3.']
];

$meeting = $meetings[$id] ?? null;
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dettagli Riunione</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />

    <link href="index.css" rel="stylesheet" />
</head>
<body class="body-index">

<div class="container py-5">
    <?php if ($meeting): ?>
        <h2 class="text-primary-blue mb-4"><strong>Riunione del <?= htmlspecialchars($meeting['date']) ?></strong></h2>

        <div class="card p-4 prodotto-card text-white">
            <h5 class="mb-3">Attività / Note</h5>
            <p class="lead text-white"><?= nl2br(htmlspecialchars($meeting['notes'])) ?></p>
        </div>

        <a href="index.php" class="btn btn-secondary mt-4">← Torna alla lista</a>
    <?php else: ?>
        <div class="alert alert-danger">Riunione non trovata.</div>
        <a href="index.php" class="btn btn-secondary">← Torna alla lista</a>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
