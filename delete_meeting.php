<?php
// Controlla se è POST (può arrivare sia via fetch JSON sia da form)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Supporta sia fetch JSON che form HTML
    $data = file_get_contents('php://input');
    $parsed = json_decode($data, true);

    $id = $parsed['id'] ?? $_POST['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID mancante']);
        exit;
    }

    // Percorsi file
    $meetingsFile = 'meetings.json';
    $archivedFile = 'archived_meetings.json';
    $trashFile = 'trashed_meetings.json';

    // Carica tutti i file
    $meetings = json_decode(file_get_contents($meetingsFile), true) ?? [];
    $archivedMeetings = json_decode(file_get_contents($archivedFile), true) ?? [];
    $trashedMeetings = json_decode(file_get_contents($trashFile), true) ?? [];

    // 1. Se esiste nei meeting attivi → sposta negli archivi
    if (isset($meetings[$id])) {
        $archivedMeetings[$id] = $meetings[$id];
        unset($meetings[$id]);

        file_put_contents($meetingsFile, json_encode($meetings, JSON_PRETTY_PRINT));
        file_put_contents($archivedFile, json_encode($archivedMeetings, JSON_PRETTY_PRINT));

        echo json_encode(['success' => true, 'moved_to' => 'archived']);
        exit;
    }

    // 2. Se esiste negli archivi → sposta nel cestino
    if (isset($archivedMeetings[$id])) {
        $trashedMeetings[$id] = $archivedMeetings[$id];
        $trashedMeetings[$id]['deleted_at'] = date('Y-m-d');

        unset($archivedMeetings[$id]);

        file_put_contents($archivedFile, json_encode($archivedMeetings, JSON_PRETTY_PRINT));
        file_put_contents($trashFile, json_encode($trashedMeetings, JSON_PRETTY_PRINT));

        // Se arrivo da form, redireziona
        if (!empty($_POST)) {
            header('Location: archived_meetings.php?msg=eliminata');
            exit;
        }

        echo json_encode(['success' => true, 'moved_to' => 'trash']);
        exit;
    }

    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Riunione non trovata']);
    exit;
}
?>
