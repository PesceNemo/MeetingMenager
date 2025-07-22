<?php
$trashedJson = file_get_contents('trashed_meetings.json');
if ($trashedJson === false) {
    die("Errore nel caricare il file trashed_meetings.json");
}

$trashedMeetings = json_decode($trashedJson, true);
if ($trashedMeetings === null) {
    die("Errore nel decodificare il file JSON");
}

function giorniRimanenti($deletedDate) {
    $oggi = new DateTime();
    $eliminata = new DateTime($deletedDate);
    $differenza = $oggi->diff($eliminata)->days;
    return max(0, 30 - $differenza);
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8" />
    <title>Cestino Riunioni</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="index.css" rel="stylesheet">
</head>
<body>

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <div class="d-flex align-items-center mb-2 mb-md-0">
            <div class="section-title-line me-3"></div>
            <h2 class="mb-0 text-primary-blue"><strong>Cestino Riunioni</strong></h2>
        </div>

        <a href="index.php" class="btn btn-secondary btn-lg shadow text-white mt-2 mt-md-0">
            <i class="bi bi-arrow-left-circle"></i> Torna alla Home
        </a>
    </div>

    <div class="row g-4">
        <?php if (!empty($trashedMeetings)): ?>
            <?php foreach ($trashedMeetings as $id => $meeting): ?>
                <?php
                    $giorni = giorniRimanenti($meeting['deleted_at']);
                    $badge = $giorni <= 5 ? 'danger' : ($giorni <= 15 ? 'warning' : 'success');
                ?>
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="card prodotto-card p-4 text-white h-100">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <h4 class="card-title gradient-text">Riunione Eliminata</h4>
                                <p class="lead">Data originale: <?= htmlspecialchars($meeting['date']) ?></p>
                                <p class="small">Eliminata il: <?= htmlspecialchars($meeting['deleted_at']) ?></p>
                                <span class="badge bg-<?= $badge ?>">Eliminazione definitiva tra <?= $giorni ?> giorni</span>
                            </div>
                            <div class="mt-3 d-flex justify-content-between">
                                <form method="POST" action="restore_meeting.php">
                                    <input type="hidden" name="id" value="<?= $id ?>">
                                    <button class="btn btn-mic shadow" type="submit">
                                        <i class="bi bi-arrow-clockwise"></i> Ripristina
                                    </button>
                                </form>
                                <form method="POST" action="delete_forever.php">
                                    <input type="hidden" name="id" value="<?= $id ?>">
                                    <button class="btn btn-delete-task shadow" onclick="return confirm('Sei sicuro di voler eliminare definitivamente questa riunione?');">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-info text-white bg-opacity-25" role="alert">
                Il cestino è vuoto.
            </div>
        <?php endif; ?>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
