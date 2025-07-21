<?php
// Carica le riunioni archiviate
$archivedMeetingsJson = file_get_contents('archived_meetings.json');
$archivedMeetings = json_decode($archivedMeetingsJson, true);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Riunioni Archiviate</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet" />
    <link href="index.css" rel="stylesheet">
    
</head>
<body class="body-index">

<div class="container py-5">
    <h2 class="text-primary-blue mb-4"><strong>Riunioni Archiviate</strong></h2>

    <div class="row g-4">
        <?php foreach ($archivedMeetings as $id => $meeting): ?>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="card prodotto-card p-4 text-white h-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h4 class="card-title gradient-text">Riunione</h4>
                            <p class="lead text-white"><?= htmlspecialchars($meeting['date']) ?></p>
                        </div>
                        <a href="open_meeting.php?id=<?= $id ?>" class="btn btn-info text-white fw-bold mt-3 shadow">
                            <i class="bi bi-folder2-open"></i> Apri
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        
        <a href="index.php" class="btn btn-secondary mt-4">← Torna alla lista</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 
