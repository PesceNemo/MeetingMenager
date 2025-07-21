<?php
$id = $_GET['id'] ?? null;

// Carica il file JSON che contiene le riunioni attive
$meetingsJson = file_get_contents('meetings.json');
$meetings = json_decode($meetingsJson, true);

// Carica il file JSON che contiene le riunioni archiviate
$archivedMeetingsJson = file_get_contents('archived_meetings.json');
$archivedMeetings = json_decode($archivedMeetingsJson, true);

// Prova a recuperare la riunione dall'elenco attivo
$meeting = $meetings[$id] ?? null;

// Se non la trovi negli attivi, prova negli archiviati
if (!$meeting) {
    $meeting = $archivedMeetings[$id] ?? null;
}

// Gestione della richiesta POST per aggiornare i task
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_index']) && isset($_POST['task_status'])) {
    $index = $_POST['task_index'];
    $task_status = $_POST['task_status'];

    // Aggiorna il task specificato con il nuovo stato
    if ($task_status === 'done') {
        $meeting['tasks'][$index] = "[Fatto] " . $meeting['tasks'][$index];  // Marca come completato
    } else {
        $meeting['tasks'][$index] = str_replace("[Fatto] ", "", $meeting['tasks'][$index]);  // Rimuovi la dicitura "Fatto"
    }

    // Verifica se tutte le attività sono completate
    $allTasksDone = true;
    foreach ($meeting['tasks'] as $task) {
        if (strpos($task, '[Fatto]') === false) {
            $allTasksDone = false;
            break;
        }
    }

    // Se tutte le attività sono completate, archivia la riunione
    if ($allTasksDone) {
        // Aggiungi la riunione agli archiviati e rimuovila dagli attivi
        $archivedMeetings[$id] = $meeting;
        unset($meetings[$id]);

        // Salva i dati aggiornati nei file JSON
        file_put_contents('meetings.json', json_encode($meetings, JSON_PRETTY_PRINT));
        file_put_contents('archived_meetings.json', json_encode($archivedMeetings, JSON_PRETTY_PRINT));

        echo json_encode(['status' => 'archived']);
        exit;
    }

    // Aggiorna il file JSON delle riunioni attive
    $meetings[$id] = $meeting;
    file_put_contents('meetings.json', json_encode($meetings, JSON_PRETTY_PRINT));

    // Risponde con un OK
    echo json_encode(['status' => 'success']);
    exit;
}

?>


<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dettagli Riunione</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet" />
    <link href="index.css" rel="stylesheet" />
    <script src="meeting.js"></script>

    <script>
        // Funzione per inviare la modifica al server
        function updateTaskStatus(index, status) {
            fetch('open_meeting.php?id=<?= $id ?>', {
                method: 'POST',
                body: new URLSearchParams({
                    task_index: index,
                    task_status: status
                }),
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    console.log('Task aggiornato correttamente');
                } else {
                    console.error('Errore nell\'aggiornamento del task');
                }
            })
            .catch(error => {
                console.error('Errore nella comunicazione con il server:', error);
            });
        }

        // Funzione per gestire il cambiamento della checkbox
        function handleCheckboxChange(event, index) {
            const status = event.target.checked ? 'done' : 'not_done';
            updateTaskStatus(index, status);
        }

        // Funzione per confermare l'eliminazione della riunione
        function confermaEliminazione(id) {
            // Mostra il modal di conferma
            const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
            modal.show();

            // Aggiungi evento di conferma
            document.getElementById('confirmDeleteButton').onclick = function() {
                fetch('open_meeting.php?id=' + id, {
                    method: 'POST',
                    body: new URLSearchParams({
                        delete_meeting: 'true'
                    }),
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'deleted') {
                        window.location.href = 'index.php'; // Ritorna alla lista delle riunioni
                    } else {
                        alert('Errore nella richiesta di eliminazione.');
                    }
                })
                .catch(error => {
                    alert('Errore nella richiesta di eliminazione.');
                    console.error('Errore nell\'eliminazione della riunione:', error);
                });
            };
        }
    </script>
</head>
<body class="body-index">

    <div class="container py-5">
        <?php if ($meeting): ?>
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                <h2 class="text-primary-blue"><strong>Riunione del <?= htmlspecialchars($meeting['date']) ?></strong></h2>
                <button class="btn btn-secondary mt-3 mt-md-0" onclick="confermaEliminazione(<?= $id ?>)">
                    <i class="bi bi-trash"></i> Elimina Riunione
                </button>
            </div>

            <div class="card p-4 prodotto-card text-white">
                <h5 class="mb-3">Attività / Note</h5>

                <ul class="list-group list-group-flush" id="task-list">
                    <?php foreach ($meeting['tasks'] as $index => $task): ?>
                        <li class="list-group-item bg-transparent text-white d-flex align-items-center">
                            <input type="checkbox" class="form-check-input me-2 task-checkbox" id="task_<?= $index ?>" 
                                <?= strpos($task, '[Fatto]') !== false ? 'checked' : '' ?> 
                                onchange="handleCheckboxChange(event, <?= $index ?>)">
                            <span class="task-text"><?= htmlspecialchars(str_replace('[Fatto] ', '', $task)) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <a href="index.php" class="btn btn-secondary mt-4">← Torna alla lista</a>
        <?php else: ?>
            <div class="alert alert-danger">Riunione non trovata.</div>
            <a href="index.php" class="btn btn-secondary">← Torna alla lista</a>
        <?php endif; ?>
    </div>

    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Conferma Eliminazione</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Sei sicuro di voler eliminare questa riunione?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <button type="button" id="confirmDeleteButton" class="btn btn-danger">Conferma Eliminazione</button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
