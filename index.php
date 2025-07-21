<?php
$riunioni = [
    ['id' => 1, 'data' => '9 Febbraio 2024'],
    ['id' => 2, 'data' => '15 Marzo 2024'],
    ['id' => 3, 'data' => '7 Giugno 2024']
];
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Gestione Riunioni</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="index.css" rel="stylesheet">
</head>
<body>

<div class="container py-5">

    <!-- Barra superiore con stanghetta e titolo -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">

        <div class="d-flex align-items-center mb-2 mb-md-0">
            <div class="section-title-line me-3"></div>
            <h2 class="mb-0 text-primary-blue"><strong>Gestione Riunioni</strong></h2>
        </div>

        <a href="new_meeting.php" class="btn btn-info btn-lg shadow text-white mt-2 mt-md-0">
            <i class="bi bi-plus-circle"></i> Nuova Riunione
        </a>

    </div>

    <div class="row g-4">
        <?php foreach ($riunioni as $riunione): ?>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="card prodotto-card p-4 text-white h-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h4 class="card-title gradient-text">Riunione</h4>
                            <p class="lead text-white"><?= htmlspecialchars($riunione['data']) ?></p>
                        </div>
                        <a href="open_meeting.php?id=<?= $riunione['id'] ?>" class="btn btn-info text-white fw-bold mt-3 shadow">
                            <i class="bi bi-folder2-open"></i> Apri
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
