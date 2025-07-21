<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'] ?? '';
    $notes = $_POST['notes'] ?? '';

    // Carica le riunioni esistenti
    $meetings = json_decode(file_get_contents('meetings.json'), true);

    // Trova l'ID della nuova riunione (potresti utilizzare un ID automatico incrementale)
    $newId = count($meetings) + 1;

    // Aggiungi la nuova riunione
    $meetings[$newId] = [
        'date' => $date,
        'tasks' => explode("\n", $notes) // Assumiamo che le note siano separate da linee nuove
    ];

    // Salva le modifiche nel file JSON
    file_put_contents('meetings.json', json_encode($meetings, JSON_PRETTY_PRINT));

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Nuova Riunione</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.min.css" rel="stylesheet" />

    <link href="index.css" rel="stylesheet" />
    <script src="meeting.js"></script>

</head>
<body class="meeting-body">

<div class="container py-5">
    <h2 class="text-primary-blue mb-4"><strong>Nuova Riunione</strong></h2>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="date" class="form-label text-white">Data riunione</label>
            <input type="date" name="date" id="date" class="form-control custom-input" required />
        </div>

        <div class="mb-3">
            <label for="notes" class="form-label text-white">Note</label>
            <textarea name="notes" id="notes" class="form-control custom-textarea" rows="6" placeholder="Scrivi tutto quello che ti serve..." required></textarea>
        </div>

        <div class="mb-4 text-center">
            <button type="button" class="btn btn-mic" aria-label="Microfono">
                <i class="bi bi-mic-fill"></i>
            </button>
        </div>

        <button type="submit" class="btn btn-primary-blue">
            <i class="bi bi-save"></i> Salva
        </button>
        <a href="index.php" class="btn btn-secondary ms-2">Annulla</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
