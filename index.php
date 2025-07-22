<?php
// Carica il file JSON che contiene le riunioni
$meetingsJson = file_get_contents('meetings.json');
if ($meetingsJson === false) {
    die("Errore nel caricare il file meetings.json");
}

// Decodifica il file JSON in un array associativo
$meetings = json_decode($meetingsJson, true);
if ($meetings === null) {
    die("Errore nel decodificare il file JSON");
}
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
    <script src="meeting.js"></script>
</head>
<body>

<div class="container py-5">

    <!-- Barra superiore con stanghetta e titolo -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">

        <div class="d-flex align-items-center mb-2 mb-md-0">
            <div class="section-title-line me-3"></div>
            <h2 class="mb-0 text-primary-blue"><strong>Gestione Riunioni</strong></h2>
        </div>

        <div class="d-flex">
            <!-- Bottone Nuova Riunione -->
            <a href="new_meeting.php" class="btn btn-info btn-lg shadow text-white mt-2 mt-md-0 me-3">
                <i class="bi bi-plus-circle"></i> Nuova Riunione
            </a>

            <!-- Bottone Archivio -->
            <a href="archived_meetings.php" class="btn btn-secondary btn-lg shadow text-white mt-2 mt-md-0 me-3">
                <i class="bi bi-archive"></i> Archivio
            </a>

            <!-- Bottone Cestino -->
            <a href="trash.php" class="btn btn-danger btn-lg shadow text-white mt-2 mt-md-0">
                <i class="bi bi-trash3"></i> Cestino
            </a>
        </div>

    </div>

    <div class="row g-4">
        <?php if (!empty($meetings)): ?>
            <?php foreach ($meetings as $id => $meeting): ?>
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="card prodotto-card p-4 text-white h-100">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <h4 class="card-title gradient-text">Riunione</h4>
                                <!-- Visualizza la data -->
                                <p class="lead text-white"><?= htmlspecialchars($meeting['date']) ?></p>
                            </div>
                            <!-- Bottone Apri -->
                            <a href="open_meeting.php?id=<?= $id ?>&return_url=index.php" class="btn btn-info text-white fw-bold mt-3 shadow">
                                <i class="bi bi-folder2-open"></i> Apri
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-warning" role="alert">
                Non ci sono riunioni da visualizzare.
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
